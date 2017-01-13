<?php
if (!class_exists('WPBakeryShortCode_Film_coundown')) {
//This for section countdown
    add_action( 'vc_before_init', 'film_Coundown', 999999);
    function film_Coundown() {
        vc_map( array(
            "name" => __( "Count down & subcribe", "filmmaker" ),
            "base" => "film_coundown",
            'weight' => 91,
            'description' => __( 'This section show coutdown', 'filmmaker' ),
            "params" => array(
              array(
                'type' => 'textfield',
                'heading' => __( 'Your count down', 'filmmaker' ),
                'param_name' => 'coundown_number',
                'description' => __( 'Countdown format number', 'filmmaker' ),
                'group'   => __('General','filmmaker'),
              ),
              array(
                'type' => 'textfield',
                'heading' => __( 'Your short text for subcribe', 'filmmaker' ),
                'param_name' => 'subcribe_title',
                'group'   => __('General','filmmaker'),
              ),
              array(
                'type' => 'colorpicker',
                'heading' => __( 'Set color number countdown', 'filmmaker' ),
                'param_name' => 'color_number',
                'group'   => __('Color setting','filmmaker'),
              ),
              array(
                'type' => 'colorpicker',
                'heading' => __( 'Set color title countdown', 'filmmaker' ),
                'param_name' => 'color_title',
                'group'   => __('Color setting','filmmaker'),
              ),
              array(
                'type' => 'colorpicker',
                'heading' => __( 'Set hover color countdown', 'filmmaker' ),
                'param_name' => 'hover_color',
                'group'   => __('Color setting','filmmaker'),
              ),
            ),
        ) );
    }
    class WPBakeryShortCode_Film_coundown extends WPBakeryShortCode {}
}
?>