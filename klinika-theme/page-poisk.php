<?php
/**
 * Template Name: Поиск
 *
 * @package Klinika
 */
get_header();
$q = isset($_GET['q']) ? sanitize_text_field(wp_unslash($_GET['q'])) : '';
$results = ['doctors' => [], 'services' => [], 'prices' => []];
if ($q !== '') {
    $needle = mb_strtolower($q);
    foreach (klinika_doctor_cards(100) as $doc) {
        $hay = mb_strtolower($doc['name'] . ' ' . ($doc['position'] ?? ''));
        if (mb_strpos($hay, $needle) !== false) {
            $results['doctors'][] = $doc;
        }
    }
    foreach (klinika_service_items() as $svc) {
        $hay = mb_strtolower($svc['title'] . ' ' . ($svc['excerpt'] ?? ''));
        if (mb_strpos($hay, $needle) !== false) {
            $results['services'][] = $svc;
        }
    }
    foreach (klinika_price_items('all') as $price) {
        $hay = mb_strtolower($price['title']);
        if (mb_strpos($hay, $needle) !== false) {
            $results['prices'][] = $price;
        }
    }
}
$total = count($results['doctors']) + count($results['services']) + count($results['prices']);
?>
<main class="<?php echo esc_attr(klinika_tw('main')); ?>">
    <section class="<?php echo esc_attr(klinika_tw('section')); ?>">
        <div class="klinika-container max-w-[920px]">
            <nav class="<?php echo esc_attr(klinika_tw('crumbs-center')); ?>" aria-label="<?php echo esc_attr(klinika_t('breadcrumbs')); ?>">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-[#009BE3] no-underline text-[#9A9A9A]"><?php klinika_e('home'); ?></a>
                <span class="mx-1.5">/</span>
                <span>Поиск</span>
            </nav>
            <h1 class="<?php echo esc_attr(klinika_tw('page-title')); ?>">Поиск</h1>
            <form method="get" action="<?php echo esc_url(klinika_page_url('poisk')); ?>" class="flex flex-col sm:flex-row gap-3 mb-8">
                <input type="search" name="q" value="<?php echo esc_attr($q); ?>" placeholder="Врач, услуга, анализ…" class="klinika-input flex-1" required>
                <button type="submit" class="klinika-booking-btn shrink-0" data-cta="search">Найти</button>
            </form>
            <?php if ($q === '') : ?>
                <p class="font-[Montserrat] text-[15px] text-[#5C5C5C]">Введите запрос по врачу, услуге или анализу.</p>
            <?php elseif ($total === 0) : ?>
                <p class="font-[Montserrat] text-[15px] text-[#5C5C5C]">Ничего не найдено по «<?php echo esc_html($q); ?>».</p>
            <?php else : ?>
                <?php if ($results['doctors']) : ?>
                    <h2 class="m-0 mt-2 mb-3 font-[Montserrat] font-semibold text-[18px] text-[#009BE3]">Врачи</h2>
                    <div class="space-y-3 mb-8">
                        <?php foreach ($results['doctors'] as $doc) : ?>
                            <a href="<?php echo esc_url($doc['url']); ?>" class="block no-underline bg-[#F4FBFE] px-4 py-3 font-[Montserrat] hover:bg-[#ECF9FF]">
                                <span class="font-semibold text-[#1a1a1a]"><?php echo esc_html($doc['name']); ?></span>
                                <span class="block text-[13px] text-[#5C5C5C]"><?php echo esc_html($doc['position'] ?? ''); ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <?php if ($results['services']) : ?>
                    <h2 class="m-0 mt-2 mb-3 font-[Montserrat] font-semibold text-[18px] text-[#009BE3]">Услуги</h2>
                    <div class="space-y-3 mb-8">
                        <?php foreach ($results['services'] as $svc) : ?>
                            <a href="<?php echo esc_url($svc['url']); ?>" class="block no-underline bg-[#F4FBFE] px-4 py-3 font-[Montserrat] text-[#1a1a1a] hover:bg-[#ECF9FF]"><?php echo esc_html($svc['title']); ?></a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <?php if ($results['prices']) : ?>
                    <h2 class="m-0 mt-2 mb-3 font-[Montserrat] font-semibold text-[18px] text-[#009BE3]">Анализы / цены</h2>
                    <div class="bg-[#F4FBFE] px-4 sm:px-8 py-2 mb-8">
                        <?php foreach ($results['prices'] as $item) : ?>
                            <div class="klinika-table-row">
                                <span class="text-[14px] sm:text-[15px] text-[#1a1a1a]"><?php echo esc_html($item['title']); ?></span>
                                <span class="font-semibold text-[15px] whitespace-nowrap"><?php echo esc_html($item['price']); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            <p class="mt-6">
                <a href="<?php echo esc_url(klinika_page_url('moi-zapis')); ?>" class="font-[Montserrat] text-[#009BE3] no-underline hover:underline">Мои записи</a>
            </p>
        </div>
    </section>
</main>
<?php get_footer(); ?>
