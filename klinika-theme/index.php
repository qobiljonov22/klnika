<?php
/**
 * Blog fallback
 *
 * @package Klinika
 */
get_header();
?>
<main class="<?php echo esc_attr(klinika_tw('main')); ?>">
    <section class="<?php echo esc_attr(klinika_tw('section')); ?>">
        <div class="klinika-container">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <article class="mb-8">
                        <h2 class="font-[Montserrat] font-bold text-[24px] mb-3">
                            <a href="<?php the_permalink(); ?>" class="no-underline"><?php the_title(); ?></a>
                        </h2>
                        <div class="<?php echo esc_attr(klinika_tw('muted')); ?>"><?php the_excerpt(); ?></div>
                    </article>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </section>
</main>
<?php get_footer(); ?>
