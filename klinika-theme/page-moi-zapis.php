<?php
/**
 * Template Name: Мои записи
 *
 * @package Klinika
 */
get_header();
$phone = isset($_POST['phone']) ? sanitize_text_field(wp_unslash($_POST['phone'])) : '';
$items = $phone !== '' ? klinika_bookings_by_phone($phone) : [];
$status_map = [
    'new'       => 'новая',
    'confirmed' => 'подтверждена',
    'cancelled' => 'отменена',
];
?>
<main class="<?php echo esc_attr(klinika_tw('main')); ?>">
    <section class="<?php echo esc_attr(klinika_tw('section')); ?>">
        <div class="klinika-container max-w-[720px]">
            <nav class="<?php echo esc_attr(klinika_tw('crumbs-center')); ?>" aria-label="<?php echo esc_attr(klinika_t('breadcrumbs')); ?>">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-[#009BE3] no-underline text-[#9A9A9A]"><?php klinika_e('home'); ?></a>
                <span class="mx-1.5">/</span>
                <span>Мои записи</span>
            </nav>
            <h1 class="<?php echo esc_attr(klinika_tw('page-title')); ?>">Мои записи</h1>
            <p class="m-0 mb-5 font-[Montserrat] text-[14px] text-[#5C5C5C]">Введите телефон, указанный при онлайн-записи.</p>
            <form method="post" class="flex flex-col sm:flex-row gap-3 mb-8" data-my-bookings-form>
                <input type="tel" name="phone" value="<?php echo esc_attr($phone); ?>" placeholder="+7 …" class="klinika-input flex-1" required data-my-bookings-phone>
                <button type="submit" class="klinika-booking-btn shrink-0" data-cta="my_bookings">Показать</button>
            </form>
            <div data-my-bookings-list class="space-y-3">
                <?php if ($phone !== '' && !$items) : ?>
                    <p class="font-[Montserrat] text-[15px] text-[#5C5C5C]">Записей не найдено.</p>
                <?php endif; ?>
                <?php foreach ($items as $item) : ?>
                    <article class="bg-[#F4FBFE] px-4 py-4 font-[Montserrat]">
                        <p class="m-0 mb-1 font-semibold text-[15px]"><?php echo esc_html($item['date'] . ' · ' . $item['time']); ?></p>
                        <p class="m-0 text-[14px] text-[#5C5C5C]"><?php echo esc_html($item['service'] ?: 'Услуга'); ?> — <?php echo esc_html($item['doctor'] ?: 'врач'); ?></p>
                        <p class="m-0 mt-2 text-[13px] text-[#009BE3]"><?php echo esc_html($status_map[$item['status']] ?? $item['status']); ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</main>
<?php get_footer(); ?>
