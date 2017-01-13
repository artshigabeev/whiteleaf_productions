<?php
    if (!class_exists('WPBakeryShortCode_Film_crew')) {
        add_action( 'vc_before_init', 'film_director', 999999);
        function film_director() {
            for($i=1; $i<=20; $i++){
                $num_of_crew[$i]=$i;
            }
            $category_crew = array();
            $taxonomy = 'crew_category';
            $tax_terms = get_terms($taxonomy);
            foreach($tax_terms as $term){
               $category_crew[ $term->name ] = $term->term_id;
            }
            vc_map( array(
                "name" => __( "Show Crew section", "filmmaker" ),
                "base" => "film_crew",
                'weight' => 91,
                'description' => __( 'This section show crew', 'filmmaker' ),
                "params" => array(
                    //General
                    array(
                    'type' => 'dropdown',
                    'heading' => __( ' Select style show crew', 'filmmaker' ),
                    'param_name' => 'crew_select',
                    'value' => array(
                        'Display list crew' => '0',
                        'Display one crew' => '1',
                        'Display tab two crew' => '2',
                        'Display double crew' => '3',
                         ),
                    'description' => __( 'Show crew width style in dropdown', 'filmmaker' ),
                    'group' => __('General', 'filmmaker'),
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __( 'Select category crew display', 'filmmaker' ),
                        'param_name' => 'crew_id',
                        'value' => $category_crew,
                        'group' => __('General', 'filmmaker'),
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __( 'Show number  of style crew list', 'filmmaker' ),
                        'param_name' => 'crew_number',
                        'value' => $num_of_crew,
                        'std' => 4,
                        'group' => __('General', 'filmmaker'),
                    ),
                    //Custom for crew list
                    array(
                        'type' => 'checkbox',
                        'heading' => __( 'Use this field', 'filmmaker' ),
                        'param_name' => 'crewlist_checkbox',
                        'description' => __( 'If checked, this field display in crew ', 'filmmaker' ),
                        'value' => array( __( 'Yes', 'filmmaker' ) => 'yes' ),
                        'group' => __('Custom for crew list', 'filmmaker'),
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __( 'Show text field ', 'filmmaker' ),
                        'param_name' => 'crewlist_textfield',
                        'group' => __('Custom for crew list', 'filmmaker'),
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __( 'Show text Link ', 'filmmaker' ),
                        'param_name' => 'crewlist_textlink',
                        'group' => __('Custom for crew list', 'filmmaker'),
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __( 'Input link ', 'filmmaker' ),
                        'param_name' => 'crewlist_link',
                        'group' => __('Custom for crew list', 'filmmaker'),
                    ),
                    array(
                        'type' => 'attach_image',
                        'heading' => __( 'Choose image display ', 'filmmaker' ),
                        'param_name' => 'crewlist_image',
                        'group' => __('Custom for crew list', 'filmmaker'),
                    ),
                    //Change color for style one crew
                    array(
                        'type' => 'colorpicker',
                        'heading' => __( 'Color change Crew Name ', 'filmmaker' ),
                        'param_name' => 'color_one_name',
                        'group' => __('Color style one crew', 'filmmaker'),
                    ),
                    array(
                        'type' => 'colorpicker',
                        'heading' => __( 'Change Color Crew Job ', 'filmmaker' ),
                        'param_name' => 'color_one_job',
                        'group' => __('Color style one crew', 'filmmaker'),
                    ),
                    array(
                        'type' => 'colorpicker',
                        'heading' => __( 'Change Color Crew Description ', 'filmmaker' ),
                        'param_name' => 'color_one_desc',
                        'group' => __('Color style one crew', 'filmmaker'),
                    ),
                    array(
                        'type' => 'colorpicker',
                        'heading' => __( 'Change Color Crew Social ', 'filmmaker' ),
                        'param_name' => 'color_one_social',
                        'group' => __('Color style one crew', 'filmmaker'),
                    ),
                    array(
                        'type' => 'colorpicker',
                        'heading' => __( 'Change background Crew Social ', 'filmmaker' ),
                        'param_name' => 'bg_one_social',
                        'group' => __('Color style one crew', 'filmmaker'),
                    ),
                    array(
                        'type' => 'colorpicker',
                        'heading' => __( 'Hover change Color Crew Social ', 'filmmaker' ),
                        'param_name' => 'hover_color_one_social',
                        'group' => __('Color style one crew', 'filmmaker'),
                    ),
                    array(
                        'type' => 'colorpicker',
                        'heading' => __( 'Hover change Color Crew Social ', 'filmmaker' ),
                        'param_name' => 'hover_bg_one_social',
                        'group' => __('Color style one crew', 'filmmaker'),
                    ), 
                    
                    array(
                        'type' => 'colorpicker',
                        'heading' => __( 'Color change Crew Name ', 'filmmaker' ),
                        'param_name' => 'color_tab_name',
                        'group' => __('Color style tabs crew', 'filmmaker'),
                    ),
                    array(
                        'type' => 'colorpicker',
                        'heading' => __( 'Change Color Crew Job ', 'filmmaker' ),
                        'param_name' => 'color_tab_job',
                        'group' => __('Color style tabs crew', 'filmmaker'),
                    ),
                    array(
                        'type' => 'colorpicker',
                        'heading' => __( 'Change Color Crew Description ', 'filmmaker' ),
                        'param_name' => 'color_tab_desc',
                        'group' => __('Color style tabs crew', 'filmmaker'),
                    ),
                    array(
                        'type' => 'colorpicker',
                        'heading' => __( 'Change Color Crew Social ', 'filmmaker' ),
                        'param_name' => 'color_tab_social',
                        'group' => __('Color style tabs crew', 'filmmaker'),
                    ),
                    array(
                        'type' => 'colorpicker',
                        'heading' => __( 'Change background Crew Social ', 'filmmaker' ),
                        'param_name' => 'bg_tab_social',
                        'group' => __('Color style tabs crew', 'filmmaker'),
                    ),
                    array(
                        'type' => 'colorpicker',
                        'heading' => __( 'Hover change Color Crew Social ', 'filmmaker' ),
                        'param_name' => 'hover_color_tab_social',
                        'group' => __('Color style tabs crew', 'filmmaker'),
                    ),
                    array(
                        'type' => 'colorpicker',
                        'heading' => __( 'Hover change Color Crew Social ', 'filmmaker' ),
                        'param_name' => 'hover_bg_tab_social',
                        'group' => __('Color style tabs crew', 'filmmaker'),
                    ), 
                    //Change color for style crew list
                    array(
                        'type' => 'colorpicker',
                        'heading' => __( 'Color change Crew Name ', 'filmmaker' ),
                        'param_name' => 'color_list_name',
                        'group' => __('Color style lists crew', 'filmmaker'),
                    ),
                    array(
                        'type' => 'colorpicker',
                        'heading' => __( 'Change Color Crew Job ', 'filmmaker' ),
                        'param_name' => 'color_list_job',
                        'group' => __('Color style lists crew', 'filmmaker'),
                    ),
                    array(
                        'type' => 'colorpicker',
                        'heading' => __( 'Change Color Crew Description ', 'filmmaker' ),
                        'param_name' => 'color_list_desc',
                        'group' => __('Color style lists crew', 'filmmaker'),
                    ),
                    array(
                        'type' => 'colorpicker',
                        'heading' => __( 'Change Color Crew Social ', 'filmmaker' ),
                        'param_name' => 'color_list_social',
                        'group' => __('Color style lists crew', 'filmmaker'),
                    ),
                    array(
                        'type' => 'colorpicker',
                        'heading' => __( 'Change background Crew Social ', 'filmmaker' ),
                        'param_name' => 'bg_list_social',
                        'group' => __('Color style lists crew', 'filmmaker'),
                    ),
                    array(
                        'type' => 'colorpicker',
                        'heading' => __( 'Hover change Color Crew Social ', 'filmmaker' ),
                        'param_name' => 'hover_color_list_social',
                        'group' => __('Color style lists crew', 'filmmaker'),
                    ),
                    array(
                        'type' => 'colorpicker',
                        'heading' => __( 'Hover change Color Crew Social ', 'filmmaker' ),
                        'param_name' => 'hover_bg_list_social',
                        'group' => __('Color style lists crew', 'filmmaker'),
                    ),     
                ),
            ) );
        }
        class WPBakeryShortCode_Film_crew extends WPBakeryShortCode {}
    }

 ?>