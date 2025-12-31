<?php

defined('COT_CODE') or die('Wrong URL');

$id = cot_import('id', 'G', 'INT');

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = cot_auth('plug', 'payordersmarket');
cot_block($usr['auth_read']);

if ($id > 0)
{
	$sql = $db->query("SELECT * FROM $db_payordersmarket  AS o
		LEFT JOIN $db_market AS m ON m.fieldmrkt_id=o.order_pid
		WHERE order_id=".$id." LIMIT 1");
}

if (!$id || !$sql || $sql->rowCount() == 0)
{
	cot_die_message(404, TRUE);
}
$payOrder = $sql->fetch();

cot_block($payOrder['order_cost'] > 0 && $payOrder['order_status'] == 'new' && $payOrder['order_userid'] == $usr['id']);

/* === Hook === */
$extp = cot_getextplugins('payordersmarket.pay.first');
foreach ($extp as $pl)
{
	include $pl;
}
/* ===== */

$orderid = $payOrder['order_id'];

if(!empty($payOrder['order_email']) && $usr['id'] == 0)
{
	$key = sha1($payOrder['order_email'].'&'.$orderid);
}

$options['code'] = $orderid;
$options['desc'] = $item['fieldmrkt_title'];

if ($db->fieldExists($db_payments, "pay_redirect"))
{
	$options['redirect'] = $cfg['mainurl'].'/'.cot_url('payordersmarket', 'id='.$orderid.'&key='.$key, '', true);
}

/* === Hook === */
foreach (cot_getextplugins('payordersmarket.neworder.add.done') as $pl)
{
	include $pl;
}
/* ===== */

cot_payments_create_order('payordersmarket', $payOrder['order_cost'], $options);
exit;
