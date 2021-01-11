<h2>Feuille Entrepôt:</h2>
<h2 class="alert">Ce rapport va disparaître</h2>
<form method="post" action="reportwindow.php" target="_blank"><table>
<tr><td>Numéros:</td></tr>
<tr><td><input autofocus type="text" name="invoicegroupid" size=10></td><td><input type="text" name="invoicegroupid6" size=10></td><td><input type="text" name="invoicegroupid11" size=10></td><td><input type="text" name="invoicegroupid12" size=10></td></tr>
<tr><td><input type="text" name="invoicegroupid2" size=10></td><td><input type="text" name="invoicegroupid7" size=10></td><td><input type="text" name="invoicegroupid13" size=10></td><td><input type="text" name="invoicegroupid14" size=10></td></tr>
<tr><td><input type="text" name="invoicegroupid3" size=10></td><td><input type="text" name="invoicegroupid8" size=10></td><td><input type="text" name="invoicegroupid15" size=10></td><td><input type="text" name="invoicegroupid16" size=10></td></tr>
<tr><td><input type="text" name="invoicegroupid4" size=10></td><td><input type="text" name="invoicegroupid9" size=10></td><td><input type="text" name="invoicegroupid17" size=10></td><td><input type="text" name="invoicegroupid18" size=10></td></tr>
<tr><td><input type="text" name="invoicegroupid5" size=10></td><td><input type="text" name="invoicegroupid10" size=10></td><td><input type="text" name="invoicegroupid19" size=10></td><td><input type="text" name="invoicegroupid20" size=10></td></tr>
<tr><td>Entrepôt:</td>
<td><select name="warehouseid"><option value=0></option><?php

$query = 'select warehouseid,warehousename from warehouse order by warehousename';
$query_prm = array();
        require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $row2 = $query_result[$i];
  echo '<option value="' . $row2['warehouseid'] . '">' . $row2['warehousename'] . '</option>';
}
?></select></td></tr>
<tr><td>Temperature:
<?php
$dp_itemname = 'temperature'; $dp_allowall = 1; $dp_nonempty = 1; $dp_selectedid = -1;
require('inc/selectitem.php');
?>
</td></tr>
<tr><td colspan="2" align="center"><input type=hidden name="report" value="deliverywarehouse"><input type="submit" value="Valider"></td></tr>
</table></form>