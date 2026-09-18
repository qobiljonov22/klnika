<?php
/**
 * Admin: settings page + meta boxes for CPT CRUD.
 *
 * @package Klinika
 */

if (!defined('ABSPATH')) {
    exit;
}

/** Contact / site settings */
function klinika_register_settings()
{
    $fields = [
        'klinika_phone',
        'klinika_phone_href',
        'klinika_address',
        'klinika_address_short',
        'klinika_hours',
        'klinika_email',
        'klinika_video_url',
        'klinika_map_embed',
        'klinika_home_copyright',
        'klinika_home_privacy_url',
    ];
    foreach ($fields as $field) {
        register_setting('klinika_settings', $field, [
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'show_in_rest'      => true,
            'default'           => '',
        ]);
    }
}
add_action('admin_init', 'klinika_register_settings');

function klinika_add_settings_menu()
{
    add_options_page(
        'Клиника — настройки',
        'Клиника',
        'manage_options',
        'klinika-settings',
        'klinika_render_settings_page'
    );
}
add_action('admin_menu', 'klinika_add_settings_menu');

function klinika_render_settings_page()
{
    if (!current_user_can('manage_options')) {
        return;
    }
    $fields = [
        'klinika_phone'            => 'Телефон (отображение)',
        'klinika_phone_href'       => 'Телефон (tel: ссылка)',
        'klinika_address'          => 'Адрес полный',
        'klinika_address_short'    => 'Адрес короткий',
        'klinika_hours'            => 'Режим работы',
        'klinika_email'            => 'E-mail',
        'klinika_video_url'        => 'YouTube / видео URL',
        'klinika_map_embed'        => 'Яндекс.Карта embed URL',
        'klinika_home_copyright'   => 'Copyright',
        'klinika_home_privacy_url' => 'URL политики конфиденциальности',
        'klinika_whatsapp'         => 'WhatsApp (цифры с кодом страны)',
        'klinika_telegram'         => 'Telegram username (@…)',
        'klinika_tg_bot_token'     => 'Telegram bot token',
        'klinika_tg_chat_id'       => 'Telegram chat ID (уведомления)',
        'klinika_sms_webhook'      => 'SMS webhook URL (опционально)',
        'klinika_ga_id'            => 'Google Analytics ID',
        'klinika_cookie_text'      => 'Текст cookie-баннера',
    ];
    ?>
    <div class="wrap">
        <h1>Клиника — настройки сайта</h1>
        <form method="post" action="options.php">
            <?php settings_fields('klinika_settings'); ?>
            <table class="form-table" role="presentation">
                <?php foreach ($fields as $key => $label) : ?>
                    <tr>
                        <th scope="row"><label for="<?php echo esc_attr($key); ?>"><?php echo esc_html($label); ?></label></th>
                        <td>
                            <input type="text" class="regular-text" id="<?php echo esc_attr($key); ?>" name="<?php echo esc_attr($key); ?>" value="<?php echo esc_attr(get_option($key, '')); ?>">
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <?php submit_button(); ?>
        </form>
        <p>Контент: меню «Клиника» (dashboard) и CPT слева. Роль «Редактор клиники» — новости/отзывы. REST: <code>/wp-json/klinika/v1/</code></p>
    </div>
    <?php
}

/** Meta boxes */
function klinika_add_meta_boxes()
{
    add_meta_box('klinika_doctor_meta', 'Данные врача', 'klinika_render_doctor_meta', 'klinika_doctor', 'normal', 'high');
    add_meta_box('klinika_review_meta', 'Источник отзыва', 'klinika_render_review_meta', 'klinika_review', 'side', 'default');
    add_meta_box('klinika_gallery_meta', 'Тип медиа', 'klinika_render_gallery_meta', 'klinika_gallery', 'side', 'default');
    add_meta_box('klinika_price_meta', 'Цена', 'klinika_render_price_meta', 'klinika_price', 'normal', 'high');
    add_meta_box('klinika_slide_meta', 'Слайд', 'klinika_render_slide_meta', 'klinika_slide', 'normal', 'high');
    add_meta_box('klinika_booking_meta', 'Заявка', 'klinika_render_booking_meta', 'klinika_booking', 'normal', 'high');
}
add_action('add_meta_boxes', 'klinika_add_meta_boxes');

