<?php 

$countries = array_unique( array_column( $destination_locations, 'country' ) );
$cities = array_unique( array_column( $destination_locations, 'city' ) );

$distance_travelled_miles = ceil( $this->calculate_total_miles_for_all_posts() );
$distance_travelled_km = ceil( $this->calculate_total_miles_for_all_posts() * 1.60934 );

$section_title = CSC_blogging_locations_Settings::$options['csc_blogging_locations_title'];

?>
<div class="csc_blogging_locations-row">
    <div class="csc_blogging_locations-info_wrapper">
        <h2 class="csc_blogging_locations-heading"><?php echo esc_html( $section_title ); ?></h2>
        <div class="csc_blogging_locations-flex__square">
            <div class="csc_blogging_locations-flex__stat__container">
                <p class="csc_blogging_locations-flex__stat__title">Countries visited</p>
                <div class="csc_blogging_locations-flex__stat__data__container">
                    <p class="csc_blogging_locations-stat" data-target=" <?php echo esc_attr(count($countries)); ?>"></p>
                    <i class="fas fa-flag"></i>
                </div>
            </div>
            <div class="csc_blogging_locations-flex__stat__container">
                <p class="csc_blogging_locations-flex__stat__title">Locations blogged about</p>
                <div class="csc_blogging_locations-flex__stat__data__container">
                    <p class="csc_blogging_locations-stat" data-target="<?php echo esc_attr(count($cities)); ?>"></p>
                    <i class="fas fa-map-pin"></i>
                </div>
            </div>
            <div id="csc_blogging_locations-miles" class="csc_blogging_locations-flex__stat__container">
                <p class="csc_blogging_locations-flex__stat__title">Miles traveled</p>
                <div class="csc_blogging_locations-flex__stat__data__container">
                    <p class="csc_blogging_locations-stat" data-target="<?php echo esc_attr($distance_travelled_miles); ?>"></p>
                    <i class="fas fa-map"></i>
                </div>
            </div>
            <div id="csc_blogging_locations-km" class="csc_blogging_locations-flex__stat__container">
                <p class="csc_blogging_locations-flex__stat__title">Kilometers traveled</p>
                <div class="csc_blogging_locations-flex__stat__data__container">
                    <p  class="csc_blogging_locations-stat" data-target="<?php echo esc_attr($distance_travelled_km); ?>"></p>
                    <i class="fas fa-map"></i>
                </div>
            </div>
        </div>   
    </div>
    <div id="map" class="csc_blogging_locations-leaflet_map"></div>
</div>