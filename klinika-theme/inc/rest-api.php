<?php
/**
 * REST API: Klinika CRUD endpoints.
 *
 * @package Klinika
 */

if (!defined('ABSPATH')) {
    exit;
}

function klinika_rest_permission_read()
{
    return true;
}

function klinika_rest_permission_write()
{
    return current_user_can('edit_posts');
}

function klinika_register_rest_routes()
{
    $ns = 'klinika/v1';

    register_rest_route($ns, '/settings', [
        [
            'methods'             => WP_REST_Server::READABLE,
            'callback'            => 'klinika_rest_get_settings',
            'permission_callback' => 'klinika_rest_permission_read',
        ],
        [
            'methods'             => WP_REST_Server::EDITABLE,
            'callback'            => 'klinika_rest_update_settings',
            'permission_callback' => 'klinika_rest_permission_write',
        ],
    ]);

    register_rest_route($ns, '/prices', [
        [
            'methods'             => WP_REST_Server::READABLE,
            'callback'            => 'klinika_rest_get_prices',
            'permission_callback' => 'klinika_rest_permission_read',
        ],
        [
            'methods'             => WP_REST_Server::CREATABLE,
            'callback'            => 'klinika_rest_create_price',
            'permission_callback' => 'klinika_rest_permission_write',
        ],
    ]);

    register_rest_route($ns, '/prices/(?P<id>\d+)', [
        [
            'methods'             => WP_REST_Server::READABLE,
            'callback'            => 'klinika_rest_get_price',
            'permission_callback' => 'klinika_rest_permission_read',
        ],
        [
            'methods'             => WP_REST_Server::EDITABLE,
            'callback'            => 'klinika_rest_update_price',
            'permission_callback' => 'klinika_rest_permission_write',
        ],
        [
            'methods'             => WP_REST_Server::DELETABLE,
            'callback'            => 'klinika_rest_delete_price',
            'permission_callback' => 'klinika_rest_permission_write',
        ],
    ]);

    register_rest_route($ns, '/booking', [
        [
            'methods'             => WP_REST_Server::CREATABLE,
            'callback'            => 'klinika_rest_create_booking',
            'permission_callback' => '__return_true',
        ],
        [
            'methods'             => WP_REST_Server::READABLE,
            'callback'            => 'klinika_rest_list_bookings',
            'permission_callback' => 'klinika_rest_permission_write',
        ],
    ]);

    register_rest_route($ns, '/booking/(?P<id>\d+)', [
        [
            'methods'             => WP_REST_Server::EDITABLE,
            'callback'            => 'klinika_rest_update_booking',
            'permission_callback' => 'klinika_rest_permission_write',
        ],
        [
            'methods'             => WP_REST_Server::DELETABLE,
            'callback'            => 'klinika_rest_delete_booking',
            'permission_callback' => 'klinika_rest_permission_write',
        ],
    ]);

    register_rest_route($ns, '/catalog', [
        [
            'methods'             => WP_REST_Server::READABLE,
            'callback'            => 'klinika_rest_catalog',
            'permission_callback' => 'klinika_rest_permission_read',
        ],
    ]);
}
add_action('rest_api_init', 'klinika_register_rest_routes');

function klinika_rest_get_settings()
{
    return [
        'phone'         => klinika_phone(),
        'phone_href'    => klinika_phone_href(),
        'address'       => klinika_address(),
        'address_short' => klinika_address_short(),
        'hours'         => klinika_hours(),
        'email'         => klinika_email(),
        'video_url'     => get_option('klinika_video_url', 'https://www.youtube.com/'),
        'map_embed'     => get_option('klinika_map_embed', ''),
    ];
}

function klinika_rest_update_settings(WP_REST_Request $request)
{
    $map = [
        'phone'         => 'klinika_phone',
        'phone_href'    => 'klinika_phone_href',
        'address'       => 'klinika_address',
        'address_short' => 'klinika_address_short',
        'hours'         => 'klinika_hours',
        'email'         => 'klinika_email',
        'video_url'     => 'klinika_video_url',
        'map_embed'     => 'klinika_map_embed',
    ];
    foreach ($map as $json => $option) {
        if ($request->offsetExists($json)) {
            update_option($option, sanitize_text_field($request->get_param($json)));
        }
    }
    return klinika_rest_get_settings();
}

function klinika_rest_price_item($post)
{
    return [
        'id'    => $post->ID,
        'title' => get_the_title($post),
        'price' => get_post_meta($post->ID, '_klinika_price_value', true),
        'group' => get_post_meta($post->ID, '_klinika_price_group', true) ?: 'priem',
    ];
}

function klinika_rest_get_prices(WP_REST_Request $request)
{
    $group = sanitize_key($request->get_param('group') ?: 'all');
    return klinika_price_items($group);
}

function klinika_rest_create_price(WP_REST_Request $request)
{
    $title = sanitize_text_field($request->get_param('title') ?: '');
    $price = sanitize_text_field($request->get_param('price') ?: '');
    $group = sanitize_key($request->get_param('group') ?: 'priem');
    if ($title === '') {
        return new WP_Error('invalid', 'Title required', ['status' => 400]);
    }
    $id = wp_insert_post([
        'post_type'   => 'klinika_price',
        'post_status' => 'publish',
        'post_title'  => $title,
    ], true);
    if (is_wp_error($id)) {
        return $id;
    }
    update_post_meta($id, '_klinika_price_value', $price);
    update_post_meta($id, '_klinika_price_group', $group);
    return klinika_rest_price_item(get_post($id));
}

function klinika_rest_get_price(WP_REST_Request $request)
{
    $post = get_post((int) $request['id']);
    if (!$post || $post->post_type !== 'klinika_price') {
        return new WP_Error('not_found', 'Price not found', ['status' => 404]);
    }
    return klinika_rest_price_item($post);
}

