<h4>Destination</h4>
<p>
    <label for="country">Country</label>
    <input type="text" name="country" id="country" value="<?php echo esc_attr($country); ?>" class="widefat">
</p>
<p>
    <label for="city">City</label>
    <input type="text" name="city" id="city" value="<?php echo esc_attr($city); ?>" class="widefat">
</p>
<p>
    <label for="destination_latitude">Destination Latitude</label>
    <input type="text" name="destination_latitude" id="destination_latitude" value="<?php echo esc_attr($destination_latitude); ?>" class="widefat">
</p>
<p>
    <label for="destination_longitude">Destination Longitude</label>
    <input type="text" name="destination_longitude" id="destination_longitude" value="<?php echo esc_attr($destination_longitude); ?>" class="widefat">
</p>
<h4>Origin Coordinates</h4>
<p>
    <label for="origin_latitude">Origin Latitude</label>
    <input type="text" name="origin_latitude" id="origin_latitude" value="<?php echo esc_attr($origin_latitude); ?>" class="widefat">
</p>
<p>
    <label for="origin_longitude">Origin Longitude</label>
    <input type="text" name="origin_longitude" id="origin_longitude" value="<?php echo esc_attr($origin_longitude); ?>" class="widefat">
</p>

<?php wp_nonce_field('save_location_details', 'location_details_nonce'); ?>