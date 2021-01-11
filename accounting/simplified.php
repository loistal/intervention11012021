<?php

# need CAREFUL refactor

require('preload/accountingnumber.php');
require('preload/taxcode.php');

$PA['returntoasid'] = 'int';
$PA['searchme'] = 'int';
$PA['simplified_currentid'] = 'int';
$PA['do_testmatchid'] = 'int';
$PA['id'] = 'uint';
$PA['adjustmentgroup_tagid'] = 'uint';
require('inc/readpost.php');

if (isset($_POST['returntoasid'])) { $executereturntoasid = $returntoasid; }

$max_simplified_lines = 16;
$accounting_inputsize = 70; #accounting input size, TODO as parameter, simplified.php and entry.php
$jstva = -1;
$line_with_vat_button = 0;
$tva_line_exists = 0;
$ttc_line_exists = 0;
$horstaxe_line_exists = 0;
$testmatchid = 0;
$keepvalues = 0;
$asid = -1;

$query = 'select adjustmentdate from adjustmentgroup where closing=1 and deleted=0 order by adjustmentdate desc limit 1';
$query_prm = array();
require('inc/doquery.php');
if ($num_results) { $min_adjustmentdate = $query_result[0]['adjustmentdate']; }
if (!isset($min_adjustmentdate) || $min_adjustmentdate == '') { $min_adjustmentdate = '0000-00-00'; }

