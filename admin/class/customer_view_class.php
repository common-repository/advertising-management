<?php
/**
 * WP_ADVERTISING_management
 *
 * Classe per la gestione della visualizzaizone della tabella advertising
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

// Se non esiste la classe eseguo il caricamento
// del file di wordpress necessario all'esecuzione

if (!class_exists('WP_List_Table')) {
 require_once(ABSPATH.'wp-admin/includes/class-wp-list-table.php');
}

// Definizione Classe per il nostro database e aggiunta
// dei metodi necessari alla visualizzazione delle informazioni

class WPAdvTableCustomer extends WP_List_Table
{
  function __construct() {
    parent::__construct(array('plural'=>'Clienti'));
  }

  // Funzione per la preparazione dei campi da visualizzare
  // e la query SQL principale che deve essere eseguita 

  function prepare_items()
  {

    if(isset($_GET['action'])=='edit'){
      //controllo il nonce
      $nonce = @esc_attr($_REQUEST['_wpnonce']);
      if(!wp_verify_nonce($nonce, 'adv_edit_customer')){
        die('Weird: Plugin is resolving itself');
      }

      do_action('editCustomer');

      exit();
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'adv_customers';
    $per_page = 25; // Numero dei record presenti in una pagina

    // Calcolo elenco de dei campi per le differenti
    // sezioni e memorizzo tutto in array separati

    $columns  = $this->get_columns();
    $hidden   = $this->get_columns_hidden();
    $sortable = $this->get_columns_sortable();

    // Bisogna memorizzare tre array che devono contenere i campi da 
    // visualizzare, quelli nascosti e quelli per eseguire l'ordinamento

    $this->_column_headers = array($columns,$hidden,$sortable);

    // Preparazione delle variabili che devono essere utilizzate
    // nella preparazione della query con gli ordinamenti e la posizione

    if (!isset($_REQUEST['paged'])) $paged = 0;
      else $paged = max(0,(intval($_REQUEST['paged'])-1)*25);

    if (isset($_REQUEST['orderby'])
        and in_array($_REQUEST['orderby'],array_keys($sortable)))
    $orderby = $_REQUEST['orderby']; else $orderby = 'ID';

    if (isset($_REQUEST['order'])
        and in_array($_REQUEST['order'],array('asc','desc')))
    $order = $_REQUEST['order']; else $order = 'asc';

    // Calcolo le variabili che contengono il numero dei record totali
    // e l'elenco dei record da visualizzare per una singola pagina

    $total_items = $wpdb->get_var(
      "SELECT COUNT(name) FROM $table_name");

    $this->items = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table_name ".
            "ORDER BY $orderby $order ".
            "LIMIT %d OFFSET %d",$per_page, $paged), ARRAY_A);

    $this->set_pagination_args(array(
        'total_items' => $total_items,
        'per_page'    => $per_page,
        'total_pages' => ceil($total_items/$per_page)
    ));        

  }

  // Funzione per la definizione dei campi che devono
  // essere visualizzati nella lista da visualizzare

  function get_columns()
  {
    $columns = array(
      'ID'                 => 'ID',
      'name'               => 'Nome',
      'surname'            => 'Cognome',
      'company'            => 'Azienda',
      'vat'                => 'Partita iva',
      'address'            => 'Indirizzo',
      'city'               => 'Città',
      'state'              => 'Stato',
      'telephone'          => 'Telefono',
      'mail'               => 'Email',
    );
    return $columns;
  }

  // Funzione per la definizione dei campi che possono
  // essere utilizzati per eseguire la funzione di ordinamento

  function get_columns_sortable()
  {
    $sortable_columns = array(
      'name'       => array('name',true)
    );
    return $sortable_columns;
  }

  // Funzione per la definizione dei campi che devono 
  // essere calcolati dalla query ma non visualizzati

  function get_columns_hidden() {
    return array();
  }

  // Funzione per reperire il valore di un campo in
  // maniera standard senza una personalizzazione di output

  function column_default($item,$column_name) { 
    return $item[$column_name]; 
  }

  

  //FUNZIONE CAMPO ID CON AZIONI
  function column_ID($item) { 
   // Definizioni azioni che devo comparire sotto la 
  // tessera quando andiamo in hover con il mouse

  $nonceEdit = wp_create_nonce('adv_edit_customer');

  $actions = array(
    'edit' => sprintf('<a href="?page=%s&action=%s&customer=%s&_wpnonce=%s">Modifica</a>', 
    esc_attr($_REQUEST['page']), 'edit', absint($item['ID']), 
    $nonceEdit),

  );

  // Ritorno  il valore della colonna tessera e
  // richiamo il metodo row_actions per le azioni 

  return sprintf('%1$s %2$s',$item['ID'],
    $this->row_actions($actions));
  }

}

function adv_SelectCustomerEdit(){
 /*
  *In questa funzione creo un form dove l'utente può modificare i dati
  *ed aggiornare il DB
  */

  //RICAVO I DATI DAL DB
  global $wpdb;

  $sql_row_customer = $wpdb->get_row( $wpdb->prepare(
    "SELECT * FROM " . $wpdb->prefix . "adv_customers WHERE ID= %d",
    intval($_GET['customer'])
  ));

  ?>

  <h1>Cliente:</h1>

  <a href="<?php echo  home_url() . "/wp-admin/admin.php?page=option_adv_management_customer"; ?>" >Torna all'elenco </a>
  <form method="post" >

    <table>

    <tr>
      <th>
        <label for="name">Nome:</label>
      </th>
      <td>  
        <input id="name" name="name" type="text" value="<?php echo $sql_row_customer->name; ?>" >
      </td>
    </tr>

    <tr>
      <th>
        <label for="surname">Cognome:</label>
      </th>
      <td>  
        <input id="surname" name="surname" type="text" value="<?php echo $sql_row_customer->surname; ?>" >
      </td>
    </tr>

    <tr>
      <th>
        <label for="company">Azienda:</label>
      </th>
      <td>  
        <input id="company" name="company" type="text" value="<?php echo $sql_row_customer->company; ?>" >
      </td>
    </tr>

    <tr>
      <th>
        <label for="vat">Partita IVA:</label>
      </th>
      <td>  
        <input id="vat" name="vat" type="text" value="<?php echo $sql_row_customer->vat; ?>" >
      </td>
    </tr>

    <tr>
      <th>
        <label for="address">Indirizzo:</label>
      </th>
      <td>  
        <input id="address" name="address" type="text" value="<?php echo $sql_row_customer->address; ?>" >
      </td>
    </tr>

    <tr>
      <th>
        <label for="city">Descrizione:</label>
      </th>
      <td>  
        <input id="city" name="city" type="text" value="<?php echo $sql_row_customer->city; ?>" >
      </td>
    </tr>

    <tr>
      <th>
        <label for="state">Stato:</label>
      </th>
      <td>  
        <input id="state" name="state" type="text" value="<?php echo $sql_row_customer->state; ?>" >
      </td>
    </tr>

    <tr>
      <th>
        <label for="telephone">Telefono:</label>
      </th>
      <td>  
        <input id="telephone" name="telephone" type="text" value="<?php echo $sql_row_customer->telephone; ?>" >
      </td>
    </tr>

    <tr>
      <th>
        <label for="mail">Email:</label>
      </th>
      <td>  
        <input id="mail" name="mail" type="text" value="<?php echo $sql_row_customer->mail; ?>" >
      </td>
    </tr>

    <tr>
        <input id="ID" name="ID" type="hidden" value="<?php echo $sql_row_customer->ID; ?>" >
        <?php wp_nonce_field( 'nonceEditCustomer', 'editCustomerNonce' ); ?>
        <td><input type="submit" value="Salva" name="submitCustomersettings"> </td>
    </tr>

</table>    
</form>


  <?php

}


//Azione quando l'utente seleziona un articolo
add_action('editCustomer','adv_SelectCustomerEdit');

?>