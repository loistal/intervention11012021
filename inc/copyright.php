<?php
if (!isset($_SESSION['ds_userid']) || isset($_SESSION['ds_menustyle']) && $_SESSION['ds_menustyle'] == 5)
{
  ?><footer>
  <p class="text-center"><a href="http://temtahiti.com" target=_blank>© 2007-2020 Tahiti Enterprise Management</a></p>
  </footer><?php
}
else
{ 
  ?><div class="copyright">
  <font size=1><a href="http://temtahiti.com" target=_blank>© 2007-2020 Tahiti Enterprise Management</a></font>
  </div><?php
}
?>