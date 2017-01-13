<?php
// Some function want fhor this theme
if ( function_exists( 'add_image_size' ) ) {
    add_image_size( 'image-content', 530, 340, true ); //(cropped)
}
//Remove comment field url email
if(!function_exists('remove_comment_fields')){
    function remove_comment_fields($fields) {
        unset($fields['url']);
        return $fields;
    }
    add_filter('comment_form_default_fields','remove_comment_fields');
}

//Them mot vai kich thuoc hinh anh
function beau_imageSize(){
    add_image_size('DR', 565, 780, true);
    add_image_size('GD', 292, 136, true);
}
add_action( 'after_setup_theme', 'beau_imageSize' );


//Function convert
//Add custom class next prev nav detail
add_filter('next_post_link', 'beau_link_attributes_next');
add_filter('previous_post_link', 'beau_link_attributes');
function beau_link_attributes($output) {
    $injection = 'class="next-back prev-post"';
    return str_replace('<a href=', '<a '.$injection.' href=', $output);
}
function beau_link_attributes_next($output) {
    $injection = 'class="next-back next-post"';
    return str_replace('<a href=', '<a '.$injection.' href=', $output);
}

function get_excerpt_by_id($post_id){
    $the_post = get_post($post_id); //Gets post ID
    $the_excerpt = $the_post->post_content; //Gets post_content to be used as a basis for the excerpt
    $excerpt_length = 35; //Sets excerpt length by word count
    $the_excerpt = strip_tags(strip_shortcodes($the_excerpt)); //Strips tags and images
    $words = explode(' ', $the_excerpt, $excerpt_length + 1);

    if(count($words) > $excerpt_length) :
        array_pop($words);
        array_push($words, '...');
        $the_excerpt = implode(' ', $words);
    endif;

    $the_excerpt = '<p>' . $the_excerpt . '</p>';

    return $the_excerpt;
}


//Check extension
function findExtension ($filename)
{
    $filename = strtolower($filename) ;
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    // var_dump($ext);
    return $ext;
}
?>