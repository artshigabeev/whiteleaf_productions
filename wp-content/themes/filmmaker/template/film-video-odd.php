<ul class="film-style-odd" >
    <?php if($loop -> have_posts()){ ?>
    <?php
         while($loop -> have_posts()) : $loop -> the_post(); $i++;
        $category = get_the_terms($loop->id, 'film_category');
    ?>
        <li class="film-odd-box">
            <div class="film-odd-img">
                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                    <?php if(has_post_thumbnail()){ ?>
                        <?php the_post_thumbnail('filmmaker_BE'); ?>
                    <?php } ?>
                </a>
                <div class="film-odd-view">
                    <span class="view-more">
                        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" <?php echo $cl_link_film; ?>>
                            <?php esc_html_e('VIEW MORE','filmmaker'); ?>
                            <i class="fa fa-long-arrow-right" <?php echo $cl_link_film; ?>></i>
                        </a>
                    </span>
                </div>
            </div>
            <div class="film-odd-content wow fadeInUp" data-wow-delay="0.3s">
                <div class="p-category ">
                    <a href="<?php echo get_category_link( $category[0]->term_id ); ?>" title="<?php echo esc_html($category[0]->name); ?>" <?php echo $cl_cat_film; ?>>
                    <?php echo esc_html($category[0]->name); ?>
                    </a></div>
                <div class="p-name ">
                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" <?php echo $cl_name_film; ?>>
                        <?php the_title(); ?>
                    </a>
                </div>
                <div class="p-desc" <?php echo $cl_desc_film; ?>>
                    <?php echo esc_html(wp_trim_words( get_the_content(), 50, '...' )); ?>
                </div>
            </div>

        </li>

        <?php endwhile; ?>
    <?php } ?>
</ul>
<?php if($film_list_checkbox_link == 'yes'){ ?>
    <div class="p-viewmore wow fadeInUp" data-wow-delay="0.3s">
            <span class="more">
                <a href="<?php echo esc_html($view_more_url); ?>" title="<?php echo esc_html($view_more_text); ?>" <?php echo $cl_viewmore; ?>>
                        <?php echo $view_more_text; ?>
                    <i class="fa fa-long-arrow-right" <?php echo $cl_viewmore; ?>></i>
                </a>
            </span>
    </div>
<?php } ?>
<div class="clearfix"></div>