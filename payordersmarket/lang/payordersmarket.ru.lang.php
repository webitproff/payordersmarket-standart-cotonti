<?php
/**
 * Russian Language File for the Plugin Pay Orders in Market
 *
 * payordersmarket plugin for Cotonti 0.9.26, PHP 8.4+
 * Filename: payordersmarket.ru.lang.php
 * @package payordersmarket
 * @version 5.0.1
 * @author webitproff
 * @copyright Copyright (c) webitproff 2025 | https://github.com/webitproff
 * @license BSD
 */
 
defined('COT_CODE') or die('Wrong URL.');

/**
 * Module Config
 */
$L['cfg_warranty'] = 'Гарантийный срок (дней)';
$L['cfg_tax'] = 'Комиссия за продажи (%)';
$L['cfg_ordersperpage'] = 'Число заказов на странице';
$L['cfg_adminid'] = 'ID пользователя для зачисления комиссии';
$L['cfg_showneworderswithoutpayment'] = 'Показывать заказы ожидающие оплату';
$L['cfg_acceptzerocostorders'] = array('Разрешать покупку товаров с ценой 0 '.((!empty(Cot::$cfg) && is_array(Cot::$cfg['payments'])) ? Cot::$cfg['payments']['valuta'] : ''));
/**
 * Plugin Info
 */
$L['info_name'] = 'name Оплата заказов в Маркете';
$L['info_desc'] = 'Плагин для оплаты товаров/услуг опубликованных в магазине';
$L['info_notes'] = 'Позволяет оплачивать товары/услуги с указанной ценой. После оплаты Продавец уведомляется по email. При этом сумма за покупку резервируется на счету сайта на гарантийный срок (например 14 дней), чтобы обеспечить безопасность проведения подобного рода продаж через сайт.';


$L['payordersmarket_title'] = 'title Оплата заказов в Маркете';

$L['payordersmarket'] = 'Заказы в магазине';
$L['payordersmarket_adminTitle'] = 'adminTitle Управление заказами';
$L['payordersmarket_admin_home_all'] = 'Все заказы';
$L['payordersmarket_admin_home_claims'] = 'Проблемные заказы';
$L['payordersmarket_admin_home_done'] = 'Исполненные заказы';
$L['payordersmarket_admin_home_cancel'] = 'Отмененные заказы';

$L['payordersmarket_mysales'] = 'Мои продажи';
$L['payordersmarket_mypurchases'] = 'Мои покупки';

$L['payordersmarket_sales_title'] = 'Мои продажи';
$L['payordersmarket_purchases_title'] = 'Мои покупки';
$L['payordersmarket_empty'] = 'Заказов нет';

$L['payordersmarket_neworder_pay'] = 'Оплатить';

$L['payordersmarket_neworder_button'] = 'Купить сейчас';
$L['payordersmarket_neworder_title'] = 'Оформление заказа';
$L['payordersmarket_neworder_product'] = 'Наименование товара';
$L['payordersmarket_neworder_count'] = 'Количество';
$L['payordersmarket_neworder_comment'] = 'Комментарий к заказу';
$L['payordersmarket_neworder_total'] = 'Итого к оплате';
$L['payordersmarket_neworder_email'] = 'Email';

$L['payordersmarket_neworder_error_count'] = 'Не указано количество';
$L['payordersmarket_order_error_claimtext'] = 'Не заполнен текст жалобы';

$L['payordersmarket_orderInfo'] = 'Информация о заказе';
$L['payordersmarket_product'] = 'Наименование товара';
$L['payordersmarket_count'] = 'Количество';
$L['payordersmarket_comment'] = 'Комментарий к заказу';
$L['payordersmarket_cost'] = 'Сумма заказа';
$L['payordersmarket_paid'] = 'Дата оплаты';
$L['payordersmarket_warranty'] = 'Гарантийный срок';

$L['payordersmarket_buyers'] = 'Покупатели';

$L['payordersmarket_done_payments_desc'] = 'Выплата по заказу № {$order_id} ({$product_title})';
$L['payordersmarket_tax_payments_desc'] = 'Доход с продажи по заказу № {$order_id} ({$product_title})';

$L['payordersmarket_paid_mail_toseller_header'] = 'Новый заказ № {$order_id} ({$product_title})';
$L['payordersmarket_paid_mail_toseller_body'] = 'Поздравляем! Пользователь {$user_name}, оформил и оплатил заказ № {$order_id} ([{$product_id}] {$product_title}). Если у покупателя не будет претензий к приобретенному товару/услуге, то по истечению гарантийного срока ({$warranty} дней) на ваш счет поступит оплата в размере {$summ} с учетом комиссии сервиса {$tax}%. Подробности заказа смотрите по ссылке:  {$link}';

$L['payordersmarket_paid_mail_tocustomer_header'] = 'Заказ № {$order_id} оплачен';
$L['payordersmarket_paid_mail_tocustomer_body'] = 'Поздравляем! Вы оплатили заказ № {$order_id} ([{$product_id}] {$product_title}) на сумму {$cost}. Подробности заказа смотрите по ссылке:  {$link}';

