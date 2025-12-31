<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=ajax
[END_COT_EXT]
==================== */

defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('market', 'module');
require_once cot_incfile('payordersmarket', 'plug');

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = cot_auth('plug', 'payordersmarket', 'RWA');
cot_block($usr['auth_read']);

$m = cot_import('m','G','ALP');
$id = cot_import('id','G','INT');
$key = cot_import('key', 'G', 'TXT');

if($m == 'download'){

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

	cot_block($usr['isadmin'] || $usr['id'] == $payOrder['order_userid'] || $usr['id'] == $payOrder['order_seller'] || !empty($key) && $usr['id'] == 0);

	if($usr['id'] == 0)
	{
		$hash = sha1($payOrder['order_email'].'&'.$payOrder['order_id']);
		cot_block($key == $hash);
	}
	
	$file = $cfg['plugin']['payordersmarket']['filepath'].'/'.$payOrder['fieldmrkt_paidfile'];
	
	if(file_exists($file) && ($payOrder['order_status'] == 'paid' || $payOrder['order_status'] == 'done') || $usr['isadmin'] || $usr['id'] == $payOrder['order_seller']){
		payordersmarket_file_download($file, $mimetype='application/octet-stream');
	}else{
		cot_block();
	}
}
