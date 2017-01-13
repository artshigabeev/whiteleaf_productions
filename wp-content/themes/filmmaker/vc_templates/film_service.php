<?php
    $icon_service = $name_service = $description_service = $border_service = $color_name_service = $color_description_service = $color_icon_service = $hover_border_service = $color_border_service = $hover_color_icon_service ='';
    extract(shortcode_atts(array(
        'icon_service'  =>'',
        'name_service' => '',
        'description_service' => '',
        'border_service' => '',
        'color_name_service' => '',
        'color_description_service' => '',
        'color_icon_service'    => '',
        'color_border_service'  => '',
        'hover_border_service'         => '',
        'hover_color_icon_service' => '',
    ), $atts));
    $cl_name_service = 'style="color:'.$color_name_service.'"';
    $cl_description_service = 'style="color:'.$color_description_service.'"';
    $cl_icon_service = 'style="color:'.$color_icon_service.'"';
    //$cl_border_service = 'style="color:'.$color_border_service.'"';
    //$cl_hover_service = 'style="color:'.$hover_service.'"';
    //$cl_hover_icon_service= 'style="color:'.$hover_color_icon_service.'"';
 ?>
 <style>
     .service-content:hover .service-icon{
           color:<?php echo  $hover_color_icon_service; ?>; 
     }
     .box-content-hover{
        border: 1px solid <?php echo $color_border_service; ?>;
     }
     .box-content-hover:hover{
        border: 1px solid <?php echo $hover_border_service; ?>
     }
 </style>
 <div class="service-content <?php echo esc_html($border_service); ?> wow fadeInUp" data-wow-delay="0.4s">
    <div class="service-icon" <?php echo $cl_icon_service; ?>>
    <span class="<?php echo esc_html($icon_service); ?>" ></span></div>
    <div class="service-name" <?php echo $cl_name_service; ?> >
        <?php echo esc_html($name_service); ?>
    </div>
    <div class="service-desc" <?php echo $cl_description_service; ?>>
        <?php echo esc_html($description_service);  ?>
    </div>
</div>