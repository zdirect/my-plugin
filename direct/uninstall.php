<?php 

$direct = get_posts([
    'post_type' => ['direct', 'agent'],
    'numberposts' => -1
])
foreach($direct as $item){
    wp_delet_post($item->ID, true);
}