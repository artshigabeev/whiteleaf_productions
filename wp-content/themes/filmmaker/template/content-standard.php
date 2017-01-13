<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 content-post-d1 none-sibar">
    <div class="content-post gray-desc">
    <?php the_content();?>
    </div>
    <div class="list-social blog-detail-social gray-desc">
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
        <div class="author-avatar col-lg-2 col-md-2 col-sm-2 col-xs-3">
            <?php printf('%s',get_avatar( get_the_author_meta( 'ID' ))); ?>
        </div>
        <div class="about-post-author col-lg-9 col-md-10 col-sm-10 col-xs-9">
            <span class="author-name"> <a href="<?php the_author_meta('url'); ?>" title="<?php the_author(); ?>"><?php the_author(); ?></a></span>
            <p><span class="author-desc"><?php the_author_meta('description'); if(!get_the_author_meta('description')) esc_html_e('No description. Please update your profile.','filmmaker'); ?></span></p>
            <span class="author-link"> <a href="<?php the_author_meta('url'); ?>" title="<?php the_author(); ?>"><?php the_author_meta('url'); ?></a></span>
        </div>
    </div><!--End filmmaker-about-author-->
    <div class="clearfix"></div>
    <!-- comment -->
    <?php
        if ( comments_open() ) {
            comments_template();
        }
    ?>
    <!-- end comment -->
</div>
 <div class="clearfix"></div>