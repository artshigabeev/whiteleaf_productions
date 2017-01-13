<?php
    $enable_map = $map_zoom = $contact_type = $contact_title = $contact_description = $contact_show = $contact_title_one = $contact_title_two = $contact_title_three = $contact_content_one = $contact_content_two = $contact_content_three = $contact_coordina = $contact_image = $map_style = $color_title = $color_info = $color_desc = $color_social = $bg_social = $color_hover_social = $bg_hover_social = $color_input_text = $color_submit = $bg_submit = $color_error = $color_error = $bg_all_form = $color_change_show = $bg_change_show ='';
    extract(shortcode_atts(array(
        'contact_type'          => '',
        'contact_title'         => '',
        'contact_description'   => '',
        'contact_show'          => '',
        'contact_title_one'     => '',
        'contact_title_two'     => '',
        'contact_title_three'   => '',
        'contact_content_one'   => '',
        'contact_content_two'   => '',
        'contact_content_three' => '',
        'contact_coordina'      => '',
        'contact_image'         => '',
        'map_style'             => '',
        'color_title'           => '',
        'color_info'            => '',
        'color_desc'            => '',
        'color_social'          => '',
        'bg_social'             => '',
        'color_hover_social'    => '',
        'bg_hover_social'       => '',
        'color_input_text'      => '',
        'color_submit'          => '',
        'bg_submit'             => '',
        'color_error'           => '',
        'bg_all_form'           => '',
        'color_change_show'     => '',
        'bg_change_show'        => '',
        'map_zoom'  => '',
        'enable_map' => '',
        ), $atts));

    $cl_title = 'style="color:'.$color_title.'"';
    $cl_info = 'style="color:'.$color_info.'"';
    $cl_desc = 'style="color:'.$color_desc.'"';   
    $map_zoom = !empty($map_zoom) ? (int) $map_zoom : 10;
    
 ?>
<style>
    
    #form-contact .form .info i, #form-contact2 .form .info i, #addres .form .info i, #addres .form .info i{
        color: <?php echo $color_social; ?>;
    }
    #form-contact .form .list-social li a, #form-contact2 .form .list-social li a, #addres .form .list-social li a, #addres .form .list-social li a{
        background: <?php echo $bg_social; ?>;
    }
    #form-contact .form .list-social li a:hover, #form-contact2 .form .list-social li a:hover, #addres .form .list-social li a:hover, #addres .form .list-social li a:hover{
        background: <?php echo $bg_hover_social; ?>;
    }
     #form-contact .form .list-social li a:hover i, #form-contact2 .form .list-social li a:hover i, #addres .form .list-social li a:hover i, #addres .form .list-social li a:hover i{
        color: <?php echo $color_hover_social; ?>
     }
     .ct_form input[type="text"]::-webkit-input-placeholder, input[type="email"]::-webkit-input-placeholder, textarea::-webkit-input-placeholder, .ct_form input[type="text"], input[type="email"], textarea {
        color: <?php echo $color_input_text; ?>;
     }
    .ct_form input[type="submit"]{
        background: <?php echo $bg_submit; ?>;
        color: <?php echo $color_submit; ?>;
    }
    .ct_form label i{
        color: <?php echo $color_submit; ?>;
    }
    span.wpcf7-not-valid-tip{
        color:<?php echo $color_error; ?>;
    }
    @media only screen and (min-width: 768px){
        #form-contact, #form-contact2, #addres, #addres{
            background:<?php echo $bg_all_form; ?>;
        }
    }
    .flim_mapview .show-hide, .flim_mapview .show-hide2{
        background: <?php echo $bg_change_show;  ?>;
        color:<?php echo $color_change_show; ?>;
    }
</style>
<?php if( $contact_show == 0  || $contact_show ==1 ){ ?>
    <?php  include(get_template_directory().'/template/film-contact-full.php'); ?>
<?php }elseif($contact_show== 2){ ?>
    <?php  include(get_template_directory().'/template/film-contact-half.php'); ?>

<?php } ?>