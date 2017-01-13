<?php while ($wp_query->have_posts()) : $wp_query->the_post(); ?>

<div class="container-fluid nopadding">
    <div class="wrapper-gallery-detail">
        <div class="centertxt">
            <div class="title-bold detail">
                <?php $number= rand(11,999); ?>
                <?php if(filmmaker_GetOption('title-single-gallery') !=''){
                        $g_text = filmmaker_GetOption('title-single-gallery');
                    }else{
                        $g_text = "Film - Gone Girl Gallery";
                     } ?>
                    <?php echo esc_html($g_text); ?>
            </div>
            <div class="list-social gray-desc">
                <ul>
                    <?php get_template_part('template/social','list'); ?>
                </ul>
            </div>
        </div>
        <ul class="row list-item">
            <?php if(get_field('gallery_film')){
                   while(has_sub_field('gallery_film')){ ?>
                    <li class="col-lg-3 col-md-3 col-sm-6 col-xs-12 item">
                        <img src="<?php the_sub_field('image_gallery_film') ?>" alt="<?php the_title(); ?>" >
                        <?php if(!empty(get_sub_field('video_url'))) : ?>
                        <?php
                        echo '
                                   <img class="click_play" alt="" src="'.get_template_directory_uri().'/asset/images/play.png">';
                        ?>
                            <div class="film-if">
                                <?php global $wp_embed; ?>
                                <?php  
                                   $_url = get_sub_field('video_url');
                                   echo $wp_embed->run_shortcode('[embed  width="900" height="542"]'.$_url.'[/embed]');
                                   
                                ?>
                            </div>   
                            
                        <?php endif; ?>
                    </li>
                <?php } ?>
            <?php  } ?>
        </ul>
<?php endwhile; ?>
    </div>
</div>
<?php if(filmmaker_GetOption('g-lightbox') == 2){ ?>
 <div class="modal fade g_fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="w_popup">
            <div class="modal-dialog" style="width:100%">
            <div class="modal-content">         
              <div class="modal-body">                
              </div>
            </div><!-- /.modal-content -->
          </div><!-- /.modal-dialog -->
        </div>         
    </div><!-- /.modal -->
<?php } else {} ?>

