<?php
   if (!class_exists('WPBakeryShortCode_Film_blog')) {

//This for testimonial
    add_action( 'vc_before_init', 'film_blog', 999999);
    function film_blog() {
        for($i=1; $i<=50; $i++){
            $film_number_post[$i] = $i;
        }
        $list_column = array(
            'Two post in row' => 'col-md-6',
            'Three post in row' => 'col-md-4',
            'Four post in row' => 'col-md-3',
            'Six post in row' => 'col-md-2',
            );
        vc_map( array(
            "name"        => __( "Show Blog section", "filmmaker" ),
            "base"        => "film_blog",
            'weight'      => 91,
            'description' => __( 'This section show post list', 'filmmaker' ),
            "params"      => array(
              //General setting
              array(
                'type' => 'dropdown',
                'heading' => __( 'Display post not width thumbnail', 'filmmaker' ),
                'param_name' => 'film_style',
                'description' => __( 'Display post not width thumbnail', 'filmmaker' ),
                'value' => array(
                    'Show type with image' => '0',
                    'Show type not image'  => '1',
                    'Show type with image text center' => '2',
                 ),
                'group' => __('General','filmmaker'),
              ),
              array(
                'type'        => 'dropdown',
                'heading'     => __( 'Show number posts', 'filmmaker' ),
                'param_name'  => 'film_number_post',
                'value'       => $film_number_post,
                'description' => __( 'Number to show your post', 'filmmaker' ),
                'group' => __('General','filmmaker'),
              ),
              array(
                'type'       => 'dropdown',
                'heading'    => __('Show number post in row for display blog width thumnail', 'filmmaker'),
                'param_name' => 'film_column_in_row',
                'value'      => $list_column,
                'std'        => '4',
                'description' => __( ' Show title blog', 'filmmaker' ),
                'group' => __('General','filmmaker'),
              ),
              // Color Setting
              array(
                'type'       => 'colorpicker',
                'heading'    => __('Set color title blog', 'filmmaker'),
                'param_name' => 'color_title_blog',
                'group' => __('Color setting','filmmaker'),
              ),
              array(
                'type'       => 'colorpicker',
                'heading'    => __('Set color date time and view and description ', 'filmmaker'),
                'param_name' => 'color_title_dv',
                'group' => __('Color setting','filmmaker'),
              ),
              array(
                'type'       => 'colorpicker',
                'heading'    => __('Set color hover ', 'filmmaker'),
                'param_name' => 'color_hover_all',
                'group' => __('Color setting','filmmaker'),
              ),
            ),
        ) );
    }
    class WPBakeryShortCode_Film_blog extends WPBakeryShortCode {}
}


 ?>