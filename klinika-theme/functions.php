<?php
/**
 * Клиника Здоровые дети — theme setup.
 *
 * @package Klinika
 */

if (!defined('ABSPATH')) {
    exit;
}

define('KLINIKA_VERSION', '1.9.0');

require_once get_template_directory() . '/inc/admin.php';
require_once get_template_directory() . '/inc/rest-api.php';
require_once get_template_directory() . '/inc/features.php';
require_once get_template_directory() . '/inc/dashboard.php';

function klinika_setup()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', [
        'height'      => 72,
        'width'       => 220,
        'flex-height' => true,
        'flex-width'  => true,
    ]);
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption']);
    register_nav_menus([
        'primary' => 'Главное меню',
    ]);
    add_image_size('klinika-card', 640, 640, true);
    add_image_size('klinika-wide', 1320, 740, true);
}
add_action('after_setup_theme', 'klinika_setup');

function klinika_assets()
{
    wp_enqueue_style('klinika-fonts', 'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap', [], null);
    wp_enqueue_style('klinika-main', get_template_directory_uri() . '/assets/css/main.css', ['klinika-fonts'], KLINIKA_VERSION);
    wp_enqueue_script('klinika-theme', get_template_directory_uri() . '/assets/js/theme.js', [], KLINIKA_VERSION, true);
    wp_localize_script('klinika-theme', 'klinikaTheme', [
        'restUrl'   => esc_url_raw(rest_url('klinika/v1/')),
        'restNonce' => wp_create_nonce('wp_rest'),
        'homeUrl'   => home_url('/'),
    ]);
}
add_action('wp_enqueue_scripts', 'klinika_assets');

function klinika_tailwind_cdn()
{
    echo '<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>' . "\n";
    echo '<style type="text/tailwindcss">' . "\n";
    echo '@theme {' . "\n";
    echo '  --breakpoint-sm: 640px;' . "\n";
    echo '  --breakpoint-md: 768px;' . "\n";
    echo '  --breakpoint-lg: 1024px;' . "\n";
    echo '  --breakpoint-xl: 1280px;' . "\n";
    echo '  --breakpoint-2xl: 1536px;' . "\n";
    echo '  --breakpoint-3xl: 1800px;' . "\n";
    echo '  --breakpoint-4xl: 2000px;' . "\n";
    echo '}' . "\n";
    echo '</style>' . "\n";
}
add_action('wp_head', 'klinika_tailwind_cdn', 1);

function klinika_register_cpts()
{
    $items = [
        'klinika_service'    => ['Услуги', 'Услуга', 'dashicons-heart'],
        'klinika_doctor'     => ['Специалисты', 'Специалист', 'dashicons-id'],
        'klinika_news'       => ['Новости', 'Новость', 'dashicons-megaphone'],
        'klinika_review'     => ['Отзывы', 'Отзыв', 'dashicons-format-quote'],
        'klinika_gallery'    => ['Галерея', 'Фото', 'dashicons-format-gallery'],
        'klinika_license'    => ['Лицензии', 'Лицензия', 'dashicons-awards'],
        'klinika_advantage'  => ['Преимущества', 'Преимущество', 'dashicons-star-filled'],
        'klinika_partner'    => ['Партнёры', 'Партнёр', 'dashicons-groups'],
        'klinika_slide'      => ['Слайды', 'Слайд', 'dashicons-images-alt2'],
        'klinika_price'      => ['Цены', 'Цена', 'dashicons-tag'],
        'klinika_booking'    => ['Заявки', 'Заявка', 'dashicons-clipboard'],
    ];

    foreach ($items as $type => $cfg) {
        $is_public = in_array($type, ['klinika_doctor', 'klinika_service', 'klinika_news'], true);
        register_post_type($type, [
            'labels' => [
                'name'          => $cfg[0],
                'singular_name' => $cfg[1],
                'add_new_item'  => 'Добавить: ' . $cfg[1],
                'edit_item'     => 'Редактировать: ' . $cfg[1],
            ],
            'public'       => $is_public,
            'show_ui'      => true,
            'show_in_rest' => true,
            'has_archive'  => false,
            'supports'     => ['title', 'editor', 'thumbnail', 'page-attributes', 'excerpt'],
            'menu_icon'    => $cfg[2],
            'rewrite'      => ['slug' => $type === 'klinika_doctor' ? 'specialist' : sanitize_title($cfg[1])],
            'capability_type' => 'post',
            'map_meta_cap'    => true,
        ]);
    }

    register_taxonomy('klinika_specialty', ['klinika_doctor', 'klinika_news'], [
        'labels' => [
            'name'          => 'Специализации',
            'singular_name' => 'Специализация',
        ],
        'public'       => true,
        'hierarchical' => true,
        'show_ui'      => true,
        'show_in_rest' => true,
        'rewrite'      => ['slug' => 'specialty'],
    ]);
}
add_action('init', 'klinika_register_cpts');

function klinika_flush_rewrites()
{
    klinika_register_cpts();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'klinika_flush_rewrites');

