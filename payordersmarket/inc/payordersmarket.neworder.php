<?php

/**
 * payordersmarket plugin
 *
 * @package payordersmarket
 * @version 1.0.0
 * @author CMSWorks Team
 * @copyright Copyright (c) CMSWorks.ru
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL');

$pid = cot_import('pid', 'G', 'INT');

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = cot_auth('plug', 'payordersmarket');
cot_block($usr['auth_read']);

if ($pid > 0)
{
	$sql = $db->query("SELECT m.*, u.* FROM $db_market AS m LEFT JOIN $db_users AS u ON u.user_id=m.fieldmrkt_ownerid WHERE fieldmrkt_id=".$pid." LIMIT 1");
}

if (!$pid || !$sql || $sql->rowCount() == 0)
{
	cot_die_message(404, TRUE);
}
$item = $sql->fetch();

cot_block(($item['fieldmrkt_costdflt'] > 0 || $cfg['plugin']['payordersmarket']['acceptzerocostorders']) && $item['fieldmrkt_state'] == 0);

/* === Hook === */
$extp = cot_getextplugins('payordersmarket.neworder.first');
foreach ($extp as $pl)
{
	include $pl;
}
/* ===== */

if ($a == 'add')
{
	cot_shield_protect();

	/* === Hook === */
	foreach (cot_getextplugins('payordersmarket.neworder.add.first') as $pl)
	{
		include $pl;
	}
	/* ===== */

	$rorder['order_count'] = cot_import('rcount', 'P', 'INT');
	$rorder['order_text'] = cot_import('rtext', 'P', 'TXT');
	$email = cot_import('remail', 'P','TXT', 100, TRUE);

	/* === Hook === */
	foreach (cot_getextplugins('payordersmarket.neworder.add.import') as $pl)
	{
		include $pl;
	}
	/* ===== */

	//cot_check(empty($rorder['order_count']), 'payordersmarket_neworder_error_count', 'rcount');
	if (!cot_check_email($email) && $usr['id'] == 0) cot_error('aut_emailtooshort', 'remail');

	if(!empty($email) && $usr['id'] == 0)
	{
		$rorder['order_userid'] = $db->query("SELECT user_id FROM $db_users WHERE user_email = ? LIMIT 1", array($email))->fetchColumn();
	}
	else
	{
		$rorder['order_userid'] = $usr['id'];
	}

	/* === Hook === */
	foreach (cot_getextplugins('payordersmarket.neworder.add.error') as $pl)
	{
		include $pl;
	}
	/* ===== */

	$rorder['order_count'] = ($rorder['order_count'] > 0) ? $rorder['order_count'] : 1;

	if (!cot_error_found())
	{
		$rorder['order_pid'] = $pid;
		$rorder['order_date'] = $sys['now'];
		$rorder['order_status'] = 'new';
		$rorder['order_title'] = $item['fieldmrkt_title'];
		$rorder['order_seller'] = $item['fieldmrkt_ownerid'];
		$rorder['order_cost'] = $item['fieldmrkt_costdflt']*$rorder['order_count'];
		$rorder['order_email'] = $email;

		if ($db->insert($db_payordersmarket, $rorder))
		{
			$orderid = $db->lastInsertId();

			if(!empty($rorder['order_email']) && $usr['id'] == 0)
			{
				$key = sha1($rorder['order_email'].'&'.$orderid);
			}

			if($rorder['order_cost'] > 0) {
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

				cot_payments_create_order('payordersmarket', $rorder['order_cost'], $options);
			} elseif($cfg['plugin']['payordersmarket']['acceptzerocostorders']) {
				cot_redirect(cot_url('payordersmarket', 'id='.$orderid.'&key='.$key, '', true));
			} else {
				cot_redirect(cot_url('payments', 'm=error&msg=3', '', true));
			}
		}
	}

	cot_redirect(cot_url('payordersmarket', 'm=neworder&pid=' . $pid, '', true));
	exit;
}

$out['subtitle'] = $L['payordersmarket_neworder_title'];
$out['head'] .= $R['code_noindex'];

$mskin = cot_tplfile(array('payordersmarket', 'neworder', $structure['market'][$item['fieldmrkt_cat']]['tpl']), 'plug');

/* === Hook === */
foreach (cot_getextplugins('payordersmarket.neworder.main') as $pl)
{
	include $pl;
}
/* ===== */

$t = new XTemplate($mskin);

$catpatharray[] = array(cot_url('market'), $L['market']);
$catpatharray = array_merge($catpatharray, cot_structure_buildpath('market', $item['fieldmrkt_cat']));
$catpatharray[] = array(cot_url('market', 'id='.$pid), $item['fieldmrkt_title']);
$catpatharray[] = array('', $L['payordersmarket_neworder_title']);

$catpath = cot_breadcrumbs($catpatharray, $cfg['homebreadcrumb'], true);

$t->assign(array(
	"BREADCRUMBS" => $catpath,
));

// Error and message handling
cot_display_messages($t);
// Установка длины обрезки текста для списка товаров из конфигурации по умолчанию
$itemListTruncateText = (int) Cot::$cfg['market']['cat___default']['markettruncatetext'];

// Проверка, указана ли категория и есть ли для неё настройка обрезки текста
if (
    !empty($c)
    && !empty(Cot::$cfg['market']['cat_' . $c])
    && isset(Cot::$cfg['market']['cat_' . $c]['markettruncatetext'])
    && ((string) Cot::$cfg['market']['cat_' . $c]['markettruncatetext'] !== '')
) {
    // Установка длины обрезки текста для текущей категории
    $itemListTruncateText = (int) Cot::$cfg['market']['cat_' . $c]['markettruncatetext'];
}

$t->assign(cot_generate_markettags($item, 'NEWORDER_MARKET_', $itemListTruncateText, $usr['isadmin'], $cfg['homebreadcrumb']));

$t->assign(array(
	"NEWORDER_FORM_SEND" => cot_url('payordersmarket', 'm=neworder&pid='.$pid.'&a=add'),
	"NEWORDER_FORM_COUNT" => cot_inputbox('text', 'rcount', 1, 'size="10" id="count"'),
	"NEWORDER_FORM_COMMENT" => cot_textarea('rtext', '', 5, 60),
	"NEWORDER_FORM_EMAIL" => cot_inputbox('text', 'remail'),
));

/* === Hook === */
foreach (cot_getextplugins('payordersmarket.neworder.tags') as $pl)
{
	include $pl;
}
/* ===== */
