<?php
//This for conver shortcode
if (!class_exists('WPBakeryShortCode_film_conver')) {
    add_action( 'vc_before_init', 'filmmaker_conver', 999999);
    function filmmaker_conver() {
        vc_map( array(
            "name" => __( "Show box film", "filmmaker" ),
            "base" => "film_conver",
            'weight' => 91,
            'description' => __( 'This section show conver section', 'filmmaker' ),
            "params" => array(
              //General setting
              array(
                'type'        => 'textfield',
                'heading'     => __( 'Title category type', 'filmmaker' ),
                'param_name'  => 'small_title',
                'description' => __( 'Main title', 'filmmaker' ),
                'group'       => __('General','filmmaker'),
              ),
              array(
                'type'        => 'textfield',
                'heading'     => __( 'Name Film ', 'filmmaker' ),
                'param_name'  => 'name_small_title',
                'group'       => __('General','filmmaker'),
              ),

              array(
                  'type'        => 'attach_image',
                  'heading'     => __( 'Image', 'filmmaker' ),
                  'param_name'  => 'image',
                  'value'       => '',
                  'description' => __( 'Select image from media library.', 'filmmaker' ),
                  'group'       => __('General','filmmaker'),
              ),
               array(
                'type'        => 'textfield',
                'heading'     => __( 'Input your link to page', 'filmmaker' ),
                'param_name'  => 'link_to',
                'value'       =>'#',
                'description' => __( 'Link to other', 'filmmaker' ),
                'group'       => __('General','filmmaker'),
              ),

              array(
                'type'        => 'textarea',
                'heading'     => __( 'Short description', 'filmmaker' ),
                'param_name'  => 'short_description',
                'group'       => __('General','filmmaker'),
              ),
              //Color Setting
              array(
                'type'        => 'colorpicker',
                'heading'     => __( 'Set color title category', 'filmmaker' ),
                'param_name'  => 'color_title_cat',
                'group'       => __('Color setting','filmmaker'),
              ),
              array(
                'type'        => 'colorpicker',
                'heading'     => __( 'Set color name film', 'filmmaker' ),
                'param_name'  => 'color_name_film',
                'group'       => __('Color setting','filmmaker'),
              ),
              array(
                'type'        => 'colorpicker',
                'heading'     => __( 'Set color description film', 'filmmaker' ),
                'param_name'  => 'color_desc_film',
                'group'       => __('Color setting','filmmaker'),
              ),
            ),
        ));
    }
    class WPBakeryShortCode_film_conver extends WPBakeryShortCode {}
}
?>