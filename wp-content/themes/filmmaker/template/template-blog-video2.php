<?php
/*
* Template Name: Template post format video 2
*/
get_header();
 ?>
    <section class="pro-tab-item">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="fl-title ">
                        <div class="fl-title-small"><?php esc_html_e('VIDEO','filmmaker') ?></div>
                        <div class="fl-title-big"><?php esc_html_e('Our Products','filmmaker') ?></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="search-tab col-lg-4 col-lg-offset-4 col-md-4 col-md-offset-4 col-sm-4 col-sm-offset-4 col-xs-4 col-xs-offset-4">
                    <div id="fl-search " class="fl-search fl-search-box">
                        <form method="GET" action="<?php echo esc_url(home_url("/")); ?>" id="search">
                            <label type="submit" class="search-input">
                                <i class="fa fa-search"></i>
                            </label>
                            <input name="s" type="text" size="40" placeholder="<?php esc_html_e('Search Video','filmmaker'); ?>" />
                        </form>
                     </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <nav class="fl-tabs">
                        <ul class="tabs">
                            <li class=""><a href="#all"  role="tab" data-fillter="all"><?php esc_html_e('ALL','filmmaker'); ?></a></li>
                            <?php
                                $categories = get_categories( );
                                foreach ( $categories as $category ){
                                  echo '
                            <li><a href="#'.$category->slug .'" data-fillter="'.$category->slug .'">' . $category->name . '</a></li>
                            ';}
                             ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="panel-default">
                <div class="panel-body ">
                    <div class="tab-content">
                        <div class="tab-pane fade-in active" id="all">
                            <?php
                                $list = array(
                                    'posts_type' => 'post',
                                    'order' => 'DESC',
                                    'paged' => $paged,
                                    'posts_per_page' => 10,
                                    'tax_query' => array( array(
                                        'taxonomy' => 'post_format',
                                        'field' => 'slug',
                                        'terms' => array('post-format-video'),
                                       ) )
                                );
                                $loop = new WP_Query($list);
                                 if ( $loop->have_posts() ) : while ( $loop->have_posts() ) : $loop->the_post();
                                    $post_format  = get_post_format();
                                    $video        = get_post_meta(get_the_ID(), '_beautheme_video_embed',TRUE);
                                    $video_file   = get_post_meta(get_the_ID(), '_beautheme_video_file',TRUE);
                                    $video_thumb = $image = "";
                                        if ($image =='') {
                                          $image = '<img alt="'.get_the_title().'" src="http://placehold.it/960x440" alt="No Image">';
                                        }
                                        switch ($post_format) {
                                            case 'audio':
                                            if ($audio !="") {
                                                $show_view = filmmaker_runshortcode($audio,'embed');
                                            }
                                            break;
                                            default:
                                                $show_view = $image;
                                            break;
                                        }
                                        if ($video !="") {
                                            $video_thumb = "video_thumb";
                                        }else{
                                            $video_thumb = "";
                                        }
                                    $getcat = get_the_category( $post->id);
                                    $cat_slug ="";
                                    foreach ( $getcat as $c) {
                                        $cat = get_category($c);
                                        $cat_slug .=' '.$cat->slug ;
                                    }
                                ?>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 post-item <?php printf('%s',$cat_slug) ?>">
                                    <div class="tab-p-container tab-p-hover">
                                        <a href="<?php the_permalink();?>">
                                            <div class="img-post <?php printf('%s', $video_thumb);?>">
                                                <?php
                                                    if (has_post_thumbnail()) {
                                                        the_post_thumbnail('filmmaker_BE');
                                                    }else {
                                                         echo $image;
                                                    } ?>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="tab-p-content">
                                        <div class="tab-p-category p-tab">
                                            <?php  the_category(' , ' );?>
                                        </div>
                                        <div class="tab-p-name">
                                            <a href="<?php the_permalink();?>" title=""><?php echo wp_trim_words( get_the_title(), 6, '...' ); ?></a>
                                        </div>
                                        <div class="tab-p-desc">
                                            <span class="comment"><a href="<?php the_permalink();?>"><?php echo get_comments_number()?><?php esc_html_e(' Comments','filmmaker') ?> </a></span><span class="view"><?php filmmaker_setPostViews(get_the_ID()); ?> <?php $view = filmmaker_getPostViews(get_the_ID()); ?><?php echo esc_html($view); ?><?php (int) $view > 1 ? esc_html_e('VIEWS','filmmaker') : esc_html_e('VIEW','filmmaker'); ?></span>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile;?>
                            <?php endif; ?>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
             <div class="row">
                <div  class="centertxt pagi col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <?php echo filmmaker_pagination($loop); ?>
                </div>
            </div>
        </div>
    </section>
    <script>
        (function($){
            "use strict"
            $('.tabs li a').click(function(event) {
                var toShow = $(this).attr('data-fillter')
                $('.tabs li a').removeClass('active')
                $(this).addClass('active')
                $('.post-item').hide('fast')
                $('.'+toShow).show('fast')
                if (toShow === 'all') {
                    $('.post-item').show('fast')
                }
                return false
            });
        })(jQuery)
    </script>
<?php while(have_posts()) : the_post();
    the_content();
endwhile;
?>
<?php wp_reset_postdata();
    get_footer();
?>