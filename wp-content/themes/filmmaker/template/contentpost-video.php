<?php get_header() ;?>
<?php
    if(filmmaker_GetOption('video-detail') !== '' ){
        $single_post_video = filmmaker_GetOption('video-detail');
    }else{
        $single_post_video ="nonebg";
    }
if(is_single('1940')){
	get_template_part('template/content','nonebg');

}else{
    get_template_part('template/content-'.$single_post_video);
}
?>
<?php get_footer(); ?>