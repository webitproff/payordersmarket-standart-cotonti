<?php
// Начинаем PHP-код
/**
 * [BEGIN_COT_EXT]
 * Hooks=global
 * [END_COT_EXT]
 */
// Указываем, что плагин работает как глобальный обработчик в Cotonti

/**
 * Global Handler File for the Plugin Pay Orders in Market
 *
 * payordersmarket plugin for Cotonti 0.9.26, PHP 8.4+
 * Filename: payordersmarket.global.php
 * @package payordersmarket
 * @version 5.0.1
 * @author  webitproff
 * @copyright Copyright (c)  webitproff 2025 | https://github.com/webitproff
 * @license BSD
 */
// Документируем метаданные плагина: название, версия, автор, копирайт, лицензия

// Проверяем, определена ли константа COT_CODE, иначе выдаём ошибку "Wrong URL"
defined('COT_CODE') or die('Wrong URL.');

// Подключаем файл конфигурации и функций плагина payordersmarket
require_once cot_incfile('payordersmarket', 'plug');
require_once cot_langfile('payordersmarket', 'plug');
// Подключаем файл модуля market для работы с товарами
require_once cot_incfile('market', 'module');
// Подключаем файл модуля payments для работы с платежами
require_once cot_incfile('payments', 'module');

// Импортируем класс PaymentDictionary для работы со статусами платежей
use cot\modules\payments\dictionaries\PaymentDictionary;
// Импортируем класс PaymentRepository для взаимодействия с базой данных платежей
use cot\modules\payments\Repositories\PaymentRepository;
// Импортируем класс PaymentService для управления статусами платежей
use cot\modules\payments\Services\PaymentService;


// регистрируем нашу таблицу
Cot::$db->registerTable('payordersmarket');

    global $db, $db_x;
    $db_payordersmarket = $db_x . 'payordersmarket';