if ($id > 0)
{
  require('preload/accounting_simplifiedgroup.php');
  
  $query = 'select journalid,accounting_simplifiedname,accounting_simplifiedid,linkto_accounting_simplifiedid,linkto_name
  from accounting_simplified where accounting_simplifiedgroupid=? and deleted=0 order by accounting_simplifiedname';
  $query_prm = array($id);
  require('inc/doquery.php');
  $main_result = $query_result; $num_results_main = $num_results;
  echo '<h2>' . d_output($accounting_simplifiedgroupA[$id]) . '</h2><table class="report">';
  for ($i=0; $i < $num_results_main; $i++)
  {
    if ($main_result[$i]['linkto_name'] == '' && $main_result[$i]['linkto_accounting_simplifiedid'] > 0)
    { $main_result[$i]['linkto_name'] = 'Payer'; }
    $link = 'accounting.php?accountingmenu=simplified&accountingmenu_sa=simplified&simplified_currentid='.$simplified_currentid.'&asid='.$main_result[$i]['accounting_simplifiedid'];
    echo d_tr();
    echo d_td_unfiltered('<a href="'.$link.'">'.$main_result[$i]['accounting_simplifiedname'].'</a>');
    $kladd = ' ';
    if ($main_result[$i]['linkto_accounting_simplifiedid'] > 0)
    {
      $query = 'select adjustmentid
      from adjustmentgroup,adjustment
      where adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid
      and accounting_simplifiedid=? and deleted=0 and referenceid>0 and matchingid=0 limit 1';
      $query_prm = array($main_result[$i]['accounting_simplifiedid']);
      require('inc/doquery.php');
      if ($num_results) { $kladd = d_output($main_result[$i]['linkto_name']); }
    }
    echo d_td_unfiltered('<a href="'.$link.'">'.$kladd.'</a>');
  }
  echo '</table>';
}
elseif (isset($_POST['asid']) && $_POST['asid'] > 0 && $searchme == 0)
{
  $asid = $_POST['asid'];
  $query = 'select * from accounting_simplified where accounting_simplifiedid=?';
  $query_prm = array($asid);
  require('inc/doquery.php');
  $row = $query_result[0];
  if ($row['linkto_name'] == '' && $row['linkto_accounting_simplifiedid'] > 0)
  { $row['linkto_name'] = 'Payer'; }
  
  $error = 0; $keepvalues = 0;
  $datename = 'adjustmentdate'; require('inc/datepickerresult.php');
  if ($adjustmentdate < $min_adjustmentdate) { $adjustmentdate = $min_adjustmentdate; }
  $adjustmentcomment = $_POST['adjustmentcomment']; if ($adjustmentcomment == '') { $error = 5; }
  $reference = $_POST['reference'];
  $deleted = (int) $_POST['deleted'];
  $wasinversed = (int) $_POST['inversedebit'];
  $referenceid_diff = $_POST['clientdiff'];
  $totaldebit = 0;
  $totalcredit = 0;
  $value = array(); $referenceid = array();
  
  for ($i = 1; $i <= $max_simplified_lines; $i++)
  {
    $value[$i] = (int) $_POST['value' . $i];
    if ($value[$i] > 0)
    {
      if ($row['line'.$i.'_debit'] == 1) { $totaldebit += $value[$i]; }
      else { $totalcredit += $value[$i]; }
    }
    $referenceid[$i] = 0;
    if ($row['line'.$i.'_show'] == 1 && $accountingnumber_needreferenceA[$row['line'.$i.'_accountingnumberid']] == 1)
    {
      $client = $_POST['client' . $i]; require('inc/findclient.php'); $referenceid[$i] = $clientid;
      if ($referenceid[$i] < 1)
      {
        if ($row['line'.$i.'_choices'] != '' && $accountingnumber_needreferenceA[$_POST['accountingnumberid'.$i]] == 0)
        {
          # no error
        }
        else { $error = 3; }
      }
      # TODO check for existing tiers
    }
  }
  $difference = d_abs($totaldebit - $totalcredit);
  if ($row['usebalanceline'] == 0 && $difference != 0) { $error = 2; }
  else
  {
    if ($totaldebit > $totalcredit) { $difference_debit = 0; }
    else { $difference_debit = 1; }
    if ($totalcredit == 0 && $totaldebit == 0) { $error = 4; }
    if ($row['usebalanceline'] == 1 && $accountingnumber_needreferenceA[$row['balanceline_accountingnumberid']] == 1)
    {
      $client = $referenceid_diff; require('inc/findclient.php'); $referenceid_diff = $clientid;
      if ($referenceid_diff < 1) { $error = 3; }
      # TODO check for existing tiers
    }
    else { $referenceid_diff = 0; }
  }
 
  if ($error == 0)
  {
    if ($_POST['modifyid'] > 0)
    {
      $agid = (int) $_POST['modifyid'];
      # TODO verify that this id is the correct type of asid
      $query = 'update adjustmentgroup set journalid=?,userid=?,adjustmentdate=?,adjustmentcomment=?,deleted=?,wasinversed=?
      ,reference=?,accounting_simplifiedid=?,adjustmentgroup_tagid=? where adjustmentgroupid=?';
      $query_prm = array($row['journalid'],$_SESSION['ds_userid'], $adjustmentdate, $adjustmentcomment, $deleted, $wasinversed
      , $reference, $asid, $adjustmentgroup_tagid, $agid);
      require('inc/doquery.php');
      for ($i = 1; $i <= $max_simplified_lines; $i++)
      {
        if ($row['line'.$i.'_choices'] != '' && $_POST['accountingnumberid'.$i] > 0)
        {
          $row['line'.$i.'_accountingnumberid'] = $_POST['accountingnumberid'.$i];
        }
        $lineexists = 1;
        if ($referenceid[$i] > 0 && $accountingnumber_needreferenceA[$row['line'.$i.'_accountingnumberid']] == 0)
        {
          $referenceid[$i] = 0;
        }
        ### 2015 05 15 inversedebit (do NOT inverse the balance account)
        $debit = $row['line'.$i.'_debit'];
        if ($row['inversedebit'] == 1 && $_POST['inversedebit'] == 1)
        {
          if ($debit == 1) { $debit = 0; }
          else { $debit = 1; }
          #if ($difference_debit == 1) { $difference_debit = 0; }
          #else { $difference_debit = 1; }
        }
        ###
        if ($lineexists == 1)
        {
          $query = 'update adjustment set value=?,debit=?,referenceid=?,accountingnumberid=? where adjustmentgroupid=? and linenr=?';
          $query_prm = array($value[$i], $debit, $referenceid[$i], $row['line'.$i.'_accountingnumberid'],$agid, $i);
          require('inc/doquery.php');
        }
        elseif ($value[$i] > 0)
        {
          $query = 'insert into adjustment (value,debit,referenceid,accountingnumberid,adjustmentgroupid,linenr) values (?,?,?,?,?,?)';
          $query_prm = array($value[$i], $debit, $referenceid[$i], $row['line'.$i.'_accountingnumberid'],$agid, $i);
          require('inc/doquery.php');
        }
      }
      if ($difference > 0)
      {
        if ($row['inversedebit'] == 1 && $_POST['inversedebit'] == 1)
        {
          if ($difference_debit == 1) { $difference_debit = 0; }
          else { $difference_debit = 1; }
        }
        if ($lineexists == 1)
        {
          $query = 'update adjustment set value=?,debit=?,referenceid=?,accountingnumberid=? where adjustmentgroupid=? and linenr=?';
          $query_prm = array($difference, $difference_debit, $referenceid_diff, $row['balanceline_accountingnumberid'],$agid, 0);
          require('inc/doquery.php');
        }
        else
        {
          $query = 'insert into adjustment (value,debit,referenceid,accountingnumberid,adjustmentgroupid,linenr) values (?,?,?,?,?,?)';
          $query_prm = array($difference, $difference_debit, $referenceid_diff, $row['balanceline_accountingnumberid'],$agid, 0);
          require('inc/doquery.php');
        }
      }
      echo '<p>L\'écriture '.$agid.' a bien été modifiée.</p>';
      unset($adjustmentcomment, $reference, $deleted, $wasinversed, $valueA, $referenceidA);
    }
    else
    {
      ### 2016 03 10 try to prevent duplicates
      #$query = 'select adjustmentgroupid from adjustmentgroup where userid=? and adjustmentdate=? and adjustmentcomment=? and reference=? and accounting_simplifiedid=?';
      #$query_prm = array($_SESSION['ds_userid'], $adjustmentdate, $adjustmentcomment, $reference, $asid);
      #require('inc/doquery.php');
      #if ($num_results) { $error = 6; }
      if (1==0) {} # 2016 03 18 no more duplicate prevention
      else
      {
        ###
        $query = 'insert into adjustmentgroup (adjustmentgroup_tagid, journalid, userid,adjustmentdate,originaladjustmentdate,adjustmenttime,adjustmentcomment, reference, accounting_simplifiedid, wasinversed) values (?,?,?, ?, curdate(), curtime(), ?, ?, ?, ?)';
        $query_prm = array($adjustmentgroup_tagid, $row['journalid'], $_SESSION['ds_userid'], $adjustmentdate, $adjustmentcomment, $reference, $asid, $wasinversed);
        require('inc/doquery.php');
        $agid = $query_insert_id;
        for ($i = 1; $i <= $max_simplified_lines; $i++)
        {
          if ($row['line'.$i.'_show'] == 1)
          {
            if ($row['line'.$i.'_choices'] != '' && $_POST['accountingnumberid'.$i] > 0)
            {
              $row['line'.$i.'_accountingnumberid'] = $_POST['accountingnumberid'.$i];
            }
            if ($wasinversed) # 2016 05 31 fix
            {
              if ($row['line'.$i.'_debit'] == 1) { $row['line'.$i.'_debit'] = 0; }
              else { $row['line'.$i.'_debit'] = 1; }
            }
            $query = 'insert into adjustment (adjustmentgroupid,value,debit,referenceid,accountingnumberid,matchingid,linenr) values (?,?,?,?,?,0,?)';
            $query_prm = array($agid, $value[$i], $row['line'.$i.'_debit'], $referenceid[$i], $row['line'.$i.'_accountingnumberid'],$i);
            require('inc/doquery.php');
            $inserted_adjustmentid = $query_insert_id;#echo '<br>acnid=',$row['line'.$i.'_accountingnumberid'],' ',$accountingnumber_matchableA[$row['line'.$i.'_accountingnumberid']];
            ###
            #echo '<br>',$do_testmatchid,' matchable=',$accountingnumber_matchableA[$row['line'.$i.'_accountingnumberid']],' anid=',$row['line'.$i.'_accountingnumberid'];
            if ($num_results && $do_testmatchid > 0 && $accountingnumber_matchableA[$row['line'.$i.'_accountingnumberid']] == 1)
            {
              $query = 'select value,debit,referenceid
              from adjustment,adjustmentgroup
              where adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid
              and deleted=0 and matchingid=0 and adjustmentid=?';
              $query_prm = array($do_testmatchid);
              require('inc/doquery.php');
              if ($query_result[0]['value'] == $value[$i] && $query_result[0]['debit'] != $row['line'.$i.'_debit'] && $query_result[0]['referenceid'] == $referenceid[$i])
              {
                $query = 'insert into matching (userid,date,clientid, accountingnumberid) values (?,CURDATE(),?,?)';
                $query_prm = array($_SESSION['ds_userid'], $referenceid[$i], $row['line'.$i.'_accountingnumberid']);
                require ('inc/doquery.php');
                $inserted_matchingid = $query_insert_id;
                $query = 'update adjustment set matchingid=? where adjustmentid=? or adjustmentid=?';
                $query_prm = array($inserted_matchingid, $inserted_adjustmentid, $do_testmatchid);
                require ('inc/doquery.php');
              }
            }
            ###
          }
        }
        if ($difference > 0)
        {
          $query = 'insert into adjustment (adjustmentgroupid,value,debit,referenceid,accountingnumberid,matchingid) values (?,?,?,?,?,0)';
          $query_prm = array($agid, $difference, $difference_debit, $referenceid_diff, $row['balanceline_accountingnumberid']);
          require('inc/doquery.php');
          $inserted_adjustmentid = $query_insert_id;
          ###
          #echo '<br>',$do_testmatchid,' matchable=',$accountingnumber_matchableA[$row['balanceline_accountingnumberid']],' anid=',$row['balanceline_accountingnumberid'];
          if ($num_results && $do_testmatchid > 0 && $accountingnumber_matchableA[$row['balanceline_accountingnumberid']] == 1)
          {
            $query = 'select value,debit,referenceid
            from adjustment,adjustmentgroup
            where adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid
            and deleted=0 and matchingid=0 and adjustmentid=?';
            $query_prm = array($do_testmatchid);
            require('inc/doquery.php');
            if ($query_result[0]['value'] == $difference && $query_result[0]['debit'] != $difference_debit && $query_result[0]['referenceid'] == $referenceid_diff)
            {
              $query = 'insert into matching (userid,date,clientid, accountingnumberid) values (?,CURDATE(),?,?)';
              $query_prm = array($_SESSION['ds_userid'], $referenceid_diff, $row['balanceline_accountingnumberid']);
              require ('inc/doquery.php');
              $inserted_matchingid = $query_insert_id;
              $query = 'update adjustment set matchingid=? where adjustmentid=? or adjustmentid=?';
              $query_prm = array($inserted_matchingid, $inserted_adjustmentid, $do_testmatchid);
              require ('inc/doquery.php');
            }
          }
          ###
        }
      }
      if ($agid > 0) { echo '<p>L\'écriture '.$agid.' a bien été enregistrée.</p>'; }
    }
  }
  else
  {
    if ($_POST['modifyid'] > 0) { $keepvalues = 0; }
    else { $keepvalues = 1; }
    switch($error)
    {
      case '2':
      echo '<h2 class=alert>L\'écriture n\'a pas pu être enregistrée : Écriture non équilibrée</h2>';
      break;
      
      case '3':
      echo '<h2 class=alert>L\'écriture n\'a pas pu être enregistrée : Tiers manquant</h2>';
      break;
      
      case '4':
      echo '<h2 class=alert>L\'écriture n\'a pas pu être enregistrée : Écriture sans valeur</h2>';
      break;
      
      case '5':
      echo '<h2 class=alert>L\'écriture n\'a pas pu être enregistrée : Le ' . $_SESSION['ds_term_accounting_comment'] . ' est obligatoire</h2>';
      break;
      
      case '7':
      echo '<h2 class=alert>Votre saisie ne peut être validée, il faut obligatoirement saisir une date</h2>';
      break;
      
      default:
      echo '<h2 class=alert>L\'écriture n\'a pas pu être enregistrée : Erreur non spécifiée</h2>';
      break;
    }
  }
}

