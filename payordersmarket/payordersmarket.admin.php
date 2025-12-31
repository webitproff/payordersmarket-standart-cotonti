<?php
/**
 * [BEGIN_COT_EXT]
 * Hooks=tools
 * [END_COT_EXT]
 */
// Указываем, что плагин работает как инструмент админки в Cotonti

/**
 * Admin Page File for the Plugin Pay Orders in Market
 *
 * payordersmarket plugin for Cotonti 0.9.26, PHP 8.4+
 * Filename: payordersmarket.admin.php
 * @package payordersmarket
 * @version 5.0.1
 * @author webitproff
 * @copyright Copyright (c)  webitproff 2025 | https://github.com/webitproff
 * @license BSD
 */
// Документируем метаданные плагина: название, версия, автор, копирайт, лицензия

// Проверяем, определена ли константа COT_CODE, иначе выдаём ошибку "Wrong URL"
defined('COT_CODE') or die('Wrong URL');

// Проверяем права доступа пользователя для плагина payordersmarket (чтение, запись, админ)
list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = cot_auth('plug', 'payordersmarket', 'RWA');
// Блокируем выполнение, если пользователь не админ
cot_block($usr['isadmin']);

// Подключаем файл конфигурации и функций плагина payordersmarket
require_once cot_incfile('payordersmarket', 'plug');
// Подключаем файл модуля market для работы с товарами
require_once cot_incfile('market', 'module');
// Подключаем файл модуля payments для работы с платежами
require_once cot_incfile('payments', 'module');
// Подключаем файл для работы с дополнительными полями
require_once cot_incfile('extrafields');

// регистрируем нашу таблицу
Cot::$db->registerTable('payordersmarket');



// Извлекаем параметр 'status' из GET-запроса (например, ?status=paid), ожидая алфавитно-цифровую строку
$status = cot_import('status', 'G', 'ALP');

// Повторно проверяем права доступа пользователя для плагина payordersmarket
list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = cot_auth('plug', 'payordersmarket');
// Блокируем выполнение, если пользователь не админ
cot_block($usr['isadmin']);

// Проверяем, задано ли количество заказов на страницу в конфигурации плагина
if($cfg['plugin']['payordersmarket']['ordersperpage'] > 0)
{
	// Извлекаем параметры пагинации: номер страницы (pn), смещение (d) и URL-параметр (d_url)
	list($pn, $d, $d_url) = cot_import_pagenav('d', $cfg['plugin']['payordersmarket']['ordersperpage']);
}

/* === Hook === */
// Получаем список плагинов для хука 'payordersmarket.admin.first'
$extp = cot_getextplugins('payordersmarket.admin.first');
// Подключаем каждый плагин из хука
foreach ($extp as $pl)
{
	// Включаем файл плагина
	include $pl;
}
/* ===== */

// Устанавливаем заголовок страницы из языкового файла
$out['subtitle'] = $L['payordersmarket_sales_title'];
// Добавляем мета-тег noindex в заголовок страницы
$out['head'] .= $R['code_noindex'];

// присваиваем шаблону имя части или локации расширения
$tpl_PartExt = 'admin';

// Загружаем шаблон для админки плагина payordersmarket
$mskin = cot_tplfile(['payordersmarket', $tpl_PartExt], 'plug');

// объект шаблона создаем после загрузки всего, что "прицепилось" на "main"

/* === Hook === */
// Получаем список плагинов для хука 'payordersmarket.admin.main'
foreach (cot_getextplugins('payordersmarket.admin.main') as $pl)
{
	// Включаем файл плагина
	include $pl;
}
/* ===== */


$adminTitle = Cot::$L['payordersmarket_adminTitle'];
// Создаём объект шаблона XTemplate с указанным файлом шаблона
$t = new XTemplate($mskin);



// Инициализируем массив $where для условий SQL-запроса
$where = [];

// Проверяем параметр 'status' для фильтрации заказов
switch($status)
{
	// Если статус 'paid', добавляем условие для выборки заказов со статусом 'paid'
	case 'paid':
		$where['order_status'] = "o.order_status='paid'";
		break;

	// Если статус 'done', добавляем условие для выборки заказов со статусом 'done'
	case 'done':
		$where['order_status'] = "o.order_status='done'";
		break;

	// Если статус 'claim', добавляем условие для выборки заказов со статусом 'claim'
	case 'claim':
		$where['order_status'] = "o.order_status='claim'";
		break;

	// Если статус 'cancel', добавляем условие для выборки заказов со статусом 'cancel'
	case 'cancel':
		$where['order_status'] = "o.order_status='cancel'";
		break;

	// Если статус 'new', проверяем настройку showneworderswithoutpayment
	case 'new':
		// Если настройка включена, показываем заказы со статусом 'new'
		if($cfg['plugin']['payordersmarket']['showneworderswithoutpayment']) {
			$where['order_status'] = "o.order_status='new'";
		// Если настройка выключена, исключаем заказы со статусом 'new'
		} else {
			$where['order_status'] = "o.order_status!='new'";
		}
		break;

	// Для всех остальных случаев (status не указан или некорректен)
	default:
		// Если настройка showneworderswithoutpayment выключена, исключаем заказы со статусом 'new'
		if(!$cfg['plugin']['payordersmarket']['showneworderswithoutpayment']) {
			$where['order_status'] = "o.order_status!='new'";
		}
		break;
}

