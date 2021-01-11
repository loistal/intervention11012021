<h2>Rapport stock client:</h2>
<form method="post" action="reportwindow.php" target="_blank"><table>
<tr><td>Numéro produit:</td><td><input autofocus type="text" STYLE="text-align:right" name="productid" size=5></td></tr>
<tr><td><input type=radio name=filtertype value=1> Catégorie:</td>
<td><select name="clientcategoryid"><?php
    
    $query = 'select clientcategoryid,clientcategoryname from clientcategory order by clientcategoryname';
    $query_prm = array();
  require('inc/doquery.php');
    for ($i=0; $i < $num_results; $i++)
    {
      $row2 = $query_result[$i];
      echo '<option value="' . $row2['clientcategoryid'] . '">' . $row2['clientcategoryname'] . '</option>';
    }
    ?></select></td></tr>
<tr><td><input type=radio name=filtertype value=2 checked> Île:</td>
<td><select name="islandid"><?php
    $query = 'select islandid,islandname from island order by islandname';
    $query_prm = array();
  require('inc/doquery.php');
    for ($i=0; $i < $num_results; $i++)
    {
      $row2 = $query_result[$i];
      echo '<option value="' . $row2['islandid'] . '">' . $row2['islandname'] . '</option>';
    }
    ?></select></td></tr>
<tr><td>Comptes fermés:</td><td><select name="nodeleted"><option value=0></option><option value=1>Exclure comptes fermés</option><option value=2>Uniquement comptes fermés</option></select></td><tr>
<tr><td>(Optionel) Num client:</td><td><input type="text" STYLE="text-align:right" name="from_clientid" size=5> à <input type="text" STYLE="text-align:right" name="to_clientid" size=5></td></tr>
<tr><td colspan="2" align="center"><input type=hidden name="report" value="reportclientstock"><input type="submit" value="Valider"></td></tr>
</table></form><?php
echo '<p class="alert">Ce rapport est très lourd, veuillez patienter pendant la création.</p>';

?>