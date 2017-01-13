<?php
$title_section = $image = $small_title = $link_to_discover = $short_description = $style_discover = $check_box_discover = $color_small_title = $color_title_section = $color_short_description = $bg_linkto = $color_linkto='';
extract(shortcode_atts(array(
    'title_section'       => '',
    'image'               => '',
    'small_title'         => '',
    'check_box_discover'  => '',
    'link_to_discover'    => '',
    'short_description'   => '',
    'style_discover'      => '',
    'color_title_section' => '',
    'color_small_title'   => '',
    'color_short_description' => '',  
    'color_linkto'        => '',  
    'bg_linkto'           => '',
), $atts));
$img = wp_get_attachment_image_src($image, 'full');
$top_color = 'style="color:'.$color_small_title.'"';
$under_top_color = 'style="color:'.$color_title_section.'"';
$description_color = 'style="color:'.$color_short_description.'"';
$text_number_color = 'style="color:'.$color_linkto.'"';
$bg_number_color = 'style="background:'.$bg_linkto.'"';
?>
<?php if($style_discover == 1) { ?>
         <div class="fl-title wow fadeInDown"  data-wow-delay="0.3s" >
         <?php if( !empty($small_title) ): ?>
             <span class="fl-title-small" <?php echo $top_color; ?>><?php echo esc_html($small_title);?></span>
        <?php endif; ?>
        <?php if( !empty($title_section) ): ?>
             <div class="fl-title-big" <?php echo $under_top_color; ?>><?php echo esc_html($title_section);?></div>
        <?php endif; ?>
        </div>
        <div class="loveflim">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 wow fadeInUp"  data-wow-delay="0.3s" >
                    <?php
                        if ($image == ""){
                    } else{ ?>
                    <div class="discover-img">
                        <img src="<?php echo esc_url($img[0]);?>" alt="<?php esc_html('discover','filmmaker'); ?>">
                    </div>
                    <?php } ?>
                </div>
            </div>
            <div class="row">
                 <div class="discover-content-box1 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="left-desc col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <?php
                            if ($short_description == ""){
                            } else{
                            ?>
                             <div class="desc1" <?php echo $description_color; ?>><?php echo esc_html($short_description);?></div>
                        <?php } ?>
                    </div>
                    <div class="right-desc gray-desc col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <?php
                            if ($content == ""){
                            } else{
                            ?>
                            <?php echo '<p>'.$content.'</p>';?>
                        <?php } ?>

                        <?php if($check_box_discover != 'yes'){
                        }else{ ?>
                            <div class="fl-mg-top">
                                <span class="more" <?php echo $bg_number_color; ?>>
                                    <a href="<?php echo esc_url($link_to_discover);?>" <?php echo $text_number_color; ?>><?php esc_html_e('View more','filmmaker');?>
                                        <i class="fa fa-long-arrow-right"></i>
                                    </a>
                                </span>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div><!-- loveflim -->
<?php } elseif ($style_discover==2){ ?>
    <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <?php
                        if ($image == ""){
                    } else{
                    ?>
                    <div class="discover-img wow fadeInLeft" data-wow-delay="0.4s">
                        <img src="<?php echo esc_url($img[0]);?>" alt="<?php esc_html('discover','filmmaker'); ?>">
                    </div>
                    <?php } ?>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="discover-item">
                        <div class="discover-title wow fadeInRight" data-wow-delay="0.4s" <?php echo $top_color; ?>><?php echo esc_html($small_title);?></div>
                        <div class="discover-name wow fadeInRight" data-wow-delay="0.5s" <?php echo $under_top_color; ?>><?php echo esc_html($title_section);?></div>
                        <?php
                            if ($short_description == ""){
                            } else{
                        ?>
                        <div class="discover-desc wow fadeInRight" data-wow-delay="0.6s" <?php echo $description_color; ?>>
                            <?php echo esc_html($short_description);?> </div>
                        <?php } ?>
                        <?php
                        if ($content == ""){
                        } else{
                        ?>
                        <div class="discover-content wow fadeInRight" data-wow-delay="0.7s"><?php echo '<p>'.$content.'</p>';?></div>
                        <?php } ?>
                        <?php if($check_box_discover != 'yes'){
                        }else{ ?>
                            <div class="fl-mg-top wow fadeInLeft" data-wow-delay="0.8s">
                                <span class="more" <?php echo $bg_number_color; ?>>
                                    <a href="<?php echo esc_url($link_to_discover);?>" <?php echo $text_number_color; ?>><?php esc_html_e('View more','filmmaker');?>
                                        <i class="fa fa-long-arrow-right"></i>
                                    </a>
                                </span>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
<?php }elseif($style_discover==3){ ?>
    <div class="container">
        <div class="row">
            <div class="fl-title" >
                 <span class="fl-title-small wow fadeInDown" data-wow-delay="0.3s" <?php echo $top_color; ?>><?php echo esc_html($title_section);?></span>
                 <div class="fl-title-big wow fadeInDown" data-wow-delay="0.4s" <?php echo $under_top_color; ?>><?php echo esc_html($small_title);?></div>
            </div>
              <div class="col-lg-6 col-md-6 col-xs-12 ">
                <?php
                    if ($image == ""){
                    } else{
                    ?>
                    <div class="discover-img wow fadeInLeft" data-wow-delay="0.4s">
                        <img src="<?php echo esc_url($img[0]);?>" alt="<?php esc_html('discover','filmmaker'); ?>">
                    </div>
                    <?php } ?>
            </div>
            <div class="col-lg-6 col-md-6 col-xs-12 ">
                <div class="padding1 wow fadeInRight" data-wow-delay="0.4s">
                     <?php
                        if ($short_description == ""){
                            } else{ ?>
                        <div class="desc" <?php echo $description_color; ?>><?php echo esc_html($short_description);?> </div>
                        <?php } ?>
                     <?php
                        if ($content == ""){
                        } else{ ?>
                        <div class="content1 gray-desc">
                            <?php echo '<p>'.$content.'</p>';?>
                        </div>
                        <?php } ?>

                        <?php if($check_box_discover != 'yes'){
                            }else{ ?>
                                <div class="fl-mg-top">
                                    <span class="more" <?php echo $bg_number_color; ?>>
                                        <a href="<?php echo esc_url($link_to_discover);?>" <?php echo $text_number_color; ?>><?php esc_html_e('View more','filmmaker');?>
                                            <i class="fa fa-long-arrow-right"></i>
                                        </a>
                                    </span>
                                </div>
                        <?php } ?>
                </div>
            </div>
        </div>
    </div>

<?php } ?>