function klinika_maybe_flush_rewrites()
{
    if (get_option('klinika_rewrite_ver') === KLINIKA_VERSION) {
        return;
    }
    klinika_register_cpts();
    flush_rewrite_rules(false);
    update_option('klinika_rewrite_ver', KLINIKA_VERSION);
}
add_action('init', 'klinika_maybe_flush_rewrites', 20);

function klinika_body_class($classes)
{
    $classes[] = 'klinika-body';
    return $classes;
}
add_filter('body_class', 'klinika_body_class');

function klinika_current_lang()
{
    if (!empty($_GET['lang'])) {
        $lang = sanitize_key(wp_unslash($_GET['lang']));
        if (in_array($lang, ['ru', 'en'], true)) {
            return $lang;
        }
    }
    if (!empty($_COOKIE['klinika_lang']) && in_array($_COOKIE['klinika_lang'], ['ru', 'en'], true)) {
        return $_COOKIE['klinika_lang'];
    }
    return 'ru';
}

function klinika_strings()
{
    return [
        'ru' => [
            'home' => 'Главная',
            'services' => 'Услуги',
            'analyses' => 'Анализы',
            'prices' => 'Цены',
            'specialists' => 'Специалисты',
            'gallery' => 'Галерея',
            'information' => 'Информация',
            'contacts' => 'Контакты',
            'about_clinic' => 'О клинике',
            'news' => 'Новости',
            'reviews' => 'Отзывы',
            'licenses' => 'Лицензии',
            'vacancies' => 'Вакансии',
            'patients' => 'Пациентам',
            'payment' => 'Об оплате',
            'authorities' => 'Контролирующие органы',
            'breadcrumbs' => 'Навигация',
            'clinic' => 'Клиника',
            'phone_label' => 'Телефон',
            'email_label' => 'E-mail',
            'address_label' => 'Адрес',
            'hours_label' => 'Режим работы',
            'map_label' => 'Карта',
            'privacy' => 'Политика конфиденциальности',
            'more' => 'Подробнее',
            'read_more' => 'Читать полностью',
            'book' => 'Записаться на прием',
            'all' => 'Все',
            'specialty_label' => 'Специализация',
            'service_list' => 'Список услуг',
            'choose_service' => 'Выберите услугу',
            'news_pagination' => 'Страницы новостей',
            'next_page' => 'Следующая страница',
            'edit_in_wp' => 'Редактировать в WordPress',
            'search_placeholder' => 'Поиск',
            'hero_title' => 'Детская клиника в Красноярске',
            'why_us' => 'Почему именно к нам?',
            'our_doctors' => 'Наши доктора',
            'callback_title' => 'Мы всегда на связи',
            'name_placeholder' => 'Имя',
            'phone_placeholder' => 'Телефон',
            'send' => 'Отправить',
            'how_to' => 'Как добраться',
            'tab_about' => 'О клинике',
            'tab_news' => 'Новости',
            'tab_reviews' => 'Отзывы',
            'tab_licenses' => 'Лицензии',
            'tab_vacancies' => 'Вакансии',
            'tab_patients' => 'Пациентам',
            'tab_payment' => 'Об оплате',
            'tab_authorities' => 'Контролирующие органы',
            'director_label' => 'Главный врач',
            'experience' => 'лет стажа',
            'pediatrician' => 'Педиатр',
            'surgeon' => 'Хирург',
            'position_default' => 'Должность',
            'lang_ru' => 'RU',
            'lang_en' => 'EN',
            'open_menu' => 'Открыть меню',
            'close_form' => 'Закрыть',
            'booking_title' => 'Запись на приём',
            'our_services' => 'Наши услуги',
            'our_gallery' => 'Галерея',
            'our_reviews' => 'Отзывы',
            'faq' => 'Вопросы',
            'partners_gov' => 'Лицензирующие ведомства',
            'partners_aop' => 'Члены АОП «Дети»',
            'always_touch' => 'Мы всегда на связи',
            'online_book' => 'Онлайн-запись',
            'learn_more' => 'Узнать больше',
            'video_about' => 'Видео о клинике',
            'call_home' => 'Вызвать на дом',
            'see_all' => 'Смотреть все',
            'see_all_doctors' => 'Смотреть всех',
            'go' => 'Перейти',
            'director' => 'Директор клиники',
            'to_doctor_card' => 'Перейти к карточке врача >>',
            'read_more_btn' => 'Читать больше',
            'waiting' => 'Мы ждём вас!',
            'your_data' => 'Ваши данные',
            'phone_number' => 'Номер телефона',
            'latest_news' => 'Последние новости',
            'our_partners' => 'Наши партнёры',
            'where_we' => 'Где мы находимся',
            'call_us' => 'По всем вопросам звоните:',
            'best_clinic' => 'Лучшая клиника по версии Комсомольской правды',
            'exp_label' => 'Стаж',
        ],
        'en' => [
            'home' => 'Home',
            'services' => 'Services',
            'analyses' => 'Tests',
            'prices' => 'Prices',
            'specialists' => 'Doctors',
            'gallery' => 'Gallery',
            'information' => 'Information',
            'contacts' => 'Contacts',
            'about_clinic' => 'About the clinic',
            'news' => 'News',
            'reviews' => 'Reviews',
            'licenses' => 'Licenses',
            'vacancies' => 'Vacancies',
            'patients' => 'For patients',
            'payment' => 'Payment',
            'authorities' => 'Authorities',
            'breadcrumbs' => 'Breadcrumbs',
            'clinic' => 'Clinic',
            'phone_label' => 'Phone',
            'email_label' => 'E-mail',
            'address_label' => 'Address',
            'hours_label' => 'Hours',
            'map_label' => 'Map',
            'privacy' => 'Privacy policy',
            'more' => 'Learn more',
            'read_more' => 'Read more',
            'book' => 'Book an appointment',
            'all' => 'All',
            'specialty_label' => 'Specialty',
            'service_list' => 'Services list',
            'choose_service' => 'Choose a service',
            'news_pagination' => 'News pagination',
            'next_page' => 'Next page',
            'edit_in_wp' => 'Edit in WordPress',
            'search_placeholder' => 'Search',
            'hero_title' => 'Children’s clinic in Krasnoyarsk',
            'why_us' => 'Why choose us?',
            'our_doctors' => 'Our doctors',
            'callback_title' => 'We are always in touch',
            'name_placeholder' => 'Name',
            'phone_placeholder' => 'Phone',
            'send' => 'Send',
            'how_to' => 'How to get there',
            'tab_about' => 'About',
            'tab_news' => 'News',
            'tab_reviews' => 'Reviews',
            'tab_licenses' => 'Licenses',
            'tab_vacancies' => 'Vacancies',
            'tab_patients' => 'Patients',
            'tab_payment' => 'Payment',
            'tab_authorities' => 'Authorities',
            'director_label' => 'Chief physician',
            'experience' => 'years of experience',
            'pediatrician' => 'Pediatrician',
            'surgeon' => 'Surgeon',
            'position_default' => 'Position',
            'lang_ru' => 'RU',
            'lang_en' => 'EN',
            'open_menu' => 'Open menu',
            'close_form' => 'Close',
            'booking_title' => 'Book an appointment',
            'our_services' => 'Our services',
            'our_gallery' => 'Gallery',
            'our_reviews' => 'Reviews',
            'faq' => 'Questions',
            'partners_gov' => 'Licensing authorities',
            'partners_aop' => 'AOP “Children” members',
            'always_touch' => 'We are always in touch',
            'online_book' => 'Online booking',
            'learn_more' => 'Learn more',
            'video_about' => 'Video about the clinic',
            'call_home' => 'Home visit',
            'see_all' => 'See all',
            'see_all_doctors' => 'See all doctors',
            'go' => 'Open',
            'director' => 'Clinic director',
            'to_doctor_card' => 'Open doctor profile >>',
            'read_more_btn' => 'Read more',
            'waiting' => 'We are waiting for you!',
            'your_data' => 'Your details',
            'phone_number' => 'Phone number',
            'latest_news' => 'Latest news',
            'our_partners' => 'Our partners',
            'where_we' => 'Where we are',
            'call_us' => 'Call us:',
            'best_clinic' => 'Best clinic by Komsomolskaya Pravda',
            'exp_label' => 'Experience',
        ],
    ];
}

