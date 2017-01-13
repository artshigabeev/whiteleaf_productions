<?php
if (!class_exists('WPBakeryShortCode_Film_team_list)')) {
//This for section countdown
    add_action( 'vc_before_init', 'film_Team', 999999);
    function film_Team() {
        for($i=1; $i<=50; $i++){
            $team_number_display[$i] = $i;
        }
        vc_map( array(
            "name" => __( "Show Crew List", "filmmaker" ),
            "base" => "film_team_list",
            'weight' => 91,
            'description' => __( 'This section show crew list', 'filmmaker' ),
            "params" => array(
                 
                 array(
                  'type' => 'dropdown',
                  'heading' => __( 'Show number crew display', 'filmmaker' ),
                  'param_name' => 'team_num',
                  'value' => $team_number_display,
                  'std' => 10,
                  'group' => __( 'General', 'filmmaker' ),
                ),
                 //color
                
                array(
                  'type' => 'colorpicker',
                  'heading' => __( 'Color Name', 'filmmaker' ),
                  'param_name' => 'color_name',
                  'group' => __( 'Color', 'filmmaker' ),
                ),
                array(
                  'type' => 'colorpicker',
                  'heading' => __( 'Color job', 'filmmaker' ),
                  'param_name' => 'color_job',
                  'group' => __( 'Color', 'filmmaker' ),
                ),
                array(
                  'type' => 'colorpicker',
                  'heading' => __( 'Color Social', 'filmmaker' ),
                  'param_name' => 'color_social',
                  'group' => __( 'Color', 'filmmaker' ),
                ),
                array(
                  'type' => 'colorpicker',
                  'heading' => __( 'Background Social', 'filmmaker' ),
                  'param_name' => 'bg_social',
                  'group' => __( 'Color', 'filmmaker' ),
                ),
                array(
                  'type' => 'colorpicker',
                  'heading' => __( 'Hover Color Social', 'filmmaker' ),
                  'param_name' => 'hover_color_social',
                  'group' => __( 'Color', 'filmmaker' ),
                ),
                array(
                  'type' => 'colorpicker',
                  'heading' => __( 'Hover Background Social', 'filmmaker' ),
                  'param_name' => 'hover_bg_social',
                  'group' => __( 'Color', 'filmmaker' ),
                ),
            ),
        ) );
    }
    class WPBakeryShortCode_Film_team_list extends WPBakeryShortCode {}
}
?>