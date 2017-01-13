<ul class="list-film2 wow fadeInUp" data-wow-delay="0.3s">
    <?php if($loop -> have_posts()){ ?>
    <?php
        while($loop -> have_posts()) : $loop -> the_post(); $i++;
        $category = get_the_terms($loop->ID, 'film_category');
    ?>
        <li class="fl-mg ">
            <div class="film-item cus-item-1 ">
                <?php if(has_post_thumbnail()){
                        the_post_thumbnail('filmmaker_BE');
                    } ?>
                <div class="center centertxt">
                    <div class="title-white"><a href="<?php echo get_category_link( $category[0]->term_id ); ?>" title="<?php echo esc_html($category[0]->name); ?>" <?php echo $cl_cat_film; ?>><?php echo esc_html($category[0]->name); ?></a></div>
                    <div class="title-bold-white">
                        <a href="<?php the_permalink(); ?>" <?php echo $cl_name_film; ?>> <?php the_title(); ?></a></div>
                    <div class="white-desc " <?php echo $cl_desc_film; ?>>
                        <?php echo esc_html(wp_trim_words( get_the_content(), 20, '...' )); ?>
                    </div>
                    <div class="fl-viewmore">
                        <a href="<?php the_permalink(); ?>" title="<?php esc_html_e('View more','filmmaker'); ?>" >
                            <span class="more-white" <?php echo $cl_link_film; ?>>
                                <?php esc_html_e('View more','filmmaker'); ?>
                                <i class="fa fa-long-arrow-right" <?php echo $cl_link_film; ?>></i>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </li>
        <?php endwhile; ?>
    <?php } ?>
        <?php
            $category_id = get_cat_ID( 'film' );
            $category_link = get_category_link( $category_id );
        ?>
    <?php if($film_list_checkbox_link == 'yes'){ ?>
    <div class="p-viewmore">
        <span class="more">
            <a href="<?php echo esc_html($view_more_url); ?>" title="<?php echo esc_html($view_more_text); ?>" <?php echo $cl_viewmore; ?>>
                <?php echo esc_html($view_more_text); ?><i class="fa fa-long-arrow-right" <?php echo $cl_viewmore; ?>></i>
            </a>
        </span>
    </div>
    <?php } ?>
</ul>