function klinika_t($key)
{
    $lang = klinika_current_lang();
    $all  = klinika_strings();
    if (isset($all[$lang][$key])) {
        return $all[$lang][$key];
    }
    return $all['ru'][$key] ?? $key;
}

function klinika_e($key)
{
    echo esc_html(klinika_t($key));
}

function klinika_option($key, $default = '')
{
    $mods = get_theme_mod($key);
    if ($mods !== false && $mods !== '') {
        return $mods;
    }
    $opt = get_option($key);
    return ($opt !== false && $opt !== '') ? $opt : $default;
}

function klinika_option_i18n($key, $i18n_key)
{
    $value = klinika_option($key, '');
    return $value !== '' ? $value : klinika_t($i18n_key);
}

function klinika_asset($path)
{
    $path = (string) $path;
    if ($path !== '' && (strpos($path, 'http://') === 0 || strpos($path, 'https://') === 0 || strpos($path, '//') === 0)) {
        return $path;
    }
    $path = ltrim($path, '/');
    $file = get_template_directory() . '/' . $path;
    if (file_exists($file)) {
        return get_template_directory_uri() . '/' . $path;
    }
    return get_template_directory_uri() . '/assets/' . $path;
}

function klinika_img($file)
{
    return klinika_asset('assets/images/' . ltrim((string) $file, '/'));
}

function klinika_img_url($attachment_id, $fallback = '')
{
    $attachment_id = (int) $attachment_id;
    if ($attachment_id) {
        $url = wp_get_attachment_image_url($attachment_id, 'full');
        if ($url) {
            return $url;
        }
    }
    return $fallback ? klinika_asset($fallback) : '';
}

function klinika_page_url($slug)
{
    $page = get_page_by_path($slug);
    return $page ? get_permalink($page) : home_url('/' . trim($slug, '/') . '/');
}

function klinika_info_hub_url()
{
    return klinika_page_url('informaciya');
}

function klinika_uslugi_url()
{
    return klinika_page_url('uslugi');
}

