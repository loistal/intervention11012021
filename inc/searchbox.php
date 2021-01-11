<?php
# TODO icons for menustyle 5
echo '<form';
if ($_SESSION['ds_menustyle'] == 2) { echo ' class="loginbox"'; }
echo ' method="post" action="search.php" target=_blank><fieldset><input type="search" class="searchbox" STYLE="text-align:right" name="name" placeholder="Recherche" size=11><br>';
echo '<input type=radio name=searchtype value=1 checked><img src="pics/tag_blue.png"> &nbsp; <input type=radio name=searchtype value=2';
if ($_SESSION['ds_customname'] == 'Pro Peinture') { echo ' checked'; }
echo '><img src="pics/vcard.png">';
if ($_SESSION['ds_menustyle'] == 1) { echo '<br> &nbsp; <button type="submit">Rechercher</button>'; }
echo '</fieldset></form>';
?>