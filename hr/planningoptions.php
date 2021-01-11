<?php

echo '<h2>' . d_trad('planningoptions') . '</h2>';

#update
if ( $currentstep == 1)
{
  $planningteamnbvalues = (int) $_POST['planningteamnbvalues'];
  $planningteamnbvaluessup = (int) $_POST['planningteamnbvaluessup'];
  $planningteamdayoff = (int) $_POST['planningteamdayoff'];
  $planningteamdayoffdisplayed = (int) $_POST['planningteamdayoffdisplayed'];  
  $planningteamcommentcolumn = (int) $_POST['planningteamcommentcolumn'];  

  $query = 'update hroptions set planningteamnbvalues=?,planningteamnbvaluessup=?,planningteamdayoff=?,planningteamdayoffdisplayed=?,planningteamcommentcolumn=?';
  $query_prm = array($planningteamnbvalues,$planningteamnbvaluessup,$planningteamdayoff,$planningteamdayoffdisplayed,$planningteamcommentcolumn);
  
  for($i=1;$i<=$ds_planningteamnbvalues;$i++)
  {
    $planningteamvalue = 'term_planningteamvalue' . $i;
    $$planningteamvalue =  $_POST['term_planningteamvalue' . $i];
    $query .= ',' . $planningteamvalue . '=?';
    array_push($query_prm,$$planningteamvalue);
  }  
  for($i=1;$i<=$ds_planningteamnbvaluessup;$i++)
  {
    $planningteamvaluesup = 'term_planningteamvaluesup' . $i;
    $$planningteamvaluesup =  $_POST['term_planningteamvaluesup' . $i];
    $query .= ',' . $planningteamvaluesup . '=?';
    array_push($query_prm,$$planningteamvaluesup);
  }    
  
  require('inc/doquery.php');
  echo '<p>'. d_trad('hroptionssaved') . '</p>';
}

#display form

$query = 'select * from hroptions';
$query_prm = array();
require('inc/doquery.php');
$row = $query_result[0];
$planningteamnbvalues = $row['planningteamnbvalues'];
$planningteamnbvaluessup = $row['planningteamnbvaluessup'];

echo '<form method="post" action="hr.php"><table>';
echo '<tr><td>' . d_trad('planningteamnbvalues:') . '</td>';
echo '<td><select name="planningteamnbvalues" >';
for($i=1;$i<=3;$i++)
{
  $selected = '';
  if ( $i == $planningteamnbvalues ) { $selected = ' SELECTED'; }
  echo '<option value=' . $i . ' ' . $selected . '>' . $i . '</option>';
}
echo '</td></tr>';

for($i=1;$i<=$planningteamnbvalues;$i++)
{
  if($i == 1)
  {
    $icol = d_trad('number1');      
  }
  else
  {
    $icol = d_trad('numberwithparam',$i);
  }
  echo '<tr><td>' . d_trad('planningteamcolname:', $icol) . '</td><td><input type="text" STYLE="text-align:right" name="term_planningteamvalue' . $i . '" value="' . $row['term_planningteamvalue'.$i] . '" size=30></td></tr>';
}

echo '<tr><td colspan=2>&nbsp;</td></tr>';

echo '<tr><td>' . d_trad('planningteamnbvaluessup:') . '</td>';
echo '<td><select name="planningteamnbvaluessup" >';
for($i=0;$i<=3;$i++)
{
  $selected = '';
  if ( $i == $planningteamnbvaluessup ) { $selected = ' SELECTED'; }
  echo '<option value=' . $i . ' ' . $selected . '>' . $i . '</option>';
}
echo '</td></tr>';

for($i=1;$i<=$planningteamnbvaluessup;$i++)
{
  if($i == 1)
  {
    $icol = d_trad('number1');
  }
  else
  {
    $icol = d_trad('numberwithparam',$i);
  }
  echo '<tr><td>' . d_trad('planningteamcolsupname:', $icol) . '</td><td><input type="text" STYLE="text-align:right" name="term_planningteamvaluesup' . $i . '" value="' . $row['term_planningteamvaluesup'.$i] . '" size=30></td></tr>';
} 

echo '<tr><td colspan=2>&nbsp;</td></tr>';

echo '<tr><td>' . d_trad('planningteamdayoff:') . '</td>';
echo '<td><select name="planningteamdayoff" >';
for($i=0;$i<=7;$i++)
{
  $selected = '';
  if ( $i == $row['planningteamdayoff'] ) { $selected = ' SELECTED'; }
  if ( $i == 0)
  {
    echo '<option value=0 ' . $selected . '>' . d_trad('none' ) . '</option>';
  }
  else
  {
    echo '<option value=' . $i . ' ' . $selected . '>' . d_trad('dayofweek' .$i ) . '</option>';
  }
}
echo '</td></tr>';

echo '<tr><td>' . d_trad('planningteamdayoffdisplayed:') . '</td>';
echo '<td><input type="checkbox" name="planningteamdayoffdisplayed" value="1"';
if ($row['planningteamdayoffdisplayed']) { echo ' checked'; }
echo '/></td></tr>';

echo '<tr><td colspan=2>&nbsp;</td></tr>';

echo '<tr><td>' . d_trad('planningteamcommentcolumn:') . '</td>';
echo '<td><input type="checkbox" name="planningteamcommentcolumn" value="1"';
if ($row['planningteamcommentcolumn']) { echo ' checked'; }
echo '/></td></tr>';
echo '<tr><td colspan="2" align="center"><input type=hidden name="step" value="1"><input type=hidden name="hrmenu" value="' . $hrmenu . '"><input type="submit" value="Valider"></td></tr></table></form>';
?>