function klinika_info_page_slugs()
{
    return ['informaciya', 'o-klinike', 'novosti', 'otzyvy', 'licenzii', 'vakansii', 'patsientam', 'ob-oplate', 'kontroliruyushchie-organy'];
}

function klinika_tw($key, $extra = '')
{
    $map = [
        'main'           => 'bg-white',
        'section'        => 'bg-white py-8 sm:py-10 lg:py-14',
        'section-crumbs' => 'bg-white pt-5 sm:pt-6',
        'crumbs-center'  => 'flex flex-wrap items-center justify-center text-[13px] sm:text-[14px] font-[Montserrat] text-[#9A9A9A] mb-4 sm:mb-6',
        'crumbs-flush'   => 'flex flex-wrap items-center text-[13px] sm:text-[14px] font-[Montserrat] text-[#9A9A9A]',
        'page-title'     => 'm-0 mb-6 sm:mb-8 text-center text-[24px] sm:text-[32px] lg:text-[36px] font-[Montserrat] font-bold text-[#1a1a1a] leading-[1.15]',
        'page-title-left'=> 'm-0 mb-6 sm:mb-8 text-left text-[24px] sm:text-[32px] lg:text-[36px] font-[Montserrat] font-bold text-[#1a1a1a] leading-[1.15]',
        'section-title'  => 'm-0 mb-6 sm:mb-8 text-center text-[26px] sm:text-[32px] lg:text-[36px] font-[Montserrat] font-bold text-[#1a1a1a] leading-[1.15]',
        'btn-green'      => 'inline-flex items-center justify-center px-8 py-3 min-w-[210px] bg-[#04AA29] hover:bg-[#039024] text-white font-[Montserrat] font-semibold text-[15px] no-underline transition-colors',
        'btn-green-sm'   => 'inline-flex items-center justify-center px-6 py-2.5 bg-[#04AA29] hover:bg-[#039024] text-white font-[Montserrat] font-semibold text-[14px] no-underline transition-colors',
        'muted'          => 'font-[Montserrat] text-[14px] sm:text-[15px] text-[#5C5C5C] leading-[1.7]',
    ];
    return trim(($map[$key] ?? '') . ' ' . $extra);
}

function klinika_get_posts_by_type($type, $count = -1)
{
    return get_posts([
        'post_type'      => $type,
        'post_status'    => 'publish',
        'posts_per_page' => $count,
        'orderby'        => ['menu_order' => 'ASC', 'date' => 'DESC'],
    ]);
}

function klinika_get_services()
{
    return klinika_get_posts_by_type('klinika_service');
}

function klinika_get_doctors()
{
    return klinika_get_posts_by_type('klinika_doctor');
}

function klinika_get_specialties()
{
    $terms = get_terms([
        'taxonomy'   => 'klinika_specialty',
        'hide_empty' => false,
    ]);
    return is_wp_error($terms) ? [] : $terms;
}

function klinika_doctor_meta($id, $key, $default = '')
{
    $value = get_post_meta($id, $key, true);
    return ($value !== '' && $value !== false) ? $value : $default;
}

function klinika_get_doctor_profile($id)
{
    return [
        'education'       => array_filter(array_map('trim', explode("\n", (string) get_post_meta($id, '_klinika_doctor_education', true)))),
        'qualifications'  => array_filter(array_map('trim', explode("\n", (string) get_post_meta($id, '_klinika_doctor_qualifications', true)))),
    ];
}

function klinika_booking_attrs_html($class = '', $doctor = '', $service = '')
{
    $attrs = 'href="#callback" data-booking-open';
    if ($doctor !== '') {
        $attrs .= ' data-booking-doctor="' . esc_attr($doctor) . '"';
    }
    if ($service !== '') {
        $attrs .= ' data-booking-service="' . esc_attr($service) . '"';
    }
    $attrs .= ' class="' . esc_attr($class) . '"';
    return $attrs;
}

function klinika_phone()
{
    return klinika_option('klinika_phone', '8 (391) 295-09-48');
}

function klinika_phone_href()
{
    return klinika_option('klinika_phone_href', '+73912950948');
}

function klinika_address()
{
    return klinika_option('klinika_address', 'г. Красноярск, ул. Чернышевского 75а');
}

function klinika_hours()
{
    return klinika_option('klinika_hours', '8.00 - 20.00');
}

function klinika_email()
{
    return klinika_option('klinika_email', 'detdoc24@yandex.ru');
}

function klinika_address_short()
{
    return klinika_option('klinika_address_short', 'ул. Чернышевского 75а');
}

function klinika_nav_items()
{
    return [
        ['slug' => 'home', 'label' => klinika_t('home'), 'url' => home_url('/')],
        ['slug' => 'ceny', 'label' => klinika_t('prices')],
        ['slug' => 'uslugi', 'label' => klinika_t('services'), 'children' => 'services'],
        ['slug' => 'specialisty', 'label' => klinika_t('specialists')],
        ['slug' => 'poisk', 'label' => 'Поиск'],
        ['slug' => 'kontakty', 'label' => klinika_t('contacts')],
        ['slug' => 'informaciya', 'label' => klinika_t('information'), 'children' => 'info'],
        ['slug' => 'otzyvy', 'label' => klinika_t('reviews')],
    ];
}

