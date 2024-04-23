<?php
/*
Plugin Name: Simplified Google Tag Manager
Description: A simple plugin to add Google Tag Manager script and iframe snippet to your website.
Version: 1.0.0
Author: Edmon Belchev (Tech Ed)
Author URI: https://github.com/Edmonbelchev
GitHub Plugin URI: https://github.com/Edmonbelchev/simplified-google-tag-manager
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Function to include the required page content
function simplified_google_tag_manager_render_page()
{
    // Check if nonce exists for the current URL
    $nonce_exists = isset($_REQUEST['_wpnonce']) ? true : false;

    // Check nonce validity if needed
    if ($nonce_exists) {
        $nonce_valid = wp_verify_nonce('_wpnonce', 'simplified-google-tag-manager');
        if (!$nonce_valid) {
            // Nonce verification failed, handle the error
            die('Nonce verification failed!');
        }
    }

    $current_page = isset($_GET['page'])
        ? $_GET['page']
        : 'simplified-google-tag-manager';

    // Verify nonce
    $nonce = wp_create_nonce('simplified-google-tag-manager');

    if (!wp_verify_nonce($nonce, 'simplified-google-tag-manager')) {
        // Nonce verification failed, handle the error
        die('Nonce verification failed!');
    } else {
        switch ($current_page) {
            case 'simplified-google-tag-manager-instructions':
                include('pages/instructions.php');
                break;
            case 'simplified-google-tag-manager-donation':
                include('pages/support.php');
                break;
            case 'simplified-google-tag-manager':
            default:
                include('pages/form.php');
                break;
        }
    }
}

// Function to handle form submissions and store values
function simplified_google_tag_manager_save_settings()
{
    if (isset($_POST['submit'])) {
        if (!current_user_can('manage_options')) {
            wp_die(esc_html_e("You do not have sufficient permissions to access this page."));
        }

        check_admin_referer('simplified_google_tag_manager_settings');

        // Save Google Tag Manager script
        if (isset($_POST['simplified_google_tag_manager_script'])) {
            $script = stripslashes_deep(sanitize_option('simplified_google_tag_manager_script', $_POST['simplified_google_tag_manager_script'], 'nohtml'));
            update_option('simplified_google_tag_manager_script', $script);
        }

        // Save iframe snippet
        if (isset($_POST['simplified_google_tag_manager_iframe'])) {
            $iframe_snippet = stripslashes_deep(sanitize_option('simplified_google_tag_manager_iframe', $_POST['simplified_google_tag_manager_iframe'], 'nohtml'));
            update_option('simplified_google_tag_manager_iframe', $iframe_snippet);
        }

        // Redirect back to settings page with success message
        wp_redirect(add_query_arg(array('page' => 'simplified-google-tag-manager', 'updated' => 'true'), admin_url('admin.php?page=simplified-google-tag-manager')));
        exit;
    }
}

// Function to add options page
function simplified_google_tag_manager_options_page()
{
    add_menu_page(
        'Simple Google Tag Manager',
        'Google Tag Manager',
        'manage_options', // This capability allows only users with 'manage_options' capability to access the page
        'simplified-google-tag-manager',
        'simplified_google_tag_manager_render_page',
        'dashicons-google' // Change the icon as needed
    );

    // Add subpages
    add_submenu_page(
        'simplified-google-tag-manager',
        'Settings',
        'Settings',
        'manage_options', // This capability allows only users with 'manage_options' capability to access the page
        'simplified-google-tag-manager',
        'simplified_google_tag_manager_render_page'
    );

    add_submenu_page(
        'simplified-google-tag-manager',
        'Instructions',
        'Instructions',
        'manage_options', // This capability allows only users with 'manage_options' capability to access the page
        'simplified-google-tag-manager-instructions',
        'simplified_google_tag_manager_render_page'
    );

    add_submenu_page(
        'simplified-google-tag-manager',
        'Support',
        'Support',
        'manage_options', // This capability allows only users with 'manage_options' capability to access the page
        'simplified-google-tag-manager-donation',
        'simplified_google_tag_manager_render_page'
    );
}

// Function to register settings
function simplified_google_tag_manager_settings()
{
    register_setting('simplified_google_tag_manager_options', 'simplified_google_tag_manager_script');
    register_setting('simplified_google_tag_manager_options', 'simplified_google_tag_manager_iframe');
}

// Hook functions
add_action('admin_menu', 'simplified_google_tag_manager_options_page');
add_action('admin_init', 'simplified_google_tag_manager_settings');
add_action('admin_post_simplified_google_tag_manager_save_settings', 'simplified_google_tag_manager_save_settings');

// Function to output Google Tag Manager script
function simplified_google_tag_manager_output_script()
{
    $script = get_option('simplified_google_tag_manager_script');

    if (!empty($script)) {
        echo '<script>' . wp_kses($script, ['']) . '</script>';
    }
}

add_action('wp_head', 'simplified_google_tag_manager_output_script');

// Function to output iframe snippet in footer
function simplified_google_tag_manager_output_iframe()
{
    $iframe_snippet = get_option('simplified_google_tag_manager_iframe');

    if (!empty($iframe_snippet)) {
        echo '<noscript>' . wp_kses($iframe_snippet, array(
            'iframe'      => array(
                'src'  => array(),
                'width' => array(),
                'height' => array(),
                'style' => array()
            )
        )) . '</noscript>';
    }
}

add_action('wp_footer', 'simplified_google_tag_manager_output_iframe');

function simplified_google_tag_manager_enqueue_styles()
{
    wp_enqueue_style('simplified-google-tag-manager-styles', plugins_url('assets/style.css', __FILE__), array(), '1.0', 'all');
}

add_action('admin_enqueue_scripts', 'simplified_google_tag_manager_enqueue_styles');
