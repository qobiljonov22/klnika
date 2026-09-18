<?php
/**
 * Client + admin convenience features (Tailwind front UI).
 *
 * @package Klinika
 */

if (!defined('ABSPATH')) {
    exit;
}

/** Extra settings */
function klinika_feature_settings_fields()
{
    return [
        'klinika_whatsapp'       => '',
        'klinika_telegram'       => '',
        'klinika_tg_bot_token'   => '',
        'klinika_tg_chat_id'     => '',
        'klinika_sms_webhook'    => '',
        'klinika_ga_id'          => '',
        'klinika_cookie_text'    => '',
    ];
}

function klinika_register_feature_settings()
{
    foreach (array_keys(klinika_feature_settings_fields()) as $field) {
        register_setting('klinika_settings', $field, [
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '',
        ]);
    }
}
add_action('admin_init', 'klinika_register_feature_settings');

function klinika_whatsapp_url()
{
    $raw = preg_replace('/\D+/', '', (string) klinika_option('klinika_whatsapp', klinika_phone_href()));
    return $raw ? 'https://wa.me/' . $raw : '';
}

function klinika_telegram_url()
{
    $u = ltrim((string) klinika_option('klinika_telegram', ''), '@');
    return $u !== '' ? 'https://t.me/' . rawurlencode($u) : '';
}

/** SEO schema */
function klinika_output_schema()
{
    $data = [
        '@context'    => 'https://schema.org',
        '@type'       => 'MedicalClinic',
        'name'        => get_bloginfo('name'),
        'url'         => home_url('/'),
        'telephone'   => klinika_phone(),
        'email'       => klinika_email(),
        'address'     => [
            '@type'           => 'PostalAddress',
            'streetAddress'   => klinika_address_short(),
            'addressLocality' => 'Красноярск',
            'addressCountry'  => 'RU',
        ],
        'openingHours'=> klinika_hours(),
        'image'       => klinika_img('logo.png'),
    ];
    if (is_singular('klinika_doctor')) {
        $id = get_the_ID();
        $data = [
            '@context' => 'https://schema.org',
            '@type'    => 'Physician',
            'name'     => get_the_title($id),
            'url'      => get_permalink($id),
            'image'    => get_the_post_thumbnail_url($id, 'large') ?: '',
            'jobTitle' => klinika_doctor_meta($id, '_klinika_doctor_position', ''),
            'worksFor' => [
                '@type' => 'MedicalClinic',
                'name'  => get_bloginfo('name'),
            ],
        ];
    }
    echo '<script type="application/ld+json">' . wp_json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "</script>\n";
}
add_action('wp_head', 'klinika_output_schema', 20);

/** Lazy-load images */
function klinika_lazy_images($content)
{
    if (is_admin() || $content === '') {
        return $content;
    }
    return preg_replace('/<img(?![^>]*loading=)/i', '<img loading="lazy"', $content);
}
add_filter('the_content', 'klinika_lazy_images', 20);
add_filter('post_thumbnail_html', 'klinika_lazy_images', 20);

/** Ensure feature pages */
function klinika_ensure_feature_pages()
{
    $pages = [
        'poisk'           => 'Поиск',
        'moi-zapis'       => 'Мои записи',
        'privacy-policy'  => 'Политика конфиденциальности',
    ];
    foreach ($pages as $slug => $title) {
        if (!get_page_by_path($slug)) {
            $id = wp_insert_post([
                'post_title'  => $title,
                'post_name'   => $slug,
                'post_status' => 'publish',
                'post_type'   => 'page',
            ]);
            if ($slug === 'privacy-policy' && !is_wp_error($id)) {
                wp_update_post([
                    'ID'           => $id,
                    'post_content' => "Настоящая Политика конфиденциальности описывает, какие персональные данные собирает сайт клиники «Здоровые дети», как они используются и хранятся.\n\nМы обрабатываем имя и телефон только для записи на приём и обратной связи. Данные не передаются третьим лицам без законного основания.\n\nПо вопросам: " . klinika_email(),
                ]);
            }
        }
    }
}
add_action('init', 'klinika_ensure_feature_pages', 30);

