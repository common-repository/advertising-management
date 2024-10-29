<?php
/**
 * WP_ADVERTISING_management
 *
 * Pagina support opzioni plugin adv management
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

 function adv_management_option_support() {
    ?>
    <h1>Impostazioni:</h1>
        
    <form method="post">

<div class="container" id="settingAdminContainer">
    <div class="form-group row">
    <label for="currency" class="col-sm-4 col-form-label" style="text-align:center;">Valuta</label>
        <div class="col-sm-8">  
            <select class="form-control" id="currency" name="currency" >
            <option value="EUR" <?php if(get_option('advertising_management_currency') == 'EUR'){ echo 'selected';} ?>>
            Euro (€)</option>
            <option value="USD" <?php if(get_option('advertising_management_currency') == 'USD'){ echo 'selected';} ?>>
            United States dollar ($)</option>
            <option value="GBP" <?php if(get_option('advertising_management_currency') == 'GBP'){ echo 'selected';} ?>>
            Pound sterling (£)</option>
            <option value="AUD" <?php if(get_option('advertising_management_currency') == 'AUD'){ echo 'selected';} ?>>
            Australian dollar ($)</option>
            <option value="CHF" <?php if(get_option('advertising_management_currency') == 'CHF'){ echo 'selected';} ?>>
            Schweizer Franken (CHF)</option>
            <option value="CAD" <?php if(get_option('advertising_management_currency') == 'CAD'){ echo 'selected';} ?>>
            Canadian dollar ($)</option>
            <option value="CZK" <?php if(get_option('advertising_management_currency') == 'CZK'){ echo 'selected';} ?>>
            Czech koruna (CZK)</option>       
            <option value="BRL" <?php if(get_option('advertising_management_currency') == 'BRL'){ echo 'selected';} ?>>
            Brazilian real (R$)</option>  
            <option value="DKK" <?php if(get_option('advertising_management_currency') == 'DKK'){ echo 'selected';} ?>>
            Danish krone (DKK)</option> 
            <option value="HKD" <?php if(get_option('advertising_management_currency') == 'HKD'){ echo 'selected';} ?>>
            Hong Kong dollar (HK$)</option> 
            <option value="INR" <?php if(get_option('advertising_management_currency') == 'INR'){ echo 'selected';} ?>>
            Indian rupee (INR))</option>     
            <option value="MXN" <?php if(get_option('advertising_management_currency') == 'MXN'){ echo 'selected';} ?>>
            Mexican peso (MXN)</option>   
            </select>
        </div>
    </div>  

    <div class="form-group row">
            <label for="emailNotice" class="col-sm-4 col-form-label" style="text-align:center;">Email per notifiche </label>
        <div class="col-sm-8">  
            
                <input class="form-control" id="advertising_management_emailNotice" type="email" name="advertising_management_emailNotice" value="
                <?php
                    echo get_option('advertising_management_emailNotice');
                ?>
                " ><small class="form-text text-muted">Lasciare il campo vuoto per disattivare le notifiche.</small>
        </div>
    </div>  

    <div class="form-group row">
        <label for="advertising_management_emailPaypal" class="col-sm-4 col-form-label" style="text-align:center;">Email PayPal </label>
        <div class="col-sm-8"> 
                <input class="form-control" id="advertising_management_emailPaypal" type="email" name="advertising_management_emailPaypal" value="
                <?php
                    echo get_option('advertising_management_emailPaypal');
                ?>
                " ><small class="form-text text-muted">Obbligatorio per ricevere pagamenti. Indica l'account Paypal in cui riceverai i pagamenti.</small>
        </div>
    </div>

    <div class="form-group row">
        <label for="advertising_management_linkPrivacyPolice" class="col-sm-4 col-form-label" style="text-align:center;">Link privacy policy:</label>
        <div class="col-sm-8"> 

                <input class="form-control" id="advertising_management_linkPrivacyPolice" type="text" name="advertising_management_linkPrivacyPolice" value="
                <?php
                    echo get_option('advertising_management_linkPrivacyPolice');
                ?>
                " ><small class="form-text text-muted">Se non vuoi inserire una privacy policy lascia il campo vuoto.</small>
        </div>
    </div>

    <div class="form-group row">
        <label for="advertising_management_linkCondGenVend" class="col-sm-4 col-form-label" style="text-align:center;">Link Condizioni generali di vendita:</label>
        <div class="col-sm-8"> 

                <input class="form-control" id="advertising_management_linkCondGenVend" type="text" name="advertising_management_linkCondGenVend" value="
                <?php
                    echo get_option('advertising_management_linkCondGenVend');
                ?>
                " ><small class="form-text text-muted">Se non vuoi inserire le condizioni di vendita generali lascia il campo vuoto.</small>
        </div>
    </div>

                <?php wp_nonce_field( 'nonceEditSettingAdv', 'saveSettingADVNonce' ); ?>
                <input class="btn btn-primary" type="submit" value="Salva" name="submitAdvsettings"><?php do_action('saveAdvsettings'); ?>
                

    </form>
</div>    
    <?php
    }
 add_action('saveAdvsettings','saveSettingAdv' );


    //funzione che salva le impostazioni generali
    function saveSettingAdv(){ 
        
        //controllo che il form sia stato compilato e che il nonce sia corretto
        if(isset( $_POST['submitAdvsettings'] ) && wp_verify_nonce( $_POST['saveSettingADVNonce'], 'nonceEditSettingAdv' ) ){


            //array delle options
            $optionsEmail = array('advertising_management_emailNotice','advertising_management_emailPaypal');
            $optionsLink = array('advertising_management_linkPrivacyPolice','advertising_management_linkCondGenVend');

            $i = 0;

            foreach($optionsEmail as $option){
                $i++;
                //inserisco i dati nelle opzioni
                update_option($option, sanitize_text_field($_POST[$option]));
            }

            foreach($optionsLink as $option){
                //inserisco i dati nelle opzioni
                update_option($option, sanitize_text_field($_POST[$option]));
            }

           

            update_option('advertising_management_currency',$_POST['currency']);

            //SIMBOLO PREZZO
            switch (get_option('advertising_management_currency')){ 
                case 'EUR':
                update_option('advertising_management_currencySymbol','€');
                break;

                case 'USD':
                update_option('advertising_management_currencySymbol','$');
                break;

                case 'GBP':
                update_option('advertising_management_currencySymbol','£');
                break;

                case 'AUD':
                update_option('advertising_management_currencySymbol','€');
                break;

                case 'CHF':
                update_option('advertising_management_currencySymbol','CHF');
                break;

                case 'CAD':
                update_option('advertising_management_currencySymbol','$');
                break;

                case 'CZK':
                update_option('advertising_management_currencySymbol','CZK');
                break;

                case 'BRL':
                update_option('advertising_management_currencySymbol','R$');
                break;

                case 'DKK':
                update_option('advertising_management_currencySymbol','DKK');
                break;

                case 'HKD':
                update_option('advertising_management_currencySymbol','HK$');
                break;

                case 'INR':
                update_option('advertising_management_currencySymbol','INR');
                break;

                case 'MXN':
                update_option('advertising_management_currencySymbol','MXN');
                break;

                default:
                update_option('advertising_management_currencySymbol','ERROR');
                break;
            }
            ?>
            <div class="notice notice-success"><p>Impostazioni aggiornate!</p></div>
            <?php     

            wp_redirect( home_url() . '/wp-admin/admin.php?page=option_adv_management_support');

        }else{
            if(isset( $_POST['submitAdvsettings'] )){
            ?>
            <div class="notice notice-error"><p>Errore nel salvataggio: si prega di riprovare</p></div>
          <?php
          }
        }
    }
 ?>