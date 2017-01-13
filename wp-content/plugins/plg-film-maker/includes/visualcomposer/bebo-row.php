<?php
//Add more option for Vituarcomposer row
if (!function_exists('bebo_containerCenter')) {
    add_action( 'vc_before_init', 'bebo_containerCenter', 999999);
    function bebo_containerCenter(){
      if(function_exists('vc_add_param')){
        vc_add_param('vc_row',array(
            'type' => 'dropdown',
            'heading' => __( 'Row stretch', 'filmmaker' ),
            'param_name' => 'full_width',
            'value' => array(
              __( 'Default', 'filmmaker' ) => '',
              __( 'Stretch row', 'filmmaker' ) => 'stretch_row',
              __( 'Stretch row and content', 'filmmaker' ) => 'stretch_row_content',
              __( 'Stretch row and content (no paddings)', 'filmmaker' ) => 'stretch_row_content_no_spaces',
              __( 'Stretch row with container', 'filmmaker' ) => 'stretch_row_content_no_spaces_content',
            ),
            'description' => __( 'Select stretching options for row and content (Note: stretched may not work properly if parent container has "overflow: hidden" CSS property).', 'filmmaker' )
            // "group" => __( 'Design options', 'bebo' ),
          )
        );

      }
    }
}
?>