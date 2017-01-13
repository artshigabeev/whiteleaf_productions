 <?php
    $format = get_post_format();
    if ( false === $format ) {
        $format = 'standard';
    }
    $postlist_args = array(
       'posts_per_page'  => -1,
       'order'           => 'ASC',
       'post_type'       => $format,
    );
    $postlist = get_posts( $postlist_args );
    $ids = array();
    foreach ($postlist as $thepost) {
       $ids[] += $thepost->ID;
    }
?>
<section class="detail-blog-1">
    <div class="imageshowpost">
        <div class="container">
            <?php
                while ( have_posts() ) : the_post();
                    $post_format  = get_post_format();
                    $audio        = get_post_meta(get_the_ID(), '_beautheme_soud_cloud',TRUE);
                    $audio_file   = get_post_meta(get_the_ID(), '_beautheme_audio_file', TRUE);
                    $video        = get_post_meta(get_the_ID(), '_beautheme_video_embed',TRUE);
                    $video_file   = get_post_meta(get_the_ID(), '_beautheme_video_file',TRUE);
                    $image        = get_the_post_thumbnail(get_the_ID(), 'large');
                     $show_view ="";
                    if ($image =='') {
                      $image = '<img alt="'.get_the_title().'" src="http://placehold.it/940x540" alt="No Image">';
                    }
                    switch ($post_format) {
                        case 'audio':
                        if ($audio !="") {
                            $show_view = filmmaker_runshortcode($audio,'embed');
                        }
                        break;
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
                        break;
                        default:
                            $show_view = $image;
                        break;
                    }
                ?>
                <?php
                    $prev_post = get_previous_post();
                    $next_post = get_next_post();
                    if (is_object( $prev_post)) {
                        $previd = $prev_post->ID ;
                    }else{
                        $previd = '';
                    }
                    if (is_object($next_post)) {
                        $nextid = $next_post->ID ;
                    }else{
                        $nextid = '';
                    }
                 ?>
                <div class="prev-detail control hidden-sm hidden-xs">
                    <?php if ( !empty($previd) ) { ?>
                        <a rel="next" href="<?php echo get_permalink($previd); ?>" title="<?php the_title(); ?>">
                            <div class="title-control title-gray"><?php esc_html_e('next post','filmmaker') ?></div>
                            <div class="img-control">
                                <?php
                                if (has_post_thumbnail()) {
                                    echo get_the_post_thumbnail($previd,'filmmaker_BE');
                                } ?>
                            </div>
                            <p class="title-desc title-control-post"><?php echo wp_trim_words( get_the_title($previd), 4, '...' ); ?></p>
                        </a>
                    <?php } ?>
                </div>

                <div class="next-detail control hidden-sm hidden-xs">
                    <?php if ( !empty($nextid) ) { ?>
                        <a rel="next" href="<?php echo get_permalink($nextid); ?>" title="<?php the_title(); ?>">
                            <div class="title-control title-gray"><?php esc_html_e('prev post','filmmaker');?></div>
                            <div class="img-control">
                                <?php
                                if (has_post_thumbnail()) {
                                    echo get_the_post_thumbnail($nextid,'filmmaker_BE');
                                } ?>
                            </div>
                            <p class="title-desc title-control-post">
                                <?php echo wp_trim_words( get_the_title($nextid), 4, '...' ); ?></p>
                        </a>
                    <?php } ?>
                </div>
               <div class="row post-detail">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="wrapper-blog-detail">
                            <div class="centertxt">
                                <div class="title-bold"><?php the_title()?></div>
                                <div class="blog-detail-social gray-desc">
                                    <div class="info">
                                        <ul class="author-name">
                                            <li class="name1"><?php esc_html_e('by:','filmmaker') ?> <?php echo get_the_author_link();?></li>
                                            <li><i class="fa fa-comment-o"></i><?php echo get_comments_number();?></li>
                                        </ul>
                                         <?php get_template_part('template/social','share' );?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="img-post-detail col-md-12 col-sm-12 col-xs-12">
                            <div class="date-post">
                                <ul>
                                    <li><?php echo get_the_date('d'); ?></li>
                                    <li><?php echo get_the_date('m'); ?></li>
                                </ul>
                            </div>
                            <?php printf('%s', $show_view);?>
                    </div>
               </div>

            <?php endwhile; ?>
        </div>
    </div>
    <div class="border container">
        <?php

            $detail_blog="";
            if (filmmaker_GetOption('single-post') != NULL) {
                $detail_blog = filmmaker_GetOption('single-post');

            }else{
                $detail_blog='standard';
            }
            if (is_single('1431')) {
                get_template_part('template/content', 'standard');
            }else{
                get_template_part('template/content', $detail_blog);
            }
        ?>
    </div>
<!-- end Blog -->
</section>
<!-- Blog -->
<section class="new">
    <div class="container">
        <?php $orig_post = $post;
        global $post;
        $categories = get_the_category($post->ID);
        if ($categories) {
        $category_ids = array();
        foreach($categories as $individual_category) $category_ids[] = $individual_category->term_id;
        $args=array(
        'post__not_in' => array($post->ID),
        'posts_per_page'=> 3,
        'orderby'=>'rand'
        );
        $my_query = new wp_query( $args );
        if( $my_query->have_posts() ) {?>
        <div class="title-box box-center title-gray"><?php esc_html_e('More News','filmmaker')?>
        </div>
        <div class="container-blog padding-top">
            <?php while( $my_query->have_posts() ) { $my_query->the_post(); ?>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 box-center">
                <a href="<?php the_permalink()?>" title="<?php the_title(); ?>">
                    <?php
                        if (has_post_thumbnail()) {
                            the_post_thumbnail('filmmaker_BE');
                    }else{
                        echo $image;
                        } ?>
                </a>
                <div class="blog-item">
                    <div class="gray-desc">
                        <ul class="time-more-new-blog" >
                            <li><?php echo get_the_date('j M Y'); ?></li>
                            <li><i class=" icon fa fa-comment-o"><b><?php echo get_comments_number()?></b></i></li>
                        </ul>
                    </div>
                    <div class="title-desc"><a href="<?php the_permalink();?>" title="<?php the_title(); ?>">
                        <?php echo get_the_title(); ?>
                        </a></div>
                    <div class="gray-desc"><?php echo wp_trim_words( get_the_excerpt(), 25, '...' ); ?></div>
                </div>
            </div>
            <?php } ?>
        </div>
    <?php } }
    $post = $orig_post;?>
</section>