/** Editor role: news + reviews only */
function klinika_register_editor_role()
{
    if (get_role('klinika_editor')) {
        return;
    }
    add_role('klinika_editor', 'Редактор клиники', [
        'read'                   => true,
        'upload_files'           => true,
        'edit_posts'             => true,
        'edit_published_posts'   => true,
        'publish_posts'          => true,
        'delete_posts'           => true,
        'delete_published_posts' => true,
    ]);
}
add_action('after_switch_theme', 'klinika_register_editor_role');
add_action('init', 'klinika_register_editor_role');

/** Telegram notify */
function klinika_telegram_notify($text)
{
    $token = klinika_option('klinika_tg_bot_token', '');
    $chat  = klinika_option('klinika_tg_chat_id', '');
    if ($token === '' || $chat === '') {
        return false;
    }
    $url = 'https://api.telegram.org/bot' . rawurlencode($token) . '/sendMessage';
    wp_remote_post($url, [
        'timeout' => 8,
        'body'    => [
            'chat_id' => $chat,
            'text'    => $text,
        ],
    ]);
    return true;
}

function klinika_notify_new_booking($id, $name, $phone, $service = '', $doctor = '', $date = '', $time = '')
{
    $msg = "Новая заявка #{$id}\nИмя: {$name}\nТел: {$phone}\nУслуга: {$service}\nВрач: {$doctor}\nДата: {$date} {$time}";
    wp_mail(get_option('admin_email'), 'Новая заявка — клиника', $msg);
    klinika_telegram_notify($msg);
}

/** Reminder cron — day before appointment */
function klinika_schedule_reminders()
{
    if (!wp_next_scheduled('klinika_daily_reminders')) {
        wp_schedule_event(time() + HOUR_IN_SECONDS, 'daily', 'klinika_daily_reminders');
    }
}
add_action('after_switch_theme', 'klinika_schedule_reminders');
add_action('init', 'klinika_schedule_reminders');

function klinika_run_reminders()
{
    $tomorrow = gmdate('Y-m-d', time() + DAY_IN_SECONDS);
    $posts = get_posts([
        'post_type'      => 'klinika_booking',
        'posts_per_page' => 100,
        'post_status'    => 'publish',
        'meta_query'     => [
            [
                'key'   => '_klinika_booking_date',
                'value' => $tomorrow,
            ],
            [
                'key'   => '_klinika_booking_status',
                'value' => 'confirmed',
            ],
        ],
    ]);
    foreach ($posts as $post) {
        if (get_post_meta($post->ID, '_klinika_reminder_sent', true)) {
            continue;
        }
        $phone = get_post_meta($post->ID, '_klinika_booking_phone', true);
        $time  = get_post_meta($post->ID, '_klinika_booking_time', true);
        $text  = "Напоминание: завтра запись в клинику «Здоровые дети» на {$time}. Тел: " . klinika_phone();
        klinika_telegram_notify($text . "\nКлиент: {$phone}");
        wp_mail(get_option('admin_email'), 'Напоминание о записи', $text . "\n" . $phone);
        update_post_meta($post->ID, '_klinika_reminder_sent', 1);
    }
}
add_action('klinika_daily_reminders', 'klinika_run_reminders');

/** Analytics CTA */
function klinika_track_cta_rest(WP_REST_Request $request)
{
    $key = sanitize_key($request->get_param('cta') ?: 'unknown');
    $stats = get_option('klinika_cta_stats', []);
    if (!is_array($stats)) {
        $stats = [];
    }
    $day = gmdate('Y-m-d');
    if (!isset($stats[$day])) {
        $stats[$day] = [];
    }
    if (!isset($stats[$day][$key])) {
        $stats[$day][$key] = 0;
    }
    $stats[$day][$key]++;
    // keep 60 days
    $stats = array_slice($stats, -60, 60, true);
    update_option('klinika_cta_stats', $stats, false);
    return ['ok' => true];
}

