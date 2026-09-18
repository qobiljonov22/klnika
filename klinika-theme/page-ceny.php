<?php
/**
 * Template Name: Цены
 *
 * @package Klinika
 */
get_header();
$groups = [
    'priem'   => 'Приёмы',
    'analizy' => 'Анализы',
    'diag'    => 'Диагностика',
];
$all_prices = klinika_price_items('all');
$min_default = 0;
$max_default = 50000;
?>
<main class="<?php echo esc_attr(klinika_tw('main')); ?>">
    <section class="<?php echo esc_attr(klinika_tw('section')); ?>">
        <div class="klinika-container max-w-[920px]">
            <nav class="<?php echo esc_attr(klinika_tw('crumbs-center')); ?>" aria-label="<?php echo esc_attr(klinika_t('breadcrumbs')); ?>">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-[#009BE3] no-underline text-[#9A9A9A]"><?php klinika_e('home'); ?></a>
                <span class="mx-1.5">/</span>
                <span><?php klinika_e('prices'); ?></span>
            </nav>
            <h1 class="<?php echo esc_attr(klinika_tw('page-title')); ?>"><?php klinika_e('prices'); ?></h1>

            <div class="mb-8 grid sm:grid-cols-3 gap-3 font-[Montserrat]" data-price-filters>
                <label class="text-[13px] text-[#5C5C5C]">От, ₽
                    <input type="number" min="0" value="<?php echo (int) $min_default; ?>" class="klinika-input mt-1" data-price-min>
                </label>
                <label class="text-[13px] text-[#5C5C5C]">До, ₽
                    <input type="number" min="0" value="<?php echo (int) $max_default; ?>" class="klinika-input mt-1" data-price-max>
                </label>
                <label class="text-[13px] text-[#5C5C5C]">Группа
                    <select class="klinika-input mt-1" data-price-group>
                        <option value="all">Все</option>
                        <?php foreach ($groups as $key => $label) : ?>
                            <option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($label); ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
            </div>

            <?php foreach ($groups as $key => $label) :
                $items = klinika_price_items($key);
                ?>
                <div data-price-group-block="<?php echo esc_attr($key); ?>">
                    <h2 class="m-0 mt-8 mb-3 font-[Montserrat] font-semibold text-[18px] text-[#009BE3]"><?php echo esc_html($label); ?></h2>
                    <div class="bg-[#F4FBFE] px-4 sm:px-8 py-2">
                        <?php foreach ($items as $item) :
                            $num = (int) preg_replace('/\D+/', '', (string) $item['price']);
                            ?>
                            <div class="klinika-table-row" data-price-row data-price-value="<?php echo esc_attr($num); ?>" data-price-group="<?php echo esc_attr($key); ?>">
                                <span class="text-[14px] sm:text-[15px] text-[#1a1a1a]"><?php echo esc_html($item['title']); ?></span>
                                <span class="font-semibold text-[15px] whitespace-nowrap"><?php echo esc_html($item['price']); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            <div class="mt-10 text-center">
                <a <?php echo klinika_booking_attrs_html(klinika_tw('btn-green')); ?> data-cta="prices_book">
                    <?php klinika_e('book'); ?>
                </a>
            </div>
        </div>
    </section>
</main>
<?php get_footer(); ?>
