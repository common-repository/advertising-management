<?php
/**
 * WP_ADVERTISING_MANAGEMENT
 *
 * Questa classe crea e gestisce il sidebar banner e il suo contenuto
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


function advertising_management_sidebar_banner(){

    global $wpdb;

    $time = current_time( 'mysql' ); 

    //recupero le pubblicità
	$salesList = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "adv_sales" );

    //controllo che il db non sia vuoto
    if($salesList == null){
        //in questo caso non faccio nulla
    }else {
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
            
                //per quanto riguarda il banner creo il contenuto
                if($Advertising->shortcode != "0"){
                    ?>
                    <a href="<?php echo $object->link; ?>" ><img src="<?php echo $object->linkImg; ?>" width="<?php echo $Advertising->width; ?>" height="<?php echo $Advertising->height; ?>" ></a>
                    <?php
                }
            }

         //RIMOSSO CHE AUMENTA DI 1 LA DISPONITA NEL CASO SIA SCADUTO DA UN GIORNO

        }
    }
 }
?>