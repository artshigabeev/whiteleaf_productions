<?php
    $number = $image = $color_text = $color_desc ='';
    extract(shortcode_atts(array(
        'number' => '',
        'image'  => '',
        'color_text'    => '',
        'color_desc'    => '',

    ), $atts));
    $event_bg = wp_get_attachment_image_src($image,'full');
    $cl_text = 'style="color:'.$color_text.'"';
    $cl_desc = 'style="color:'.$color_desc.'"';
 ?>
 <style>
    .event-item{
        <?php if($image != ''){ ?>
            background: url('<?php echo esc_url($event_bg[0]); ?>')  no-repeat;
            background-attachment: fixed;
            background-size:cover;
        <?php }else{ ?>
            background: rgba(0,0,0,0.2);
        <?php } ?>    
    }
 </style>

  <div class="event-item"  >
        <div class="event-content" <?php echo $cl_desc; ?>>
            <?php echo '<p>'.$content.'</p>'; ?>
        </div>
        <div class="e-1500" <?php echo $cl_text; ?>>
            <?php if($number == ""){
                esc_html_e('1500', 'filmmaker');
            } else { ?>
                <?php echo esc_html($number); ?>
            <?php } ?>
        </div>
    </div>