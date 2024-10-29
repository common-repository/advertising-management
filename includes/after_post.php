<?php
/**
 * WP_ADVERTISING_MANAGEMENT
 *
 * Aggiunge contenuto dopo i post
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


function advertising_management_content_after_post($content){
    global $wpdb;   

    //recupero l'opzione contenente l'id di pubblicità
    $id = get_option('IDadvertisingAfterPost');

    //recupero le informazioni sulla pubblicità
    $AdvertisingSelect = $wpdb->get_row( "SELECT * FROM " . $wpdb->prefix . "adv_sales WHERE ID = $id" );

    /*
    *in questa situazione posso avere due casi: 
    *Link normale senza immagine da inserire sotto il post
    *Banner come link sotto il post
    *Verifico in che situazione mi trovo
    */
       
    if($AdvertisingSelect->linkImg == NULL){
 
        /*
        *Caso in cui non è inserito alcun banner quindi è un singolo link
        */

         if(!is_feed() && !is_home() && !is_page()){ 
 
             //inserisco il link
             $content .= "<a href=\"".$AdvertisingSelect->link."\">".$AdvertisingSelect->textLink."</a>";      
             
         }  


    }else{
    /*
    *Caso in cui è inserito un banner quindi è da trattare come banner sotto un post
    */

         if(!is_feed() && !is_home() && !is_page()){ 
 
        //inserisco il banner
        $content .= "<a href=\"".$AdvertisingSelect->link."\"><img src=\"".$AdvertisingSelect->linkImg."\" width=\"".$AdvertisingSelect->width."\" height=\"".$AdvertisingSelect->height."\"></a>";      
        
        }  

    }

 
     return $content;
 }

 
//aggiungo azione e filtro di aggiunta contenuto
add_action('the_content','advertising_management_content_after_post');


add_filter( 'the_content' , 'advertising_management_content_after_post' );
function advertising_after_post(){  
    //richiamo la funzione
    do_action('the_content');

}
?>