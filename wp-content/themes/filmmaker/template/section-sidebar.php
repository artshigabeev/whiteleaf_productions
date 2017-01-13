<?php
$noimage = "";
?>

<section class="blog-03">
    <div class="container">
        <div class="col-lg-8 col-md-9 col-sm-12 col-xs-12 nopadding">
            <ul class="list-post-03">
                <?php if(have_posts()){ while ( have_posts() ) : the_post();?>
                <?php
                    if ($noimage) {
                        switch ($noimage) {
                        case 'noimage':
                            $post_Class = "no-img-title";
                            $post_Class2 = "no-img";
                            break;
                        default:
                            $post_Class = "";
                            $post_Class2 ="";
                            break;
                        }
                    }else{
                        $post_Class ="";
                        $post_Class2 ="";
                    }
                ?>
                <li class="item">
                    <div class="post-img <?php printf('%s', $post_Class2);?>">
                        <a href="<?php the_permalink();?>">
                            <div class="date-post">
                                <ul>
                                    <li><?php echo get_the_date('d'); ?></li>
                                    <li><?php echo get_the_date('m'); ?></li>
                                </ul>
                            </div>
                            <?php
                                if (has_post_thumbnail()) {
                                   the_post_thumbnail();
                                }else{
                                    echo '<img alt="'.get_the_title().'" src="http://placehold.it/940x540" alt="'.esc_html__('No image','filmmaker').'">';
                                 }?>
                        </a>
                    </div>
                    <div class="post-t <?php printf('%s', $post_Class);?>">
                        <div class="info">
                            <ul class="more-info">
                                <li><a href="<?php the_permalink();?>"><?php the_category( ', ' ); ?></a></li>
                                <li class="name1"><?php esc_html_e('by:','filmmaker'); ?><?php the_author(); ?></li>
                                <li><a href="<?php the_permalink();?>"><?php filmmaker_setPostViews(get_the_ID()); ?> <?php $view = filmmaker_getPostViews(get_the_ID()); ?><?php echo esc_html($view); ?> <?php (int) $view > 1 ? esc_html_e('VIEWS','filmmaker') : esc_html_e('VIEW','filmmaker'); ?></a></li>
                                <li id="template_share" class="template_share"><a href="#" title="<?php esc_html_e('share','filmmaker'); ?>"><i class="fa fa-share-alt"></i></a>
                                     <?php get_template_part('template/social','share'); ?>
                                </li>
                            </ul>
                        </div>
                        <div class="title-post">
                            <a href="<?php the_permalink();?>">
                            <?php echo get_the_title(); ?>
                            </a>
                        </div>
                    </div>
                </li>
                <?php endwhile;
                 } ?>
            </ul>
            <div  class="centertxt pagi col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?php echo filmmaker_pagination($wp_query); ?>
            </div>
        </div>
        <div class="side-bar side-no-padding col-lg-4 col-md-3 hidden-sm hidden-xs">
            <?php get_sidebar(); ?>
        </div>
    </div>
</section>
