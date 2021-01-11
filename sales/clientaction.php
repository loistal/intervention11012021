<?php

$PA['close_case'] = 'uint';
$PA['client'] = '';
$PA['clientactionfield1'] = '';
$PA['actionname'] = '';
$PA['employeeid'] = 'uint';
$PA['clientactioncatid'] = 'uint';
$PA['originid'] = 'uint';
$PA['contact_typeid'] = 'uint';
require('inc/readpost.php');

$dp_allowdeletedclients = 1;
require('inc/findclient.php');

if ($clientid > 0 && $actionname != '')
{
  $datename = 'actiondate'; require('inc/datepickerresult.php');
  $query = 'insert into clientaction (originid,contact_typeid,clientid,actiondate,employeeid,clientactioncatid
  ,actionname,userid,clientactionfield1)
  values (?,?,?,?,?,?,?,?,?)';
  $query_prm = array($originid, $contact_typeid, $clientid, $actiondate, $employeeid, $clientactioncatid, $actionname
  , $_SESSION['ds_userid'], $clientactionfield1);
  require('inc/doquery.php');
  if ($num_results)
  {
    echo '<p>Évènement ajouté.</p>';
    $clientactionid = $query_insert_id;
  }
  $filename = $_FILES['imagefile']['tmp_name'];
  if ($clientactionid && is_uploaded_file($filename))
  {
    $image = file_get_contents($filename);
		$imagetype = pathinfo ( $_FILES['imagefile']['name'], PATHINFO_EXTENSION);		

    if ($image)
    {
      $query = 'insert into image (image,imagetype) values (?,?)';
      $query_prm = array($image,$imagetype);
      require ('inc/doquery.php');
      if ($num_results)
      {
        $imageid = $query_insert_id;
        $query = 'update clientaction set imageid=? where clientactionid=?';
        $query_prm = array($imageid, $clientactionid);
        require ('inc/doquery.php');
      }
    }
  }
  echo '<br>'; # TODO better feedback
}

?>
<h2>Évènement</h2>
<form enctype="multipart/form-data" method="post" action="sales.php">
<table><tr><td>Date :<td><?php
$datename = 'actiondate';
require('inc/datepicker.php');
?><tr><td>
<?php
require ('inc/selectclient.php');
?>
<tr><td>Provenance :<td><select name="originid"><option value=0>Client</option>
<?php
echo '<option value=1'; if ($originid == 1) { echo ' selected'; }
echo '>'.d_output($_SESSION['ds_customname']).'</option>';
?>
</select>
<tr><td>Type d'intéraction :<td>
<select name="contact_typeid">
<option value=0></option><?php
echo '<option value=1'; if ($contact_typeid == 1) { echo ' selected'; } echo '>Téléphone</option>';
echo '<option value=2'; if ($contact_typeid == 2) { echo ' selected'; } echo '>E-mail</option>';
echo '<option value=3'; if ($contact_typeid == 3) { echo ' selected'; } echo '>Contact direct</option>';
?></select>
<tr><td>Évènement :<td><input type="text" STYLE="text-align:left" name="actionname" value="<?php echo $actionname; ?>" size=80>
<?php
if (isset($_SESSION['ds_term_clientactionfield1']) && $_SESSION['ds_term_clientactionfield1'] != '')
{
  echo '<tr><td>' . d_output($_SESSION['ds_term_clientactionfield1']) . ' :
  <td><input type="text" STYLE="text-align:left" name="clientactionfield1" value="' . $clientactionfield1 . '" size=80>';
}
?>
<tr><td colspan=2>&nbsp;
<tr><td>Employé(e) :<?php
$dp_itemname = 'employee'; $dp_selectedid = $employeeid;
require('inc/selectitem.php');
?>
<tr><td>Catégorie d'action :<?php
$dp_itemname = 'clientactioncat'; $dp_selectedid = $clientactioncatid;
require('inc/selectitem.php');
echo '<tr><td colspan=2>&nbsp;<tr><td>Ajouter image :</td><td><input name="imagefile" type="file" size=50>
<tr><td colspan="2" align="center"><input type=hidden name="salesmenu" value="' . $salesmenu . '">
<input type="submit" value="Valider"></table></form>';

?>