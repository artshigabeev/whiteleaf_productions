<?php
/** COMMENTS WALKER */
// return;
if (!class_exists('beau_Theme_walker_comment')) {
	class beau_Theme_walker_comment extends Walker_Comment {

		// init classwide variables
		var $tree_type = 'comment';
		var $db_fields = array( 'parent' => 'comment_parent', 'id' => 'comment_ID' );
		function __construct() { ?>

			<!-- <h3 id="comments-title">Comments</h3> -->
			<ul class="list-comment">

		<?php }

		/** START_LVL
		 * Starts the list before the CHILD elements are added. Unlike most of the walkers,
		 * the start_lvl function means the start of a nested comment. It applies to the first
		 * new level under the comments that are not replies. Also, it appear that, by default,
		 * WordPress just echos the walk instead of passing it to &$output properly. Go figure.  */
		function start_lvl( &$output, $depth = 0, $args = array() ) {
			$GLOBALS['comment_depth'] = $depth + 1; ?>
			<ul class="sub-list-comment">
		<?php }

		/** END_LVL
		 * Ends the children list of after the elements are added. */
		function end_lvl( &$output, $depth = 0, $args = array() ) {
			$GLOBALS['comment_depth'] = $depth + 1; ?>
			</ul><!-- /.children -->
		<?php }

		/** START_EL */
		function start_el( &$output, $comment, $depth = 0, $args = array(), $id = 0) {
			$depth++;
			$add_below ="";
			$GLOBALS['comment_depth'] = $depth;
			$GLOBALS['comment'] = $comment;
			$parent_class = ( empty( $args['has_children'] ) ? 'main-comment' : '' ); ?>
			<li <?php comment_class( $parent_class ); ?> id="comment-<?php esc_attr(comment_ID()) ?>">
				<div id="comment-body-<?php esc_attr(comment_ID())?>" class="comment-body">
                    <div class="avt ">
					   <?php echo ( $args['avatar_size'] != 0 ? get_avatar( $comment, $args['avatar_size'] ) :'' ); ?>
                    </div>
                    <div class="txt">
                        <div class="author-comment"><?php echo get_comment_author_link(); ?>
                        </div>
                        <div class="reply-time">
                            <div class="time pull-left">
                                Posted <?php comment_date(); ?>
                            </div>
                            <div class="rep pull-left">
                                <?php $reply_args = array(
                                'add_below' => $add_below,
                                'depth' => $depth,
                                'reply_text'=> '<i class="fa fa-reply"></i>',
                                'max_depth' => $args['max_depth'] );
                                comment_reply_link( array_merge( $args, $reply_args ) );  ?><?php //edit_comment_link( '(Edit)' ); ?>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="comment-message" id="comment-content-<?php esc_attr(comment_ID()); ?>">
                            <?php if( !$comment->comment_approved ) : ?>
                            <em class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'filmmaker' );?></em>
                            <?php else: comment_text(); ?>
                            <?php endif; ?>
                        </div>
                    </div>
				</div><!-- /.comment-body -->
			</li>
		<?php }

		function end_el(&$output, $comment, $depth = 0, $args = array() ) { ?>
		<?php }
		/** DESTRUCTOR
		 * I just using this since we needed to use the constructor to reach the top
		 * of the comments list, just seems to balance out :) */
		function __destruct() { ?>

		</ul><!-- /#comment-list -->
		<?php }
	}
}
?>