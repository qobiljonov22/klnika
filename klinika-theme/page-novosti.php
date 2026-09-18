<?php
/**
 * Template Name: Новости
 *
 * @package Klinika
 */
get_header();
$query = new WP_Query([
    'post_type'      => 'klinika_news',
    'post_status'    => 'publish',
    'posts_per_page' => 6,
    'paged'          => max(1, (int) get_query_var('paged')),
]);
$items = $query->posts;
$fallback_text = 'Клиника «Здоровые дети» продолжает программу наблюдения малышей: профилактические осмотры, вакцинация и консультации специалистов в одном месте.';
if (empty($items)) {
    for ($i = 0; $i < 6; $i++) {
        $items[] = (object) [
            'post_content' => $fallback_text,
            'post_date'    => '2026-01-01 12:00:00',
            '_fake'        => true,
        ];
    }
}
?>
<main class="<?php echo esc_attr(klinika_tw('main')); ?>">
    <section class="<?php echo esc_attr(klinika_tw('section')); ?>">
        <div class="klinika-container max-w-[920px]">
            <nav class="<?php echo esc_attr(klinika_tw('crumbs-center')); ?>" aria-label="<?php echo esc_attr(klinika_t('breadcrumbs')); ?>">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-[#009BE3] no-underline text-[#9A9A9A]"><?php klinika_e('home'); ?></a>
                <span class="mx-1.5">/</span>
                <a href="<?php echo esc_url(klinika_info_hub_url()); ?>" class="hover:text-[#009BE3] no-underline text-[#9A9A9A]"><?php klinika_e('information'); ?></a>
                <span class="mx-1.5">/</span>
                <span><?php klinika_e('news'); ?></span>
            </nav>
            <h1 class="<?php echo esc_attr(klinika_tw('page-title')); ?>"><?php klinika_e('news'); ?></h1>
            <?php foreach ($items as $news) :
                $is_fake = !empty($news->_fake);
                $date_h  = $is_fake ? '01.01.2026' : get_the_date('d.m.Y', $news);
                $text    = wp_strip_all_tags($news->post_content ?: $fallback_text);
                ?>
                <article class="flex items-stretch gap-4 py-5 border-b border-[#7EC8E3]">
                    <span class="hidden sm:block w-[5px] shrink-0 bg-[#009BE3]"></span>
                    <div>
                        <time class="block mb-2 text-[13px] text-[#A0A0A0] font-[Montserrat]"><?php echo esc_html($date_h); ?></time>
                        <p class="m-0 font-[Montserrat] text-[15px] leading-[1.55]"><?php echo esc_html($text); ?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
</main>
<?php get_footer(); ?>
