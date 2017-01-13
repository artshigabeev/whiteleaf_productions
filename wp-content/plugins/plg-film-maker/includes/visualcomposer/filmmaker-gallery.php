<?php
//This for gallery shortcode
if (!class_exists('WPBakeryShortCode_film_gallery')) {
    add_action( 'vc_before_init', 'filmmaker_gallery', 999999);
    function filmmaker_gallery() {
        vc_map( array(
            "name" => __( "Gallery Film", "filmmaker" ),
            "base" => "film_gallery",
            'weight' => 91,
            'description' => __( 'This section show gallery section', 'filmmaker' ),
            "params" => array(
                //General
                array(
                  'type' => 'textfield',
                  'heading' => __( 'Title section', 'filmmaker' ),
                  'param_name' => 'title_section',
                  'value' => __('We love film ..','filmmaker'),
                  'group' => __('General', 'filmmaker'),
                ),
                array(
                  'type' => 'textfield',
                  'heading' => __( 'Small title', 'filmmaker' ),
                  'param_name' => 'small_title',
                  'value' => __('Gallery .','filmmaker'),
                   'group' => __('General', 'filmmaker'),
                ),
                array(
                  'type' => 'textfield',
                  'heading' => __( 'Link to', 'filmmaker' ),
                  'param_name' => 'link_to',
                  'value' =>'#',
                  'description' => __( 'Link to other', 'filmmaker' ),
                  'group' => __('General', 'filmmaker'),
                ),
                array(
                  'type' => 'dropdown',
                  'heading' => __( 'Style gallery', 'filmmaker' ),
                  'param_name' => 'style_gallery',
                  'value' => array(
                        'Type gallery Image 1' =>0,
                        'Type gallery Image 2' =>1,
                    ),
                  'description' => __( 'Choose style gallery', 'filmmaker' ),
                  'group' => __('General', 'filmmaker'),
                ),
                array(
                    'type'              => 'autocomplete',
                    'heading'           => __( 'Select film show', 'filmmaker' ),
                    'param_name'        => 'gallery_list_id',
                    'settings'          => array(
                        'multiple'      => true,
                        'sortable'      => true,
                        'min_length'    => 1,
                        'no_hide'       => true, // In UI after select doesn't hide an select list
                        'groups'        => true, // In UI show results grouped by groups
                        'unique_values' => true, // In UI show results except selected. NB! You should manually check values in backend
                        'display_inline'=> true, // In UI show results inline view
                        'values'        => beau_get_post('gallery'),
                    ),
                    'description'   => __( 'Allow max 6 post', 'filmmaker' ),
                    'group'         => __( 'General', 'filmmaker' ),
                ),

                // //Group link1
                // array(
                //     'type' => 'attach_image',
                //     'heading' => __( 'Image', 'filmmaker' ),
                //     'param_name' => 'image1',
                //     'value' => '',
                //     'description' => __( 'Select image from media library.', 'filmmaker' ),
                //      'group' => __('Group 1','filmmaker'),
                // ),
                // array(
                //   'type' => 'textfield',
                //   'heading' => __( 'Link to post gallery', 'filmmaker' ),
                //   'param_name' => 'link_post1',
                //   'value' =>'#',
                //   'description' => __( 'Link to other', 'filmmaker' ),
                //   'group' => __('Group 1', 'filmmaker'),
                // ),
                // //Group link2
                // array(
                //     'type' => 'attach_image',
                //     'heading' => __( 'Image', 'filmmaker' ),
                //     'param_name' => 'image2',
                //     'value' => '',
                //     'description' => __( 'Select image from media library.', 'filmmaker' ),
                //      'group' => __('Group 1','filmmaker'),
                // ),
                // array(
                //   'type' => 'textfield',
                //   'heading' => __( 'Link to post gallery', 'filmmaker' ),
                //   'param_name' => 'link_post2',
                //   'value' =>'#',
                //   'description' => __( 'Link to other', 'filmmaker' ),
                //   'group' => __('Group 1','filmmaker'),
                // ),
                // //Group link3
                // array(
                //     'type' => 'attach_image',
                //     'heading' => __( 'Image', 'filmmaker' ),
                //     'param_name' => 'image3',
                //     'value' => '',
                //     'description' => __( 'Select image from media library.', 'filmmaker' ),
                //      'group' => __('Group 1','filmmaker'),
                // ),
                // array(
                //   'type' => 'textfield',
                //   'heading' => __( 'Link to post gallery', 'filmmaker' ),
                //   'param_name' => 'link_post3',
                //   'value' =>'#',
                //   'description' => __( 'Link to other', 'filmmaker' ),
                //   'group' => __('Group 1','filmmaker'),
                // ),
                // //Group link4
                // array(
                //     'type' => 'attach_image',
                //     'heading' => __( 'Image', 'filmmaker' ),
                //     'param_name' => 'image4',
                //     'value' => '',
                //     'description' => __( 'Select image from media library.', 'filmmaker' ),
                //      'group' => __('Group 1','filmmaker'),
                // ),
                // array(
                //   'type' => 'textfield',
                //   'heading' => __( 'Link to post gallery', 'filmmaker' ),
                //   'param_name' => 'link_post4',
                //   'value' =>'#',
                //   'description' => __( 'Link to other', 'filmmaker' ),
                //   'group' => __('Group 1','filmmaker'),
                // ),
                // //Group link5
                // array(
                //     'type' => 'attach_image',
                //     'heading' => __( 'Image', 'filmmaker' ),
                //     'param_name' => 'image5',
                //     'value' => '',
                //     'description' => __( 'Select image from media library.', 'filmmaker' ),
                //      'group' => __('Group 1','filmmaker'),
                // ),
                // array(
                //   'type' => 'textfield',
                //   'heading' => __( 'Link to post gallery', 'filmmaker' ),
                //   'param_name' => 'link_post5',
                //   'value' =>'#',
                //   'description' => __( 'Link to other', 'filmmaker' ),
                //   'group' => __('Group 1','filmmaker'),
                // ),
                // //Group link6
                // array(
                //     'type' => 'attach_image',
                //     'heading' => __( 'Image', 'filmmaker' ),
                //     'param_name' => 'image6',
                //     'value' => '',
                //     'description' => __( 'Select image from media library.', 'filmmaker' ),
                //      'group' => __('Group 1','filmmaker'),
                // ),
                // array(
                //   'type' => 'textfield',
                //   'heading' => __( 'Link to post gallery', 'filmmaker' ),
                //   'param_name' => 'link_post6',
                //   'value' =>'#',
                //   'description' => __( 'Link to other', 'filmmaker' ),
                //   'group' => __('Group 1','filmmaker'),
                // ),
                // //Group link7
                // array(
                //     'type' => 'attach_image',
                //     'heading' => __( 'Image', 'filmmaker' ),
                //     'param_name' => 'image7',
                //     'value' => '',
                //     'description' => __( 'Select image from media library.', 'filmmaker' ),
                //      'group' => __('Group 1','filmmaker'),
                // ),
                // array(
                //   'type' => 'textfield',
                //   'heading' => __( 'Link to post gallery', 'filmmaker' ),
                //   'param_name' => 'link_post7',
                //   'value' =>'#',
                //   'description' => __( 'Link to other', 'filmmaker' ),
                //   'group' => __('Group 1','filmmaker'),
                // ),

            ),
        ));
    }
    class WPBakeryShortCode_film_gallery extends WPBakeryShortCode {}
}
?>
