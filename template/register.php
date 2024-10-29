<?php
/**
 * WP_ADVERTISING_MANAGEMENT
 *
 * Questa classe permette la creazione della prima pagina di registrazione da parte dell'utenteche desidera comprare un banner pubblicitario
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

function advertising_management_register(){  

    //controllo che l'utente stia arrivando dalla pagina custom

    if(isset($_GET['token']) && $_GET['token'] == 'adv_custom_to_register'){

    //VARIABILE DI CONTROLLO PER STATE SALES
    $controlState = false;
    
    $nonce = @esc_attr($_REQUEST['_wpnonce']);

    //controllo nonce
    if(!wp_verify_nonce($nonce, 'adv_custom_adv_to_register')){
        die('Weird: Plugin is resolving itself');
    }


    if (isset($_GET['Item']) && isset($_GET['ID'])) { 
        
        global $wpdb;

        //ricavo i dati dal database dell'item scelto dall'utente
        $itemSelected = $wpdb->get_row( $wpdb->prepare(
            "SELECT * FROM " . $wpdb->prefix . "adv_advertising WHERE ID= %d",
            intval($_GET['Item'])
        ));


        $IDSelected = $wpdb->get_row( $wpdb->prepare(
            "SELECT * FROM " . $wpdb->prefix . "adv_sales WHERE ID= %d",
            intval($_GET['ID'])
        ));

        /*
        *State 0 defalut, 1 custom, 2 registrato, 3 pagato
        *
        *Controllo sia allo stato 1 altrimenti non è possibile procedere
        */
        if($IDSelected->state != '1')
        $controlState=true;
                
    }else{
        /*
        *In questo caso l'id e/o l'item non è impostato
        */?>

        <h4> Errore: mancanza dati <br>
        La preghiamo di <a href="<?php echo home_url() ?>">riprovare</a></h4>
        
    <?php

    }  

    if($itemSelected===null || $IDSelected === null || $controlState === true){ 
        /*
        *In questo caso l'id e/o ID passato tramite get non risulta presente nel DB
        */?>

        <h4> Errore: il prodotto da lei scelto non è disponibile <br>
        La preghiamo di <a href="<?php echo home_url() ?>">riprovare</a></h4>
        
    <?php
    }else{

        /*
        *Riga presente nel DB proseguo
        */
    ?>
    <div align="center">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Prodotto</th>
                            <th scope="col">Descrizione</th>
                            <th scope="col">Prezzo</th>
                        </tr>
                    </thead>
                    <tbody>    
                        <tr>
                            <td><?php echo $itemSelected->name; ?></td>
                            <td><?php echo $itemSelected->description; ?></td>
                            <td><?php echo $itemSelected->price . ' ' . get_option('advertising_management_currencySymbol'); ?></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th scope="col">Tot:</th>
                            <th><?php echo $itemSelected->price . ' ' . get_option('advertising_management_currencySymbol'); ?></th>
                        </tr>    
                    </tfoot>   
                    </table>
            </div>
        </div>
    </div>
    <br>
    <hr>
    <br>
    <h5>Informazioni personali:</h5>
    <form method="post" >
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Nome:</label>
                <input class="form-control" type="text" id="name" name="name" placeholder="Nome" required>
            </div>
        </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="surname">Cognome:</label>
                    <input class="form-control" type="text" id="surname" name="surname" placeholder="Cognome" required>
                </div>
            </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="company">Azienda:</label>
                <input class="form-control" type="text" id="company" name="company" placeholder="Ragione sociale">
            </div>
        </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="vat">Partita IVA:</label>
                    <input class="form-control" type="text" id="vat" name="vat" placeholder="Partita iva">
                </div>
            </div>        
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="address">Indirizzo:</label>
                <input class="form-control" type="text" id="address" name="address" placeholder="Indirizzo" required>
            </div>
        </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="city">Città:</label>
                    <input class="form-control" type="text" id="city" name="city" placeholder="Città" required>
                </div>
            </div>        
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="state">Stato:</label>
                <input class="form-control" type="text" id="state" name="state" placeholder="Stato" required>
            </div>
        </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="telephone">Recapito telefonico:</label>
                    <input class="form-control" type="tel" id="telephone" name="telephone" placeholder="Recapito telefonico" required>
                </div>
            </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="mail">E-mail:</label>
                <input class="form-control" type="email" id="mail" name="mail" placeholder="E-mail" required>
            </div>
        </div>
    </div>
    <div class="row">
            <div class="col-md-12">                
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="invoice" id="invoice" />
                    <label class="form-check-label" for="defaultCheck1">
                        Fattura richiesta
                    </label>
                </div>
            </div>
    </div>
    

    
    <input type="hidden" name="timeonline" value="<?php echo  $itemSelected->timeOnline; ?>" >
    <input type="hidden" name="totalprice" value="<?php echo  $itemSelected->price; ?>" >

    <input type="hidden" name="IDAdvSales" value="<?php echo  $_GET['ID']; ?>" >

       <!-- Identify your business so that you can collect the payments. -->
       <input type="hidden" name="business" value="<?php echo get_option('advertising_management_emailPaypal'); ?>">
                
                <!-- Specify a Buy Now button. -->
                <input type="hidden" name="cmd" value="_xclick">
              
                <!-- Specify details about the item that buyers will purchase. -->
                <input type="hidden" name="item_name" value="Advertising <?php echo $itemSelected->name; ?>">
                <input type="hidden" name="amount" value="<?php echo  $itemSelected->price; ?>">
                <input type="hidden" name="currency_code" value="EUR">

                <input type="hidden" name="item_name" value="Advertising <?php echo $itemSelected->name; ?>">
                <input type="hidden" name="amount" value="<?php echo  $itemSelected->price; ?>">
      
                
              <input type="hidden" value="<?php echo home_url() . "/pagina-di-ringraziamento?ID=$IDadv&Key=$RandomKeyPayment"; ?>" name="return">
              <input type="hidden" value="1" name="rm">
      
      
              <input type="hidden" value="<?php echo home_url() ?>" name="cancel_return">

              <?php
      
      if( get_option('advertising_management_emailPaypal') == ""){ 


          print "
          <p>Paypal non impostato. Contattare l'amministratore del sito.</p>
          ";

      }else{ 

          ?>         
             <div class="row" id="buttonPayment">
             <div class="col-md-12">
                 <input id="AdvManagementSubitRegister" value="Procedi al pagamento" type="submit" name="submitSignUP" ><?php do_action('SubmitRegistration');  ?>
             </div>
         </div>

         </form>
          
         <div class="row" id="imgPaypal">
             <div class="col-md-12">
      <img src="https://www.paypalobjects.com/webstatic/en_US/i/buttons/cc-badges-ppppcmcvdam.png" alt="Credit Card Badges">
      </div>
         </div><?php

   }

}
}
}



