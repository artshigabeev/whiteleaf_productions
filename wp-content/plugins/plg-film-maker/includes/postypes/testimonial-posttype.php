<?php

function testimonial_post_type() {

    $labels = array(
        'name'                  => _x( 'Testimonial', 'Post Type General Name', 'filmmaker' ),
        'singular_name'         => _x( 'Testimonial', 'Post Type Singular Name', 'filmmaker' ),
        'menu_name'             => __( 'Testimonial', 'filmmaker' ),
        'name_admin_bar'        => __( 'Testimonial', 'filmmaker' ),
        'parent_item_colon'     => __( '', 'filmmaker' ),
        'all_items'             => __( 'All Tetimonial', 'filmmaker' ),
        'add_new_item'          => __( 'Add New Testimonial', 'filmmaker' ),
        'add_new'               => __( 'Add New Testimonial', 'filmmaker' ),
        'new_item'              => __( 'New Testimonial', 'filmmaker' ),
        'edit_item'             => __( 'Edit Testimonial', 'filmmaker' ),
        'update_item'           => __( 'Update Testimonial', 'filmmaker' ),
        'view_item'             => __( 'View Testimonial', 'filmmaker' ),
        'search_items'          => __( 'Search Testimonial', 'filmmaker' ),
        'not_found'             => __( 'Not found Testimonial', 'filmmaker' ),
        'not_found_in_trash'    => __( 'Not found testimonial in Trash', 'filmmaker' ),
        'items_list'            => __( 'Testimonial list', 'filmmaker' ),
        'items_list_navigation' => __( 'Testimonial list navigation', 'filmmaker' ),
        'filter_items_list'     => __( 'Filter testimonial list', 'filmmaker' ),
    );
    $args = array(
        'label'                 => __( 'Testimonial', 'filmmaker' ),
        'description'           => __( 'Post Type Testimonial Description', 'filmmaker' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor',  'thumbnail', ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-format-chat',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
    );
    register_post_type( 'testimonial', $args );

}
add_action( 'init', 'testimonial_post_type', 0 );
 ?>