// Получаем все платежи с областью 'payordersmarket' и статусом 'paid' через метод getByCondition
$paysInMarket = PaymentRepository::getInstance()->getByCondition([
    'pay_area' => "pay_area = 'payordersmarket'",
    'pay_status' => "pay_status = 'paid'"
]);
// Проверяем, есть ли платежи с указанными параметрами
if ($paysInMarket)
{
    // Перебираем все найденные платежи
    foreach ($paysInMarket as $pay)
    {
        // Обновляем статус платежа на 'done' с помощью сервиса PaymentService
        if (PaymentService::getInstance()->setStatus($pay['pay_id'], PaymentDictionary::STATUS_DONE, null))
        {
            // Обновляем заказ в таблице $db_payordersmarket, устанавливая время оплаты и статус 'paid'
            $db->update($db_payordersmarket, array('order_paid' => (int)$sys['now'], 'order_status' => 'paid'), "order_id=".(int)$pay['pay_code']);

            // Получаем данные о заказе из таблицы $db_payordersmarket, связывая с $db_market
            $payOrder = $db->query("SELECT * FROM $db_payordersmarket AS o
                LEFT JOIN $db_market AS m ON m.fieldmrkt_id=o.order_pid
                WHERE order_id=".$pay['pay_code'])->fetch();

            // Получаем данные о продавце из таблицы $db_users
            $seller = $db->query("SELECT * FROM $db_users WHERE user_id=".$payOrder['order_seller'])->fetch();
            // Проверяем, зарегистрирован ли покупатель
            if($payOrder['order_userid'] > 0)
            {
                // Если покупатель зарегистрирован, получаем его данные
                $customer = $db->query("SELECT * FROM $db_users WHERE user_id=".$payOrder['order_userid'])->fetch();
            }
            // Если покупатель — гость
            else
            {
                // Используем email заказа как имя и email покупателя
                $customer['user_name'] = $payOrder['order_email'];
                $customer['user_email'] = $payOrder['order_email'];
            }

            // Рассчитываем сумму для продавца, вычитая свой процент/налог от продаж на сайте
            $summ = $payOrder['order_cost'] - $payOrder['order_cost']*$cfg['plugin']['payordersmarket']['tax']/100;

            // Формируем заголовок письма для продавца
            $rsubject = cot_rc($L['payordersmarket_paid_mail_toseller_header'], array('order_id' => $payOrder['order_id'], 'product_title' => $payOrder['fieldmrkt_title']));
            // Формируем тело письма для продавца
            $rbody = cot_rc($L['payordersmarket_paid_mail_toseller_body'], array(
                'user_name' => $customer['user_name'],
                'product_id' => $payOrder['fieldmrkt_id'],
                'product_title' => $payOrder['fieldmrkt_title'],
                'order_id' => $payOrder['order_id'],
                'summ' => $summ.' '.$cfg['payments']['valuta'],
                'tax' => $cfg['plugin']['payordersmarket']['tax'],
                'warranty' => $cfg['plugin']['payordersmarket']['warranty'],
                'sitename' => $cfg['maintitle'],
                'link' => COT_ABSOLUTE_URL . cot_url('payordersmarket', "id=" . $payOrder['order_id'], '', true)
            ));
            // Отправляем письмо продавцу
            cot_mail($seller['user_email'], $rsubject, $rbody);

            // Проверяем, указан ли email покупателя
            if(!empty($payOrder['order_email']))
            {
                // Генерируем ключ для ссылки
                $key = sha1($payOrder['order_email'].'&'.$payOrder['order_id']);
            }

            // Формируем заголовок письма для покупателя
            $rsubject = cot_rc($L['payordersmarket_paid_mail_tocustomer_header'], array('order_id' => $payOrder['order_id'], 'product_title' => $payOrder['fieldmrkt_title']));
            // Формируем тело письма для покупателя
            $rbody = cot_rc($L['payordersmarket_paid_mail_tocustomer_body'], array(
                'user_name' => $customer['user_name'],
                'product_id' => $payOrder['fieldmrkt_id'],
                'product_title' => $payOrder['fieldmrkt_title'],
                'order_id' => $payOrder['order_id'],
                'cost' => $payOrder['order_cost'].' '.$cfg['payments']['valuta'],
                'tax' => $cfg['plugin']['payordersmarket']['tax'],
                'warranty' => $cfg['plugin']['payordersmarket']['warranty'],
                'sitename' => $cfg['maintitle'],
                'link' => COT_ABSOLUTE_URL . cot_url('payordersmarket', "id=" . $payOrder['order_id'] . '&key=' . $key, '', true)
            ));
            // Отправляем письмо покупателю
            cot_mail($customer['user_email'], $rsubject, $rbody);

            // Вызываем хуки для события 'payordersmarket.order.paid'
            foreach (cot_getextplugins('payordersmarket.order.paid') as $pl)
            {
                // Подключаем файл плагина
                include $pl;
            }
        }
    }
}

// Проверяем, включена ли настройка принятия заказов с нулевой стоимостью
if($cfg['plugin']['payordersmarket']['acceptzerocostorders']) {
    global $db, $db_x;
    $db_payordersmarket = $db_x . 'payordersmarket';
    // Получаем заказы с нулевой стоимостью и статусом 'new'
    $payordersmarket = $db->query("SELECT * FROM $db_payordersmarket AS o
        LEFT JOIN $db_market AS m ON m.fieldmrkt_id=o.order_pid
        WHERE order_status='new' AND order_cost<=0")->fetchAll();
    // Перебираем заказы с нулевой стоимостью
    foreach ($payordersmarket as $payOrder)
    {
        // Обновляем заказ, устанавливая время оплаты и статус 'paid'
        $db->update($db_payordersmarket, array('order_paid' => (int)$sys['now'], 'order_status' => 'paid'), "order_id=".(int)$payOrder['order_id']);

        // Получаем данные о продавце
        $seller = $db->query("SELECT * FROM $db_users WHERE user_id=".$payOrder['order_seller'])->fetch();
        // Проверяем, зарегистрирован ли покупатель
        if($payOrder['order_userid'] > 0)
        {
            // Получаем данные покупателя
            $customer = $db->query("SELECT * FROM $db_users WHERE user_id=".$payOrder['order_userid'])->fetch();
        }
        // Если покупатель — гость
        else
        {
            // Используем email заказа как имя и email
            $customer['user_name'] = $payOrder['order_email'];
            $customer['user_email'] = $payOrder['order_email'];
        }

        // Устанавливаем сумму равной 0
        $summ = 0;

        // Формируем заголовок письма для продавца
        $rsubject = cot_rc($L['payordersmarket_paid_mail_toseller_header'], array('order_id' => $payOrder['order_id'], 'product_title' => $payOrder['fieldmrkt_title']));
        // Формируем тело письма для продавца
        $rbody = cot_rc($L['payordersmarket_paid_mail_toseller_body'], array(
            'user_name' => $customer['user_name'],
            'product_id' => $payOrder['fieldmrkt_id'],
            'product_title' => $payOrder['fieldmrkt_title'],
            'order_id' => $payOrder['order_id'],
            'summ' => $summ.' '.$cfg['payments']['valuta'],
            'tax' => $cfg['plugin']['payordersmarket']['tax'],
            'warranty' => $cfg['plugin']['payordersmarket']['warranty'],
            'sitename' => $cfg['maintitle'],
            'link' => COT_ABSOLUTE_URL . cot_url('payordersmarket', "id=" . $payOrder['order_id'], '', true)
        ));
        // Отправляем письмо продавцу
        cot_mail($seller['user_email'], $rsubject, $rbody);

        // Проверяем, указан ли email покупателя
        if(!empty($payOrder['order_email']))
        {
            // Генерируем ключ для ссылки
            $key = sha1($payOrder['order_email'].'&'.$payOrder['order_id']);
        }

        // Формируем заголовок письма для покупателя
        $rsubject = cot_rc($L['payordersmarket_paid_mail_tocustomer_header'], array('order_id' => $payOrder['order_id'], 'product_title' => $payOrder['fieldmrkt_title']));
        // Формируем тело письма для покупателя
        $rbody = cot_rc($L['payordersmarket_paid_mail_tocustomer_body'], array(
            'user_name' => $customer['user_name'],
            'product_id' => $payOrder['fieldmrkt_id'],
            'product_title' => $payOrder['fieldmrkt_title'],
            'order_id' => $payOrder['order_id'],
            'cost' => $payOrder['order_cost'].' '.$cfg['payments']['valuta'],
            'tax' => $cfg['plugin']['payordersmarket']['tax'],
            'warranty' => $cfg['plugin']['payordersmarket']['warranty'],
            'sitename' => $cfg['maintitle'],
            'link' => COT_ABSOLUTE_URL . cot_url('payordersmarket', "id=" . $payOrder['order_id'] . '&key=' . $key, '', true)
        ));
        // Отправляем письмо покупателю
        cot_mail($customer['user_email'], $rsubject, $rbody);

        // Вызываем хуки для события 'payordersmarket.order.paid'
        foreach (cot_getextplugins('payordersmarket.order.paid') as $pl)
        {
            // Подключаем файл плагина
            include $pl;
        }
    }
}

// Рассчитываем гарантийный срок в секундах
$warranty = $cfg['plugin']['payordersmarket']['warranty']*60*60*24;
// Получаем оплаченные заказы с истёкшим гарантийным сроком
$payordersmarket = $db->query("SELECT * FROM $db_payordersmarket AS o
    LEFT JOIN $db_market AS m ON m.fieldmrkt_id=o.order_pid
    WHERE order_status='paid' AND order_paid+".$warranty."<".$sys['now'])->fetchAll();
// Перебираем заказы с истёкшим гарантийным сроком
foreach ($payordersmarket as $payOrder)
{
    // Получаем данные о продавце
    $seller = $db->query("SELECT * FROM $db_users WHERE user_id=".$payOrder['order_seller'])->fetch();

    // Проверяем, если стоимость заказа равна 0 или меньше
    if($payOrder['order_cost'] <= 0) {
        // Создаём массив для обновления заказа
        $rorder = array();
        // Устанавливаем время завершения заказа
        $rorder['order_done'] = $sys['now'];
        // Устанавливаем статус заказа на 'done'
        $rorder['order_status'] = 'done';

        // Обновляем запись в таблице заказов
        if($db->update($db_payordersmarket, $rorder, "order_id=".$payOrder['order_id']))
        {
            // Пустой блок для будущих действий
        }

        // Вызываем хуки для события 'payordersmarket.order.done'
        foreach (cot_getextplugins('payordersmarket.order.done') as $pl)
        {
            // Подключаем файл плагина
            include $pl;
        }

        // Пропускаем дальнейшую обработку для заказов с нулевой стоимостью
        continue;
    }

    // Рассчитываем сумму для продавца, вычитая налог
    $summ = $payOrder['order_cost'] - $payOrder['order_cost']*$cfg['plugin']['payordersmarket']['tax']/100;

    // Формируем данные для выплаты продавцу
    $payinfo['pay_userid'] = $payOrder['order_seller'];
    $payinfo['pay_area'] = 'balance';
    $payinfo['pay_code'] = 'payordersmarket:'.$payOrder['order_id'];
    $payinfo['pay_summ'] = $summ;
    $payinfo['pay_cdate'] = $sys['now'];
    $payinfo['pay_pdate'] = $sys['now'];
    $payinfo['pay_adate'] = $sys['now'];
    $payinfo['pay_status'] = 'done';
    $payinfo['pay_desc'] = cot_rc($L['payordersmarket_done_payments_desc'], array(
        'product_title' => $payOrder['fieldmrkt_title'],
        'order_id' => $payOrder['order_id']
    ));

    // Добавляем запись о выплате в таблицу $db_payments
    if($db->insert($db_payments, $payinfo))
    {
        // Формируем заголовок письма для продавца о выплате
        $rsubject = cot_rc($L['payordersmarket_done_mail_toseller_header'], array('order_id' => $payOrder['order_id'], 'product_title' => $payOrder['fieldmrkt_title']));
        // Формируем тело письма для продавца о выплате
        $rbody = cot_rc($L['payordersmarket_done_mail_toseller_body'], array(
            'product_id' => $payOrder['fieldmrkt_id'],
            'product_title' => $payOrder['fieldmrkt_title'],
            'order_id' => $payOrder['order_id'],
            'summ' => $summ.' '.$cfg['payments']['valuta'],
            'tax' => $cfg['plugin']['payordersmarket']['tax'],
            'sitename' => $cfg['maintitle'],
            'link' => COT_ABSOLUTE_URL . cot_url('payordersmarket', "id=" . $payOrder['order_id'], '', true)
        ));
        // Отправляем письмо продавцу о выплате
        cot_mail($seller['user_email'], $rsubject, $rbody);

        // Устанавливаем время завершения и статус 'done'
        $rorder['order_done'] = $sys['now'];
        $rorder['order_status'] = 'done';

        // Обновляем запись в таблице заказов
        if($db->update($db_payordersmarket, $rorder, "order_id=".$payOrder['order_id']))
        {
            // Проверяем, указан ли adminid и есть ли налог
            if($cfg['plugin']['payordersmarket']['adminid'] > 0 && $cfg['plugin']['payordersmarket']['tax'] > 0)
            {
                // Формируем данные для выплаты налога администратору
                $payinfo['pay_userid'] = $cfg['plugin']['payordersmarket']['adminid'];
                $payinfo['pay_area'] = 'balance';
                $payinfo['pay_code'] = 'payordersmarket:'.$payOrder['order_id'];
                $payinfo['pay_summ'] = $payOrder['order_cost']*$cfg['plugin']['payordersmarket']['tax']/100;
                $payinfo['pay_cdate'] = $sys['now'];
                $payinfo['pay_pdate'] = $sys['now'];
                $payinfo['pay_adate'] = $sys['now'];
                $payinfo['pay_status'] = 'done';
                $payinfo['pay_desc'] = cot_rc($L['payordersmarket_tax_payments_desc'], array(
                    'product_title' => $payOrder['fieldmrkt_title'],
                    'order_id' => $payOrder['order_id']
                ));

                // Добавляем запись о выплате налога
                $db->insert($db_payments, $payinfo);
            }
        }

        // Вызываем хуки для события 'payordersmarket.order.done'
        foreach (cot_getextplugins('payordersmarket.order.done') as $pl)
        {
            // Подключаем файл плагина
            include $pl;
        }
    }
}
