<?php
/**
 * Template Name: Лицензии
 *
 * @package Klinika
 */
get_header();
$licenses = klinika_license_items();
?>
<main class="<?php echo esc_attr(klinika_tw('main')); ?>">
    <section class="<?php echo esc_attr(klinika_tw('section')); ?>">
        <div class="klinika-container max-w-[920px] xl:max-w-[1040px] 2xl:max-w-[1160px]">
            <nav class="<?php echo esc_attr(klinika_tw('crumbs-center')); ?>" aria-label="<?php echo esc_attr(klinika_t('breadcrumbs')); ?>">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-[#009BE3] no-underline text-[#9A9A9A]"><?php klinika_e('home'); ?></a>
                <span class="mx-1.5">/</span>
                <a href="<?php echo esc_url(klinika_info_hub_url()); ?>" class="hover:text-[#009BE3] no-underline text-[#9A9A9A]"><?php klinika_e('information'); ?></a>
                <span class="mx-1.5">/</span>
                <span><?php klinika_e('licenses'); ?></span>
            </nav>
            <h1 class="<?php echo esc_attr(klinika_tw('page-title')); ?>"><?php klinika_e('licenses'); ?></h1>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5 lg:gap-6">
                <?php foreach ($licenses as $license) : ?>
                    <figure class="m-0">
                        <img src="<?php echo esc_url($license['image']); ?>" alt="<?php echo esc_attr($license['title']); ?>" class="w-full aspect-[3/4] object-contain border border-[#E8F4FB] bg-white p-2 sm:p-3">
                        <?php if (!empty($license['title'])) : ?>
                            <figcaption class="mt-2 text-center font-[Montserrat] text-[13px] sm:text-[14px] text-[#5C5C5C]"><?php echo esc_html($license['title']); ?></figcaption>
                        <?php endif; ?>
                    </figure>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</main>
<?php get_footer(); ?>
