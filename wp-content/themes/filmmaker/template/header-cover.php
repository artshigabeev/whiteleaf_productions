<?php
    $logo = "";
    if (filmmaker_GetOption('filmmaker-logo') != NULL) {
       $logo = filmmaker_GetOption('filmmaker-logo');
       $logo_url = $logo['url'];
    }
?>
<?php
    $beau_cover_img_url = get_post_meta(get_the_ID(),'_beautheme_header_using_cover', TRUE);
    if (!$beau_cover_img_url =="") {
        $beau_cover = $beau_cover_img_url;
    }else{
        $beau_cover = '';
    }
?>
<div class="slide-flim" >
    <img src="<?php printf('%s', $beau_cover);?>" alt="">
</div>