function klinika_render_doctor_meta($post)
{
    wp_nonce_field('klinika_save_meta', 'klinika_meta_nonce');
    $position = get_post_meta($post->ID, '_klinika_doctor_position', true);
    $experience = get_post_meta($post->ID, '_klinika_doctor_experience', true);
    $education = get_post_meta($post->ID, '_klinika_doctor_education', true);
    $qualifications = get_post_meta($post->ID, '_klinika_doctor_qualifications', true);
    $schedule = get_post_meta($post->ID, '_klinika_doctor_schedule', true);
    $home = get_post_meta($post->ID, '_klinika_doctor_home', true);
    $has_thumb = has_post_thumbnail($post->ID);
    ?>
    <div style="background:#f0f6fc;border-left:4px solid #009BE3;padding:10px 14px;margin-bottom:14px;">
        <strong>Wizard «добавить врача»</strong>
        <ol style="margin:8px 0 0;padding-left:18px;">
            <li><?php echo $has_thumb ? '✓' : '○'; ?> Миниатюра (фото)</li>
            <li><?php echo $position !== '' ? '✓' : '○'; ?> Должность / специальность</li>
            <li><?php echo $experience !== '' ? '✓' : '○'; ?> Стаж</li>
            <li><?php echo $schedule !== '' ? '✓' : '○'; ?> Расписание</li>
        </ol>
    </div>
    <p><label>Должность<br><input type="text" class="widefat" name="klinika_doctor_position" value="<?php echo esc_attr($position); ?>"></label></p>
    <p><label>Стаж (лет)<br><input type="number" class="small-text" name="klinika_doctor_experience" value="<?php echo esc_attr($experience); ?>" min="0"></label></p>
    <p><label>Расписание (по строке: Пн 09:00–14:00)<br><textarea class="widefat" rows="4" name="klinika_doctor_schedule"><?php echo esc_textarea($schedule); ?></textarea></label></p>
    <p><label><input type="checkbox" name="klinika_doctor_home" value="1" <?php checked($home, '1'); ?>> Выезд на дом</label></p>
    <p><label>Образование (по строке)<br><textarea class="widefat" rows="4" name="klinika_doctor_education"><?php echo esc_textarea($education); ?></textarea></label></p>
    <p><label>Квалификация (по строке)<br><textarea class="widefat" rows="4" name="klinika_doctor_qualifications"><?php echo esc_textarea($qualifications); ?></textarea></label></p>
    <?php
}

function klinika_render_booking_meta($post)
{
    wp_nonce_field('klinika_save_meta', 'klinika_meta_nonce');
    $phone = get_post_meta($post->ID, '_klinika_booking_phone', true);
    $service = get_post_meta($post->ID, '_klinika_booking_service', true);
    $doctor = get_post_meta($post->ID, '_klinika_booking_doctor', true);
    $date = get_post_meta($post->ID, '_klinika_booking_date', true);
    $time = get_post_meta($post->ID, '_klinika_booking_time', true);
    $status = get_post_meta($post->ID, '_klinika_booking_status', true) ?: 'new';
    $note = get_post_meta($post->ID, '_klinika_booking_note', true);
    ?>
    <p><label>Телефон<br><input type="text" class="widefat" name="klinika_booking_phone" value="<?php echo esc_attr($phone); ?>"></label></p>
    <p><label>Услуга<br><input type="text" class="widefat" name="klinika_booking_service" value="<?php echo esc_attr($service); ?>"></label></p>
    <p><label>Врач<br><input type="text" class="widefat" name="klinika_booking_doctor" value="<?php echo esc_attr($doctor); ?>"></label></p>
    <p><label>Дата<br><input type="text" class="widefat" name="klinika_booking_date" value="<?php echo esc_attr($date); ?>"></label></p>
    <p><label>Время<br><input type="text" class="widefat" name="klinika_booking_time" value="<?php echo esc_attr($time); ?>"></label></p>
    <p><label>Статус<br>
        <select name="klinika_booking_status">
            <option value="new" <?php selected($status, 'new'); ?>>новая</option>
            <option value="confirmed" <?php selected($status, 'confirmed'); ?>>подтверждена</option>
            <option value="cancelled" <?php selected($status, 'cancelled'); ?>>отменена</option>
        </select>
    </label></p>
    <p><label>Комментарий<br><textarea class="widefat" rows="3" name="klinika_booking_note"><?php echo esc_textarea($note); ?></textarea></label></p>
    <?php
}

