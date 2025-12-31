<?php
// Начинаем PHP-код
/**
 * claim form File for the Plugin Pay Orders in Market
 *
 * payordersmarket plugin for Cotonti 0.9.26, PHP 8.4+
 * Filename: payordersmarket.addclaim.php
 * @package payordersmarket
 * @version 5.0.1
 * @author  webitproff
 * @copyright Copyright (c)  webitproff 2025 | https://github.com/webitproff
 * @license BSD
 */
// Документируем метаданные плагина: название, версия, автор, копирайт, лицензия

// Проверяем, определена ли константа COT_CODE, иначе выдаём ошибку "Wrong URL"
defined('COT_CODE') or die('Wrong URL');

// Извлекаем параметр 'id' из GET-запроса, ожидая целое число
$id = cot_import('id', 'G', 'INT');

// Проверяем права доступа пользователя для плагина payordersmarket (чтение, запись, админ)
list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = cot_auth('plug', 'payordersmarket');
// Блокируем выполнение, если нет прав на чтение
cot_block($usr['auth_read']);

// Проверяем, указан ли ID заказа
if ($id > 0)
{
    // Выполняем SQL-запрос для получения данных о заказе и связанном товаре
    $sql = $db->query("SELECT * FROM $db_payordersmarket AS o
        LEFT JOIN $db_market AS m ON m.fieldmrkt_id=o.order_pid
        WHERE order_id=".$id." LIMIT 1");
}

// Проверяем, корректен ли ID, существует ли запрос и есть ли результат
if (!$id || !$sql || $sql->rowCount() == 0)
{
    // Выводим ошибку 404, если заказ не найден
    cot_die_message(404, TRUE);
}
// Извлекаем данные о заказе из результата запроса
$payOrder = $sql->fetch();

// Проверяем, что заказ имеет статус 'paid' и принадлежит текущему пользователю
cot_block($payOrder['order_status'] == 'paid' && $payOrder['order_userid'] == $usr['id']);

/* === Hook === */
// Получаем список плагинов для хука 'payordersmarket.addclaim.first'
$extp = cot_getextplugins('payordersmarket.addclaim.first');
// Подключаем каждый плагин из хука
foreach ($extp as $pl)
{
    // Включаем файл плагина
    include $pl;
}
/* ===== */

// Проверяем, отправлена ли форма (параметр a=add)
if ($a == 'add')
{
    // Защищаем от CSRF-атак
    cot_shield_protect();
    
    /* === Hook === */
    // Получаем список плагинов для хука 'payordersmarket.addclaim.add.first'
    foreach (cot_getextplugins('payordersmarket.addclaim.add.first') as $pl)
    {
        // Включаем файл плагина
        include $pl;
    }
    /* ===== */
    
    // Извлекаем текст жалобы из POST-запроса
    $rorder['order_claimtext'] = cot_import('rclaimtext', 'P', 'TXT');
    
    /* === Hook === */
    // Получаем список плагинов для хука 'payordersmarket.addclaim.add.import'
    foreach (cot_getextplugins('payordersmarket.addclaim.add.import') as $pl)
    {
        // Включаем файл плагина
        include $pl;
    }
    /* ===== */

    // Проверяем, заполнен ли текст жалобы, и выдаём ошибку, если пусто
    cot_check(empty($rorder['order_claimtext']), 'payordersmarket_order_error_claimtext', 'rclaimtext');
    
    /* === Hook === */
    // Получаем список плагинов для хука 'payordersmarket.addclaim.add.error'
    foreach (cot_getextplugins('payordersmarket.addclaim.add.error') as $pl)
    {
        // Включаем файл плагина
        include $pl;
    }
    /* ===== */

    // Проверяем, нет ли ошибок валидации
    if (!cot_error_found())
    {
        // Устанавливаем время подачи жалобы
        $rorder['order_claim'] = $sys['now'];
        // Устанавливаем статус заказа на 'claim'
        $rorder['order_status'] = 'claim';
        
        // Обновляем запись в таблице заказов
        $db->update($db_payordersmarket, $rorder, 'order_id='.$id);
        
        // Получаем данные о продавце
        $seller = $db->query("SELECT * FROM $db_users WHERE user_id=".$payOrder['order_seller'])->fetch();
        // Получаем данные о покупателе
        $customer = $db->query("SELECT * FROM $db_users WHERE user_id=".$payOrder['order_userid'])->fetch();
            
        // Формируем заголовок письма для продавца о жалобе
        $rsubject = cot_rc($L['payordersmarket_addclaim_mail_toseller_header'], array('order_id' => $payOrder['order_id'], 'product_title' => $payOrder['fieldmrkt_title']));
        // Формируем тело письма для продавца
        $rbody = cot_rc($L['payordersmarket_addclaim_mail_toseller_body'], array(
            'product_title' => $payOrder['fieldmrkt_title'],
            'order_id' => $payOrder['order_id'],    
            'sitename' => $cfg['maintitle'],
            'link' => COT_ABSOLUTE_URL . cot_url('payordersmarket', "id=" . $payOrder['order_id'], '', true)
        ));
        // Отправляем письмо продавцу
        cot_mail($seller['user_email'], $rsubject, $rbody);
        
        // Формируем заголовок письма для админа о жалобе
        $rsubject = cot_rc($L['payordersmarket_addclaim_mail_toadmin_header'], array('order_id' => $payOrder['order_id'], 'product_title' => $payOrder['fieldmrkt_title']));
        // Формируем тело письма для админа
        $rbody = cot_rc($L['payordersmarket_addclaim_mail_toadmin_body'], array(
            'product_title' => $payOrder['fieldmrkt_title'],
            'order_id' => $payOrder['order_id'],    
            'sitename' => $cfg['maintitle'],
            'link' => COT_ABSOLUTE_URL . cot_url('payordersmarket', "id=" . $payOrder['order_id'], '', true)
        ));

        /* === Hook === */
        // Получаем список плагинов для хука 'payordersmarket.addclaim.done'
        foreach (cot_getextplugins('payordersmarket.addclaim.done') as $pl)
        {
            // Включаем файл плагина
            include $pl;
        }
        /* ===== */

        // Отправляем письмо админу
        cot_mail($cfg['adminemail'], $rsubject, $rbody);

        // Перенаправляем на страницу заказа
        cot_redirect(cot_url('payordersmarket', 'm=order&id=' . $id, '', true));
        exit;
    }
    
    // Если есть ошибки, перенаправляем на форму подачи жалобы
    cot_redirect(cot_url('payordersmarket', 'm=addclaim&id=' . $id, '', true));
    exit;
}

// Устанавливаем заголовок страницы
$out['subtitle'] = $L['payordersmarket_neworder_title'];
// Добавляем мета-тег noindex в заголовок страницы
$out['head'] .= $R['code_noindex'];

// Загружаем шаблон для формы подачи жалобы, используя категорию из $payOrder
$mskin = cot_tplfile(array('payordersmarket', 'addclaim', isset($payOrder['fieldmrkt_cat']) ? $structure['market'][$payOrder['fieldmrkt_cat']]['tpl'] : ''), 'plug');

/* === Hook === */
// Получаем список плагинов для хука 'payordersmarket.addclaim.main'
foreach (cot_getextplugins('payordersmarket.addclaim.main') as $pl)
{
    // Включаем файл плагина
    include $pl;
}
/* ===== */

// Создаём объект шаблона XTemplate с указанным файлом шаблона
$t = new XTemplate($mskin);

// Формируем массив для хлебных крошек
$catpatharray[] = array(cot_url('market'), $L['market']);
$catpatharray[] = array('', $L['payordersmarket_addclaim_title']);

// Создаём строку хлебных крошек
$catpath = cot_breadcrumbs($catpatharray, $cfg['homebreadcrumb'], true);

// Передаём данные в шаблон
$t->assign(array(
    // Хлебные крошки
    "BREADCRUMBS" => $catpath,
));

// Обрабатываем ошибки и сообщения
cot_display_messages($t);

// Передаём данные формы в шаблон
$t->assign(array(
    // URL для отправки формы
    "CLAIM_FORM_SEND" => cot_url('payordersmarket', 'm=addclaim&id='.$id.'&a=add'),
    // Поле для ввода текста жалобы
    "CLAIM_FORM_TEXT" => cot_textarea('rclaimtext', '', 5, 60),
));

/* === Hook === */
// Получаем список плагинов для хука 'payordersmarket.addclaim.tags'
foreach (cot_getextplugins('payordersmarket.addclaim.tags') as $pl)
{
    // Включаем файл плагина
    include $pl;
}
/* ===== */
?>