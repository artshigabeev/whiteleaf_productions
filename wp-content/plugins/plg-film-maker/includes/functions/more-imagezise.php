<?php
if ( function_exists('more_image_size') ) {

// Add theme support for this theme
    function more_image_size() {
        // For thumbnai and size image
        set_post_thumbnail_size( 518,340, true);
        add_image_size('page-cover',1368,500, true);
        add_image_size('page-cover-player',1368,700, true);
        add_image_size('main-thumbnail',345,520, true);
        add_image_size('book-thumbnail',100,150, true);
        add_image_size('book-other-thumbnail',240,365, true);
        add_image_size('post-thumbnail',525,340, true);
        add_image_size('post-other-thumbnail',245,140, true);
        add_image_size('small-play-thumbnail',603,390, true);
        // Theme support with nav menu
        add_theme_support( "nav-menus" );
        $nav_menus['main-menu'] = __( 'Main menu', BEAU_THEME_DOMAIN);
        register_nav_menus( $nav_menus );
    }
    add_action( 'after_setup_theme', 'more_image_size' );
}
?>