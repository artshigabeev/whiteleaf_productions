<?php
if (!class_exists('WPBakeryShortCode_film_instagram')) {
    add_action( 'vc_before_init', 'filmmaker_instagram', 999999);
    function filmmaker_instagram() {
        // for($i=0; $i<=8; $i++){
        //   $bebo_perpage_arr[$i] = $i;
        // }
        vc_map( array(
            "name" => __( "Instagram show", "filmmaker" ),
            "base" => "film_instagram",
            'weight' => 91,
            'description' => __( 'This section show instagram image in grid', 'filmmaker' ),
            "params" => array(
                array(
                  'type' => 'textfield',
                  'heading' => __( 'Your text on top', 'filmmaker' ),
                  'param_name' => 'title_section',
                  'description' => __( 'This author Instagram link.', 'filmmaker' ),
                ),
                array(
                  'type' => 'textfield',
                  'heading' => __( 'Your instagram user', 'filmmaker' ),
                  'param_name' => 'instagram_user',
                  'description' => __( 'This author Instagram link.', 'filmmaker' ),
                ),
                // array(
                //   'type' => 'dropdown',
                //   'heading' => __( 'Show images', 'filmmaker' ),
                //   'param_name' => 'perpage',
                //   'value' => $bebo_perpage_arr,
                //   'std' => 3,
                //   'admin_label' => true,
                //   'description' => __( 'Select columns count.', 'filmmaker' )
                // ),
            ),
        ) );
    }
    class WPBakeryShortCode_film_instagram extends WPBakeryShortCode {}
}

?>