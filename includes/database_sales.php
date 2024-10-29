<?php
/**
 * WP_ADVERTISING_MANAGEMENT
 *
 * Creazione e gestione databse sales
 *
 *
 * @package     advertising
 * @subpackage  Classes/API
 * @copyright   Copyright (c) 2018, Marcon Simone
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

global $db_adv_management;

$db_adv_management='1.0';

function created_adv_db_sales() {

    //DATABASE SALES
    global $wpdb;
    global $db_adv_management;
    $table_name = $wpdb->prefix . 'adv_sales';
  
    $charset_collate = $wpdb->get_charset_collate();
    $sql=" CREATE TABLE $table_name (
        ID mediumint(9) NOT NULL AUTO_INCREMENT,
        saledate datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        state tinyint DEFAULT '0',
        customerID mediumint(9),
        advertisingID mediumint(9) NOT NULL,
        payPalPaymentID mediumint(9),
        totalprice integer,
        pay boolean DEFAULT '0' NOT NULL,
        link text NOT NULL,
        textLink tinytext,
        linkImg tinytext,
        invoice boolean DEFAULT '0' NOT NULL,
        datefinish datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        PRIMARY KEY (ID),
        FOREIGN KEY (customerID) REFERENCES wp_adv_customers (ID),
        FOREIGN KEY (advertisingID) REFERENCES wp_adv_advertising (ID)
        ) $charset_collate;";


    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    add_option('db_adv_sales',$db_adv_management);
}

function delete_adv_db_sales(){
    global $wpdb;

    $table_name_sales = $wpdb->prefix . 'adv_sales';
    $wpdb->query( "DROP TABLE IF EXISTS $table_name_sales");

    delete_option('db_adv_sales');
}

?>