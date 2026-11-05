<?php
$_SERVER['HTTP_HOST']='127.0.0.1:8080';
$_SERVER['SERVER_NAME']='127.0.0.1';
require '/var/www/html/wp-load.php';
function verify_team($condition, $label) { if(!$condition) { throw new RuntimeException($label); } echo "PASS: $label\n"; }
function team_markup() { ob_start(); include get_theme_file_path('front-page.php'); return ob_get_clean(); }
$previous=get_theme_mod('team_page',false);
$team_test_post_id=wp_insert_post(array('post_type'=>'page','post_status'=>'draft','post_title'=>'Проверка команды','post_content'=>'team_content_smoke_unique'),true);
if(is_wp_error($team_test_post_id)) { throw new RuntimeException('Could not create team test page'); }
try {
 set_theme_mod('team_page',$team_test_post_id);
 verify_team(!str_contains(team_markup(),'team_content_smoke_unique'),'draft team page hidden');
 wp_update_post(array('ID'=>$team_test_post_id,'post_status'=>'publish'));
 verify_team(str_contains(team_markup(),'team_content_smoke_unique'),'published team content displayed');
 wp_update_post(array('ID'=>$team_test_post_id,'post_password'=>wp_generate_password()));
 verify_team(!str_contains(team_markup(),'team_content_smoke_unique'),'password protected team page hidden');
} finally {
 if($previous===false) { remove_theme_mod('team_page'); } else { set_theme_mod('team_page',$previous); }
 wp_delete_post($team_test_post_id,true);
}