function klinika_info_nav_items()
{
    return [
        'o-klinike' => klinika_t('tab_about'),
        'novosti' => klinika_t('tab_news'),
        'otzyvy' => klinika_t('tab_reviews'),
        'licenzii' => klinika_t('tab_licenses'),
        'vakansii' => klinika_t('tab_vacancies'),
        'patsientam' => klinika_t('tab_patients'),
        'ob-oplate' => klinika_t('tab_payment'),
        'kontroliruyushchie-organy' => klinika_t('tab_authorities'),
    ];
}

function klinika_get_page_content($slug)
{
    $demo = [
        'o-klinike' => [
            'paragraphs' => [
                'Семейная клиника «Здоровые дети» — это современная педиатрическая помощь в Красноярске: осмотры, диагностика, анализы и программы наблюдения.',
                'Мы работаем бережно и понятно для родителей: объясняем назначения и сопровождаем ребёнка на каждом этапе лечения.',
            ],
            'list_items' => [
                'Опытные детские специалисты',
                'Собственная лаборатория',
                'Программы наблюдения и выезд на дом',
            ],
        ],
        'vakansii' => [
            'paragraphs' => [
                'Мы открыты к специалистам, которым важны дети, команда и развитие.',
                'Отправьте резюме на почту клиники — мы свяжемся с вами.',
            ],
        ],
        'patsientam' => [
            'paragraphs' => [
                'Возьмите паспорт родителя, свидетельство о рождении ребёнка и предыдущие медицинские документы.',
                'Запись возможна по телефону и через форму на сайте.',
            ],
        ],
        'ob-oplate' => [
            'paragraphs' => [
                'Оплатить приём можно наличными, картой и по программе ДМС партнёров клиники.',
            ],
        ],
        'kontroliruyushchie-organy' => [
            'paragraphs' => [
                'Клиника работает на основании действующей медицинской лицензии. Контакты контролирующих органов можно уточнить на ресепшене.',
            ],
        ],
    ];
    return $demo[$slug] ?? ['paragraphs' => [], 'list_items' => []];
}

function klinika_info_demo_paragraphs($slug)
{
    $data = klinika_get_page_content($slug);
    return $data['paragraphs'] ?? [];
}

function klinika_get_kontakty_page_data($page_id = 0)
{
    unset($page_id);
    return [
        'heading'          => klinika_t('contacts'),
        'address'          => klinika_address(),
        'hours'            => klinika_hours(),
        'phone'            => klinika_phone(),
        'phone_href'       => klinika_phone_href(),
        'email'            => klinika_email(),
        'directions_title' => klinika_t('how_to'),
        'directions'       => [
            'Остановка «Алексеева» — 3 минуты пешком.',
            'Бесплатная парковка у входа в клинику.',
        ],
        'btn_text'         => klinika_t('book'),
        'btn_url'          => '#callback',
        'map_url'          => klinika_img('map.png'),
        'photo_1_url'      => klinika_img('clinic-facade.png'),
        'photo_2_url'      => klinika_img('clinic-facade-full.png'),
    ];
}

function klinika_price_items($group = 'all')
{
    $posts = get_posts([
        'post_type'      => 'klinika_price',
        'posts_per_page' => 200,
        'post_status'    => 'publish',
        'orderby'        => ['menu_order' => 'ASC', 'title' => 'ASC'],
    ]);
    if (!empty($posts)) {
        $items = [];
        foreach ($posts as $post) {
            $items[] = [
                'id'    => $post->ID,
                'group' => get_post_meta($post->ID, '_klinika_price_group', true) ?: 'priem',
                'title' => get_the_title($post),
                'price' => get_post_meta($post->ID, '_klinika_price_value', true) ?: '—',
            ];
        }
    } else {
        $items = [
            ['group' => 'priem', 'title' => 'Приём педиатра первичный', 'price' => '2 200 ₽'],
            ['group' => 'priem', 'title' => 'Приём педиатра повторный', 'price' => '1 800 ₽'],
            ['group' => 'priem', 'title' => 'Приём невролога', 'price' => '2 500 ₽'],
            ['group' => 'priem', 'title' => 'Приём ЛОР-врача', 'price' => '2 400 ₽'],
            ['group' => 'analizy', 'title' => 'Общий анализ крови', 'price' => '650 ₽'],
            ['group' => 'analizy', 'title' => 'Общий анализ мочи', 'price' => '450 ₽'],
            ['group' => 'analizy', 'title' => 'Глюкоза в крови', 'price' => '280 ₽'],
            ['group' => 'analizy', 'title' => 'Биохимия крови (базовая)', 'price' => '1 900 ₽'],
            ['group' => 'analizy', 'title' => 'Мазок на флору', 'price' => '720 ₽'],
            ['group' => 'diag', 'title' => 'УЗИ брюшной полости', 'price' => '2 100 ₽'],
            ['group' => 'diag', 'title' => 'ЭКГ', 'price' => '900 ₽'],
            ['group' => 'diag', 'title' => 'УЗИ сердца', 'price' => '2 800 ₽'],
        ];
    }
    if ($group === 'all') {
        return $items;
    }
    return array_values(array_filter($items, static function ($item) use ($group) {
        return $item['group'] === $group;
    }));
}

