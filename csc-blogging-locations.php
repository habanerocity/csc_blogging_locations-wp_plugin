<?php
/*
Plugin Name: CSC Blogging Locations
Description: A map which displays blog post locations and some statistics about them.
Version: 1.0
Author: Lindy Ramirez
Author URI: https://www.lindyramirez.com
License: GPL2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

require_once plugin_dir_path(__FILE__) . 'includes/class.csc-blogging-locations_meta-box.php';

if (!class_exists('CSC_Blogging_Locations')) {
    class CSC_Blogging_Locations {
        public function __construct() {
            $this->define_constants();
            new CSC_Blogging_Locations_Meta_Box();
            add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
            add_shortcode('csc_blog_locations_map', array($this, 'render_map_shortcode'));
        }

        public function define_constants() {
            define('CSC_BLOGGING_LOCATIONS_PATH', plugin_dir_path(__FILE__));
            define('CSC_BLOGGING_LOCATIONS_URL', plugin_dir_url(__FILE__));
            define('CSC_BLOGGING_LOCATIONS_VERSION', '1.0.0');
        }

        public function activate() {
            update_option('rewrite_rules', '');
        }

        public function deactivate() {
            flush_rewrite_rules();
        }

        public static function uninstall() {
            // Uninstall functionality here if needed
        }

        public function enqueue_scripts() {
            wp_enqueue_style('csc-blogging-locations-css', CSC_BLOGGING_LOCATIONS_URL . 'assets/css/frontend.css', array(), CSC_BLOGGING_LOCATIONS_VERSION, 'all');

            wp_enqueue_script('csc-blogging-locations-js', CSC_BLOGGING_LOCATIONS_URL . 'assets/js/counter.js', array(), CSC_BLOGGING_LOCATIONS_VERSION, true);

            wp_enqueue_style('leaflet-css', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css');
            wp_enqueue_script('leaflet-js', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', array(), null, true);
        }

        public function render_map_shortcode() {
            $destination_locations = $this->get_locations_data();
            ob_start();
            require_once CSC_BLOGGING_LOCATIONS_PATH . 'views/map-view.php';
            return ob_get_clean();
        }
        
        public function calculate_total_miles_for_all_posts() {
            $args = array(
                'post_type' => 'post',
                'post_status' => 'publish',
                'posts_per_page' => -1, // Retrieve all posts
            );
        
            $query = new WP_Query($args);
            $totalDistanceKm = 0;
        
            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    $origin_latitude = get_post_meta(get_the_ID(), 'origin_latitude', true);
                    $origin_longitude = get_post_meta(get_the_ID(), 'origin_longitude', true);
                    $destination_latitude = get_post_meta(get_the_ID(), 'destination_latitude', true);
                    $destination_longitude = get_post_meta(get_the_ID(), 'destination_longitude', true);
        
                    // Check if all coordinates are numeric and not empty
                    if (is_numeric($origin_latitude) && is_numeric($origin_longitude) &&
                        is_numeric($destination_latitude) && is_numeric($destination_longitude)) {
                        $distance = $this->haversineGreatCircleDistance(
                            $origin_latitude, $origin_longitude,
                            $destination_latitude, $destination_longitude,
                            6371 // Earth radius in kilometers
                        );
                        $totalDistanceKm += $distance;                
                    }
                }
            }
        
            wp_reset_postdata(); // Reset the global post object
        
            $round_trip = $totalDistanceKm * 2; // roundtrip
        
            return $distance_in_miles = $round_trip * 0.621371; //convert km to miles
        }

        private function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371){
            // Convert all inputs to floats if they are numeric, otherwise set to 0.0
            $latitudeFrom = is_numeric($latitudeFrom) ? (float)$latitudeFrom : 0.0;
            $longitudeFrom = is_numeric($longitudeFrom) ? (float)$longitudeFrom : 0.0;
            $latitudeTo = is_numeric($latitudeTo) ? (float)$latitudeTo : 0.0;
            $longitudeTo = is_numeric($longitudeTo) ? (float)$longitudeTo : 0.0;
        
            if ($latitudeFrom !== 0.0 && $longitudeFrom !== 0.0 && $latitudeTo !== 0.0 && $longitudeTo !== 0.0) {
                // Haversine formula to calculate the distance
                $latFrom = deg2rad($latitudeFrom);
                $lonFrom = deg2rad($longitudeFrom);
                $latTo = deg2rad($latitudeTo);
                $lonTo = deg2rad($longitudeTo);
        
                $latDelta = $latTo - $latFrom;
                $lonDelta = $lonTo - $lonFrom;
        
                $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                    cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
                return $angle * $earthRadius;
            } else {
                // Handle the case where inputs are invalid (e.g., return 0 or throw an exception)
                return 0; 
            }
        }

        private function get_locations_data() {
            $args = array(
                'post_type' => 'post',
                'posts_per_page' => -1,
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'country',
                        'compare' => 'EXISTS',
                    ),
                    array(
                        'key' => 'city',
                        'compare' => 'EXISTS',
                    ),
                    array(
                        'key' => 'destination_latitude',
                        'compare' => 'EXISTS',
                    ),
                    array(
                        'key' => 'destination_longitude',
                        'compare' => 'EXISTS',
                    ),
                    array(
                        'key' => 'origin_latitude',
                        'compare' => 'EXISTS',
                    ),
                    array(
                        'key' => 'origin_longitude',
                        'compare' => 'EXISTS',
                    ),
                ),
            );

            $query = new WP_Query($args);
            $destination_locations = array();

            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    $destination_locations[] = array(
                        'country' => get_post_meta(get_the_ID(), 'country', true),
                        'city' => get_post_meta(get_the_ID(), 'city', true),
                        'destination_latitude' => get_post_meta(get_the_ID(), 'destination_latitude', true),
                        'destination_longitude' => get_post_meta(get_the_ID(), 'destination_longitude', true),
                        'origin_latitude' => get_post_meta(get_the_ID(), 'origin_latitude', true),
                        'origin_longitude' => get_post_meta(get_the_ID(), 'origin_longitude', true),
                    );
                }
                wp_reset_postdata();
            }

            return $destination_locations;
        }
    }
}

if (class_exists('CSC_Blogging_Locations')) {
    $csc_blogging_locations = new CSC_Blogging_Locations();
    register_activation_hook(__FILE__, array($csc_blogging_locations, 'activate'));
    register_deactivation_hook(__FILE__, array($csc_blogging_locations, 'deactivate'));
    register_uninstall_hook(__FILE__, array('CSC_Blogging_Locations', 'uninstall'));
}