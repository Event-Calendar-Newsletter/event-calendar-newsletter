# AGENTS.md

Guidance for coding agents working in this repository, and for AI agents using Event Calendar Newsletter on a WordPress site.

## What the plugin does

Event Calendar Newsletter (free, on WordPress.org) reads upcoming events from a supported calendar plugin and turns them into HTML to paste into a newsletter. The admin page is **Event Calendar Newsletter** in wp-admin (`admin.php?page=eventcalendarnewsletter`). It has no shortcodes or blocks; output is generated on the admin page or through the abilities below.

Event Calendar Newsletter Pro (private repo `Event-Calendar-Newsletter/event-calendar-newsletter-pro`) bundles this repository as `core/` and adds more calendars, Saved Templates (RSS feeds and email tags for Mailchimp, MailPoet, Newsletter and FluentCRM), and more designs and filters.

## Using the plugin from an AI agent (WordPress Abilities API)

On WordPress 6.9+ the plugin registers abilities in the `event-calendar-newsletter` category ([`includes/abilities.php`](includes/abilities.php)). They are available to PHP (`wp_get_ability()`), to the REST API under `/wp-json/wp-abilities/v1/abilities`, and to MCP clients through the WordPress MCP Adapter. They need the same capability as the admin page (`add_users`, or `ecn_admin` once roles are saved on the Settings page; filtered by `ecn_admin_capability`).

| Ability | What it does |
| --- | --- |
| `event-calendar-newsletter/get-calendars` | Lists active supported calendars and their identifiers. |
| `event-calendar-newsletter/get-format-options` | Lists the format tags for a calendar, designs, grouping options and the default format. |
| `event-calendar-newsletter/generate-newsletter` | Returns newsletter HTML and the event count for the given settings. Read-only; does not change saved settings. |

All three are read-only, so over REST they are called with `GET` and `input` as query parameters, for example:

```
GET /wp-json/wp-abilities/v1/abilities/event-calendar-newsletter/generate-newsletter/run?input[event_calendar]=the-events-calendar&input[events_future_in_days]=14&input[design]=compact
```

Typical flow: call `get-calendars`, then `get-format-options` for that calendar if writing a custom format, then `generate-newsletter`.

### Settings

| Setting | Values |
| --- | --- |
| `event_calendar` | Required. Identifier from `get-calendars`. |
| `events_future_in_days` | Days of upcoming events from today (default 30). |
| `design` | `default`, `compact` or `custom` (Pro adds `table`). Defaults to `custom` when `format` is set. |
| `format` | HTML per event with tags like `{title}`, `{start_date}`, `{link}`; `{start_date\|F j}` for a date format; `{if_location_name}...{/if_location_name}` and `{if_not_TAG}` for conditionals. |
| `group_events` | `normal` (Pro adds `day` and `month`). |

Calendar-specific filters are passed as extra settings with the same names as the admin form fields.

### Supported calendars

Free: The Events Calendar (`the-events-calendar`), Events Manager (`events-manager`), Event Organiser (`event-organiser`), All-in-One Event Calendar (`all-in-one-event-calendar`), Simple Calendar (`google-calendar-events`), Ajax Event Calendar (`ajax-calendar`). Other calendars, listed in `ecn_available_pro_calendars()`, need Pro; `get-calendars` returns any that are active as `unsupported_calendars`.

## Code layout

- `event-calendar-newsletter.php`: plugin bootstrap.
- `includes/ecnadmin.class.php`: admin page, AJAX handlers, `get_events()` and `get_output_from_data()` (the output pipeline and its filters).
- `includes/ecncalendarevent.class.php`: an event and the format tag replacement; `get_available_format_tags()`.
- `includes/ecncalendarfeed*.class.php`: one class per calendar; register new ones in `ECNCalendarFeedFactory` (Pro uses the `ecn_available_plugins` filter).
- `includes/output_formats/`: built-in designs, applied through the `ecn_output_format` filter.
- `includes/abilities.php`: Abilities API integration. Pro extends it through the `ecn_abilities_settings_properties`, `ecn_available_designs` and `ecn_available_group_events` filters.

## Rules for changes

- No Pro-only code or upsell-gated features in this plugin (WordPress.org guidelines). Pro adds features through hooks.
- Changes here reach Pro only when Pro's `core/` is synced; Pro code must not assume new core functions exist without checking.
- New and changed docblocks use `@since [version]`; release tooling fills in the version. Do not bump versions.
- Add a changelog entry for user-facing changes as a YAML file in `.changelogs/` (do not edit `CHANGELOG.md`):

  ```yaml
  significance: minor # patch | minor | major
  type: added         # added | changed | fixed | removed | deprecated | security
  entry: Short user-facing summary.
  ```

  Repo-only changes (docs, tests, tooling) need no entry.
- Lint with `vendor/bin/php-cs-fixer fix --dry-run --diff --config=.php_cs.php` after `composer install`.
- Tests are PHPUnit under `tests/` (`bin/install-wp-tests.sh` sets up the WordPress test library).
