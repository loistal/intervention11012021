<?php

# 2016 03 11 refactor/remake needed

#clients/modclient

# Initialize all GET/POST variables
require('modclient/readpostvars.php');

$err_clientname_dup = 0;
$err_clientname_empty = 0;

# UPDATE
if ($saveme == 1 && $clientid > 0) 
{
  # log any account status changes
  require('modclient/blockclient.php');
  
  # takes care of updating the client in the database
  require('modclient/updateclient.php'); 
}

# Find client
if (!isset($_POST['clientid'])) { require('inc/findclient.php'); }

# Update client form
if ($clientid < 1 && $addclient != 1) 
{
?>
<form method="post" action="clients.php">
  <fieldset>
    <legend>Modifier Client
    <?php
    if ($_SESSION['ds_purchaseaccess'] || $_SESSION['ds_accountingaccess']) 
    { 
      echo '/ Fournisseur '; 
    }
    ?>
    </legend>
    <?php
      $canaddclient = 1;
      require('inc/selectclient.php');
    ?>
    <input type=hidden name="step" value="0">
    <input type=hidden name="clientsmenu" value="<?php echo $clientsmenu ?>">
    <input type="submit" value="Valider">
  </fieldset>
</form>

<?php
}

if ($addclient == 1) 
{
  $onlyaddclient = 1;
  require('inc/selectclient.php');
}

