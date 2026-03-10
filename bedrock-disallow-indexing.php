<?php

/*
Plugin Name:  Disallow Indexing
Plugin URI:   https://roots.io/bedrock/
Description:  Disallow indexing of your site on non-production environments.
Version:      2.0.1
Author:       Roots
Author URI:   https://roots.io/
Text Domain:  roots
License:      MIT License
*/

if (! defined('DISALLOW_INDEXING') || DISALLOW_INDEXING !== true) {
    return;
}

add_action('pre_option_blog_public', '__return_zero');

add_action('admin_init', function () {
    if (! apply_filters('roots/bedrock/disallow_indexing_admin_notice', true)) {
        return;
    }

    add_action('admin_notices', function () {
        $env = defined('WP_ENV') && WP_ENV ? WP_ENV : null;

        if (! $env) {
            $env = wp_get_environment_type();
        }

        $env = apply_filters('roots/bedrock/disallow_indexing_environment_type', $env);

        if (! $env) {
            printf(
                '<div class="notice notice-warning"><p>%s</p></div>',
                sprintf(
                    /* translators: %s: Bedrock prefix. */
                    __('%s Search engine indexing has been discouraged.', 'roots'),
                    '<strong>Bedrock:</strong>'
                )
            );

            return;
        }

        printf(
            '<div class="notice notice-warning"><p>%s</p></div>',
            sprintf(
                /* translators: 1: Bedrock prefix, 2: Environment type. */
                __('%1$s Search engine indexing has been discouraged because the current environment is %2$s.', 'roots'),
                '<strong>Bedrock:</strong>',
                '<code>'.esc_html($env).'</code>'
            )
        );
    });
});
