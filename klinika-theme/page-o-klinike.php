<?php
/**
 * Template Name: О клинике
 *
 * @package Klinika
 */
get_header();
$data = klinika_get_page_content('o-klinike');
get_template_part('template-parts/info', 'section', [
    'klinika_info_active'     => 'o-klinike',
    'klinika_info_title'      => klinika_t('about_clinic'),
    'klinika_info_paragraphs' => $data['paragraphs'] ?? [],
    'klinika_info_list'       => $data['list_items'] ?? [],
    'klinika_info_hero'       => klinika_demo_photo('about'),
]);
get_footer();
