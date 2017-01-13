<ul class="list-social">
	<?php
		if (filmmaker_GetOption('show-social-link')) {
                    $imdb_arr = array(
                        'imdb'  => 'IMDB',
                        'imdb-pro'  => 'ImdbPRO'
                    );
			foreach(filmmaker_GetOption('show-social-link') as $key=> $social){
                            if(in_array($social, array_keys($imdb_arr))) {                               
                                echo '<li><a href="'.esc_url(filmmaker_GetOption('beau-'.$social)).'" target="_blank"><span>'.$imdb_arr[$social].'</span></a></li>';      
                                continue;
                            }
                            if(filmmaker_GetOption('beau-'.$social) != NULL){                                
                                echo '<li><a href="'.esc_url(filmmaker_GetOption('beau-'.$social)).'" target="_blank"><i class="fa fa-'.esc_attr($social).'"></i></a></li>';                                
                            } 
			}
                }
	?>
</ul>