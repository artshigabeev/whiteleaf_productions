
<section>
    <?php if($enable_map) :  ?>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCOeGmyX-gl-BqGDrCvYd1qeEWstO9553Y&amp;sensor=false&amp;libraries=places,geometry&amp;v=3.18"></script>
    <script type="text/javascript">
        (function($){
            "use strict";
            google.maps.event.addDomListener(window, 'load', init);
            function init() {
                var mapOptions = {
                    zoom: 10,
                    scrollwheel: false,
                    center: new google.maps.LatLng(<?php
                         if($contact_coordina == ''){
                           echo esc_html('40.5500, -73.9400');
                         }else{
                            echo esc_html($contact_coordina);
                         }
                     ?>),
                    styles: <?php if($map_style ==''){
                        echo '[{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"hue":"#000000"},{"saturation":-100},{"lightness":100},{"visibility":"off"}]},{"featureType":"all","elementType":"labels.icon","stylers":[{"hue":"#000000"},{"saturation":-100},{"lightness":100},{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"hue":"#000000"},{"saturation":-100},{"lightness":100},{"visibility":"on"}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"hue":"#D1D3D4"},{"saturation":-88},{"lightness":-7},{"visibility":"on"}]},{"featureType":"landscape","elementType":"labels","stylers":[{"hue":"#939598"},{"saturation":-91},{"lightness":-34},{"visibility":"on"}]},{"featureType":"road","elementType":"geometry","stylers":[{"hue":"#414042"},{"saturation":-98},{"lightness":-60},{"visibility":"on"}]},{"featureType":"poi","elementType":"all","stylers":[{"hue":"#E3EBE5"},{"saturation":-61},{"lightness":57},{"visibility":"off"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"hue":"#E3EBE5"},{"saturation":-100},{"lightness":57},{"visibility":"on"}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"visibility":"off"}]},{"featureType":"administrative.country","elementType":"all","stylers":[{"hue":"#E3EBE5"},{"saturation":-100},{"lightness":81},{"visibility":"off"}]},{"featureType":"administrative.province","elementType":"all","stylers":[{"hue":"#E3EBE5"},{"saturation":-100},{"lightness":81},{"visibility":"off"}]},{"featureType":"administrative.locality","elementType":"geometry","stylers":[{"hue":"#FFFFFF"},{"saturation":0},{"lightness":100},{"visibility":"on"}]},{"featureType":"administrative.locality","elementType":"labels","stylers":[{"hue":"#939598"},{"saturation":2},{"lightness":59},{"visibility":"on"}]},{"featureType":"administrative.neighborhood","elementType":"labels","stylers":[{"hue":"#939598"},{"saturation":-100},{"lightness":16},{"visibility":"on"}]},{"featureType":"administrative.neighborhood","elementType":"all","stylers":[{"hue":"#939598"},{"saturation":-100},{"lightness":16},{"visibility":"on"}]},{"featureType":"administrative.land_parcel","elementType":"all","stylers":[{"hue":"#939598"},{"saturation":-100},{"lightness":16},{"visibility":"simplified"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"hue":"#939598"},{"saturation":-98},{"lightness":-8},{"visibility":"on"}]},{"featureType":"road.highway","elementType":"labels","stylers":[{"hue":"#FFFFFF"},{"saturation":-100},{"lightness":100},{"visibility":"off"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"hue":"#6D6E71"},{"saturation":-98},{"lightness":-43},{"visibility":"on"}]},{"featureType":"road.arterial","elementType":"labels","stylers":[{"hue":"#FFFFFF"},{"saturation":-100},{"lightness":100},{"visibility":"off"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"hue":"#000000"},{"saturation":-100},{"lightness":-100},{"visibility":"on"}]},{"featureType":"road.local","elementType":"labels","stylers":[{"hue":"#FFFFFF"},{"saturation":-100},{"lightness":100},{"visibility":"off"}]}]';
                    }else{
                        echo $map_style;
                    } ?>
                };
                var mapElement = document.getElementById('flim_mapview');
                var map = new google.maps.Map(mapElement, mapOptions);
                var marker = new google.maps.Marker({
                    position: new google.maps.LatLng(<?php
                         if($contact_coordina == ''){
                           echo esc_html('40.5500, -73.9400');
                         }else{
                            echo esc_html($contact_coordina);
                         }
                     ?>),
                    map: map,
                    icon: '<?php if($contact_image == ""){ ?><?php echo get_template_directory_uri(); ?>/asset/images/icon_map.png<?php }else{echo wp_get_attachment_url($contact_image);}?>',
                    title: 'Map title'
                });
                map.setZoom(<?php echo $map_zoom  ?>);
             }
        })(jQuery)
    </script>
    <?php endif; ?>
    <div class="flim_mapview col-lg-12 col-sm-12 col-xs-12">
        <div id="flim_mapview"></div>
        <?php if($enable_map) : ?>
            <span class="show-hide btn-active"><?php esc_html_e('Show info','filmmaker'); ?> </span>
            <script>
                (function($){
                    'use strict';
                    $('.show-hide').click(function(event) {
                        if($(this).hasClass('btn-active')){
                            $(this).removeClass('btn-active').addClass('btn-hidden').html('<?php esc_html_e('Show map','filmmaker'); ?> <i class="icon-ct fa fa-map-marker"></i>');
                            $('#form-contact').fadeIn("slow");
                        }else{
                            $(this).addClass('btn-active').removeClass('btn-hidden').html('<?php esc_html_e('Show Info','filmmaker'); ?> <i class="icon-ct fa fa-paper-plane"></i> ');
                            $('#form-contact').fadeOut("slow");
                        }
                    });
                })(jQuery)
            </script>
        <?php endif; ?>    
        <div id="form-contact" <?php if(!$enable_map) echo 'style="display: block;"'; ?> >
            <div class="form container">
                <div class="fl-row">
                    <div class="title-white" <?php echo $cl_title; ?>><?php echo esc_html($contact_title); ?></div>
                    <div class="address-info white-desc"  <?php echo $cl_info; ?>>
                            <div>
                                <?php echo esc_html($contact_title_one); ?>
                                <?php echo esc_html($contact_content_one); ?>
                            </div>
                            <div>
                                <?php echo esc_html($contact_title_two); ?>
                                <?php echo esc_html($contact_content_two); ?>
                            </div>
                            <div>
                                <?php echo esc_html($contact_title_three); ?>
                                <?php echo esc_html($contact_content_three); ?>
                            </div>

                    </div>
                    <div class="icon">
                        <div class="list-social gray-desc">
                            <div class="info">
                                <?php get_template_part('template/social','author' );?>
                            </div>
                        </div><!--End list-social-->
                    </div>
                    <?php if($contact_description == '') { ?>
                     <?php }else{ ?>
                        <div class="white-desc address-info" <?php echo $cl_desc; ?>><?php echo esc_html($contact_description); ?></div>
                     <?php } ?>

                    <div class="ct_form">
                        <?php  echo do_shortcode('[contact-form-7 id=" '.$contact_type.'"]'); ?>

                    </div>
                </div>
            </div>
        </div><!-- form-contact -->
    </div><!-- flim_mapview -->
<!-- end map -->
</section>