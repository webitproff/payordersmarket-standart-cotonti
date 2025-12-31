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

require_once cot_incfile('market', 'module');
require_once cot_incfile('payordersmarket', 'plug');

global $db_market, $cfg;

// See function cot_extrafield_add in system/extrafields.php

cot_extrafield_add($db_market, 'paidfile', 'file', $R['input_file'],'zip,rar','','','', '','datas/marketfiles');

if(!file_exists('datas/marketfiles')) mkdir('datas/marketfiles');