<?php
$perpage = $show_perpage = $color_quote = $color_name = $color_comment = $color_page = $color_page_active =  "";
extract(shortcode_atts(array(
    'perpage'	=> '',
    'show_perpage'	=> '',
    'color_quote'   => '',
    'color_name'    => '',
    'color_comment' => '',
    'color_page'    => '',
    'color_page_active' =>'',

), $atts));
$cl_name = 'style="color:'.$color_name.'"';
$cl_comment = 'style="color:'.$color_comment.'"';
$args = array(
	'post_type' 		=>'testimonial',
	'posts_per_page' 	=>$perpage,
	'orderby'			=> 'date',
);
$loop = new WP_Query( $args);
wp_reset_postdata();
 $random = rand(11,999);
?>
<style>
    .testimonial-quote:before{color:<?php echo $color_quote; ?>;}
    .ms-testimonial .ms-bullets.ms-dir-h .ms-bullet{background: <?php echo  $color_page; ?> }
    .ms-testimonial .ms-bullets.ms-dir-h .ms-bullet.ms-bullet-selected{background: <?php echo  $color_page_active; ?> }
</style>
<section class="testimonial-item">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
            <!-- masterslider -->
            <div class="master-slider ms-testimonial ms-skin-default" id="masterslider-testimonial">
                <?php while($loop->have_posts()) : $loop->the_post(); ?>
                    <div class="ms-slide">
                        <div class="testimonial-quote " >
                            <?php echo get_the_post_thumbnail(get_the_ID(), 'small'); ?>
                            <div class="author" <?php echo $cl_name; ?>><?php the_title(); ?></div>
                            <div class="testimonial-desc" <?php echo $cl_comment; ?>>
                                <?php the_content(); ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
            </div>
            <script type="text/javascript">
                var slider<?php echo $random; ?> = new MasterSlider();
                slider<?php echo $random; ?>.control("bullets", {autohide:false});
                slider<?php echo $random; ?>.setup("masterslider-testimonial", {
                    height:10,
                    centerControls:false,
                    space:5,
                    layout:"fillwidth",
                    autoHeight:true,
                    loop:true
                });
            </script>
        </div>
      </div>
    </div>
</section>