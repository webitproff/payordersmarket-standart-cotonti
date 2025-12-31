<?php
/**
 * English Language File for the Plugin Pay Orders in Market
 *
 * payordersmarket plugin for Cotonti 0.9.26, PHP 8.4+
 * Filename: payordersmarket.en.lang.php
 * @package payordersmarket
 * @version 5.0.1
 * @author webitproff
 * @copyright Copyright (c) webitproff 2025 | https://github.com/webitproff
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL.');

/**
 * Module Config
 */
$L['cfg_warranty'] = 'Warranty period (days)';
$L['cfg_tax'] = 'Sales commission (%)';
$L['cfg_ordersperpage'] = 'Orders per page';
$L['cfg_adminid'] = 'User ID to receive commission';
$L['cfg_showneworderswithoutpayment'] = 'Show orders awaiting payment';
$L['cfg_acceptzerocostorders'] = array(
    'Allow purchasing items with price 0 ' .
    ((!empty(Cot::$cfg) && is_array(Cot::$cfg['payments'])) ? Cot::$cfg['payments']['valuta'] : '')
);

/**
 * Plugin Info
 */
$L['info_name'] = 'name Market Order Payments';
$L['info_desc'] = 'Plugin for paying for goods/services published in the market';
$L['info_notes'] = 'Allows payment for goods/services with a specified price. After payment, the Seller is notified by email. The purchase amount is reserved on the site account for the warranty period (e.g. 14 days) to ensure secure transactions through the website.';

$L['payordersmarket_title'] = 'title Market Order Payments';

$L['payordersmarket'] = 'Market orders';
$L['payordersmarket_adminTitle'] = 'adminTitle Order management';
$L['payordersmarket_admin_home_all'] = 'All orders';
$L['payordersmarket_admin_home_claims'] = 'Disputed orders';
$L['payordersmarket_admin_home_done'] = 'Completed orders';
$L['payordersmarket_admin_home_cancel'] = 'Cancelled orders';

$L['payordersmarket_mysales'] = 'My sales';
$L['payordersmarket_mypurchases'] = 'My purchases';

$L['payordersmarket_sales_title'] = 'My sales';
$L['payordersmarket_purchases_title'] = 'My purchases';
$L['payordersmarket_empty'] = 'No orders';

$L['payordersmarket_neworder_pay'] = 'Pay';

$L['payordersmarket_neworder_button'] = 'Buy now';
$L['payordersmarket_neworder_title'] = 'Checkout';
$L['payordersmarket_neworder_product'] = 'Product name';
$L['payordersmarket_neworder_count'] = 'Quantity';
$L['payordersmarket_neworder_comment'] = 'Order comment';
$L['payordersmarket_neworder_total'] = 'Total to pay';
$L['payordersmarket_neworder_email'] = 'Email';

$L['payordersmarket_neworder_error_count'] = 'Quantity not specified';
$L['payordersmarket_order_error_claimtext'] = 'Claim text is not filled';

$L['payordersmarket_orderInfo'] = 'Order information';
$L['payordersmarket_product'] = 'Product name';
$L['payordersmarket_count'] = 'Quantity';
$L['payordersmarket_comment'] = 'Order comment';
$L['payordersmarket_cost'] = 'Order amount';
$L['payordersmarket_paid'] = 'Payment date';
$L['payordersmarket_warranty'] = 'Warranty period';

$L['payordersmarket_buyers'] = 'Buyers';

$L['payordersmarket_done_payments_desc'] = 'Payout for order #{$order_id} ({$product_title})';
$L['payordersmarket_tax_payments_desc'] = 'Sales revenue for order #{$order_id} ({$product_title})';

$L['payordersmarket_paid_mail_toseller_header'] = 'New order #{$order_id} ({$product_title})';
$L['payordersmarket_paid_mail_toseller_body'] =
    'Congratulations! User {$user_name} has placed and paid for order #{$order_id} ([{$product_id}] {$product_title}). ' .
    'If the buyer has no claims regarding the purchased product/service, after the warranty period ({$warranty} days) ' .
    'the payment amount of {$summ} minus the service commission of {$tax}% will be credited to your account. ' .
    'Order details are available at the following link: {$link}';

$L['payordersmarket_paid_mail_tocustomer_header'] = 'Order #{$order_id} has been paid';
$L['payordersmarket_paid_mail_tocustomer_body'] =
    'Congratulations! You have paid for order #{$order_id} ([{$product_id}] {$product_title}) in the amount of {$cost}. ' .
    'Order details are available at the following link: {$link}';

