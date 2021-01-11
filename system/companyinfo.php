<?php

# refactor! SESSION varaibles and nonsense

#We force the id to 1, because we only need one row for this table
$companyinfoid = 1;
$can_modify_closingdate = 1;
$query = 'select adjustmentgroupid,adjustmentdate from adjustmentgroup where closing=1 and deleted=0 order by adjustmentdate desc limit 1';
$query_prm = array();
require('inc/doquery.php');
if ($num_results > 0) { $can_modify_closingdate = 0; }

if(isset($_POST['tva_encaissement']))
{
  $idtahiti = $_POST['idtahiti'];
  $rc = $_POST['rc'];
  $socialsecuritynumber = $_POST['socialsecuritynumber'];
  $companyname = $_POST['companyname'];
  $infoactivity = $_POST['infoactivity'];
  $infophonenumber = $_POST['infophonenumber'];
  $infoaddress1 = $_POST['infoaddress1'];
  $infoaddress2 = $_POST['infoaddress2'];
  $infoemail = $_POST['infoemail'];
  $postaladdress = $_POST['postaladdress'];
  $postalcode = $_POST['postalcode'];
  $infocity = $_POST['infocity'];
  $tva_encaissement = $_POST['tva_encaissement']+0;
  $tva_decl_type = $_POST['tva_decl_type']+0;
  $socialsecuritysectorid = $_POST['socialsecuritysectorid'];
  $collectiveagreementid = $_POST['collectiveagreementid'];
  $seniority_bonus_calc = $_POST['seniority_bonus_calc'];
  $datename = 'accounting_closingdate'; require('inc/datepickerresult.php');

  #We display the value if we find a row in the database
  $query = 'SELECT * FROM companyinfo WHERE companyinfoid = ?'; 
  $query_prm = array($companyinfoid);

  require('inc/doquery.php');

  #if record exists we update the record
  if ($num_results == 0)
  {
    $query = 'INSERT INTO companyinfo (companyinfoid) values (1)';
    $query_prm = array();
    require('inc/doquery.php');
  }
  $query = 'UPDATE companyinfo SET rc=?,seniority_bonus_calc=?,collectiveagreementid=?,socialsecuritynumber=?,tva_decl_type=?,tva_encaissement=?,idtahiti = ?, companyname = ?, infoactivity = ?, infophonenumber = ?, infoaddress1 = ?, infoaddress2 = ?, infoemail = ?, postaladdress = ?, postalcode = ?, infocity = ?,socialsecuritysectorid=? WHERE companyinfoid = ?'; 
  $query_prm = array($rc,$seniority_bonus_calc,$collectiveagreementid,$socialsecuritynumber,$tva_decl_type,$tva_encaissement,$idtahiti, $companyname, $infoactivity, $infophonenumber, $infoaddress1, $infoaddress2, $infoemail, $postaladdress, $postalcode, $infocity,$socialsecuritysectorid, $companyinfoid);
  require('inc/doquery.php');
  if ($can_modify_closingdate)
  {
    $query = 'update companyinfo set accounting_closingdate=? where companyinfoid=?';
    $query_prm = array($accounting_closingdate, $companyinfoid);
    require('inc/doquery.php');
  }
}

$query = 'SELECT * FROM companyinfo WHERE companyinfoid = ?';
$query_prm = array($companyinfoid);

require('inc/doquery.php');

if($num_results > 0) 
{
  #if the record exist we display the value in each input
  $query = 'SELECT * FROM companyinfo WHERE companyinfoid = ?'; 
  $query_prm = array($companyinfoid);

  require('inc/doquery.php');
  $row = $query_result[0];
  $seniority_bonus_calc = $row['seniority_bonus_calc'];
  $collectiveagreementid = $row['collectiveagreementid'];
  $idtahitivalue = $row['idtahiti'];
  $rc = $row['rc'];
  $socialsecuritynumber = $row['socialsecuritynumber'];
  $companyname = $row['companyname'];
  $infoactivity = $row['infoactivity'];
  $infophonenumber = $row['infophonenumber'];
  $infoaddress1 = $row['infoaddress1'];
  $infoaddress2 = $row['infoaddress2'];
  $infoemail = $row['infoemail'];
  $postaladdress = $row['postaladdress'];
  $postalcode = $row['postalcode'];
  $infocity = $row['infocity'];
  $_SESSION['ds_tva_encaissement'] = $row['tva_encaissement'];
  $_SESSION['ds_tva_decl_type'] = $row['tva_decl_type'];
  $_SESSION['ds_socialsecuritysectorid'] = $row['socialsecuritysectorid']; # SESSION varaibles???? TODO fix
  $_SESSION['ds_accounting_closingdate'] = $row['accounting_closingdate'];
}    
?>

