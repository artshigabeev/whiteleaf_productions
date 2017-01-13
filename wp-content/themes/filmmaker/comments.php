<?php
if ( post_password_required() ) { return; }
$commenter = wp_get_current_commenter();
$req = get_option( 'require_name_email' );
$aria_req = ( $req ? " aria-required='true'" : '' );
?>
<div class="beau-comment">
    <h3 class="title-comment"><?php esc_html_e('Comments','filmmaker')?> (<?php echo get_comments_number()?>)</h3>
    <?php if ( have_comments() ) : ?>
    <?php wp_list_comments( array(
        'walker'        => new beau_theme_walker_comment,
        'style'         => 'ul',
        'callback'      => null,
        'end-callback'  => null,
        'type'          => 'all',
        'page'          => null,
        'avatar_size'   => 80
    ) ); ?>
    <?php endif;?>
    <div class="clearfix"></div>
    <h3 id="title_reply"><?php esc_html_e('Leave a reply','filmmaker') ?></h3>
    <div class="des"><?php esc_html_e('Should you ever have a question, please dont hesitate to send a message or reach out on our social media.','filmmaker') ?>
    </div>
    <?php
        $classCn = "";
        if ( is_user_logged_in() ) {
            $classCn = "sm-is-login";
        }
    ?>
    <div class="form-comment <?php echo($classCn) ?> ">
        <?php
        comment_form(
            array(
                'label_submit'  =>esc_html__('Submit comment','filmmaker'),
                'title_reply'   => '',
                'comment_notes_before' =>'<ul class="beau-contact beau-comment-form col-md-12 col-sm-12 col-xs-12">',
                'fields' => array(
                    'author' => '<li class="col-md-6 col-sm-6 col-xs-12"><input id="email" class="required email" name="email" type="text" placeholder="'.esc_html__('Email','filmmaker').'' . ( $req ? '*' : '' ) .'" value="' . esc_attr(  $commenter['comment_author_email'] ) . '"' . $aria_req . ' /></li>',
                    'email' => '<li class="col-md-6 col-sm-6 col-xs-12">
                    <input id="author" class="required" name="author" placeholder="'.esc_html__('Name','filmmaker').' '. ( $req ? '*' : '' ) .'" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '"' . $aria_req . ' /></li></ul>',
                ),

                'comment_field' => '<li class="message col-md-6 col-sm-6 col-xs-12 pull-right"><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" class="required" placeholder="'.esc_html__('Message *','filmmaker').'"></textarea></li>',
            )
        );
        paginate_comments_links();
        previous_comments_link();
        ?>
        <?php if ( is_singular() ) wp_enqueue_script( "comment-reply" ); ?>
    </div>
</div>
