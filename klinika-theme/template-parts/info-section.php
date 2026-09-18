<?php
/**
 * Shared information page layout
 *
 * @package Klinika
 */
$active     = $args['klinika_info_active'] ?? '';
$title      = $args['klinika_info_title'] ?? get_the_title();
$paragraphs = $args['klinika_info_paragraphs'] ?? [];
$list_items = $args['klinika_info_list'] ?? [];
$images     = $args['klinika_info_images'] ?? [];
$hero       = $args['klinika_info_hero'] ?? '';
?>
<main class="<?php echo esc_attr(klinika_tw('main')); ?>">
    <section class="<?php echo esc_attr(klinika_tw('section')); ?>">
        <div class="klinika-container max-w-[920px]">
            <nav class="<?php echo esc_attr(klinika_tw('crumbs-center')); ?>" aria-label="<?php echo esc_attr(klinika_t('breadcrumbs')); ?>">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-[#009BE3] no-underline text-[#9A9A9A]"><?php klinika_e('home'); ?></a>
                <span class="mx-1.5">/</span>
                <a href="<?php echo esc_url(klinika_info_hub_url()); ?>" class="hover:text-[#009BE3] no-underline text-[#9A9A9A]"><?php klinika_e('information'); ?></a>
                <span class="mx-1.5">/</span>
                <span><?php echo esc_html($title); ?></span>
            </nav>
            <h1 class="<?php echo esc_attr(klinika_tw('page-title')); ?>"><?php echo esc_html($title); ?></h1>
            <?php if ($hero) : ?>
                <img src="<?php echo esc_url($hero); ?>" alt="" class="w-full max-w-[560px] mx-auto mb-8 aspect-[4/5] sm:aspect-[16/11] object-cover object-top">
            <?php endif; ?>
            <div class="flex flex-wrap justify-center gap-2 mb-8">
                <?php foreach (klinika_info_nav_items() as $slug => $label) :
                    $is = $slug === $active;
                    ?>
                    <a href="<?php echo esc_url(klinika_page_url($slug)); ?>" class="px-3 py-1.5 text-[13px] font-[Montserrat] no-underline border <?php echo $is ? 'bg-[#009BE3] text-white border-[#009BE3]' : 'text-[#009BE3] border-[#7EC8E3] hover:bg-[#ECF9FF]'; ?>">
                        <?php echo esc_html($label); ?>
                    </a>
                <?php endforeach; ?>
            </div>
            <div class="<?php echo esc_attr(klinika_tw('muted')); ?>">
                <?php foreach ($paragraphs as $para) : ?>
                    <p class="m-0 mb-5"><?php echo esc_html($para); ?></p>
                <?php endforeach; ?>
                <?php if ($list_items) : ?>
                    <ol class="m-0 pl-5 space-y-2">
                        <?php foreach ($list_items as $item) : ?>
                            <li><?php echo esc_html($item); ?></li>
                        <?php endforeach; ?>
                    </ol>
                <?php endif; ?>
                <?php if ($images) : ?>
                    <div class="mt-8 flex flex-wrap items-center justify-center gap-6">
                        <?php foreach ($images as $src) : ?>
                            <img src="<?php echo esc_url($src); ?>" alt="" class="h-10 sm:h-12 w-auto object-contain">
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>
