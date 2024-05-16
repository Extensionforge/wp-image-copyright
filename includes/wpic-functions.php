<?php
// Custom fields for attachments
add_filter('attachment_fields_to_edit', 'wpic_attachment_fields', 10, 2);
function wpic_attachment_fields($form_fields, $post) {
    $wpic_fields = '
    <div id="wpic-field-container-' . $post->ID . '" class="wpic-field-container">
        <strong>' . __('Bildrecht Angaben', 'wp-image-copyright') . '</strong>
        <div class="wpic-field">
            <label for="attachments-' . $post->ID . '-wpic_name">' . __('Name des Bildes', 'wp-image-copyright') . '</label>
            <input type="text" class="text" id="attachments-' . $post->ID . '-wpic_name" name="attachments[' . $post->ID . '][wpic_name]" value="' . esc_attr(get_post_meta($post->ID, 'wpic_name', true)) . '">
            <p class="help">' . __('(Name, wie er auf der Webseite, woher das Bild stammt, steht.)', 'wp-image-copyright') . '</p>
        </div>
        <div class="wpic-field">
            <label for="attachments-' . $post->ID . '-wpic_url">' . __('URL zum Bild', 'wp-image-copyright') . '</label>
            <input type="text" class="text" id="attachments-' . $post->ID . '-wpic_url" name="attachments[' . $post->ID . '][wpic_url]" value="' . esc_url(get_post_meta($post->ID, 'wpic_url', true)) . '">
        </div>
        <div class="wpic-field">
            <label for="attachments-' . $post->ID . '-wpic_author">' . __('Autor des Bildes', 'wp-image-copyright') . '</label>
            <input type="text" class="text" id="attachments-' . $post->ID . '-wpic_author" name="attachments[' . $post->ID . '][wpic_author]" value="' . esc_attr(get_post_meta($post->ID, 'wpic_author', true)) . '">
        </div>
        <div class="wpic-field">
            <label for="attachments-' . $post->ID . '-wpic_author_url">' . __('URL zum Autor', 'wp-image-copyright') . '</label>
            <input type="text" class="text" id="attachments-' . $post->ID . '-wpic_author_url" name="attachments[' . $post->ID . '][wpic_author_url]" value="' . esc_url(get_post_meta($post->ID, 'wpic_author_url', true)) . '">
        </div>
        <div class="wpic-field">
            <label for="attachments-' . $post->ID . '-wpic_portal">' . __('Portalname', 'wp-image-copyright') . '</label>
            <input type="text" class="text" id="attachments-' . $post->ID . '-wpic_portal" name="attachments[' . $post->ID . '][wpic_portal]" value="' . esc_attr(get_post_meta($post->ID, 'wpic_portal', true)) . '">
        </div>
        <div class="wpic-field">
            <label for="attachments-' . $post->ID . '-wpic_portal_url">' . __('URL zum Portal', 'wp-image-copyright') . '</label>
            <input type="text" class="text" id="attachments-' . $post->ID . '-wpic_portal_url" name="attachments[' . $post->ID . '][wpic_portal_url]" value="' . esc_url(get_post_meta($post->ID, 'wpic_portal_url', true)) . '">
        </div>
        <div class="wpic-field">
            <label for="attachments-' . $post->ID . '-wpic_image_id">' . __('Bild ID', 'wp-image-copyright') . '</label>
            <input type="text" class="text" id="attachments-' . $post->ID . '-wpic_image_id" name="attachments[' . $post->ID . '][wpic_image_id]" value="' . esc_attr(get_post_meta($post->ID, 'wpic_image_id', true)) . '">
        </div>
    </div>';

    $form_fields['wpic_fields'] = array(
        'label' => '',
        'input' => 'html',
        'html' => $wpic_fields,
    );

    return $form_fields;
}

// Save custom fields
add_filter('attachment_fields_to_save', 'wpic_attachment_fields_save', 10, 2);
function wpic_attachment_fields_save($post, $attachment) {
    if (!current_user_can('edit_post', $post['ID'])) {
        return $post;
    }

    if (isset($attachment['wpic_name'])) {
        update_post_meta($post['ID'], 'wpic_name', sanitize_text_field($attachment['wpic_name']));
    }
    if (isset($attachment['wpic_url'])) {
        update_post_meta($post['ID'], 'wpic_url', esc_url_raw($attachment['wpic_url']));
    }
    if (isset($attachment['wpic_author'])) {
        update_post_meta($post['ID'], 'wpic_author', sanitize_text_field($attachment['wpic_author']));
    }
    if (isset($attachment['wpic_author_url'])) {
        update_post_meta($post['ID'], 'wpic_author_url', esc_url_raw($attachment['wpic_author_url']));
    }
    if (isset($attachment['wpic_portal'])) {
        update_post_meta($post['ID'], 'wpic_portal', sanitize_text_field($attachment['wpic_portal']));
    }
    if (isset($attachment['wpic_portal_url'])) {
        update_post_meta($post['ID'], 'wpic_portal_url', esc_url_raw($attachment['wpic_portal_url']));
    }
    if (isset($attachment['wpic_image_id'])) {
        update_post_meta($post['ID'], 'wpic_image_id', sanitize_text_field($attachment['wpic_image_id']));
    }
    return $post;
}

// Add custom column in media list view
add_filter('manage_media_columns', 'wpic_add_media_columns');
function wpic_add_media_columns($columns) {
    $columns['wpic_source'] = __('Quellenangabe', 'wp-image-copyright');
    return $columns;
}

add_action('manage_media_custom_column', 'wpic_media_column_content', 10, 2);
function wpic_media_column_content($column_name, $post_id) {
    if ($column_name == 'wpic_source') {
        $wpic_name = get_post_meta($post_id, 'wpic_name', true);
        if ($wpic_name) {
            echo '<span class="wpic-checked">✔️</span>';
        } else {
            echo '<span class="wpic-unchecked">—</span>';
        }
    }
}
?>
