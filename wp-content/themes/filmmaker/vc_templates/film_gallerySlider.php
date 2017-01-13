<?php
 $style_discover = "";
extract(shortcode_atts(array(
    'style_discover'  =>'',
), $atts));
?>

<?php if(get_field('gallery_film', $style_discover)){ ?>
    <?php $g_number = rand(11,999); ?>
        <section class="gallery-slider wow fadeInUp" data-wow-delay="0.3s">
            <div class="g-detail-slider">
                <div class="master-slider  ms-skin-default" id="masterslider<?php echo esc_html($g_number); ?>">
                    <?php while(has_sub_field('gallery_film', $style_discover )){ ?>
                             <div class="ms-slide">
                                <img src="<?php the_sub_field('image_gallery_film') ?>" alt="<?php the_title(); ?>">
                                <div class="transition-none"><?php the_sub_field('short_description_image'); ?></div>
                                <?php
                                    $video_url = get_sub_field('video_url');
                                    if ($video_url !="") {
                                ?>
                                <a href="<?php echo esc_url($video_url);?>?hd=1&wmode=opaque&controls=1&showinfo=0" data-type="video"><?php esc_html_e('Gallery video','filmmaker');?></a>
                                <?php }?>
                            </div>
                    <?php } ?>
                </div>
            </div>
        </section>
<?php }else{
        echo '<div class="not-found-data">';
        echo esc_html_e('Not found gallery','filmmaker');
        echo '</div>';
    } ?>
    <script>
        (function($){
            "use strict";
            var sliderg<?php echo esc_js($g_number); ?> = new MasterSlider();
                sliderg<?php echo esc_js($g_number); ?>.control('arrows');
                sliderg<?php echo esc_js($g_number); ?>.setup('masterslider<?php echo esc_html($g_number); ?>' , {
                    width:1170,
                    height:662,
                    space:0,
                    loop:true,
                    view:'wave',
                    layout:'partialview'
            });
        })(jQuery);
    </script>