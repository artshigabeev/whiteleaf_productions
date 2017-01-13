<?php
if (!class_exists('WPBakeryShortCode_Film_testimonials')) {

//This for testimonial
    add_action( 'vc_before_init', 'film_Testimonials', 999999);
    function film_Testimonials() {
        for($i=1; $i<=50; $i++){
            $bebo_perpage_arr[$i] = $i;
        }
        vc_map( array(
            "name" => __( "Show testimonial", "filmmaker" ),
            "base" => "film_testimonials",
            'weight' => 91,
            'description' => __( 'This section show testimonial', 'filmmaker' ),
            "params" => array(
                array(
                  'type' => 'dropdown',
                  'heading' => __( 'Perpage', 'filmmaker' ),
                  'param_name' => 'perpage',
                  'value' => $bebo_perpage_arr,
                  'std' => 3,
                  'admin_label' => true,
                  'description' => __( 'Number to show your testimonial', 'filmmaker' ),
                  'group'       => __('General','filmmaker'),
                ),
                array(
                  'type' => 'colorpicker',
                  'heading' => __( 'Setting color quote ', 'filmmaker' ),
                  'param_name' => 'color_quote',
                  'group'       => __('Color setting','filmmaker'),
                ),
                array(
                  'type' => 'colorpicker',
                  'heading' => __( 'Setting color name', 'filmmaker' ),
                  'param_name' => 'color_name',
                  'group'       => __('Color setting','filmmaker'),
                ),
                array(
                  'type' => 'colorpicker',
                  'heading' => __( 'Setting color comment Testimonial', 'filmmaker' ),
                  'param_name' => 'color_comment',
                  'group'       => __('Color setting','filmmaker'),
                ),
                array(
                  'type' => 'colorpicker',
                  'heading' => __( 'Setting color pagination testimonial', 'filmmaker' ),
                  'param_name' => 'color_page',
                  'group'       => __('Color setting','filmmaker'),
                ),
                array(
                  'type' => 'colorpicker',
                  'heading' => __( 'Setting color pagination testimonial active', 'filmmaker' ),
                  'param_name' => 'color_page_active',
                  'group'       => __('Color setting','filmmaker'),
                ),
            ),
        ) );
    }
    class WPBakeryShortCode_Film_testimonials extends WPBakeryShortCode {}
}

 ?>