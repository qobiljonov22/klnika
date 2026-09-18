<?php
/**
 * Admin dashboard: заявки, analytics, CSV, backup.
 *
 * @package Klinika
 */

if (!defined('ABSPATH')) {
    exit;
}

function klinika_add_dashboard_menu()
{
    add_menu_page(
        'Клиника',
        'Клиника',
        'edit_posts',
        'klinika-dashboard',
        'klinika_render_dashboard',
        'dashicons-heart',
        3
    );
    add_submenu_page('klinika-dashboard', 'Dashboard', 'Dashboard', 'edit_posts', 'klinika-dashboard', 'klinika_render_dashboard');
    add_submenu_page('klinika-dashboard', 'Цены CSV', 'Цены CSV', 'manage_options', 'klinika-prices-csv', 'klinika_render_prices_csv');
    add_submenu_page('klinika-dashboard', 'Backup', 'Backup', 'manage_options', 'klinika-backup', 'klinika_render_backup_page');
}
add_action('admin_menu', 'klinika_add_dashboard_menu');

function klinika_booking_status_label($status)
{
    $map = [
        'new'       => 'новая',
        'confirmed' => 'подтверждена',
        'cancelled' => 'отменена',
    ];
    return $map[$status] ?? $status;
}

function klinika_render_dashboard()
{
    if (!current_user_can('edit_posts')) {
        return;
    }

    $today = gmdate('Y-m-d');
    $bookings_today = get_posts([
        'post_type'      => 'klinika_booking',
        'posts_per_page' => 50,
        'post_status'    => 'publish',
        'date_query'     => [['after' => '1 day ago']],
    ]);
    $new_reviews = get_posts([
        'post_type'      => 'klinika_review',
        'posts_per_page' => 8,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
    ]);
    $open_slots = 12; // placeholder slots capacity signal
    $cta = get_option('klinika_cta_stats', []);
    $cta_today = is_array($cta) && isset($cta[$today]) ? $cta[$today] : [];
    arsort($cta_today);

    $pending = get_posts([
        'post_type'      => 'klinika_booking',
        'posts_per_page' => 20,
        'post_status'    => 'publish',
        'meta_query'     => [
            'relation' => 'OR',
            [
                'key'     => '_klinika_booking_status',
                'compare' => 'NOT EXISTS',
            ],
            [
                'key'   => '_klinika_booking_status',
                'value' => 'new',
            ],
        ],
    ]);
    ?>
    <div class="wrap">
        <h1>Клиника — dashboard</h1>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;margin:16px 0;">
            <div style="background:#fff;border:1px solid #c3c4c7;padding:16px;border-radius:4px;">
                <div style="font-size:12px;color:#646970;">Заявки за сутки</div>
                <div style="font-size:28px;font-weight:600;"><?php echo (int) count($bookings_today); ?></div>
            </div>
            <div style="background:#fff;border:1px solid #c3c4c7;padding:16px;border-radius:4px;">
                <div style="font-size:12px;color:#646970;">Новые (без статуса)</div>
                <div style="font-size:28px;font-weight:600;"><?php echo (int) count($pending); ?></div>
            </div>
            <div style="background:#fff;border:1px solid #c3c4c7;padding:16px;border-radius:4px;">
                <div style="font-size:12px;color:#646970;">Новые отзывы</div>
                <div style="font-size:28px;font-weight:600;"><?php echo (int) count($new_reviews); ?></div>
            </div>
            <div style="background:#fff;border:1px solid #c3c4c7;padding:16px;border-radius:4px;">
                <div style="font-size:12px;color:#646970;">Слоты (ориентир)</div>
                <div style="font-size:28px;font-weight:600;"><?php echo (int) $open_slots; ?></div>
            </div>
        </div>

        <h2>Сегодняшние / новые заявки</h2>
        <table class="widefat striped">
            <thead>
                <tr><th>ID</th><th>Клиент</th><th>Телефон</th><th>Дата/время</th><th>Статус</th><th></th></tr>
            </thead>
            <tbody>
            <?php if (!$pending) : ?>
                <tr><td colspan="6">Нет новых заявок</td></tr>
            <?php endif; ?>
            <?php foreach ($pending as $post) :
                $st = get_post_meta($post->ID, '_klinika_booking_status', true) ?: 'new';
                ?>
                <tr>
                    <td><?php echo (int) $post->ID; ?></td>
                    <td><?php echo esc_html(get_the_title($post)); ?></td>
                    <td><?php echo esc_html(get_post_meta($post->ID, '_klinika_booking_phone', true)); ?></td>
                    <td><?php echo esc_html(get_post_meta($post->ID, '_klinika_booking_date', true) . ' ' . get_post_meta($post->ID, '_klinika_booking_time', true)); ?></td>
                    <td><?php echo esc_html(klinika_booking_status_label($st)); ?></td>
                    <td><a href="<?php echo esc_url(get_edit_post_link($post->ID)); ?>">Открыть</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <h2 style="margin-top:28px;">Новые отзывы</h2>
        <ul>
            <?php foreach ($new_reviews as $r) : ?>
                <li><a href="<?php echo esc_url(get_edit_post_link($r->ID)); ?>"><?php echo esc_html(get_the_title($r)); ?></a>
                    — <?php echo esc_html(get_the_date('', $r)); ?></li>
            <?php endforeach; ?>
        </ul>

        <h2>Analytics CTA (сегодня)</h2>
        <?php if (!$cta_today) : ?>
            <p>Пока нет кликов.</p>
        <?php else : ?>
            <table class="widefat striped" style="max-width:480px;">
                <thead><tr><th>CTA</th><th>Клики</th></tr></thead>
                <tbody>
                <?php foreach ($cta_today as $key => $count) : ?>
                    <tr><td><?php echo esc_html($key); ?></td><td><?php echo (int) $count; ?></td></tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <p style="margin-top:24px;">
            <a class="button button-primary" href="<?php echo esc_url(admin_url('edit.php?post_type=klinika_booking')); ?>">Все заявки</a>
            <a class="button" href="<?php echo esc_url(admin_url('post-new.php?post_type=klinika_doctor')); ?>">Добавить врача</a>
            <a class="button" href="<?php echo esc_url(admin_url('admin.php?page=klinika-prices-csv')); ?>">Импорт/экспорт цен</a>
            <a class="button" href="<?php echo esc_url(admin_url('options-general.php?page=klinika-settings')); ?>">Настройки / Telegram</a>
        </p>
    </div>
    <?php
}