$L['payordersmarket_done_mail_toseller_header'] = 'Выплата по заказу № {$order_id} ({$product_title})';
$L['payordersmarket_done_mail_toseller_body'] = 'Поздравляем! На ваш счет поступила оплата по заказу № {$order_id} ([{$product_id}] {$product_title}) в размере {$summ} с учетом комиссии сервиса {$tax}%. Подробности заказа смотрите по ссылке: {$link}';

$L['payordersmarket_sales_paidorders'] = 'Оплаченные заказы';
$L['payordersmarket_sales_doneorders'] = 'Исполненные заказы';
$L['payordersmarket_sales_claimorders'] = 'Проблемные заказы';
$L['payordersmarket_sales_cancelorders'] = 'Отмененные заказы';

$L['payordersmarket_purchases_paidorders'] = 'Оплаченные покупки';
$L['payordersmarket_purchases_doneorders'] = 'Исполненные покупки';
$L['payordersmarket_purchases_claimorders'] = 'Проблемные покупки';
$L['payordersmarket_purchases_cancelorders'] = 'Отмененные покупки';
$L['payordersmarket_purchases_new'] = 'Ожидают оплаты';

$L['payordersmarket_status_paid'] = 'Оплаченный';
$L['payordersmarket_status_done'] = 'Исполненный';
$L['payordersmarket_status_claim'] = 'Проблемный';
$L['payordersmarket_status_cancel'] = 'Отмененный';
$L['payordersmarket_status_new'] = 'Новый';

$L['payordersmarket_addclaim_title'] = 'Подача жалобы по заказу';
$L['payordersmarket_addclaim_button'] = 'Подать жалобу в арбитраж';
$L['payordersmarket_claim_title'] = 'Жалоба';
$L['payordersmarket_claim_accept'] = 'Отменить заказ';
$L['payordersmarket_claim_cancel'] = 'Отказать в жалобе';

$L['payordersmarket_claim_payments_seller_desc'] = 'Выплата за заказ №{$order_id} ([ID {$product_id}] {$product_title}), согласно решению администрации сайта.';
$L['payordersmarket_claim_payments_customer_desc'] = 'Возврат за заказ №{$order_id} ([ID {$product_id}] {$product_title}), согласно решению администрации сайта.';

$L['payordersmarket_claim_error_cost'] = 'Сумма выплат не соответствует общей стоимости заказа';

$L['payordersmarket_addclaim_mail_toseller_header'] = 'Жалоба по заказу № {$order_id}';
$L['payordersmarket_addclaim_mail_toseller_body'] = 'Покупатель подал жалобу по заказу № {$order_id} ([ID {$product_id}] {$product_title}). Подробность смотрите по ссылке: {$link}';

$L['payordersmarket_addclaim_mail_toadmin_header'] = 'Жалоба по заказу № {$order_id}';
$L['payordersmarket_addclaim_mail_toadmin_body'] = 'Покупатель подал жалобу по заказу № {$order_id} ([ID {$product_id}] {$product_title}). Подробность смотрите по ссылке: {$link}';

$L['payordersmarket_acceptclaim_mail_toseller_header'] = 'Отмена заказа № {$order_id}';
$L['payordersmarket_acceptclaim_mail_toseller_body'] = 'Заказ № {$order_id} ([ID {$product_id}] {$product_title}) отменен в связи с тем, что покупатель подал жалобу. Подробности смотрите по ссылке: {$link}';

$L['payordersmarket_acceptclaim_mail_tocustomer_header'] = 'Отмена заказа № {$order_id}';
$L['payordersmarket_acceptclaim_mail_tocustomer_body'] = 'Заказ № {$order_id} ([ID {$product_id}] {$product_title}) отменен в связи с вашей жалобой. Подробности смотрите по ссылке: {$link}';

$L['payordersmarket_cancelclaim_mail_tocustomer_header'] = 'Жалобы по заказу № {$order_id} отклонена';
$L['payordersmarket_cancelclaim_mail_tocustomer_body'] = 'Ваша жалоба по заказу № {$order_id} ([ID {$product_id}] {$product_title}) отклонена администрацией сайта. Подробности смотрите по ссылке: {$link}';

$L['payordersmarket_file'] = 'Файлы для продажи';
$L['payordersmarket_file_for_download'] = 'Файлы для скачивания';
$L['payordersmarket_file_download'] = 'Скачать';

$L['payordersmarket_order_number'] = 'Заказ № ';
$L['payordersmarket_order_cost'] = 'Сумма';
$L['payordersmarket_order_date'] = 'Дата заказа';
$L['payordersmarket_order_paid'] = 'Дата оплаты';
$L['payordersmarket_order_status'] = 'Статус';
$L['payordersmarket_order_market_item_title'] = 'Товар';
$L['payordersmarket_order_seller_user'] = 'Продавец';
