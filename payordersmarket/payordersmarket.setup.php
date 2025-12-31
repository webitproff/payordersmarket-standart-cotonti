<?php

/* ====================
 * [BEGIN_COT_EXT]
 * Code=payordersmarket
 * Name=Pay Orders in Market
 * Category=Payments
 * Description=
 * Version=5.0.1
 * Date=Dec 10th, 2025
 * Author=
 * Copyright=Copyright (c)
 * Notes=
 * Auth_guests=
 * Lock_guests=12345A
 * Auth_members=RW
 * Lock_members=12345A
 * Requires_modules=payments,market
 * [END_COT_EXT]
 *
 * [BEGIN_COT_EXT_CONFIG]
 * warranty=01:string::14:Warranty period
 * tax=02:string::10:Selling commission
 * ordersperpage=03:select:0,1,2,3,4,5,10,15,20,25:5:Число заказов на странице
 * filepath=04:string::datas/marketfiles:File path
 * adminid=04:string::0:Admin id
 * showneworderswithoutpayment=05:radio::1:Show new orders without payment
 * acceptzerocostorders=06:radio::1:Accept orders with 0 price
 * [END_COT_EXT_CONFIG]
 */

/**
 * Setup & Config File for the Plugin Pay Orders in Market
 *
 * payordersmarket plugin for Cotonti 0.9.26, PHP 8.4+
 * Filename: payordersmarket.setup.php
 * @package payordersmarket
 * @version 5.0.1
 * @author webitproff
 * @copyright Copyright (c) webitproff 2025 | https://github.com/webitproff
 * @license BSD
 */

