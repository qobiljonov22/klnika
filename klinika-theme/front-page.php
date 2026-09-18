<?php

/**
 * Homepage — Figma 1:1, Tailwind sm–4xl
 *
 * @package Klinika
 */
get_header();

$services   = array_slice(klinika_service_items(), 0, 7);
$doctors    = klinika_doctor_cards(4);
$reviews    = array_slice(klinika_review_items(), 0, 3);
$gallery    = klinika_gallery_items();
$advantages = klinika_advantage_defaults();
$about_img  = klinika_demo_photo('about');
$hero_img   = klinika_demo_photo('hero');
$partners   = klinika_partner_logos();
$news_items = klinika_news_items(3);
$hero_lead  = 'Внимательный осмотр, понятные рекомендации родителям и спокойная атмосфера для ребёнка. Приём в клинике и вызов педиатра на дом.';
$about_p1   = 'Семейная клиника «Здоровые дети» в Красноярске — это педиатрия, диагностика и анализы в одном месте. Мы сопровождаем семью от первого визита до планового наблюдения.';
$about_p2   = 'Врачи объясняют назначения простым языком, а администраторы помогают выбрать удобное время. Работаем ежедневно с 8:00 до 20:00.';
$about_quote = 'Главное — чтобы ребёнку было спокойно, а родителям — понятно, что делать дальше.';
$video_url  = klinika_option('klinika_video_url', 'https://www.youtube.com/');
$home_rows  = array_slice($services, 0, 4);
if (count($home_rows) < 4) {
    $home_rows = [
        ['title' => 'Педиатр', 'url' => klinika_uslugi_url(), 'slug' => 'pediatr'],
        ['title' => 'Терапевт', 'url' => klinika_uslugi_url(), 'slug' => 'terapevt'],
        ['title' => 'ЛОР', 'url' => klinika_uslugi_url(), 'slug' => 'lor'],
        ['title' => 'Невролог', 'url' => klinika_uslugi_url(), 'slug' => 'nevrolog'],
    ];
}
?>
<main class="bg-white" id="main-content">

    <section class="relative overflow-visible bg-white pt-4 sm:pt-6 lg:pt-8 xl:pt-10 2xl:pt-12 3xl:pt-14 4xl:pt-16 pb-8 sm:pb-10 lg:pb-14">
        <div class="klinika-container">
            <img src="<?php echo esc_url(klinika_img('sheep.png')); ?>" alt="" class="klinika-deco pointer-events-none absolute left-[-12px] sm:left-0 top-[42%] lg:top-[38%] z-[2] hidden md:block h-auto min-w-[56px] !max-w-[72px] md:min-w-[72px] md:!max-w-[96px] lg:min-w-[88px] lg:!max-w-[112px] xl:min-w-[104px] xl:!max-w-[128px] 2xl:min-w-[120px] 2xl:!max-w-[148px] 3xl:min-w-[136px] 3xl:!max-w-[168px]" aria-hidden="true">

            <div class="relative flex flex-col lg:flex-row gap-6 sm:gap-8 lg:gap-10 xl:gap-14 2xl:gap-16 items-center max-w-[920px] xl:max-w-[1040px] 2xl:max-w-[1160px] 3xl:max-w-[1280px] mx-auto" data-hero-slider>
                <img src="<?php echo esc_url(klinika_img('monkey.png')); ?>" alt="" class="klinika-deco pointer-events-none absolute left-[96%] top-[170px] z-[2] hidden md:block h-auto min-w-[188px] !max-w-none" aria-hidden="true">

                <div class="relative mx-auto w-[90%] max-w-[328px] overflow-visible md:max-w-[420px] lg:max-w-none">
                    <img src="<?php echo esc_url(klinika_img('line-stroke.png') . '?v=' . KLINIKA_VERSION); ?>" alt="" class="pointer-events-none absolute left-1/2 top-1/2 z-[1] hidden max-lg:!block !h-[92px] !w-[calc(100%+72px)] !max-w-none -translate-x-1/2 -translate-y-1/2 rotate-[12deg] object-fill" aria-hidden="true">
                    <img src="<?php echo esc_url($hero_img); ?>" alt="" class="relative z-10 box-border block !h-[209px] !w-full border-2 border-[#009BE3] object-cover object-[center_28%] lg:border-0 lg:!h-auto lg:aspect-[4/5] lg:object-top">
                </div>

                <div class="text-center lg:text-left px-1 sm:px-2 lg:px-0 mx-auto lg:mx-0 w-full max-w-[520px] lg:max-w-none">
                    <h1 class="m-0 mb-3 sm:mb-4 lg:mb-5 font-[Montserrat] font-bold text-[#1a1a1a] leading-[1.1] text-[28px] sm:text-[32px] md:text-[36px] lg:text-[40px] xl:text-[48px] 2xl:text-[56px] 3xl:text-[64px] 4xl:text-[72px]">
                        Приём<br class="hidden lg:block"> педиатра
                    </h1>
                    <p class="m-0 mb-4 sm:mb-5 lg:mb-6 font-[Montserrat] text-[#5C5C5C] leading-[1.6] text-[13px] sm:text-[14px] lg:text-[15px] xl:text-[16px] 2xl:text-[17px] max-w-[36ch] sm:max-w-[42ch] mx-auto lg:mx-0">
                        <?php echo esc_html($hero_lead); ?>
                    </p>
                    <div class="flex items-center justify-center lg:justify-start gap-2 mb-5 sm:mb-6 text-left">
                        <img src="<?php echo esc_url(klinika_img('trophy.png')); ?>" alt="" class="w-8 h-8 xl:w-9 xl:h-9 object-contain shrink-0">
                        <p class="m-0 font-[Montserrat] text-[12px] sm:text-[13px] xl:text-[14px] text-[#5C5C5C] leading-snug max-w-[22ch]"><?php klinika_e('best_clinic'); ?></p>
                    </div>

                    <div class="flex items-center gap-3 md:hidden">
                        <button type="button" class="klinika-hero-arrow shrink-0" data-hero-prev aria-label="Prev">
                            <svg class="w-4 h-4" viewBox="0 0 26 23" fill="currentColor" aria-hidden="true">
                                <path d="M0.44 12.11a1.5 1.5 0 010-2.12L9.99.44a1.5 1.5 0 012.12 2.12L3.62 11.05l8.49 8.48a1.5 1.5 0 01-2.12 2.12L.44 12.11zM25.06 12.55H1.5v-3h23.56v3z" />
                            </svg>
                        </button>
                        <a href="<?php echo esc_url(klinika_uslugi_url()); ?>" class="klinika-booking-btn flex-1"><?php klinika_e('learn_more'); ?></a>
                        <button type="button" class="klinika-hero-arrow shrink-0" data-hero-next aria-label="Next">
                            <svg class="w-4 h-4 rotate-180" viewBox="0 0 26 23" fill="currentColor" aria-hidden="true">
                                <path d="M0.44 12.11a1.5 1.5 0 010-2.12L9.99.44a1.5 1.5 0 012.12 2.12L3.62 11.05l8.49 8.48a1.5 1.5 0 01-2.12 2.12L.44 12.11zM25.06 12.55H1.5v-3h23.56v3z" />
                            </svg>
                        </button>
                    </div>

                    <div class="hidden md:block relative">
                        <div class="flex items-center justify-between gap-4">
                            <a href="<?php echo esc_url(klinika_uslugi_url()); ?>" class="klinika-booking-btn shrink-0"><?php klinika_e('learn_more'); ?></a>
                            <img src="<?php echo esc_url(klinika_img('palm.png')); ?>" alt="" class="klinika-deco pointer-events-none absolute left-1/2 -translate-x-1/2 bottom-0 w-12 lg:w-14 xl:w-16">
                            <div class="flex items-center gap-3 shrink-0">
                                <button type="button" class="klinika-hero-arrow" data-hero-prev aria-label="Prev">
                                    <svg class="w-4 h-4" viewBox="0 0 26 23" fill="currentColor" aria-hidden="true">
                                        <path d="M0.44 12.11a1.5 1.5 0 010-2.12L9.99.44a1.5 1.5 0 012.12 2.12L3.62 11.05l8.49 8.48a1.5 1.5 0 01-2.12 2.12L.44 12.11zM25.06 12.55H1.5v-3h23.56v3z" />
                                    </svg>
                                </button>
                                <button type="button" class="klinika-hero-arrow" data-hero-next aria-label="Next">
                                    <svg class="w-4 h-4 rotate-180" viewBox="0 0 26 23" fill="currentColor" aria-hidden="true">
                                        <path d="M0.44 12.11a1.5 1.5 0 010-2.12L9.99.44a1.5 1.5 0 012.12 2.12L3.62 11.05l8.49 8.48a1.5 1.5 0 01-2.12 2.12L.44 12.11zM25.06 12.55H1.5v-3h23.56v3z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="mt-3 h-2 bg-[#E7F5FC] border-t-[3px] border-[#009BE3]"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white pb-[var(--klinika-section-pb)]" data-reveal>
        <div class="klinika-container">
            <h2 class="m-0 mb-8 sm:mb-10 lg:mb-12 xl:mb-14 text-center font-[Montserrat] font-bold text-[#1a1a1a] text-[22px] sm:text-[26px] md:text-[28px] lg:text-[32px] xl:text-[36px] 2xl:text-[40px] 3xl:text-[44px]"><?php klinika_e('why_us'); ?></h2>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-x-4 gap-y-10 sm:gap-x-6 lg:gap-8 xl:gap-10 2xl:gap-12 max-w-[1100px] 3xl:max-w-[1280px] 4xl:max-w-[1400px] mx-auto">
                <?php foreach ($advantages as $i => $item) : ?>
                    <div class="flex flex-col items-center text-center">
                        <div class="relative mb-4 sm:mb-5">
                            <img src="<?php echo esc_url(klinika_asset($item['image'])); ?>" alt="" class="w-[92px] h-[92px] sm:w-[108px] sm:h-[108px] lg:w-[120px] lg:h-[120px] xl:w-[128px] xl:h-[128px] 2xl:w-[140px] 2xl:h-[140px] 3xl:w-[152px] 3xl:h-[152px]" loading="lazy">
                            <span class="absolute left-1/2 -translate-x-1/2 -bottom-3 w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-[#009BE3] text-white font-[Montserrat] font-bold text-[13px] sm:text-[14px] flex items-center justify-center"><?php echo (int) ($i + 1); ?></span>
                        </div>
                        <h3 class="m-0 mb-2 mt-2 font-[Montserrat] font-semibold text-[15px] sm:text-[16px] lg:text-[17px] xl:text-[18px]"><?php echo esc_html($item['title']); ?></h3>
                        <p class="m-0 font-[Montserrat] text-[12px] sm:text-[13px] lg:text-[14px] text-[#5C5C5C] leading-[1.45] max-w-[22ch]"><?php echo esc_html($item['text']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="bg-white pb-[var(--klinika-section-pb)]" data-reveal>
        <div class="klinika-container">
            <h2 class="m-0 mb-6 sm:mb-8 lg:mb-10 text-center font-[Montserrat] font-bold text-[22px] sm:text-[26px] lg:text-[32px] xl:text-[36px]"><?php klinika_e('video_about'); ?></h2>
            <div class="relative max-w-[920px] xl:max-w-[1000px] 2xl:max-w-[1100px] 3xl:max-w-[1200px] mx-auto overflow-hidden bg-black group">
                <img src="<?php echo esc_url(klinika_demo_photo('video')); ?>" alt="" class="w-full aspect-video object-cover opacity-90 transition-opacity duration-300 group-hover:opacity-80" loading="lazy">
                <a href="<?php echo esc_url($video_url); ?>" target="_blank" rel="noopener" class="absolute inset-0 flex items-center justify-center" aria-label="Смотреть видео о клинике" data-cta="video_play">
                    <span class="w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-white/95 text-[#FF0000] flex items-center justify-center shadow-lg transition-transform duration-300 group-hover:scale-105">
                        <svg class="w-7 h-7 ml-1" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M8 5v14l11-7z" />
                        </svg>
                    </span>
                </a>
            </div>
        </div>
    </section>

    <section class="bg-white pb-[var(--klinika-section-pb)]" data-reveal>
        <div class="klinika-container max-w-[720px] xl:max-w-[800px] 2xl:max-w-[880px] 3xl:max-w-[960px]">
            <h2 class="m-0 mb-6 sm:mb-8 lg:mb-10 text-center font-[Montserrat] font-bold text-[22px] sm:text-[26px] lg:text-[32px] xl:text-[36px]"><?php klinika_e('our_services'); ?></h2>
            <div class="flex flex-col gap-3 sm:gap-4">
                <?php foreach ($home_rows as $i => $row) :
                    $filled = ($row['slug'] ?? '') === 'terapevt' || $i === 1;
                ?>
                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 border-b border-[#04AA29] pb-3 sm:pb-4 sm:border-b-0 sm:grid sm:grid-cols-[1fr_auto_auto] sm:items-center sm:gap-3">
                        <p class="m-0 font-[Montserrat] font-medium text-[15px] sm:text-[16px] lg:text-[17px] text-center sm:text-left sm:border-b sm:border-[#04AA29] sm:pb-3"><?php echo esc_html($row['title']); ?></p>
                        <a <?php echo klinika_booking_attrs_html('inline-flex items-center justify-center min-w-[180px] sm:min-w-[200px] px-4 py-2.5 rounded-full border border-[#04AA29] font-[Montserrat] text-[13px] sm:text-[14px] no-underline ' . ($filled ? 'bg-[#04AA29] text-white' : 'bg-white text-[#04AA29] hover:bg-[#04AA29] hover:text-white')); ?>><?php klinika_e('call_home'); ?></a>
                        <a <?php echo klinika_booking_attrs_html('inline-flex items-center justify-center min-w-[180px] sm:min-w-[200px] px-4 py-2.5 rounded-full border border-[#04AA29] bg-white text-[#04AA29] font-[Montserrat] text-[13px] sm:text-[14px] no-underline hover:bg-[#04AA29] hover:text-white'); ?>><?php klinika_e('book'); ?></a>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="mt-6 sm:mt-8 text-center">
                <a href="<?php echo esc_url(klinika_uslugi_url()); ?>" class="inline-flex items-center gap-1 font-[Montserrat] font-medium text-[15px] no-underline text-[#1a1a1a] hover:text-[#009BE3]"><?php klinika_e('see_all'); ?> <span aria-hidden="true">›</span></a>
            </div>
        </div>
    </section>

    <section class="bg-white pb-[var(--klinika-section-pb)]" data-reveal>
        <div class="klinika-container">
            <h2 class="m-0 mb-5 sm:mb-6 md:mb-8 text-center font-[Montserrat] font-bold text-[#1a1a1a] text-[24px] sm:text-[28px] md:text-[32px] lg:hidden"><?php klinika_e('about_clinic'); ?></h2>

            <div class="grid lg:grid-cols-2 gap-6 sm:gap-8 lg:gap-10 xl:gap-14 2xl:gap-16 3xl:gap-20 items-start">
                <div class="relative mx-auto min-w-0 max-w-full sm:max-w-[340px] md:max-w-[380px] lg:mx-0 lg:max-w-[420px] xl:max-w-[460px] 2xl:max-w-[500px] 3xl:max-w-[540px] 4xl:max-w-[580px] md:pt-8 lg:pt-10">
                    <img src="<?php echo esc_url(klinika_img('zebra.png')); ?>" alt="" class="klinika-deco pointer-events-none absolute left-0 top-0 translate-y-[-87px] z-[1] hidden md:block h-auto min-w-[56px] !max-w-[72px] lg:min-w-[72px] lg:!max-w-[96px] xl:min-w-[84px] xl:!max-w-[112px] 2xl:min-w-[96px] 2xl:!max-w-[128px]" aria-hidden="true">
                    <img src="<?php echo esc_url($about_img); ?>" alt="" class="relative z-0 block h-auto min-w-full max-w-full aspect-[3/4] object-cover object-top">
                    <div class="relative z-[1] bg-[#009BE3] text-white px-4 py-3 sm:px-5 sm:py-4 md:px-6 md:py-5">
                        <p class="m-0 font-[Montserrat] font-medium text-[12px] sm:text-[13px] md:text-[14px] leading-snug"><?php klinika_e('director'); ?></p>
                        <p class="m-0 mt-0.5 font-[Montserrat] font-semibold text-[16px] sm:text-[18px] md:text-[20px] lg:text-[22px] leading-snug">Марина Александровна</p>
                        <a href="<?php echo esc_url(klinika_page_url('specialisty')); ?>" class="mt-2 inline-block font-[Montserrat] text-[12px] sm:text-[13px] md:text-[14px] text-white underline underline-offset-2"><?php klinika_e('to_doctor_card'); ?></a>
                    </div>
                </div>

                <div class="min-w-0 max-w-[640px] lg:max-w-none mx-auto lg:mx-0">
                    <h2 class="m-0 mb-4 sm:mb-5 lg:mb-6 xl:mb-8 hidden lg:block font-[Montserrat] font-bold text-[#1a1a1a] text-[28px] xl:text-[32px] 2xl:text-[36px] 3xl:text-[40px] 4xl:text-[44px] leading-tight"><?php klinika_e('about_clinic'); ?></h2>

                    <div class="font-[Montserrat] text-[#5C5C5C] text-[13px] sm:text-[14px] md:text-[15px] xl:text-[16px] 2xl:text-[17px] leading-[1.7] space-y-3 sm:space-y-4">
                        <p class="m-0"><?php echo esc_html($about_p1); ?></p>
                        <p class="m-0"><?php echo esc_html($about_p2); ?></p>
                        <p class="m-0 hidden lg:block">В клинике принимают педиатр, невролог, ЛОР, хирург и другие детские специалисты. Есть собственная лаборатория и программы наблюдения.</p>
                        <p class="m-0 hidden xl:block">Запись онлайн занимает пару минут: выберите врача или услугу, удобную дату и время — администратор подтвердит заявку.</p>
                    </div>

                    <div class="mt-6 sm:mt-8 lg:mt-10 flex flex-col gap-5 sm:gap-6 md:flex-row md:items-end md:justify-between">
                        <blockquote class="m-0 min-w-0 max-w-[280px] sm:max-w-[320px] pl-4 border-l-2 border-[#1a1a1a] font-[Montserrat] font-medium text-[#1a1a1a] text-[14px] sm:text-[15px] md:text-[16px] leading-snug">
                            <?php echo esc_html($about_quote); ?>
                        </blockquote>
                        <a href="<?php echo esc_url(klinika_page_url('o-klinike')); ?>" class="klinika-booking-btn shrink-0 self-start md:self-auto"><?php klinika_e('read_more_btn'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white pb-[var(--klinika-section-pb)]">
        <div class="klinika-container">
            <div class="relative" data-gallery-slider>
                <div class="relative mb-5 sm:mb-6 lg:mb-8">
                    <img src="<?php echo esc_url(klinika_img('zebra.png')); ?>" alt="" class="klinika-deco pointer-events-none absolute -left-1 -top-3 z-[1] h-auto min-w-[48px] !max-w-[56px] sm:hidden" aria-hidden="true">
                    <h2 class="m-0 text-center font-[Montserrat] font-bold text-[#1a1a1a] text-[24px] sm:text-[26px] lg:text-[32px] xl:text-[36px]"><?php klinika_e('our_gallery'); ?></h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 lg:gap-5">
                    <?php foreach (array_slice($gallery, 0, 3) as $i => $src) : ?>
                        <img src="<?php echo esc_url($src); ?>" alt="" class="<?php echo $i === 0 ? '' : 'hidden sm:block'; ?> min-w-0 max-w-full w-full aspect-[3/2] sm:aspect-[4/3] object-cover" data-gallery-item>
                    <?php endforeach; ?>
                </div>

                <div class="mt-5 sm:mt-6 flex items-center justify-between min-w-0 max-w-full sm:max-w-[360px] mx-auto px-1">
                    <button type="button" class="inline-flex items-center justify-center min-w-[40px] max-w-[40px] h-10 rounded-full bg-[#009BE3] text-white border-0 cursor-pointer shrink-0" data-gallery-prev aria-label="Prev">
                        <svg class="min-w-[14px] max-w-[14px] h-3.5" viewBox="0 0 26 23" fill="currentColor" aria-hidden="true">
                            <path d="M0.44 12.11a1.5 1.5 0 010-2.12L9.99.44a1.5 1.5 0 012.12 2.12L3.62 11.05l8.49 8.48a1.5 1.5 0 01-2.12 2.12L.44 12.11zM25.06 12.55H1.5v-3h23.56v3z" />
                        </svg>
                    </button>
                    <a href="<?php echo esc_url(klinika_page_url('galereya')); ?>" class="font-[Montserrat] font-medium text-[14px] sm:text-[15px] text-[#1a1a1a] underline underline-offset-2"><?php klinika_e('go'); ?> ›</a>
                    <button type="button" class="inline-flex items-center justify-center min-w-[40px] max-w-[40px] h-10 rounded-full bg-[#009BE3] text-white border-0 cursor-pointer shrink-0" data-gallery-next aria-label="Next">
                        <svg class="min-w-[14px] max-w-[14px] h-3.5 rotate-180" viewBox="0 0 26 23" fill="currentColor" aria-hidden="true">
                            <path d="M0.44 12.11a1.5 1.5 0 010-2.12L9.99.44a1.5 1.5 0 012.12 2.12L3.62 11.05l8.49 8.48a1.5 1.5 0 01-2.12 2.12L.44 12.11zM25.06 12.55H1.5v-3h23.56v3z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white pb-[var(--klinika-section-pb)]" data-reveal>
        <div class="klinika-container">
            <div class="flex items-center justify-between gap-4 mb-6 sm:mb-8 lg:mb-10">
                <h2 class="m-0 font-[Montserrat] font-bold text-[22px] sm:text-[26px] lg:text-[32px] xl:text-[36px]"><?php klinika_e('our_doctors'); ?></h2>
                <a href="<?php echo esc_url(klinika_page_url('specialisty')); ?>" class="hidden sm:inline-flex items-center px-4 py-2 rounded-full border border-[#009BE3] text-[#009BE3] font-[Montserrat] text-[13px] lg:text-[14px] no-underline hover:bg-[#ECF9FF]"><?php klinika_e('see_all_doctors'); ?></a>
            </div>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-5 xl:gap-6">
                <?php foreach ($doctors as $i => $doctor) : ?>
                    <article>
                        <a href="<?php echo esc_url($doctor['url']); ?>" class="block no-underline text-inherit">
                            <div class="relative">
                                <img src="<?php echo esc_url($doctor['image']); ?>" alt="<?php echo esc_attr($doctor['name']); ?>" class="w-full aspect-[4/5] object-cover">
                                <span class="absolute bottom-2 left-2 bg-white/90 text-[#009BE3] font-[Montserrat] text-[11px] sm:text-[12px] px-2 py-1"><?php echo esc_html(klinika_t('exp_label') . ' ' . (int) $doctor['experience'] . ' лет'); ?></span>
                            </div>
                            <div class="bg-[#009BE3] text-white px-3 py-2 sm:px-4 sm:py-3 min-h-[72px]">
                                <p class="m-0 font-[Montserrat] font-semibold text-[13px] sm:text-[14px] lg:text-[15px]"><?php echo esc_html($doctor['name']); ?></p>
                                <p class="m-0 font-[Montserrat] text-[12px] sm:text-[13px] opacity-90"><?php echo esc_html($doctor['position']); ?></p>
                            </div>
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>
            <div class="sm:hidden mt-5 text-center">
                <a href="<?php echo esc_url(klinika_page_url('specialisty')); ?>" class="inline-flex items-center px-5 py-2 rounded-full border border-[#009BE3] text-[#009BE3] font-[Montserrat] text-[14px] no-underline"><?php klinika_e('see_all_doctors'); ?></a>
            </div>
        </div>
    </section>

    <section class="bg-white pb-[var(--klinika-section-pb)]">
        <div class="klinika-container max-w-[980px] xl:max-w-[1040px] 2xl:max-w-[1120px] 3xl:max-w-[1200px]">
            <h2 class="m-0 mb-6 sm:mb-8 lg:mb-10 text-center font-[Montserrat] font-bold text-[22px] sm:text-[26px] lg:text-[32px] xl:text-[36px]"><?php klinika_e('our_reviews'); ?></h2>
            <div class="flex flex-col gap-4 sm:gap-5">
                <?php foreach ($reviews as $review) : ?>
                    <article class="flex flex-col sm:flex-row sm:items-stretch border-b border-[#E5E5E5] pb-4 sm:border-0 sm:pb-0">
                        <div class="flex-1 sm:pr-6 sm:py-3">
                            <p class="m-0 mb-1 font-[Montserrat]">
                                <span class="font-semibold text-[14px] sm:text-[15px]"><?php echo esc_html($review['name']); ?></span>
                                <span class="ml-2 text-[#04AA29] text-[13px]"><?php echo esc_html($review['source']); ?></span>
                            </p>
                            <p class="m-0 font-[Montserrat] text-[13px] sm:text-[14px] text-[#5C5C5C] leading-[1.55]"><?php echo esc_html(wp_trim_words($review['text'], 28, '...')); ?></p>
                        </div>
                        <a href="<?php echo esc_url(klinika_page_url('otzyvy')); ?>" class="mt-3 sm:mt-0 sm:w-[160px] lg:w-[180px] inline-flex items-center justify-center bg-[#009BE3] !text-white font-[Montserrat] text-[14px] no-underline px-5 py-3 sm:py-0"><?php klinika_e('go'); ?> ›</a>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section id="callback" class="klinika-callback relative overflow-hidden py-10 sm:py-12 lg:py-16 xl:py-20 2xl:py-24">
        <img src="<?php echo esc_url(klinika_img('zebra.png')); ?>" alt="" class="klinika-deco pointer-events-none absolute left-[4%] top-1/2 -translate-y-1/2 w-16 sm:w-20 lg:w-28 xl:w-32 hidden sm:block">
        <img src="<?php echo esc_url(klinika_img('giraffe.png')); ?>" alt="" class="klinika-deco pointer-events-none absolute right-[6%] top-1/2 -translate-y-1/2 w-14 sm:w-16 lg:w-24 xl:w-28 hidden sm:block">
        <img src="<?php echo esc_url(klinika_img('monkey.png')); ?>" alt="" class="klinika-deco pointer-events-none absolute right-[18%] bottom-2 w-12 lg:w-16 hidden xl:block">
        <div class="klinika-container relative z-10">
            <div class="mx-auto w-full max-w-[420px] sm:max-w-[460px] lg:max-w-[520px] bg-white px-5 py-7 sm:px-8 sm:py-9 lg:px-10 lg:py-12 shadow-[0_12px_40px_rgba(0,155,227,0.12)]">
                <h2 class="m-0 mb-5 sm:mb-6 text-center font-[Montserrat] font-bold text-[22px] sm:text-[26px] lg:text-[28px]"><?php klinika_e('waiting'); ?></h2>
                <form method="post" class="space-y-3 sm:space-y-4">
                    <?php wp_nonce_field('klinika_callback', 'klinika_callback_nonce'); ?>
                    <input type="hidden" name="klinika_callback" value="1">
                    <label class="block font-[Montserrat] text-[13px] text-[#5C5C5C]"><?php klinika_e('your_data'); ?></label>
                    <input class="klinika-input" type="text" name="callback_name" placeholder="<?php echo esc_attr(klinika_t('name_placeholder')); ?>" required>
                    <input class="klinika-input" type="tel" name="callback_phone" placeholder="<?php echo esc_attr(klinika_t('phone_number')); ?>" required>
                    <button type="submit" class="klinika-booking-btn w-full"><?php klinika_e('book'); ?></button>
                </form>
            </div>
        </div>
    </section>

    <section class="bg-white py-10 sm:py-12 lg:py-16">
        <div class="klinika-container max-w-[860px] xl:max-w-[920px] 2xl:max-w-[1000px]">
            <h2 class="m-0 mb-6 sm:mb-8 text-center font-[Montserrat] font-bold text-[22px] sm:text-[26px] lg:text-[32px]"><?php klinika_e('latest_news'); ?></h2>
            <div class="flex flex-col gap-4 sm:gap-5">
                <?php foreach ($news_items as $news) : ?>
                    <article class="flex gap-3 sm:gap-4 border border-[#E5E5E5] p-3 sm:p-4">
                        <span class="w-[4px] shrink-0 bg-[#009BE3]"></span>
                        <div class="min-w-0 flex-1">
                            <p class="m-0 mb-1 text-[12px] text-[#9A9A9A] font-[Montserrat]"><?php echo esc_html($news['date']); ?></p>
                            <p class="m-0 font-[Montserrat] text-[13px] sm:text-[14px] text-[#5C5C5C] leading-[1.5]"><?php echo esc_html($news['text']); ?></p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
            <div class="mt-6 text-center">
                <a href="<?php echo esc_url(klinika_page_url('novosti')); ?>" class="font-[Montserrat] text-[15px] no-underline text-[#1a1a1a]"><?php klinika_e('go'); ?> ›</a>
            </div>
        </div>
    </section>

    <section class="bg-white pb-[var(--klinika-section-pb)]">
        <div class="klinika-container">
            <h2 class="m-0 mb-6 sm:mb-8 text-center font-[Montserrat] font-bold text-[22px] sm:text-[26px] lg:text-[32px]"><?php klinika_e('our_partners'); ?></h2>
            <div class="flex items-center justify-center gap-3 sm:gap-6 lg:gap-10">
                <button type="button" class="hidden sm:inline-block w-9 h-9 rounded-full bg-[#009BE3] text-white border-0 cursor-pointer justify-items-center" data-partners-prev>
                    <svg class="min-w-[14px] max-w-[14px] h-3.5" viewBox="0 0 26 23" fill="currentColor" aria-hidden="true">
                            <path d="M0.44 12.11a1.5 1.5 0 010-2.12L9.99.44a1.5 1.5 0 012.12 2.12L3.62 11.05l8.49 8.48a1.5 1.5 0 01-2.12 2.12L.44 12.11zM25.06 12.55H1.5v-3h23.56v3z" />
                        </svg>
                </button>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 sm:gap-8 lg:gap-10 items-center justify-items-center" data-partners>
                    <?php foreach (array_slice($partners, 0, 4) as $logo) : ?>
                        <img src="<?php echo esc_url($logo); ?>" alt="" class="h-10 sm:h-12 lg:h-14 w-auto max-w-[120px] object-contain">
                    <?php endforeach; ?>
                </div>
                <button type="button" class="hidden sm:inline-block w-9 h-9 rounded-full bg-[#009BE3] text-white border-0 cursor-pointer justify-items-center" data-partners-next>
                    <svg class="min-w-[14px] max-w-[14px] h-3.5 rotate-180" viewBox="0 0 26 23" fill="currentColor" aria-hidden="true">
                            <path d="M0.44 12.11a1.5 1.5 0 010-2.12L9.99.44a1.5 1.5 0 012.12 2.12L3.62 11.05l8.49 8.48a1.5 1.5 0 01-2.12 2.12L.44 12.11zM25.06 12.55H1.5v-3h23.56v3z" />
                        </svg>
                </button>
            </div>
        </div>
    </section>

    <section class="bg-white pb-[var(--klinika-section-pb)]">
        <div class="klinika-container">
            <h2 class="m-0 mb-3 sm:mb-4 text-center font-[Montserrat] font-bold text-[22px] sm:text-[26px] lg:text-[32px]"><?php klinika_e('where_we'); ?></h2>
            <p class="m-0 mb-6 sm:mb-8 text-center font-[Montserrat] text-[14px] sm:text-[15px] lg:text-[16px] text-[#5C5C5C]">
                <?php echo esc_html(klinika_address()); ?><br>
                E-mail: <a href="mailto:<?php echo esc_attr(klinika_email()); ?>" class="no-underline text-[#5C5C5C]"><?php echo esc_html(klinika_email()); ?></a>
            </p>
            <div class="grid lg:grid-cols-[1.4fr_0.8fr] gap-3 sm:gap-4">
                <div class="klinika-map h-[220px] sm:h-[280px] lg:h-[320px] xl:h-[360px] 2xl:h-[400px] overflow-hidden">
                    <iframe title="<?php echo esc_attr(klinika_t('map_label')); ?>" src="https://yandex.ru/map-widget/v1/?ll=92.8686%2C56.0122&z=16&l=map&pt=92.8686%2C56.0122%2Cpm2rdm&text=Красноярск%2C%20Чернышевского%2075а" loading="lazy"></iframe>
                </div>
                <div class="grid grid-cols-2 lg:grid-cols-1 gap-3 sm:gap-4">
                    <img src="<?php echo esc_url($gallery[0] ?? klinika_demo_photo('clinic')); ?>" alt="" class="w-full h-[120px] sm:h-[140px] lg:h-[152px] xl:h-[172px] object-cover">
                    <img src="<?php echo esc_url($gallery[1] ?? klinika_demo_photo('service')); ?>" alt="" class="w-full h-[120px] sm:h-[140px] lg:h-[152px] xl:h-[172px] object-cover">
                </div>
            </div>
        </div>
    </section>
</main>
<?php get_footer(); ?>