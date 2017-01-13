<?php
// Register Custom Post Type
function custom_post_type() {

    $labels = array(
        'name'                  => _x( 'Films', 'Post Type General Name', 'filmmaker' ),
        'singular_name'         => _x( 'film', 'Post Type Singular Name', 'filmmaker' ),
        'menu_name'             => __( 'Film', 'filmmaker' ),
        'name_admin_bar'        => __( 'Film', 'filmmaker' ),
        'parent_item_colon'     => __( 'Parent Film:', 'filmmaker' ),
        'all_items'             => __( 'All Films', 'filmmaker' ),
        'add_new_item'          => __( 'Add New Film', 'filmmaker' ),
        'add_new'               => __( 'Add New Film', 'filmmaker' ),
        'new_item'              => __( 'New Film', 'filmmaker' ),
        'edit_item'             => __( 'Edit Film', 'filmmaker' ),
        'update_item'           => __( 'Update film', 'filmmaker' ),
        'view_item'             => __( 'View film', 'filmmaker' ),
        'search_items'          => __( 'Search film', 'filmmaker' ),
        'not_found'             => __( 'Not found film', 'filmmaker' ),
        'not_found_in_trash'    => __( 'Not found film in Trash', 'filmmaker' ),
        'items_list'            => __( 'Films list', 'filmmaker' ),
        'items_list_navigation' => __( 'Films list navigation', 'filmmaker' ),
        'filter_items_list'     => __( 'Filter Film list', 'filmmaker' ),
    );
    $args = array(
        'label'                 => __( 'Film', 'filmmaker' ),
        'description'           => __( 'Post Type Film Description', 'filmmaker' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'thumbnail', 'page-attributes', 'editor','featured-post' ),
        'taxonomies'            => array( 'film_category',  ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-video-alt',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
    );
    register_post_type( 'film', $args );

}
add_action( 'init', 'custom_post_type', 0 );

function film_category() {

    $labels = array(
        'name'                       => _x( 'Categories', 'Taxonomy General Name', 'filmmaker' ),
        'singular_name'              => _x( 'Category', 'Taxonomy Singular Name', 'filmmaker' ),
        'menu_name'                  => __( 'Category', 'filmmaker' ),
        'all_items'                  => __( 'All category', 'filmmaker' ),
        'parent_item'                => __( 'Parent category', 'filmmaker' ),
        'parent_item_colon'          => __( 'Parent category:', 'filmmaker' ),
        'new_item_name'              => __( 'New Film category ', 'filmmaker' ),
        'add_new_item'               => __( 'Add New Film category ', 'filmmaker' ),
        'edit_item'                  => __( 'Edit Film category ', 'filmmaker' ),
        'update_item'                => __( 'Update Film category ', 'filmmaker' ),
        'view_item'                  => __( 'View Film category ', 'filmmaker' ),
        'separate_items_with_commas' => __( 'Separate Film category  with commas', 'filmmaker' ),
        'add_or_remove_items'        => __( 'Add or remove Film category ', 'filmmaker' ),
        'choose_from_most_used'      => __( 'Choose from the most used', 'filmmaker' ),
        'popular_items'              => __( 'Popular Film category ', 'filmmaker' ),
        'search_items'               => __( 'Search Film category ', 'filmmaker' ),
        'not_found'                  => __( 'Not Found', 'filmmaker' ),
        'items_list'                 => __( 'Film category  list', 'filmmaker' ),
        'items_list_navigation'      => __( 'Film category  list navigation', 'filmmaker' ),
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
    register_taxonomy( 'film_category', array( 'film' ), $args );

}
add_action( 'after_setup_theme', 'film_category', 0 );

?>