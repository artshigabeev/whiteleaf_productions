<?php
get_header();
if (filmmaker_GetOption('white-logo') != NULL) {
    $logo_white = filmmaker_GetOption('white-logo');
}else{
    $logo_white = filmmaker_GetOption('filmmaker-logo');
}
$image = filmmaker_GetOption('image404');
?>
<section id="page404">
    <div class="content-404">
        <div class="container">
            <div class="logo-white">
            <?php if ($logo_white != ''): ?>
                <a href="<?php echo esc_url(home_url('/'));?>" title="<?php esc_html_e('Home Page','filmmaker'); ?>"><img alt="<?php esc_html_e('Logo white','filmmaker');?>" src="<?php echo esc_url($logo_white['url']);?>"></a>
            <?php endif ?>
            </div><!-- End #page404 -->
            <div class="box-text404">
                <div class="title-box404"><?php esc_html_e('erro!','filmmaker');?></div>
                <div class="text-404"><?php esc_html_e('404 Not found','filmmaker');?></div>
                <a href="<?php echo esc_url(home_url('/'));?>" title="<?php esc_html_e('Home Page','filmmaker'); ?>" class="btn-white"><?php esc_html_e('Home page','filmmaker');?></span></a>
            </div><!-- End .box-text404-->
        </div>
    </div><!--End .content-404-->
    <?php  if ($image) {?>
    <img class="img404" src="<?php echo esc_url($image['url'])?>" alt="<?php esc_html_e('Not found image','filmmaker')?>">
    <?php }?>
</section>
<?php get_footer();?>
