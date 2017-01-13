<?php

function gallery_post_type() {

    $labels = array(
        'name'                  => _x( 'Gallery', 'Post Type General Name', 'filmmaker' ),
        'singular_name'         => _x( 'Gallery', 'Post Type Singular Name', 'filmmaker' ),
        'menu_name'             => __( 'Gallery', 'filmmaker' ),
        'name_admin_bar'        => __( 'Gallery', 'filmmaker' ),
        'parent_item_colon'     => __( '', 'filmmaker' ),
        'all_items'             => __( 'All Gallery', 'filmmaker' ),
        'add_new_item'          => __( 'Add New Gallery', 'filmmaker' ),
        'add_new'               => __( 'Add New Gallery', 'filmmaker' ),
        'new_item'              => __( 'New Gallery', 'filmmaker' ),
        'edit_item'             => __( 'Edit Gallery', 'filmmaker' ),
        'update_item'           => __( 'Update Gallery', 'filmmaker' ),
        'view_item'             => __( 'View Gallery', 'filmmaker' ),
        'search_items'          => __( 'Search Gallery', 'filmmaker' ),
        'not_found'             => __( 'Not found Gallery', 'filmmaker' ),
        'not_found_in_trash'    => __( 'Not found garllery in Trash', 'filmmaker' ),
        'items_list'            => __( 'Gallery list', 'filmmaker' ),
        'items_list_navigation' => __( 'Gallery list navigation', 'filmmaker' ),
        'filter_items_list'     => __( 'Filter Gallery list', 'filmmaker' ),
    );
    $args = array(
        'label'                 => __( 'Gallery', 'filmmaker' ),
        'description'           => __( 'Post Type Gallery Description', 'filmmaker' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'thumbnail', 'page-attributes', 'editor' ),
        'taxonomies'            => array( 'gallery_category',  ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-format-gallery',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
    );
    register_post_type('gallery', $args );

}
add_action( 'init', 'gallery_post_type', 0 );

function gallery_category() {

    $labels = array(
        'name'                       => _x( 'Categories', 'Taxonomy General Name', 'filmmaker' ),
        'singular_name'              => _x( 'Category', 'Taxonomy Singular Name', 'filmmaker' ),
        'menu_name'                  => __( 'Category', 'filmmaker' ),
        'all_items'                  => __( 'All category', 'filmmaker' ),
        'parent_item'                => __( 'Parent category', 'filmmaker' ),
        'parent_item_colon'          => __( 'Parent category:', 'filmmaker' ),
        'new_item_name'              => __( 'New Gallery category ', 'filmmaker' ),
        'add_new_item'               => __( 'Add New Gallery category ', 'filmmaker' ),
        'edit_item'                  => __( 'Edit Gallery category ', 'filmmaker' ),
        'update_item'                => __( 'Update Gallery category ', 'filmmaker' ),
        'view_item'                  => __( 'View Gallery category ', 'filmmaker' ),
        'separate_items_with_commas' => __( 'Separate Gallery category  with commas', 'filmmaker' ),
        'add_or_remove_items'        => __( 'Add or remove Gallery category ', 'filmmaker' ),
        'choose_from_most_used'      => __( 'Choose from the most used', 'filmmaker' ),
        'popular_items'              => __( 'Popular Gallery category ', 'filmmaker' ),
        'search_items'               => __( 'Search Gallery category ', 'filmmaker' ),
        'not_found'                  => __( 'Not Found', 'filmmaker' ),
        'items_list'                 => __( 'Gallery category  list', 'filmmaker' ),
        'items_list_navigation'      => __( 'Gallery category  list navigation', 'filmmaker' ),
    );
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
    );
    register_taxonomy( 'gallery_category', array( 'gallery' ), $args );

}
add_action( 'after_setup_theme', 'gallery_category', 0 );

?>