if ($clientid > 0) 
{
  # get the client
  $query = 'select * from client where clientid=?';
  $query_prm = array($clientid);
  require('inc/doquery.php');
  $rowclient = $query_result[0];

  echo '<h2>Modifier Client ';
  if ($_SESSION['ds_purchaseaccess'] || $_SESSION['ds_accountingaccess']) {
    echo '/ Fournisseur ';
  }
  echo $clientid, ':</h2>';

?>
  <form method="post" action="clients.php">
  
  <input type=hidden name="step" value="0">
  <input type=hidden name="saveme" value="1">
  <input type=hidden name="clientsmenu" value="<?php echo $clientsmenu; ?>">
  <input type=hidden name="clientid" value="<?php echo $clientid ?>">

<?php
  $css_alert = '';
  if ($err_clientname_empty || $err_clientname_dup) { $css_alert = 1; }

?>

<table>
  <tr>
    <td>Nom:
    <td>
      <input type="text" name="clientname" 
             value="<?php echo d_input(d_decode($clientname)) ?>" size=50>
  <tr>
    <td>Prénom:
    <td>
      <input type="text" name="clientfirstname" 
             value="<?php echo d_input($rowclient['clientfirstname']) ?>" size=50>
  <tr>
    <td>Code client:
    <td>
      <input type="text" name="clientcode" 
             value="<?php echo d_input($rowclient['clientcode']) ?>" size=50>
  <tr>
    <td>Raison sociale:
    <td>
      <input type="text" name="companytypename" 
             value="<?php echo d_input($rowclient['companytypename']) ?>" size=20>
  <tr>
    <td>No Tahiti:
    <td>
      <input type="text" name="tahitinumber" 
             value="<?php echo d_input($rowclient['tahitinumber']) ?>" size=20>
  <tr>
    <td>Date de création:
    <td>

<?php

  $datename = 'creationdate';
  $selecteddate = $rowclient['creationdate'];
  if ($selecteddate == NULL) { $dp_setempty = 1; }
  require('inc/datepicker.php');

?>

&nbsp; Dossier: 
<select name="dossier">
  <option value=0>Non</option>
  <option value=1 <?php if ($rowclient['dossier']) { echo ' selected'; } ?> >Oui</option>
</select>
<tr> 
  <td>Peut acheter (interdit):
  <td>
    <select name="blocked">
      <option value="0">COMPTE NORMAL</option>
      <option value="2" <?php if ($rowclient['blocked'] == 2) { echo 'selected'; } ?>
        >COMPTE SUSPENDU</option>
      <option value="1" <?php if ($rowclient['blocked'] == 1) { echo 'selected'; } ?>
        >COMPTE INTERDIT</option>
    </select>
<tr>
  <td>Type de prix:
  <td>
    <select name="usedetail">
      <option value="0" <?php if ($rowclient['usedetail'] == 0) { echo 'selected'; } ?>>
        Normal
      </option>
      
      <?php if ($_SESSION['ds_term_prixalternatif'] !== ''): ?>
      
        <option value="1" <?php if ($rowclient['usedetail'] != 0) { echo 'selected'; } ?>> 
          <?php echo $_SESSION['ds_term_prixalternatif'] ?>
        </option>

      <?php endif; ?>

    </select>
<tr>
  <td>Remise par défaut:
  <td>
    <input type=number min=0 size=5 name="discount" 
           value="<?php echo d_input($rowclient['discount'])?>"> %
<tr>
  <td>Majoration:
  <td>
    <input type=number min=0 size=5 name="surcharge" 
           value="<?php echo d_input($rowclient['surcharge'])?>"> %
<tr>
  <td>Paiement TVA:
  <td>
    <select name="vatexempt">
      <option value="0" <?php if ($rowclient['vatexempt'] == 0) { echo 'selected'; } ?>>Oui</option>
      <option value="1" <?php if ($rowclient['vatexempt'] == 1) { echo 'selected'; } ?>>Non</option>
    </select>
<tr>
  <td>Délai de paiement:


<?php

$dp_selectedid = $rowclient['clienttermid'];
$dp_itemname = 'clientterm';
require('inc/selectitem.php');
 
?>

<tr>
  <td colspan=2>&nbsp;

<?php if ($_SESSION['ds_term_clientfield1'] != ''): ?>
  <tr>
    <td>
      <?php echo d_output($_SESSION['ds_term_clientfield1']) ?>:
    <td>
      <input type=text name="clientfield1" value="<?php echo d_input($rowclient['clientfield1'])?>">
<?php endif; ?>

<?php if ($_SESSION['ds_term_clientfield2'] != ''): ?>
  <tr>
    <td>
      <?php echo d_output($_SESSION['ds_term_clientfield2']) ?>:
    <td>
      <input type=text name="clientfield2" value="<?php echo d_input($rowclient['clientfield2'])?>">
<?php endif; ?>

<?php if ($_SESSION['ds_term_clientfield3'] != ''): ?>
  <tr>
    <td>
      <?php echo d_output($_SESSION['ds_term_clientfield3']) ?>:
    <td>
      <input type=text name="clientfield3" value="<?php echo d_input($rowclient['clientfield3'])?>">
<?php endif; ?>

<?php if ($_SESSION['ds_term_clientfield4'] != ''): ?>
  <tr>
    <td>
      <?php echo d_output($_SESSION['ds_term_clientfield4']) ?>:
    <td>
      <input type=text name="clientfield4" value="<?php echo d_input($rowclient['clientfield4'])?>">
<?php endif; ?>

<?php if ($_SESSION['ds_term_clientfield5'] != ''): ?>
  <tr>
    <td>
      <?php echo d_output($_SESSION['ds_term_clientfield5']) ?>:
    <td>
      <input type=text name="clientfield5" value="<?php echo d_input($rowclient['clientfield5'])?>">
<?php endif; ?>

<?php if ($_SESSION['ds_term_clientfield6'] != ''): ?>
  <tr>
    <td>
      <?php echo d_output($_SESSION['ds_term_clientfield6']) ?>:
    <td>
      <input type=text name="clientfield6" value="<?php echo d_input($rowclient['clientfield6'])?>">
<?php endif; ?>

<?php

if ($_SESSION['ds_term_client_customdate1'] != '') 
{
  echo '<tr><td>Date ' . $_SESSION['ds_term_client_customdate1'] . ':<td>';
  $datename = 'client_customdate1';
  $selecteddate = $rowclient['client_customdate1'];
  if ($selecteddate == NULL) {
    $dp_setempty = 1;
  }
  require('inc/datepicker.php');
}

if ($_SESSION['ds_term_client_customdate2'] != '') 
{
  echo '<tr><td>Date ' . $_SESSION['ds_term_client_customdate2'] . ':<td>';
  $datename = 'client_customdate2';
  $selecteddate = $rowclient['client_customdate2'];
  if ($selecteddate == NULL) {
    $dp_setempty = 1;
  }
  require('inc/datepicker.php');
}

if ($_SESSION['ds_term_client_customdate3'] != '') 
{
  echo '<tr><td>Date ' . $_SESSION['ds_term_client_customdate3'] . ':<td>';
  $datename = 'client_customdate3';
  $selecteddate = $rowclient['client_customdate3'];
  if ($selecteddate == NULL) {
    $dp_setempty = 1;
  }
  require('inc/datepicker.php');
}

?>

<?php if ($_SESSION['ds_use_loyalty_points']): ?>
  <tr>
    <td colspan=2>&nbsp;
  <tr>
    <td>Points de Fidelité :
    <td>
    <input type=checkbox name="use_loyalty_points" value="1" 
           <?php if ($rowclient['use_loyalty_points'] == 1) { echo ' checked'; } ?>>
  <tr>
    <td>Date Fidelité:
    <td>
      <?php 
        $datename = 'loyaltydate';
        $selecteddate = $rowclient['loyaltydate'];
        if ($selecteddate == NULL) { $dp_setempty = 1; }
        require('inc/datepicker.php');
      ?>
  <tr>
    <td>Points au début :
    <td>
      <input type=number name="loyalty_start" value="<?php echo $rowclient['loyalty_start'] ?>">
<?php endif; ?>

<tr>
  <td colspan=2>&nbsp;
<tr>
  <td colspan=10>
    <input type=checkbox name="isclient" value="1" 
      <?php if ($rowclient['isclient'] == 1) { echo ' checked'; }?>> Client &nbsp; &nbsp; 
    <input type=checkbox name="issupplier" value="1" 
      <?php if ($rowclient['issupplier'] == 1) { echo ' checked'; }?>> Fournisseur &nbsp; &nbsp; 
    <input type=checkbox name="isemployee" value="1" 
      <?php if ($rowclient['isemployee'] == 1) { echo ' checked'; }?>> Salarié &nbsp; &nbsp; 
    <input type=checkbox name="isother" value="1" 
      <?php if ($rowclient['isother'] == 1) { echo ' checked'; }?>> Autre
<tr>
  <td>Lead time (mois):
  <td>
    <input type=number min=0 name="leadtime" value="<?php echo d_input($rowclient['leadtime']) ?>">
    <tr>
    <td colspan=2>
    &nbsp;
<tr>
  <?php $term_clientemployee1 = lcfirst($_SESSION['ds_term_clientemployee1']); ?>
  <td>Employé <?php echo $term_clientemployee1; ?>
  
<?php

$dp_selectedid = $rowclient['employeeid'];
$dp_itemname = 'employee';
$dp_iscashier = 1;
require('inc/selectitem.php');
 
?>   

<tr>
  <?php $term_clientemployee2 = lcfirst($_SESSION['ds_term_clientemployee2']); ?>
  <td>Employé <?php echo $term_clientemployee2; ?>
  
<?php

$dp_selectedid = $rowclient['employeeid2'];
$dp_itemname = 'employee';
$dp_iscashier = 1;
$dp_addtoid = 2;
require('inc/selectitem.php');
 
?> 

<tr>
  <td colspan=2>&nbsp;
<tr>
  <td>Adresse ligne 1:
  <td>
    <input type="text" name="address" value="<?php echo d_input($rowclient['address']) ?>"  size=50>
<tr>
  <td>Adresse ligne 2:
  <td>
    <input type="text" name="postaladdress" 
           value="<?php echo d_input($rowclient['postaladdress'])?>"  size=50>
<tr>
  <td>Code Postal:
  <td>
    <input type="text" name="postalcode" value="<?php echo d_input($rowclient['postalcode']) ?>" 
           size=50>
<tr>
  <td>Ile/Ville:
  <td>
    <select name="townid">

<?php 

  $query = 'select townid,townname,islandname ' 
         . 'from town,island '
         . 'where town.islandid=island.islandid '
         . 'order by islandname,townname';
  require('inc/doquery.php');

  for ($i = 0; $i < $num_results; $i++) 
  {
    $selected = '';
    if ($query_result[$i]['townid'] == $rowclient['townid']) { $selected = ' selected'; }
    echo '<option value="' . $query_result[$i]['townid'] . '"' . $selected . '>' 
      . d_input($query_result[$i]['islandname']) . '/' 
      . $query_result[$i]['townname'] . '</option>';
  }

?>

    </select>
<tr>
  <td>Ville (hors PF / manuel):
  <td>
    <input type="text" name="town_name" value="<?php echo d_input($rowclient['town_name']) ?>"  
           size=50>
<tr>
  <td>Pays:

<?php 

$dp_selectedid = $rowclient['countryid'];
$dp_itemname = 'country';
require('inc/selectitem.php');

?>

<tr>
  <td>Adresse géo:
  <td>
    <input type="text" name="quarter" value="<?php echo d_input($rowclient['quarter']) ?>"  size=50> 
    (comment trouver ce client)
<tr>
  <td>Contact:
  <td>
    <input type="text" name="contact" value="<?php echo d_input($rowclient['contact']) ?>"  size=80>
<tr>
  <td>Contact 2:
  <td>
    <input type="text" name="contact2" value="<?php echo d_input($rowclient['contact2']) ?>"  
           size=80>
<tr>
  <td>Contact 3:
  <td>
    <input type="text" name="contact3" value="<?php echo d_input($rowclient['contact3']) ?>" 
           size=80>

<?php if ($_SESSION['ds_term_client_telephone'] != ''): ?>
  <tr>
    <td>
      <?php echo d_output($_SESSION['ds_term_client_telephone'])?>:
    <td>
      <input type="text" name="telephone" value="<?php echo d_input($rowclient['telephone']) ?>"  
             size=80>
<?php endif; ?>

<?php if ($_SESSION['ds_term_client_cellphone'] != ''): ?>
  <tr>
    <td>
      <?php echo d_output($_SESSION['ds_term_client_cellphone'])?>:
    <td>
      <input type="text" name="cellphone" value="<?php echo d_input($rowclient['cellphone']) ?>"  
             size=80>
<?php endif; ?>

<?php if ($_SESSION['ds_term_client_telephone3'] != ''): ?>
  <tr>
    <td>
      <?php echo d_output($_SESSION['ds_term_client_telephone3'])?>:
    <td>
      <input type="text" name="telephone3" value="<?php echo d_input($rowclient['telephone3']) ?>"  
             size=80>
<?php endif; ?>

<?php if ($_SESSION['ds_term_client_telephone4'] != ''): ?>
  <tr>
    <td>
      <?php echo d_output($_SESSION['ds_term_client_telephone4'])?>:
    <td>
      <input type="text" name="telephone4" value="<?php echo d_input($rowclient['telephone4']) ?>"  
             size=80>
<?php endif; ?>

<tr>
  <td>Fax:
  <td>
    <input type="text" name="fax" value="<?php echo d_input($rowclient['fax']) ?>"  size=80>

<?php if ($_SESSION['ds_term_client_email'] != ''): ?>
  <tr>
    <td>
      <?php echo d_output($_SESSION['ds_term_client_email'])?>:
    <td>
      <input type="text" name="email" value="<?php echo d_input($rowclient['email']) ?>"  
             size=80>
<?php endif; ?>

<?php if ($_SESSION['ds_term_client_email2'] != ''): ?>
  <tr>
    <td>
      <?php echo d_output($_SESSION['ds_term_client_email2'])?>:
    <td>
      <input type="text" name="email2" value="<?php echo d_input($rowclient['email2']) ?>"  
             size=80>
<?php endif; ?>

<?php if ($_SESSION['ds_term_client_email3'] != ''): ?>
  <tr>
    <td>
      <?php echo d_output($_SESSION['ds_term_client_email3'])?>:
    <td>
      <input type="text" name="email3" value="<?php echo d_input($rowclient['email3']) ?>"  
             size=80>
<?php endif; ?>

<?php if ($_SESSION['ds_term_client_email4'] != ''): ?>
  <tr>
    <td>
      <?php echo d_output($_SESSION['ds_term_client_email4'])?>:
    <td>
      <input type="text" name="email4" value="<?php echo d_input($rowclient['email4']) ?>"  
             size=80>
<?php endif; ?>

<tr>
  <td>Email pour Factures/Relevés:
    <td>
      <input type="text" name="batchemail" value="<?php echo d_input($rowclient['batchemail']) ?>"  
             size=80>
<tr>
  <td colspan=2>&nbsp;

<?php

$dp_description = $_SESSION['ds_term_clientcategory'];
$dp_selectedid = $rowclient['clientcategoryid'];
$dp_itemname = 'clientcategory';
require('inc/selectitem.php');

$dp_description = $_SESSION['ds_term_clientcategory2'];
$dp_selectedid = $rowclient['clientcategory2id'];
$dp_itemname = 'clientcategory2';
require('inc/selectitem.php');

$dp_description = $_SESSION['ds_term_clientcategory3'];
$dp_selectedid = $rowclient['clientcategory3id'];
$dp_itemname = 'clientcategory3';
require('inc/selectitem.php');

?>

<tr>
  <td>Secteur:

<?php 

$dp_selectedid = $rowclient['clientsectorid'];
$dp_itemname = 'clientsector';
require('inc/selectitem.php');

?>

    <tr>
      <td>RC 
      <td>
        <input type="text" name="rc" value="<?php echo d_input($rowclient['rc']) ?>"  size=20>
    <tr>
      <td>Limite de crédit:
      <td>
        <input type="text" name="outstandinglimit" 
              value="<?php echo d_input($rowclient['outstandinglimit']) ?>"  size=20>
    <tr>
      <td>Référence de banque:
        <td>
          <input type="text" name="bankreference" 
                value="<?php echo d_input($rowclient['bankreference']) ?>"  size=30>
    <tr>
      <td>Commentaire:
        <td>
          <input type="text" name="comment" 
                value="<?php echo d_input($rowclient['clientcomment']) ?>"  size=100>
    <tr>
      <td valign=top>Historique:
      <td>
        <textarea name="clienthistory" rows=6 
                  cols=80><?php echo d_input($rowclient['clienthistory'])?></textarea>
    <tr>
      <td>&nbsp;
    <tr>
      <td colspan=2 align=left>Coordonnées bancaires
    <tr>
      <td>Titulaire:
      <td>
        <input type="text" name="titu" value="<?php echo d_input($rowclient['titu']) ?>"  size=50>
    <tr>
      <td>Domiciliation:
      <td>
        <input type="text" name="domi" value="<?php echo d_input($rowclient['domi']) ?>"  size=50>
    <tr>
      <td>Code Banque (5):
      <td>
        <input type="text" name="codebanque" value="<?php echo d_input($rowclient['codebanque']) ?>" 
              size=50>
    <tr>
      <td>Code Guichet (5):
      <td>
        <input type="text" name="guichet" value="<?php echo d_input($rowclient['guichet']) ?>" 
               size=50>
    <tr>
      <td>No Compte (11):
      <td>
        <input type="text" name="account" value="<?php echo d_input($rowclient['account']) ?>" 
               size=50>
    <tr>
      <td>Clé RIB:
      <td>
        <input type="text" name="clerib" value="<?php echo d_input($rowclient['clerib']) ?>"  
               size=50>
        <input type=hidden name="clientsmenu" value="<?php echo $clientsmenu ?>">
    <tr>
      <td colspan="2" align="center">
        <input type="submit" value="Valider">

  </table> 
</form>

<?php }