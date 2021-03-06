<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html <?php language_attributes(); ?>> <!--<![endif]-->
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <!--[if lt IE 9]>
        <script src="<?php echo esc_url( get_template_directory_uri() ); ?>/asset/js/html5.js"></script>
    <![endif]-->
    <!-- Mobile Specific Metas
    ================================================== -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <?php wp_head(); ?>
</head>
    <?php
        $customHeader = get_post_meta(get_the_ID(),'_beautheme_your_custom_header', TRUE);
    ?>
<body <?php body_class(); ?> id="wrapper">
<?php
        // Check header page
        if ($customHeader) {
            switch ($customHeader) {
            case 'header_imagecover':
                $header_Class =" header-flim header-white";
                break;
            case 'header_slider':

                $header_Class = " header-flim header-white";
                break;
            default:
                $header_Class = "header-black";
                break;
            }
        }else{
            $header_Class ="header-black";
        }
    ?>
<?php $fix_menu= "";
    if(filmmaker_GetOption('header-fixed-menu') == 2){
        $fix_menu = "mn-fixed";
    }
 ?>
<section class="fl-main-menu">
    <?php if (!is_404()) : ?>
    <header class=" <?php printf('%s', $header_Class);?>  <?php echo $fix_menu; ?>" >
        <div class="bg-fl-search">
        </div>
            <?php
                $header = '';
                $header_page_setting = get_post_meta( get_the_ID(), '_beautheme_custom_header', TRUE );
                if (filmmaker_GetOption('header-type') != NULL) {
                    $header =  filmmaker_GetOption('header-type');
                }
                if ($header_page_setting) {
                    $header = $header_page_setting;
                }
                if (!$header) {
                    $header = 'default';
                }
                get_template_part('template/header', $header);
            ?>
    </header>
    <?php endif ?>
    <?php
        // Check header page
        if ($customHeader) {
            switch ($customHeader) {
            case 'header_imagecover':
                get_template_part('template/header', 'cover');
                break;
            case 'header_slider':
                get_template_part('template/header', 'slider');
                break;
            }
        }

    ?>
</section>

