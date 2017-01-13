
<?php
	if ( '' != get_the_post_thumbnail( $post->ID ) ) {
		$pinterestimage = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
		$pinImage = $pinterestimage[0];
	} else {
		$pinImage = '';
	}
?>
<ul class="list-social">
    <li><?php echo esc_html_e('Share :','filmmaker') ?></li>
    <li ><a rel="nofollow" href="http://www.facebook.com/sharer.php?u=<?php echo urlencode(get_permalink( $post->ID )) ?>&amp;t=<?php echo htmlspecialchars(urlencode(html_entity_decode(get_the_title( $post->ID ), ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8') ?>" title="<?php esc_html_e('Share to Facebook','filmmaker')?>" onclick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=700,height=450');return false;"><i class="fa fa-facebook"></i></a><i class="cs c-icon-cresta-spinner animate-spin"></i></li>
    <li ><a rel="nofollow" href="http://twitter.com/share?text=<?php echo htmlspecialchars(urlencode(html_entity_decode(the_title_attribute( 'echo=0' ), ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8') ?>&amp;url=<?php echo  urlencode(get_permalink( $post->ID )) ?>" title="<?php esc_html_e('Share to Twitter','filmmaker')?>" onclick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=700,height=450');return false;"><i class="fa fa-twitter"></i></a><i class="cs c-icon-cresta-spinner animate-spin"></i></li>
    <li ><a rel="nofollow" href="https://plus.google.com/share?url=<?php echo  urlencode(get_permalink( $post->ID )) ?>" title="<?php esc_html_e('Share to Google Plus','filmmaker')?>" onclick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=700,height=450');return false;"><i class="fa fa-google-plus"></i></a><i class="cs c-icon-cresta-spinner animate-spin"></i></li>
	<li ><a rel="nofollow" href="http://pinterest.com/pin/create/bookmarklet/?url=<?php echo urlencode(get_permalink( $post->ID )) ?>&amp;media=<?php echo esc_attr($pinImage)?>&amp;description=<?php echo htmlspecialchars(urlencode(html_entity_decode(get_the_title( $post->ID ), ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8')?>" title="<?php esc_html_e('Share to Pinterest','filmmaker')?>" onclick="window.open(this.href, 'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=700,height=450');return false;"><i class="fa fa-pinterest"></i></a><i class="cs c-icon-cresta-spinner animate-spin"></i></li>
	<li ><a rel="nofollow" href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo  urlencode(get_permalink( $post->ID )) ?>&amp;title=<?php echo htmlspecialchars(urlencode(html_entity_decode(get_the_title( $post->ID ), ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8') ?>&amp;source=<?php echo esc_url( home_url( '/' )) ?>" title="<?php esc_html_e('Share to LinkedIn','filmmaker')?>" onclick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=700,height=450');return false;"><i class="fa fa-linkedin"></i></a><i class="cs c-icon-cresta-spinner animate-spin"></i></li>
</ul>