<?php
//This for discover shortcode
if (!class_exists('WPBakeryShortCode_film_discover')) {
    add_action( 'vc_before_init', 'filmmaker_discover', 999999);
    function filmmaker_discover() {
        vc_map( array(
            "name" => __( "Discover", "filmmaker" ),
            "base" => "film_discover",
            'weight' => 91,
            'description' => __( 'This section show discover section', 'filmmaker' ),
            "params" => array(
              array(
                  'type' => 'textfield',
                  'heading' => __( 'Title top section', 'filmmaker' ),
                  'param_name' => 'small_title',
                  'group' => __('General', 'Filmmaker'),
                ),
                array(
                  'type' => 'textfield',
                  'heading' => __( 'Title under top section', 'filmmaker' ),
                  'param_name' => 'title_section',
                  'group' => __('General', 'Filmmaker'),
                ),
                array(
                  'type' => 'colorpicker',
                  'heading' => __( 'Color Title top section', 'filmmaker' ),
                  'param_name' => 'color_small_title',
                  'group' => __('General', 'Filmmaker'),
                ),
                array(
                  'type' => 'colorpicker',
                  'heading' => __( 'Color Title top section', 'filmmaker' ),
                  'param_name' => 'color_title_section',
                  'group' => __('General', 'Filmmaker'),
                ),
                 array(
                  'type' => 'dropdown',
                  'heading' => __( 'Style discover', 'filmmaker' ),
                  'param_name' => 'style_discover',
                  'value' => array(
                        '=== choose type ===' => '',
                        'Show big Image' =>1,
                        'Show small Image' =>2,
                        'Show haft content, haft Image' =>3,
                    ),
                  'description' => __( 'Choose style discover', 'filmmaker' ),
                  'group' => __('General', 'Filmmaker'),
                ),
                
                array(
                    'type' => 'attach_image',
                    'heading' => __( 'Image', 'filmmaker' ),
                    'param_name' => 'image',
                    'value' => '',
                    'description' => __( 'Select image from media library.', 'filmmaker' ),
                    'group' => __('Content', 'Filmmaker'),
                ),
                array(
                  'type' => 'checkbox',
                  'heading' => __( 'Link read More', 'filmmaker' ),
                  'param_name' => 'check_box_discover',
                  'value' => array(__('Yes','Filmmaker') => 'yes'),
                  'group' => __('Content', 'Filmmaker'),
                ),

                 array(
                  'type' => 'textfield',
                  'param_name' => 'link_to_discover',
                  'value' =>'#',
                  'group' => __('Content', 'Filmmaker'),
                ),
                 array(
                  'type' => 'colorpicker',
                  'heading' => __( 'Color link out', 'filmmaker' ),
                  'param_name' => 'color_linkto',
                  'group' => __('Content', 'Filmmaker'),
                ),
                array(
                  'type' => 'colorpicker',
                  'heading' => __( 'Background Link to', 'filmmaker' ),
                  'param_name' => 'bg_linkto',
                  'group' => __('Content', 'Filmmaker'),
                ), 

                array(
                    'type' => 'textarea',
                    'heading' => __( 'Short description', 'filmmaker' ),
                    'param_name' => 'short_description',
                    'group' => __('Content', 'Filmmaker'),
                ),
                array(
                  'type' => 'colorpicker',
                  'heading' => __( 'Color description section', 'filmmaker' ),
                  'param_name' => 'color_short_description',
                  'group' => __('Content', 'Filmmaker'),
                ),
                array(
                    'type' => 'textarea_html',
                    'heading' => __( 'Content', 'filmmaker' ),
                    'param_name' => 'content',
                    'group' => __('Content', 'Filmmaker'),
                ),


            ),
        ));
    }
    class WPBakeryShortCode_film_discover extends WPBakeryShortCode {}
}

 ?>