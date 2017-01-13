<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCOeGmyX-gl-BqGDrCvYd1qeEWstO9553Y&amp;sensor=false&amp;libraries=places,geometry&amp;v=3.18"></script>
    <?php if($enable_map) :  ?>
    <script type="text/javascript">
        "use strict";
        google.maps.event.addDomListener(window, 'load', init);
        function init() {
            var mapOptions = {
                zoom: 8,
                scrollwheel: false,
                // mapTypeId: google.maps.MapTypeId.ROADMAP,
                center: new google.maps.LatLng(<?php
                         if($contact_coordina == ''){
                           echo esc_html('40.5500, -73.9400');
                         }else{
                            echo esc_html($contact_coordina);
                         }
                     ?>),
                styles: <?php if($map_style ==''){
                    echo '[{"featureType":"landscape","stylers":[{"saturation":-100},{"lightness":65},{"visibility":"on"}]},{"featureType":"poi","stylers":[{"saturation":-100},{"lightness":51},{"visibility":"simplified"}]},{"featureType":"road.highway","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"road.arterial","stylers":[{"saturation":-100},{"lightness":30},{"visibility":"on"}]},{"featureType":"road.local","stylers":[{"saturation":-100},{"lightness":40},{"visibility":"on"}]},{"featureType":"transit","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"administrative.province","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"labels","stylers":[{"visibility":"on"},{"lightness":-25},{"saturation":-100}]},{"featureType":"water","elementType":"geometry","stylers":[{"hue":"#ffff00"},{"lightness":-25},{"saturation":-97}]}]';
                }{
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
    </script>
    <?php endif; ?>
    <div class="flim_mapview  mapview1 col-lg-6 col-sm-6 col-xs-12">
        <div class="map-addres">
         
            <div id="flim_mapview"></div>
            <?php if($enable_map) : ?>
            <span class="show-hide btn-active"><?php esc_html_e('Show info','filmmaker'); ?> </span>
            <script>
                (function($){
                    'use strict';
                    $('.show-hide').click(function(event) {
                        if($(this).hasClass('btn-active')){
                            $(this).removeClass('btn-active').addClass('btn-hidden').html('<?php esc_html_e('Show map','filmmaker'); ?> <i class="icon-ct fa fa-map-marker"></i>');
                            $('#addres').fadeIn("slow");
                        }else{
                            $(this).addClass('btn-active').removeClass('btn-hidden').html('<?php esc_html_e('Show Info','filmmaker'); ?> <i class="icon-ct fa fa-paper-plane"></i> ');
                            $('#addres').fadeOut("slow");
                        }
                    });
                })(jQuery)
            </script>
            <?php endif; ?>
            <div id="addres" <?php if(!$enable_map) {echo 'style="display:block;"';} ?> >
                <div class="form">
                    <div class="item-add">
                        <?php if($contact_title_one == ''){ ?>
                         <?php } else { ?>
                            <div class="title-white" <?php echo $cl_title; ?>>
                                <?php echo esc_html($contact_title_one); ?>
                            </div>
                         <?php } ?>

                         <?php if($contact_content_one == ''){ ?>
                         <?php } else { ?>
                            <div class="white-desc" <?php echo $cl_info; ?>>
                                <?php echo esc_html($contact_content_one); ?>
                            </div>
                         <?php } ?>
                    </div>
                    <div class="item-add">
                        <?php if($contact_title_two == ''){ ?>
                         <?php } else { ?>
                            <div class="title-white" <?php echo $cl_title; ?>>
                                <?php echo esc_html($contact_title_two); ?>
                            </div>
                         <?php } ?>
                         <?php if($contact_content_two == ''){ ?>
                         <?php } else { ?>
                            <div class="white-desc" <?php echo $cl_info; ?>>
                                <?php echo esc_html($contact_content_two); ?>
                            </div>
                         <?php } ?>
                    </div>
                    <div class="item-add">
                        <?php if($contact_title_three == ''){ ?>
                         <?php } else { ?>
                            <div class="title-white" <?php echo $cl_title; ?>>
                                <?php echo esc_html($contact_title_three); ?>
                            </div>
                         <?php } ?>
                         <?php if($contact_content_three == ''){ ?>
                         <?php } else { ?>
                            <div class="white-desc" <?php echo $cl_info; ?>>
                                <?php echo esc_html($contact_content_three); ?>
                            </div>
                         <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- end map -->
    <div class="col-lg-6 col-sm-6 col-xs-12 nopadding">
        <div id="form-contact2">
            <div class="form container">
                <div class="fl-row">
                    <?php if ($contact_title == ''){ ?>
                    <?php }else{ ?>
                        <div class="title-white" <?php echo $cl_title; ?>><?php echo esc_html($contact_title); ?></div>
                    <?php } ?>

                    <?php if($contact_description == '') { ?>
                     <?php }else{ ?>
                        <div class="white-desc address-info" <?php echo $cl_desc ?>>
                            <?php echo esc_html($contact_description); ?>
                        </div>
                     <?php } ?>
                    <div class="icon">
                        <div class="list-social gray-desc padding5">
                            <div class="info">
                                 <?php get_template_part('template/social','author' );?>
                            </div>
                        </div><!--End list-social-->
                    </div>
                    <div class="ct_form">
                         <?php  echo do_shortcode('[contact-form-7 id=" '.$contact_type.' "]'); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>