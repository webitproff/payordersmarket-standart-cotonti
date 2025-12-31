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

$id = cot_import('id', 'G', 'INT');
$key = cot_import('key', 'G', 'TXT');

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = cot_auth('plug', 'payordersmarket');
cot_block($usr['auth_read']);

if ($id > 0)
{
	$sql = $db->query("SELECT * FROM $db_payordersmarket  AS o
		LEFT JOIN $db_market AS m ON m.fieldmrkt_id=o.order_pid
		WHERE ".(!$cfg['plugin']['payordersmarket']['showneworderswithoutpayment'] ? "order_status!='new' AND" : "")." order_id=".$id." LIMIT 1");
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

/* === Hook === */
$extp = cot_getextplugins('payordersmarket.order.first');
foreach ($extp as $pl)
{
	include $pl;
}
/* ===== */

$out['subtitle'] = $L['payordersmarket_title'];
$out['head'] .= $R['code_noindex'];

$mskin = cot_tplfile(array('payordersmarket', 'order', $structure['market'][$payOrder['fieldmrkt_cat']]['tpl']), 'plug');

/* === Hook === */
foreach (cot_getextplugins('payordersmarket.order.main') as $pl)
{
	include $pl;
}
/* ===== */

$t = new XTemplate($mskin);

$catpatharray[] = array(cot_url('market'), $L['market']);
//$catpatharray = array_merge($catpatharray, cot_structure_buildpath('market', $item['fieldmrkt_cat']));
//$catpatharray[] = array(cot_url('market', 'id='.$id), $payOrder['fieldmrkt_title']);
$catpatharray[] = array('', $L['payordersmarket_title']);

$catpath = cot_breadcrumbs($catpatharray, $cfg['homebreadcrumb'], true);

$t->assign(array(
	"BREADCRUMBS" => $catpath,
));

// Error and message handling
cot_display_messages($t);

$t->assign(cot_generate_markettags($payOrder['order_pid'], 'ORDER_MARKET_', $usr['isadmin'], $cfg['homebreadcrumb']));
$t->assign(cot_generate_usertags($payOrder['order_seller'], 'ORDER_SELLER_'));
$t->assign(cot_generate_usertags($payOrder['order_userid'], 'ORDER_CUSTOMER_'));

$t->assign(array(
	"ORDER_ID" => $payOrder['order_id'],
	"ORDER_COUNT" => $payOrder['order_count'],
	"ORDER_COST" => $payOrder['order_cost'],
	"ORDER_TITLE" => $payOrder['order_title'],
	"ORDER_COMMENT" => $payOrder['order_text'],
	"ORDER_EMAIL" => $payOrder['order_email'],
	"ORDER_PAID" => $payOrder['order_paid'],
	"ORDER_DONE" => $payOrder['order_done'],
	"ORDER_STATUS" => $payOrder['order_status'],
	"ORDER_DOWNLOAD" => (in_array($payOrder['order_status'], ['paid', 'done']) && !empty($payOrder['fieldmrkt_paidfile']) && file_exists($cfg['plugin']['payordersmarket']['filepath'].'/'.$payOrder['fieldmrkt_paidfile'])) ? cot_url('plug', 'r=payordersmarket&m=download&id='.$payOrder['order_id'].'&key='.$key) : '',
	"ORDER_LOCALSTATUS" => $L['payordersmarket_status_'.$payOrder['order_status']],
	"ORDER_WARRANTYDATE" => $payOrder['order_paid'] + $cfg['plugin']['payordersmarket']['warranty']*60*60*24,
));

if($payOrder['order_status'] == 'claim')
{
	$t->assign(array(
		"CLAIM_DATE" => $payOrder['order_claim'],
		"CLAIM_TEXT" => $payOrder['order_claimtext'],
	));

	/* === Hook === */
	foreach (cot_getextplugins('payordersmarket.order.claim') as $pl)
	{
		include $pl;
	}
	/* ===== */

	if($usr['isadmin'])
	{
		// Отменяем заказ, возвращаем оплату покупателю
		if($a == 'acceptclaim')
		{
			$rorder['order_cancel'] = $sys['now'];
			$rorder['order_status'] = 'cancel';

			if($db->update($db_payordersmarket, $rorder, 'order_id='.$id))
			{
				if($payOrder['order_userid'] > 0)
				{
					$payinfo['pay_userid'] = $payOrder['order_userid'];
					$payinfo['pay_area'] = 'balance';
					$payinfo['pay_code'] = 'market:'.$payOrder['order_id'];
					$payinfo['pay_summ'] = $payOrder['order_cost'];
					$payinfo['pay_cdate'] = $sys['now'];
					$payinfo['pay_pdate'] = $sys['now'];
					$payinfo['pay_adate'] = $sys['now'];
					$payinfo['pay_status'] = 'done';
					$payinfo['pay_desc'] = cot_rc($L['payordersmarket_claim_payments_customer_desc'],
						array(
							'product_title' => $payOrder['fieldmrkt_title'],
							'order_id' => $payOrder['order_id']
						)
					);

					$db->insert($db_payments, $payinfo);
				}

				$seller = $db->query("SELECT * FROM $db_users WHERE user_id=".$payOrder['order_seller'])->fetch();

				if($payOrder['order_userid'] > 0)
				{
					$customer = $db->query("SELECT * FROM $db_users WHERE user_id=".$payOrder['order_userid'])->fetch();
				}
				else
				{
					$customer['user_name'] = $payOrder['order_email'];
					$customer['user_email'] = $payOrder['order_email'];
				}

				// Уведопляем продавца об отмене заказа
				$rsubject = cot_rc($L['payordersmarket_acceptclaim_mail_toseller_header'], array('order_id' => $payOrder['order_id'], 'product_title' => $payOrder['fieldmrkt_title']));
				$rbody = cot_rc($L['payordersmarket_acceptclaim_mail_toseller_body'], array(
					'product_id' => $payOrder['fieldmrkt_id'],
					'product_title' => $payOrder['fieldmrkt_title'],
					'order_id' => $payOrder['order_id'],
					'sitename' => $cfg['maintitle'],
					'link' => COT_ABSOLUTE_URL . cot_url('payordersmarket', "id=" . $payOrder['order_id'], '', true)
				));
				cot_mail ($seller['user_email'], $rsubject, $rbody);

				// Уведопляем покупателя об отмене заказа
				$rsubject = cot_rc($L['payordersmarket_acceptclaim_mail_tocustomer_header'], array('order_id' => $payOrder['order_id'], 'product_title' => $payOrder['fieldmrkt_title']));
				$rbody = cot_rc($L['payordersmarket_acceptclaim_mail_tocustomer_body'], array(
					'product_id' => $payOrder['fieldmrkt_id'],
					'product_title' => $payOrder['fieldmrkt_title'],
					'order_id' => $payOrder['order_id'],
					'sitename' => $cfg['maintitle'],
					'link' => COT_ABSOLUTE_URL . cot_url('payordersmarket', "id=" . $payOrder['order_id'], '', true)
				));

				/* === Hook === */
				foreach (cot_getextplugins('payordersmarket.order.acceptclaim.done') as $pl)
				{
					include $pl;
				}
				/* ===== */

				cot_mail ($customer['user_email'], $rsubject, $rbody);

				cot_redirect(cot_url('payordersmarket', 'm=order&id=' . $id, '', true));
				exit;
			}

			cot_redirect(cot_url('payordersmarket', 'm=order&id=' . $id, '', true));
			exit;
		}

		// Отменяем жалобу
		if($a == 'cancelclaim')
		{
			$rorder['order_claim'] = 0;
			$rorder['order_status'] = 'paid';

			if($db->update($db_payordersmarket, $rorder, 'order_id='.$id))
			{
				$customer = $db->query("SELECT * FROM $db_users WHERE user_id=".$payOrder['order_userid'])->fetch();

				// Уведопляем покупателя об отклонении жалобы
				$rsubject = cot_rc($L['payordersmarket_cancelclaim_mail_tocustomer_header'], array('order_id' => $payOrder['order_id'], 'product_title' => $payOrder['fieldmrkt_title']));
				$rbody = cot_rc($L['payordersmarket_cancelclaim_mail_tocustomer_body'], array(
					'product_title' => $payOrder['fieldmrkt_title'],
					'order_id' => $payOrder['order_id'],
					'sitename' => $cfg['maintitle'],
					'link' => COT_ABSOLUTE_URL . cot_url('payordersmarket', "id=" . $payOrder['order_id'], '', true)
				));

				/* === Hook === */
				foreach (cot_getextplugins('payordersmarket.order.cancelclaim.done') as $pl)
				{
					include $pl;
				}
				/* ===== */

				cot_mail ($customer['user_email'], $rsubject, $rbody);
			}

			cot_redirect(cot_url('payordersmarket', 'm=order&id=' . $id, '', true));
			exit;
		}

		$t->parse('MAIN.CLAIM.ADMINCLAIM');
	}
	$t->parse('MAIN.CLAIM');
}

/* === Hook === */
foreach (cot_getextplugins('payordersmarket.order.tags') as $pl)
{
	include $pl;
}
/* ===== */
