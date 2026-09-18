<?php
/**
 * Single doctor
 *
 * @package Klinika
 */
get_header();
$doctor_id  = get_the_ID();
$position   = klinika_doctor_meta($doctor_id, '_klinika_doctor_position', klinika_t('position_default'));
$experience = (int) klinika_doctor_meta($doctor_id, '_klinika_doctor_experience', 10);
$image      = get_the_post_thumbnail_url($doctor_id, 'large') ?: klinika_demo_doctors()[0]['image'];
$profile    = klinika_get_doctor_profile($doctor_id);
$schedule   = array_filter(array_map('trim', explode("\n", (string) get_post_meta($doctor_id, '_klinika_doctor_schedule', true))));
$home       = get_post_meta($doctor_id, '_klinika_doctor_home', true) === '1';
$doctor_name = get_the_title($doctor_id);
$reviews = get_posts([
    'post_type'      => 'klinika_review',
    'posts_per_page' => 6,
    'post_status'    => 'publish',
    's'              => $doctor_name,
]);
if (!$reviews) {
    $reviews = array_slice(get_posts([
        'post_type'      => 'klinika_review',
        'posts_per_page' => 3,
        'post_status'    => 'publish',
    ]), 0, 3);
}
?>
<main class="<?php echo esc_attr(klinika_tw('main')); ?>">
    <section class="<?php echo esc_attr(klinika_tw('section')); ?>">
        <div class="klinika-container">
            <nav class="<?php echo esc_attr(klinika_tw('crumbs-flush', 'mb-6')); ?>" aria-label="<?php echo esc_attr(klinika_t('breadcrumbs')); ?>">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-[#009BE3] no-underline text-[#9A9A9A]"><?php klinika_e('home'); ?></a>
                <span class="mx-1.5">/</span>
                <a href="<?php echo esc_url(klinika_page_url('specialisty')); ?>" class="hover:text-[#009BE3] no-underline text-[#9A9A9A]"><?php klinika_e('specialists'); ?></a>
                <span class="mx-1.5">/</span>
                <span><?php the_title(); ?></span>
            </nav>
            <div class="grid lg:grid-cols-[320px_1fr] gap-8 items-start">
                <img src="<?php echo esc_url($image); ?>" alt="<?php the_title_attribute(); ?>" class="w-full aspect-[4/5] object-cover" loading="lazy">
                <div>
                    <h1 class="m-0 mb-2 text-[28px] sm:text-[36px] font-[Montserrat] font-bold"><?php the_title(); ?></h1>
                    <p class="m-0 mb-1 text-[16px] text-[#5C5C5C] font-[Montserrat]"><?php echo esc_html($position); ?></p>
                    <p class="m-0 mb-2 text-[14px] text-[#9A9A9A] font-[Montserrat]"><?php echo esc_html($experience . ' ' . klinika_t('experience')); ?></p>
                    <?php if ($home) : ?>
                        <p class="m-0 mb-6 inline-block font-[Montserrat] text-[13px] text-[#04AA29] border border-[#04AA29] px-3 py-1">на дому</p>
                    <?php else : ?>
                        <div class="mb-6"></div>
                    <?php endif; ?>
                    <div class="<?php echo esc_attr(klinika_tw('muted', 'mb-8')); ?>">
                        <?php the_content(); ?>
                    </div>
                    <?php if ($schedule) : ?>
                        <h2 class="m-0 mb-3 font-[Montserrat] font-semibold text-[18px]">Расписание</h2>
                        <ul class="<?php echo esc_attr(klinika_tw('muted', 'mb-6 pl-5')); ?>">
                            <?php foreach ($schedule as $line) : ?>
                                <li class="mb-1"><?php echo esc_html($line); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                    <?php if (!empty($profile['education'])) : ?>
                        <h2 class="m-0 mb-3 font-[Montserrat] font-semibold text-[18px]">Образование</h2>
                        <ul class="<?php echo esc_attr(klinika_tw('muted', 'mb-6 pl-5')); ?>">
                            <?php foreach ($profile['education'] as $item) : ?>
                                <li class="mb-1"><?php echo esc_html($item); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                    <a <?php echo klinika_booking_attrs_html(klinika_tw('btn-green'), $doctor_name); ?> data-cta="doctor_book">Записаться к этому врачу</a>
                </div>
            </div>

            <?php if ($reviews) : ?>
                <div class="mt-12 max-w-[920px]">
                    <h2 class="m-0 mb-5 font-[Montserrat] font-semibold text-[22px]">Отзывы</h2>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <?php foreach ($reviews as $review) : ?>
                            <blockquote class="m-0 bg-[#F4FBFE] p-5 font-[Montserrat]">
                                <p class="m-0 mb-3 text-[14px] leading-relaxed text-[#1a1a1a]"><?php echo esc_html(wp_trim_words($review->post_content ?: $review->post_title, 40)); ?></p>
                                <cite class="not-italic text-[13px] text-[#5C5C5C]"><?php echo esc_html(get_the_title($review)); ?></cite>
                            </blockquote>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>
<?php get_footer(); ?>
