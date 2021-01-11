<?php

# input: $dp_selectedid $dp_allowall $dp_nonempty $dp_noblank $dp_description $dp_notr $dp_colspan $dp_notddescr
# output: $_POST['clientcategory3id']
if($dp_notr != 1) { echo '<tr>'; }
if ($dp_description != '') 
{ 
  if($dp_notddescr != 1) { echo '<td>'; }
	echo $dp_description . ':</td>';
}
if(isset($dp_colspan)) { echo '<td colspan=' . $dp_colspan . '>';}
else {echo '<td>';}
echo '<select name="clientcategory3id">';
if ($dp_allowall == 1) { echo '<option value=-1>'. d_trad('selectall') .'</option>'; }
if ($dp_noblank != 1)
{
  echo '<option value=0';
  if ($dp_selectedid === 0) { echo ' selected'; }
  echo '></option>';
}
if ($dp_nonempty == 1)
{
  echo '<option value=-2';
  if ($dp_selectedid == -2) { echo ' selected'; }
  echo '>'.d_trad('nonempty').'</option>';
}
$query = 'select clientcategory3id,clientcategory3name,clientcategorygroup3name from clientcategory3 LEFT JOIN clientcategorygroup3 on clientcategory3.clientcategorygroup3id=clientcategorygroup3.clientcategorygroup3id where clientcategory3.deleted = 0 order by clientcategorygroup3name,clientcategory3name';
$query_prm = array();
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $row2 = $query_result[$i];
  echo '<option value="' . $row2['clientcategory3id'] . '"';
	if ($dp_selectedid == $row2['clientcategory3id']) { echo ' selected'; }
	echo '>';
	if ($row2['clientcategorygroup3name'] != NULL) { echo $row2['clientcategorygroup3name'] . '/' ;}
	echo $row2['clientcategory3name'] . '</option>';
}
?></select><?php
if ($dp_description != '') { echo '</td>'; }
if($dp_notr != 1) { echo '</tr>'; }

unset($dp_description);
unset($dp_allowall,$dp_nonempty);
unset($dp_selectedid);
unset($dp_noblank);

?>