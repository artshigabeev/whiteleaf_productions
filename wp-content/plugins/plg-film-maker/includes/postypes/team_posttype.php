<?php
    function team_post_type() {

    $labels = array(
        'name'                  => _x( 'Team', 'Post Type General Name', 'filmmaker' ),
        'singular_name'         => _x( 'Team', 'Post Type Singular Name', 'filmmaker' ),
        'menu_name'             => __( 'Team', 'filmmaker' ),
        'name_admin_bar'        => __( 'Team', 'filmmaker' ),
        'parent_item_colon'     => __( 'Parent Team:', 'filmmaker' ),
        'all_items'             => __( 'All Teams', 'filmmaker' ),
        'add_new_item'          => __( 'Add New Person', 'filmmaker' ),
        'add_new'               => __( 'Add New Person', 'filmmaker' ),
        'new_item'              => __( 'New Team', 'filmmaker' ),
        'edit_item'             => __( 'Edit Team', 'filmmaker' ),
        'update_item'           => __( 'Update Team', 'filmmaker' ),
        'view_item'             => __( 'View Team', 'filmmaker' ),
        'search_items'          => __( 'Search Team', 'filmmaker' ),
        'not_found'             => __( 'Not found', 'filmmaker' ),
        'not_found_in_trash'    => __( 'Not found team in Trash', 'filmmaker' ),
        'items_list'            => __( 'Team list', 'filmmaker' ),
        'items_list_navigation' => __( 'Teams list navigation', 'filmmaker' ),
        'filter_items_list'     => __( 'Filter teams list', 'filmmaker' ),
    );
    $args = array(
        'label'                 => __( 'Team', 'filmmaker' ),
        'description'           => __( 'Team Description', 'filmmaker' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'thumbnail','editor', ),
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
    register_post_type( 'team', $args );

}
add_action( 'init', 'team_post_type', 0 );
 ?>