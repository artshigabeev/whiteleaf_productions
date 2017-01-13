<div class="fl-footer3">
    <div class="fl-footer" >
        <div class="container">
            <div class="row">
                <?php
                    if (function_exists("dynamic_sidebar")) {
                        for ($i=1; $i <= 4 ; $i++) {

                            if ( is_active_sidebar( 'sidebar-footer-'.$i ) ){
                               dynamic_sidebar( 'sidebar-footer-'.$i );
                            }

                        }
                    }
                ?>
            </div>
            <?php if (filmmaker_GetOption('footer-subcrible') == 2){ ?>
            <div class="row ">
                <div class="col-md-12 col-sm-12 col-xs-12 ">
                    <div class="ft-subcri-box">
                        <div class="ft-subcri">
                            <form id="beau-subcribe" action="#"  method="get">
                                <input type="text" class="control-form" name="email" id="email" value="" placeholder="<?php echo filmmaker_GetOption('subcribe-footer'); ?>">
                                <button class="btn btn-default" type="submit" >
                                    <i class="fa fa-paper-plane"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <span class="subcribe-message"></span>
                </div>
            </div>
            <?php } ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="copyright">
                        <span class="text-copyright">
                            <?php echo filmmaker_GetOption('filmmaker-footer-text'); ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>