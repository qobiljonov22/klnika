<?php
/**
 * Template Name: Услуги
 *
 * @package Klinika
 */
get_header();

$uslugi_url = klinika_uslugi_url();
$services   = klinika_service_items();
$current_slug = isset($_GET['service']) ? sanitize_title(wp_unslash($_GET['service'])) : '';
$current = $services[0] ?? null;
if ($current_slug) {
    foreach ($services as $service) {
        if ($service['slug'] === $current_slug) {
            $current = $service;
            break;
        }
    }
} else {
    foreach ($services as $service) {
        if ($service['slug'] === 'pediatr' || $service['title'] === 'Педиатр') {
            $current = $service;
            break;
        }
    }
}
if (!$current && $services) {
    $current = $services[0];
}
$active_title = $current['title'] ?? klinika_t('services');
?>
<main class="<?php echo esc_attr(klinika_tw('main')); ?>">
    <section class="<?php echo esc_attr(klinika_tw('section-crumbs')); ?>">
        <div class="klinika-container">
            <nav class="<?php echo esc_attr(klinika_tw('crumbs-flush')); ?>" aria-label="<?php echo esc_attr(klinika_t('breadcrumbs')); ?>">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-[#009BE3] no-underline text-[#9A9A9A]"><?php klinika_e('home'); ?></a>
                <span class="mx-1.5">/</span>
                <a href="<?php echo esc_url($uslugi_url); ?>" class="hover:text-[#009BE3] no-underline text-[#9A9A9A]"><?php klinika_e('services'); ?></a>
                <span class="mx-1.5">/</span>
                <span><?php echo esc_html($active_title); ?></span>
            </nav>
        </div>
    </section>

    <section class="bg-white py-6 lg:py-10">
        <div class="klinika-container">
            <h1 class="<?php echo esc_attr(klinika_tw('page-title-left')); ?>"><?php echo esc_html($active_title); ?></h1>
            <div class="flex flex-col lg:flex-row gap-6 lg:gap-10 items-start">
                <aside class="w-full lg:w-[300px] shrink-0" data-uslugi-nav>
                    <nav class="hidden md:block border border-[#E8E8E8] font-[Montserrat]" aria-label="<?php echo esc_attr(klinika_t('service_list')); ?>">
                        <ul class="m-0 p-0 list-none">
                            <?php foreach ($services as $i => $service) :
                                $is_active = $current && $current['slug'] === $service['slug'];
                                ?>
                                <li class="<?php echo $i > 0 ? 'border-t border-[#E8E8E8]' : ''; ?>">
                                    <a href="<?php echo esc_url($service['url']); ?>" class="block px-5 py-3.5 text-[15px] no-underline transition-colors <?php echo $is_active ? 'bg-[#009BE3] text-white font-medium' : 'text-[#5C5C5C] hover:bg-[#ECF9FF] hover:text-[#009BE3]'; ?>">
                                        <?php echo esc_html($service['title']); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </nav>
                    <div class="md:hidden relative z-30 font-[Montserrat]">
                        <div class="hidden fixed inset-0 z-20 bg-black/40" data-uslugi-backdrop></div>
                        <button type="button" data-uslugi-toggle aria-expanded="false" class="relative z-30 flex w-full items-center justify-between gap-3 px-4 py-3 bg-white border border-[#7EC8E3] text-[#009BE3] text-[15px] cursor-pointer">
                            <span data-uslugi-label><?php echo esc_html($active_title); ?></span>
                            <svg class="w-3 h-3" data-uslugi-icon viewBox="0 0 12 8" fill="currentColor"><path d="M6 8L0.2 0.5h11.6L6 8z"/></svg>
                        </button>
                        <div class="hidden absolute left-0 right-0 top-full mt-2 z-30 bg-white rounded-[24px] shadow-lg py-4 px-5" data-uslugi-modal>
                            <ul class="m-0 p-0 list-none">
                                <?php foreach ($services as $service) : ?>
                                    <li>
                                        <a href="<?php echo esc_url($service['url']); ?>" class="block py-2.5 text-[16px] text-[#333] no-underline"><?php echo esc_html($service['title']); ?></a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </aside>
                <div class="flex-1 min-w-0">
                    <?php if ($current) : ?>
                        <img src="<?php echo esc_url($current['image']); ?>" alt="<?php echo esc_attr($current['title']); ?>" class="w-full max-w-[560px] aspect-[16/11] object-cover mb-6">
                        <div class="<?php echo esc_attr(klinika_tw('muted', 'mb-8 space-y-4')); ?>">
                            <p class="m-0"><?php echo esc_html($current['text']); ?></p>
                        </div>
                        <a <?php echo klinika_booking_attrs_html(klinika_tw('btn-green')); ?>>
                            <?php klinika_e('book'); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</main>
<?php get_footer(); ?>
