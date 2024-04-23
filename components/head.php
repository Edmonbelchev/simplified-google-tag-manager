<?php

/**
 * Head component for the plugin.
 *
 * @package SimpleGoogleTagManager
 */

$mainUrl = admin_url('admin.php?page=');

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

// Check if the current page matches certain slugs
$is_form_page = isset($_GET['page']) && $_GET['page'] === 'simplified-google-tag-manager';
$is_instructions_page = isset($_GET['page']) && $_GET['page'] === 'simplified-google-tag-manager-instructions';
$is_support_page = isset($_GET['page']) && $_GET['page'] === 'simplified-google-tag-manager-donation';
?>

<div class="tabs-wrapper">
    <div class="nav-tab-wrapper">
        <a href="?page=simplified-google-tag-manager" class="nav-tab <?php if ($is_form_page) echo 'nav-tab-active' ?>">Settings</a>
        <a href="?page=simplified-google-tag-manager-instructions" class="nav-tab <?php if ($is_instructions_page) echo 'nav-tab-active' ?>">Instructions</a>
        <a href="?page=simplified-google-tag-manager-donation" class="nav-tab <?php if ($is_support_page) echo 'nav-tab-active' ?>">Support</a>
    </div>
</div>