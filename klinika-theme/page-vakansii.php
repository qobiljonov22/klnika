<?php
/**
 * Template Name: Вакансии
 *
 * @package Klinika
 */
get_header();
$data = klinika_get_page_content('vakansii');
get_template_part('template-parts/info', 'section', [
    'klinika_info_active'     => 'vakansii',
    'klinika_info_title'      => klinika_t('tab_vacancies'),
    'klinika_info_paragraphs' => $data['paragraphs'] ?? [],
]);
get_footer();
