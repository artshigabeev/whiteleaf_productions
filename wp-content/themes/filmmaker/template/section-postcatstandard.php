<section class="content-blog-1">
    <div class="container">
        <?php
            if(is_category() || is_single()){
              $cat = get_category_by_path(get_query_var('category_name'),false);
              $current = $cat->cat_ID;
              $current_name = $cat->cat_name;
              $current_cat_slug = $cat->slug;
            }
            else{
                $current = $current_name = $current_cat_slug ='';
            }
            if (is_tag()) {
                $postTag = get_term_by('slug', get_query_var('tag'), 'post_tag');
                $tagName = $postTag->slug;

                if ($tagName!=NULL) {
                    $tags = $postTag->slug;
                    $tags_name = $postTag->name;
                }
            }else{
                $tags = $tags_name = '';
            }
        ?>
        <?php if ( have_posts()) {?>
        <ul class="list-post-item">
            <?php
                while ( have_posts() ) : the_post();
                    $post_format  = get_post_format();
                    $audio        = get_post_meta(get_the_ID(), '_beautheme_soud_cloud',TRUE);
                    $audio_file   = get_post_meta(get_the_ID(), '_beautheme_audio_file', TRUE);
                    $video        = get_post_meta(get_the_ID(), '_beautheme_video_embed',TRUE);
                    $video_file   = get_post_meta(get_the_ID(), '_beautheme_video_file',TRUE);
                    $image        = get_the_post_thumbnail(get_the_ID(), 'large');
                        if ($image =='') {
                          $image = '';
                        }
                        $video_thumb = $show_view  = "";
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
                        if ($show_view) {
                            $class_ma =" col-lg-6 col-md-6 col-sm-12 col-xs-12";
                        }else{
                            $class_ma = "";
                        }
                    ?>
            <li id="post-<?php the_ID(); ?>" class="post-item col-lg-12 col-sm-12 col-xs-12">
                <div class="post-image <?php echo esc_attr($class_ma);?>">
                    <div class="date-post">
                        <ul>
                            <li><?php echo get_the_date('d'); ?></li>
                            <li><?php echo get_the_date('m'); ?></li>
                        </ul>
                    </div>
                    <div class="img-black <?php printf('%s', $video_thumb);?>">
                        <?php
                            if (has_post_thumbnail( get_the_ID())) {
                                the_post_thumbnail('filmmaker_BE');
                            }elseif ($post_format = 'audio') {
                                 printf('%s',$show_view);
                            } ?>
                    </div>
                </div>
                <div class="post-info col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="info">
                        <ul class="more-info">
                            <li class="name1"><?php esc_html_e( "by : ",'filmmaker' );  ?> <?php echo get_the_author_link();?></li>
                            <li><i class="fa fa-comment-o"></i><a href="<?php the_permalink();?>"><?php echo get_comments_number()?> </a></li>
                            <li><i class="fa fa-tag"></i><a href="<?php the_permalink();?>"><?php the_category( ', ' ); ?></a></li>
                        </ul>
                    </div>
                    <a href="<?php the_permalink();?>">
                        <div class="title-post">
                            <?php the_title()?>
                        </div>
                    </a>
                    <div class="gray-desc"><?php echo wp_trim_words( the_excerpt(), 30, '...' ); ?></div>
                    <span class="more"><a href="<?php the_permalink();?>"><?php esc_html_e('View more','filmmaker')?><i class="fa fa-long-arrow-right"></i></a></span>
                </div>
            </li>
            <?php endwhile; ?>
            <?php }?>
        </ul>
        <div class="pagination">
            <?php echo filmmaker_pagination($wp_query);?>
        </div>
    </div>
</section>
<?php wp_reset_postdata(); ?>