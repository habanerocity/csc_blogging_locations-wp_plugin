<?php 

if( ! class_exists( 'CSC_Blogging_Locations_Settings' ) ){
    class CSC_Blogging_Locations_Settings{
        public static $options;

        public function __construct(){
            self::$options = get_option( 'csc_blogging_locations_options' );
            add_action( 'admin_init', array( $this, 'admin_init' ) );
        }

        public function admin_init(){

            register_setting( 'csc_blogging_locations_group', 'csc_blogging_locations_options' );

            add_settings_section(
                'csc_blogging_locations_main_section',
                'How does it work?',
                null,
                'csc_blogging_locations_page1'
            );

            add_settings_section(
                'csc_blogging_locations_second_section',
                'Other Plugin Options',
                null,
                'csc_blogging_locations_page2'
            );

            add_settings_field(
                'csc_blogging_locations_shortcode',
                'Shortcode',
                array( $this, 'csc_blogging_locations_callback' ),
                'csc_blogging_locations_page1',
                'csc_blogging_locations_main_section'
            );

            add_settings_field(
                'csc_blogging_locations_title',
                'Blogging Locations Section Title',
                array( $this, 'csc_blogging_locations_title_callback' ),
                'csc_blogging_locations_page2',
                'csc_blogging_locations_second_section'
            );
        }

        public function csc_blogging_locations_callback(){
            ?>
            <span>Use the shortcode [csc_blog_locations_map] to display the map in any page or post.</span>
            <?php
        }

        public function csc_blogging_locations_title_callback(){
            ?>
                <input
                type="text"
                name="csc_blogging_locations_options[csc_blogging_locations_title]"
                id="csc_blogging_locations_title"
                value="<?php echo esc_attr( isset( self::$options['csc_blogging_locations_title'] ) ? self::$options['csc_blogging_locations_title'] : '' ); ?>"
                >
            <?php
        }
    }
}


?>