$L['payordersmarket_done_mail_toseller_header'] = 'Payout for order #{$order_id} ({$product_title})';
$L['payordersmarket_done_mail_toseller_body'] =
    'Congratulations! Your account has been credited with the payment for order #{$order_id} ' .
    '([{$product_id}] {$product_title}) in the amount of {$summ} minus the service commission of {$tax}%. ' .
    'Order details are available at the following link: {$link}';

$L['payordersmarket_sales_paidorders'] = 'Paid orders';
$L['payordersmarket_sales_doneorders'] = 'Completed orders';
$L['payordersmarket_sales_claimorders'] = 'Disputed orders';
$L['payordersmarket_sales_cancelorders'] = 'Cancelled orders';

$L['payordersmarket_purchases_paidorders'] = 'Paid purchases';
$L['payordersmarket_purchases_doneorders'] = 'Completed purchases';
$L['payordersmarket_purchases_claimorders'] = 'Disputed purchases';
$L['payordersmarket_purchases_cancelorders'] = 'Cancelled purchases';
$L['payordersmarket_purchases_new'] = 'Awaiting payment';

$L['payordersmarket_status_paid'] = 'Paid';
$L['payordersmarket_status_done'] = 'Completed';
$L['payordersmarket_status_claim'] = 'Disputed';
$L['payordersmarket_status_cancel'] = 'Cancelled';
$L['payordersmarket_status_new'] = 'New';

$L['payordersmarket_addclaim_title'] = 'Submit order claim';
$L['payordersmarket_addclaim_button'] = 'Submit claim to arbitration';
$L['payordersmarket_claim_title'] = 'Claim';
$L['payordersmarket_claim_accept'] = 'Cancel order';
$L['payordersmarket_claim_cancel'] = 'Reject claim';

$L['payordersmarket_claim_payments_seller_desc'] =
    'Payout for order #{$order_id} ([ID {$product_id}] {$product_title}) according to the site administration decision.';
$L['payordersmarket_claim_payments_customer_desc'] =
    'Refund for order #{$order_id} ([ID {$product_id}] {$product_title}) according to the site administration decision.';

$L['payordersmarket_claim_error_cost'] = 'Payout amount does not match the total order cost';

$L['payordersmarket_addclaim_mail_toseller_header'] = 'Claim for order #{$order_id}';
$L['payordersmarket_addclaim_mail_toseller_body'] =
    'The buyer has submitted a claim for order #{$order_id} ([ID {$product_id}] {$product_title}). ' .
    'Details are available at the following link: {$link}';

$L['payordersmarket_addclaim_mail_toadmin_header'] = 'Claim for order #{$order_id}';
$L['payordersmarket_addclaim_mail_toadmin_body'] =
    'The buyer has submitted a claim for order #{$order_id} ([ID {$product_id}] {$product_title}). ' .
    'Details are available at the following link: {$link}';

$L['payordersmarket_acceptclaim_mail_toseller_header'] = 'Order #{$order_id} cancelled';
$L['payordersmarket_acceptclaim_mail_toseller_body'] =
    'Order #{$order_id} ([ID {$product_id}] {$product_title}) has been cancelled due to a buyer claim. ' .
    'Details are available at the following link: {$link}';

$L['payordersmarket_acceptclaim_mail_tocustomer_header'] = 'Order #{$order_id} cancelled';
$L['payordersmarket_acceptclaim_mail_tocustomer_body'] =
    'Order #{$order_id} ([ID {$product_id}] {$product_title}) has been cancelled based on your claim. ' .
    'Details are available at the following link: {$link}';

$L['payordersmarket_cancelclaim_mail_tocustomer_header'] = 'Claim for order #{$order_id} rejected';
$L['payordersmarket_cancelclaim_mail_tocustomer_body'] =
    'Your claim for order #{$order_id} ([ID {$product_id}] {$product_title}) has been rejected by the site administration. ' .
    'Details are available at the following link: {$link}';

$L['payordersmarket_file'] = 'Files for sale';
$L['payordersmarket_file_for_download'] = 'Files for download';
$L['payordersmarket_file_download'] = 'Download';

$L['payordersmarket_order_number'] = 'Order #';
$L['payordersmarket_order_cost'] = 'Amount';
$L['payordersmarket_order_date'] = 'Order date';
$L['payordersmarket_order_paid'] = 'Payment date';
$L['payordersmarket_order_status'] = 'Status';
$L['payordersmarket_order_market_item_title'] = 'Product';
$L['payordersmarket_order_seller_user'] = 'Seller';
