<?php
if (!defined('ABSPATH')) { exit; }
function zv_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', array('height'=>180, 'width'=>180, 'flex-height'=>true, 'flex-width'=>true));
    add_theme_support('html5', array('search-form','gallery','caption','style','script'));
}
add_action('after_setup_theme', 'zv_setup');
function zv_assets() {
    wp_enqueue_style('zerno-very', get_stylesheet_uri(), array(), '1.2.1');
    wp_enqueue_script('zerno-very', get_theme_file_uri('assets/site.js'), array(), '1.2.1', true);
}
add_action('wp_enqueue_scripts', 'zv_assets');
function zv_customize($c) {
    $c->add_section('zv_project', array('title'=>'Проект «Семейный путь»', 'priority'=>30));
    foreach (array('grant_logo'=>'Логотип грантов', 'experience_photo'=>'Фото прошлых мероприятий') as $id=>$label) {
        $c->add_setting($id, array('sanitize_callback'=>'absint'));
        $c->add_control(new WP_Customize_Media_Control($c, $id, array('label'=>$label,'section'=>'zv_project','mime_type'=>'image')));
    }
    foreach (array('family_booking'=>'Ссылка записи: «Семейный путь»','christmas_booking'=>'Ссылка записи: «Торжество веры и любви»','past_video'=>'Видео прошлых лет','past_audio'=>'Аудиозаписи прошлых лет') as $id=>$label) {
        $c->add_setting($id, array('sanitize_callback'=>'esc_url_raw'));
        $c->add_control($id, array('label'=>$label,'section'=>'zv_project','type'=>'url'));
    }
    $c->add_setting('team_page', array('sanitize_callback'=>'absint'));
    $c->add_control('team_page', array('label'=>'Страница с командой и волонтёрами', 'description'=>'Выберите опубликованную страницу с фотографиями, именами и ролями участников.', 'section'=>'zv_project', 'type'=>'dropdown-pages'));
    $c->add_setting('christmas_time', array('default'=>'Время уточняется','sanitize_callback'=>'sanitize_text_field'));
    $c->add_control('christmas_time', array('label'=>'Время январского мероприятия','section'=>'zv_project','type'=>'text'));
}
add_action('customize_register', 'zv_customize');
function zv_placeholder($label) { echo '<div class="placeholder"><span aria-hidden="true">◇</span><p>'.esc_html($label).'</p></div>'; }
function zv_booking($key) {
    $url = get_theme_mod($key);
    if ($url) { echo '<a class="button" href="'.esc_url($url).'">Записаться</a>'; }
    else { echo '<button class="button booking-pending" type="button">Записаться</button>'; }
}
require_once get_theme_file_path('project-materials.php');
