<?php
    function partner_post_type() {

    $labels = array(
        'name'                  => _x( 'Partner', 'Post Type General Name', 'filmmaker' ),
        'singular_name'         => _x( 'Partner', 'Post Type Singular Name', 'filmmaker' ),
        'menu_name'             => __( 'Partner', 'filmmaker' ),
        'name_admin_bar'        => __( 'Partner', 'filmmaker' ),
        'parent_item_colon'     => __( '', 'filmmaker' ),
        'all_items'             => __( 'All Partner', 'filmmaker' ),
        'add_new_item'          => __( 'Add New Partner', 'filmmaker' ),
        'add_new'               => __( 'Add New Partner', 'filmmaker' ),
        'new_item'              => __( 'New Partner', 'filmmaker' ),
        'edit_item'             => __( 'Edit Partner', 'filmmaker' ),
        'update_item'           => __( 'Update Partner', 'filmmaker' ),
        'view_item'             => __( 'View Partner', 'filmmaker' ),
        'search_items'          => __( 'Search Partner', 'filmmaker' ),
        'not_found'             => __( 'Not found Partner', 'filmmaker' ),
        'not_found_in_trash'    => __( 'Not found partner in Trash', 'filmmaker' ),
        'items_list'            => __( 'Partner list', 'filmmaker' ),
        'items_list_navigation' => __( 'Partner list navigation', 'filmmaker' ),
        'filter_items_list'     => __( 'Filter Partner list', 'filmmaker' ),
    );
    $args = array(
        'label'                 => __( 'Partner', 'filmmaker' ),
        'description'           => __( 'Post Type Partner Description', 'filmmaker' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'thumbnail' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-businessman',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
    );
    register_post_type( 'partner', $args );

}
add_action( 'init', 'partner_post_type', 0 );


 ?>