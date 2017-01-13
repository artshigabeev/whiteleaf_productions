<?php
if (!class_exists('WPBakeryShortCode_Film_video')) {

    add_action( 'vc_before_init', 'film_Video', 999999);
    function beau_get_post( $post_type = 'post' ) {
      $posts = get_posts( array(
        'posts_per_page'  => -1,
        'post_type'     => $post_type,
      ));
      $result = array();
      foreach ( $posts as $post ) {
        $result[] = array(
          'value' => $post->ID,
          'label' => $post->post_title,
        );
      }
      return $result;
    }
    function film_Video() {
        vc_map( array(
            "name" => __( "Show Film List", "filmmaker" ),
            "base" => "film_video",
            'weight' => 91,
            'description' => __( 'This section show film list', 'filmmaker' ),
            "params" => array(
              //General
              array(
                'type'          => 'textfield',
                'heading'       => __( 'Show small title film list', 'filmmaker' ),
                'param_name'    => 'small_title_product_list',
                'group'         => __( 'General', 'filmmaker' ),
              ),
               array(
                'type'          => 'textfield',
                'heading'       => __( 'Show big title film list', 'filmmaker' ),
                'param_name'    => 'big_title_product_list',
                'group'         => __( 'General', 'filmmaker' ),
              ),
              array(
                'type'          => 'dropdown',
                'heading'       => __( 'Display film style odd', 'filmmaker' ),
                'param_name'    => 'film_style',
                'value'         => array(
                      'Show type default'   => '0',
                      'Show type odd'       => '1',
                      'Show type full '     => '2',
                      'Show only 4 items'   => '3',
                 ),
                'group'         => __( 'General', 'filmmaker' ),
              ),
              array(
                'type'              => 'autocomplete',
                'heading'           => __( 'Select film show', 'filmmaker' ),
                'param_name'        => 'film_list_id',
                'settings'          => array(
                    'multiple'      => true,
                    'sortable'      => true,
                    'min_length'    => 1,
                    'no_hide'       => true, // In UI after select doesn't hide an select list
                    'groups'        => true, // In UI show results grouped by groups
                    'unique_values' => true, // In UI show results except selected. NB! You should manually check values in backend
                    'display_inline'=> true, // In UI show results inline view
                    'values'        => beau_get_post('film')
                ),
                'description'   => __( 'Type title post to choose', 'filmmaker' ),
                'group'         => __( 'General', 'filmmaker' ),
              ),
              array(
                'type'          => 'textfield',
                'heading'       => __( 'Link View More Film', 'filmmaker' ),
                'param_name'    => 'view_more_text',
                'group'         => __( 'General', 'filmmaker' ),
              ),
              array(
                'type'          => 'checkbox',
                'heading'       => __( 'Display view more film', 'filmmaker' ),
                'param_name'    => 'film_list_checkbox_link',
                'description'   => __( 'If checked, this field display view more film  ', 'filmmaker' ),
                'value'         => array( __( 'Yes', 'filmmaker' ) => 'yes' ),
                'group'         => __( 'General', 'filmmaker' ),
              ),
              array(
                'type'          => 'textfield',
                'heading'       => __( 'Link VieW More Film', 'filmmaker' ),
                'param_name'    => 'view_more_url',
                'value'         => '#',
                'group'         => __( 'General', 'filmmaker' ),
              ),
              //Setting Color
              array(
                'type'          => 'colorpicker',
                'heading'       => __( 'Set Color small title', 'filmmaker' ),
                'param_name'    => 'color_small_title',
                'group'         => __( 'Setting color', 'filmmaker' ),
              ),
              array(
                'type'          => 'colorpicker',
                'heading'       => __( 'Set Color big title', 'filmmaker' ),
                'param_name'    => 'color_big_title',
                'group'         => __( 'Setting color', 'filmmaker' ),
              ),
              array(
                'type'          => 'colorpicker',
                'heading'       => __( 'Set Color view more', 'filmmaker' ),
                'param_name'    => 'color_viewmore',
                'group'         => __( 'Setting color', 'filmmaker' ),
              ),
              array(
                'type'          => 'colorpicker',
                'heading'       => __( 'Set border color view more', 'filmmaker' ),
                'param_name'    => 'color_border_viewmore',
                'group'         => __( 'Setting color', 'filmmaker' ),
              ),
              array(
                'type'          => 'colorpicker',
                'heading'       => __( 'Set hover Color view more', 'filmmaker' ),
                'param_name'    => 'hover_color_viewmore',
                'group'         => __( 'Setting color', 'filmmaker' ),
              ),
              array(
                'type'          => 'colorpicker',
                'heading'       => __( 'Set hover background view more', 'filmmaker' ),
                'param_name'    => 'hover_background_viewmore',
                'group'         => __( 'Setting color', 'filmmaker' ),
              ),
              //Set Color Content film
              array(
                'type'          => 'colorpicker',
                'heading'       => __( 'Set color category film', 'filmmaker' ),
                'param_name'    => 'color_category_film',
                'group'         => __( 'Set Color Content film', 'filmmaker' ),
              ),
              array(
                'type'          => 'colorpicker',
                'heading'       => __( 'Set Color Name film', 'filmmaker' ),
                'param_name'    => 'color_name_film',
                'group'         => __( 'Set Color Content film', 'filmmaker' ),
              ),
              array(
                'type'          => 'colorpicker',
                'heading'       => __( 'Set Color description film', 'filmmaker' ),
                'param_name'    => 'color_description_film',
                'group'         => __( 'Set Color Content film', 'filmmaker' ),
              ),
              array(
                'type'          => 'colorpicker',
                'heading'       => __( 'Set Color link view Detail film', 'filmmaker' ),
                'param_name'    => 'color_link_film',
                'group'         => __( 'Set Color Content film', 'filmmaker' ),
              ),
              array(
                'type'          => 'colorpicker',
                'heading'       => __( 'Set border Color link view Detail film', 'filmmaker' ),
                'param_name'    => 'color_border_link_film',
                'group'         => __( 'Set Color Content film', 'filmmaker' ),
              ),
              array(
                'type'          => 'colorpicker',
                'heading'       => __( 'Set color hover link view Detail film', 'filmmaker' ),
                'param_name'    => 'hover_color_link_film',
                'group'         => __( 'Set Color Content film', 'filmmaker' ),
              ),
              array(
                'type'          => 'colorpicker',
                'heading'       => __( 'Set background hover link view Detail film', 'filmmaker' ),
                'param_name'    => 'hover_background_link_film',
                'group'         => __( 'Set Color Content film', 'filmmaker' ),
              ),
            ),
        ) );
    }
    class WPBakeryShortCode_Film_video extends WPBakeryShortCode {}
}
?>
