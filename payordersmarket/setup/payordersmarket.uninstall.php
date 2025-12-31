<?php

/**
 * payordersmarket plugin
 *
 * @author 
 * @copyright Copyright (c)
 * @license BSD License
 **/
 
defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('market', 'module');

global $db_market;

cot_extrafield_remove($db_market, 'paidfile');
