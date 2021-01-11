<?php

if ($_SESSION['ds_clientaccess_clientid'] == 4126) # Nestle
{
  switch($clientaccessmenu)
  {
    case 'sohreport':
    ?><h2>SOH Report (txt):</h2>
    <form method="post" action="customreportwindow.php" target=_blank><table>
    <tr><td colspan="2" align="center">
    <input type=hidden name="report" value="sohreport">
    <input type="submit" value="Valider"></td></tr>
    </table></form><?php
    break;
    
    case 'nestledaily':
      ?><h2>Nestlé vente/jour (txt):</h2>
      <form method="post" action="customreportwindow.php" target=_blank><table>
      <tr><td>Date <select name="mychoice2"><option value="1">livraison</option></select> de </td><td><?php #<option value="3">comptable</option><option value="2">saisie</option>
      $datename = 'fromdate'; $dp_datepicker_min = mb_substr($_SESSION['ds_curdate'],0,4) . '-01-01';
      require('inc/datepicker.php');
      echo '</td></tr><tr><td> à </td><td>';
      $datename = 'todate';
      require('inc/datepicker.php');
      ?></td></tr>
      <?php
      #<tr><td>Factures:</td><td><select name="mychoice"><option value="1">Confirmées</option><option value="2">Non-confirmées</option></select></td></tr>
      ?>
      <tr><td colspan="2" align="center">
      <input type=hidden name="report" value="nestledaily">
      <input type="submit" value="Valider"></td></tr>
      </table></form><?php
      break;
      
      case 'prodcat':
      echo '<form method="post" action="customreportwindow.php" target="_blank">';
      echo '<input type="radio" name="mycat" value="6" CHECKED> Nestlé<br>
      <input type="radio" name="mycat" value="7"> Nestlé avec codes fournisseurs<br>
      <br>
      Champs: Code Barre<input type="checkbox" name="showean" value="1"><br>
      Commercial: <input type="checkbox" name="salesrep" value="1"><br>
      <input type=hidden name="report" value="prodcat"><input type="submit" value="Catalogue de produits"></form>';
    break;
    
    case 'nestlebdl':
      require('preload/clientcategory.php');
      require('preload/clientcategory2.php');

      echo '<h2>Rapport de BdL Nestlé:</h2><form method="post" action="reportwindow.php" target="_blank"><table>';
      echo '<tr><td>Date:</td><td><select name="datefield"><option value=0>' . $_SESSION['ds_term_accountingdate'] . '</option>';
      if ($_SESSION['ds_hidedeliverydate'] == 0) { echo '<option value=1>' . $_SESSION['ds_term_deliverydate'] . '</option>'; }
      echo '<option value=2>Saisie</option><option value=3>Payable</option></select></td></tr>';
      echo '<tr><td>De:</td><td>';
      $datename = 'startdate';
      require('inc/datepicker.php');
      echo '</td></tr>';
      echo '<tr><td>A:</td><td>';
      $datename = 'stopdate';
      require('inc/datepicker.php');
      echo '</td></tr>';
      echo '<tr><td align=right><input type=checkbox name="bynumber" value=1></td><td>Par numéro: <input type="text" STYLE="text-align:right" name="startid" size=10> à <input type="text" STYLE="text-align:right" name="stopid" size=10> (et ne pas par date)</td></tr>';

      ?><tr><td>Client:</td><td><input autofocus type="text" STYLE="text-align:right" name="client" size=20></td></tr>

      <?php
      $dp_itemname = 'employee'; $dp_addtoid = 'f'; $dp_issales = 1; $dp_description = 'Employé (facture)'; $dp_allowall = 1; $dp_selectedid = -1;
      require ('inc/selectitem.php');
      ?>

      <input type=hidden name="mychoice" value=2>
      
      <tr><td>Type:</td><?php
      echo '<td><select name="mychoice2"><option value=7>' . $_SESSION['ds_term_invoicenotice'] . '</option><option value=8>Avoir ' . $_SESSION['ds_term_invoicenotice'] . '</option></select></td></tr>';

      ?><tr><td>Ranger par:</td><td><select name="mychoice3"><option value=1>Numéro facture</option><option value=2>Numéro client</option>
      <option value=3><?php echo $_SESSION['ds_term_reference']; ?></option>
      <?php
      if ($_SESSION['ds_term_field1'] != "")
      {
        echo '<option value=4>' . $_SESSION['ds_term_field1'] . '</option>';
      }
      if ($_SESSION['ds_term_field2'] != "")
      {
        echo '<option value=5>' . $_SESSION['ds_term_field2'] . '</option>';
      }
      ?>
      </select></td></tr>
      <tr><td colspan="2" align="center"><input type="submit" value="Valider"></td></tr>

      </table><input type=hidden name="report" value="invoicereport"></form>
      <?php
    break;
    
    
    case 'confirmbdl':
  
    ?><script type='text/javascript' src='jq/jquery.js'></script>

    <script type='text/javascript'>
    $(document).ready(function(){

    // source http://www.formget.com/checkuncheck-all-checkboxes-using-jquery/

    $("#confirmall").attr("data-type","check");
    $("#confirmall").click(function(){
    if($("#confirmall").attr("data-type")==="check")
    {
    $(".confirm").prop("checked",true);
    $("#confirmall").attr("data-type","uncheck");
    }
    else
    {
    $(".confirm").prop("checked",false);
    $("#confirmall").attr("data-type","check");
    }
    })
    });
    </script>

    <script type='text/javascript'>
    $(document).ready(function(){
    $("#cancelall").attr("data-type","check");
    $("#cancelall").click(function(){
    if($("#cancelall").attr("data-type")==="check")
    {
    $(".cancel").prop("checked",true);
    $("#cancelall").attr("data-type","uncheck");
    }
    else
    {
    $(".cancel").prop("checked",false);
    $("#cancelall").attr("data-type","check");
    }
    })
    });
    </script>
    <?php

    require('preload/localvessel.php');

    $myuserid = $_POST['myuserid']+0;
    $limitdates = $_POST['limitdates']+0;
    if ($_SESSION['ds_confirmonlyown']) { $myuserid = $_SESSION['ds_userid']; }

    if ($myuserid == 0)
    {
      ?><h2>Confirmer / annuler BdL:</h2>
      <form method="post" action="clientaccess.php"><table>
      <input type=hidden name="myuserid" value="-1">
      <tr><td colspan="2" align="center"><input type=hidden name="step" value="1"><input type=hidden name="clientaccessmenu" value="confirmbdl">
      <input type="submit" value="Valider"></td></tr>
      <tr><td>&nbsp;
      <tr><td><input type=checkbox name="limitdates" value=1>Limiter les dates:<br>
      De: <?php $datename = 'startdate'; require('inc/datepicker.php');
      echo '<br>A: '; $datename = 'stopdate'; require('inc/datepicker.php');
      ?></table></form><?php
    }
    else
    {
      if ($_POST['confirm'])
      {
        $listconfirmed = ''; $listcancelled = ''; $in_confirmed = '(';
        $all_results = $_POST['results']+0;
        for ($i=0; $i < $all_results; $i++)
        {
          if ($_POST['confirmed' . $i] && !$_POST['cancelled' . $i])
          {
            ### check if sum of lines equals invoiceprice
            $ok = 0;
            $query = 'select invoiceprice from invoice where invoiceid=?';
            $query_prm = array($_POST['confirmed' . $i]);
            require('inc/doquery.php');
            $invoicetotal = $query_result[0]['invoiceprice']+0;
            $query = 'select sum(lineprice+linevat) as linetotals from invoiceitem where invoiceid=?';
            $query_prm = array($_POST['confirmed' . $i]);
            require('inc/doquery.php');
            $linetotals = $query_result[0]['linetotals']+0;
            if ($_SESSION['ds_invoicedeductions'] == 1)
            {
              $query = 'select sum(deduction) as linetotals from invoicededuction where invoiceid=?';
              $query_prm = array($_POST['confirmed' . $i]);
              require('inc/doquery.php');
              $linetotals -= $query_result[0]['linetotals']+0;
            }
            #if (myround($linetotals) == myround($invoicetotal) || $_SESSION['ds_invoicedeductions'] == 1) { $ok = 1; } # TODO fix check with deductions
            $linetotals = myround($linetotals); $invoicetotal = myround($invoicetotal); # 2017 01 25
            if ($linetotals == $invoicetotal || $_SESSION['ds_invoicedeductions'] == 1) { $ok = 1; } # TODO fix check with deductions
            else
            {
              echo '<span class="alert">Erreur sur facture ' . $_POST['confirmed' . $i] . ' (annulée)</span> ('.$linetotals.' vs '.$invoicetotal.')<br>';
              $_POST['cancelled' . $i] = $_POST['confirmed' . $i];
              $_POST['confirmed' . $i] = '';
            }
            ###
            if ($ok)
            {
              $querymain = 'update invoice set confirmed=1,proforma=0,invoicedate=curdate(),invoicetime=curtime()';
              if ($_SESSION['ds_confirmchangesdate'] == 1)
              {
                $query = 'select daystopay,special from client,invoice,clientterm where invoice.clientid=client.clientid and client.clienttermid=clientterm.clienttermid and invoiceid=?';
                $query_prm = array($_POST['confirmed' . $i]);
                require('inc/doquery.php');
                $rowEXTRA = $query_result[0];
                $daystopay = $rowEXTRA['daystopay'];
                if ($rowEXTRA['special'] == 1) # end of month
                {
                  $endofmonthdate = new DateTime($_SESSION['ds_curdate']);
                  $endofmonthdate->modify('last day of this month');
                  $daystopay = ((int) $endofmonthdate->format('d')) - ((int) substr($_SESSION['ds_curdate'],8,2));
                }
                $querymain = $querymain . ',accountingdate=curdate(),paybydate=DATE_ADD(curdate(), INTERVAL ' . $daystopay . ' DAY)';
              }
              $query = $querymain . ' where invoiceid=?';
              $query_prm = array($_POST['confirmed' . $i]);
              require('inc/doquery.php');
              if($num_results == count($query_prm))
              {
                $listconfirmed .= ' <a href="printwindow.php?report=showinvoice&invoiceid=' . $_POST['confirmed' . $i] . '" target=_blank>' . $_POST['confirmed' . $i] . '</a>';
                $in_confirmed .= $_POST['confirmed' . $i] . ',';
              }
            }
          }
          if ($_POST['cancelled' . $i] && !$_POST['confirmed' . $i])
          {
            $query = 'update invoice set cancelledid=1,invoicedate=curdate(),invoicetime=curtime() where invoiceid=?';
            $query_prm = array($_POST['cancelled' . $i]);
            require('inc/doquery.php');
            if($num_results == count($query_prm))
            {
              $listcancelled .= ' <a href="printwindow.php?report=showinvoice&invoiceid=' . $_POST['cancelled' . $i] . '" target=_blank>' . $_POST['cancelled' . $i] . '</a>';
            }
          }
        }
        echo 'Factures confirmées:'.$listconfirmed.'<br>Factures annulées:'.$listcancelled.'<br><br>';
        
        require('inc/move_to_history.php');

      }

      $listall = '';
      $query = 'select localvesselid,reference,isnotice,proforma,isreturn,invoiceid,accountingdate,clientname,invoice.clientid as clientid,invoiceprice,initials
      from invoice,client,usertable
      where invoice.userid=usertable.userid and invoice.clientid=client.clientid
      and cancelledid=0 and confirmed=0';
      $query_prm = array();
      $query .= ' and isnotice=1 and isreturn=0';
      if ($myuserid > 0)
      {
        $query .= ' and invoice.userid=?'; array_push($query_prm, $myuserid);
      }
      if ($limitdates == 1)
      {
        $datename = 'startdate'; require('inc/datepickerresult.php');
        $datename = 'stopdate'; require('inc/datepickerresult.php');
        $query .= ' and invoice.accountingdate>=?'; array_push($query_prm, $startdate);
        $query .= ' and invoice.accountingdate<=?'; array_push($query_prm, $stopdate);
      }
      $query = $query . ' order by invoiceid';
      require('inc/doquery.php');
      echo '<form method="post" action="clientaccess.php"><table class="detailinput"><tr><td><b>Confirmer</td><td><b>Facture</td>';
      echo '<td><b>' . $_SESSION['ds_term_accountingdate'] . '</td><td><b>Client</td><td><b>Prix total</td>';
      if ($_SESSION['ds_term_reference'] != "") { echo '<td><b>' . d_output($_SESSION['ds_term_reference']) . '</td>'; }
      else { echo '<td><b>Référence</td>'; }
      echo '<td><b>Facturier</td>';
      if (isset($localvesselA)) { echo '<td><b>Bateau</b></td>'; }
      echo '<td><b>Annuler</td></tr>';
      for ($i=0; $i < $num_results; $i++)
      {
        $row = $query_result[$i];
        $listall = $listall . ' ' . $row['invoiceid'];
        $returntext = ""; if ($row['isreturn'] == 1) { $returntext = '(Avoir) '; }
        if ($row['proforma'] == 1) { $returntext = '(Proforma) '; }
        if ($row['isnotice'] == 1) { $returntext = '('.$_SESSION['ds_term_invoicenotice'].') '; }
        echo '<tr><td> &nbsp; <input type="checkbox" class="confirm" name="confirmed' . $i . '" value="' . $row['invoiceid'] . '"></td><td align=right>' . $returntext . '<a href="printwindow.php?report=showinvoice&usedefaultstyle=1&invoiceid=' . $row['invoiceid'] . '" target=_blank>' . myfix($row['invoiceid']) . '</a></td><td align=right>' . datefix2($row['accountingdate']) . '</td><td>' . $row['clientid'] . ': ' . d_output(d_decode($row['clientname'])) . '</td><td align=right>' . myfix($row['invoiceprice']) . '</td><td>' . $row['reference'] . '</td><td>' . $row['initials'] . '</td>';
        if (isset($localvesselA)) { echo '<td>' . $localvesselA[$row['localvesselid']] . '</td>'; }
        echo '<td> &nbsp; <input type="checkbox" class="cancel" name="cancelled' . $i . '" value="' . $row['invoiceid'] . '"></td></tr>';
      }
      $colspan=6; if (isset($localvesselA)) { $colspan++; }
      echo '<tr><td> &nbsp; <input type="button" id="confirmall" value="Tous" /></td><td colspan='.$colspan.'></td><td> &nbsp;  <input type="button" id="cancelall" value="Tous" /></td></tr>';
      echo '<tr><td colspan="10" align="center"><input type=hidden name="step" value="1"><input type="hidden" name="listall" value="' . $listall . '"><input type=hidden name="clientaccessmenu" value="' . $clientaccessmenu . '"><input type=hidden name="confirm" value="1"><input type=hidden name="myuserid" value="' . $_POST['myuserid'] . '"><input type=hidden name="results" value="' . $num_results . '"><input type="submit" value="Confirmer / annuler facture(s)"></td></tr>';
      if ($limitdates == 1)
      {
        echo '<input type=hidden name=limitdates value="' . $limitdates . '"><input type=hidden name=startdate value="' . $startdate . '"><input type=hidden name=stopdate value="' . $stopdate . '">';
      }
      echo '</table></form>';
    }
  
  break;
    
  }

}

?>