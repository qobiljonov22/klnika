<?php
/**
 * Template Name: Пациентам
 *
 * @package Klinika
 */
get_header();
$data = klinika_get_page_content('patsientam');
get_template_part('template-parts/info', 'section', [
    'klinika_info_active'     => 'patsientam',
    'klinika_info_title'      => klinika_t('tab_patients'),
    'klinika_info_paragraphs' => $data['paragraphs'] ?? [],
]);
get_footer();
