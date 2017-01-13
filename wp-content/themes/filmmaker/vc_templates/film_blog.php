<?php
    $film_number_post = $film_style = $film_column_in_row = $color_title_blog = $color_title_dv = $color_hover_all = '';
    extract(shortcode_atts(array(
        'film_style'            => '',
        'film_number_post'      => '',
        'film_column_in_row'    => '',
        'color_title_blog'      => '',
        'color_title_dv'        => '',
        'color_hover_all'    => '',
    ) , $atts));
    $args = array(
        'post_type' => 'post',
        'showposts' => $film_number_post,
        'orderby' => 'date',
        'film_style' => '',
    );
    $loop = new WP_Query($args);
    wp_reset_postdata();
 ?>
 <style>
    .content-blog-item a, .blog-item .blog-name a, .box-blog-hover .blog-item3 .title-desc{color: <?php echo $color_title_blog; ?>;}
    .content-blog-item .new-date, .content-blog-item .view-point .fa, .content-blog-item .view-point .fa b, .blog-item .blog-time span, .blog-item .blog-desc, .box-blog-hover .blog-item3 .gray-desc, .box-blog-hover .blog-item3 .blog-date, .box-blog-hover .blog-item3 .fa b{
        color:<?php echo $color_title_dv; ?>;
    }
    .content-blog-item .view-point .fa-comment-o{
        border-left:1px solid <?php echo $color_title_dv; ?>;
    }
    .blog-item .blog-time span:first-child, .box-blog-hover .blog-item3 .blog-date{
        border-right:1px solid <?php echo $color_title_dv; ?>;
    }
    .content-blog-item:hover .new-date, .blog-item .blog-name a:hover, .box-blog-hover .blog-item3 .title-desc:hover{
        color:<?php echo $color_hover_all; ?>;
    }
    @media only screen and (min-width: 1170px){
        .content-blog-item:hover .title-blog {
            border-left: 5px solid <?php echo $color_hover_all; ?>;
        }
    }
 </style>
        <div class="container-fluid">
            <div class="row">
                <?php
                    if($loop->have_posts()) {
                ?>
                    <?php while($loop->have_posts()) : $loop->the_post();
                     ?>
                        <?php if($film_style == 0){?>
                            <?php include( get_template_directory() . '/template/film-blog-type1.php'); ?>
                        <?php } elseif($film_style == 1){?>
                            <?php include( get_template_directory() . '/template/film-blog-type2.php'); ?>
                        <?php }elseif($film_style == 2){ ?>
                            <?php include( get_template_directory() . '/template/film-blog-type3.php'); ?>
                         <?php } ?>
                    <?php endwhile; ?>
                <?php } ?>


            </div>
        </div>

