<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <form action="options.php" method="post">
        <?php 
            settings_fields( 'csc_blogging_locations_group' );
            do_settings_sections( 'csc_blogging_locations_page1' );
            do_settings_sections( 'csc_blogging_locations_page2' );
            submit_button( 'Save Settings' );
        ?>
    </form>
</div>