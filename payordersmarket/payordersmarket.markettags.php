<?php

/**
 * [BEGIN_COT_EXT]
 * Hooks=markettags.main
 * [END_COT_EXT]
 */

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

global $db_payordersmarket;

$key = cot_import('key', 'G', 'TXT');

$payOrder = $db->query("SELECT * FROM $db_payordersmarket AS o
	LEFT JOIN $db_market AS m ON m.fieldmrkt_id=o.order_pid
	WHERE order_pid=".$item_data['fieldmrkt_id']." AND order_status!='new' AND order_userid=".$usr['id']." LIMIT 1")->fetch();

if(!empty($key)){
	$hash = sha1($payOrder['order_email'].'&'.$payOrder['order_id']);
}
if ($payOrder && ($usr['id'] > 0 || $usr['id'] == 0 && !empty($key) && $key == $hash)){
	$temp_array['ORDER_ID'] = $payOrder['order_id'];
	$temp_array['ORDER_URL'] = cot_url('payordersmarket', 'id='.$payOrder['order_id'].'&key='.$key);
	$temp_array['ORDER_COUNT'] = $payOrder['order_count'];
	$temp_array['ORDER_COST'] = $payOrder['order_cost'];
	$temp_array['ORDER_TITLE'] = $payOrder['order_title'];
	$temp_array['ORDER_COMMENT'] = $payOrder['order_text'];
	$temp_array['ORDER_EMAIL'] = $payOrder['order_email'];
	$temp_array['ORDER_PAID'] = $payOrder['order_paid'];
	$temp_array['ORDER_DONE'] = $payOrder['order_done'];
	$temp_array['ORDER_STATUS'] = $payOrder['order_status'];
	$temp_array['ORDER_DOWNLOAD'] = (in_array($payOrder['order_status'], ['paid', 'done']) && !empty($payOrder['fieldmrkt_paidfile']) && file_exists($cfg['plugin']['payordersmarket']['filepath'].'/'.$payOrder['fieldmrkt_paidfile'])) ? cot_url('plug', 'r=payordersmarket&m=download&id='.$payOrder['order_id'].'&key='.$key) : '';
	$temp_array['ORDER_LOCALSTATUS'] = $L['payordersmarket_status_'.$payOrder['order_status']];
	$temp_array['ORDER_WARRANTYDATE'] = $payOrder['order_paid'] + $cfg['plugin']['payordersmarket']['warranty']*60*60*24;
}