<?php
/**
 * Template Name: Анализы
 *
 * @package Klinika
 */
get_header();
$items = klinika_price_items('analizy');
?>
<main class="<?php echo esc_attr(klinika_tw('main')); ?>">
    <section class="<?php echo esc_attr(klinika_tw('section')); ?>">
        <div class="klinika-container max-w-[920px]">
            <nav class="<?php echo esc_attr(klinika_tw('crumbs-center')); ?>" aria-label="<?php echo esc_attr(klinika_t('breadcrumbs')); ?>">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-[#009BE3] no-underline text-[#9A9A9A]"><?php klinika_e('home'); ?></a>
                <span class="mx-1.5">/</span>
                <span><?php klinika_e('analyses'); ?></span>
            </nav>
            <h1 class="<?php echo esc_attr(klinika_tw('page-title')); ?>"><?php klinika_e('analyses'); ?></h1>
            <div class="bg-[#F4FBFE] px-4 sm:px-8 py-2">
                <?php foreach ($items as $item) : ?>
                    <div class="klinika-table-row">
                        <span class="text-[14px] sm:text-[15px] text-[#1a1a1a]"><?php echo esc_html($item['title']); ?></span>
                        <span class="font-semibold text-[15px] text-[#1a1a1a] whitespace-nowrap"><?php echo esc_html($item['price']); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="mt-10 text-center">
                <a <?php echo klinika_booking_attrs_html(klinika_tw('btn-green')); ?>>
                    <?php klinika_e('book'); ?>
                </a>
            </div>
        </div>
    </section>
</main>
<?php get_footer(); ?>