function klinika_advantage_defaults()
{
    $posts = get_posts([
        'post_type'      => 'klinika_advantage',
        'posts_per_page' => 8,
        'post_status'    => 'publish',
        'orderby'        => ['menu_order' => 'ASC', 'date' => 'ASC'],
    ]);
    if (!empty($posts)) {
        $items = [];
        foreach ($posts as $post) {
            $items[] = [
                'title' => get_the_title($post),
                'text'  => wp_strip_all_tags($post->post_content) ?: get_the_excerpt($post),
                'image' => get_the_post_thumbnail_url($post, 'thumbnail') ?: klinika_img('icon-quality.png'),
            ];
        }
        return $items;
    }
    return [
        ['title' => 'Качество', 'text' => '80% наших врачей обладают высшей или 1-й категорией', 'image' => klinika_img('icon-quality.png')],
        ['title' => 'Анализы', 'text' => 'Вы можете сдавать любые анализы, даже самые редкие', 'image' => klinika_img('icon-lab.png')],
        ['title' => 'Программы', 'text' => 'Для вашего удобства мы разработали программы медицинского обслуживания', 'image' => klinika_img('icon-program.png')],
        ['title' => 'На дому', 'text' => 'Проводим лечение на дому, если состояние пациента не позволяет прийти в клинику', 'image' => klinika_img('icon-home.png')],
    ];
}

function klinika_demo_photo($key)
{
    $photos = [
        'hero'    => klinika_img('doctor-marina.png'),
        'video'   => klinika_img('video-cover.png'),
        'about'   => klinika_img('doctor-marina.png'),
        'service' => klinika_img('gallery-reception.png'),
        'clinic'  => klinika_img('clinic-facade.png'),
        'map'     => klinika_img('map.png'),
        'facade'  => klinika_img('clinic-facade-full.png'),
        'license' => klinika_img('certificate.png'),
    ];
    return $photos[$key] ?? $photos['clinic'];
}

function klinika_demo_gallery()
{
    return [
        klinika_img('clinic-facade.png'),
        klinika_img('gallery-vaccine.png'),
        klinika_img('gallery-reception.png'),
        klinika_img('clinic-facade-full.png'),
        klinika_img('video-cover.png'),
        klinika_img('doctor-elena.png'),
    ];
}

function klinika_partner_logos()
{
    $posts = get_posts([
        'post_type'      => 'klinika_partner',
        'posts_per_page' => 20,
        'post_status'    => 'publish',
        'orderby'        => ['menu_order' => 'ASC', 'date' => 'ASC'],
    ]);
    if (!empty($posts)) {
        $items = [];
        foreach ($posts as $post) {
            $url = get_the_post_thumbnail_url($post, 'medium');
            if ($url) {
                $items[] = $url;
            }
        }
        if ($items) {
            return $items;
        }
    }
    return [
        klinika_img('partner-alfa.png'),
        klinika_img('partner-sber.png'),
        klinika_img('partner-akson.png'),
        klinika_img('partner-elc.png'),
        klinika_img('partner-2gis.png'),
        klinika_img('partner-tinkoff.png'),
        klinika_img('partner-prodoctorov.png'),
    ];
}

function klinika_news_items($limit = 3)
{
    $posts = get_posts([
        'post_type'      => 'klinika_news',
        'posts_per_page' => $limit,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
    ]);
    $demo = [
        ['title' => 'Новое расписание невролога', 'date' => '18.03.2026', 'text' => 'Открыли запись к неврологу на выходные и обновили программу диспансерного наблюдения для детей до года.', 'url' => klinika_page_url('novosti')],
        ['title' => 'Лаборатория без очередей', 'date' => '02.03.2026', 'text' => 'Сдать анализы можно с 8:00 — результаты приходят на e-mail родителя.', 'url' => klinika_page_url('novosti')],
        ['title' => 'Вызов врача на дом', 'date' => '15.02.2026', 'text' => 'Педиатр приедет в день обращения при недомогании ребёнка. Запись по телефону и онлайн.', 'url' => klinika_page_url('novosti')],
    ];
    if (!empty($posts)) {
        $items = [];
        foreach ($posts as $i => $post) {
            $raw = wp_strip_all_tags($post->post_content ?: $post->post_excerpt);
            $is_placeholder = $raw === ''
                || preg_match('/lorem|on the other hand|denounce with righteous/i', $raw);
            if ($is_placeholder && isset($demo[$i])) {
                $items[] = array_merge($demo[$i], [
                    'date' => get_the_date('d.m.Y', $post) ?: $demo[$i]['date'],
                    'url'  => get_permalink($post) ?: $demo[$i]['url'],
                ]);
                continue;
            }
            $items[] = [
                'title' => get_the_title($post),
                'date'  => get_the_date('d.m.Y', $post),
                'text'  => wp_trim_words($raw, 36, '...'),
                'url'   => get_permalink($post) ?: klinika_page_url('novosti'),
            ];
        }
        return $items;
    }
    return array_slice($demo, 0, $limit);
}

