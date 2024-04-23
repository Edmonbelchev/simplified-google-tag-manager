<?php
$script         = get_option('simplified_google_tag_manager_script');
$iframe_snippet = get_option('simplified_google_tag_manager_iframe');
?>

<div class="simplified-google-tag-wrapper">
    <?php require_once WP_PLUGIN_DIR . '/simplified-google-tag-manager/components/head.php'; ?>

    <h2>Settings</h2>

    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <input type="hidden" name="action" value="simplified_google_tag_manager_save_settings">
        <?php wp_nonce_field('simplified_google_tag_manager_settings'); ?>

        <div class="input-group">
            <label for="simplified_google_tag_manager_script">Google Tag Manager Script:</label>
            <textarea name="simplified_google_tag_manager_script" rows="10" cols="50"><?php echo esc_textarea($script) ?></textarea>
        </div>

        <div class="input-group">
            <label for="simplified_google_tag_manager_iframe">Iframe Snippet:</label>
            <textarea name="simplified_google_tag_manager_iframe" rows="10" cols="50"><?php echo esc_textarea($iframe_snippet) ?></textarea>
        </div>

        <?php submit_button(); ?>
    </form>
</div>