function klinika_bookings_by_phone($phone)
{
    $phone = preg_replace('/\D+/', '', (string) $phone);
    if (strlen($phone) < 7) {
        return [];
    }
    $posts = get_posts([
        'post_type'      => 'klinika_booking',
        'posts_per_page' => 50,
        'post_status'    => 'publish',
    ]);
    $out = [];
    foreach ($posts as $post) {
        $p = preg_replace('/\D+/', '', (string) get_post_meta($post->ID, '_klinika_booking_phone', true));
        if ($p === '') {
            continue;
        }
        $match = ($p === $phone)
            || (strlen($phone) >= 7 && strpos($p, substr($phone, -7)) !== false)
            || (strlen($p) >= 7 && strpos($phone, substr($p, -7)) !== false);
        if (!$match) {
            continue;
        }
        $out[] = [
            'id'      => $post->ID,
            'title'   => get_the_title($post),
            'phone'   => get_post_meta($post->ID, '_klinika_booking_phone', true),
            'service' => get_post_meta($post->ID, '_klinika_booking_service', true),
            'doctor'  => get_post_meta($post->ID, '_klinika_booking_doctor', true),
            'date'    => get_post_meta($post->ID, '_klinika_booking_date', true),
            'time'    => get_post_meta($post->ID, '_klinika_booking_time', true),
            'status'  => get_post_meta($post->ID, '_klinika_booking_status', true) ?: 'new',
            'note'    => get_post_meta($post->ID, '_klinika_booking_note', true),
        ];
    }
    return $out;
}

function klinika_register_feature_rest()
{
    register_rest_route('klinika/v1', '/cta', [
        [
            'methods'             => WP_REST_Server::CREATABLE,
            'callback'            => 'klinika_track_cta_rest',
            'permission_callback' => '__return_true',
        ],
    ]);
    register_rest_route('klinika/v1', '/my-bookings', [
        [
            'methods'             => WP_REST_Server::CREATABLE,
            'callback'            => static function (WP_REST_Request $req) {
                $phone = sanitize_text_field($req->get_param('phone') ?: '');
                return klinika_bookings_by_phone($phone);
            },
            'permission_callback' => '__return_true',
        ],
    ]);
}
add_action('rest_api_init', 'klinika_register_feature_rest');

