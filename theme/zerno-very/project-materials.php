<?php
if (!defined('ABSPATH')) { exit; }
function zv_materials_menu() {
    add_management_page('Материалы проекта', 'Материалы проекта', 'edit_posts', 'zv-materials', 'zv_materials_screen');
}
add_action('admin_menu', 'zv_materials_menu');
function zv_materials_screen() {
    if (!current_user_can('edit_posts')) { return; }
    ?>
    <div class="wrap"><h1>Материалы проекта</h1>
    <h2>🌟 Готовимся к Рождественским встречам!</h2>
    <p>Текст новости подготовлен. Кнопка создаст черновик или откроет ранее созданную запись. Существующий текст не перезаписывается.</p>
    <p><strong>Перед публикацией:</strong> загрузите фотографию онлайн-встречи в «Изображение записи», проверьте дату и текст. Автоматической публикации нет.</p>
    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
    <input type="hidden" name="action" value="zv_prepare_news">
    <?php wp_nonce_field('zv_prepare_news'); submit_button('Открыть черновик новости'); ?>
    </form></div>
    <?php
}
function zv_prepare_news() {
    if (!current_user_can('edit_posts')) { wp_die('Недостаточно прав.', '', array('response'=>403)); }
    check_admin_referer('zv_prepare_news');
    $existing = get_posts(array('post_type'=>'post', 'post_status'=>'any', 'meta_key'=>'_zv_material_key', 'meta_value'=>'christmas-meeting-2026', 'numberposts'=>1));
    if ($existing) {
        $post_id = $existing[0]->ID;
        if (!current_user_can('edit_post', $post_id)) { wp_die('Недостаточно прав.', '', array('response'=>403)); }
    } else {
        $content = file_get_contents(get_theme_file_path('content/christmas-meeting.html'));
        if ($content === false) { wp_die('Не удалось прочитать материал.'); }
        $post_id = wp_insert_post(wp_slash(array(
            'post_type'=>'post', 'post_status'=>'draft', 'post_title'=>'🌟 Готовимся к Рождественским встречам!',
            'post_content'=>$content, 'post_name'=>'gotovimsya-k-rozhdestvenskim-vstrecham',
            'meta_input'=>array('_zv_material_key'=>'christmas-meeting-2026')
        )), true);
        if (is_wp_error($post_id)) { wp_die(esc_html($post_id->get_error_message())); }
    }
    wp_safe_redirect(admin_url('post.php?post='.absint($post_id).'&action=edit'));
    exit;
}
add_action('admin_post_zv_prepare_news', 'zv_prepare_news');
