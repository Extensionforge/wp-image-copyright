<?php
// Create admin menu for the plugin
add_action('admin_menu', 'wpic_create_menu');
function wpic_create_menu() {
    add_menu_page(__('WP Image Copyright - Dashboard', 'wp-image-copyright'), __('WP Image Copyright', 'wp-image-copyright'), 'manage_options', 'wp-image-copyright', 'wpic_settings_page');
    add_submenu_page('wp-image-copyright', __('Einstellungen', 'wp-image-copyright'), __('Einstellungen', 'wp-image-copyright'), 'manage_options', 'wp-image-copyright-settings', 'wpic_settings_subpage');
}

// Admin toolbar
function wpic_admin_toolbar() {
    $screen = get_current_screen();
    if ($screen->id != 'toplevel_page_wp-image-copyright' && $screen->id != 'wp-image-copyright_page_wp-image-copyright-settings') {
        return;
    }
    ?>
    <div id="wpic-admin-toolbar" class="wpic-admin-toolbar">
        <div class="wpic-admin-toolbar-inner">
            <div class="wpic-toolbar-left">
                <div class="wpic-toolbar-logo-left">
                    <img src="<?php echo plugin_dir_url(__FILE__); ?>../images/wpic_logo_100.webp" alt="WP Image Copyright Logo">
                </div>
                <div class="wpic-toolbar-menu">
                    <a href="<?php echo admin_url('admin.php?page=wp-image-copyright'); ?>"><?php _e('Dashboard', 'wp-image-copyright'); ?></a>
                    <a href="<?php echo admin_url('admin.php?page=wp-image-copyright-settings'); ?>"><?php _e('Settings', 'wp-image-copyright'); ?></a>
                </div>
            </div>
            <div class="wpic-toolbar-logo-right">
                <img src="<?php echo plugin_dir_url(__FILE__); ?>../images/extensionforge_logo_b_400w.webp" alt="ExtensionForge Logo">
            </div>
        </div>
    </div>
    <div id="wpic-header-bar">
        <h1><?php _e('WP Image Copyright - Dashboard', 'wp-image-copyright'); ?></h1>
    </div>
    <?php
}
add_action('in_admin_header', 'wpic_admin_toolbar');

// Admin settings page
function wpic_settings_page() {
    ?>
    <div class="wrap wpic-dashboard">
        <form method="post" action="options.php" id="wpic_main_settings">
            <?php
            settings_fields('wpic-settings-group');
            do_settings_sections('wpic-settings-group');
            wp_nonce_field('wpic_save_settings', 'wpic_nonce');
            ?>
            <table class="form-table wpic-form-table">
                <tr valign="top" class="wpic-field">
                    <th scope="row" class="wpic-label"><label for="wpic_showpage_checkbox"><?php _e('Bildanzeige im Shortcode', 'wp-image-copyright'); ?></label></th>
                    <td class="wpic-input"><input type="checkbox" name="wpic_showpage" value="1" <?php checked(1, get_option('wpic_showpage'), true); ?> id="wpic_showpage_checkbox"/>
                        <p class="description"><?php _e('Möchten Sie einen Scan der Seite, wo Bilder verwendet werden, veranlassen?', 'wp-image-copyright'); ?></p>
                    </td>
                </tr>
                <tr valign="top" class="wpic-field">
                    <th scope="row" class="wpic-label"><label for="wpic_view"><?php _e('Shortcode Ansicht', 'wp-image-copyright'); ?></label></th>
                    <td class="wpic-input">
                        <input type="radio" name="wpic_view" value="list" <?php checked('list', get_option('wpic_view'), true); ?>> <?php _e('Listen Ansicht', 'wp-image-copyright'); ?><br>
                        <input type="radio" name="wpic_view" value="columns" <?php checked('columns', get_option('wpic_view'), true); ?>> <?php _e('Spalten Ansicht', 'wp-image-copyright'); ?>
                    </td>
                </tr>
            </table>

            <h2 class="wpic-label"><?php _e('Shortcode', 'wp-image-copyright'); ?></h2>
            <p class="wpic-description"><?php _e('Verwenden Sie den folgenden Shortcode, um die Bildrechte anzuzeigen:', 'wp-image-copyright'); ?></p>
            <code class="wpic-shortcode">
                <?php
                $shortcode = '[wpimgcopy';
                if (get_option('wpic_showpage')) {
                    $shortcode .= ' showpage="true"';
                }
                if (get_option('wpic_view') == 'columns') {
                    $shortcode .= ' columns="true"';
                }
                $shortcode .= ']';
                echo esc_html($shortcode);
                ?>
            </code>

            <?php submit_button(); ?>
        </form>

        <?php if (get_option('wpic_showpage')) : ?>
            <form method="post" id="wpic_scan_form">
                <?php wp_nonce_field('wpic_scan_action', 'wpic_scan_nonce'); ?>
                <input type="hidden" name="wpic_scan_pages" value="1">
                <?php submit_button(__('Seiten scannen', 'wp-image-copyright')); ?>
            </form>
            <?php if (isset($_POST['wpic_scan_pages']) && check_admin_referer('wpic_scan_action', 'wpic_scan_nonce')): ?>
                <p class="wpic-success"><?php _e('Seiten wurden erfolgreich gescannt', 'wp-image-copyright'); ?></p>
            <?php endif; ?>
            <form method="post">
                <?php wp_nonce_field('wpic_delete_scan_action', 'wpic_delete_scan_nonce'); ?>
                <input type="hidden" name="wpic_delete_scanned_data" value="1">
                <?php submit_button(__('Daten wieder entfernen', 'wp-image-copyright')); ?>
            </form>
        <?php endif; ?>
    </div>
    <?php
}

