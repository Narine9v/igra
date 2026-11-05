<!doctype html>
<html <?php language_attributes(); ?>>
<head><meta charset="<?php bloginfo('charset'); ?>"><meta name="viewport" content="width=device-width, initial-scale=1"><?php wp_head(); ?></head>
<body <?php body_class(); ?>><?php wp_body_open(); ?>
<a class="skip-link" href="#main">Перейти к содержанию</a>
<header class="site-header wrap">
<div class="brand"><?php if (has_custom_logo()) { the_custom_logo(); } ?><a class="brand-name" href="<?php echo esc_url(home_url('/')); ?>">Зерно веры<span>Семейный центр</span></a></div>
<button class="menu-toggle" type="button" aria-expanded="false" aria-controls="navigation" aria-label="Открыть меню"><span></span><span></span><span></span></button>
<nav id="navigation" aria-label="Основное меню"><?php foreach(array('about'=>'О проекте','events'=>'Мероприятия','team'=>'Команда','news'=>'Новости','gallery'=>'Фото и видео') as $id=>$label): ?><a href="<?php echo esc_url(home_url('/').'#'.$id); ?>"><?php echo esc_html($label); ?></a><?php endforeach; ?></nav>
</header>