if (isset($_GET['asid']) && $_GET['asid'] > 0 && $asid <= 0)
{
  $asid = $_GET['asid'];
  ### for link to "pay", and bankstatement insert
  if (isset($_GET['com'])) { $adjustmentcomment = $_GET['com']; $set_default = 0; }
  else { $set_default = 1; }
  if (isset($_GET['ref'])) { $reference = $_GET['ref']; }
  if (isset($_GET['refid'])) { $link_refid = (int) $_GET['refid']; }
  if (isset($_GET['val'])) { $link_value = $_GET['val']; } else { $link_value = 0; }
  if ($link_value > 0) { $keepvalues = 1; }
  if (isset($_GET['testmatchid'])) { $testmatchid = (int) $_GET['testmatchid']; }
  if (isset($_GET['adjustmentdate'])) { $adjustmentdate = $_GET['adjustmentdate']; } # HERE TODO date
  ###
}
if ($asid > 0)
{
  $modifyid = 0;
  $query = 'select * from accounting_simplified where accounting_simplifiedid=?';
  $query_prm = array($asid);
  require('inc/doquery.php');
  $row = $query_result[0];
  if ($set_default)
  {
    $adjustmentcomment = $row['default_adjustmentcomment'];
    $reference = $row['default_reference'];
  }
  if ($row['linkto_name'] == '' && $row['linkto_accounting_simplifiedid'] > 0)
  { $row['linkto_name'] = 'Payer'; }
  if ($_SESSION['ds_autocomplete'] == 1)
  {
    $num_taxcodes = 0; $num_ttc = 0; $num_tva = 0; $num_other = 0; $ttc_field_is_readonly = 0; $sum_line = 0;
    for ($i = 1; $i <= $max_simplified_lines; $i++)
    {
      if ($row['line'.$i.'_show'] == 1)
      {
        #echo '<br>DEBUG $row[\'line'.$i.'_vatcalc\']='.$row['line'.$i.'_vatcalc'];
        if ($row['line'.$i.'_vatcalc'] > 0 && $row['line'.$i.'_vatcalc'] < 9000)
        {
          $jstva = $taxcodeA[($row['line'.$i.'_vatcalc']+0)]; $line_with_vat_button = $i; $num_taxcodes++;
        }
        if ($row['line'.$i.'_vatcalc'] == 9001)
        {
          $num_ttc++;
          if ($row['line'.$i.'_readonly'] == 1) { $ttc_field_is_readonly = 1; }
        }
        if ($row['line'.$i.'_vatcalc'] == 9002) { $num_tva++; }
        if ($row['line'.$i.'_vatcalc'] == 9003) { }
        if ($row['line'.$i.'_vatcalc'] == 0) { $num_other++; }
        if ($row['line'.$i.'_vatcalc'] == 9100) { $sum_line = $i; }
      }
    }
    if ($num_taxcodes == 1 && $num_ttc == 1 && $num_tva == 1 && $num_other == 0) { }
    else { $jstva = -1; }
    #echo '<br>DEBUG $jstva='.$jstva . ' $num_taxcodes='.$num_taxcodes. ' $num_ttc='.$num_taxcodes . ' $num_tva='.$num_tva;
    if ($jstva > -1)
    {
      ?><script type="text/javascript" src="jq/jquery.js"></script>
      <script>
      $(document).ready(function ()
      {
        function isempty(value) {
          if (!$.trim(value).length) {
            return true;
          } else {
            return false;
          }
        }
        
        $("#calculer").click(function ()
        {
          var tauxtva = parseInt(<?php echo $jstva; ?>);
          var horstaxe = $('#horstaxe').val();
          var sanstaxe = $('#sanstaxe').val() || 0;
          var tva = $('#tva').val();
          var ttc = $('#ttc').val();
          var temp = 0;
          // ttc field is readonly, set it to undefined
          <?php
          if ($ttc_field_is_readonly == 1) {
          ?>
          ttc = undefined;
          <?php
          }
          ?>
          
          // HT MAIN SCENARIO
          if (!isempty(horstaxe) && isempty(tva) && isempty(ttc))
          {
            tva = (parseInt(horstaxe) * tauxtva) / 100;
            tva = Math.round(tva);
            ttc = parseInt(horstaxe) + parseInt(tva) + parseInt(sanstaxe);
            $('#tva').val(tva);
            $('#ttc').val(ttc);
          }
          
          // TTC
          if (!isempty(ttc) && isempty(tva) && isempty(horstaxe))
          {
            horstaxe = 100 * ttc;
            temp = 100 + tauxtva;
            horstaxe = horstaxe / temp;
            horstaxe = Math.round(horstaxe);
            tva = ttc - horstaxe;
            $('#tva').val(tva);
            $('#horstaxe').val(horstaxe);
          }
          
          // HT TVA
          if (!isempty(horstaxe) && !isempty(tva) && isempty(ttc))
          {
            ttc = Math.abs(parseInt(horstaxe) + parseInt(tva)) + parseInt(sanstaxe);
            $('#ttc').val(ttc);
          }
          
          // TVA TTC
          if (isempty(horstaxe) && !isempty(tva) && !isempty(ttc))
          {
            horstaxe = Math.abs(parseInt(ttc) - parseInt(tva));
            $('#horstaxe').val(horstaxe);
          }
          
          // HT TCC
          if (!isempty(horstaxe) && isempty(tva) && !isempty(horstaxe))
          {
            tva = Math.abs(parseInt(ttc) - parseInt(horstaxe));
            $('#tva').val(tva);
          }
          
        });
      });
      </script><?php
    }
    elseif ($sum_line > 0)
    {
      ?><script type="text/javascript" src="jq/jquery.js"></script>
      <script>
      $(document).ready(function ()
      {
        $("#part1,#part2,#part3,#part4,#part5,#part6,#part7,#part8,#part9,#part10,#part11,#part12,#part13,#part14,#part15,#part16,#part17,#part18,#part19,#part20").on("keydown keyup", function() {
          var part1 = $('#part1').val() || 0;
          var part2 = $('#part2').val() || 0;
          var part3 = $('#part3').val() || 0;
          var part4 = $('#part4').val() || 0;
          var part5 = $('#part5').val() || 0;
          var part6 = $('#part6').val() || 0;
          var part7 = $('#part7').val() || 0;
          var part8 = $('#part8').val() || 0;
          var part9 = $('#part9').val() || 0;
          var part10 = $('#part10').val() || 0;
          var part11 = $('#part11').val() || 0;
          var part12 = $('#part12').val() || 0;
          var part13 = $('#part13').val() || 0;
          var part14 = $('#part14').val() || 0;
          var part15 = $('#part15').val() || 0;
          var part16 = $('#part16').val() || 0;
          var part17 = $('#part17').val() || 0;
          var part18 = $('#part18').val() || 0;
          var part19 = $('#part19').val() || 0;
          var part20 = $('#part20').val() || 0;
          var totalsum = parseInt(part1) + parseInt(part2) + parseInt(part3) + parseInt(part4) + parseInt(part5) + parseInt(part6) + parseInt(part7) + parseInt(part8) + parseInt(part9)
           + parseInt(part10) + parseInt(part11) + parseInt(part12) + parseInt(part13) + parseInt(part14) + parseInt(part15) + parseInt(part16) + parseInt(part17) + parseInt(part18)
           + parseInt(part19) + parseInt(part20);
          $('#totalsum').val(totalsum);
        });
      });
      </script><?php
    }
  }
  
  if ($keepvalues == 0)
  {
    if ($set_default == 0) { $adjustmentcomment = ''; $reference = ''; }
    $deleted = 0; unset($referenceid,$referenceid_diff);
    $adjustmentgroup_tagid = 0;
  }
  
  echo '<h2>';
  if(isset($_GET['modify']) && $_GET['modify'] == 1 && $_GET['agid'] > 0)
  {
    $keepvalues = 0;
    $agid = (int) $_GET['agid'];
    
    $matchingerror = 0; $matchingerrorlist = array(); $reconcileerrorlist = array();
    $query = 'select matchingid,value,referenceid,debit,accountingnumberid,reconciliationid from adjustment where adjustmentgroupid=?';
    $query_prm = array($agid);
    require('inc/doquery.php');
    for ($i=0; $i < $num_results; $i++)
    {
      if ($query_result[$i]['matchingid'] > 0)
      {
        $matchingerror = 1;
        array_push($matchingerrorlist,$query_result[$i]['matchingid']);
      }
      if ($query_result[$i]['reconciliationid'] > 0)
      {
        $matchingerror = 2;
        array_push($reconcileerrorlist,$query_result[$i]['reconciliationid']);
      }
    }
    if ($matchingerror == 0)
    {
      $query = 'select bankstatementid from bankstatement where adjustmentgroupid=? limit 1';
      $query_prm = array($agid);
      require('inc/doquery.php');
      if ($num_results)
      {
        echo '<p class=alert>Modification impossible car l\'écriture '.$agid.' figure sur un relevé bancaire.</p>';
        $agid = -1;
        $matchingerror = 3;
      }
    }
    if ($matchingerror == 1)
    {
      $adjustmentcomment = '';
      $reference = '';
      $selecteddate = '';
      echo '<p class=alert>L\'écriture '.$agid.' ne peut être modifiée, car elle est lettrée.<br>Numéros lettrage :';
      $matchingerrorlist = array_unique($matchingerrorlist);
      foreach ($matchingerrorlist as $kladd) { echo ' <a href="accounting.php?accountingmenu=undo&accountingmenu_sa=control&matchingid='.$kladd.'">' . $kladd . '</a>'; }
      echo '</p>';
      $agid = -1;
    }
    elseif ($matchingerror == 2)
    {
      $adjustmentcomment = '';
      $reference = '';
      $selecteddate = '';
      echo '<p class=alert>L\'écriture '.$agid.' ne peut être modifiée, car elle est rapprochée.<br>Numéros rapprochement :';
      $reconcileerrorlist = array_unique($reconcileerrorlist);
      foreach ($reconcileerrorlist as $kladd) { echo ' ' . $kladd; }
      echo '</p>';
      $agid = -1;
    }
    else
    {
      $query = 'select adjustmentgroup.adjustmentgroupid,adjustmentdate,adjustmentcomment,reference,deleted,wasinversed
      ,adjustmentgroup_tagid
      from adjustmentgroup
      where adjustmentgroup.adjustmentgroupid=? and adjustmentdate>=?
      order by adjustmentdate,adjustmentgroupid desc';
      $query_prm = array($agid,$min_adjustmentdate);
      require('inc/doquery.php');
      if ($num_results)
      {
        $modifyid = $agid;
        $adjustmentgroup_tagid = $query_result[0]['adjustmentgroup_tagid'];
        $selecteddate = $query_result[0]['adjustmentdate'];
        $adjustmentcomment = $query_result[0]['adjustmentcomment'];
        $reference = $query_result[0]['reference'];
        $deleted = $query_result[0]['deleted'];
        $wasinversed = $query_result[0]['wasinversed'];
        $query = 'select value,referenceid,linenr,accountingnumberid from adjustment where adjustmentgroupid=? order by linenr,adjustmentid';
        $query_prm = array($agid);
        require('inc/doquery.php');
        for ($i = 0; $i < $num_results; $i++)
        {
          $linenr = $query_result[$i]['linenr'];
          $valueA[$linenr] = $query_result[$i]['value']+0;
          $referenceidA[$linenr] = $query_result[$i]['referenceid'];
          $accountingnumberidA[$linenr] = $query_result[$i]['accountingnumberid']; # for _choices
        }
        echo 'Modifier l\'écriture ' . $agid .': ';
      }
    }
  }
  
  echo d_output($row['accounting_simplifiedname']) . '</h2>';
  if ($row['explanation'] != '') { echo '<p>',d_output($row['explanation']),'</p>'; }
  echo '<form method="post" action="accounting.php"><table>';
  echo '<tr><td>'.$_SESSION['ds_term_accounting_comment'].' :</td><td><input autofocus type="text" name="adjustmentcomment" value="' . d_input($adjustmentcomment) . '" size='.$accounting_inputsize.'></td></tr>
  <tr><td>'.$_SESSION['ds_term_accounting_reference'].' :
  <td colspan=7><input type="text" name="reference"';
  if (isset($reference)) { echo ' value="' . d_input($reference) . '"'; }
  echo ' size='.$accounting_inputsize.'>';
  echo '<tr><td>Date:</td><td colspan=7>';
  $datename = 'adjustmentdate'; $dp_datepicker_min = $min_adjustmentdate;
  require('inc/datepicker.php');
  if ($row['use_adjustmentgroup_tag'] == 1)
  {
    echo ' &nbsp; ';
    $dp_itemname = 'adjustmentgroup_tag'; $dp_description = $_SESSION['ds_term_accounting_tag'];
    $dp_notable = 1; $dp_allowall = 0; $dp_blank = 1; $dp_selectedid = $adjustmentgroup_tagid;
    require('inc/selectitem.php');
  }
  if ($row['inversedebit'] == 1)
  {
    echo '<tr><td>' . d_output($row['inversedebit_title']) . ':</td><td colspan=7><input type="checkbox" name="inversedebit"';
    if ($wasinversed == 1) { echo ' checked'; }
    echo ' value=1></td></tr>';
  }
  if ($modifyid > 0)
  {
    echo '<tr><td>Supprimer:</td><td colspan=7><input type="checkbox" name="deleted"';
    if ($deleted == 1)
    { 
      echo ' checked';
    }
    echo ' value=1></td></tr>';
  }
  if ($row['usebalanceline'] == 1 && $_SESSION['ds_accounting_simplified_showac'])
  {
    echo '<tr><td>[',$accountingnumberA[$row['balanceline_accountingnumberid']],']:<td>Compte Balance';
  }
  if ($row['usebalanceline'] == 1 && $accountingnumber_needreferenceA[$row['balanceline_accountingnumberid']] == 1)
  {
    echo '<tr><td>';
    if ($keepvalues == 0) { unset($_POST['clientdiff']); }
    if (isset($referenceid_diff)) { $client = $referenceid_diff; } else { $client = 0; }
    if (isset($referenceidA[0]) && $referenceidA[0] > 0) { $client = $referenceidA[0]; }
    if ($client == 0) { $client = $row['balance_partyfill']; }
    if ($client == 0) { $client = ''; }
    ###
    if ($link_refid > 0) { $client = $link_refid; }
    ###
    $dp_description = 'Tiers'; $dp_addtoid = 'diff'; require('inc/selectclient.php');
    echo ' &nbsp; <a href="accounting.php?accountingmenu=party" target=_blank>Ajouter</a>';
  }
  for ($i = 1; $i <= $max_simplified_lines; $i++)
  {
    if ($row['line'.$i.'_show'] == 1)
    {
      if ($keepvalues == 1)
      {
        if (isset($_POST['value'.$i])) { $valueA[$i] = $_POST['value'.$i]; }
        if (isset($_POST['accountingnumberid'.$i])) { $accountingnumberidA[$i] = $_POST['accountingnumberid'.$i]; }
      }
      ###
      if ($link_value > 0) { $valueA[$i] = $link_value+0; }
        #  && $accountingnumber_needreferenceA[$row['line'.$i.'_accountingnumberid']] == 1
        # 2015 06 24 took off the above requirement, in effect we can only have ONE field on the target menu
      ###
      echo '<tr><td>';
      if ($_SESSION['ds_accounting_simplified_showac'])
      {
        echo '[',$accountingnumberA[$row['line'.$i.'_accountingnumberid']],'] ';
      }
      echo d_output($row['line'.$i.'_title']);
      echo '<td>';
      if ($row['line'.$i.'_choices'] != '')
      {
        if ($row['line'.$i.'_choices'] == '*')
        {
          # choice of all accounts
          echo ' <select name="accountingnumberid'.$i.'">';
          foreach ($accountingnumberA as $xid => $part)
          {
            if ($accountingnumber_deletedA[$xid] == 0)
            {
              echo '<option value="' . $xid . '"';
              if ($xid == $accountingnumberidA[$i]) { echo ' selected'; }
              echo '>' . $accountingnumber_longA[$xid] . '</option>';
            }
          }
          echo '</select>';
        }
        else
        {
          # assuming this field is already verified, see add_sa.php
          echo ' <select name="accountingnumberid'.$i.'">';
          $choice_partA = explode(" ", $row['line'.$i.'_choices']);
          foreach ($choice_partA as $xid => $part)
          {
            if ($xid%2 == 0)
            {
              $part = array_search($part, $accountingnumberA);
              echo '<option value="' . $part . '"';
              if ($part == $accountingnumberidA[$i]) { echo ' selected'; }
              echo '>';
            }
            else
            {
              echo d_input($part) . '</option>';
            }
          }
          echo '</select>';
        }
      }
      echo '<input type="text"';
      $jsid = 'part'.$i;
      if ($row['line'.$i.'_vatcalc'] > 0 && $row['line'.$i.'_vatcalc'] < 9000) { $jsid = 'tva'; $tva_line_exists++; }
      elseif ($row['line'.$i.'_vatcalc'] == 9001) { $jsid = 'ttc'; $ttc_line_exists++; }
      elseif ($row['line'.$i.'_vatcalc'] == 9002) { $jsid = 'horstaxe'; $horstaxe_line_exists++; }
      elseif ($row['line'.$i.'_vatcalc'] == 9003) { $jsid = 'sanstaxe'; }
      elseif ($row['line'.$i.'_vatcalc'] == 9100) { $jsid = 'totalsum'; }
      if ($jsid != '') { echo ' id="' . $jsid . '"'; }
      echo ' STYLE="text-align:right" name="value' . $i . '"';
      if (isset($valueA[$i])) { echo ' value="' . $valueA[$i] . '"'; }
      echo ' size=15';
      if ($row['line'.$i.'_readonly'] == 1) { echo ' readonly'; } # this is a BAD idea but CAGEST wants it
      echo '>';
      if ($accountingnumber_needreferenceA[$row['line'.$i.'_accountingnumberid']] == 1)
      {
        echo '<tr><td>';
        if ($keepvalues == 0) { unset($_POST['client'.$i]); }
        if (isset($referenceid[$i])) { $client = $referenceid[$i]; }
        if (isset($referenceidA[$i]) && $referenceidA[$i] > 0) { $client = $referenceidA[$i]; }
        if (isset($row['line'.$i.'_partyfill']) && (!isset($client) || $client == 0)) { $client = $row['line'.$i.'_partyfill']; }
        if ($client == 0) { $client = ''; }
        ###
        if (isset($link_refid) && $link_refid > 0) { $client = $link_refid; }
        ###
        $dp_description = 'Tiers'; $dp_addtoid = $i; require('inc/selectclient.php');
        echo ' &nbsp; <a href="accounting.php?accountingmenu=party" target=_blank>Ajouter</a>';
      }
      if ($line_with_vat_button == $i && $jstva > 0)
      {
        echo '<style type="text/css">
        .btn-calculate {
          background: '.$_SESSION['ds_linkcolor'].';
          background-image: -webkit-linear-gradient(top, '.$_SESSION['ds_linkcolor'].', '.$_SESSION['ds_linkcolor'].');
          background-image: -moz-linear-gradient(top, '.$_SESSION['ds_linkcolor'].', '.$_SESSION['ds_linkcolor'].');
          background-image: -ms-linear-gradient(top, '.$_SESSION['ds_linkcolor'].', '.$_SESSION['ds_linkcolor'].');
          background-image: -o-linear-gradient(top, '.$_SESSION['ds_linkcolor'].', '.$_SESSION['ds_linkcolor'].');
          background-image: linear-gradient(to bottom, '.$_SESSION['ds_linkcolor'].', '.$_SESSION['ds_linkcolor'].');
          -webkit-border-radius: 6;
          -moz-border-radius: 6;
          border-radius: 6px;
          font-family: Arial;
          color: #ffffff;
          font-size: 14px;
          padding: 5px 10px 5px 10px;
          text-decoration: none;
          cursor: pointer;
        }

        .btn-calculate:hover {
          background: '.$_SESSION['ds_linkcolor'].';
          background-image: -webkit-linear-gradient(top, '.$_SESSION['ds_linkcolor'].', '.$_SESSION['ds_linkcolor'].');
          background-image: -moz-linear-gradient(top, '.$_SESSION['ds_linkcolor'].', '.$_SESSION['ds_linkcolor'].');
          background-image: -ms-linear-gradient(top, '.$_SESSION['ds_linkcolor'].', '.$_SESSION['ds_linkcolor'].');
          background-image: -o-linear-gradient(top, '.$_SESSION['ds_linkcolor'].', '.$_SESSION['ds_linkcolor'].');
          background-image: linear-gradient(to bottom, '.$_SESSION['ds_linkcolor'].', '.$_SESSION['ds_linkcolor'].');
          text-decoration: none;
        }
        </style>
        <span class="btn-calculate" id="calculer">Calculer</span>';
      }
    }
  }
  echo '<tr><td colspan="2" align="center"><input type=hidden name="accountingmenu" value="' . $accountingmenu . '"><input type="submit" value="Valider"></td></tr>';
  if ($modifyid > 0) { echo '<input type=hidden name="modifyid" value="' . $modifyid . '">'; }
  if ($testmatchid > 0) { echo '<input type=hidden name="do_testmatchid" value="' . $testmatchid . '">'; }
  if ($returntoasid > 0) { echo '<input type=hidden name="returntoasid" value="' . $returntoasid . '">'; }
  echo '<input type=hidden name="asid" value="' . $asid . '"><input type=hidden name="accountingmenu_sa" value="simplified">
  <input type=hidden name="simplified_currentid" value="' . $simplified_currentid . '"></table></form>';
  
}

