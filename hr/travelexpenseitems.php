<?php

# TODO IMPORTANT replace into => on duplicate key update

if (!isset($paymenttypeA)) { require('preload/paymenttype.php'); }

$MAX_LENGTH_DISPLAYED = 60;

#title
switch ($currentstepitem)
{
  case $STEP_FORM_EXPITEM:
    echo '<h2>' . d_trad('travelexpenseitem') . '</h2>';
    //$travelexpenseid = $_GET['travelexpenseid'];    
    break;
    
  case $STEP_FORM_EXPITEM_ADD:
    echo '<h2>' . d_trad('addexpense') . '</h2>'; 
    $travelexpenseid = $_POST['travelexpenseid'];    
    break;   

  case $STEP_FORM_EXPITEM_MODIFY:
    echo '<h2>' . d_trad('modifyexpense') . '</h2>';
    $travelexpenseitemid = $_GET['travelexpenseitemid'];  
    $travelexpenseid = $_GET['travelexpenseid'];     
    break;   

  case $STEP_FORM_EXPITEM_VALIDATE_ADD:
    echo '<h2>' . d_trad('travelexpenseitem') . '</h2>';
    break;   

  case $STEP_FORM_EXPITEM_VALIDATE_MOD:
    echo '<h2>' . d_trad('travelexpenseitem') . '</h2>';
    break;       
}

$numrows = 0;
if ($currentstepitem == $STEP_FORM_EXPITEM_VALIDATE_ADD || $currentstepitem == $STEP_FORM_EXPITEM_VALIDATE_MOD)
{
  # save
  if ($currentstepitem == $STEP_FORM_EXPITEM_VALIDATE_MOD) 
  { 
    $travelexpenseitemid = $_POST['travelexpenseitemid'] +0;
  } 
  if ($currentstepitem != $STEP_FORM_EXPITEM) 
  {   
    $travelexpenseid = $_POST['travelexpenseid'];
  }
  $datename = 'date'; require('inc/datepickerresult.php'); 
  $travelexpensetypeid= $_POST['travelexpensetypeid']+0;
  $travelexpenseitemdescr = $_POST['travelexpenseitemdescr'];
  $num = $_POST['num']+0;   if ( $num == 0) { $num = 1;}
  $unitprice = (int)$_POST['price']+0; 
  $unitpricevat = (int)$_POST['pricevat']+0; 
  $paymenttypeid = $_POST['paymenttypeid']+0;  
  $refundamount = (int)$_POST['refundamount']+0;  
  $refundamountvat = (int)$_POST['refundamountvat']+0;  
  $deleted = $_POST['deleted'] + 0;  

  #calculation of refund
  #Refund the price paid except if it overpasses limit 
  if ($deleted == 0)
  {
    #without tax
    $price = $unitprice * $num;  
    $refundlimitunit = $travelexpensetype_refundlimitA[$travelexpensetypeid]; 
    $refundlimit = $refundlimitunit * $num; 
    if ($refundamount == 0)
    {
      if ( $refundlimit == 0) 
      { 
        $refundamount = $price;
      }
      else
      {
        $refundamount = $refundlimit;
        if ( $price > 0 && $refundamount > $price ) { $refundamount = $price;} 
        elseif ($price > $refundamount) { echo '<p class=alert>' . d_trad('limitexceeded',array($travelexpenseitemdescr,myfix($refundlimitunit))) . '</p>';}
      }
    }
    else
    { 
      if ( $refundamount > $price )
      {
        echo '<p class=alert>' . d_trad('refundamountsupprice',array($travelexpenseitemdescr)) . '</p>';
      }
      if ( $refundlimit > 0  && $refundamount > $refundlimit ) 
      { 
        echo '<p class=alert>' . d_trad('limitexceeded',array($travelexpenseitemdescr,myfix($refundlimitunit))) . '</p>';
      }
    }
    
    #same calculation with VAT
    $pricevat = $unitpricevat * $num;
    $refundlimitunitvat = $travelexpensetype_refundlimitvatA[$travelexpensetypeid];
    $refundlimitvat =  $refundlimitunitvat * $num;
    if ($refundamountvat == 0)
    {
      if ( $refundlimitvat == 0) 
      { 
        $refundamountvat = $pricevat;
      }
      else
      {
        $refundamountvat = $refundlimitvat;
        if ( $pricevat >0 && $refundamountvat > $pricevat  ) { $refundamountvat = $pricevat;}  
        elseif ($pricevat > $refundamountvat) { echo '<p class=alert>' . d_trad('limitvatexceeded',array($travelexpenseitemdescr,myfix($refundlimitunitvat))) . '</p>';}    
      }
    }
    else 
    { 
      if ( $refundamountvat > $pricevat )
      {
        echo '<p class=alert>' . d_trad('refundamountvatsupprice',array($travelexpenseitemdescr)) . '</p>';
      }
      if ( $refundlimitvat > 0 && $refundamountvat > $refundlimitvat ) 
      { 
        echo '<p class=alert>' . d_trad('limitvatexceeded',array($travelexpenseitemdescr,myfix($refundlimitunitvat))) . '</p>';
      }
    }
  }
    
  $query = 'REPLACE INTO travelexpenseitem (travelexpenseitemid,travelexpenseid,date,travelexpensetypeid,travelexpenseitemdescr,num,price,priceVAT,paymenttypeid,refundamount,refundamountVAT,deleted) values (?,?,?,?,?,?,?,?,?,?,?,?)';
  $query_prm = array($travelexpenseitemid,$travelexpenseid,$date,$travelexpensetypeid,$travelexpenseitemdescr,$num,$unitprice,$unitpricevat,$paymenttypeid,$refundamount,$refundamountvat,$deleted);
  require ('inc/doquery.php');
  if ( $num_results > 0 )
  {
    $travelexpenseitemid = $query_insert_id;
    if ($currentstepitem == $STEP_FORM_EXPITEM_VALIDATE_ADD)
    {
      echo '<p>' . d_trad('travelexpenseitemadded',$travelexpenseitemdescr) . '</p><br>';   
    }
    else
    {
      echo '<p>' . d_trad('travelexpenseitemmodified',$travelexpenseitemdescr) . '</p><br>';      
    }
  }  
}
  
