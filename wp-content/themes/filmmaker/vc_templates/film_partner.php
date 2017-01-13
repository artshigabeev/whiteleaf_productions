<?php
    $partner_number = $partner_style ='';
    extract(shortcode_atts(array(
        'partner_number' => '',
        'partner_style' => '',
        ), $atts));
    $args = array(
        'post_type' => 'partner',
        'showposts' => $partner_number,
        'orderby'   => 'date',
    );
    $loop = new WP_Query($args);
    wp_reset_postdata();
 ?>
 <section class="partner-item">
        <div class="container">
            <div class="row">
                 <?php
                    if($loop->have_posts()){
                 ?>
                <?php while($loop->have_posts()) :  $loop->the_post(); ?>
                    <div class="<?php echo esc_html($partner_style); ?>">
                        <div class="partner-img wow fadeInUp" data-wow-delay="0.3s">

                            <a href="<?php the_field('url'); ?>" title="<?php esc_html_e('partner','filmmaker'); ?>">
                                    <?php the_post_thumbnail(); ?>
                             </a>
                        </div>
                    </div>
                <?php endwhile; ?>
                <?php } ?>
            </div>
        </div>
    </section>