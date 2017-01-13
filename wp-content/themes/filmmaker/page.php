<?php
get_header();

if (!is_front_page()) { ?>
         <?php while ( have_posts() ) : the_post();?>
        <div class="container contain_default">
            <div class="container-box">
                <?php the_title('<h1>','</h1>',TRUE)?>
                <?php the_content();?>
            </div>
            <?php
                if ( comments_open() ) {
                    comments_template();
                }
            ?>
        </div>
    <?php endwhile;?>
<?php }else{ ?>
    <?php while ( have_posts() ) : the_post(); ?>
        <?php the_content();?>
    <?php endwhile;?>
<?php } ?>
<?php
    get_footer();
?>