if ($asid > 0 || $searchme == 1)
{
  $querymain = 'select adjustmentgroup.adjustmentgroupid,adjustmentdate,adjustmentcomment,reference,deleted from adjustmentgroup
  where adjustmentgroup.accounting_simplifiedid=? and deleted=0';
  if ($searchme == 1)
  {
    $asid = $_POST['asid'];
    $query = 'select * from accounting_simplified where accounting_simplifiedid=?';
    $query_prm = array($asid);
    require('inc/doquery.php');
    $row = $query_result[0];
    $datename = 'startdate'; require('inc/datepickerresult.php');
    $datename = 'stopdate'; require('inc/datepickerresult.php');
    $query = $querymain . ' and adjustmentdate>=? and adjustmentdate<=?';
    $query_prm = array($asid, $startdate, $stopdate);
    if ($_POST['adjustmentcomment'] != '') { $query .= ' and adjustmentcomment like ?'; array_push($query_prm, '%'.$_POST['adjustmentcomment'].'%'); }
    if ($_POST['reference'] != '') { $query .= ' and reference like ?'; array_push($query_prm, '%'.$_POST['reference'].'%'); }
    $query .= ' order by adjustmentdate desc,adjustmentgroupid';
    
  }
  else
  {
    $query = $querymain . ' order by adjustmentdate desc,adjustmentgroupid desc limit 50'; # hardcode last 50
    $query_prm = array($asid);
  }
  require('inc/doquery.php');
  $main_result = $query_result; $num_results_main = $num_results;
  if($num_results_main)
  {
    $found_needreference = 0; $found_line = -1; ## NEW find fields with needreference=1
    echo '<br><table class=report STYLE="min-width: 800px"><thead><th>Écriture<th>Date<th>'.$_SESSION['ds_term_accounting_comment'].'<th>'.$_SESSION['ds_term_accounting_reference'];
    if ($row['usebalanceline'] == 1 && $accountingnumber_needreferenceA[$row['balanceline_accountingnumberid']] == 1)
    {
      echo '<th>Tiers';
      $found_needreference++; $found_line = 0;
    }
    for ($i = 1; $i <= $max_simplified_lines; $i++)
    {
      if ($row['line'.$i.'_show'] == 1)
      {
        echo '<th>' . d_output($row['line'.$i.'_title']);
        if ($accountingnumber_needreferenceA[$row['line'.$i.'_accountingnumberid']] == 1)
        {
          echo '<th>Tiers';
          $found_needreference++; $found_line = $i;
        }
      }
    }
    if ($row['linkto_accounting_simplifiedid'] > 0 && $row['linkto_name'] != '') { echo '<th>'; }
    echo '</thead>';
    if ($found_needreference > 1) { $found_needreference = 0; $found_line = -1; }

    $linkto_groupid = 0;
    for ($i = 0; $i < $num_results_main; $i++)
    {
      $matchingid = 0;
      echo d_tr();
      #echo d_td_old($main_result[$i]['adjustmentgroupid'],0,0,0,'##accounting.php?accountingmenu=simplified&modify=1&accountingmenu_sa=simplified&simplified_currentid='.$simplified_currentid.'&asid='.$asid.'&agid='.$main_result[$i]['adjustmentgroupid']);
      $link_modify = '<a href="accounting.php?accountingmenu=simplified&modify=1&accountingmenu_sa=simplified&simplified_currentid='.$simplified_currentid.'&asid='.$asid.'&agid='.$main_result[$i]['adjustmentgroupid'].'">'.$main_result[$i]['adjustmentgroupid'].'</a>';
      $link_show = '<a href="reportwindow.php?report=entryreport&id='.$main_result[$i]['adjustmentgroupid'].'&accounting_simplifiedid='.$asid.'&alldates=1&journalid=-1&adjustmentgroup_tagid=-1" target=_blank>Afficher</a>';
      echo d_td_unfiltered($link_modify.' '.$link_show);
      echo d_td_old(datefix2($main_result[$i]['adjustmentdate']));
      echo d_td_old($main_result[$i]['adjustmentcomment']);
      echo d_td_old($main_result[$i]['reference']);
      if ($row['usebalanceline'] == 1)
      {
        $query = 'select value,referenceid,matchingid,adjustmentid from adjustment where adjustmentgroupid=? and linenr=0';
        $query_prm = array($main_result[$i]['adjustmentgroupid']);
        require('inc/doquery.php');
        $matchingid = $query_result[0]['matchingid']; $link_refid = $query_result[0]['referenceid']; $link_val = $query_result[0]['value']; $link_adjustmentid = $query_result[0]['adjustmentid']; # if ($found_line == 0)
        if ($accountingnumber_needreferenceA[$row['balanceline_accountingnumberid']] == 1)
        {
          $query = 'select clientname from client where clientid=? limit 1';
          $query_prm = array($link_refid);
          require('inc/doquery.php');
          echo d_td_old($query_result[0]['clientname'],1);
        }
      }
      $query = 'select value,referenceid,linenr,clientname,matchingid,adjustmentid from adjustment left outer join client on adjustment.referenceid=client.clientid where adjustmentgroupid=? and linenr>0 order by linenr,adjustmentid';
      $query_prm = array($main_result[$i]['adjustmentgroupid']);
      require('inc/doquery.php');
      $linenrA = array();
      for ($y = 0; $y < $num_results; $y++)
      {
        $linenr = (int) $query_result[$y]['linenr'];
        if ($found_line == $linenr) { $matchingid = $query_result[$y]['matchingid']; $link_refid = $query_result[$y]['referenceid']; $link_val = $query_result[$y]['value']; $link_adjustmentid = $query_result[$y]['adjustmentid']; }
        $linenrA[$linenr] = d_td_old(myfix($query_result[$y]['value']),1);
        if ($accountingnumber_needreferenceA[$row['line'.$linenr.'_accountingnumberid']] == 1)
        {
          $linenrA[$linenr] .= d_td_old($query_result[$y]['clientname'],1);
        }
      }
      for ($y = 1; $y <= $max_simplified_lines; $y++)
      {
        if ($row['line'.$y.'_show'] == 1)
        {
          if ($linenrA[$y] != '') { echo $linenrA[$y]; }
          else { echo d_td_old(''); }
        }
      }
      if ($row['linkto_accounting_simplifiedid'] > 0 && $row['linkto_name'] != '')
      {
        if ($matchingid == 0)
        {
          if ($linkto_groupid == 0)
          {
            $query = 'select accounting_simplifiedgroupid from accounting_simplified where accounting_simplifiedid=?';
            $query_prm = array($row['linkto_accounting_simplifiedid']);
            require('inc/doquery.php');
            $linkto_groupid = $query_result[0]['accounting_simplifiedgroupid'];
          }
          $link = '##accounting.php?accountingmenu=simplified&accountingmenu_sa=simplified&simplified_currentid='.$linkto_groupid.'&asid='.$row['linkto_accounting_simplifiedid'];
          $link .= '&com='.$main_result[$i]['adjustmentcomment'].'&ref='.$main_result[$i]['reference'].'&refid='.$link_refid.'&val='.$link_val;
          $link .= '&testmatchid='.$link_adjustmentid;
          $link .= '&returntoasid='.$asid;
          if ($_SESSION['ds_accounting_simplified_keepdate'])
          {
            $link .= '&adjustmentdate='.$main_result[$i]['adjustmentdate'];
          }
          echo d_td_old(d_output($row['linkto_name']),0,0,0,$link);
        }
        else { echo d_td(); }
      }
    }
    
    echo '</table>';
  }

  echo '<br><form method="post" action="accounting.php"><table>';
  echo '<tr><td>Début:</td><td>';
  $datename = 'startdate'; if (isset($startdate)) { $selecteddate = $startdate; }
  require('inc/datepicker.php');
  echo '</td></tr><tr><td>Fin:</td><td>';
  $datename = 'stopdate'; if (isset($stopdate)) { $selecteddate = $stopdate; }
  require('inc/datepicker.php');
  echo '</td></tr>
  <tr><td>'.$_SESSION['ds_term_accounting_comment'].' :<td><input autofocus type="text" name="adjustmentcomment" value="' . d_input($_POST['adjustmentcomment']) . '" size=20></td></tr>
  <tr><td>'.$_SESSION['ds_term_accounting_reference'].' :<td colspan=7><input type="text" name="reference" value="' . d_input($_POST['reference']) . '" size=20></td></tr>
  <tr><td colspan="2" align="center"><input type=hidden name="accountingmenu" value="' . $accountingmenu . '">
  <input type=hidden name="accountingmenu_sa" value="simplified"><input type=hidden name="asid" value="' . $asid . '">
  <input type=hidden name="simplified_currentid" value="' . $simplified_currentid . '"><input type=hidden name="searchme" value=1>
  <input type="submit" value="Afficher"></td></tr></table></form>';
}

if (isset($executereturntoasid) && $executereturntoasid > 0)
{
  echo '<meta http-equiv="refresh" content="1; url=accounting.php?accountingmenu=simplified&accountingmenu_sa=simplified&asid='.$executereturntoasid.'">';
}

?>