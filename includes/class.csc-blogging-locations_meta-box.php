<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('CSC_Blogging_Locations_Meta_Box')) {
    class CSC_Blogging_Locations_Meta_Box {
        public function __construct() {
            add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
            add_action('save_post', array($this, 'save_meta_boxes'));
        }

        public function add_meta_boxes() {
            add_meta_box('location_meta_box', 'CSC Blogging Locations - Trip Details', array($this, 'render_meta_box'), 'post', 'normal', 'high');
        }

        public function render_meta_box($post) {
            
            $country = get_post_meta($post->ID, 'country', true);
            $city = get_post_meta($post->ID, 'city', true);
            $destination_latitude = get_post_meta($post->ID, 'destination_latitude', true);
            $destination_longitude = get_post_meta($post->ID, 'destination_longitude', true);

            $origin_latitude = get_post_meta($post->ID, 'origin_latitude', true);
            $origin_longitude = get_post_meta($post->ID, 'origin_longitude', true);

            include CSC_BLOGGING_LOCATIONS_PATH . '/views/metabox-view.php';
        }

        public function save_meta_boxes($post_id) {
            if (!isset($_POST['location_details_nonce']) || !wp_verify_nonce($_POST['location_details_nonce'], 'save_location_details')) {
                return;
            }

            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return;
            }

            if (isset($_POST['post_type']) && $_POST['post_type'] === 'post' && !current_user_can('edit_post', $post_id)) {
                return;
            }

            $fields = ['country', 'city', 'destination_latitude', 'destination_longitude', 'origin_latitude', 'origin_longitude'];
            foreach ($fields as $field) {
                if (isset($_POST[$field])) {
                    update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
                }
            }
        }
    }
}