function klinika_render_review_meta($post)
{
    wp_nonce_field('klinika_save_meta', 'klinika_meta_nonce');
    $source = get_post_meta($post->ID, '_klinika_review_source', true);
    echo '<p><label>Источник<br><input type="text" class="widefat" name="klinika_review_source" value="' . esc_attr($source) . '" placeholder="ProDoctorov"></label></p>';
}

function klinika_render_gallery_meta($post)
{
    wp_nonce_field('klinika_save_meta', 'klinika_meta_nonce');
    $type = get_post_meta($post->ID, '_klinika_gallery_type', true) ?: 'photo';
    ?>
    <p>
        <label><input type="radio" name="klinika_gallery_type" value="photo" <?php checked($type, 'photo'); ?>> Фото</label><br>
        <label><input type="radio" name="klinika_gallery_type" value="video" <?php checked($type, 'video'); ?>> Видео</label>
    </p>
    <p><label>Video URL<br><input type="url" class="widefat" name="klinika_gallery_video_url" value="<?php echo esc_attr(get_post_meta($post->ID, '_klinika_gallery_video_url', true)); ?>"></label></p>
    <?php
}

function klinika_render_price_meta($post)
{
    wp_nonce_field('klinika_save_meta', 'klinika_meta_nonce');
    $price = get_post_meta($post->ID, '_klinika_price_value', true);
    $group = get_post_meta($post->ID, '_klinika_price_group', true) ?: 'priem';
    ?>
    <p><label>Цена (текст)<br><input type="text" class="widefat" name="klinika_price_value" value="<?php echo esc_attr($price); ?>" placeholder="2 200 ₽"></label></p>
    <p><label>Группа<br>
        <select name="klinika_price_group">
            <option value="priem" <?php selected($group, 'priem'); ?>>Приёмы</option>
            <option value="analizy" <?php selected($group, 'analizy'); ?>>Анализы</option>
            <option value="diag" <?php selected($group, 'diag'); ?>>Диагностика</option>
        </select>
    </label></p>
    <?php
}

function klinika_render_slide_meta($post)
{
    wp_nonce_field('klinika_save_meta', 'klinika_meta_nonce');
    $subtitle = get_post_meta($post->ID, '_klinika_slide_subtitle', true);
    $btn = get_post_meta($post->ID, '_klinika_slide_btn', true);
    $url = get_post_meta($post->ID, '_klinika_slide_url', true);
    ?>
    <p><label>Подзаголовок<br><textarea class="widefat" rows="3" name="klinika_slide_subtitle"><?php echo esc_textarea($subtitle); ?></textarea></label></p>
    <p><label>Текст кнопки<br><input type="text" class="widefat" name="klinika_slide_btn" value="<?php echo esc_attr($btn); ?>"></label></p>
    <p><label>Ссылка кнопки<br><input type="url" class="widefat" name="klinika_slide_url" value="<?php echo esc_attr($url); ?>"></label></p>
    <?php
}