function klinika_license_items()
{
    $posts = get_posts([
        'post_type'      => 'klinika_license',
        'posts_per_page' => 24,
        'post_status'    => 'publish',
        'orderby'        => ['menu_order' => 'ASC', 'date' => 'DESC'],
    ]);
    if (!empty($posts)) {
        $items = [];
        foreach ($posts as $post) {
            $url = get_the_post_thumbnail_url($post, 'large');
            if ($url) {
                $items[] = [
                    'title' => get_the_title($post),
                    'image' => $url,
                ];
            }
        }
        if ($items) {
            return $items;
        }
    }
    return [
        ['title' => 'Лицензия', 'image' => klinika_img('certificate.png')],
        ['title' => 'Лицензия', 'image' => klinika_img('certificate.png')],
        ['title' => 'Лицензия', 'image' => klinika_img('certificate.png')],
    ];
}

function klinika_demo_services()
{
    $text = 'Приём специалиста в семейной клинике «Здоровые дети» — внимательный осмотр, понятные рекомендации и бережный подход к каждому ребёнку. Мы проводим профилактику, диагностику и лечение с учётом возраста и особенностей пациента.';
    return [
        ['slug' => 'pediatr', 'title' => 'Педиатр', 'text' => $text],
        ['slug' => 'nevrolog', 'title' => 'Невролог', 'text' => $text],
        ['slug' => 'hirurg', 'title' => 'Хирург', 'text' => $text],
        ['slug' => 'lor', 'title' => 'ЛОР', 'text' => $text],
        ['slug' => 'oftalmolog', 'title' => 'Офтальмолог', 'text' => $text],
        ['slug' => 'kardiolog', 'title' => 'Кардиолог', 'text' => $text],
        ['slug' => 'uzi', 'title' => 'УЗИ', 'text' => $text],
    ];
}

function klinika_service_items()
{
    $posts = klinika_get_services();
    if (!empty($posts)) {
        $items = [];
        foreach ($posts as $post) {
            $items[] = [
                'slug'  => $post->post_name,
                'title' => get_the_title($post),
                'text'  => wp_strip_all_tags($post->post_content) ?: klinika_demo_services()[0]['text'],
                'url'   => add_query_arg('service', $post->post_name, klinika_uslugi_url()),
                'image' => get_the_post_thumbnail_url($post, 'large') ?: klinika_demo_photo('service'),
            ];
        }
        return $items;
    }
    $items = [];
    foreach (klinika_demo_services() as $row) {
        $row['url']   = add_query_arg('service', $row['slug'], klinika_uslugi_url());
        $row['image'] = klinika_demo_photo('service');
        $items[] = $row;
    }
    return $items;
}

function klinika_demo_doctors()
{
    $photos = [
        klinika_img('doctor-marina.png'),
        klinika_img('doctor-elena.png'),
        klinika_img('gallery-reception.png'),
        klinika_img('gallery-vaccine.png'),
    ];
    $names = [
        ['Марина Александровна', 'Педиатр', 15],
        ['Иван Сергеевич', 'Хирург', 10],
        ['Елена Викторовна', 'Невролог', 12],
        ['Ольга Николаевна', 'ЛОР-врач', 8],
    ];
    $items = [];
    foreach ($names as $i => $row) {
        $items[] = [
            'name'       => $row[0],
            'position'   => $row[1],
            'experience' => $row[2],
            'image'      => $photos[$i],
            'url'        => klinika_page_url('specialisty'),
        ];
    }
    return $items;
}

function klinika_doctor_cards($limit = 4)
{
    $posts = klinika_get_doctors();
    $demo  = klinika_demo_doctors();
    if (!empty($posts)) {
        $items = [];
        foreach (array_slice($posts, 0, $limit) as $i => $doctor) {
            $name = trim(get_the_title($doctor));
            $position = klinika_doctor_meta($doctor->ID, '_klinika_doctor_position', '');
            $is_placeholder = ($name === '' || preg_match('/имя|фамилия|должность|doctor\s*\d+/iu', $name . ' ' . $position));
            if ($is_placeholder && isset($demo[$i])) {
                $items[] = array_merge($demo[$i], [
                    'url' => get_permalink($doctor) ?: $demo[$i]['url'],
                    'image' => get_the_post_thumbnail_url($doctor, 'medium_large') ?: $demo[$i]['image'],
                ]);
                continue;
            }
            $items[] = [
                'name'       => $name,
                'position'   => $position !== '' ? $position : 'Специалист',
                'experience' => (int) klinika_doctor_meta($doctor->ID, '_klinika_doctor_experience', $demo[$i]['experience'] ?? 10),
                'image'      => get_the_post_thumbnail_url($doctor, 'medium_large') ?: ($demo[$i % count($demo)]['image'] ?? klinika_img('doctor-marina.png')),
                'url'        => get_permalink($doctor),
            ];
        }
        return $items;
    }
    return array_slice($demo, 0, $limit);
}

