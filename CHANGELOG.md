Changelog
=========

v2.17.3 - 2026-09-02
--------------------

##### New Features

+ Adding note about EventKoi support.

##### Updates and Enhancements

+ Avoid translations of product name and deactivation reason keys.

##### Bug Fixes

+ Fixed position of deactivation survey modal on short plugins list.


v2.17.2 - 2026-08-23
--------------------

##### New Features

+ Adding note about Sugar Calendar support.


v2.17.1 - 2026-03-20
--------------------

##### Updates and Enhancements

+ Added supported calendar detection and instructions update.


v2.17.0 - 2026-02-07
--------------------

+ Adding "location names" option for The Events Calendar PRO
+ Additional error handling in case the timezone is incorrect, or there's some issue creating the datetime object vs. strtotime.
+ Fix if using UTC date/time zone.
+ Avoid overwriting the saved options ($data) array in the loop.
+ Avoid warning if replacement output is null


v2.16.2 - 2025-11-27
--------------------

+ Avoid showing marketing opt-in incorrectly


v2.16.1 - 2024-01-24
--------------------

+ Updating discount form


v2.16.0 - 2023-10-30
--------------------

+ Improved date/time support for several other calendars


v2.15.0 - 2023-08-05
--------------------

+ Improved date/time support


v2.14.1 - 2023-08-05
--------------------

+ Adding note about what Events Manager we support


v2.14.0 - 2022-10-29
--------------------

+ Fix for TinyMCE not working with editor in certain cases
+ [Dev] Adding additional parameter for The Events Calendar filter


v2.12.0 - 2021-03-08
--------------------

+ Better onboarding and updated wording when no compatible calendar is found


v2.11.0 - 2020-03-31
--------------------

+ Adding featured tag for The Events Calendar
+ Additional filters added for developer use


v2.10.0 - 2019-11-08
--------------------

+ Adding ability to format start_date, start_time, end_time and end_date inline, ie. {start_date|l, F j, Y}
+ Fix for adding multiple of the same if_ condition tags


v2.9.0 - 2019-05-07
--------------------

+ Fix for if_end_date condition not really doing anything


v2.8.0 - 2018-12-04
--------------------

+ Fix for WordPress 5.0 not showing custom editor at the right height
+ Allowing custom added fields to be used with conditional checks
+ Ensuring excerpt is an option for Events Manager
+ Fix for Event Organiser not getting location_* field data properly


v2.7.0 - 2018-12-03
--------------------

+ Additional WP filters for date/time formatting and excerpt length
+ Optional tracking to improve performance and compatibility


v2.6.1 - 2018-02-24
--------------------

+ Adding accessibility alt tag for images with The Events Calendar
+ Updating email signup form


v2.6.0 - 2018-12-03
--------------------

+ Removing non-working 'select all' button
+ Demo video for pasting the HTML
+ Help text tweaks


v2.5.5 - 2017-09-22
--------------------

+ Improved i18n handling
+ Text copy tweaks
+ Additional ecn_get_excerpt filter
+ Adds organizer details to available The Events Calendar fields


v2.5.4 - 2017-06-01
--------------------

+ Improved query performance for Events Manager


v2.5.3 - 2017-05-13
--------------------

+ Better handling for all day events in the default template
+ Additional tags for The Events Calendar


v2.5.2 - 2017-03-06
--------------------

+ Fixes issues with links in all-in-one by time.ly


v2.5.0 - 2017-01-09
--------------------

+ Adding default and compact designs
+ Adding {tags} and {tag_links} for certain calendars


v2.4.1 - 2016-08-14
--------------------

+ Compability change for older versions of PHP


v2.4.0 - 2016-08-08
--------------------

+ Adds Event Organiser support
+ Support for additional conditional statements like {if_end_time}...{/if_end_time}
+ Additional format options in dropdown
+ Refactoring codebase and additional tests


v2.3.5 - 2016-08-05
--------------------

+ Adding class/function exists checks


v2.3.4 - 2016-04-27
--------------------

+ Adding location_phone tag
+ Adding location website and phone for The Events Calendar


v2.3.3 - 2016-04-01
--------------------

+ Only including Published events in output for The Events Calendar
+ Adding manual excerpt if there is none in post_excerpt


v2.3.2 - 2016-03-28
--------------------

+ Handling for multi-day events expanded in Simple Calendar


v2.3.1 - 2016-03-11
--------------------

+ Fixes bug with Events Manager where incorrect events selected based on date range


v2.3.0 - 2016-02-27
--------------------

+ Adding Events Manager support


v2.2.2 - 2016-02-27
--------------------

+ Adds check to see if previously saved plugin is no longer available


v2.2.1 - 2016-02-26
--------------------

+ Compatibility changes for The Events Calendar


v2.2.0 - 2016-02-26
--------------------

+ Adding {categories} and {category_links} tags


v2.1.1 - 2016-02-09
--------------------

+ Fixing issue in Simple Calendar with multiple events at the same day/time


v2.1.0 - 2016-02-06
--------------------

+ Adding settings page


v2.0.3 - 2016-01-23
--------------------

+ Fixing issue with The Events Calendar not returning all events


v2.0.2 - 2016-01-21
--------------------

+ Fixing the {all_day} tag


v2.0.1 - 2016-01-20
--------------------

+ Using Tribe__Events__Query for ECN
+ Adding condition {if_all_day} and {if_not_all_day} tags


v2.0.0 - 2016-01-11
--------------------

+ Improved editing using the WordPress editor
+ Additional formatting tags for Simple Calendar


v1.9.2 - 2016-01-06
--------------------

+ Fix to fetch events from all Simple Calendar calendars


v1.9.1 - 2016-01-01
--------------------

+ Fixing timezone issue with All-in-One Event Calendar


v1.9.0 - 2015-12-31
--------------------

+ Adding initial support for All-in-One Event Calendar by Time.ly


v1.8.0 - 2015-12-23
--------------------

+ Fixing date dropdowns


v1.7.0 - 2015-12-21
--------------------

+ Removing freemius
+ Ensuring The Events Calendar events are in future


v1.6.2 - 2015-12-19
--------------------

+ Option to force fetching new events for Simple Calendar


v1.6.1 - 2015-12-14
--------------------

+ Fixing issue with Simple Calendar and past events
+ Ensuring new events fetched when generating newsletter
+ Updating Freemius to remove two menu items appearing before activation in 4.4


v1.6.0 - 2015-12-09
--------------------

+ Adding Simple Calendar support


v1.5.1 - 2015-12-06
--------------------

+ Updating version of freemius


v1.5.0 - 2015-12-06
--------------------

+ Added option for smaller time periods (1, 2 or 3 week)
+ Added freemius stats to aid in feedback


v1.4.0 - 2015-11-24
--------------------

+ German translation
+ Fixed issue with quotes in the HTML format
+ Added {link_url}


v1.3.0 - 2015-10-08
--------------------

+ Added translation support


v1.2.0 - 2015-09-21
--------------------

+ Fix formatting of free event cost
+ Added ecn_admin_capability filter to modify who has access to the events calendar screen


v1.1.0 - 2015-01-26
--------------------

+ Minor fixes


v1.0.0 - 2014-08-25
--------------------

+ Initial release
