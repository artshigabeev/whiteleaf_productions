<?php
   if (!class_exists('WPBakeryShortCode_Film_partner')) {

//This for testimonial
    add_action( 'vc_before_init', 'film_partner', 999999);
    function film_partner() {
        for($i=1; $i<=20; $i++){
            $partner_number_post[$i] = $i;
        }
        $list_column = array(
            'Two post in row' => 'col-md-6 col-sm-4 col-xs-12',
            'Three post in row' => 'col-md-4 col-sm-6 col-xs-12',
            'Four post in row' => 'col-md-3 col-sm-4 col-xs-12',
            'Six post in row' => 'col-md-2 col-sm-4 col-xs-6',
            );
        vc_map( array(
            "name"        => __( "Show partner section", "filmmaker" ),
            "base"        => "film_partner",
            'weight'      => 91,
            'description' => __( 'This section show partner list', 'filmmaker' ),
            "params"      => array(
                array(
                  'type'        => 'dropdown',
                  'heading'     => __( 'Show title section', 'filmmaker' ),
                  'param_name'  => 'partner_number',
                  'value'       => $partner_number_post,
                  'std'    => 6,
                ),
                  array(
                    'type' => 'dropdown',
                    'heading' => __( 'Display number in row', 'filmmaker' ),
                    'param_name' => 'partner_style',
                    'value' => $list_column,
                    'std' => 4,
                ),
            ),
        ) );
    }
    class WPBakeryShortCode_Film_partner extends WPBakeryShortCode {}
}


 ?>