if ( $currentstepitem > $STEP_FORM_EXPITEM_TRAVELEXP)
{
  if ( $currentstepitem != $STEP_FORM_EXPITEM_ADD)
  {
    # pre-filled form 
    $query = 'select * from travelexpenseitem where deleted=0 ';
    if ( $currentstepitem ==  $STEP_FORM_EXPITEM_MODIFY )
    {
      $query .= ' and travelexpenseitemid=?';
      $query_prm = array($travelexpenseitemid);    
    }
    else
    {
      $query .= ' and travelexpenseid=?';
      $query_prm = array($travelexpenseid);   
    }
    require ('inc/doquery.php');
    $numrows = $num_results;
    if ($numrows > 0 )
    {
      $row = $query_result;  
    }
  }

  ?>
  <form method="post" action="hr.php" name=formexpitem>
  <?php 
  if ( $numrows > 0 && $currentstepitem != $STEP_FORM_EXP_MODIFY)
  {?>
    <table class="report">
    <thead>
      <th><?php echo d_trad('travelexpenseitemdescr'); ?></th>   
      <th><?php echo d_trad('date'); ?></th> 
      <th><?php echo d_trad('travelexpensetype'); ?></th> 
      <th><?php echo d_trad('num'); ?></th> 
      <th><?php echo d_trad('unitprice'); ?></th> 
      <th><?php echo d_trad('unitpricevat'); ?></th> 
      <th><?php echo d_trad('paymenttype'); ?></th>        
      <th><?php echo d_trad('refundamount'); ?></th> 
      <th><?php echo d_trad('refundamountvat'); ?></th> 
      <?php
      if(( $ds_showdeleteditems  || $currentstepitem == $STEP_FORM_EXPITEM_MODIFY) && $currentstepitem != $STEP_FORM_EXPITEM_ADD)
      {
        echo '<th>' . d_trad('deleted') . '</th>';
      } ?>
    </thead><?php 
  }
  else
  {
    echo '<table>';
  }

  if ( $numrows > 0 && $currentstepitem != $STEP_FORM_EXP_MODIFY)
  {
    $totalprice = 0;$totalpricevat = 0;
    $totalrefundamount = 0;$totalrefundamountvat = 0;
    for ($r=0;$r<$numrows;$r++)
    {
      $travelexpenseitemid = $row[$r]['travelexpenseitemid'];
      $travelexpenseitemdescrdisplayed = d_output($row[$r]['travelexpenseitemdescr']); 
      $travelexpensetypeid = $row[$r]['travelexpensetypeid'] +0 ;
      $travelexpensetype = '';if ($travelexpensetypeid > 0) { $travelexpensetype = $travelexpensetypeA[$travelexpensetypeid];}
      $paymenttypeid = $row[$r]['paymenttypeid'] +0 ;
      $paymenttype = '';if ($paymenttypeid > 0) { $paymenttype = $paymenttypeA[$paymenttypeid];}
      $date = $row[$r]['date'];
      $num = $row[$r]['num'];
      $unitprice = $row[$r]['price'];
      $unitpricevat = $row[$r]['priceVAT'];
      $refundamount = $row[$r]['refundamount'];
      $refundamountvat = $row[$r]['refundamountVAT'];
      $href = '';
      
      if ($state == $STATE_SAVED)
      {
        $href = '<a href="hr.php?hrmenu=travelexpenses&step=' . $STEP_FORM_EXP_MODIFY . '&stepitem=' . $STEP_FORM_EXPITEM_MODIFY . '&travelexpenseitemid=' . $travelexpenseitemid. '&travelexpenseid=' . $travelexpenseid . '">';
      }
      echo d_tr();   
      
      if ( strlen($travelexpenseitemdescrdisplayed) >= $MAX_LENGTH_DISPLAYED ) { $travelexpenseitemdescrdisplayed = substr($travelexpenseitemdescrdisplayed,0,$MAX_LENGTH_DISPLAYED) . '...'; }
      echo '<td>' . $href . $travelexpenseitemdescrdisplayed. '</a></td>'; 
      
      echo '<td>' . $href . datefix2($date) . '</a></td>';
      echo '<td>' . $href . d_output($travelexpensetype) . '</a></td>';          
      echo '<td align=right>' . $href . $num . '</a></td>';  
      $totalprice += $unitprice * $num;   
      $totalpricevat += $unitpricevat * $num;   
      #don't add item whitout refundamount
      if ($refundamount > 0 )
      {
        $totalrefundamount += $refundamount;      
      }
      if ($refundamountvat > 0 )
      {
        $totalrefundamountvat += $refundamountvat;      
      }      
      echo '<td align=right>' . $href  . myfix($unitprice) . '</a></td>';      
      echo '<td align=right>' . $href  . myfix($unitpricevat) . '</a></td>';      
      echo '<td>' . $href  . $paymenttype . '</a></td>';   
      echo '<td align=right>' . $href  . myfix($refundamount) . '</a></td>';    
      echo '<td align=right>' . $href  . myfix($refundamountvat) . '</a></td>';      
      if ($currentstepitem != $STEP_FORM_EXPITEM_ADD && $ds_showdeleteditems)
      {
        echo '<td align=center>' . $href ;
        if ($row[$r]['deleted'] == 1) { echo '&radic;'; }
        echo '</a></td>';
      }
      echo '</tr>';     
    }
      
    #total
    echo '<tr><td><b>' . d_trad('TOTAL') . '</b></td>';
    echo '<td>&nbsp;</td>';
    echo '<td>&nbsp;</td>';
    echo '<td>&nbsp;</td>';
    echo '<td align=right><b>' . myfix($totalprice) . '</b></td>';        
    echo '<td align=right><b>' . myfix($totalpricevat) . '</b></td>';        
    echo '<td>&nbsp;</td>';   
    echo '<td align=right><b>' . myfix($totalrefundamount) . '</b></td>';       
    echo '<td align=right><b>' . myfix($totalrefundamountvat) . '</b></td>';      
    if ($currentstepitem != $STEP_FORM_EXPITEM_ADD && $ds_showdeleteditems)
    {
      echo '<td>&nbsp;</td>';         
    }
    echo '</tr>';    
    echo '</table>';
  }
  else if ( $currentstepitem == $STEP_FORM_EXPITEM_ADD || $currentstepitem == $STEP_FORM_EXP_MODIFY)
  {?>
    <tr><td><?php echo d_trad('travelexpenseitemdescr:'); ?></td>
    <td><input type=text name=travelexpenseitemdescr value="<?php echo d_input($row[0]['travelexpenseitemdescr']); ?>"></td></tr>
    <tr><td><?php echo d_trad('date:'); ?></td>
    <td><?php $datename = 'date';$dp_datepicker_min='2014-01-01';$selecteddate=$row[0]['date'];require('inc/datepicker.php');?></td></tr>
  
    <?php $dp_itemname = 'travelexpensetype'; $dp_noblank = 1;$dp_description = d_trad('travelexpensetype');$dp_selectedid = $row[0]['travelexpensetypeid'];
    require('inc/selectitem.php');
    
    echo '<tr><td>' . d_trad('num:') . '</td>';
    echo '<td><input type=number name=num value='. $row[0]['num'] .'></td></tr>';
    
    echo '<tr><td>' . d_trad('unitprice:') . '</td>';
    echo '<td><input type=text name=price style="text-align:right;" value=' . d_input($row[0]['price'],'decimal')  . '></td></tr>';
    echo '<tr><td>' . d_trad('unitpricevat:') . '</td>';
    echo '<td><input type=text name=pricevat style="text-align:right;" value=' . ($row[0]['priceVAT']+0) . '></td></tr>';    
    
    $dp_itemname = 'paymenttype'; $dp_noblank = 0;$dp_description = d_trad('paymenttype');$dp_selectedid = $paymenttypeid;
    require('inc/selectitem.php');
    
    if ( $currentstepitem == $STEP_FORM_EXP_MODIFY && ($ds_ishrsuperuser || $ismanager))
    {
      echo '<tr><td>' . d_trad('refundamount:') . '</td>';
      echo '<td><input type=text name=refundamount style="text-align:right;" value=' . d_input($row[0]['refundamount'],'decimal') . '></td></tr>';
      echo '<tr><td>' . d_trad('refundamountvat:') . '</td>';
      echo '<td><input type=text name=refundamountvat style="text-align:right;" value=' . d_input($row[0]['refundamountVAT'],'decimal') . '></td></tr>';      
    }
   
    if ($currentstepitem != $STEP_FORM_EXPITEM_ADD)
    {
      echo '<tr><td>' . d_trad('deleted:') . '</td><td><input type=checkbox name="deleted" value=1 ';
      if ($row[0]['deleted']) { echo ' checked '; }
      echo '></td></tr>';
      echo '<input type=hidden name="travelexpenseitemid" value=' . $travelexpenseitemid .'>';
    }
    echo '<input type=hidden name="travelexpenseid" value=' . $travelexpenseid .'>';    
    echo '</table>';    
  }
} ?>

