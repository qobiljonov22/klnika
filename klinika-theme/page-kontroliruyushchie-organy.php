<?php
/**
 * Template Name: Контролирующие органы
 *
 * @package Klinika
 */
get_header();
$data = klinika_get_page_content('kontroliruyushchie-organy');
get_template_part('template-parts/info', 'section', [
    'klinika_info_active'     => 'kontroliruyushchie-organy',
    'klinika_info_title'      => klinika_t('tab_authorities'),
    'klinika_info_paragraphs' => $data['paragraphs'] ?? [],
]);
get_footer();
