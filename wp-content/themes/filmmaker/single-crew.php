<?php get_header() ;?>
<div class="container">
<?php if (have_posts()) { the_post(); ?>
<div class="col-md-6 col-sm-6 col-xs-12 team-avatar">
    <div class="thumb-team">
        <?php the_post_thumbnail('large');?>
    </div>
</div>
<div class="col-md-6 col-sm-6 col-xs-12 info-team">
    <div class="title-member">
        <h3><?php the_title();?></h3>
        <span class="jobs"><?php the_field('crew_job');?></span>
    </div>
    <div class="content-team">
        <?php echo the_content();?>
        <div class="clearfix"></div>
        <ul class="list-social">
            <?php
                if(have_rows('crew_social')){
                    while( have_rows('crew_social') ) : the_row();
            ?>
                <li><a href="<?php echo  get_sub_field('crew_social_link');?>"><i class ="fa <?php echo  get_sub_field('screw_social_icon');?>"></i></a></li>
            <?php
                endwhile;
                }
            ?>

        </ul>
    </div>
</div>
<?php } ?>
<?php get_footer(); ?>
