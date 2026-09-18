<?php
/**
 * Template Name: Контакты
 *
 * @package Klinika
 */
get_header();
$data = klinika_get_kontakty_page_data();
?>
<main class="<?php echo esc_attr(klinika_tw('main')); ?>">
    <section class="<?php echo esc_attr(klinika_tw('section')); ?>">
        <div class="klinika-container max-w-[920px]">
            <nav class="<?php echo esc_attr(klinika_tw('crumbs-center')); ?>" aria-label="<?php echo esc_attr(klinika_t('breadcrumbs')); ?>">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-[#009BE3] no-underline text-[#9A9A9A]"><?php klinika_e('home'); ?></a>
                <span class="mx-1.5">/</span>
                <span><?php klinika_e('contacts'); ?></span>
            </nav>
            <h1 class="<?php echo esc_attr(klinika_tw('page-title')); ?>"><?php echo esc_html($data['heading']); ?></h1>
            <div class="grid sm:grid-cols-2 gap-6 font-[Montserrat] text-[15px] text-[#5C5C5C] mb-8">
                <div>
                    <p class="m-0 mb-2 font-semibold text-[#1a1a1a]"><?php klinika_e('address_label'); ?></p>
                    <p class="m-0 mb-4"><?php echo esc_html($data['address']); ?></p>
                    <p class="m-0 mb-2 font-semibold text-[#1a1a1a]"><?php klinika_e('hours_label'); ?></p>
                    <p class="m-0"><?php echo esc_html($data['hours']); ?></p>
                </div>
                <div>
                    <p class="m-0 mb-2 font-semibold text-[#1a1a1a]"><?php klinika_e('phone_label'); ?></p>
                    <p class="m-0 mb-4"><a href="tel:<?php echo esc_attr($data['phone_href']); ?>" class="no-underline text-[#009BE3]"><?php echo esc_html($data['phone']); ?></a></p>
                    <p class="m-0 mb-2 font-semibold text-[#1a1a1a]"><?php klinika_e('email_label'); ?></p>
                    <p class="m-0"><a href="mailto:<?php echo esc_attr($data['email']); ?>" class="no-underline text-[#009BE3]"><?php echo esc_html($data['email']); ?></a></p>
                </div>
            </div>
            <div class="grid sm:grid-cols-2 gap-4 mb-8">
                <img src="<?php echo esc_url($data['photo_1_url']); ?>" alt="" class="w-full aspect-[4/3] object-cover">
                <img src="<?php echo esc_url($data['photo_2_url']); ?>" alt="" class="w-full aspect-[4/3] object-cover">
            </div>
            <h2 class="m-0 mb-4 font-[Montserrat] font-bold text-[22px]"><?php echo esc_html($data['directions_title']); ?></h2>
            <ul class="<?php echo esc_attr(klinika_tw('muted', 'mb-8 pl-5')); ?>">
                <?php foreach ($data['directions'] as $line) : ?>
                    <li class="mb-2"><?php echo esc_html($line); ?></li>
                <?php endforeach; ?>
            </ul>
            <div class="klinika-map w-full h-[280px] sm:h-[340px] overflow-hidden mb-8 border border-[#E8F4FB]">
                <iframe title="<?php echo esc_attr(klinika_t('map_label')); ?>" src="https://yandex.ru/map-widget/v1/?ll=92.9275%2C56.0506&z=16&l=map&pt=92.9275%2C56.0506%2Cpm2rdm" loading="lazy"></iframe>
            </div>
            <div class="text-center">
                <a <?php echo klinika_booking_attrs_html(klinika_tw('btn-green')); ?>><?php echo esc_html($data['btn_text']); ?></a>
            </div>
        </div>
    </section>
</main>
<?php get_footer(); ?>
