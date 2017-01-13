<ul class="list-social">
    <li><?php echo esc_html_e('Share :','filmmaker') ?></li>
    <?php
        if (filmmaker_GetOption('show-social-link')) {
            foreach(filmmaker_GetOption('show-social-link') as $key=> $social){
                if(filmmaker_GetOption('beau-'.$social)){
                    if (filmmaker_GetOption('beau-'.$social) != '') {
                       echo '<li><a href="'.esc_url(filmmaker_GetOption('beau-'.$social)).'" target="_blank"><i class="fa fa-'.esc_attr($social).'"></i></a></li>';
                    }
                }
            }
        }
    ?>
</ul>