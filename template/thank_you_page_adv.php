<?php
/**
 * WP_ADVERTISING_MANAGEMENT
 *
 * Questa classe permette la creazione della prima pagina di ringraziamento
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

function thankYou_page_advertising(){  


    if(isset($_GET['ID']) && isset($_GET['Key'])){

        global $wpdb;

        $table_name_sales = $wpdb->prefix . 'adv_sales';
        $table_name_paypal = $wpdb->prefix . 'adv_paypal';
        $table_name_customers = $wpdb->prefix . 'adv_customers';
        $key_sql_advertising = $wpdb->prefix . 'adv_advertising';

        //RICAVO LA KEY E LA CONFRONTO CON QUELLA PASSATA
        $key_sql = $wpdb->get_row( $wpdb->prepare(
            "SELECT * FROM $table_name_paypal WHERE salesID= %d",
            intval($_GET['ID'])
        ));

        //RICAVO LA KEY E LA CONFRONTO CON QUELLA PASSATA
        $key_sql_sale = $wpdb->get_row( $wpdb->prepare(
            "SELECT * FROM $table_name_sales WHERE ID= %d",
            intval($_GET['ID'])
        ));

        //RICAVO LA KEY E LA CONFRONTO CON QUELLA PASSATA
        $key_sql_customer = $wpdb->get_row(
            "SELECT * FROM $table_name_customers WHERE ID=" .  $key_sql_sales->customerID
            );    

         //RICAVO LA KEY E LA CONFRONTO CON QUELLA PASSATA
         $key_sql_advertising = $wpdb->get_row(
            "SELECT * FROM $table_name_advertising WHERE ID=" .  $key_sql_sales->advertisingID
            );

        
        //CONTROLLO NON SIA SCADUTA LA SESSIONE
        $dataOraAdesso =   current_time( 'mysql' );
        
        if($dataOraAdesso < $key_sql->expireTime && $key_sql->textKeyRandom == $_GET['Key']){
            

            //AGGIORNO DATI SUL DB
            $sql_sale_update = $wpdb->update( $table_name_sales,
                array(
                    'state' => '3',
                    'pay' => '1'
                ),
                array('ID' =>  $_GET['ID'])
            );

            ?>
            <p style="font-size:22px">Grazie per il suo acquisto. Il suo articolo verrà pubblicato a breve. Di seguito un riepilogo.</p>

        

           <?php

           //Se specificato procedo con l'invio della mail 

           if(get_option('advertising_management_emailNotice') != '')
           { 

            $to = get_option('advertising_management_emailNotice');
            $subject = 'Nuovo ordine spazio pubblicitario WpAdvertisingManagement';
            $body = "E' stato acquistato un nuovo spazio pubblicitario nel tuo sito web \n
            Spazio pubblicitario: $key_sql_advertising->name \n
            Acquirente:  $key_sql_customer->name $key_sql_customer->surname \n
            Totale:  $key_sql_sale->totalprice \n
            Nel caso di acquisto di un articolo si ricorda di procedere a visionarlo e pubblicarlo. In caso di pubblicazione oltre la data di acquisto si ricorda di modificare la data di scadenza.";
            $headers = array('Content-Type: text/html; charset=UTF-8');
 
            wp_mail( $to, $subject, $body, $headers );
           
            }

        }else{ 

            ?>

            <p>PAGAMENTO NON DISPONIBILE. SESSIONE SCADUTA O CHIAVE NON VALIDA.</p>
    
    
            <?php
        }

    }else{ 
        ?>

        <p>ACCESSO NEGATO</p>


        <?php
    }

}

?>