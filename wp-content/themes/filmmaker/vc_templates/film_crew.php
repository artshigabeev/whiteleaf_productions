<?php
     $crew_select = $crew_id= $crew_number = $crewlist_checkbox = $crewlist_textfield = $crewlist_textlink = $crewlist_link = $crewlist_image = $color_one_name = $color_one_job = $color_one_desc = $color_one_social = $bg_one_social = $hover_color_one_social = $hover_bg_one_social = $color_tab_name = $color_tab_job = $color_tab_desc = $color_tab_social = $bg_tab_social = $hover_color_tab_social = $hover_bg_tab_social = $color_list_name = $color_list_job = $color_list_desc = $color_list_social = $bg_list_social = $hover_color_list_social = $hover_bg_list_social ='';
    extract(shortcode_atts(array(
        'crew_select'           => '',
        'crew_id'               => '',
        'crew_number'           => '',
        'crewlist_checkbox'     => '',
        'crewlist_textfield'    => '',
        'crewlist_textlink'     => '',
        'crewlist_link'         => '',
        'crewlist_image'        => '',
        'color_one_name'        => '',
        'color_one_job'         => '',
        'color_one_desc'        => '',
        'color_one_social'      => '',
        'bg_one_social'         => '',
        'hover_color_one_social'=> '',
        'hover_bg_one_social'   => '',
        'color_tab_name'        => '',
        'color_tab_job'         => '',
        'color_tab_desc'        => '',
        'color_tab_social'      => '',
        'bg_tab_social'         => '',
        'hover_color_tab_social'=> '',
        'hover_bg_tab_social'   => '',
        'color_list_name'        => '',
        'color_list_job'         => '',
        'color_list_desc'        => '',
        'color_list_social'      => '',
        'bg_list_social'         => '',
        'hover_color_list_social'=> '',
        'hover_bg_list_social'   => '',
        ), $atts));
    $type= 'crew';
    //change style one crew 
    $cl_one_name = 'style="color:'.$color_one_name.'"';
    $cl_one_job = 'style="color:'.$color_one_job.'"';
    $cl_one_desc = 'style="color:'.$color_one_desc.'"';
    $cl_tab_name = 'style="color:'.$color_tab_name.'"';
    $cl_tab_job = 'style="color:'.$color_tab_job.'"';
    $cl_tab_desc = 'style="color:'.$color_tab_desc.'"';
    $cl_list_name = 'style="color:'.$color_list_name.'"';
    $cl_list_job = 'style="color:'.$color_list_job.'"';
    $cl_list_desc = 'style="color:'.$color_list_desc.'"';
 ?>
 <style>
    /*style one crew*/ 
     .director-social .social a{
        color: <?php echo $color_one_social; ?>;
     }
     .director-social .social{
        background: <?php echo $bg_one_social; ?>;
     }
    .director-social .social:hover{
        background: <?php echo $hover_bg_one_social; ?>;
    }
    .director-social .social:hover a{
        color: <?php echo $hover_color_one_social; ?>;
    }
    /*style tabs crew*/ 
    .director-social-tab .social a{
        color: <?php echo $color_tab_social; ?>;
     }
    .director-social-tab .social{
        background: <?php echo $bg_tab_social; ?>;
     }
    .director-social-tab .social:hover{
        background: <?php echo $hover_bg_tab_social; ?>;
    }
    .director-social-tab .social:hover a{
        color: <?php echo $hover_color_tab_social; ?>;
    }
    /*style list crew*/
    .crew-social .social a{
        color: <?php echo $color_list_social; ?>;
     }
    .crew-social .social{
        background: <?php echo $bg_list_social; ?>;
     }
    .crew-social .social:hover{
        background: <?php echo $hover_bg_list_social; ?>;
    }
    .crew-social .social:hover a{
        color: <?php echo $hover_color_list_social; ?>;
    }
 </style>
<section class="crew-item pd-crew">
    <div class="container">
        <div class="row">
            <?php if($crew_select == 0){ ?>
                <?php include( get_template_directory() . '/template/section-crewlist.php'); ?>

            <?php } ?>
            <?php if($crew_select == 1){ ?>
                <?php include( get_template_directory() . '/template/section-crewsingle.php'); ?>

            <?php } ?>
             <?php if($crew_select == 2){ ?>
                <?php include( get_template_directory() . '/template/section-crewtab.php'); ?>

            <?php } ?>
            <?php if($crew_select == 3){ ?>
                <?php include( get_template_directory() . '/template/section-crewdouble.php'); ?>
            <?php } ?>
        </div>
    </div>
</section>
 


