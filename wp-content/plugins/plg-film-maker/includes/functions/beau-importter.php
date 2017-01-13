<?php

if (is_file(BEAU_PLUGIN_DIR . '/classes/class-msp-importer.php')) {
    include(BEAU_PLUGIN_DIR . '/classes/class-msp-importer.php');
}   
if (!function_exists('beau_after_import_settings')) {

    function beau_after_import_settings($demo_active_import, $demo_directory_path) {
        reset($demo_active_import);

        //clean up default widget data

        $widgets = get_option('sidebars_widgets');
        foreach ($widgets as $key => $value) {
            $widgets[$key] = array();
        }
        update_option('sidebars_widgets', $widgets, true);

        // key
        $current_key = key($demo_active_import);
        /**
         * Setting Menu
         */
        // echo $demo_active_import[$current_key]['directory'];
        $wbc_menu_array = array('Filmmaker');
        if (isset($demo_active_import[$current_key]['directory']) &&
                !empty($demo_active_import[$current_key]['directory']) &&
                in_array($demo_active_import[$current_key]['directory'], $wbc_menu_array)) {
            $top_menu = get_term_by('name', 'Main menu', 'main-menu');
            $small_menu = get_term_by('name', 'Menu Nav Right', 'menu-nav-right');
            $mobile_menu = get_term_by('name', 'Menu Nav Left', 'menu-nav-left');
            // echo "Menu";
            if (isset($top_menu->term_id)) {
                set_theme_mod('nav_menu_locations', array(
                    'main-menu' => $top_menu->term_id,
                    'menu-nav-right' => $small_menu->term_id,
                    'menu-nav-left' => $mobile_menu->term_id,
                        )
                );
            }
        }


        /**
         * Import Master slider
         */
        if (class_exists('MSP_Importer_Extens')) {
            //If it's demo3 or demo5
            $wbc_sliders_array = array(
                'Filmmaker' => 'slider.json',
                
            );
            if (isset($demo_active_import[$current_key]['directory']) && !empty($demo_active_import[$current_key]['directory']) && array_key_exists($demo_active_import[$current_key]['directory'], $wbc_sliders_array)) {
                $wbc_slider_import = $wbc_sliders_array[$demo_active_import[$current_key]['directory']];
                if (file_exists($demo_directory_path . $wbc_slider_import)) {
                    echo "Slider";
                    $masterSliderDemo = new MSP_Importer_Extens;
                    $fileJsonSlider = file_get_contents($demo_directory_path . $wbc_slider_import);
                    $masterSliderDemo->import_data($fileJsonSlider);
                }
            }
        }
        
        /**
         * Setting Home Page
         */
        $wbc_home_pages = array(
            'Filmmaker' => 'Home page',
        );
        if (isset($demo_active_import[$current_key]['directory']) && !empty($demo_active_import[$current_key]['directory']) && array_key_exists($demo_active_import[$current_key]['directory'], $wbc_home_pages)) {
            $page = get_page_by_title($wbc_home_pages[$demo_active_import[$current_key]['directory']]);
            //echo "Home page";
            if (isset($page->ID)) {
                update_option('page_on_front', $page->ID);
                update_option('show_on_front', 'page');
            }
        }
    }

    add_action('wbc_importer_after_theme_options_import', 'beau_after_import_settings', 100, 12);
}// End Setting Menu, Slider and Hompage
if (!function_exists('beautheme_demo_directory_path')) {

    function beautheme_demo_directory_path($demo_directory_path) {
        $demo_directory_path = BEAU_PLUGIN_DIR . '/demo-data/';
        return $demo_directory_path;
    }

    add_filter('wbc_importer_dir_path', 'beautheme_demo_directory_path');
}
