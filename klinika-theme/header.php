<?php

/**
 * Header — Figma 1:1
 *
 * @package Klinika
 */
$services = klinika_service_items();
$nav_services = array_slice($services, 0, 8);
$wa = function_exists('klinika_whatsapp_url') ? klinika_whatsapp_url() : '';
$tg = function_exists('klinika_telegram_url') ? klinika_telegram_url() : '';
if ($wa === '') {
    $wa = 'https://wa.me/' . preg_replace('/\D+/', '', (string) klinika_phone_href());
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-[100] focus:bg-[#009BE3] focus:!text-white focus:px-4 focus:py-2 focus:font-[Montserrat] focus:text-sm focus:no-underline">Перейти к содержимому</a>

    <header class="fixed top-0 w-full z-50 bg-white/95 backdrop-blur-sm shadow-[0_1px_0_#E8F4FB] transition-shadow duration-300" data-site-header>
        <div class="lg:hidden flex items-center justify-between gap-3 px-4 py-1.5 text-[11px] sm:text-[12px] font-[Montserrat] text-[#5C5C5C] border-b border-[#E8F4FB]">
            <span><?php echo esc_html(klinika_address_short()); ?></span>
            <a href="tel:<?php echo esc_attr(klinika_phone_href()); ?>" class="no-underline text-[#009BE3] font-medium whitespace-nowrap"><?php echo esc_html(klinika_phone()); ?></a>
        </div>

        <div class="klinika-header-top">
            <div class="klinika-container flex items-center justify-between gap-4 py-3 lg:py-4 xl:py-5">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="flex items-center no-underline shrink-0">
                    <img src="<?php echo esc_url(klinika_img('logo.png')); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" class="h-10 sm:h-12 lg:h-14 xl:h-[68px] 2xl:h-[76px] w-auto">
                </a>

                <div class="hidden lg:flex items-center justify-end flex-1 min-w-0 gap-3 xl:gap-6 2xl:gap-8 3xl:gap-10 font-[Montserrat] text-[12px] xl:text-[14px] 2xl:text-[15px] 3xl:text-[16px] 4xl:text-[17px] text-[#5C5C5C]">
                    <span class="hidden xl:inline-flex items-center gap-2 whitespace-nowrap">
                        <svg class="w-4 h-4 text-[#009BE3] shrink-0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M12 2a7 7 0 00-7 7c0 5.25 7 13 7 13s7-7.75 7-13a7 7 0 00-7-7zm0 9.5A2.5 2.5 0 1112 6a2.5 2.5 0 010 5.5z" />
                        </svg>
                        <?php echo esc_html(klinika_address_short()); ?>
                    </span>
                    <span class="hidden xl:inline-flex items-center gap-2 whitespace-nowrap">
                        <svg class="w-4 h-4 text-[#009BE3] shrink-0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M12 2a10 10 0 100 20 10 10 0 000-20zm1 11H7V11h5V6h2v7z" />
                        </svg>
                        <?php echo esc_html(klinika_hours()); ?>
                    </span>
                    <a href="tel:<?php echo esc_attr(klinika_phone_href()); ?>" class="inline-flex items-center gap-2 no-underline text-[#5C5C5C] hover:text-[#009BE3] whitespace-nowrap">
                        <svg class="w-4 h-4 text-[#009BE3] shrink-0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1.1-.2 1.2.4 2.5.6 3.8.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1C10.6 21 3 13.4 3 4c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.6.6 3.8.1.4 0 .8-.3 1.1l-2.2 2.2z" />
                        </svg>
                        <?php echo esc_html(klinika_phone()); ?>
                    </a>
                    <a href="<?php echo esc_url(klinika_page_url('poisk')); ?>" class="inline-flex items-center justify-center w-10 h-10 rounded-full border border-[#E8F4FB] text-[#009BE3] no-underline hover:bg-[#ECF9FF]" aria-label="Поиск" data-cta="header_search">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <circle cx="11" cy="11" r="7" />
                            <path d="M20 20l-3.5-3.5" />
                        </svg>
                    </a>
                    <a <?php echo klinika_booking_attrs_html('klinika-booking-btn shrink-0 !text-[#fff]'); ?> data-cta="header_book"><?php klinika_e('online_book'); ?></a>
                </div>

                <button type="button" class="klinika-burger z-[999] lg:hidden flex flex-col justify-between h-[37px] w-10 py-[9px] px-[15px] items-center rounded-md border border-solid border-[#fff] bg-[#009BE3] rounded-full p-0 cursor-pointer" data-menu-btn aria-expanded="false" aria-label="<?php echo esc_attr(klinika_t('open_menu')); ?>">
                    <span class="bg-[#fff] block w-5 h-[2px]"></span>
                    <span class="bg-[#fff] block w-5 h-[2px]"></span>
                    <span class="bg-[#fff] block w-5 h-[2px]"></span>
                </button>
            </div>
        </div>

        <nav class="klinika-nav bg-[#ECF9FF] max-lg:absolute max-lg:top-0 max-lg:left-0 max-lg:z-[55] max-lg:w-full max-lg:h-[100vh] max-lg:overflow-y-auto max-lg:overflow-x-hidden max-lg:bg-white max-lg:hidden max-lg:[&.is-open]:block lg:block lg:static lg:h-auto lg:overflow-visible" data-nav>
            <div class="klinika-container max-lg:pt-20 max-lg:pb-8">
                <ul class="nav-list max-lg:!flex max-lg:flex-col max-lg:[&.is-open]:mt-1 max-lg:[&.is-open]:min-h-[73vh] max-lg:[&.is-open]:max-h-[73vh] max-lg:[&.is-open]:overflow-y-auto max-lg:[&.is-open]:overflow-x-hidden max-lg:[&.is-open]:overscroll-contain max-lg:[&.is-open]:py-2 max-lg:[&.is-open]:pb-4" data-nav-list>
                    <?php foreach (klinika_nav_items() as $item) :
                        $url = !empty($item['url']) ? $item['url'] : klinika_page_url($item['slug']);
                        $is_home = ($item['slug'] === 'home');
                        $current = $is_home ? is_front_page() : is_page($item['slug']);
                        $children = $item['children'] ?? '';
                    ?>
                        <li class="nav-item <?php echo $children ? 'has-children relative' : ''; ?> <?php echo $current ? 'is-current' : ''; ?>" <?php echo $children ? 'data-dropdown' : ''; ?>>
                            <?php if ($children) : ?>
                                <div class="nav-trigger flex items-center" data-dropdown-btn>
                                    <a href="<?php echo esc_url($url); ?>" class="nav-link"><?php echo esc_html($item['label']); ?></a>
                                    <button type="button" class="nav-chevron" aria-expanded="false">
                                        <svg class="w-2.5 h-2.5" data-dropdown-icon viewBox="0 0 12 8" fill="currentColor">
                                            <path d="M6 8L0.2 0.5h11.6L6 8z" />
                                        </svg>
                                    </button>
                                </div>
                                <ul class="nav-dropdown hidden" data-dropdown-menu>
                                    <?php if ($children === 'services') : ?>
                                        <?php foreach ($nav_services as $service) : ?>
                                            <li class="nav-subitem"><a class="nav-sublink" href="<?php echo esc_url($service['url']); ?>"><?php echo esc_html($service['title']); ?></a></li>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <?php foreach (klinika_info_nav_items() as $slug => $label) : ?>
                                            <li class="nav-subitem"><a class="nav-sublink" href="<?php echo esc_url(klinika_page_url($slug)); ?>"><?php echo esc_html($label); ?></a></li>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </ul>
                            <?php else : ?>
                                <a href="<?php echo esc_url($url); ?>" class="nav-link nav-trigger <?php echo $current ? 'is-active' : ''; ?>"><?php echo esc_html($item['label']); ?></a>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="lg:hidden absolute right-3 bottom-2 z-[70] flex flex-row w-3/5 justify-start items-center gap-2.5" data-sticky-contacts>
                <a href="tel:<?php echo esc_attr(klinika_phone_href()); ?>" data-cta="sticky_call" class="inline-flex items-center gap-2 min-h-[48px] rounded-full bg-[#04AA29] !text-[#fff] shadow-[0_8px_24px_rgba(4,170,41,0.35)] no-underline font-[Montserrat] text-[12px] font-semibold pl-3.5 pr-4" aria-label="Позвонить">
                    <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1.1-.2 1.2.4 2.5.6 3.8.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1C10.6 21 3 13.4 3 4c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.6.6 3.8.1.4 0 .8-.3 1.1l-2.2 2.2z" />
                    </svg>
                    Позвонить
                </a>
                <?php if ($wa) : ?>
                    <a href="<?php echo esc_url($wa); ?>" target="_blank" rel="noopener" data-cta="sticky_whatsapp" class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-[#25D366] !text-[#fff] shadow-[0_8px_24px_rgba(37,211,102,0.35)] no-underline" aria-label="WhatsApp">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M20.5 3.5A11 11 0 004.1 17.5L3 21l3.6-1A11 11 0 0020.5 3.5zm-8.5 17a9 9 0 01-4.6-1.3l-.3-.2-2.7.7.7-2.6-.2-.3A9 9 0 1112 20.5zm5-6.7c-.3-.1-1.6-.8-1.8-.9s-.4-.1-.6.1-.7.9-.8 1-.3.2-.6.1a7.4 7.4 0 01-2.2-1.4 8.2 8.2 0 01-1.5-1.9c-.2-.3 0-.4.1-.6l.4-.5c.1-.2.1-.3 0-.5l-.8-1.9c-.2-.5-.4-.4-.6-.4h-.5c-.2 0-.5.1-.7.3s-1 1-1 2.4 1 2.8 1.2 3 .2.3 2 3.1 2.6 2.4 3.1 2.6a7 7 0 002.1.6c.3 0 .9 0 1.2-.2s1.6-1.1 1.8-2.1.2-.8.1-.9-.2-.2-.5-.3z" />
                        </svg>
                    </a>
                <?php endif; ?>
                <?php if ($tg) : ?>
                    <a href="<?php echo esc_url($tg); ?>" target="_blank" rel="noopener" data-cta="sticky_telegram" class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-[#2AABEE] !text-[#fff] shadow-[0_8px_24px_rgba(42,171,238,0.35)] no-underline" aria-label="Telegram">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M9.04 15.3l-.37 5.2c.53 0 .76-.23 1.04-.5l2.5-2.4 5.18 3.8c.95.52 1.63.25 1.89-.88l3.42-16.06h.01c.3-1.41-.51-1.96-1.44-1.62L2.3 9.7C.93 10.23.95 11 2.12 11.34l4.6 1.44L17.4 6.2c.54-.36 1.03-.16.63.2" />
                        </svg>
                    </a>
                <?php endif; ?>
            </div>
        </nav>
    </header>