<input type=hidden name="hrmenu" value="<?php echo $hrmenu; ?>">
<input type=hidden name="numrows" value="<?php echo $numrows; ?>">
<?php
if ($state == $STATE_SAVED)
{
  if ($currentstepitem == $STEP_FORM_EXPITEM )
  {
    echo '<input type=hidden name="travelexpenseid" value="' . $travelexpenseid . '"><input type=hidden name="stepitem" value="' . $STEP_FORM_EXPITEM_ADD . '"><br><div align="center"><input type="submit" value="' . d_trad('add') . '"></div>';
  }
  else if ($currentstepitem == $STEP_FORM_EXPITEM_ADD)
  {
    echo '<input type=hidden name="travelexpenseid" value="' . $travelexpenseid . '"><input type=hidden name="stepitem" value="' . $STEP_FORM_EXPITEM_VALIDATE_ADD . '"><br><div align="center"><input type="submit" value="' . d_trad('add') . '"></div>';
  }
  else if ($currentstepitem == $STEP_FORM_EXPITEM_MODIFY)
  {
    echo '<input type=hidden name="stepitem" value="' . $STEP_FORM_EXPITEM_VALIDATE_MOD . '"><br><div align="center"><input type="submit" value="' . d_trad('modify') . '"></div>';
  } 
  else if ($currentstepitem == $STEP_FORM_EXPITEM_VALIDATE_ADD || $currentstepitem == $STEP_FORM_EXPITEM_VALIDATE_MOD)
  {
    echo '<input type=hidden name="travelexpenseid" value="' . $travelexpenseid . '"><input type=hidden name="stepitem" value="' . $STEP_FORM_EXPITEM_ADD . '"><br><div align="center"><input type="submit" value="' . d_trad('add') . '"></div>';
  } 
}

?>
</table>
</form>