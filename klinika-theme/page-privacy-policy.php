<?php
/**
 * Template Name: Политика конфиденциальности
 *
 * @package Klinika
 */
get_header();
?>
<main class="<?php echo esc_attr(klinika_tw('main')); ?>">
    <section class="<?php echo esc_attr(klinika_tw('section')); ?>">
        <div class="klinika-container max-w-[800px]">
            <nav class="<?php echo esc_attr(klinika_tw('crumbs-center')); ?>" aria-label="<?php echo esc_attr(klinika_t('breadcrumbs')); ?>">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-[#009BE3] no-underline text-[#9A9A9A]"><?php klinika_e('home'); ?></a>
                <span class="mx-1.5">/</span>
                <span>Политика конфиденциальности</span>
            </nav>
            <h1 class="<?php echo esc_attr(klinika_tw('page-title')); ?>">Политика конфиденциальности</h1>
            <div class="font-[Montserrat] text-[15px] leading-relaxed text-[#1a1a1a] space-y-4">
                <?php
                while (have_posts()) :
                    the_post();
                    if (get_the_content()) {
                        the_content();
                    } else {
                        echo '<p>Мы обрабатываем имя и телефон только для записи на приём и обратной связи. Cookie используются для удобства сайта. Данные не передаются третьим лицам без законного основания.</p>';
                        echo '<p>Контакты: ' . esc_html(klinika_email()) . ', ' . esc_html(klinika_phone()) . '.</p>';
                    }
                endwhile;
                ?>
            </div>
        </div>
    </section>
</main>
<?php get_footer(); ?>