function klinika_demo_reviews()
{
    $text = 'Обратились в клинику по рекомендации. Врач внимательно осмотрел ребёнка, всё объяснил спокойно и понятно. Персонал доброжелательный, в клинике чисто и уютно. Обязательно придём ещё.';
    return [
        ['name' => 'Марина Уфимцева', 'source' => 'ProDoctorov', 'text' => $text],
        ['name' => 'Анна Смирнова', 'source' => '2ГИС', 'text' => $text],
        ['name' => 'Дмитрий Козлов', 'source' => 'Яндекс', 'text' => $text],
    ];
}

function klinika_review_items()
{
    $posts = klinika_get_posts_by_type('klinika_review');
    if (!empty($posts)) {
        $items = [];
        foreach ($posts as $post) {
            $items[] = [
                'name'   => get_the_title($post),
                'source' => get_post_meta($post->ID, '_klinika_review_source', true) ?: 'ProDoctorov',
                'text'   => wp_strip_all_tags($post->post_content),
            ];
        }
        return $items;
    }
    return klinika_demo_reviews();
}

function klinika_demo_faq()
{
    return [
        ['q' => 'Как записаться на приём?', 'a' => 'Запись возможна по телефону клиники или через форму на сайте. Администратор подберёт удобное время и специалиста.'],
        ['q' => 'Нужна ли подготовка к анализам?', 'a' => 'Для большинства исследований достаточно прийти утром натощак. Точные рекомендации подскажет администратор при записи.'],
        ['q' => 'Можно ли вызвать врача на дом?', 'a' => 'Да, в клинике доступен выезд педиатра на дом. Оставьте заявку — мы согласуем время визита.'],
    ];
}

function klinika_gallery_items()
{
    $posts = klinika_get_posts_by_type('klinika_gallery', 6);
    if (!empty($posts)) {
        $items = [];
        foreach ($posts as $post) {
            $url = get_the_post_thumbnail_url($post, 'large');
            if ($url) {
                $items[] = $url;
            }
        }
        if ($items) {
            return $items;
        }
    }
    return klinika_demo_gallery();
}

function klinika_handle_callback()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['klinika_callback'])) {
        return;
    }
    if (!isset($_POST['klinika_callback_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['klinika_callback_nonce'])), 'klinika_callback')) {
        return;
    }
    $name  = sanitize_text_field(wp_unslash($_POST['callback_name'] ?? ''));
    $phone = sanitize_text_field(wp_unslash($_POST['callback_phone'] ?? ''));
    if ($name === '' || $phone === '') {
        wp_safe_redirect(add_query_arg('callback', 'empty', wp_get_referer() ?: home_url('/')));
        exit;
    }
    $id = wp_insert_post([
        'post_type'   => 'klinika_booking',
        'post_status' => 'publish',
        'post_title'  => $name . ' — ' . $phone,
        'post_content'=> 'Заявка с формы обратного звонка',
    ]);
    if (!is_wp_error($id) && $id) {
        update_post_meta($id, '_klinika_booking_phone', $phone);
        update_post_meta($id, '_klinika_booking_status', 'new');
        if (function_exists('klinika_notify_new_booking')) {
            klinika_notify_new_booking($id, $name, $phone);
        } else {
            wp_mail(
                get_option('admin_email'),
                'Заявка с сайта клиники',
                "Имя: {$name}\nТелефон: {$phone}"
            );
        }
    } else {
        wp_mail(
            get_option('admin_email'),
            'Заявка с сайта клиники',
            "Имя: {$name}\nТелефон: {$phone}"
        );
    }
    wp_safe_redirect(add_query_arg('callback', 'ok', wp_get_referer() ?: home_url('/')));
    exit;
}
add_action('template_redirect', 'klinika_handle_callback');

function klinika_set_lang_cookie()
{
    if (empty($_GET['lang'])) {
        return;
    }
    $lang = sanitize_key(wp_unslash($_GET['lang']));
    if (in_array($lang, ['ru', 'en'], true)) {
        setcookie('klinika_lang', $lang, time() + YEAR_IN_SECONDS, COOKIEPATH ?: '/', COOKIE_DOMAIN ?: '');
        $_COOKIE['klinika_lang'] = $lang;
    }
}
add_action('init', 'klinika_set_lang_cookie');

function klinika_ensure_pages()
{
    $pages = [
        'uslugi' => 'Услуги',
        'analizy' => 'Анализы',
        'ceny' => 'Цены',
        'specialisty' => 'Специалисты',
        'galereya' => 'Галерея',
        'kontakty' => 'Контакты',
        'informaciya' => 'Информация',
        'o-klinike' => 'О клинике',
        'novosti' => 'Новости',
        'otzyvy' => 'Отзывы',
        'licenzii' => 'Лицензии',
        'vakansii' => 'Вакансии',
        'patsientam' => 'Пациентам',
        'ob-oplate' => 'Об оплате',
        'kontroliruyushchie-organy' => 'Контролирующие органы',
        'poisk' => 'Поиск',
        'moi-zapis' => 'Мои записи',
        'privacy-policy' => 'Политика конфиденциальности',
    ];
    foreach ($pages as $slug => $title) {
        if (!get_page_by_path($slug)) {
            wp_insert_post([
                'post_title'  => $title,
                'post_name'   => $slug,
                'post_status' => 'publish',
                'post_type'   => 'page',
            ]);
        }
    }
}
add_action('init', 'klinika_ensure_pages');
