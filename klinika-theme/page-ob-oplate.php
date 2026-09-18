<?php
/**
 * Template Name: Об оплате
 *
 * @package Klinika
 */
get_header();
$data = klinika_get_page_content('ob-oplate');
get_template_part('template-parts/info', 'section', [
    'klinika_info_active'     => 'ob-oplate',
    'klinika_info_title'      => klinika_t('tab_payment'),
    'klinika_info_paragraphs' => $data['paragraphs'] ?? [],
    'klinika_info_images'     => [
        klinika_img('pay-mir.png'),
        klinika_img('pay-mastercard.png'),
    ],
]);
get_footer();