/** Front chrome: sticky + cookie + a11y */
function klinika_render_client_chrome()
{
    $wa = klinika_whatsapp_url();
    $tg = klinika_telegram_url();
    $cookie = klinika_option('klinika_cookie_text', 'Мы используем cookie для удобства сайта. Продолжая, вы соглашаетесь с политикой конфиденциальности.');
    ?>
    <div class="fixed right-3 bottom-24 z-[70] flex flex-col gap-2.5 lg:hidden" data-sticky-contacts>
        <a href="tel:<?php echo esc_attr(klinika_phone_href()); ?>" data-cta="sticky_call" class="inline-flex items-center gap-2 min-h-[48px] rounded-full bg-[#04AA29] !text-white shadow-[0_8px_24px_rgba(4,170,41,0.35)] no-underline font-[Montserrat] text-[12px] font-semibold pl-3.5 pr-4" aria-label="Позвонить">
            <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1.1-.2 1.2.4 2.5.6 3.8.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1C10.6 21 3 13.4 3 4c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.6.6 3.8.1.4 0 .8-.3 1.1l-2.2 2.2z"/></svg>
            Позвонить
        </a>
        <?php if ($wa) : ?>
            <a href="<?php echo esc_url($wa); ?>" target="_blank" rel="noopener" data-cta="sticky_whatsapp" class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-[#25D366] !text-white shadow-[0_8px_24px_rgba(37,211,102,0.35)] no-underline" aria-label="WhatsApp">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20.5 3.5A11 11 0 004.1 17.5L3 21l3.6-1A11 11 0 0020.5 3.5zm-8.5 17a9 9 0 01-4.6-1.3l-.3-.2-2.7.7.7-2.6-.2-.3A9 9 0 1112 20.5zm5-6.7c-.3-.1-1.6-.8-1.8-.9s-.4-.1-.6.1-.7.9-.8 1-.3.2-.6.1a7.4 7.4 0 01-2.2-1.4 8.2 8.2 0 01-1.5-1.9c-.2-.3 0-.4.1-.6l.4-.5c.1-.2.1-.3 0-.5l-.8-1.9c-.2-.5-.4-.4-.6-.4h-.5c-.2 0-.5.1-.7.3s-1 1-1 2.4 1 2.8 1.2 3 .2.3 2 3.1 2.6 2.4 3.1 2.6a7 7 0 002.1.6c.3 0 .9 0 1.2-.2s1.6-1.1 1.8-2.1.2-.8.1-.9-.2-.2-.5-.3z"/></svg>
            </a>
        <?php endif; ?>
        <?php if ($tg) : ?>
            <a href="<?php echo esc_url($tg); ?>" target="_blank" rel="noopener" data-cta="sticky_telegram" class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-[#2AABEE] !text-white shadow-[0_8px_24px_rgba(42,171,238,0.35)] no-underline" aria-label="Telegram">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M9.04 15.3l-.37 5.2c.53 0 .76-.23 1.04-.5l2.5-2.4 5.18 3.8c.95.52 1.63.25 1.89-.88l3.42-16.06h.01c.3-1.41-.51-1.96-1.44-1.62L2.3 9.7C.93 10.23.95 11 2.12 11.34l4.6 1.44L17.4 6.2c.54-.36 1.03-.16.63.2"/></svg>
            </a>
        <?php endif; ?>
    </div>

    <div class="fixed left-3 bottom-3 z-[70] hidden sm:flex flex-col gap-1 bg-white/95 border border-[#E8F4FB] rounded-lg p-1.5 shadow-md" data-a11y-toolbar>
        <button type="button" class="border-0 bg-[#ECF9FF] text-[#009BE3] font-[Montserrat] text-[12px] px-2.5 py-1.5 cursor-pointer rounded hover:bg-[#009BE3] hover:text-white transition-colors" data-a11y-font="up" title="Крупнее">A+</button>
        <button type="button" class="border-0 bg-[#ECF9FF] text-[#009BE3] font-[Montserrat] text-[12px] px-2.5 py-1.5 cursor-pointer rounded hover:bg-[#009BE3] hover:text-white transition-colors" data-a11y-font="down" title="Мельче">A−</button>
        <button type="button" class="border-0 bg-[#1a1a1a] text-white font-[Montserrat] text-[12px] px-2.5 py-1.5 cursor-pointer rounded" data-a11y-contrast title="Контраст">◐</button>
    </div>

    <div class="fixed inset-x-0 bottom-0 z-[80] hidden bg-[#1a1a1a]/95 backdrop-blur-sm text-white px-4 py-3.5 sm:px-6" data-cookie-banner>
        <div class="klinika-container flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-6">
            <p class="m-0 flex-1 font-[Montserrat] text-[13px] sm:text-[14px] leading-snug"><?php echo esc_html($cookie); ?>
                <a href="<?php echo esc_url(klinika_page_url('privacy-policy')); ?>" class="underline !text-white ml-1">Подробнее</a>
            </p>
            <button type="button" class="klinika-booking-btn shrink-0 !px-5 !py-2 !text-[14px]" data-cookie-accept>Принять</button>
        </div>
    </div>
    <?php
}
add_action('wp_footer', 'klinika_render_client_chrome', 5);

function klinika_output_seo_meta()
{
    if (is_admin()) {
        return;
    }
    $title = wp_get_document_title();
    $desc  = 'Семейная клиника «Здоровые дети» в Красноярске: педиатрия, анализы, диагностика, вызов врача на дом. Запись онлайн.';
    if (is_singular()) {
        $excerpt = get_the_excerpt();
        if ($excerpt) {
            $desc = wp_strip_all_tags($excerpt);
        }
    }
    $url   = is_singular() ? get_permalink() : home_url('/');
    $image = is_singular() && has_post_thumbnail() ? get_the_post_thumbnail_url(null, 'large') : klinika_img('logo.png');
    echo '<meta name="description" content="' . esc_attr(wp_trim_words($desc, 28, '')) . '">' . "\n";
    echo '<meta property="og:type" content="website">' . "\n";
    echo '<meta property="og:title" content="' . esc_attr($title) . '">' . "\n";
    echo '<meta property="og:description" content="' . esc_attr(wp_trim_words($desc, 28, '')) . '">' . "\n";
    echo '<meta property="og:url" content="' . esc_url($url) . '">' . "\n";
    echo '<meta property="og:image" content="' . esc_url($image) . '">' . "\n";
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
}
add_action('wp_head', 'klinika_output_seo_meta', 5);

function klinika_output_ga()
{
    $id = klinika_option('klinika_ga_id', '');
    if ($id === '') {
        return;
    }
    $id = esc_js($id);
    echo "<!-- GA --><script async src=\"https://www.googletagmanager.com/gtag/js?id={$id}\"></script><script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','{$id}');</script>\n";
}
add_action('wp_head', 'klinika_output_ga', 30);
