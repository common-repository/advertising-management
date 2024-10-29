<?php
/**
 * WP_ADVERTISING_management
 *
 * Pagina clienti opzioni plugin adv management
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

 function adv_management_option_customer() {

  if(isset($_POST['submitCustomersettings'])){ 
    
    //controllo nonce
    if(!wp_verify_nonce( $_POST['editCustomerNonce'], 'nonceEditCustomer')){
      ?>
    <div class="notice notice-error"><p>Errore: si prega di riprovare</p></div>
    <?php
    }
    else
    {
        //controllo nonce superato

        $name = sanitize_text_field( $_POST['name'] );
        $surname = sanitize_text_field( $_POST['surname'] );
        $company = sanitize_text_field( $_POST['company'] );
        $vat = sanitize_text_field( $_POST['vat'] );
        $address = sanitize_text_field( $_POST['address'] );
        $city = sanitize_text_field( $_POST['city'] );
        $state = sanitize_text_field( $_POST['state'] );
        $telephone = sanitize_text_field( $_POST['telephone'] );
        $mail = sanitize_text_field( $_POST['mail'] );

        global $wpdb;

        $save_update_adv = $wpdb->update( $wpdb->prefix . 'adv_customers',
                array(
                    'name' => $name,
                    'surname'=> $surname,
                    'company'=> $company,
                    'vat'=> $vat,
                    'address' => $address,
                    'city'=> $city,
                    'state'=> $state,
                    'telephone'=> $telephone,
                    'mail'=> $mail
                ),
                array('ID' => $_POST['ID'])
              ); 

        //salvataggio non andato a buon fine
        if($save_update_adv == false)  { 
          ?>
            <div class="notice notice-error"><p>Errore nel salvataggio: si prega di riprovare</p></div>
          <?php
        }else   { 
          ?>
            <div class="notice notice-success"><p>Cliente aggiornato!</p></div>
          <?php
        }

    }


  }

  $table = new WPAdvTableCustomer(); // Il codice della classe a seguire
  $table->prepare_items(); // Metodo per elenco campi

  // Definizione variabili per contenere i valori
  // di paginazione e il nome della pagina visualizzata

  $page  = filter_input(INPUT_GET,'page' ,FILTER_SANITIZE_STRIPPED);
  $paged = filter_input(INPUT_GET,'paged',FILTER_SANITIZE_NUMBER_INT);

  echo '<div class="wrap">';
  echo '<h2>Clienti:</h2>';

  // Form per contenere la tabella con elenco records
  // presenti nel database e campi definiti nella classe

  echo '<form id="persons-table" method="GET">';
  echo '<input type="hidden" name="paged" value="'.$paged.'"/>';
    $table->display(); // Metodo per visualizzare elenco records
  echo '</form>';

  echo '</div>';
    }


 ?>