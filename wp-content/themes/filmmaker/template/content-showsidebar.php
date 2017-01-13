<div class="content-detail-post1 col-lg-8 col-md-8 col-sm-12 col-xs-12 ">
    <div class="content-post-d1">
        <div class="content-post gray-desc">
        <?php the_content();?>
        </div>
    </div>
    <div class="list-social blog-detail-social  gray-desc">
        <div class="info">
            <ul class="more-info">
                <li ><i class="fa fa-tag"></i><?php the_category( ', ' ); ?></li>
                <li >
                    <?php get_template_part('template/social','share' );?>
                </li>
            </ul>
        </div>
    </div>
    <div class="tags-blog">
        <?php the_tags(); ?>
    </div>
    <div class="filmmaker-about-author">
        <div class="author-avatar col-md-2 col-sm-2 col-xs-3">
            <?php printf('%s',get_avatar( get_the_author_meta( 'ID' ))); ?>
        </div>
        <div class="about-post-author col-md-10 col-sm-10 col-xs-9">
            <span class="author-name"> <a href="<?php the_author_meta('url'); ?>" title="<?php the_author(); ?>"><?php the_author(); ?></a></span>
            <p><span class="author-desc"><?php the_author_meta('description'); if(!get_the_author_meta('description')) esc_html_e('No description. Please update your profile.','filmmaker'); ?></span></p>
            <span class="author-link"> <a href="<?php the_author_meta('url'); ?>"title="<?php the_author(); ?>"><?php the_author_meta('url'); ?></a></span>
        </div>
    </div>
    <div class="clearfix"></div>
       <?php
            if ( comments_open() ) {
                comments_template();
            }
        ?>
    <div class="clearfix"></div>
</div>
<div class="single-right-sidebar col-lg-4 col-md-4 hidden-xs hidden-sm">
    <?php
        if (function_exists("dynamic_sidebar")) {
            if ( is_active_sidebar( 'sidebar-blog-widget' ) ){
                dynamic_sidebar( 'sidebar-blog-widget' );
            }
        }
    ?>
</div><!-- side-bar -->
 <div class="clearfix"></div>