<div class="<?php echo esc_html($film_column_in_row); ?> box-center box-blog-hover">
    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
        <?php if(has_post_thumbnail()){
            the_post_thumbnail('filmmaker_BE');
         } ?>
    </a>
    <div class="blog-item3">
        <div class="gray-desc wow fadeInUp" data-wow-delay="0.4s">
                <span class="blog-date"><?php  the_time('j F, Y') ?></span>
                <i class="fa fa-comment-o"><b><?php comments_number('0', '1', '%'); ?></b></i>
        </div>
        <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
             <div class="title-desc wow fadeInUp" data-wow-delay="0.5s">
                    <?php the_title(); ?>
            </div>
        </a>
         <div class="gray-desc wow fadeInUp" data-wow-delay="0.6s">
            <?php echo wp_trim_words(get_the_content(),15, '...'); ?>
        </div>
    </div>
</div>
