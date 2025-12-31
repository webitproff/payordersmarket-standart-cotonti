<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=admin.home.sidepanel
Order=5
[END_COT_EXT]
==================== */

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

require_once cot_incfile('payordersmarket', 'plug');

$tt = new XTemplate(cot_tplfile('payordersmarket.admin.home', 'plug', true));

$payordersmarketcount = $db->query("SELECT COUNT(*) FROM $db_payordersmarket WHERE 1");
$payordersmarketcount = $payordersmarketcount->fetchColumn();

$payordersmarketclaims = $db->query("SELECT COUNT(*) FROM $db_payordersmarket WHERE order_status='claim'");
$payordersmarketclaims = $payordersmarketclaims->fetchColumn();

$payordersmarketdone = $db->query("SELECT COUNT(*) FROM $db_payordersmarket WHERE order_status='done'");
$payordersmarketdone = $payordersmarketdone->fetchColumn();

$tt->assign(array(
	'ADMIN_HOME_ORDERS_URL' => cot_url('admin', 'm=other&p=payordersmarket'),
	'ADMIN_HOME_ORDERS_COUNT' => $payordersmarketcount,
	'ADMIN_HOME_CLAIMS_URL' => cot_url('admin', 'm=other&p=payordersmarket&status=claim'),
	'ADMIN_HOME_CLAIMS_COUNT' => $payordersmarketclaims,
	'ADMIN_HOME_DONE_URL' => cot_url('admin', 'm=other&p=payordersmarket&status=done'),
	'ADMIN_HOME_DONE_COUNT' => $payordersmarketdone,
));

$tt->parse('MAIN');

$line = $tt->text('MAIN');
