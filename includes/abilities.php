<?php

/**
 * WordPress Abilities API (WordPress 6.9+) integration.
 *
 * Lets AI agents and other tools discover the supported calendars and format tags, and generate
 * newsletter-ready HTML for upcoming events, the same as the Event Calendar Newsletter admin page.
 *
 * @since [version]
 */
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( ! function_exists( 'ecn_abilities_user_can_manage' ) ) {
    /**
     * Whether the current user can use the Event Calendar Newsletter admin features.
     *
     * Uses the same capability as the Event Calendar Newsletter admin page.
     *
     * @since [version]
     *
     * @return bool
     */
    function ecn_abilities_user_can_manage() {
        return current_user_can( apply_filters( 'ecn_admin_capability', 'add_users' ) );
    }

    /**
     * Load what the calendar feeds need to detect their plugins outside of wp-admin, such as in REST requests.
     *
     * @since [version]
     */
    function ecn_abilities_prepare_feeds() {
        if ( ! function_exists( 'is_plugin_active' ) ) {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
    }

    /**
     * Available designs, keyed by the value of the "design" setting.
     *
     * @since [version]
     *
     * @return array<string,string>
     */
    function ecn_abilities_get_designs() {
        /*
         * Filters the designs available for the "design" setting.
         *
         * @since [version]
         *
         * @param array<string,string> $designs Design value => label.
         */
        return apply_filters( 'ecn_available_designs', [
            'default' => __( 'Default', 'event-calendar-newsletter' ),
            'compact' => __( 'Minimal/Compact', 'event-calendar-newsletter' ),
            'custom' => __( 'Custom (uses the "format" setting)', 'event-calendar-newsletter' ),
        ] );
    }

    /**
     * Available ways to group events, keyed by the value of the "group_events" setting.
     *
     * @since [version]
     *
     * @return array<string,string>
     */
    function ecn_abilities_get_group_events_options() {
        /*
         * Filters the values available for the "group_events" setting.
         *
         * @since [version]
         *
         * @param array<string,string> $options Group events value => label.
         */
        return apply_filters( 'ecn_available_group_events', [
            'normal' => __( 'None (Show events in order)', 'event-calendar-newsletter' ),
        ] );
    }

    /**
     * JSON schema properties for the settings used to fetch and format events.
     *
     * These are the same fields as the form on the Event Calendar Newsletter admin page. Calendar-specific
     * filters (such as categories) are passed through as additional properties.
     *
     * @since [version]
     *
     * @return array
     */
    function ecn_abilities_get_settings_properties() {
        /*
         * Filters the JSON schema properties of the newsletter settings accepted by the abilities.
         *
         * @since [version]
         *
         * @param array $properties JSON schema properties.
         */
        return apply_filters( 'ecn_abilities_settings_properties', [
            'event_calendar' => [
                'type' => 'string',
                'description' => __( 'Identifier of the calendar plugin to fetch events from, from the event-calendar-newsletter/get-calendars ability (for example "the-events-calendar").', 'event-calendar-newsletter' ),
            ],
            'events_future_in_days' => [
                'type' => 'integer',
                'description' => __( 'How many days of upcoming events to include, starting today. The admin page offers 7, 14 and 21 (weeks) and 30 to 360 (months).', 'event-calendar-newsletter' ),
                'default' => 30,
            ],
            'design' => [
                'type' => 'string',
                'description' => __( 'Design for each event, from the designs returned by event-calendar-newsletter/get-format-options. Use "custom" to use the "format" setting. Defaults to "custom" when a format is given, otherwise "default".', 'event-calendar-newsletter' ),
            ],
            'format' => [
                'type' => 'string',
                'description' => __( 'HTML for each event when the design is "custom", with tags such as {title}, {start_date} and {link}, and conditional blocks such as {if_end_time}...{/if_end_time}. See event-calendar-newsletter/get-format-options.', 'event-calendar-newsletter' ),
            ],
            'group_events' => [
                'type' => 'string',
                'description' => __( 'How to group events, from the group_events options returned by event-calendar-newsletter/get-format-options.', 'event-calendar-newsletter' ),
                'default' => 'normal',
            ],
        ] );
    }

    /**
     * Fill in defaults for settings passed to an ability.
     *
     * @since [version]
     *
     * @param array $settings settings passed to the ability
     *
     * @return array
     */
    function ecn_abilities_prepare_settings( $settings ) {
        $settings = is_array( $settings ) ? $settings : [];

        if ( empty( $settings['design'] ) ) {
            $settings['design'] = ( isset( $settings['format'] ) and '' !== $settings['format'] ) ? 'custom' : 'default';
        }

        return wp_parse_args( $settings, [
            'events_future_in_days' => 30,
            'format' => '',
            'group_events' => 'normal',
        ] );
    }

    /**
     * Register the Event Calendar Newsletter ability category.
     *
     * @since [version]
     */
    function ecn_register_ability_category() {
        wp_register_ability_category( 'event-calendar-newsletter', [
            'label' => __( 'Event Calendar Newsletter', 'event-calendar-newsletter' ),
            'description' => __( 'Turn upcoming events from WordPress calendar plugins into newsletter-ready HTML.', 'event-calendar-newsletter' ),
        ] );
    }
    add_action( 'wp_abilities_api_categories_init', 'ecn_register_ability_category' );

    /**
     * Register the Event Calendar Newsletter abilities.
     *
     * @since [version]
     */
    function ecn_register_abilities() {
        wp_register_ability( 'event-calendar-newsletter/get-calendars', [
            'label' => __( 'Get supported calendars', 'event-calendar-newsletter' ),
            'description' => __( 'Lists the active calendar plugins that events can be fetched from, with the identifier to use as the "event_calendar" setting.', 'event-calendar-newsletter' ),
            'category' => 'event-calendar-newsletter',
            'output_schema' => [
                'type' => 'object',
                'properties' => [
                    'calendars' => [
                        'type' => 'array',
                        'items' => [
                            'type' => 'object',
                            'properties' => [
                                'id' => [ 'type' => 'string' ],
                                'name' => [ 'type' => 'string' ],
                            ],
                        ],
                    ],
                    'unsupported_calendars' => [
                        'type' => 'array',
                        'description' => __( 'Active calendar plugins that need Event Calendar Newsletter Pro.', 'event-calendar-newsletter' ),
                        'items' => [ 'type' => 'string' ],
                    ],
                ],
            ],
            'execute_callback' => 'ecn_ability_get_calendars',
            'permission_callback' => 'ecn_abilities_user_can_manage',
            'meta' => [
                'show_in_rest' => true,
                'annotations' => [
                    'readonly' => true,
                    'destructive' => false,
                    'idempotent' => true,
                ],
            ],
        ] );

        wp_register_ability( 'event-calendar-newsletter/get-format-options', [
            'label' => __( 'Get format options', 'event-calendar-newsletter' ),
            'description' => __( 'Lists the format tags available for a calendar, the designs, the ways to group events and the default format, for writing the "format" setting.', 'event-calendar-newsletter' ),
            'category' => 'event-calendar-newsletter',
            'input_schema' => [
                'type' => 'object',
                'properties' => [
                    'event_calendar' => [
                        'type' => 'string',
                        'description' => __( 'Calendar identifier from event-calendar-newsletter/get-calendars. Leave out to list every tag.', 'event-calendar-newsletter' ),
                    ],
                ],
                'default' => [],
            ],
            'output_schema' => [
                'type' => 'object',
                'properties' => [
                    'tags' => [
                        'type' => 'object',
                        'description' => __( 'Format tag => description. Use a tag as {tag}.', 'event-calendar-newsletter' ),
                        'additionalProperties' => [ 'type' => 'string' ],
                    ],
                    'conditional_tags' => [ 'type' => 'string' ],
                    'date_formats' => [ 'type' => 'string' ],
                    'designs' => [
                        'type' => 'object',
                        'additionalProperties' => [ 'type' => 'string' ],
                    ],
                    'group_events' => [
                        'type' => 'object',
                        'additionalProperties' => [ 'type' => 'string' ],
                    ],
                    'default_format' => [ 'type' => 'string' ],
                ],
            ],
            'execute_callback' => 'ecn_ability_get_format_options',
            'permission_callback' => 'ecn_abilities_user_can_manage',
            'meta' => [
                'show_in_rest' => true,
                'annotations' => [
                    'readonly' => true,
                    'destructive' => false,
                    'idempotent' => true,
                ],
            ],
        ] );

        wp_register_ability( 'event-calendar-newsletter/generate-newsletter', [
            'label' => __( 'Generate newsletter events', 'event-calendar-newsletter' ),
            'description' => __( 'Fetches upcoming events from a calendar and returns them formatted as HTML to paste into a newsletter. Does not change any saved settings.', 'event-calendar-newsletter' ),
            'category' => 'event-calendar-newsletter',
            'input_schema' => [
                'type' => 'object',
                'properties' => ecn_abilities_get_settings_properties(),
                'required' => [ 'event_calendar' ],
                'additionalProperties' => true,
            ],
            'output_schema' => [
                'type' => 'object',
                'properties' => [
                    'html' => [ 'type' => 'string' ],
                    'event_count' => [ 'type' => 'integer' ],
                ],
            ],
            'execute_callback' => 'ecn_ability_generate_newsletter',
            'permission_callback' => 'ecn_abilities_user_can_manage',
            'meta' => [
                'show_in_rest' => true,
                'annotations' => [
                    'readonly' => true,
                    'destructive' => false,
                    'idempotent' => true,
                ],
            ],
        ] );
    }
    add_action( 'wp_abilities_api_init', 'ecn_register_abilities' );

    /**
     * Execute the event-calendar-newsletter/get-calendars ability.
     *
     * @since [version]
     *
     * @return array
     */
    function ecn_ability_get_calendars() {
        ecn_abilities_prepare_feeds();

        $calendars = [];

        foreach ( ECNCalendarFeedFactory::get_available_calendar_feeds() as $feed ) {
            $calendars[] = [
                'id' => $feed->get_identifier(),
                'name' => $feed->get_description(),
            ];
        }

        return [
            'calendars' => $calendars,
            'unsupported_calendars' => class_exists( 'ECNPro' ) ? [] : ecn_available_pro_calendars(),
        ];
    }

    /**
     * Execute the event-calendar-newsletter/get-format-options ability.
     *
     * @since [version]
     *
     * @param array|null $input ability input
     *
     * @return array
     */
    function ecn_ability_get_format_options( $input = null ) {
        ecn_abilities_prepare_feeds();

        $calendar = ( is_array( $input ) and ! empty( $input['event_calendar'] ) ) ? $input['event_calendar'] : false;

        return [
            'tags' => ECNCalendarEvent::get_available_format_tags( $calendar ),
            'conditional_tags' => __( 'Wrap content in {if_TAG}...{/if_TAG} to show it only when the event has a value for TAG, or {if_not_TAG}...{/if_not_TAG} when it does not, for example {if_location_name} at {location_name}{/if_location_name}. {if_end_time}, {if_end_date}, {if_all_day} and {if_not_all_day} are also available.', 'event-calendar-newsletter' ),
            'date_formats' => __( 'Add a PHP date format after a pipe to change how a date or time shows, for example {start_date|F j, Y} or {start_time|g:i a}. Works with start_date, start_time, end_date and end_time.', 'event-calendar-newsletter' ),
            'designs' => ecn_abilities_get_designs(),
            'group_events' => ecn_abilities_get_group_events_options(),
            'default_format' => "<h2>{title}</h2>\n{if_event_image}<p>{event_image}</p>{/if_event_image}\n<p>{start_date} {if_not_all_day}@ {start_time}{if_end_time} to {end_time}{/if_end_time}{/if_not_all_day}{if_location_name} at {location_name}{/if_location_name}</p>\n<p>{description}</p>\n<p>{link}</p>",
        ];
    }

    /**
     * Execute the event-calendar-newsletter/generate-newsletter ability.
     *
     * @since [version]
     *
     * @param array $input newsletter settings
     *
     * @return array|WP_Error
     */
    function ecn_ability_generate_newsletter( $input ) {
        global $ecn_admin_class;

        ecn_abilities_prepare_feeds();

        $data = ecn_abilities_prepare_settings( $input );
        $available = wp_list_pluck( ecn_ability_get_calendars()['calendars'], 'id' );

        if ( ! in_array( $data['event_calendar'], $available, true ) ) {
            return new WP_Error(
                'ecn_invalid_calendar',
                /* translators: 1: calendar identifier, 2: comma-separated list of available calendar identifiers */
                sprintf( __( 'The calendar "%1$s" is not available. Available calendars: %2$s.', 'event-calendar-newsletter' ), $data['event_calendar'], implode( ', ', $available ) ),
                [ 'status' => 400 ]
            );
        }

        try {
            $data['events'] = $ecn_admin_class->get_events( $data );
            $html = $ecn_admin_class->get_output_from_data( $data );
        } catch ( Exception $e ) {
            return new WP_Error( 'ecn_generate_failed', $e->getMessage(), [ 'status' => 500 ] );
        }

        return [
            'html' => trim( $html ),
            'event_count' => count( $data['events'] ),
        ];
    }
}
