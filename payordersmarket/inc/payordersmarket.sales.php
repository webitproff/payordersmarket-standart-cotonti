<?php

/**
 * payordersmarket plugin
 *
 * @package payordersmarket
 * @version 5.0.1
 * @author webitproff
 * @copyright Copyright (c) webitproff 2025 | https://github.com/webitproff
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL');

$status = cot_import('status', 'G', 'ALP');

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = cot_auth('plug', 'payordersmarket');
cot_block($usr['id'] > 0 && $usr['auth_read']);

if($cfg['plugin']['payordersmarket']['ordersperpage'] > 0)
{
	list($pn, $d, $d_url) = cot_import_pagenav('d', $cfg['plugin']['payordersmarket']['ordersperpage']);
}

/* === Hook === */
$extp = cot_getextplugins('payordersmarket.sales.first');
foreach ($extp as $pl)
{
	include $pl;
}
/* ===== */

$out['subtitle'] = $L['payordersmarket_sales_title'];
$out['head'] .= $R['code_noindex'];

$mskin = cot_tplfile(array('payordersmarket', 'sales'), 'plug');

/* === Hook === */
foreach (cot_getextplugins('payordersmarket.sales.main') as $pl)
{
	include $pl;
}
/* ===== */

$t = new XTemplate($mskin);

$where['userid'] = "order_seller=" . $usr['id'];

switch($status)
{

	case 'paid':
		$where['order_status'] = "o.order_status='paid'";
		break;

	case 'done':
		$where['order_status'] = "o.order_status='done'";
		break;

	case 'claim':
		$where['order_status'] = "o.order_status='claim'";
		break;

	case 'cancel':
		$where['order_status'] = "o.order_status='cancel'";
		break;

	default:
		$where['order_status'] = "o.order_status!='new'";
		break;
}

$order['date'] = 'o.order_date DESC';

/* === Hook === */
foreach (cot_getextplugins('payordersmarket.sales.query') as $pl)
{
	include $pl;
}
/* ===== */

$where = ($where) ? 'WHERE ' . implode(' AND ', $where) : '';
$order = ($order) ? 'ORDER BY ' . implode(', ', $order) : '';
$query_limit = ($cfg['plugin']['payordersmarket']['ordersperpage'] > 0) ? "LIMIT $d, ".$cfg['plugin']['payordersmarket']['ordersperpage'] : '';

$totalitems = $db->query("SELECT COUNT(*) FROM $db_payordersmarket AS o
	LEFT JOIN $db_market AS m ON o.order_pid=m.fieldmrkt_id
	" . $where . "")->fetchColumn();

$sql = $db->query("SELECT * FROM $db_payordersmarket AS o
	LEFT JOIN $db_market AS m ON o.order_pid=m.fieldmrkt_id
	" . $where . "
	" . $order . "
	" . $query_limit . "");

$pagenav = cot_pagenav('payordersmarket', 'm=sales&status=' . $status, $d, $totalitems, $cfg['plugin']['payordersmarket']['ordersperpage']);

$t->assign(array(
	"PAGENAV_COUNT" => $totalitems,
	"PAGENAV_PAGES" => $pagenav['main'],
	"PAGENAV_PREV" => $pagenav['prev'],
	"PAGENAV_NEXT" => $pagenav['next'],
));

$catpatharray[] = array(cot_url('market'), $L['market']);
$catpatharray[] = array('', $L['payordersmarket_sales_title']);

$catpath = cot_breadcrumbs($catpatharray, $cfg['homebreadcrumb'], true);

$t->assign(array(
	"BREADCRUMBS" => $catpath,
));

/* === Hook === */
$extp = cot_getextplugins('payordersmarket.sales.loop');
/* ===== */

while ($payOrder = $sql->fetch())
{
	$t->assign(cot_generate_markettags($payOrder, 'ORDER_ROW_MARKET_'));

	if($payOrder['order_userid'] > 0)
	{
		$t->assign(cot_generate_usertags($payOrder['order_userid'], 'ORDER_ROW_CUSTOMER_'));
	}

	$t->assign(array(
		"ORDER_ROW_ID" => $payOrder['order_id'],
		"ORDER_ROW_URL" => cot_url('payordersmarket','m=order&id='.$payOrder['order_id']),
		"ORDER_ROW_COUNT" => $payOrder['order_count'],
		"ORDER_ROW_COST" => $payOrder['order_cost'],
		"ORDER_ROW_COMMENT" => $payOrder['order_text'],
		"ORDER_ROW_EMAIL" => $payOrder['order_email'],
		"ORDER_ROW_DATE" => $payOrder['order_date'],
		"ORDER_ROW_PAID" => $payOrder['order_paid'],
		"ORDER_ROW_STATUS" => $payOrder['order_status'],
		"ORDER_ROW_LOCALSTATUS" => $L['payordersmarket_status_'.$payOrder['order_status']],
		"ORDER_ROW_WARRANTYDATE" => $payOrder['order_paid'] + $cfg['plugin']['payordersmarket']['warranty']*60*60*24,
	));

	/* === Hook - Part2 : Include === */
	foreach ($extp as $pl)
	{
		include $pl;
	}
	/* ===== */

	$t->parse("MAIN.ORDER_ROW");
}

/* === Hook === */
foreach (cot_getextplugins('payordersmarket.sales.tags') as $pl)
{
	include $pl;
}
/* ===== */
