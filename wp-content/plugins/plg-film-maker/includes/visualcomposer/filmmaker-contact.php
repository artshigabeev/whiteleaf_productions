<?php
if (!class_exists('WPBakeryShortCode_Film_contact')) {
//This for section countdown
    add_action( 'vc_before_init', 'film_Contact', 999999);
    function film_Contact() {
        $link_contact = array( );
        $args = array('post_type' => 'wpcf7_contact_form');
        $cf7Forms = get_posts( $args );
        if( $cf7Forms ){
             foreach($cf7Forms as $cf7Form){
               $link_contact[ $cf7Form->post_title ] = $cf7Form->ID;
            }
        }
        $map_style_data = array(
          'default' => '',
          'UnsaturatedBrowns' => '[{"elementType":"geometry","stylers":[{"hue":"#ff4400"},{"saturation":-68},{"lightness":-4},{"gamma":0.72}]},{"featureType":"road","elementType":"labels.icon"},{"featureType":"landscape.man_made","elementType":"geometry","stylers":[{"hue":"#0077ff"},{"gamma":3.1}]},{"featureType":"water","stylers":[{"hue":"#00ccff"},{"gamma":0.44},{"saturation":-33}]},{"featureType":"poi.park","stylers":[{"hue":"#44ff00"},{"saturation":-23}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"hue":"#007fff"},{"gamma":0.77},{"saturation":65},{"lightness":99}]},{"featureType":"water","elementType":"labels.text.stroke","stylers":[{"gamma":0.11},{"weight":5.6},{"saturation":99},{"hue":"#0091ff"},{"lightness":-86}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"lightness":-48},{"hue":"#ff5e00"},{"gamma":1.2},{"saturation":-23}]},{"featureType":"transit","elementType":"labels.text.stroke","stylers":[{"saturation":-64},{"hue":"#ff9100"},{"lightness":16},{"gamma":0.47},{"weight":2.7}]}]',

          'light-mono' => '[{"featureType":"water","elementType":"all","stylers":[{"visibility":"simplified"},{"hue":"#e9ebed"},{"saturation":-78},{"lightness":67}]},{"featureType":"landscape","elementType":"all","stylers":[{"visibility":"simplified"},{"hue":"#ffffff"},{"saturation":-100},{"lightness":100}]},{"featureType":"road","elementType":"geometry","stylers":[{"visibility":"simplified"},{"hue":"#bbc0c4"},{"saturation":-93},{"lightness":31}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"},{"hue":"#ffffff"},{"saturation":-100},{"lightness":100}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"visibility":"simplified"},{"hue":"#e9ebed"},{"saturation":-90},{"lightness":-8}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"on"},{"hue":"#e9ebed"},{"saturation":10},{"lightness":69}]},{"featureType":"administrative.locality","elementType":"all","stylers":[{"visibility":"on"},{"hue":"#2c2e33"},{"saturation":7},{"lightness":19}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"on"},{"hue":"#bbc0c4"},{"saturation":-93},{"lightness":31}]},{"featureType":"road.arterial","elementType":"labels","stylers":[{"visibility":"simplified"},{"hue":"#bbc0c4"},{"saturation":-93},{"lightness":-2}]}]',
        );
        vc_map( array(
            "name" => __( "Contact Info", "filmmaker" ),
            "base" => "film_contact",
            'weight' => 91,
            "params" => array(
            // Default setting
              array(
                'type' => 'dropdown',
                'heading' => __( 'choose  form ', 'filmmaker' ),
                'param_name' => 'contact_type',
                'value' => $link_contact,
                'group' => __('Generate' , 'filmmaker')
              ),
              array(
                'type' => 'dropdown',
                'heading' => __( 'Show type display form ', 'filmmaker' ),
                'param_name' => 'contact_show',
                  'value' => array(
                      '=== Choose type ===' => '',
                      'Type Contact Full' => 1,
                      'Type Contact Haft' => 2,
                      ),
                'group' => __('Generate' , 'filmmaker')
              ),
              array(
                'type' => 'dropdown',
                'heading' => __( 'choose map style ', 'filmmaker' ),
                'param_name' => 'map_style',
                'value' => $map_style_data,
                'group' => __('Generate' , 'filmmaker')
              ),
                array(
                'type' => 'checkbox',
                'heading' => __( 'Show Map', 'filmmaker' ),
                'param_name' => 'enable_map',                
                'instruction' => __('Do you want to show map ?' , 'filmmaker') ,
                 'group' => __('Generate' , 'filmmaker')   
              ),
              array(
                'type' => 'textfield',
                'heading' => __( 'Show title section ', 'filmmaker' ),
                'param_name' => 'contact_title',
                'group' => __('Generate' , 'filmmaker')
              ),
              array(
                'type' => 'textarea',
                'heading' => __( 'Your short text for subcribe', 'filmmaker' ),
                'param_name' => 'contact_description',
                  'group' => __('Generate' , 'filmmaker')
              ),
              // Map
              array(
                    'type' => 'textfield',
                    'heading' => __( 'Your coordinates', 'filmmaker' ),
                    'param_name' => 'contact_coordina',
                    'dependency' => array(
                        'element' => 'enable_map',
                        'value'   => 'true'
                    ),
                    'value' => '40.5800, -73.8700',
                    'group' => __('Map' , 'filmmaker')
              ),
              array(
                'type' => 'textfield',
                'heading' => __( 'Zoom level', 'filmmaker' ),
                'description' => __( 'Set default zoom for your map - must be number', 'filmmaker' ),
                'param_name' => 'map_zoom',
                'value' => '10',
                   'dependency' => array(
                      'element' => 'enable_map',
                      'value'   => 'true'
                  ),
                'group' => __('Map' , 'filmmaker')
              ),
              array(
                  'type' => 'attach_image',
                  'heading' => __( 'Image for coordinates', 'js_composer' ),
                  'param_name' => 'contact_image',
                  'value' => '',
                   'dependency' => array(
                      'element' => 'enable_map',
                      'value'   => 'true'
                  ),
                  'description' => __( 'Select image from media library.', 'js_composer' ),                  
                  'group' => __('Map' , 'filmmaker'),
              ),
          // info 1

              array(
                'type' => 'textfield',
                'heading' => __( 'Title Address info', 'filmmaker' ),
                'param_name' => 'contact_title_one',
                'group' => __('Info' , 'filmmaker')
              ),
              array(
                'type' => 'textfield',
                'heading' => __( 'Content Address info', 'filmmaker' ),
                'param_name' => 'contact_content_one',
                'group' => __('Info' , 'filmmaker')
              ),
              // info 2

              array(
                'type' => 'textfield',
                'heading' => __( 'Title Phone', 'filmmaker' ),
                'param_name' => 'contact_title_two',
                'value' =>'PHONE',
                'group' => __('Info 02' , 'filmmaker')
              ),
              array(
                'type' => 'textfield',
                'heading' => __( 'Phone Number', 'filmmaker' ),
                'param_name' => 'contact_content_two',
                'group' => __('Info 02' , 'filmmaker')
              ),
              // info 3
              array(
                'type' => 'textfield',
                'heading' => __( 'Title Email', 'filmmaker' ),
                'param_name' => 'contact_title_three',
                'group' => __('Info 03' , 'filmmaker')
              ),
              array(
                'type' => 'textfield',
                'heading' => __( 'Email Info', 'filmmaker' ),
                'param_name' => 'contact_content_three',
                'group' => __('Info 03' , 'filmmaker')
              ),
              // Set color INFO
              array(
                'type' => 'colorpicker',
                'heading' => __( 'Set color button change(show info/ show map)', 'filmmaker' ),
                'param_name' => 'color_change_show',
                'group' => __('Setting color' , 'filmmaker')
              ),
              array(
                'type' => 'colorpicker',
                'heading' => __( 'Set color title contact', 'filmmaker' ),
                'param_name' => 'color_title',
                'group' => __('Setting color' , 'filmmaker')
              ),
              array(
                'type' => 'colorpicker',
                'heading' => __( 'Set color contact information', 'filmmaker' ),
                'param_name' => 'color_info',
                'group' => __('Setting color' , 'filmmaker')
              ),
              array(
                'type' => 'colorpicker',
                'heading' => __( 'Set color contact description', 'filmmaker' ),
                'param_name' => 'color_desc',
                'group' => __('Setting color' , 'filmmaker')
              ),
              array(
                'type' => 'colorpicker',
                'heading' => __( 'Set color contact social', 'filmmaker' ),
                'param_name' => 'color_social',
                'group' => __('Setting color' , 'filmmaker')
              ),
              array(
                'type' => 'colorpicker',
                'heading' => __( 'Set hover color contact social', 'filmmaker' ),
                'param_name' => 'color_hover_social',
                'group' => __('Setting color' , 'filmmaker')
              ),
              
              array(
                'type' => 'colorpicker',
                'heading' => __( 'Set color input text contact form', 'filmmaker' ),
                'param_name' => 'color_input_text',
                'group' => __('Setting color' , 'filmmaker')
              ),
              array(
                'type' => 'colorpicker',
                'heading' => __( 'Set color submit form', 'filmmaker' ),
                'param_name' => 'color_submit',
                'group' => __('Setting color' , 'filmmaker')
              ),
              
              array(
                'type' => 'colorpicker',
                'heading' => __( 'Set color error contact form', 'filmmaker' ),
                'param_name' => 'color_error',
                'group' => __('Setting color' , 'filmmaker')
              ),
              
              //Setting background
              array(
                'type' => 'colorpicker',
                'heading' => __( 'Set background button change(show info/ show map)', 'filmmaker' ),
                'param_name' => 'bg_change_show',
                'group' => __('Setting background' , 'filmmaker')
              ),
              array(
                'type' => 'colorpicker',
                'heading' => __( 'Set background contact social', 'filmmaker' ),
                'param_name' => 'bg_social',
                'group' => __('Setting background' , 'filmmaker')
              ),
              array(
                'type' => 'colorpicker',
                'heading' => __( 'Set hover background contact social', 'filmmaker' ),
                'param_name' => 'bg_hover_social',
                'group' => __('Setting background' , 'filmmaker')
              ),
              array(
                'type' => 'colorpicker',
                'heading' => __( 'Set background submit form', 'filmmaker' ),
                'param_name' => 'bg_submit',
                'group' => __('Setting background' , 'filmmaker')
              ),
              array(
                'type' => 'colorpicker',
                'heading' => __( 'Set background contact form', 'filmmaker' ),
                'param_name' => 'bg_all_form',
                'group' => __('Setting background' , 'filmmaker')
              ),
            ),
        ) );
    }
    class WPBakeryShortCode_Film_contact extends WPBakeryShortCode {}
}
?>