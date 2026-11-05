<?php get_header(); ?>
<main id="main" class="wrap article">
<?php if(have_posts()): while(have_posts()): the_post(); ?>
<article <?php post_class(); ?>>
<?php if(is_singular()): ?><p><a class="text-link" href="<?php echo esc_url(home_url('/').'#news'); ?>">← На главную</a></p><h1><?php the_title(); ?></h1><?php if(get_post_type()==='post'): ?><p class="post-date"><time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('d.m.Y')); ?></time></p><?php endif; ?><?php the_post_thumbnail('large'); ?><div class="article-content"><?php the_content(); ?></div><?php wp_link_pages(); ?>
<?php else: ?><h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2><?php the_excerpt(); ?><?php endif; ?>
</article>
<?php endwhile; the_posts_pagination(); else: ?><h1>Страница не найдена</h1><p>Вернитесь на <a href="<?php echo esc_url(home_url('/')); ?>">главную страницу</a>.</p><?php endif; ?>
</main><?php get_footer(); ?>
