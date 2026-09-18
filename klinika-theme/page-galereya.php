<?php
/**
 * Template Name: Галерея
 *
 * @package Klinika
 */
get_header();
$gallery = klinika_gallery_items();
?>
<main class="<?php echo esc_attr(klinika_tw('main')); ?>">
    <section class="<?php echo esc_attr(klinika_tw('section')); ?>">
        <div class="klinika-container">
            <nav class="<?php echo esc_attr(klinika_tw('crumbs-center')); ?>" aria-label="<?php echo esc_attr(klinika_t('breadcrumbs')); ?>">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-[#009BE3] no-underline text-[#9A9A9A]"><?php klinika_e('home'); ?></a>
                <span class="mx-1.5">/</span>
                <span><?php klinika_e('gallery'); ?></span>
            </nav>
            <h1 class="<?php echo esc_attr(klinika_tw('page-title')); ?>"><?php klinika_e('gallery'); ?></h1>
            <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                <?php foreach ($gallery as $src) : ?>
                    <img src="<?php echo esc_url($src); ?>" alt="" class="w-full aspect-[4/3] object-cover">
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</main>
<?php get_footer(); ?>
