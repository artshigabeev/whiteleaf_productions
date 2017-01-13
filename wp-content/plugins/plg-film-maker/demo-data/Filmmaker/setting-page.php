<?php
/**
 * Set front, blog page , menu
 */
function setting_page(){
    $page_setting = array(
        'front' => 'Home page',
        'blog' => ''
    );
    // menu array : menu_location => menu name
    $menu_setting = array(
        'main-menu'     => 'Main menu',
        'menu-nav-right'   => 'menu-nav-right',
        'menu-nav-left'    => 'menu-nav-left',
    );
    return array(
        'page_setting' => $page_setting,
        'menu_setting' => $menu_setting
    );
}
