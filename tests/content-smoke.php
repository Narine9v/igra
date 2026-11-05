<?php
$_SERVER['HTTP_HOST']='127.0.0.1:8080';
require '/var/www/html/wp-load.php';
function check($condition, $label) { if (!$condition) { fwrite(STDERR, "FAIL: $label\n"); exit(1); } echo "PASS: $label\n"; }
check(get_stylesheet() === 'zerno-very', 'custom theme active');
$id=wp_insert_post(array('post_type'=>'post','post_status'=>'draft','post_title'=>'Проверка новости — черновик','post_content'=>'Тестовая запись для проверки. Будет удалена.'),true);
check(!is_wp_error($id) && $id>0,'draft created');
$q=new WP_Query(array('post_type'=>'post','post_status'=>'publish','post__in'=>array($id)));
check(!$q->have_posts(),'draft excluded from published news');
wp_update_post(array('ID'=>$id,'post_status'=>'publish','post_title'=>'Проверка публикации новости'));
check(get_post_status($id)==='publish','news published');
wp_update_post(array('ID'=>$id,'post_title'=>'Проверка редактирования новости'));
check(get_the_title($id)==='Проверка редактирования новости','news edited');
file_put_contents('/tmp/zv-smoke-id',(string)$id);
echo "TEST_POST_ID=$id\n";
