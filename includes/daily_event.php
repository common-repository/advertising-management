<?php
/**
 * WP_ADVERTISING_MANAGEMENT
 *
 * Evento giornaliero per gestione pubblicità automatica LINK - BANNER SOTTO POST
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

function event_daily_advertising(){
    
    global $wpdb;

    $time = current_time( 'mysql' ); 
    
    //recupero le pubblicità
	$salesList = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "adv_sales" );
    
    //mostro la pubblicità acquistata  
    foreach ($salesList as $object) 
    {
         /*
            *   Prima di procedere controllo:
            *   Che la data di scadenza banner sia ancora valida
            *   Che lo stato dell'annuncio sia su 3 ovvero registrato e pagato
            *   Che pay sia a 1 ovvero pagato
            */
        if($object->datefinish >= $time && $object->state == '3' && $object->pay == '1'){
        
            //recupero le informazioni sulla pubblicità
            $Advertising = $wpdb->get_row( "SELECT * FROM " . $wpdb->prefix . "adv_advertising WHERE ID = $object->advertisingID" );
            
            //per quanto riguarda i link o banner sotto i post li attacco automaticamente
            if($Advertising->shortcode == "0"){
      
                //mi salvo l'id della pubblicità da aggiungere
                update_option('advertising_management_IDadvertising',$object->ID);

                //rimando alla funzione che aggiunge il contenuto
                do_action('add_content');                

            }else{

                //NEL CASO SIA DA MODIFICARE IL CONTENUTO DEL SHORTCODE
      
                //Richiamo la funzione per modificare il contenuto del shortcode
                add_shortcode('advertising_management_sidebar_banner', 'sidebar_banner_plugin_advertising');         

            }
            
        } 
        
        /*
        *Ricavo la data di ieri e la confronto con la data scad. annuncio
        *Se la data di ieri è uguale a uqella di scad. annuncio-> aumento di 1 la disp.
        *Altrimenti non faccio nulla
        */

        //Ddata nella quale il banner verrà tolto
        $dateYesterday = date('Y-m-d H:i:s', mktime(0, 0, 0, date('m'), date('d') - 1, date('Y')));

        if($object->datefinish == $dateYesterday){

            $number = intval($object->availability);

            //aumento disponibilità di 1
            $sql_sale_update = $wpdb->update( $wpdb->prefix . 'adv_advertising',
            array(
               'availability' => $number+1
            ),
            array('ID' => $object->ID)
            );      
        } 
    }
    
    

}

add_action('add_content','advertising_after_post');


/*function sidebar_banner_plugin_advertising() {

    ob_start();
 
    advertising_management_sidebar_banner();
 
    return ob_get_clean();
 }*/
     

?>