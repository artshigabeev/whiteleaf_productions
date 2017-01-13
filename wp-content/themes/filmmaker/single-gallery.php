<?php get_header() ;?>
<?php
    if(filmmaker_GetOption('gallery-single') !== '' ){
        $g_single = filmmaker_GetOption('gallery-single');
    }else{
        $g_single ="list";
    }

    if(is_single('1360')){
        get_template_part('template/section-gallery','list');
    }else{
        get_template_part('template/section-gallery-'.$g_single);
    }
?>
<?php get_footer(); ?>
