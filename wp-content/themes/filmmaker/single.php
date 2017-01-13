<?php
    get_header();
    if (filmmaker_GetOption('single-post') !=='') {
        $beau_single = filmmaker_GetOption('single-post');
}?>
<?php
    if ( has_post_format( 'video')){
        get_template_part('template/contentpost-video');
    }else{
        get_template_part('template/contentpost-post');
    }
?>
<?php wp_reset_postdata(); ?>
<?php get_footer();?>