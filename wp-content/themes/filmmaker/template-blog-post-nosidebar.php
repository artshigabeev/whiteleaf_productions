<?php
/*
* Template Name: Template blogs post full page
*/
get_header(); ?>
<section class="content-blog-1">
    <div class="container">
        <?php
                $args = array(
                  'post_type' => 'post',
                  'paged' => $paged,
                  'posts_per_page' => 6,
                );
                $loop = new WP_Query( $args);
                wp_reset_postdata();
            ?>
            <?php if ( have_posts()) { ?>
        <ul class="list-post-item row">
            <?php
                while ( $loop ->have_posts() ) : $loop ->the_post();
                    $post_format  = get_post_format();
                    $audio        = get_post_meta(get_the_ID(), '_beautheme_soud_cloud',TRUE);
                    $audio_file   = get_post_meta(get_the_ID(), '_beautheme_audio_file', TRUE);
                    $video        = get_post_meta(get_the_ID(), '_beautheme_video_embed',TRUE);
                    $video_file   = get_post_meta(get_the_ID(), '_beautheme_video_file',TRUE);
                    $image        = get_the_post_thumbnail(get_the_ID('BE'), '');
                    if ($image =='') {
                      $image = '<img alt="'.get_the_title().'" src="http://placehold.it/940x540" alt="No Image">';
                    }
                    $video_thumb = "";
                    switch ($post_format) {
                        case 'audio':
                        if ($audio !="") {
                            $show_view = filmmaker_runshortcode($audio,'embed');
                        }
                        break;
                        case 'video':
                            if ($video !="") {
                                $video_thumb = "video_thumb";
                            }else{
                                $video_thumb = "";
                            }
                        break;
                        default:
                            $show_view = $image;
                        break;
                    }
            ?>
            <li id="post-<?php the_ID(); ?>" <?php post_class("post-item col-lg-12 col-md-12 col-sm-12 col-xs-12"); ?>>
                <a href="<?php the_permalink();?>" title="<?php the_title(); ?>">
                    <div class="post-image col-lg-6 col-md-6 col-sm-12 col-xs-12 wow fadeInUp"  wow-data-delay="0.3s">
                        <div class="date-post">
                            <ul>
                                <li><?php echo get_the_date('d'); ?></li>
                                <li><?php echo get_the_date('m'); ?></li>
                            </ul>
                        </div>
                        <div class="img-black <?php printf('%s', $video_thumb);?> " >
                            <?php
                                if (has_post_thumbnail( get_the_ID())) {
                                    the_post_thumbnail('filmmaker_BE');
                                }else {
                                    echo $image;
                                } ?>
                        </div>
                    </div>
                </a>
                <div class="post-info col-lg-6 col-md-6 col-sm-12 col-xs-12 wow fadeInUp"  wow-data-delay="0.3s">
                    <div class="info ">
                        <ul class="more-info">
                            <li class="name1"><?php esc_html_e('by :','filmmaker'); ?> <?php echo get_the_author_link();?></li>
                            <li><i class="fa fa-comment-o"></i><a href="<?php the_permalink();?>"><?php echo get_comments_number()?> </a></li>
                            <li><i class="fa fa-tag"></i><?php the_category( ', ' ); ?></li>
                        </ul>
                    </div>
                    <a href="<?php the_permalink();?>" title="<?php the_title(); ?>">
                        <div class="title-post">
                            <?php echo wp_trim_words(get_the_title(), 10 ,' ...'); ?>
                        </div>
                    </a>
                    <div class="gray-desc"><?php echo wp_trim_words( get_the_content(), 28, '...' ); ?>
                    </div>
                    <span class="more">
                        <a href="<?php the_permalink();?>" title="<?php esc_html_e('View more','filmmaker'); ?>"><?php esc_html_e('View more','filmmaker'); ?>
                        <i class="fa fa-long-arrow-right"></i></a></span>
                </div>
            </li><!-- post-item -->
            <?php endwhile;
                wp_reset_postdata();
            ?>
            <?php }?>
        </ul><!-- list-post-item -->
        <div class="clearfix"></div>
        <div class="fl-pagination"><?php echo filmmaker_pagination($loop); ?></div>
    </div><!-- container -->
</section>
<?php
    while (have_posts()) : the_post();
        the_content();
    endwhile;
    get_footer();
?>