function klinika_rest_update_price(WP_REST_Request $request)
{
    $id = (int) $request['id'];
    $post = get_post($id);
    if (!$post || $post->post_type !== 'klinika_price') {
        return new WP_Error('not_found', 'Price not found', ['status' => 404]);
    }
    $update = ['ID' => $id];
    if ($request->offsetExists('title')) {
        $update['post_title'] = sanitize_text_field($request->get_param('title'));
    }
    wp_update_post($update);
    if ($request->offsetExists('price')) {
        update_post_meta($id, '_klinika_price_value', sanitize_text_field($request->get_param('price')));
    }
    if ($request->offsetExists('group')) {
        update_post_meta($id, '_klinika_price_group', sanitize_key($request->get_param('group')));
    }
    return klinika_rest_price_item(get_post($id));
}

function klinika_rest_delete_price(WP_REST_Request $request)
{
    $id = (int) $request['id'];
    $post = get_post($id);
    if (!$post || $post->post_type !== 'klinika_price') {
        return new WP_Error('not_found', 'Price not found', ['status' => 404]);
    }
    wp_trash_post($id);
    return ['deleted' => true, 'id' => $id];
}

function klinika_rest_create_booking(WP_REST_Request $request)
{
    $name = sanitize_text_field($request->get_param('name') ?: '');
    $phone = sanitize_text_field($request->get_param('phone') ?: '');
    $service = sanitize_text_field($request->get_param('service') ?: '');
    $doctor = sanitize_text_field($request->get_param('doctor') ?: '');
    $date = sanitize_text_field($request->get_param('date') ?: '');
    $time = sanitize_text_field($request->get_param('time') ?: '');

    if ($name === '' || $phone === '') {
        return new WP_Error('invalid', 'Name and phone required', ['status' => 400]);
    }

    $id = wp_insert_post([
        'post_type'   => 'klinika_booking',
        'post_status' => 'publish',
        'post_title'  => $name . ' — ' . $phone,
        'post_content'=> sprintf("Услуга: %s\nВрач: %s\nДата: %s\nВремя: %s", $service, $doctor, $date, $time),
    ], true);

    if (is_wp_error($id)) {
        return $id;
    }

    update_post_meta($id, '_klinika_booking_phone', $phone);
    update_post_meta($id, '_klinika_booking_service', $service);
    update_post_meta($id, '_klinika_booking_doctor', $doctor);
    update_post_meta($id, '_klinika_booking_date', $date);
    update_post_meta($id, '_klinika_booking_time', $time);
    update_post_meta($id, '_klinika_booking_status', 'new');

    if (function_exists('klinika_notify_new_booking')) {
        klinika_notify_new_booking($id, $name, $phone, $service, $doctor, $date, $time);
    } else {
        wp_mail(
            get_option('admin_email'),
            'Онлайн-запись с сайта',
            "Имя: {$name}\nТелефон: {$phone}\nУслуга: {$service}\nВрач: {$doctor}\nДата: {$date}\nВремя: {$time}"
        );
    }

    $sms = klinika_option('klinika_sms_webhook', '');
    if ($sms !== '') {
        wp_remote_post($sms, [
            'timeout' => 6,
            'body'    => [
                'phone'   => $phone,
                'message' => "Код подтверждения записи: {$id}. Клиника «Здоровые дети».",
            ],
        ]);
    }

    return ['id' => $id, 'ok' => true, 'confirm' => (string) $id];
}

function klinika_rest_list_bookings()
{
    $posts = get_posts([
        'post_type'      => 'klinika_booking',
        'posts_per_page' => 100,
        'post_status'    => 'publish',
    ]);
    $items = [];
    foreach ($posts as $post) {
        $items[] = [
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
    return $items;
}

function klinika_rest_update_booking(WP_REST_Request $request)
{
    $id = (int) $request['id'];
    $post = get_post($id);
    if (!$post || $post->post_type !== 'klinika_booking') {
        return new WP_Error('not_found', 'Booking not found', ['status' => 404]);
    }
    foreach ([
        'phone' => '_klinika_booking_phone',
        'service' => '_klinika_booking_service',
        'doctor' => '_klinika_booking_doctor',
        'date' => '_klinika_booking_date',
        'time' => '_klinika_booking_time',
        'status' => '_klinika_booking_status',
        'note' => '_klinika_booking_note',
    ] as $param => $meta) {
        if ($request->offsetExists($param)) {
            update_post_meta($id, $meta, sanitize_text_field($request->get_param($param)));
        }
    }
    if ($request->offsetExists('title')) {
        wp_update_post(['ID' => $id, 'post_title' => sanitize_text_field($request->get_param('title'))]);
    }
    return ['id' => $id, 'updated' => true];
}

function klinika_rest_delete_booking(WP_REST_Request $request)
{
    $id = (int) $request['id'];
    $post = get_post($id);
    if (!$post || $post->post_type !== 'klinika_booking') {
        return new WP_Error('not_found', 'Booking not found', ['status' => 404]);
    }
    wp_trash_post($id);
    return ['deleted' => true, 'id' => $id];
}

function klinika_rest_catalog()
{
    return [
        'services'  => array_map(static function ($s) {
            return ['slug' => $s['slug'], 'title' => $s['title'], 'url' => $s['url']];
        }, klinika_service_items()),
        'doctors'   => klinika_doctor_cards(50),
        'reviews'   => klinika_review_items(),
        'gallery'   => klinika_gallery_items('all'),
        'prices'    => klinika_price_items('all'),
        'partners'  => klinika_partner_logos(),
        'advantages'=> klinika_advantage_defaults(),
    ];
}
