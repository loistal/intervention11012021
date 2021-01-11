<?php

#ini_set('display_errors', 'On');
#error_reporting(E_ALL);

# Build web page
require ('inc/standard.php');
require ('inc/top.php');
require ('inc/logo.php');
require ('inc/menu.php');

# table
?>
</div><div id="wrapper">
<title>C CLEAN</title>
<div id="leftmenu">
  <div id="selectactionbar">
    <div class="selectaction">
      <?php
      echo '<b>Import:</b><br>';
      echo '&nbsp; <a href="custom.php?custommenu=import">Import CSV compta</a><br>';
      ?>
      <br>
    </div>
  </div>

<?php
require ('inc/searchbox.php');
require ('inc/copyright.php');
?>
</div><div id="mainprogram">
<?php

$custommenu = "";
if (isset($_GET['custommenu'])) { $custommenu = $_GET['custommenu']; }
if (isset($_POST['custommenu'])) { $custommenu = $_POST['custommenu']; }
$custommenu = d_safebasename($custommenu);

$currentstep = 0;
if (isset($_POST['step'])) { $currentstep = $_POST['step']+0; }

# Go to the menuitem
switch($custommenu)
{

  case 'import':
  # config
  $separator = ';';
  $proceed = (int) $_POST['proceed'];

  echo '<h2>Importer Écritures Comptables</h2>';
  if ($_POST['importme'] == 1)
  {
    $fp = fopen ($_FILES['userfile']['tmp_name'],'r');
    $i = -1;
    echo '<table class=report>';
    while ($line=fgets($fp))
    {
      $i++;
      $line = mb_convert_encoding($line, "UTF-8");
      $lineA = explode($separator, $line);
      echo '<tr>';
      foreach ($lineA as $x => $value)
      {
        echo '<td>['.$x.']' . trim($value,'"');
      }
      if ($i>0)
      {
        $date[$i] = substr($lineA[1],4,4).'-'.substr($lineA[1],2,2).'-'.substr($lineA[1],0,2);
        $journal[$i] = $lineA[2];
        $account[$i] = $lineA[3];
        $client[$i] = $lineA[4];
        $debit[$i] = $lineA[5]; if ($debit[$i] == 'D') { $debit[$i] = 1; } else { $debit[$i] = 0; }
        $amount[$i] = (int) $lineA[6];
        $info[$i] = trim($lineA[7],'"');
        $reference[$i] = (int) $lineA[8];
      }
    }
    $num = $i;echo '</table>';
    for ($i=1; $i<=$num; $i++)
    {
      if ($i == 1 || $reference[$i] != $reference[($i-1)])
      {
        $linenr = 0;
        if ($reference > 0)
        {
          $query = 'select journalid from journal where journalname=?';
          $query_prm = array($journal[$i]);
          require('inc/doquery.php');
          if ($num_results)
          {
            $journalid = $query_result[0]['journalid'];
          }
          else
          {
            if ($journal[$i] == '') { $journalid = 0; }
            else
            {
              $query = 'insert into journal (journalname) values (?)';
              $query_prm = array($journal[$i]);
              require('inc/doquery.php');
              $journalid = $query_insert_id; echo '<br>Journal '.$journal[$i].' créé';
            }
          }
          $query = 'insert into adjustmentgroup (userid,adjustmentdate,originaladjustmentdate,adjustmenttime,adjustmentcomment,reference,journalid)
          values (?, ?, curdate(), curtime(), ?, ?, ?)';
          $query_prm = array($_SESSION['ds_userid'], $date[$i], $info[$i], $reference[$i], $journalid);
          require('inc/doquery.php');
          $agid = $query_insert_id;
        }
      }
      if ($agid > 0)
      {
        $query = 'select clientid from client where clientcode=?';
        $query_prm = array($client[$i]);
        require('inc/doquery.php');
        if ($num_results)
        {
          $clientid = $query_result[0]['clientid'];
        }
        else
        {
          $query = 'insert into client (clientname,clientcode) values (?,?)';
          $query_prm = array($client[$i],$client[$i]);
          require('inc/doquery.php');
          $clientid = $query_insert_id; echo '<br>Client '.$client[$i].' créé';
        }
        $query = 'select accountingnumberid from accountingnumber where acnumber=?';
        $query_prm = array($account[$i]);
        require('inc/doquery.php');
        if ($num_results)
        {
          $anid = $query_result[0]['accountingnumberid'];
        }
        else
        {
          $query = 'insert into accountingnumber (acname,acnumber,accountinggroupid) values (?,?,1)';
          $query_prm = array($account[$i],$account[$i]);
          require('inc/doquery.php');
          $anid = $query_insert_id; echo '<br>Compte '.$account[$i].' créé';
        }
        $query = 'insert into adjustment (linenr,debit,adjustmentgroupid,value,accountingnumberid,referenceid) values (?,?,?,?,?,?)';
        $query_prm = array($linenr, $debit[$i], $agid, $amount[$i], $anid, $clientid);
        require('inc/doquery.php');
        $linenr++;
      }
      /*
      echo '<tr><td>'.$date[$i];
      echo '<td>'.$journal[$i];
      echo '<td>'.$account[$i];
      echo '<td>'.$client[$i];
      echo '<td>'.$debit[$i];
      echo '<td>'.$amount[$i];
      echo '<td>'.$info[$i];
      echo '<td>'.$reference[$i];
      */
    }
  }
  else
  {
    ?>
    <form enctype="multipart/form-data" method="post" action="custom.php"><table>
    <tr><td>Fichier:</td><td><input type="hidden" name="MAX_FILE_SIZE" value="1000000"><input type="file" name="userfile" size=50></td></tr>
    <tr><td colspan=2><select name="proceed"><option value=0>Afficher (et ne pas insérer)</option><option value=1>Afficher et insérer</option></select></td></tr>
    <tr><td colspan="2" align="left"><input type=hidden name="importme" value="1"><input type=hidden name="custommenu" value="<?php echo $custommenu; ?>"><input type="submit" value="Import"></table></form><?php
  
    ?><br><p><b>Les champs à renseigner:</b><br>
    <i>ligne</i> optionel<br>
    <i>date</i> format: jjmmaaaa<br>
    <i>journal</i> optionel<br>
    <i>compte</i> le numéro de compte EXACTE comme dans la base<br>
    <i>tiers</i> le NOM du client EXACTE comme dans la base<br>
    <i>d/c</i> débit(D) ou crédit(C)<br>
    <i>montant</i> integer<br>
    <i>libelle</i> optionel<br>
    <i>reference</i> OBLIGATOIRE, champs texte pour identifier l'écriture (different pour chaque écriture)<br>
    <br>Exemple:<br>
    </p>
<pre>ligne;date;journal;compte;tiers;d/c;montant;libelle;reference
1;01082019;VTE;411000;CLIENT X;D;16000;"Facture EXEMPLE";abc123
2;01082019;VTE;445713;;C;1841;"Facture EXEMPLE";abc123
3;01082019;VTE;706030;;C;14159;"Facture EXEMPLE";abc123
4;01082019;VTE;411000;EXEMPLE Y;D;22600;"Facture EXEMPLE";def456
5;01082019;VTE;445713;;C;2600;"Facture EXEMPLE";def456
6;01082019;VTE;706030;;C;20000;"Facture EXEMPLE";def456</pre>
    <?php
  }
  break;
  

  default:

  break;
}
?>

<?php
require ('inc/bottom.php');
?>