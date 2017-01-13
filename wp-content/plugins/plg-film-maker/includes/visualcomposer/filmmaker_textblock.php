<?php
   if (!class_exists('WPBakeryShortCode_Film_textblock')) {

//This for testimonial
    add_action( 'vc_before_init', 'film_textblock', 999999);
    function film_textblock() {
        vc_map( array(
            "name"        => __( "Show Film Text Block", "filmmaker" ),
            "base"        => "film_textblock",
            'weight'      => 91,
            'description' => __( 'This show film text block', 'filmmaker' ),
            "params"      => array(
                array(
                  'type'        => 'textfield',
                  'heading'     => __( 'Show small title', 'filmmaker' ),
                  'param_name'  => 'text_small',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Display title big', 'filmmaker' ),
                    'param_name' => 'text_big',
                ),
                array(
                    'type' => 'colorpicker',
                    'heading' => __( 'Color text small', 'filmmaker' ),
                    'param_name' => 'color_text_small',
                ),
                array(
                    'type' => 'colorpicker',
                    'heading' => __( 'Color text big', 'filmmaker' ),
                    'param_name' => 'color_text_big',
                ),
            ),
        ) );
    }
    class WPBakeryShortCode_Film_textblock extends WPBakeryShortCode {}
}
 ?>