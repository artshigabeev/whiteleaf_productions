<?php
    $beau_cover = $footer_bg = $footer_bg_url ='';
    if (filmmaker_GetOption('img-footer-bg') != NULL) {
       $footer_bg = filmmaker_GetOption('img-footer-bg');
       $footer_bg_url = $footer_bg['url'];
       $beau_cover = 'style="background:url('.$footer_bg_url.') no-repeat; background-size:cover" ';
    }
?>
<div class="footer-landing2 " id="content-ft"  <?php printf('%s', $beau_cover); ?> >
    <div class="container">
        <?php if (filmmaker_GetOption('footer-subcrible') == 2){ ?>
        <div id="subcribe" class="col-lg-12 col-sm-12">
            <div class="ft-subcri2">
                <form id="beau-subcribe" action="#"  method="get">
                    <input type="text" class="control-form" name="email" id="email" value="" placeholder="<?php echo filmmaker_GetOption('subcribe-footer'); ?>">
                    <button class="btn btn-default" type="submit" >
                        <i class="fa fa-paper-plane"></i></button>
                </form>
            </div>
             <span class="subcribe-message"></span>
        </div>
        <?php } ?>
        <?php
            if (function_exists("dynamic_sidebar")) {
                for ($i=1; $i <= 4 ; $i++) {

                    if ( is_active_sidebar( 'sidebar-footer-'.$i ) ){
                       dynamic_sidebar( 'sidebar-footer-'.$i );
                    }
                }
            }
        ?>
        <div class="footer-2">
            <div class="col-lg-12 col-xs-12 copyright centertxt">
                <span class="text-copyright">
                <?php echo filmmaker_GetOption('filmmaker-footer-text'); ?></span>
            </div>
        </div>
    </div>
</div>
