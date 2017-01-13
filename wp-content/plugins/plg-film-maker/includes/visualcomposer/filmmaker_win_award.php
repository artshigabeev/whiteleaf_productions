<?php
   if (!class_exists('WPBakeryShortCode_Film_win_award')) {
    add_action( 'vc_before_init', 'film_win_award', 999999);
    function film_win_award() {
        vc_map( array(
            "name"        => __( "Show winning award", "filmmaker" ),
            "base"        => "film_win_award",
            'weight'      => 91,
            'description' => __( 'This section show post list', 'filmmaker' ),
            "params"      => array(
                array(
                  'type'        => 'attach_image',
                  'heading'     => __( 'Display your image winning award', 'filmmaker' ),
                  'param_name'  => 'win_image',
                ),
                  array(
                    'type' => 'textfield',
                    'heading' => __( 'Link to winning award', 'filmmaker' ),
                    'param_name' => 'win_url',
                    'value' => __('#','filmmaker'),
                ),
            ),
        ) );
    }
    class WPBakeryShortCode_Film_win_award extends WPBakeryShortCode {}
}
 ?>