function klinika_handle_csv_backup_actions()
{
    if (!is_admin() || !current_user_can('manage_options')) {
        return;
    }
    if (isset($_POST['klinika_csv_export']) && check_admin_referer('klinika_csv')) {
        $rows = [['title', 'price', 'group']];
        foreach (klinika_price_items('all') as $item) {
            $rows[] = [$item['title'], $item['price'], $item['group'] ?? 'priem'];
        }
        nocache_headers();
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=klinika-prices.csv');
        $out = fopen('php://output', 'w');
        fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));
        foreach ($rows as $row) {
            fputcsv($out, $row, ';');
        }
        fclose($out);
        exit;
    }
    if (isset($_POST['klinika_backup_export']) && check_admin_referer('klinika_backup')) {
        $types = ['klinika_service', 'klinika_doctor', 'klinika_news', 'klinika_review', 'klinika_gallery', 'klinika_license', 'klinika_price', 'klinika_slide', 'klinika_advantage', 'klinika_partner'];
        $dump = ['exported_at' => gmdate('c'), 'site' => home_url('/'), 'posts' => []];
        foreach ($types as $type) {
            $posts = get_posts(['post_type' => $type, 'posts_per_page' => 500, 'post_status' => 'any']);
            foreach ($posts as $post) {
                $dump['posts'][] = [
                    'type'    => $type,
                    'title'   => $post->post_title,
                    'content' => $post->post_content,
                    'status'  => $post->post_status,
                    'meta'    => get_post_meta($post->ID),
                ];
            }
        }
        nocache_headers();
        header('Content-Type: application/json; charset=utf-8');
        header('Content-Disposition: attachment; filename=klinika-backup-' . gmdate('Ymd-His') . '.json');
        echo wp_json_encode($dump, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }
}
add_action('admin_init', 'klinika_handle_csv_backup_actions');

