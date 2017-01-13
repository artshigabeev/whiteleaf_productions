<?php
/*
* Template Name: Template blogs post have sidebar
*/
get_header(); ?>
<section class="content-blog-1">
    <div class="container">
        <div class="col-lg-8 col-md-9 col-sm-12 col-xs-12 nopadding">
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
            <ul class="list-post-item">
                <?php
                while ( $loop ->have_posts() ) : $loop ->the_post();
                    $post_format  = get_post_format();
                    $audio        = get_post_meta(get_the_ID(), '_beautheme_soud_cloud',TRUE);
                    $audio_file   = get_post_meta(get_the_ID(), '_beautheme_audio_file', TRUE);
                    $video        = get_post_meta(get_the_ID(), '_beautheme_video_embed',TRUE);
                    $video_file   = get_post_meta(get_the_ID(), '_beautheme_video_file',TRUE);
                    $image        = get_the_post_thumbnail(get_the_ID(''), 'filmmaker_BE');
                    if ($image =='') {
                      $image = '<img alt="'.get_the_title().'" src="http://placehold.it/940x540" alt="No Image">';
                    }
                    $video_thumb = $show_view = "";
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
                        <div class="post-image col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="date-post " >
                                <ul>
                                    <li><?php echo get_the_date('d'); ?></li>
                                    <li><?php echo get_the_date('m'); ?></li>
                                    <li><?php echo get_the_date('Y'); ?></li>
                                </ul>
                            </div>
                            <div class="img-black <?php printf('%s', $video_thumb);?>">
                                <?php
                                    if (has_post_thumbnail( get_the_ID())) {
                                        the_post_thumbnail('filmmaker_BE');
                                    }else{
                                            echo $image;
                                        } ?>
                            </div>
                        </div>
                    </a>
                    <div class="post-info post-info2 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="info">
                            <ul class="more-info">
                                <li class="name1"><?php esc_html_e('by :','filmmaker'); ?><?php echo get_the_author_link();?></li>
                                <li><i class="fa fa-comment-o"></i><a href="<?php the_permalink();?>"><?php echo get_comments_number()?></a></li>
                                <li><i class="fa fa-tag"></i><?php the_category( ', ' ); ?></li>
                            </ul>
                        </div>
                        <a href="<?php the_permalink();?>" title="<?php the_title(); ?>">
                            <div class="title-post">
                                <?php the_title()?>
                            </div>
                        </a>
                        <div class="gray-desc">
                            <?php echo wp_trim_words(get_the_content(), 40,'...') ?>
                        </div>
                        <span class="more wow fadeInUp" data-wow-delay="0.3s"><a href="<?php the_permalink();?>"><?php esc_html_e('View more','filmmaker')?><i class="fa fa-long-arrow-right"></i></a></span>
                    </div>
                </li><!-- post-item -->
                <?php endwhile; ?>
            <?php }?>
            </ul><!-- list-post-item -->
            <div class="clearfix"></div>
            <div class="fl-pagination"><?php echo filmmaker_pagination($loop); ?></div>
        </div>
    <!-- side bar -->
        <div class="side-bar-2 archive-sidebar side-bar col-lg-4 col-md-3 hidden-sm hidden-xs">
            <?php
                if (function_exists("dynamic_sidebar")) {
                    if ( is_active_sidebar( 'sidebar-blog-widget' ) ){
                        dynamic_sidebar( 'sidebar-blog-widget' );
                    }
                }
            ?>
        </div><!-- side-bar -->
    </div><!-- container -->
</section>
<?php wp_reset_postdata();
    get_footer();
?>