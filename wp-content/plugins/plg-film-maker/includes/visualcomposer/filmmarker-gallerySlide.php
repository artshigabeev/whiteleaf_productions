<?php
//This for discover shortcode
if (!class_exists('WPBakeryShortCode_film_gallerySlider')) {
    add_action( 'vc_before_init', 'film_galleryslide', 999999);
    function film_galleryslide() {
        $g = array();
        $args = array(
            'post_type' => 'gallery',
            'taxonomy' =>'film_gallery',
            );

        $galleries = get_posts($args);
         if( $galleries ){
             foreach( $galleries as $gall){
               $g[$gall->post_name] = $gall->ID;
            }
        }
        vc_map( array(
            "name" => __( "Gallery Slider", "filmmaker" ),
            "base" => "film_gallerySlider",
            'weight' => 91,
            'description' => __( 'This section show discover section', 'filmmaker' ),
            "params" => array(
                array(
                  'type' => 'dropdown',
                  'heading' => __( 'Choose Gallery Film', 'filmmaker' ),
                  'param_name' => 'style_discover',
                  'value' => $g,
                  'description' => __( 'Choose style discover', 'filmmaker' ),
                ),
            ),
        ));
    }
    class WPBakeryShortCode_film_gallerySlider extends WPBakeryShortCode {}
}

?>