// Settings subpage for deleting data
function wpic_settings_subpage() {
    ?>
    <div class="wrap wpic-settings">
        <h1><?php _e('Einstellungen', 'wp-image-copyright'); ?></h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('wpic-settings-group-delete');
            do_settings_sections('wpic-settings-group-delete');
            wp_nonce_field('wpic_delete_settings', 'wpic_delete_nonce');
            ?>
            <table class="form-table wpic-form-table">
                <tr valign="top" class="wpic-field">
                    <th scope="row" class="wpic-label"><label for="wpic_delete_data"><?php _e('Daten Entfernen', 'wp-image-copyright'); ?></label></th>
                    <td class="wpic-input"><input type="checkbox" name="wpic_delete_data" value="1" <?php checked(1, get_option('wpic_delete_data'), true); ?> id="wpic_delete_data"/>
                        <p class="description"><?php _e('Sollen alle Daten, welche dieses Plugin betreffen mit entfernt werden? <br>Aktivieren, wenn ja.', 'wp-image-copyright'); ?></p>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Register settings
add_action('admin_init', 'wpic_register_settings');
function wpic_register_settings() {
    register_setting('wpic-settings-group', 'wpic_showpage');
    register_setting('wpic-settings-group', 'wpic_view');
}

add_action('admin_init', 'wpic_register_delete_settings');
function wpic_register_delete_settings() {
    register_setting('wpic-settings-group-delete', 'wpic_delete_data');
}

// Handle scan and deletion of scanned data
if (isset($_POST['wpic_scan_pages'])) {
    add_action('admin_init', 'wpic_scan_pages');
}
if (isset($_POST['wpic_delete_scanned_data'])) {
    add_action('admin_init', 'wpic_delete_scanned_data');
}

function wpic_scan_pages() {
    if (!current_user_can('manage_options')) {
        return;
    }

    if (!check_admin_referer('wpic_scan_action', 'wpic_scan_nonce')) {
        return;
    }

    $attachments = get_posts(array(
        'post_type' => 'attachment',
        'post_status' => 'inherit',
        'posts_per_page' => -1,
    ));

    foreach ($attachments as $attachment) {
        $post_id = $attachment->ID;
        $attachment_url = wp_get_attachment_url($post_id);

        $pages = get_posts(array(
            'post_type' => 'page',
            'post_status' => 'publish',
            'posts_per_page' => -1,
        ));

        $found_pages = array();

        foreach ($pages as $page) {
            if (strpos($page->post_content, $attachment_url) !== false) {
                $found_pages[] = get_permalink($page->ID);
            }
        }

        update_post_meta($post_id, 'wpic_found_pages', $found_pages);
    }
}

function wpic_delete_scanned_data() {
    if (!current_user_can('manage_options')) {
        return;
    }

    if (!check_admin_referer('wpic_delete_scan_action', 'wpic_delete_scan_nonce')) {
        return;
    }

    $attachments = get_posts(array(
        'post_type' => 'attachment',
        'post_status' => 'inherit',
        'posts_per_page' => -1,
    ));

    foreach ($attachments as $attachment) {
        delete_post_meta($attachment->ID, 'wpic_found_pages');
    }
}
?>