function klinika_save_meta($post_id)
{
    if (!isset($_POST['klinika_meta_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['klinika_meta_nonce'])), 'klinika_save_meta')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $map = [
        'klinika_doctor_position'       => '_klinika_doctor_position',
        'klinika_doctor_experience'     => '_klinika_doctor_experience',
        'klinika_doctor_education'      => '_klinika_doctor_education',
        'klinika_doctor_qualifications' => '_klinika_doctor_qualifications',
        'klinika_doctor_schedule'       => '_klinika_doctor_schedule',
        'klinika_review_source'         => '_klinika_review_source',
        'klinika_gallery_type'          => '_klinika_gallery_type',
        'klinika_gallery_video_url'     => '_klinika_gallery_video_url',
        'klinika_price_value'           => '_klinika_price_value',
        'klinika_price_group'           => '_klinika_price_group',
        'klinika_slide_subtitle'        => '_klinika_slide_subtitle',
        'klinika_slide_btn'             => '_klinika_slide_btn',
        'klinika_slide_url'             => '_klinika_slide_url',
        'klinika_booking_phone'         => '_klinika_booking_phone',
        'klinika_booking_service'       => '_klinika_booking_service',
        'klinika_booking_doctor'        => '_klinika_booking_doctor',
        'klinika_booking_date'          => '_klinika_booking_date',
        'klinika_booking_time'          => '_klinika_booking_time',
        'klinika_booking_status'        => '_klinika_booking_status',
        'klinika_booking_note'          => '_klinika_booking_note',
    ];

    foreach ($map as $field => $meta_key) {
        if (!isset($_POST[$field])) {
            continue;
        }
        $value = wp_unslash($_POST[$field]);
        if (strpos($field, 'url') !== false) {
            $value = esc_url_raw($value);
        } elseif (is_string($value) && (
            strpos($field, 'education') !== false
            || strpos($field, 'qualifications') !== false
            || strpos($field, 'subtitle') !== false
            || strpos($field, 'schedule') !== false
            || strpos($field, 'note') !== false
        )) {
            $value = sanitize_textarea_field($value);
        } else {
            $value = sanitize_text_field($value);
        }
        update_post_meta($post_id, $meta_key, $value);
    }

    if (get_post_type($post_id) === 'klinika_doctor') {
        update_post_meta($post_id, '_klinika_doctor_home', isset($_POST['klinika_doctor_home']) ? '1' : '0');
    }
}
add_action('save_post', 'klinika_save_meta');

/** Register post meta for REST */
function klinika_register_post_meta()
{
    $metas = [
        'klinika_doctor' => [
            '_klinika_doctor_position'       => 'string',
            '_klinika_doctor_experience'     => 'integer',
            '_klinika_doctor_education'      => 'string',
            '_klinika_doctor_qualifications' => 'string',
            '_klinika_doctor_schedule'       => 'string',
            '_klinika_doctor_home'           => 'string',
        ],
        'klinika_review' => [
            '_klinika_review_source' => 'string',
        ],
        'klinika_gallery' => [
            '_klinika_gallery_type'      => 'string',
            '_klinika_gallery_video_url' => 'string',
        ],
        'klinika_price' => [
            '_klinika_price_value' => 'string',
            '_klinika_price_group' => 'string',
        ],
        'klinika_slide' => [
            '_klinika_slide_subtitle' => 'string',
            '_klinika_slide_btn'      => 'string',
            '_klinika_slide_url'      => 'string',
        ],
        'klinika_booking' => [
            '_klinika_booking_phone'   => 'string',
            '_klinika_booking_service' => 'string',
            '_klinika_booking_doctor'  => 'string',
            '_klinika_booking_date'    => 'string',
            '_klinika_booking_time'    => 'string',
            '_klinika_booking_status'  => 'string',
            '_klinika_booking_note'    => 'string',
        ],
    ];

    foreach ($metas as $type => $fields) {
        foreach ($fields as $key => $type_name) {
            register_post_meta($type, $key, [
                'type'              => $type_name,
                'single'            => true,
                'show_in_rest'      => true,
                'auth_callback'     => static function () {
                    return current_user_can('edit_posts');
                },
            ]);
        }
    }
}
add_action('init', 'klinika_register_post_meta');
