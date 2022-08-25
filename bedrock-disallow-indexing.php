<?php
/*
Plugin Name:  Disallow Indexing
Plugin URI:   https://roots.io/bedrock/
Description:  Disallow indexing of your site on non-production environments.
Version:      2.0.0
Author:       Roots
Author URI:   https://roots.io/
Text Domain:  roots
License:      MIT License
*/

if (!defined('DISALLOW_INDEXING') || DISALLOW_INDEXING !== true) {
    return;
}

add_action('pre_option_blog_public', '__return_zero');

add_action('admin_init', function () {
    if (!apply_filters('roots/bedrock/disallow_indexing_admin_notice', true)) {
        return;
    }

    add_action('admin_notices', function () {
        if (defined('WP_ENV') && WP_ENV) {
            $wp_env = WP_ENV;
        } else if (function_exists('wp_get_environment_type')) {
            $wp_env = wp_get_environment_type();
        } else {
            $wp_env = null;
        }

        $wp_env = apply_filters('roots/bedrock/disallow_indexing_environment_type', $wp_env);
        if ($wp_env) {
            $message = sprintf(
                __('%1$s Search engine indexing has been discouraged because the current environment is %2$s.', 'roots'),
                '<strong>Bedrock:</strong>',
                '<code>'.$wp_env.'</code>'
            );
        } else {
            $message = sprintf(
                __('%1$s Search engine indexing has been discouraged.', 'roots'),
                '<strong>Bedrock:</strong>'
            );
        }

        echo "<div class='notice notice-warning'><p>{$message}</p></div>";
    });
});
