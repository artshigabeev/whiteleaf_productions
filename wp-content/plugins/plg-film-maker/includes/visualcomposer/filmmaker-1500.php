<?php
if (!class_exists('WPBakeryShortCode_Film_1500')) {
//This for section countdown
    add_action( 'vc_before_init', 'film_1500', 999999);
    function film_1500() {

        vc_map( array(
            "name" => __( "1500", "filmmaker" ),
            "base" => "film_1500",
            'weight' => 91,
            "params" => array(
              //General
                 array(
                  'type' => 'textfield',
                  'heading' => __( 'Show short text or number ', 'filmmaker' ),
                  'param_name' => 'number',
                  'value' => '',
                  'group' => __( 'General', 'filmmaker' ),
                ),
                  array(
                  'type' => 'attach_image',
                  'heading' => __( 'Choose background', 'filmmaker' ),
                  'param_name' => 'image',
                  'value' => '#',
                  'group' => __( 'General', 'filmmaker' ),
                ),
                array(
                  'type' => 'textarea_html',
                  'heading' => __( 'Your Description ', 'filmmaker' ),
                  'param_name' => 'content',
                  'admin_label' => true,
                  'group' => __( 'General', 'filmmaker' ),
                ),
              //Color 
                array(
                  'type' => 'colorpicker',
                  'heading' => __( 'Change color short text ', 'filmmaker' ),
                  'param_name' => 'color_text',
                  'group' => __( 'Color setting', 'filmmaker' ),
                ),
                array(
                  'type' => 'colorpicker',
                  'heading' => __( 'Change color description ', 'filmmaker' ),
                  'param_name' => 'color_desc',
                  'group' => __( 'Color setting', 'filmmaker' ),
                ),
            ),
        ) );
    }
    class WPBakeryShortCode_Film_1500 extends WPBakeryShortCode {}
}
?>