// Устанавливаем порядок сортировки по дате заказа (по убыванию)
$order['date'] = 'o.order_date DESC';

/* === Hook === */
// Получаем список плагинов для хука 'payordersmarket.admin.query'
foreach (cot_getextplugins('payordersmarket.admin.query') as $pl)
{
	// Включаем файл плагина
	include $pl;
}
/* ===== */

// Формируем строку условий SQL-запроса, объединяя элементы массива $where через AND
$where = ($where) ? 'WHERE ' . implode(' AND ', $where) : '';
// Формируем строку сортировки SQL-запроса, объединяя элементы массива $order через запятую
$order = ($order) ? 'ORDER BY ' . implode(', ', $order) : '';
// Формируем лимит для пагинации, если задано количество заказов на страницу
$query_limit = ($cfg['plugin']['payordersmarket']['ordersperpage'] > 0) ? "LIMIT $d, ".$cfg['plugin']['payordersmarket']['ordersperpage'] : '';

// Получаем общее количество заказов для пагинации
$totalitems = $db->query("SELECT COUNT(*) FROM $db_payordersmarket AS o
	LEFT JOIN $db_market AS m ON o.order_pid=m.fieldmrkt_id
	" . $where . "")->fetchColumn();

// Выполняем SQL-запрос для получения списка заказов с учётом условий, сортировки и лимита
$sql = $db->query("SELECT * FROM $db_payordersmarket AS o
	LEFT JOIN $db_market AS m ON o.order_pid=m.fieldmrkt_id
	" . $where . "
	" . $order . "
	" . $query_limit . "");

// Формируем данные для пагинации
$pagenav = cot_pagenav('admin', 'm=other&p=payordersmarket&status=' . $status, $d, $totalitems, $cfg['plugin']['payordersmarket']['ordersperpage']);

// Передаем данные пагинации в шаблон
$t->assign(array(
	// Общее количество заказов
	"PAGENAV_COUNT" => $totalitems,
	// HTML-код пагинации (страницы)
	"PAGENAV_PAGES" => $pagenav['main'],
	// Ссылка на предыдущую страницу
	"PAGENAV_PREV" => $pagenav['prev'],
	// Ссылка на следующую страницу
	"PAGENAV_NEXT" => $pagenav['next'],
));

/* === Hook === */
// Получаем список плагинов для хука 'payordersmarket.admin.loop'
$extp = cot_getextplugins('payordersmarket.admin.loop');
/* ===== */

// Перебираем результаты SQL-запроса
while ($payOrder = $sql->fetch())
{
	// Передаём в шаблон теги для товара, связанного с заказом
	$t->assign(cot_generate_markettags($payOrder, 'ORDER_ROW_MARKET_'));
	// Передаём в шаблон теги для продавца
	$t->assign(cot_generate_usertags($payOrder['order_seller'], 'ORDER_ROW_SELLER_'));

	// Проверяем, есть ли ID покупателя
	if($payOrder['order_userid'] > 0)
	{
		// Передаём в шаблон теги для покупателя, если он зарегистрирован
		$t->assign(cot_generate_usertags($payOrder['order_userid'], 'ORDER_ROW_CUSTOMER_'));
	}

	// Передаём в шаблон данные о заказе
	$t->assign(array(
		// ID заказа
		"ORDER_ROW_ID" => $payOrder['order_id'],
		// URL страницы заказа
		"ORDER_ROW_URL" => cot_url('payordersmarket','m=order&id='.$payOrder['order_id']),
		// Количество товаров в заказе
		"ORDER_ROW_COUNT" => $payOrder['order_count'],
		// Стоимость заказа
		"ORDER_ROW_COST" => $payOrder['order_cost'],
		// Комментарий к заказу
		"ORDER_ROW_COMMENT" => $payOrder['order_text'],
		// Email покупателя
		"ORDER_ROW_EMAIL" => $payOrder['order_email'],
		// Дата создания заказа
		"ORDER_ROW_DATE" => $payOrder['order_date'],
		// Дата оплаты заказа
		"ORDER_ROW_PAID" => $payOrder['order_paid'],
		// Статус заказа
		"ORDER_ROW_STATUS" => $payOrder['order_status'],
		// Дата окончания гарантийного срока (время оплаты + гарантийный срок в секундах)
		"ORDER_ROW_WARRANTYDATE" => $payOrder['order_paid'] + $cfg['plugin']['payordersmarket']['warranty']*60*60*24,
	));

	/* === Hook - Part2 : Include === */
	// Подключаем плагины для хука 'payordersmarket.admin.loop'
	foreach ($extp as $pl)
	{
		// Включаем файл плагина
		include $pl;
	}
	/* ===== */

	// Обрабатываем блок шаблона для текущего заказа
	$t->parse("MAIN.ORDER_ROW");
}

/* === Hook === */
// Получаем список плагинов для хука 'payordersmarket.admin.tags'
foreach (cot_getextplugins('payordersmarket.admin.tags') as $pl)
{
	// Включаем файл плагина
	include $pl;
}
/* ===== */

$t->assign(cot_generatePaginationTags($pagenav));

cot_display_messages($t);
// Обрабатываем основной блок шаблона
$t->parse("MAIN");
// Добавляем результат обработки шаблона в переменную $pluginBody
$pluginBody .= $t->text("MAIN");