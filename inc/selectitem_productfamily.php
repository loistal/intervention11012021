<?php

# input: $dp_selectedid $dp_allowall $dp_nonempty $dp_noblank $dp_description $dp_notr $dp_colspan
# output: $_POST['productfamilyid']

if(!isset($dp_notr)) { $dp_notr = 0; }
if($dp_notr != 1) { echo '<tr>'; }
if ($dp_description != '') { echo '<td>' . $dp_description . ':</td>'; }
if(isset($dp_colspan)) { echo '<td colspan=' . $dp_colspan . '>';}
else {echo '<td>';}
echo '<select name="productfamilyid">';
if ($dp_allowall == 1) { echo '<option value=-1>'. d_trad('selectall') .'</option>'; }
if ($dp_noblank != 1)
{
  echo '<option value=0';
  if (isset($dp_selectedid) && $dp_selectedid === 0) { echo ' selected'; }
  echo '></option>';
}
if ($dp_nonempty == 1)
{
  echo '<option value=-2';
  if (isset($dp_selectedid) && $dp_selectedid == -2) { echo ' selected'; }
  echo '>'.d_trad('nonempty').'</option>';
}
$query = 'select productfamilyid,productfamilyname,productfamilygroupname,productdepartmentname from productfamily,productfamilygroup,productdepartment where productfamilygroup.productdepartmentid=productdepartment.productdepartmentid and productfamily.productfamilygroupid=productfamilygroup.productfamilygroupid order by productdepartmentname,productfamilygroupname,productfamilyname';
$query_prm = array();
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $row2 = $query_result[$i];
  echo '<option value="' . $row2['productfamilyid'] . '"';
	if (isset($dp_selectedid) && $dp_selectedid == $row2['productfamilyid']) { echo ' selected'; }
	echo '>' . $row2['productdepartmentname'] . '/' . $row2['productfamilygroupname'] . '/' . $row2['productfamilyname'] . '</option>';
}
?></select><?php
if ($dp_description != '') { echo '</td>'; }
if($dp_notr != 1) { echo '</tr>'; }

unset($dp_description);
unset($dp_allowall,$dp_nonempty);
unset($dp_selectedid);
unset($dp_noblank);

?>