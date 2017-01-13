<div class="footer-landing f-type4 f-home1">
    <?php if (filmmaker_GetOption('footer-subcrible') == 2){ ?>
    <div class="subcribe">
        <div class="ft-subcri2">
            <form id="beau-subcribe" action="#"  method="get">
                <input type="text" class="control-form" name="email" id="email" value="" placeholder="<?php echo filmmaker_GetOption('subcribe-footer'); ?>">
                <button class="btn btn-default" type="submit" >
                    <i class="fa fa-paper-plane"></i></button>
            </form>
        </div>
        <div class="clearfix"></div>
    </div>
    <?php } ?>
    <div class="footer-social">
        <?php get_template_part('template/social','author' );?>
    </div>
    <div class="copyright"><span class="text-copyright">
        <?php
        echo filmmaker_GetOption('filmmaker-footer-text'); ?></span>
    </div>

</div>