<?php

$query = 'select productdepartmentname,productfamilyname,productfamilygroupname,departmentrank,familygrouprank,familyrank from productdepartment,productfamilygroup,productfamily where productfamilygroup.productdepartmentid=productdepartment.productdepartmentid and productfamily.productfamilygroupid=productfamilygroup.productfamilygroupid
order by departmentrank,productdepartmentname,familygrouprank,productfamilygroupname,familyrank,productfamilyname';
$query_prm = array();
require('inc/doquery.php');
echo '<h2>Liste des familles de produits:</h2>';
echo '<table class=report><thead><th>Département (rangement)</th><th>Famille (rangement)</th><th>Sous-famille (rangement)</th></thead>';
for ($i=0; $i < $num_results; $i++)
{
  $row = $query_result[$i];
  echo d_tr() .'<td>' . $row['productdepartmentname'] . ' (' . $row['departmentrank'] . ')</td><td>' . $row['productfamilygroupname'] . ' (' . $row['familygrouprank'] . ')</td><td>' . $row['productfamilyname'] . ' (' . $row['familyrank'] . ')</td></tr>';
}
echo '</table>';

?>