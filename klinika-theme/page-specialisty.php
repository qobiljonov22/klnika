<?php
/**
 * Template Name: Специалисты
 *
 * @package Klinika
 */
get_header();
$doctors     = klinika_doctor_cards(12);
$specialties = klinika_get_specialties();
if (is_wp_error($specialties)) {
    $specialties = [];
}
$wp_doctors = klinika_get_doctors();
?>
<main class="<?php echo esc_attr(klinika_tw('main')); ?>">
    <section class="<?php echo esc_attr(klinika_tw('section')); ?>">
        <div class="klinika-container">
            <nav class="<?php echo esc_attr(klinika_tw('crumbs-center')); ?>" aria-label="<?php echo esc_attr(klinika_t('breadcrumbs')); ?>">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-[#009BE3] no-underline text-[#9A9A9A]"><?php klinika_e('home'); ?></a>
                <span class="mx-1.5">/</span>
                <span><?php klinika_e('specialists'); ?></span>
            </nav>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4" data-specialisty-filter>
                <h1 class="m-0 text-[28px] sm:text-[36px] font-[Montserrat] font-bold"><?php klinika_e('specialists'); ?></h1>
                <div class="relative w-full sm:w-[240px]">
                    <div class="hidden fixed inset-0 z-40 bg-black/40 sm:hidden" data-specialisty-backdrop></div>
                    <button type="button" data-specialisty-filter-toggle class="relative z-50 flex w-full items-center justify-between gap-3 px-4 py-3 bg-white border border-[#7EC8E3] font-[Montserrat] text-[15px] cursor-pointer">
                        <span data-specialisty-filter-label class="text-[#009BE3]"><?php klinika_e('all'); ?></span>
                        <svg class="w-3 h-3 text-[#009BE3]" data-specialisty-filter-icon viewBox="0 0 12 8" fill="currentColor"><path d="M6 8L0.2 0.5h11.6L6 8z"/></svg>
                    </button>
                    <ul class="hidden absolute left-0 right-0 top-full mt-1 z-50 m-0 list-none bg-white border border-[#009BE3] px-4 py-3" data-specialisty-filter-list>
                        <li>
                            <button type="button" data-specialty="all" data-label="<?php echo esc_attr(klinika_t('all')); ?>" class="block w-full text-left border-0 bg-transparent py-2 font-[Montserrat] text-[15px] cursor-pointer"><?php klinika_e('all'); ?></button>
                        </li>
                        <?php foreach ($specialties as $specialty) : ?>
                            <li>
                                <button type="button" data-specialty="<?php echo esc_attr($specialty->slug); ?>" data-label="<?php echo esc_attr($specialty->name); ?>" class="block w-full text-left border-0 bg-transparent py-2 font-[Montserrat] text-[15px] cursor-pointer"><?php echo esc_html($specialty->name); ?></button>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <label class="mb-8 inline-flex items-center gap-2 font-[Montserrat] text-[14px] text-[#5C5C5C] cursor-pointer">
                <input type="checkbox" class="accent-[#009BE3]" data-home-filter>
                только «на дому»
            </label>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5" data-specialisty-grid>
                <?php foreach ($doctors as $i => $doctor) :
                    $spec = 'all';
                    $home = '0';
                    if (!empty($wp_doctors[$i])) {
                        $terms = get_the_terms($wp_doctors[$i]->ID, 'klinika_specialty');
                        if ($terms && !is_wp_error($terms)) {
                            $spec = implode(',', wp_list_pluck($terms, 'slug'));
                        }
                        $home = get_post_meta($wp_doctors[$i]->ID, '_klinika_doctor_home', true) === '1' ? '1' : '0';
                    }
                    ?>
                    <article class="bg-white" data-doctor-card data-specialty="<?php echo esc_attr($spec); ?>" data-home="<?php echo esc_attr($home); ?>">
                        <img src="<?php echo esc_url($doctor['image']); ?>" alt="<?php echo esc_attr($doctor['name']); ?>" class="w-full aspect-[4/5] object-cover" loading="lazy">
                        <div class="pt-3">
                            <h2 class="m-0 mb-1 font-[Montserrat] font-semibold text-[16px]"><?php echo esc_html($doctor['name']); ?></h2>
                            <p class="m-0 text-[14px] text-[#5C5C5C]"><?php echo esc_html($doctor['position']); ?></p>
                            <p class="m-0 mb-3 text-[13px] text-[#9A9A9A]"><?php echo esc_html($doctor['experience'] . ' ' . klinika_t('experience')); ?><?php echo $home === '1' ? ' · на дому' : ''; ?></p>
                            <a href="<?php echo esc_url($doctor['url']); ?>" class="<?php echo esc_attr(klinika_tw('btn-green-sm', 'w-full')); ?>"><?php klinika_e('more'); ?></a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</main>
<?php get_footer(); ?>
