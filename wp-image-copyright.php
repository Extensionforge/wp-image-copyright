<?php
/**
 * WP Image Copyright
 *
 * @package       WPIMAGECOPYRIGHT
 * @version       1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:   WP Image Copyright
 * Plugin URI:    https://extensionforge.com
 * Description:   Copyright-Angaben bei Bildern, welche steuerbar über einen Shortcode, auf einer extra Seite oder bei den Bildern direkt angezeigt werden können.
 * Version:       1.0.0
 * Author:        Steve Kraft with chat GPT
 * Author URI:    https://stevekraft.de
 * License:       GPLv3 or later
 * Text Domain:   wp-image-copyright
 * Domain Path:   /languages
 */

/*
Copyright (C) [2024] [Steve D. Kraft in help with ChatGPT] (E-Mail: [direct@extensionforge.com])

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

*/

if ( ! defined('ABSPATH')) {
    die;
}

// Include required files
require_once plugin_dir_path(__FILE__) . 'includes/wpic-functions.php';
require_once plugin_dir_path(__FILE__) . 'includes/wpic-admin.php';
require_once plugin_dir_path(__FILE__) . 'includes/wpic-shortcode.php';

// Register activation and deactivation hooks
register_activation_hook(__FILE__, 'wpic_activate');
register_deactivation_hook(__FILE__, 'wpic_deactivate');

// Activation callback
function wpic_activate() {
    // Activation code here
}

// Deactivation callback
function wpic_deactivate() {
    // Deactivation code here
}

// Enqueue scripts and styles for admin
add_action('admin_enqueue_scripts', 'wpic_enqueue_admin_scripts');
function wpic_enqueue_admin_scripts() {
    wp_enqueue_script('wpic-js', plugin_dir_url(__FILE__) . 'js/wpic.js', array('jquery'), '1.0.0', true);
    wp_enqueue_style('wpic-styles', plugin_dir_url(__FILE__) . 'css/wpic-styles.css');
}

// Enqueue scripts and styles for frontend
add_action('wp_enqueue_scripts', 'wpic_enqueue_frontend_assets');
function wpic_enqueue_frontend_assets() {
    wp_enqueue_style('wpic-frontend-styles', plugin_dir_url(__FILE__) . 'css/wpic-frontend-styles.css');
    wp_enqueue_script('wpic-frontend-js', plugin_dir_url(__FILE__) . 'js/wpic-frontend.js', array('jquery'), '1.0.0', true);
}

// Add settings link on plugin page
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'wpic_add_plugin_page_settings_link');
function wpic_add_plugin_page_settings_link($links) {
    $links[] = '<a href="' . esc_url(get_admin_url(null, 'admin.php?page=wp-image-copyright')) . '">' . __('Settings', 'wp-image-copyright') . '</a>';
    return $links;
}

// Enqueue custom admin styles and scripts
add_action('admin_enqueue_scripts', 'wpic_enqueue_custom_admin_assets');
function wpic_enqueue_custom_admin_assets() {
    wp_enqueue_style('wpic-custom-admin-styles', plugin_dir_url(__FILE__) . 'css/wpic-custom-admin-styles.css');
    wp_enqueue_script('wpic-custom-admin-scripts', plugin_dir_url(__FILE__) . 'js/wpic-custom-admin-scripts.js', array('jquery'), null, true);
}



// Load plugin textdomain
add_action('plugins_loaded', 'wpic_load_textdomain');
function wpic_load_textdomain() {
    load_plugin_textdomain('wp-image-copyright', false, dirname(plugin_basename(__FILE__)) . '/languages');
}

// Uninstall callback
function wpic_uninstall() {
    if (get_option('wpic_delete_data')) {
        // Delete all metadata created by the plugin
        global $wpdb;
        $wpdb->query("DELETE FROM $wpdb->postmeta WHERE meta_key IN ('wpic_name', 'wpic_url', 'wpic_author', 'wpic_author_url', 'wpic_portal', 'wpic_portal_url', 'wpic_found_pages')");
        
        // Delete plugin options
        delete_option('wpic_showpage');
        delete_option('wpic_view');
        delete_option('wpic_delete_data');
    }
}
register_uninstall_hook(__FILE__, 'wpic_uninstall');
?>
