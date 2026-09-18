<?php
/**
 * Template Name: Информация
 *
 * @package Klinika
 */
get_header();
get_template_part('template-parts/info', 'section', [
    'klinika_info_active'     => 'o-klinike',
    'klinika_info_title'      => klinika_t('information'),
    'klinika_info_paragraphs' => klinika_get_page_content('o-klinike')['paragraphs'] ?? [],
]);
get_footer();
