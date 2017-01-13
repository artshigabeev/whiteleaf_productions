<?php
function crew_post_type() {

    $labels = array(
        'name'                  => _x( 'Crew', 'Post Type General Name', 'filmmaker' ),
        'singular_name'         => _x( 'Crew', 'Post Type Singular Name', 'filmmaker' ),
        'menu_name'             => __( 'Crew', 'filmmaker' ),
        'name_admin_bar'        => __( 'Crew', 'filmmaker' ),
        'parent_item_colon'     => __( '', 'filmmaker' ),
        'all_items'             => __( 'All crew', 'filmmaker' ),
        'add_new_item'          => __( 'Add Crew', 'filmmaker' ),
        'add_new'               => __( 'Add Crew', 'filmmaker' ),
        'new_item'              => __( 'New Crew', 'filmmaker' ),
        'edit_item'             => __( 'Edit Crew', 'filmmaker' ),
        'update_item'           => __( 'Update Crew', 'filmmaker' ),
        'view_item'             => __( 'View Crew', 'filmmaker' ),
        'search_items'          => __( 'Search Crew', 'filmmaker' ),
        'not_found'             => __( 'Not found Crew', 'filmmaker' ),
        'not_found_in_trash'    => __( 'Not found crew in Trash', 'filmmaker' ),
        'items_list'            => __( 'Crew list', 'filmmaker' ),
        'items_list_navigation' => __( 'Crew list navigation', 'filmmaker' ),
        'filter_items_list'     => __( 'Filter Crew list', 'filmmaker' ),
    );
    $args = array(
        'label'                 => __( 'Crew', 'filmmaker' ),
        'description'           => __( 'Post Type crew Description', 'filmmaker' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'thumbnail', 'editor','page-attributes' ),
        'taxonomies'            => array( 'crew_category' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-groups',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
    );
    register_post_type( 'crew', $args );

}
add_action( 'init', 'crew_post_type', 0 );



function crew_category() {

    $labels = array(
        'name'                       => _x( 'Categories', 'Taxonomy General Name', 'filmmaker' ),
        'singular_name'              => _x( 'Category', 'Taxonomy Singular Name', 'filmmaker' ),
        'menu_name'                  => __( 'Category', 'filmmaker' ),
        'all_items'                  => __( 'All category', 'filmmaker' ),
        'parent_item'                => __( 'Parent category', 'filmmaker' ),
        'parent_item_colon'          => __( 'Parent category:', 'filmmaker' ),
        'new_item_name'              => __( 'New Crew category ', 'filmmaker' ),
        'add_new_item'               => __( 'Add New Crew category ', 'filmmaker' ),
        'edit_item'                  => __( 'Edit Crew category ', 'filmmaker' ),
        'update_item'                => __( 'Update Crew category ', 'filmmaker' ),
        'view_item'                  => __( 'View Crew category ', 'filmmaker' ),
        'separate_items_with_commas' => __( 'Separate Crew category  with commas', 'filmmaker' ),
        'add_or_remove_items'        => __( 'Add or remove Crew category ', 'filmmaker' ),
        'choose_from_most_used'      => __( 'Choose from the most used', 'filmmaker' ),
        'popular_items'              => __( 'Popular Crew category ', 'filmmaker' ),
        'search_items'               => __( 'Search Crew category ', 'filmmaker' ),
        'not_found'                  => __( 'Not Found', 'filmmaker' ),
        'items_list'                 => __( 'Crew category  list', 'filmmaker' ),
        'items_list_navigation'      => __( 'Crew category  list navigation', 'filmmaker' ),
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
    register_taxonomy( 'crew_category', array( 'crew' ), $args );

}
add_action( 'after_setup_theme', 'crew_category', 0 );

 ?>