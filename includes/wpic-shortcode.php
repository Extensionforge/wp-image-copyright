<?php
// Register shortcode
add_shortcode('wpimgcopy', 'wpic_shortcode');
function wpic_shortcode($atts) {
    $atts = shortcode_atts(array(
        'showpage' => false,
        'columns' => false,
    ), $atts, 'wpimgcopy');

    $args = array(
        'post_type' => 'attachment',
        'post_status' => 'inherit',
        'posts_per_page' => -1,
    );

    $attachments = get_posts($args);

    if (!$attachments) {
        return '<p>' . __('Keine Bilder gefunden.', 'wp-image-copyright') . '</p>';
    }

    $portals = array();
    foreach ($attachments as $attachment) {
        $portal = get_post_meta($attachment->ID, 'wpic_portal', true);
        if ($portal && !in_array($portal, $portals)) {
            $portals[] = $portal;
        }
    }

    $output = '<div class="wpic-search-container">';
    $output .= '<input type="text" id="wpic-search" class="wpic-search" placeholder="' . esc_attr__('Suche Bilder...', 'wp-image-copyright') . '">';
    $output .= '</div>';

    // Filtergruppe
    $output .= '<div class="wpic-filtergroup">';
    
    // Anzahl der Einträge Filter
    $output .= '<div class="wpic-entries-per-page">';
    $output .= '<label for="wpic-entries-per-page">' . __('Einträge pro Seite:', 'wp-image-copyright') . '</label>';
    $output .= '<select id="wpic-entries-per-page">';
    $output .= '<option value="20">20</option>';
    $output .= '<option value="40">40</option>';
    $output .= '<option value="60">60</option>';
    $output .= '</select>';
    $output .= '</div>';

    // Portal-Filter
    $output .= '<div class="wpic-portal-filter">';
    $output .= '<label for="wpic-portal-filter">' . __('Portal:', 'wp-image-copyright') . '</label>';
    $output .= '<select id="wpic-portal-filter">';
    $output .= '<option value="">' . __('Alle Portale', 'wp-image-copyright') . '</option>';
    foreach ($portals as $portal) {
        $output .= '<option value="' . esc_attr($portal) . '">' . esc_html($portal) . '</option>';
    }
    $output .= '</select>';
    $output .= '</div>';

    $output .= '</div>'; // Ende der Filtergruppe

    if ($atts['columns']) {
        $output .= '<div id="wpic-entries" class="wpic-columns">';
    } else {
        $output .= '<ul id="wpic-entries" class="wpic-list">';
    }

    foreach ($attachments as $attachment) {
        $wpic_name = get_post_meta($attachment->ID, 'wpic_name', true);
        $wpic_url = get_post_meta($attachment->ID, 'wpic_url', true);
        $wpic_author = get_post_meta($attachment->ID, 'wpic_author', true);
        $wpic_author_url = get_post_meta($attachment->ID, 'wpic_author_url', true);
        $wpic_portal = get_post_meta($attachment->ID, 'wpic_portal', true);
        $wpic_portal_url = get_post_meta($attachment->ID, 'wpic_portal_url', true);
        $wpic_image_id = get_post_meta($attachment->ID, 'wpic_image_id', true);
        $wpic_found_pages = get_post_meta($attachment->ID, 'wpic_found_pages', true);

        if (!$wpic_name) continue;

        $entry = '';

        if ($atts['columns']) {
            $entry .= '<div class="wpic-entry wpic-column-item" data-portal="' . esc_attr($wpic_portal) . '">';
        } else {
            $entry .= '<li class="wpic-entry wpic-list-item" data-portal="' . esc_attr($wpic_portal) . '">';
        }

        // Bildname
        if ($wpic_url) {
            $entry .= '<a href="' . esc_url($wpic_url) . '" class="wpic-image-url" target="_blank">' . esc_html($wpic_name) . '</a>';
        } else {
            $entry .= esc_html($wpic_name);
        }
        $entry .= '<br>';

        // Autor
        if ($wpic_author) {
            $entry .= __('Von', 'wp-image-copyright') . ' ';
            if ($wpic_author_url) {
                $entry .= '<a href="' . esc_url($wpic_author_url) . '" class="wpic-author-url" target="_blank">' . esc_html($wpic_author) . '</a>';
            } else {
                $entry .= esc_html($wpic_author);
            }
            $entry .= '<br>';
        }

        // Portal
        if ($wpic_portal) {
            $entry .= __('Auf', 'wp-image-copyright') . ' ';
            if ($wpic_portal_url) {
                $entry .= '<a href="' . esc_url($wpic_portal_url) . '" class="wpic-portal-url" target="_blank">' . esc_html($wpic_portal) . '</a>';
            } else {
                $entry .= esc_html($wpic_portal);
            }
            $entry .= '<br>';
        }

        // Bild ID
        if ($wpic_image_id) {
            $entry .= __('Bild ID:', 'wp-image-copyright') . ' ' . esc_html($wpic_image_id);
            $entry .= '<br>';
        }

        // Gefundene Seiten
        if ($atts['showpage'] && $wpic_found_pages) {
            $entry .= __('Wird auf folgenden Seiten angezeigt:', 'wp-image-copyright') . '<br>';
            $pages = array();
            foreach ($wpic_found_pages as $page) {
                $pages[] = '<a href="' . esc_url($page) . '" class="wpic-page-url">' . __('hier', 'wp-image-copyright') . '</a>';
            }
            $entry .= implode(', ', $pages);
            $entry .= '<br>';
        }

        if ($atts['columns']) {
            $entry .= '</div>';
        } else {
            $entry .= '</li>';
        }

        $output .= $entry;
    }

    if ($atts['columns']) {
        $output .= '</div>';
    } else {
        $output .= '</ul>';
    }

    // Pagination
    $output .= '<div id="wpic-pagination" class="wpic-pagination">';
    $output .= '<button id="wpic-prev-page" class="wpic-pagination-btn">&lt;</button>';
    $output .= '<span id="wpic-page-numbers"></span>';
    $output .= '<button id="wpic-next-page" class="wpic-pagination-btn">&gt;</button>';
    $output .= '</div>';

    return $output;
}
?>
