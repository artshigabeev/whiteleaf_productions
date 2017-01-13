<?php
$title_section = $small_title = $link_to = $show_number = $gallery_list_id = '';
extract(shortcode_atts(array(
    'title_section'     => '',
    'small_title'       => '',
    'link_to'           => '',
    'show_number'       => '',
    'style_gallery'     => '',
    'gallery_list_id'   =>'',

), $atts));
$listFeatureGallery = new WP_Query(array('post_type'=>'gallery','post__in' =>  explode(',',$gallery_list_id)));
wp_reset_postdata();
?>

<?php if($style_gallery == 0) { ?>
    <div class="container-fluid ">
        <div class="box-center box-top">
            <span class="title-gray"><?php echo esc_html($small_title);?></span>
            <div class="title-bold"><?php echo esc_html($title_section);?></div>
        </div>
        <div class="gallery-list">
            <div class="list">

                <?php
                $i =0;
                while($listFeatureGallery->have_posts()) : $listFeatureGallery->the_post();
                if ($i < 2) {
                ?>
                <?php  if ($i==0) {?> <div class="col-lg-6 col-md-6 col-xs-12 "><?php }?>
                <div class="list-item col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <a href="<?php the_permalink();?>" title="<?php the_title();?>">
                        <div class="image wow fadeInUp" data-wow-delay="0.4s">
                            <?php the_post_thumbnail();?>
                        </div>
                    </a>
                </div>
                <?php }elseif ($i==2) {?>
                        <div class="list-item col-sm-12 col-md-12 col-xs-12">
                            <a href="<?php the_permalink();?>" title="<?php the_title();?>">
                                <div class="image3 wow fadeInUp" data-wow-delay="0.7s">
                                   <?php the_post_thumbnail();?>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class=" col-lg-6 col-sm-6 col-md-6 col-xs-12">
                <?php }else{
                    $divClass ='list-item col-lg-6 col-md-6 col-xs-12';
                    $imageClass = 'image2';
                    if ($i> 3) {$imageClass = 'image'; $divClass = 'list-item col-lg-12 col-xs-12';}
                    if ($i==4) {echo '<div class=" col-lg-6 col-md-6 col-xs-12">';}
                    ?>
                        <div class="<?php echo esc_attr($divClass);?>">
                            <a href="<?php the_permalink();?>" title="<?php the_title();?>">
                                <div class="<?php echo esc_attr($imageClass);?> wow fadeInUp" data-wow-delay="0.8s">
                                <?php the_post_thumbnail();?>
                                </div>
                            </a>
                        </div>
                    <?php }?>
                <?php
                    $i++;
                    endwhile;
                ?>
                    </div>
                    <?php if (!$link_to) {?>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <span class="more"><a href="<?php echo esc_url($link_to);?>" title="<?php esc_html_e('View All Gallery','filmmaker'); ?>"><?php esc_html_e('View All Gallery','filmmaker'); ?><i class="fa fa-long-arrow-right"></i></a></span>
                    </div>
                    <?php }?>

                    </div>
                </div>

            </div>
        </div>
    </div>
<!-- end gallery -->
<?php } elseif ($style_gallery == 1){ ?>
    <div class="container-fluid ">
        <div class="box-center box-top">
            <span class="title-gray"><?php echo esc_html($small_title);?></span>
            <div class="title-bold"><?php echo esc_html($title_section);?></div>
        </div>

        <div class="gallery-list">
            <div class="list list2">
            <?php
            $i =0;
            while($listFeatureGallery->have_posts()) : $listFeatureGallery->the_post();
            ?>
            <?php if($i==0){?><div class="col-lg-12 col-sm-12 col-xs-12"><?php }?>
            <?php
                $imgClass = 'image2';
                if ($i>1) { $imgClass = 'image';}

                if ($i!=1 && $i < 4) {
                    $classItem = "list-item col-lg-3 col-md-3 col-sm-3 col-xs-12";
                }else{
                    $classItem = "list-item col-lg-6 col-md-6 col-sm-6 col-xs-12";
                }
                if ($i==4 || $i==5) {
                   $classItem = 'list-item col-lg-6 col-md-6 col-sm-6 col-xs-12';
                }
            ?>
            <?php if($i==4){?></div><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 "><div class="col-lg-6 col-md-6 col-sm-6  col-xs-12"><?php }?>
                <div class="flm-gallery-item <?php echo esc_attr($classItem);?>">
                    <a href="<?php the_permalink(); ?>" title="<?php the_title();?>">
                        <div class="<?php echo esc_attr($imgClass);?> wow fadeInUp" data-wow-delay="0.3s">
                           <?php the_post_thumbnail();?>
                        </div>
                    </a>
                </div>
            <?php if($i==5){?></div><?php }?>
            <?php $i++; endwhile; ?>
                </div>
            </div>
            <div class="view_all_gallery wow fadeInUp" data-wow-delay="0.3s">
                <span class="more"> <a href="<?php echo esc_url($link_to);?>"><?php echo esc_html_e('View All Gallery','filmmaker') ?><i class="fa fa-long-arrow-right"></i></a></span>
            </div>
        </div>
    </div>

<?php } ?>