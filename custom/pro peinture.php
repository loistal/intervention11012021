<?php

# Build web page
require ('inc/standard.php');
require ('inc/top.php');
require ('inc/logo.php');
require ('inc/menu.php');

# table
?>
</div><div id="wrapper">
<title>Pro Peinture</title>
<div id="leftmenu">
  <div id="selectactionbar">
    <div class="selectaction">
      <?php
      if ($_SESSION['ds_systemaccess'])
      {
      #echo '&nbsp; <a href="custom.php?custommenu=import">Fichier pour compta</a><br>';
      #echo '<br>';
      echo '&nbsp; <a href="custom.php?custommenu=usermargin">Marge par utilisateur</a><br>';
      echo '<br>';
      echo '&nbsp; <a href="custom.php?custommenu=suppliermargin">Marge par fournisseur</a><br>';
      }
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
  
  case 'usermargin':
  require('reports/usermargin.php');
  break;
  
  case 'suppliermargin':
    echo '<h2>Marge par fournisseur</h2>';
    ?>
    <form method="post" action="customreportwindow.php" target="_blank"><table><?php
    echo '<tr><td>Début:</td><td>';
    $datename = 'startdate';
    require ('inc/datepicker.php');
    echo '</td></tr>';
    echo '<tr><td>Fin:</td><td>';
    $datename = 'stopdate';
    require ('inc/datepicker.php');
    echo '</td></tr>';
    echo '<tr><td>Fournisseur:<td><select name="supplierid"><option value=0></option>';
    $query = 'select clientid,clientname from client where deleted=0 and issupplier=1 order by clientname';
    $query_prm = array();
    require('inc/doquery.php');
    for ($i=0; $i < $num_results; $i++)
    {
      echo '<option value="' . $query_result[$i]['clientid'] . '"';
      echo '>' . d_output(d_decode($query_result[$i]['clientname'])) . '</option>';
    }
    echo '</select>';
    ?>
    <tr><td colspan="2" align="center"><input type="submit" value="Valider"></td></tr>
    <input type=hidden name="report" value="suppliermargin">
    </table></form><?php
  break;

  case 'import':
  
  $PA['go'] = 'int';
  $PA['start'] = 'date';
  $PA['stop'] = 'date';
  require('inc/readpost.php');
  
  if ($go == 1)
  {
    require('preload/accountingnumber.php');
    require('preload/user.php');
    
    echo '<p>Fichier du ',datefix2($start),' à ',datefix2($stop),'</p>';
    
    $query = 'select distinct adjustment.adjustmentgroupid
    from adjustment,adjustmentgroup
    where adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid
    and accountingnumberid=1 and integrated=1 and adjustmentdate>=? and adjustmentdate<=?
    order by adjustmentdate';
    $query_prm = array($start, $stop);
    require('inc/doquery.php');
    for ($i=0;$i<=$num_results;$i++)
    {
      $agid_listA[] = $query_result[$i]['adjustmentgroupid'];
    }
    $agid_listA = array_filter(array_unique($agid_listA));
    sort($agid_listA);
    echo '<table class="report"><thead><th>Ecriture<th>Date<th>Client<th>Compte<th>Référence<th>Utilisateur<th>Débit<th>Crédit</thead>';
    foreach ($agid_listA as $agid)
    {
      $query = 'select adjustment.adjustmentgroupid,adjustmentdate,referenceid,accountingnumberid,adjustmentcomment,reference,userid,debit,value,matchingid
      from adjustment,adjustmentgroup
      where adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid
      and adjustmentgroup.adjustmentgroupid=?
      order by accountingnumberid';
      $query_prm = array($agid);
      require('inc/doquery.php');
      $main_result = $query_result; $num_results_main = $num_results;
      for ($i=0; $i < $num_results_main; $i++)
      {
        if ($main_result[$i]['accountingnumberid'] == 1)
        {
          # try to determine paymenttype
          /*
          Il faudrait créer des 411 différents, à savoir :
          C411AMEX
          C411ESP
          C411CHQ
          C411CB
          C411CREDIT
          C411VIRT
          */
          $stupidaccount = 'C411INCONNU';
          if ($main_result[$i]['matchingid'] > 0)
          {
            $agid_listA2 = array();
            $query = 'select adjustmentgroupid from adjustment where matchingid=? and adjustmentgroupid<>?';
            $query_prm = array($main_result[$i]['matchingid'], $agid);
            require('inc/doquery.php');
            for ($x=0;$x<=$num_results;$x++)
            {
              $agid_listA2[] = $query_result[$x]['adjustmentgroupid'];
            }
            $agid_listA2 = array_filter(array_unique($agid_listA2));
            sort($agid_listA2);
            $agid_list2 = '(';
            foreach ($agid_listA2 as $kladd)
            {
              $agid_list2 .= $kladd . ',';
            }
            $agid_list2 = rtrim($agid_list2,',') . ')';
            if ($agid_list2 == '()') { $agid_list2 = '(-1)'; }
          }
          $query = 'select distinct accountingnumberid from adjustment where adjustmentgroupid in '.$agid_list2.' and accountingnumberid<>1';
          $query_prm = array();
          require('inc/doquery.php');
          if ($num_results == 1)
          {
            # hardcode
            if ($query_result[$i]['accountingnumberid'] == 895) { $stupidaccount = 'C411AMEX'; }
            elseif ($query_result[$i]['accountingnumberid'] == 12) { $stupidaccount = 'C411ESP'; }
            elseif ($query_result[$i]['accountingnumberid'] == 22) { $stupidaccount = 'C411CHQ'; }
            elseif ($query_result[$i]['accountingnumberid'] == 24) { $stupidaccount = 'C411CB'; }
            elseif ($query_result[$i]['accountingnumberid'] == -987) { $stupidaccount = 'C411CREDIT'; } #???
            elseif ($query_result[$i]['accountingnumberid'] == 23) { $stupidaccount = 'C411VIRT'; }
          }
        }
        echo d_tr();
        echo d_td($main_result[$i]['adjustmentgroupid']);
        echo d_td($main_result[$i]['adjustmentdate']);
        if ($main_result[$i]['referenceid'] > 0)
        {
          echo d_td($main_result[$i]['referenceid']);
        }
        else
        {
          echo d_td();
        }
        if ($main_result[$i]['accountingnumberid'] > 1)
        {
          echo d_td($accountingnumberA[$main_result[$i]['accountingnumberid']]);
        }
        else
        {
          echo d_td($stupidaccount);
        }
        echo d_td($main_result[$i]['adjustmentcomment'].' '.$main_result[$i]['reference']);
        echo d_td($userA[$main_result[$i]['userid']]);
        if ($main_result[$i]['debit'] == 1)
        {
          echo d_td($main_result[$i]['value']+0);
          echo d_td();
        }
        else
        {
          echo d_td();
          echo d_td($main_result[$i]['value']+0);
        }
      }
    }
    echo '</table>';
  }
  else
  {
    ?><h2>Fichier pour compta:</h2>
    <form method="post" action="custom.php"><table>
    <tr><td>Début:</td><td colspan=4><?php
    $datename = 'start'; $selecteddate = $_SESSION['ds_curdate'];
    require('inc/datepicker.php');
    ?></td></tr>
    <tr><td>Fin:</td><td colspan=4><?php
    $datename = 'stop'; $selecteddate = $_SESSION['ds_curdate'];
    require('inc/datepicker.php');
    ?></td></tr><tr><td colspan="5" align="center">
    <input type=hidden name="go" value="1">
    <input type=hidden name="custommenu" value="import">
    <input type="submit" value="Valider"></td></tr></table></form><?php
  }
  break;

  default:

  break;
}
?>

<?php
require ('inc/bottom.php');
?>