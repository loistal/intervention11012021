<?php

if ($_SESSION['ds_clientaccess_clientid'] == 4126) # Nestle
{
  if ($_SESSION['ds_userid'] == 106 || $_SESSION['ds_userid'] == 108 || $_SESSION['ds_userid'] == 105)
  {
    echo '&nbsp; <a href="clientaccess.php?clientaccessmenu=nestledaily">Nestlé vente/jour (txt)</a><br>';

    echo '<br>&nbsp; <a href="clientaccess.php?clientaccessmenu=prodcat">Catalogue Produit</a><br>';
    
    echo '<br>&nbsp; <a href="clientaccess.php?clientaccessmenu=sohreport">SOH report (txt)</a><br>';
  }
  
  if ($_SESSION['ds_userid'] == 104 || $_SESSION['ds_userid'] == 108 || $_SESSION['ds_userid'] == 107)
  {
    echo '<br>&nbsp; <a href="clientaccess.php?clientaccessmenu=confirmbdl">Confirmer BdL Nestlé</a><br>';
  }

}

?>
<br><br>