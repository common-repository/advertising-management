<?php
/**
 * WP_ADVERTISING_MANAGEMENT
 *
 * Creazione e gestione databse advertising
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

function created_adv_db_customers() {

    //DTABASE CLIENTI
    global $wpdb;
    global $db_adv_management;
    $table_name = $wpdb->prefix . 'adv_customers';
  
    $charset_collate = $wpdb->get_charset_collate();
    $sql=" CREATE TABLE $table_name (
        ID mediumint(9) NOT NULL AUTO_INCREMENT,
        createddate datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        name tinytext NOT NULL,
        surname tinytext NOT NULL,
        company tinytext,
        vat varchar(25),
        address tinytext NOT NULL,
        city tinytext NOT NULL,
        state tinytext NOT NULL,
        telephone tinytext,
        mail tinytext NOT NULL,
        PRIMARY KEY (ID)
        ) $charset_collate;";


    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    add_option('db_adv_customers',$db_adv_management);
}

function delete_adv_db_customers(){
    global $wpdb;

    $table_name = $wpdb->prefix . 'adv_customers';
    $wpdb->query( "DROP TABLE IF EXISTS $table_name");

    delete_option('db_adv_customers');
}



 ?>