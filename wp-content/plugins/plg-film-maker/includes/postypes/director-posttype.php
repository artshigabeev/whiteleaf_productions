<?php

  function director_post_type() {

    $labels = array(
        'name'                  => _x( 'Director', 'Post Type General Name', 'filmmaker' ),
        'singular_name'         => _x( 'Director', 'Post Type Singular Name', 'filmmaker' ),
        'menu_name'             => __( 'Director', 'filmmaker' ),
        'name_admin_bar'        => __( 'Director', 'filmmaker' ),
        'parent_item_colon'     => __( '', 'filmmaker' ),
        'all_items'             => __( 'All Director', 'filmmaker' ),
        'add_new_item'          => __( 'Add New Director', 'filmmaker' ),
        'add_new'               => __( 'Add New Director', 'filmmaker' ),
        'new_item'              => __( 'New Director', 'filmmaker' ),
        'edit_item'             => __( 'Edit Director', 'filmmaker' ),
        'update_item'           => __( 'Update Director', 'filmmaker' ),
        'view_item'             => __( 'View Director', 'filmmaker' ),
        'search_items'          => __( 'Search Director', 'filmmaker' ),
        'not_found'             => __( 'Not found Director', 'filmmaker' ),
        'not_found_in_trash'    => __( 'Not found director in Trash', 'filmmaker' ),
        'items_list'            => __( 'Director list', 'filmmaker' ),
        'items_list_navigation' => __( 'Director list navigation', 'filmmaker' ),
        'filter_items_list'     => __( 'Filter Director list', 'filmmaker' ),
    );
    $args = array(
        'label'                 => __( 'Director', 'filmmaker' ),
        'description'           => __( 'Post Type Director Description', 'filmmaker' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'thumbnail', 'editor', 'page-attributes' ),
        'taxonomies'            => array( 'director_category',  ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-admin-users',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
    );
    register_post_type( 'director', $args );
}
add_action( 'init', 'director_post_type', 0 );

function director_category() {

    $labels = array(
        'name'                       => _x( 'Categories', 'Taxonomy General Name', 'filmmaker' ),
        'singular_name'              => _x( 'Category', 'Taxonomy Singular Name', 'filmmaker' ),
        'menu_name'                  => __( 'Category', 'filmmaker' ),
        'all_items'                  => __( 'All category', 'filmmaker' ),
        'parent_item'                => __( 'Parent category', 'filmmaker' ),
        'parent_item_colon'          => __( 'Parent category:', 'filmmaker' ),
        'new_item_name'              => __( 'New Director category ', 'filmmaker' ),
        'add_new_item'               => __( 'Add New Director category ', 'filmmaker' ),
        'edit_item'                  => __( 'Edit Director category ', 'filmmaker' ),
        'update_item'                => __( 'Update Director category ', 'filmmaker' ),
        'view_item'                  => __( 'View Director category ', 'filmmaker' ),
        'separate_items_with_commas' => __( 'Separate Director category  with commas', 'filmmaker' ),
        'add_or_remove_items'        => __( 'Add or remove Director category ', 'filmmaker' ),
        'choose_from_most_used'      => __( 'Choose from the most used', 'filmmaker' ),
        'popular_items'              => __( 'Popular Director category ', 'filmmaker' ),
        'search_items'               => __( 'Search Director category ', 'filmmaker' ),
        'not_found'                  => __( 'Not Found', 'filmmaker' ),
        'items_list'                 => __( 'Director category  list', 'filmmaker' ),
        'items_list_navigation'      => __( 'Director category  list navigation', 'filmmaker' ),
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
    register_taxonomy( 'director_category', array( 'director' ), $args );

}
add_action( 'after_setup_theme', 'director_category', 0 );

?>