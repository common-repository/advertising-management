<?php
/**
 * WP_ADVERTISING_MANAGEMENT
 *
 * Creazione e gestione databse paypal
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

global $db_adv_managment;

$db_adv_management='1.0';

function created_adv_db_paypal() {

    //DTABASE CLIENTI
    global $wpdb;
    global $db_adv_management;
    $table_name = $wpdb->prefix . 'adv_paypal';
  
    $charset_collate = $wpdb->get_charset_collate();
    $sql=" CREATE TABLE $table_name (
        ID mediumint(9) NOT NULL AUTO_INCREMENT,
		expireTime datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        salesID mediumint(9) NOT NULL,        
        textKeyRandom tinytext,      
        PRIMARY KEY (ID),
        FOREIGN KEY (salesID) REFERENCES wp_adv_sales (ID) 
        ) $charset_collate;";


    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    add_option('db_adv_paypal',$db_adv_management);

}

function delete_adv_db_paypal(){
    global $wpdb;

    $table_name = $wpdb->prefix . 'adv_paypal';
    $wpdb->query( "DROP TABLE IF EXISTS $table_name");

    delete_option('db_adv_paypal');
}


?>