<?php
    if(filmmaker_GetOption('option-arch-film') != NULL ){
        $f_archive = filmmaker_GetOption('option-arch-film');
    }else{
        $f_archive = "full";
    }   
    $order_by = filmmaker_GetOption('tax-film-order-by') == NULL ? 'date' : filmmaker_GetOption('tax-film-order-by');
    $order = filmmaker_GetOption('tax-film-order') == NULL ? 'asc' : filmmaker_GetOption('tax-film-order');
    $enable_pagination = filmmaker_GetOption('tax-film-enable-pagination') == NULL ? 0 : filmmaker_GetOption('tax-film-enable-pagination');
 
   
    $paged = empty(get_query_var('paged')) ? 1 : get_query_var('paged');
    $tax_query = array();
    if(!empty(get_query_var('film_category'))) {
       $tax_query = array(
            'taxonomy' => 'film_category',
            'terms' => get_query_var('film_category'),
            'field' => 'slug',                     
        );
    }   
    global $wp_query;
    $args = array(
        'post_type' => 'film',        
        'orderby' => $order_by,
        'order' => $order,
        'paged' => $paged,
        'tax_query' => array(
            $tax_query
        ),
    );   
    
    $wp_query = new WP_Query($args);         
    get_template_part('template/section-film-'.$f_archive);      
?>