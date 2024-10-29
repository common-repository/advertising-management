<?php
/**
 * WP_ADVERTISING_MANAGEMENT
 *
 * Creazione e gestione database per plugin
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
function created_adv_db_advertising(){

//DTABASE PUBBLICITA
global $wpdb;
global $db_adv_management_advertising;

 $table_name = $wpdb->prefix . 'adv_advertising';
  
 $charset_collate = $wpdb->get_charset_collate();
 $sql=" CREATE TABLE $table_name (
     ID mediumint(9) NOT NULL AUTO_INCREMENT,
     createddate datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
     name tinytext NOT NULL,
     description text NOT NULL,
     shortcode tinytext NOT NULL,
     advtype tinytext NOT NULL,
     width mediumint(9),
     height mediumint(9),
     price mediumint(9) NOT NULL,
     timeOnline mediumint(9) DEFAULT '7' NOT NULL,
     activeted boolean DEFAULT '1' NOT NULL,
     availability mediumint(10)  DEFAULT '1' NOT NULL,
     PRIMARY KEY (ID)
     ) $charset_collate;";


 require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
 dbDelta($sql);

 //salvo opzione versione tabella
 add_option('db_adv_advertising',$db_adv_management_advertising);

 //popolo i dati predefiniti
 insert_data_db_advertising($table_name);

}

function insert_data_db_advertising($table_name){
    global $wpdb;
    
    //data
    $time = current_time( 'mysql' );

    //primo dato link normale
    $sql = $wpdb->insert( $table_name,
            array(
                'createddate'=> $time,
                'name'=> 'Link',
                'description'=> 'Inseriremo un link nella parte inferiore dei nostri post alla tua pagina',
                'shortcode'=> "0",
                'advtype'=>  'link',
                'price'=> '1'
                )
            );

    //secondo dato banner sidebar 250 x 250
    $sql = $wpdb->insert( $table_name,
    array(
        'createddate'=> $time,
        'name'=> 'Sidebar banner',
        'description'=> 'Inseriremo un tuo banner di dimensioni 250 x 250 nella nostra sidebar',
        'shortcode'=> "[advertising_management_sidebar_banner]",
        'advtype'=>  'sidebar_banner',
        'width' => '250',
        'height' => '250',
        'price'=> '1'
        )
    );

     //secondo dato banner post 300 x 150
     $sql = $wpdb->insert( $table_name,
     array(
         'createddate'=> $time,
         'name'=> 'Post banner',
         'description'=> 'Inseriremo un tuo banner 300 x 150 nella parte inferiore dei nostri post alla tua pagina',
         'shortcode'=> "0",
         'advtype'=>  'post_banner',
         'width' => '300',
         'height' => '150',
         'price'=> '1'
         )
     );
 }

 function delete_adv_db_advertising(){
    global $wpdb;

    $table_name_adv = $wpdb->prefix . 'adv_advertising';
    $wpdb->query( "DROP TABLE IF EXISTS $table_name_adv");

    delete_option('db_adv_advertising');
}


?>