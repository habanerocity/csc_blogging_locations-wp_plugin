<?php 
// include CSC_BLOGGING_LOCATIONS_PATH . '/functions/functions.php';

$countries = array_unique( array_column( $destination_locations, 'country' ) );
$cities = array_unique( array_column( $destination_locations, 'city' ) );

$distance_travelled_miles = ceil( $this->calculate_total_miles_for_all_posts() );
$distance_travelled_km = ceil( $this->calculate_total_miles_for_all_posts() * 1.60934 );

?>
<div class="csc_blogging_locations-row">
    <div class="csc_blogging_locations-info_wrapper">
        <h2 class="csc_blogging_locations-heading">Our Travel Stats So Far</h2>
        <div class="csc_blogging_locations-flex__square">
            <div class="csc_blogging_locations-flex__stat__container">
                <p class="csc_blogging_locations-flex__stat__title">Countries visited</p>
                <div class="csc_blogging_locations-flex__stat__data__container">
                    <p class="csc_blogging_locations-stat" data-target=" <?php echo count($countries); ?>"></p>
                    <i class="fas fa-flag"></i>
                </div>
            </div>
            <div class="csc_blogging_locations-flex__stat__container">
                <p class="csc_blogging_locations-flex__stat__title">Locations blogged about</p>
                <div class="csc_blogging_locations-flex__stat__data__container">
                    <p class="csc_blogging_locations-stat" data-target="<?php echo count($cities); ?>"></p>
                    <i class="fas fa-map-pin"></i>
                </div>
            </div>
            <div id="csc_blogging_locations-miles" class="csc_blogging_locations-flex__stat__container">
                <p class="csc_blogging_locations-flex__stat__title">Miles traveled</p>
                <div class="csc_blogging_locations-flex__stat__data__container">
                    <p class="csc_blogging_locations-stat" data-target="<?php echo $distance_travelled_miles; ?>"></p>
                    <i class="fas fa-map"></i>
                </div>
            </div>
            <div id="csc_blogging_locations-km" class="csc_blogging_locations-flex__stat__container">
                <p class="csc_blogging_locations-flex__stat__title">Kilometers traveled</p>
                <div class="csc_blogging_locations-flex__stat__data__container">
                    <p  class="csc_blogging_locations-stat" data-target="<?php echo $distance_travelled_km; ?>"></p>
                    <i class="fas fa-map"></i>
                </div>
            </div>
        </div>   
    </div>
    <div id="map" class="csc_blogging_locations-leaflet_map"></div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var map = L.map('map').setView([14.554789, 120.990010], 4);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    var locations = <?php echo json_encode($destination_locations); ?>;
    
    locations.forEach(function(location) {
        L.marker([location.destination_latitude, location.destination_longitude]).addTo(map)
            .bindPopup(location.city + ', ' + location.country);
    });
});
</script>