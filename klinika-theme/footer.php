<?php
/**
 * Footer — Figma 1:1 + multi-step booking modal
 *
 * @package Klinika
 */
$ok = isset($_GET['callback']) && $_GET['callback'] === 'ok';
$booking_services = array_slice(klinika_service_items(), 0, 12);
$booking_doctors  = klinika_doctor_cards(12);
?>

<footer class="klinika-footer border-t border-[#EEEEEE] py-8 sm:py-10 lg:py-12 xl:py-14 2xl:py-16" style="background-color:#f7f7f7;background-image:url('<?php echo esc_url(klinika_img('footer.png')); ?>');background-repeat:repeat;background-position:left top;">
    <div class="klinika-container flex flex-col gap-8">
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-8">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="flex items-center no-underline shrink-0 mx-auto lg:mx-0">
                <img src="<?php echo esc_url(klinika_img('logo.png')); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" class="h-12 lg:h-14 xl:h-16 w-auto">
            </a>
            <nav class="grid grid-cols-2 sm:grid-cols-3 gap-x-8 gap-y-2 font-[Montserrat] text-[13px] text-[#5C5C5C] text-center sm:text-left mx-auto lg:mx-0" aria-label="Навигация в подвале">
                <a href="<?php echo esc_url(klinika_page_url('specialisty')); ?>" class="no-underline hover:text-[#009BE3]"><?php klinika_e('specialists'); ?></a>
                <a href="<?php echo esc_url(klinika_page_url('ceny')); ?>" class="no-underline hover:text-[#009BE3]"><?php klinika_e('prices'); ?></a>
                <a href="<?php echo esc_url(klinika_page_url('poisk')); ?>" class="no-underline hover:text-[#009BE3]">Поиск</a>
                <a href="<?php echo esc_url(klinika_page_url('moi-zapis')); ?>" class="no-underline hover:text-[#009BE3]">Мои записи</a>
                <a href="<?php echo esc_url(klinika_page_url('kontakty')); ?>" class="no-underline hover:text-[#009BE3]"><?php klinika_e('contacts'); ?></a>
                <a href="<?php echo esc_url(klinika_option('klinika_home_privacy_url', klinika_page_url('privacy-policy'))); ?>" class="no-underline hover:text-[#009BE3]"><?php klinika_e('privacy'); ?></a>
            </nav>
            <div class="text-center lg:text-right font-[Montserrat]">
                <p class="m-0 mb-1 text-[13px] text-[#9A9A9A]"><?php klinika_e('call_us'); ?></p>
                <a href="tel:<?php echo esc_attr(klinika_phone_href()); ?>" class="no-underline font-semibold text-[20px] sm:text-[22px] lg:text-[24px] text-[#1a1a1a]"><?php echo esc_html(klinika_phone()); ?></a>
                <p class="m-0 mt-2 text-[13px] text-[#9A9A9A]"><?php echo esc_html(klinika_hours()); ?></p>
            </div>
        </div>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-t border-[#E5E5E5] pt-5 font-[Montserrat] text-[12px] text-[#9A9A9A] text-center sm:text-left">
            <span><?php echo esc_html(klinika_option('klinika_home_copyright', '© Семейная клиника «Здоровые дети»')); ?></span>
            <span><?php echo esc_html(klinika_address_short()); ?></span>
        </div>
    </div>
</footer>

<div class="klinika-modal" data-booking-modal data-booking-root>
    <div class="relative w-full max-w-[440px] sm:max-w-[480px] bg-white p-5 sm:p-7 rounded-md max-h-[min(92vh,720px)] overflow-y-auto" data-booking-dialog>
        <button type="button" class="absolute top-3 right-3 bg-transparent border-0 text-2xl leading-none cursor-pointer z-10" data-booking-close aria-label="<?php echo esc_attr(klinika_t('close_form')); ?>">×</button>

        <div data-booking-step="form">
            <h2 class="m-0 mb-5 font-[Montserrat] font-bold text-[22px] text-center"><?php klinika_e('waiting'); ?></h2>
            <form class="space-y-3" data-booking-form>
                <input class="klinika-input" type="text" name="name" placeholder="<?php echo esc_attr(klinika_t('name_placeholder')); ?>" required data-booking-name>
                <input class="klinika-input" type="tel" name="phone" placeholder="<?php echo esc_attr(klinika_t('phone_number')); ?>" required data-booking-phone>

                <div class="relative" data-booking-dropdown="service">
                    <button type="button" class="klinika-input text-left flex items-center justify-between gap-2 booking-select-btn" data-booking-select-btn>
                        <span data-booking-select-label><?php echo esc_html(klinika_t('services') ?: 'Услуга'); ?></span>
                        <svg class="w-3 h-3 booking-select-chevron shrink-0" viewBox="0 0 12 8" fill="currentColor"><path d="M6 8L0.2 0.5h11.6L6 8z"/></svg>
                    </button>
                    <ul class="booking-select-menu hidden" data-booking-select-menu>
                        <?php foreach ($booking_services as $svc) : ?>
                            <li><button type="button" data-value="<?php echo esc_attr($svc['title']); ?>"><?php echo esc_html($svc['title']); ?></button></li>
                        <?php endforeach; ?>
                    </ul>
                    <input type="hidden" name="service" value="" data-booking-service>
                </div>

                <div class="relative" data-booking-dropdown="doctor">
                    <button type="button" class="klinika-input text-left flex items-center justify-between gap-2 booking-select-btn" data-booking-select-btn>
                        <span data-booking-select-label><?php echo esc_html(klinika_t('specialists') ?: 'Врач'); ?></span>
                        <svg class="w-3 h-3 booking-select-chevron shrink-0" viewBox="0 0 12 8" fill="currentColor"><path d="M6 8L0.2 0.5h11.6L6 8z"/></svg>
                    </button>
                    <ul class="booking-select-menu hidden" data-booking-select-menu>
                        <?php foreach ($booking_doctors as $doc) : ?>
                            <li><button type="button" data-value="<?php echo esc_attr($doc['name']); ?>"><?php echo esc_html($doc['name']); ?></button></li>
                        <?php endforeach; ?>
                    </ul>
                    <input type="hidden" name="doctor" value="" data-booking-doctor>
                </div>

                <button type="button" class="klinika-booking-btn !text-[#fff] w-full" data-booking-next="calendar"><?php klinika_e('book'); ?></button>
            </form>
        </div>

        <div class="hidden" data-booking-step="calendar">
            <h2 class="m-0 mb-4 font-[Montserrat] font-bold text-[20px] text-center">Выберите дату</h2>
            <div class="flex items-center justify-between mb-3">
                <button type="button" class="border-0 bg-transparent text-[#009BE3] text-xl cursor-pointer" data-cal-prev>‹</button>
                <p class="m-0 font-[Montserrat] font-semibold text-[15px]" data-cal-label></p>
                <button type="button" class="border-0 bg-transparent text-[#009BE3] text-xl cursor-pointer" data-cal-next>›</button>
            </div>
            <div class="grid grid-cols-7 gap-1 text-center font-[Montserrat] text-[12px] text-[#9A9A9A] mb-1">
                <span>Пн</span><span>Вт</span><span>Ср</span><span>Чт</span><span>Пт</span><span>Сб</span><span>Вс</span>
            </div>
            <div class="grid grid-cols-7 gap-1 mb-5" data-cal-grid></div>
            <button type="button" class="klinika-booking-btn !text-[#fff] w-full disabled:opacity-40" data-booking-next="time" disabled>Далее</button>
            <button type="button" class="mt-2 w-full border-0 bg-transparent text-[#009BE3] font-[Montserrat] text-[14px] cursor-pointer" data-booking-back="form">Назад</button>
        </div>

        <div class="hidden" data-booking-step="time">
            <h2 class="m-0 mb-4 font-[Montserrat] font-bold text-[20px] text-center">Выберите время</h2>
            <p class="m-0 mb-4 text-center font-[Montserrat] text-[14px] text-[#5C5C5C]" data-booking-date-view></p>
            <div class="grid grid-cols-3 sm:grid-cols-4 gap-2 mb-5" data-time-grid>
                <?php foreach (['09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '14:00', '14:30', '15:00', '15:30', '16:00'] as $slot) : ?>
                    <button type="button" class="booking-time-slot is-available" data-time="<?php echo esc_attr($slot); ?>"><?php echo esc_html($slot); ?></button>
                <?php endforeach; ?>
            </div>
            <button type="button" class="klinika-booking-btn !text-[#fff] w-full disabled:opacity-40" data-booking-submit disabled>Записаться</button>
            <button type="button" class="mt-2 w-full border-0 bg-transparent text-[#009BE3] font-[Montserrat] text-[14px] cursor-pointer" data-booking-back="calendar">Назад</button>
        </div>

        <div class="hidden text-center py-6" data-booking-step="success">
            <p class="m-0 mb-2 text-[40px]" aria-hidden="true">✓</p>
            <h2 class="m-0 mb-2 font-[Montserrat] font-bold text-[22px]">Заявка отправлена</h2>
            <p class="m-0 mb-6 font-[Montserrat] text-[14px] text-[#5C5C5C]">Мы свяжемся с вами для подтверждения (SMS/Telegram). Номер заявки можно проверить в «Мои записи».</p>
            <a href="<?php echo esc_url(klinika_page_url('moi-zapis')); ?>" class="inline-block mb-3 font-[Montserrat] text-[14px] text-[#009BE3] no-underline hover:underline">Мои записи</a>
            <button type="button" class="klinika-booking-btn !text-[#fff] block mx-auto" data-booking-close>Закрыть</button>
        </div>
    </div>
</div>

<?php if ($ok) : ?>
    <div class="fixed bottom-4 right-4 z-50 bg-[#04AA29] text-white font-[Montserrat] text-sm px-4 py-3 shadow-lg">Заявка отправлена</div>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
