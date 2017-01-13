<?php
if (!class_exists('WPBakeryShortCode_Film_videoblog')) {
    add_action( 'vc_before_init', 'film_videoblog', 999999);
    function film_videoblog() {
        vc_map( array(
            "name" => __( "Show Video blog", "filmmaker" ),
            "base" => "film_videoblog",
            'weight' => 91,
            'description' => __( 'This section show film list', 'filmmaker' ),
            "params" => array(
              array(
                'type' => 'textfield',
                'heading' => __( 'Input id category post have video', 'filmmaker' ),
                'param_name' => 'film_videoblog_id',
                'description' => __('ex: 23,34,45 if you not set video blog get all video in category blog','filmmaker'),
              ),
            ),
        ) );
    }
    class WPBakeryShortCode_Film_videoblog extends WPBakeryShortCode {}
}
?>
