<div class="list-flim row">
    <?php if($loop -> have_posts()){
        $i = 0;
        while($loop -> have_posts()) : $loop -> the_post();
        $category = get_the_terms($loop->ID, 'film_category');
        if ($i < 4) {
    ?>
    <?php if ($i==0) {?>
    <div class=" col-lg-9 col-md-9 col-sm-9 col-xs-12">
        <div class="flim-item cus-item-4 cus-item-big">
            <?php if(has_post_thumbnail()){
                    the_post_thumbnail('filmmaker_BE');
                } ?>
            <div class="center centertxt ">
                <div class="title-white wow fadeInUp" data-wow-delay="0.4s">
                    <a href="<?php echo get_category_link( $category[0]->term_id ); ?>" title="<?php echo esc_html($category[0]->name); ?>"><?php echo esc_html($category[0]->name); ?>
                    </a>
                </div>
                <div class="title-bold-white fl-type1 wow fadeInUp" data-wow-delay="0.5s">
                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                        <?php the_title(); ?>
                    </a>
                </div>
                <div class="white-desc wow fadeInUp" data-wow-delay="0.6s">
                    <?php echo esc_html(wp_trim_words( get_the_content(), 20, '...' )); ?>
                </div>
                <div class="list-viewmore">
                     <span class="view-more"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                        <?php esc_html_e('view more','filmmaker'); ?>
                      <i class="fa fa-long-arrow-right"></i></a></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
    <?php }else{?>
        <div class="flim-item cus-item-4 cus-item-small">
            <?php if(has_post_thumbnail()){
                the_post_thumbnail('filmmaker_SM');
            } ?>
            <div class="center centertxt ">
                <div class="title-white wow fadeInUp" data-wow-delay="0.4s"><?php echo esc_html($category[0]->name); ?></div>
                <div class="title-bold-white fl-type2 wow fadeInUp" data-wow-delay="0.5s">
                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                        <?php the_title(); ?>
                    </a>
                </div>
            </div>
        </div>

    <?php
            }
        $i++;
        }
        endwhile;
        }
    ?>
    </div>
</div>
<?php if($film_list_checkbox_link == 'yes'){ ?>
    <div class="col-lg-3 col-lg-offset-9 col-xs-12">
       <div class="type4-viewmore">
            <span class="more">
                <a href="<?php echo esc_html($view_more_url); ?>" title="<?php echo esc_attr($view_more_text); ?>">
                    <?php echo esc_attr($view_more_text); ?>
                    <i class="fa fa-long-arrow-right"></i>
                </a>
            </span>
        </div>
    </div>
<?php } ?>
<div class="clearfix"></div>