<?php
// Run ONLY inside the local Docker service: docker compose exec -T wordpress php < scripts/setup-local.php
define('WP_INSTALLING', true);
$_SERVER['HTTP_HOST'] = '127.0.0.1:8080';
$_SERVER['SERVER_NAME'] = '127.0.0.1';
$_SERVER['SERVER_PORT'] = '8080';
$_SERVER['REQUEST_URI'] = '/';
require '/var/www/html/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/upgrade.php';
if (!is_blog_installed()) {
    $password = wp_generate_password(30, true, true);
    wp_install('Зерно веры — Семейный путь', 'local_editor', 'local@example.invalid', false, '', $password);
    file_put_contents('/var/www/html/.local-admin-password', $password);
    chmod('/var/www/html/.local-admin-password', 0600);
}
update_option('home', 'http://127.0.0.1:8080');
update_option('siteurl', 'http://127.0.0.1:8080');
update_option('blogdescription', 'Торжество веры и любви: семейный путь');
update_option('timezone_string', 'Europe/Moscow');
update_option('blog_public', '0');
switch_theme('zerno-very');
// Do not delete existing posts or overwrite editorial content.
foreach (array('foto-i-video'=>'Фото и видео','partnery'=>'Партнёры') as $slug=>$title) {
    if (!get_page_by_path($slug)) { wp_insert_post(array('post_type'=>'page','post_status'=>'draft','post_name'=>$slug,'post_title'=>$title)); }
}
echo "WordPress installed; theme active; search indexing disabled locally.\n";