<h2>Informations concernant votre entreprise :</h2>
<?php
if ($dauphin_currentmenu == 'options') { echo '<form method="post" action="options.php">'; }
else { echo '<form method="post" action="system.php">'; }
?>
	<table>
        <tr>
          <td>N° TAHITI :</td>
          <td><input type="text" name="idtahiti" size="20" value="<?php if(isset($idtahitivalue) && !empty($idtahitivalue)) { echo d_input($idtahitivalue); } ?>"></td>
        </tr>
        
        <tr>
          <td>RC :</td>
          <td><input type="text" name="rc" size="20" value="<?php if(isset($rc) && !empty($rc)) { echo d_input($rc); } ?>"></td>
        </tr>
        
        <tr>
          <td>Matricule CPS :</td>
          <td><input type="text" name="socialsecuritynumber" size="20" value="<?php if(isset($socialsecuritynumber) && !empty($socialsecuritynumber)) { echo d_input($socialsecuritynumber); } ?>"></td>
        </tr>

        <tr>
          <td>Nom / Raison sociale :</td>
          <td><input type="text" name="companyname" size="20" value="<?php if(isset($companyname) && !empty($companyname)) { echo d_input($companyname); } ?>"></td>
        </tr>

        <tr>
          <td>Activité exercée :</td>
          <td><input type="text" name="infoactivity" size="20" value="<?php if(isset($infoactivity) && !empty($infoactivity)) { echo d_input($infoactivity); } ?>"></td>
        </tr>

        <tr>
          <td>Téléphone :</td>
          <td><input type="text" name="infophonenumber" size="20" value="<?php if(isset($infophonenumber) && !empty($infophonenumber)) { echo d_input($infophonenumber); } ?>"></td>
        </tr>

        <tr>
          <td>Adresse 1 :</td>
          <td><input type="text" name="infoaddress1" size="20" value="<?php if(isset($infoaddress1) && !empty($infoaddress1)) { echo d_input($infoaddress1); } ?>"></td>
        </tr>

        <tr>
          <td>Adresse 2 :</td>
          <td><input type="text" name="infoaddress2" size="20" value="<?php if(isset($infoaddress2) && !empty($infoaddress2)) { echo d_input($infoaddress2); } ?>"></td>
        </tr>

        <tr>
          <td>Adresse mail de la société ou du représentant légal :</td>
          <td><input type="text" name="infoemail" size="20" value="<?php if(isset($infoemail) && !empty($infoemail)) { echo d_input($infoemail); } ?>"></td>
        </tr>

        <tr>
          <td>Boîte postale :</td>
          <td><input type="text" name="postaladdress" size="20" value="<?php if(isset($postaladdress) && !empty($postaladdress)) { echo d_input($postaladdress); } ?>"></td>
        </tr>

        <tr>
          <td>Code postal :</td>
          <td><input type="text" name="postalcode" size="20" value="<?php if(isset($postalcode) && !empty($postalcode)) { echo d_input($postalcode); } ?>"></td>
        </tr>

        <tr>
          <td>Commune :</td>
          <td><input type="text" name="infocity" size="20" value="<?php if(isset($infocity) && !empty($infocity)) { echo d_input($infocity); } ?>"></td>
        </tr>
        
        <tr>
          <td>TVA sur :</td>
          <td><select name="tva_encaissement"><option value=0>Débit</option><option value=1
          <?php if ($_SESSION['ds_tva_encaissement'] == 1) { echo ' selected'; } ?>
          >Encaissements</option><option value=2
          <?php if ($_SESSION['ds_tva_encaissement'] == 2) { echo ' selected'; } ?>
          >Mixte</option></select></td>
        </tr>
        
        <tr>
          <td>Régime TVA :</td>
          <td><select name="tva_decl_type"><option value=0>Réel</option><option value=1
          <?php if ($_SESSION['ds_tva_decl_type'] == 1) { echo ' selected'; } ?>
          >Simplifié</option></select></td>
        </tr>
        
        <tr><td>Secteur CPS:<?php $dp_itemname = 'socialsecuritysector'; $dp_selectedid = $_SESSION['ds_socialsecuritysectorid']; require('inc/selectitem.php'); ?>
        
        <tr><td>Convention collective:<?php $dp_itemname = 'collectiveagreement'; $dp_selectedid = $collectiveagreementid; require('inc/selectitem.php'); ?>
        
        <tr><td>Calcul de la prime d'ancienneté:<td><select name="seniority_bonus_calc">
        <option value=0>Manuel</option>
        <option value=1<?php if ($seniority_bonus_calc == 1) { echo ' selected'; } ?>>Standard</option>
        <option value=2<?php if ($seniority_bonus_calc == 2) { echo ' selected'; } ?>>1,5% après 10 ans</option>
        </select>

        <tr>
        <td>Date de clôture du premier exercice :
        <td><?php # TODO no modifying this after first closing exists
        $datename = 'accounting_closingdate';
        $selecteddate = $_SESSION['ds_accounting_closingdate']; if ($selecteddate == NULL) { $dp_setempty = 1; }
        if ($can_modify_closingdate == 1)
        {
          require('inc/datepicker.php');
        }
        else { echo datefix2($_SESSION['ds_accounting_closingdate']); }
        ?>
        
        <tr>
          <td colspan="2" align="center">
            <?php
            if ($dauphin_currentmenu == 'options') { echo '<input type="hidden" name="optionsmenu" value="' . $optionsmenu . '">'; }
            else  { echo '<input type="hidden" name="systemmenu" value="' . $systemmenu . '">'; }
            ?>
            <input type="submit" value="Valider">
          </td>
        </tr>
        
    </table>
</form>       