add_action( 'wp', 'adv_send_customer');

function adv_send_customer() {
    global $wpdb;
    

    //nomi tabella
    $table_name_customers = $wpdb->prefix . 'adv_customers';
    $table_name_sales = $wpdb->prefix . 'adv_sales';
    $table_name_paypal = $wpdb->prefix . 'adv_paypal';

    $time = current_time( 'mysql' ); 
    

    if ( isset( $_POST['submitSignUP'] ) ) {
            //pulizia dei dati
            $name = sanitize_text_field($_POST['name']);
            $surname = sanitize_text_field($_POST['surname']);
            $company = sanitize_text_field($_POST['company']);
            $vat = sanitize_text_field($_POST['vat']);
            $address = sanitize_text_field($_POST['address']);
            $city = sanitize_text_field($_POST['city']);
            $state = sanitize_text_field($_POST['state']);
            $telephone = sanitize_text_field($_POST['telephone']);
            $mail = sanitize_email($_POST['mail']);

            $sql_customer = $wpdb->insert( $table_name_customers,
            array(
                'createddate'=> $time,
                'name'=> $name,
                'surname'=> $_POST['surname'],
                'company'=>  $_POST['company'],
                'vat'=> $_POST['vat'],
                'address'=> $_POST['address'],
                'city'=> $_POST['city'],
                'state'=> $_POST['state'],
                'telephone'=> $_POST['telephone'],
                'mail'=> $_POST['mail']
            ));

            $Id_customer = $wpdb->insert_id;

            if($sql_customer == false){
                /* 
                *INSERIMENTO DEL CLIENTE NON AVVENUTO CON SUCCESSO
                */

                ?>

<h1 style="text-align:center;">ERRORE NEL PROCESSO DI PAGAMENTO CONTATTARE L'AMMINISTRATORE DEL SITO</h1>

                <?php

            }else{

                /* 
                *INSERIMENTO DEL CLIENTE AVVENUTO CON SUCCESSO
                */


                //raccolgo ID
                $IDadv = $_POST['IDAdvSales'];

                //Ddata nella quale il banner verrà tolto
                $newdate = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + $_POST['timeonline'], date('Y')));
            
                //controllo se l'utente desidera fattura o meno
                if($_POST['invoice'] == 'on'){ 
                    $invoice = 1;
                }else{ 
                    $invoice = 0;
                }
           
                $sql_sale_update = $wpdb->update( $table_name_sales,
                array(
                    'state' => '2',
                    'saledate'=> $time,
                    'customerID'=> $Id_customer,
                    'totalprice'=> $_POST['totalprice'],
                    'invoice'=> $invoice,
                    'datefinish' => $newdate
                ),
                array('ID' =>  $IDadv)
                );   

                //inserisco dati pagamento in wp_paypal per controllo pagamento
                 
                //aggiungo 30 minuti all'orario
                $now = current_time( 'mysql' ); 
                $expireTimePayment = date("Y-m-d H:i:s", strtotime($now) + 1800);
               

                //MI SALVO LA KEY RANDOM PER IL PAGAMENTO
                $RandomKeyPayment = bin2hex(random_bytes(10));
                
                //inserisco la riga nel DB
                $sql_paypal = $wpdb->insert( $table_name_paypal,
                array(
                'expireTime'=> $expireTimePayment,
                'salesID'=> $IDadv,
                'textKeyRandom'=> $RandomKeyPayment            
                ));

               

                
                //UNA VOLTA INSERITI I DATI PER QUANTO RIGUARDA IL POST VA CREATO 
                //COLLEGAMENTO PAYPAL PAY 0O 1 E STATE 3
                ?>
                <p></p>
                
                <form name="SendPaypalPaymentADV" action="https://www.paypal.com/cgi-bin/webscr" method="post" >

                <!--PAYPAL-->
                <!-- Identify your business so that you can collect the payments. -->
                <input type="hidden" name="business" value="<?php echo get_option('advertising_management_emailPaypal'); ?>">
                
                <!-- Specify a Buy Now button. -->
                <input type="hidden" name="cmd" value="_xclick">
              
                <!-- Specify details about the item that buyers will purchase. -->
                <input type="hidden" name="item_name" value="Advertising <?php echo $_POST['item_name']; ?>">
                <input type="hidden" name="amount" value="<?php echo  $_POST['amount']; ?>">
                <input type="hidden" name="currency_code" value="<?php echo get_option('advertising_management_currency'); ?>">
      
                
              <input type="hidden" value="<?php echo home_url() . "/pagina-di-ringraziamento?ID=$IDadv&Key=$RandomKeyPayment"; ?>" name="return">
              <input type="hidden" value="1" name="rm">
      
      
              <input type="hidden" value="<?php echo home_url() ?>" name="cancel_return">


              <script type="text/javascript">
                document.SendPaypalPaymentADV.submit();
              </script>
              
                <?php
               
            }
    }

}


?>