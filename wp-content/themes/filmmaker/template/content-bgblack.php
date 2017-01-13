<section class="view-detail-item view-detail-bg">
    <div class="container">
        <?php
        while ( have_posts() ) : the_post();
            $post_format  = get_post_format();
            $video        = get_post_meta(get_the_ID(), '_beautheme_video_embed',TRUE);
            $video_file   = get_post_meta(get_the_ID(), '_beautheme_video_file',TRUE);
            $video_thumb="";
                switch ($post_format) {
                    case 'video':
                        if($video_file !=""){
                            $show_view = '<video width="100%" height="400" controls>';
                            if (findExtension($video_file) == 'mp4') {
                                $show_view = '<source src="'.esc_attr($video_file).'" type="video/mp4">';
                            }
                            if (findExtension($video_file) == 'ogg') {
                                $show_view = '<source src="'.esc_attr($video_file).'" type="video/ogg">';
                            }
                            $show_view = __('Your browser does not support the video tag.','filmmaker').'
                            </video>';
                        }
                        if ($video !="") {
                            $show_view = filmmaker_runshortcode($video,'embed');
                        }
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
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="detail-video">
                    <?php printf('%s', $show_view);
                    echo '<div class="img-fl-detail" id="flplay">';
                        if (has_post_thumbnail()) {
                            the_post_thumbnail();
                        } ?>
                    <?php
                        echo '</div>';
                        echo '<div class="click_play" id="click-img-play">';
                        echo '<img src="'.get_template_directory_uri().'/asset/images/play.png" alt="'.esc_html__('play','filmmaker').'">';
                        echo '</div>';
                    ?>
                    <script type="text/javascript">
                        (function($){
                            "use strict";
                            $(document).ready(function () {
                                $('iframe').addClass('yplay');
                                $('#click-img-play').click(function(){
                                    $('#flplay').slideUp('1500');
                                    $(".yplay")[0].src += "?&autoplay=1";
                                    $('#click-img-play').hide(300);
                               });
                            });
                        })(jQuery);
                    </script>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="fl-title">
                    <div class="fl-title-small2 st-title"><?php the_category( ', ' ); ?></div>
                    <div class="fl-title-medium ftm"><?php the_title()?></div>
                </div>
                <div class="d-share">
                    <ul class="more-info ds">
                        <li>
                            <?php get_template_part('template/social','share' );?>
                        </li>
                        <li>|</li>
                        <li>
                            <?php echo filmmaker_getPostViews(get_the_ID()); ?>
                        </li>
						<?php $view = filmmaker_getPostViews(get_the_ID()); ?>
                        <li><?php (int) $view > 1 ? esc_html_e('VIEWS','filmmaker') : esc_html_e('VIEW','filmmaker'); ?></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Detail Content -->
        <?php endwhile; ?>
    </div>
</section>
<Section class="detail-item">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 right-content">
                <div class="detail-view">
                    <div class="detail-title"><?php esc_html_e( 'DESCRIPTION', 'filmmaker'); ?></div>
                    <div class="detain-content">
                        <?php the_content(); ?>
                    </div>
                </div>
                <div class="filmmaker-about-author">
                    <div class="author-avatar col-md-2 col-sm-2 col-xs-3">
                        <?php printf('%s',get_avatar( get_the_author_meta( 'ID' ))); ?>
                    </div>
                    <div class="about-post-author col-md-10 col-sm-10 col-xs-9">
                        <span class="author-name"> <a href="<?php the_author_meta('url'); ?>" title="<?php the_author(); ?>"><?php the_author(); ?></a></span>
                        <p><span class="author-desc"><?php the_author_meta('description'); if(!get_the_author_meta('description')) esc_html_e('No description. Please update your profile.','filmmaker'); ?></span></p>
                        <span class="author-link"> <a href="<?php the_author_meta('url'); ?>"><?php the_author_meta('url'); ?></a></span>
                    </div>
                </div><!--End filmmaker-about-author-->
                <div class="clearfix"></div>
                <?php comments_template();?>
            </div>
            <div class="col-lg-4 col-md-4 left-sidebar hidden-sm hidden-xs" >
                <?php $orig_post = $post;
                    global $post;
                    $categories = get_the_category($post->ID);
                    if ($categories) {
                    $category_ids = array();
                    foreach($categories as $individual_category) $category_ids[] = $individual_category->term_id;
                    $args=array(
                    'post__not_in' => array($post->ID),
                    'posts_per_page'=> 6,
                    );
                    $my_query = new wp_query( $args );
                    if( $my_query->have_posts() ) {?>
                <div class="sidebar">
                    <div class="detail-title"><?php esc_html_e('MORE VIDEO','filmmaker')?></div>
                    <ul class="relate-post">
                        <?php while( $my_query->have_posts() ) { $my_query->the_post();?>
                        <li>
                            <div class="img-post col-md-6 col-sm-12">
                                <a href="<?php the_permalink();?>" title="<?php the_title(); ?>">
                                    <?php if (has_post_thumbnail()) {
                                        printf(get_the_post_thumbnail());
                                    } ?>
                                </a>
                            </div>
                            <div class="titlemore col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <p class="relate-title"><?php the_category( ', ' ); ?></p>
                                <h3><a href="<?php the_permalink();?>" title="<?php the_title(); ?>"><?php echo wp_trim_words( get_the_title(), 4, '...' ); ?></a></h3>
                                <p class="status"><span><?php echo get_comments_number()?><?php esc_html_e('Comment','filmmaker' ); ?> </span><span><?php filmmaker_setPostViews(get_the_ID()); ?> <?php $view = filmmaker_getPostViews(get_the_ID()); ?><?php echo esc_html($view) ; ?> <?php (int) $view > 1 ? esc_html_e('VIEWS','filmmaker') : esc_html_e('VIEW','filmmaker'); ?></span></p>
                            </div>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
                <?php } }
                $post = $orig_post;?>
            </div>
        </div>
    </div>
</Section>
<?php wp_reset_postdata(); ?>