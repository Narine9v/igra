<?php
$_SERVER['HTTP_HOST']='127.0.0.1:8080';
require '/var/www/html/wp-load.php';
$file='/tmp/zv-smoke-id';
if(file_exists($file)) {
    $id=(int)file_get_contents($file);
    $post=get_post($id);
    if($post && $post->post_title==='Проверка редактирования новости' && $post->post_content==='Тестовая запись для проверки. Будет удалена.') { wp_delete_post($id,true); }
    unlink($file);
}
echo "Test content removed.\n";
