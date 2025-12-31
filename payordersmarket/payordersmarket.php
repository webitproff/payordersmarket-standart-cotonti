<?php

/**
 * [BEGIN_COT_EXT]
 * Hooks=standalone
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

defined('COT_CODE') && defined('COT_PLUG') or die('Wrong URL');

$env['location'] = 'payordersmarket';


require_once cot_incfile('payordersmarket', 'plug');
require_once cot_incfile('market', 'module');
require_once cot_incfile('payments', 'module');
require_once cot_incfile('extrafields');


// Mode choice
if (!in_array($m, ['sales', 'purchases', 'addclaim', 'pay'])) {
	// Извлекаем параметр 'id' из GET-запроса, ожидая целое число
$id = cot_import('id', 'G', 'INT');
	if (isset($_GET['id'])) {
		$m = 'order';
	} else {
		$m = 'neworder';
	}
}
// подключаем нужный файл режима плагина для фронтэнда
require_once cot_incfile('payordersmarket', 'plug', $m);

