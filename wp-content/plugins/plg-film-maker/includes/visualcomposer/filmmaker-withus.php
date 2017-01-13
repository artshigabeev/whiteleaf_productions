<?php
if (!class_exists('WPBakeryShortCode_Film_withus')) {

//This for testimonial
    add_action( 'vc_before_init', 'film_withus', 999999);
    function film_withus() {
        vc_map( array(
            "name" => __( "Show link join crew Film", "filmmaker" ),
            "base" => "film_withus",
            'weight' => 91,
            'description' => __( 'This section show testimonial', 'filmmaker' ),
            "params" => array(
              //General
              array(
                'type' => 'textfield',
                'heading' => __( 'Input text field', 'filmmaker' ),
                'param_name' => 'withus_text',
                'group' => __( 'General', 'filmmaker' ),
              ),
              array(
                'type' => 'textfield',
                'heading' => __( 'Input text link', 'filmmaker' ),
                'param_name' => 'withus_text_link',
                'group' => __( 'General', 'filmmaker' ),
              ),
              array(
                'type' => 'textfield',
                'heading' => __( 'link', 'filmmaker' ),
                'param_name' => 'withus_url',
                'value' => __('#','filmmaker'),
                'group' => __( 'General', 'filmmaker' ),
              ),
              //Color setting
              array(
                'type' => 'colorpicker',
                'heading' => __( 'Change color title', 'filmmaker' ),
                'param_name' => 'color_title',
                'group' => __( 'Setting color', 'filmmaker' ),
              ),
              array(
                'type' => 'colorpicker',
                'heading' => __( 'Change color link ', 'filmmaker' ),
                'param_name' => 'color_link_submit',
                'group' => __( 'Setting color', 'filmmaker' ),
              ),
              array(
                'type' => 'colorpicker',
                'heading' => __( 'Change color border link', 'filmmaker' ),
                'param_name' => 'color_border_link',
                'group' => __( 'Setting color', 'filmmaker' ),
              ),
              array(
                'type' => 'colorpicker',
                'heading' => __( 'Hover color link ', 'filmmaker' ),
                'param_name' => 'hover_color_link',
                'group' => __( 'Setting color', 'filmmaker' ),
              ),
              array(
                'type' => 'colorpicker',
                'heading' => __( 'Hover background link ', 'filmmaker' ),
                'param_name' => 'hover_bg_link',
                'group' => __( 'Setting color', 'filmmaker' ),
              ),
              array(
                'type' => 'colorpicker',
                'heading' => __( 'Background box link join', 'filmmaker' ),
                'param_name' => 'bg_link_join',
                'group' => __( 'Setting color', 'filmmaker' ),
              ),
            ),
        ) );
    }
    class WPBakeryShortCode_Film_withus extends WPBakeryShortCode {}
}

 ?>