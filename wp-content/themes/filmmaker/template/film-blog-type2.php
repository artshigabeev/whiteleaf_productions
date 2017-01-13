<div class="content-blog-item col-lg-9 col-lg-offset-3 col-md-10 col-md-offset-1 col-sm-12 col-xs-12 wow fadeInUp" data-wow-delay="0.4s">
        <div class="col-lg-1 col-md-2 col-sm-2 col-xs-3 new-date ">
            <div class="wow fadeInUp" data-wow-delay="0.5s">
                <b><?php the_time('j'); ?></b>
                <p class="my"><?php the_time('M Y'); ?></p>
            </div>
        </div>
        <div class="title-blog col-lg-10 col-md-10 col-sm-10  col-xs-9 wow fadeInUp" data-wow-delay="0.5s">
            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                <?php the_title(); ?>
            </a>
            <div class="view-point">
                <i class="fa fa-eye">
                    <b><?php echo filmmaker_getPostViews(get_the_ID()); ?></b>
                </i>
                <i class="fa fa-comment-o">
                    <b><?php comments_number('0', '1', '%'); ?>
                     </b>
                 </i>
             </div>
         </div>
</div>
