<?php

echo '
<img src="barcode.php?size=40&text=' . $_POST['barcode'] . '" width=' . $_POST['width'] . '; height=' . $_POST['height'] . '>
<div width=600 style="text-align: center; max-width: ' . $_POST['width'] . 'px;">
<span style="font-size: ' . $_POST['fontsize'] . 'px; font-family: calibri; ">
' . d_output($_POST['barcode']) . '
</span>
</div>
';


?>