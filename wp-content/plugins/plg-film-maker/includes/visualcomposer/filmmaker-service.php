<?php
if (!class_exists('WPBakeryShortCode_Film_service')) {
    add_action( 'vc_before_init', 'film_Service', 999999);
    function film_Service() {
        $film_icons = array(
            'Film icon 01'        => 'be-1be',
            'Film icon 02'        => 'be-2be',
            'Film icon 03'        => 'be-3be',
            'Film icon 04'        => 'be-4be',
            'Film icon 05'        => 'be-5be',
            'Film icon 06'        => 'be-6be',
            'Film icon 07'        => 'be-7be',
            'Film icon 08'        => 'be-8be',
            'Film icon 09'        => 'be-9be',
            'Film icon 10'        => 'be-10be',
            'Film icon 011'        => 'be-11be',
            'Film icon 012'        => 'be-12be',
            'Film icon 013'        => 'be-13be',
            'Film icon 014'        => 'be-14be',
            'Film icon 015'        => 'be-15be',
            'Film icon 016'        => 'be-16be',
            'Film icon 017'        => 'be-17be',
            'Film icon 018'        => 'be-18be',
            'Film icon 019'        => 'be-19be',
            'Film icon 020'        => 'be-20be',
            'Film icon 021'        => 'be-21be',
            'Film icon 022'        => 'be-22be',
            'Film icon 023'        => 'be-23be',
            'Film icon 024'        => 'be-24be',
            'Film icon 025'        => 'be-25be',
            'Film icon 026'        => 'be-26be',
            'Film icon 027'        => 'be-27be',
            'Film icon 028'        => 'be-28be',
            'Film icon 029'        => 'be-29be',
            'Film icon 030'        => 'be-30be',
            'Film icon 031'        => 'be-31be',
            'Film icon 032'        => 'be-32be',
            'Film icon 033'        => 'be-33be',
            'Film icon 034'        => 'be-34be',
            'Film icon 035'        => 'be-35be',
            'Film icon 036'        => 'be-36be',
            'Film icon 037'        => 'be-37be',
            'Film icon 038'        => 'be-38be',
            'Film icon 039'        => 'be-39be',
            'Film icon 040'        => 'be-40be',
            'Film icon 041'        => 'be-41be',
            'Film icon 042'        => 'be-42be',
            'Film icon 043'        => 'be-43be',
            'Film icon 044'        => 'be-44be',
            'Film icon 045'        => 'be-45be',
            'Film icon 046'        => 'be-46be',
        );
         $film_class = array(
            'None' => '',
            'have border' => 'box-content-hover',
        );

        vc_map( array(
            "name" => __( "Service", "filmmaker" ),
            "base" => "film_service",
            'weight' => 91,
            'description' => __( 'This section show video', 'filmmaker' ),
            "params" => array(
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Name Service', 'filmmaker' ),
                    'param_name' => 'name_service',
                    'value' => '',
                    'group' => __( 'General', 'filmmaker'),
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => __( 'Select icon service ', 'filmmaker' ),
                    'param_name' => 'icon_service',
                    'value' => $film_icons ,
                    'group' => __( 'General', 'filmmaker'),
                ),
               
                
                array(
                    'type' => 'textarea',
                    'heading' => __( 'Service description', 'filmmaker'),
                    'param_name' => 'description_service',
                    'description' => __( 'Description for service', 'filmmaker'),
                    'group' => __( 'General', 'filmmaker'),
                ),
               
                array(
                    'type' => 'dropdown',
                    'heading' => __( 'Choose border service', 'filmmaker' ),
                    'param_name' => 'border_service',
                    'value' => $film_class,
                    'group' => __( 'General', 'filmmaker'),
                ),
                //Setting color;
                array(
                    'type' => 'colorpicker',
                    'heading' => __( 'Color Name service', 'filmmaker'),
                    'param_name' => 'color_name_service',
                    'group' => __( 'Setting', 'filmmaker'),
                ),
                 array(
                    'type' => 'colorpicker',
                    'heading' => __( 'Color icon service ', 'filmmaker' ),
                    'param_name' => 'color_icon_service',
                    'group' => __( 'Setting', 'filmmaker'),
                ),
                 array(
                    'type' => 'colorpicker',
                    'heading' => __( 'Color hover icon service ', 'filmmaker' ),
                    'param_name' => 'hover_color_icon_service',
                    //'value' => $film_icons ,
                    'group' => __( 'Setting', 'filmmaker'),
                ),
                  array(
                    'type' => 'colorpicker',
                    'heading' => __( 'Color Description service', 'filmmaker'),
                    'param_name' => 'color_description_service',
                    'group' => __( 'Setting', 'filmmaker'),
                ),
                array(
                    'type' => 'colorpicker',
                    'heading' => __( 'color border service ', 'filmmaker' ),
                    'param_name' => 'color_border_service',
                    //'value' => $film_icons ,
                    'group' => __( 'Setting', 'filmmaker'),
                ),
                array(
                    'type' => 'colorpicker',
                    'heading' => __( 'Hover border service ', 'filmmaker' ),
                    'param_name' => 'hover_border_service',
                    //'value' => $film_icons ,
                    'group' => __( 'Setting', 'filmmaker'),
                ),
            ),
       ) );
    }
    class WPBakeryShortCode_Film_service extends WPBakeryShortCode {}
}
?>