function klinika_render_prices_csv()
{
    if (!current_user_can('manage_options')) {
        return;
    }
    $imported = 0;
    if (isset($_POST['klinika_csv_import']) && check_admin_referer('klinika_csv') && !empty($_FILES['klinika_csv']['tmp_name'])) {
        $fh = fopen($_FILES['klinika_csv']['tmp_name'], 'r');
        if ($fh) {
            $first = fgetcsv($fh, 0, ';');
            $sep = (is_array($first) && count($first) >= 2) ? ';' : ',';
            if ($sep === ',') {
                rewind($fh);
                $first = fgetcsv($fh, 0, ',');
            }
            while (($row = fgetcsv($fh, 0, $sep)) !== false) {
                if (count($row) < 2) {
                    continue;
                }
                $title = sanitize_text_field($row[0]);
                $price = sanitize_text_field($row[1]);
                $group = sanitize_key($row[2] ?? 'priem');
                if ($title === '' || strtolower($title) === 'title') {
                    continue;
                }
                $id = wp_insert_post([
                    'post_type'   => 'klinika_price',
                    'post_status' => 'publish',
                    'post_title'  => $title,
                ]);
                if ($id && !is_wp_error($id)) {
                    update_post_meta($id, '_klinika_price_value', $price);
                    update_post_meta($id, '_klinika_price_group', $group ?: 'priem');
                    $imported++;
                }
            }
            fclose($fh);
        }
    }
    ?>
    <div class="wrap">
        <h1>Цены — Excel/CSV</h1>
        <?php if ($imported) : ?>
            <div class="notice notice-success"><p>Импортировано: <?php echo (int) $imported; ?></p></div>
        <?php endif; ?>
        <form method="post">
            <?php wp_nonce_field('klinika_csv'); ?>
            <p><button type="submit" name="klinika_csv_export" class="button button-primary" value="1">Экспорт CSV</button></p>
        </form>
        <hr>
        <form method="post" enctype="multipart/form-data">
            <?php wp_nonce_field('klinika_csv'); ?>
            <p>Формат: <code>title;price;group</code> (group: priem / analizy / diag)</p>
            <input type="file" name="klinika_csv" accept=".csv,text/csv" required>
            <p><button type="submit" name="klinika_csv_import" class="button" value="1">Импорт CSV</button></p>
        </form>
    </div>
    <?php
}

function klinika_render_backup_page()
{
    if (!current_user_can('manage_options')) {
        return;
    }
    ?>
    <div class="wrap">
        <h1>Backup контента</h1>
        <p>Скачайте JSON-бэкап CPT клиники. Staging: создайте копию сайта в Local WP / хостинге.</p>
        <form method="post">
            <?php wp_nonce_field('klinika_backup'); ?>
            <button type="submit" name="klinika_backup_export" class="button button-primary" value="1">Скачать backup JSON</button>
        </form>
    </div>
    <?php
}

/** Gallery / license sortable by menu_order */
function klinika_admin_sortable_assets($hook)
{
    if ($hook !== 'edit.php') {
        return;
    }
    $screen = get_current_screen();
    if (!$screen || !in_array($screen->post_type, ['klinika_gallery', 'klinika_license'], true)) {
        return;
    }
    wp_enqueue_script('jquery-ui-sortable');
    wp_add_inline_script('jquery-ui-sortable', "jQuery(function($){
        var \$tbody=\$('.wp-list-table tbody');
        \$tbody.sortable({items:'tr',axis:'y',update:function(){
            var order=[];
            \$tbody.find('tr').each(function(i){var id=\$(this).attr('id'); if(id){order.push({id:id.replace('post-',''),menu_order:i+1});}});
            $.post(ajaxurl,{action:'klinika_save_order',nonce:'" . esc_js(wp_create_nonce('klinika_order')) . "',order:JSON.stringify(order)});
        }});
    });");
}
add_action('admin_enqueue_scripts', 'klinika_admin_sortable_assets');

function klinika_ajax_save_order()
{
    if (!current_user_can('edit_posts') || !check_ajax_referer('klinika_order', 'nonce', false)) {
        wp_send_json_error(null, 403);
    }
    $order = json_decode(wp_unslash($_POST['order'] ?? '[]'), true);
    if (!is_array($order)) {
        wp_send_json_error();
    }
    foreach ($order as $row) {
        $id = (int) ($row['id'] ?? 0);
        $mo = (int) ($row['menu_order'] ?? 0);
        if ($id) {
            wp_update_post(['ID' => $id, 'menu_order' => $mo]);
        }
    }
    wp_send_json_success();
}
add_action('wp_ajax_klinika_save_order', 'klinika_ajax_save_order');
