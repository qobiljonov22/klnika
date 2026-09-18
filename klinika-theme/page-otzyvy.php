<?php
/**
 * Template Name: Отзывы
 *
 * @package Klinika
 */
get_header();
$reviews = klinika_review_items();
?>
<main class="<?php echo esc_attr(klinika_tw('main')); ?>">
    <section class="<?php echo esc_attr(klinika_tw('section')); ?>">
        <div class="klinika-container max-w-[920px]">
            <nav class="<?php echo esc_attr(klinika_tw('crumbs-center')); ?>" aria-label="<?php echo esc_attr(klinika_t('breadcrumbs')); ?>">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-[#009BE3] no-underline text-[#9A9A9A]"><?php klinika_e('home'); ?></a>
                <span class="mx-1.5">/</span>
                <a href="<?php echo esc_url(klinika_info_hub_url()); ?>" class="hover:text-[#009BE3] no-underline text-[#9A9A9A]"><?php klinika_e('information'); ?></a>
                <span class="mx-1.5">/</span>
                <span><?php klinika_e('reviews'); ?></span>
            </nav>
            <h1 class="<?php echo esc_attr(klinika_tw('page-title')); ?>"><?php klinika_e('reviews'); ?></h1>
            <div class="space-y-5">
                <?php foreach ($reviews as $review) : ?>
                    <article class="border border-[#7EC8E3] p-5 sm:p-7">
                        <p class="<?php echo esc_attr(klinika_tw('muted', 'mb-4')); ?>"><?php echo esc_html($review['text']); ?></p>
                        <p class="m-0 font-[Montserrat] font-semibold"><?php echo esc_html($review['name']); ?></p>
                        <p class="m-0 text-[13px] text-[#009BE3] font-[Montserrat]"><?php echo esc_html($review['source']); ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</main>
<?php get_footer(); ?>
