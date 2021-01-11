<?php

if ($_SESSION['ds_menustyle'] == 5)
{
  ?>
  <nav class="navigation">
    <div class="row row-center">

      <a class="logo-brand" href="index.php"><img src="pics/logo_ana.png" alt="TEM"></a>
             
      <ul class="column column-auto column-lg-auto navigation-list">
      <?php
      if (isset($_SESSION['ds_userid']))
      {
        if (isset($_SESSION['ds_userid']))
        {
          if ($_SESSION['ds_clientaccess'] == 1)
          {
            if ($dauphin_currentmenu == '') { echo '<li class="active">'; }
            else { echo '<li>'; }
            echo '<a href="clientaccess.php"><span>' . d_output(d_decode($_SESSION['ds_clientname'])) . '</span></a></li>';
          }

          if ($_SESSION['ds_clientaccess'] == 0 && $_SESSION['ds_salesaccess'] == 1)
          {
            if ($dauphin_currentmenu == 'sales') { echo '<li class="active">'; }
            else { echo '<li>'; }
            echo '<a href="sales.php"><span>Vente</span></a></li>';
          }
          
          if ($_SESSION['ds_clientaccess'] == 0 && $_SESSION['ds_usedelivery'] > 0 && $_SESSION['ds_deliveryaccess'] > 0)
          {
            if ($dauphin_currentmenu == 'delivery') { echo '<li class="active">'; }
            else { echo '<li>'; }
            echo '<a href="delivery.php"><span>Livraison</span></a></li>';
          }
          
          if ($_SESSION['ds_clientaccess'] == 0 && $_SESSION['ds_clientsaccess'] == 1)
          {
            if ($dauphin_currentmenu == 'clients') { echo '<li class="active">'; }
            else { echo '<li>'; }
            echo '<a href="clients.php"><span>Clients</span></a></li>';
          }
          
          if ($_SESSION['ds_clientaccess'] == 0 && $_SESSION['ds_usebyaccess'] == 1)
          {
            if ($dauphin_currentmenu == 'products') { echo '<li class="active">'; }
            else { echo '<li>'; }
            echo '<a href="products.php"><span>Produits</span></a></li>';
          }
          
          if ($_SESSION['ds_clientaccess'] == 0 && $_SESSION['ds_warehouseaccess'] == 1)
          {
            if ($dauphin_currentmenu == 'warehouse') { echo '<li class="active">'; }
            else { echo '<li>'; }
            echo '<a href="warehouse.php"><span>Entrepôt</span></a></li>';
          }
          
          if ($_SESSION['ds_clientaccess'] == 0 && $_SESSION['ds_purchaseaccess'] == 1)
          {
            if ($dauphin_currentmenu == 'purchase') { echo '<li class="active">'; }
            else { echo '<li>'; }
            echo '<a href="purchase.php"><span>Achat</span></a></li>';
          }
          
          if ($_SESSION['ds_clientaccess'] == 0 && $_SESSION['ds_accountingaccess'] == 1)
          {
            if ($dauphin_currentmenu == 'accounting') { echo '<li class="active">'; }
            else { echo '<li>'; }
            echo '<a href="accounting.php"><span>Compta</span></a></li>';
          }
          
          if ($_SESSION['ds_clientaccess'] == 0 && $_SESSION['ds_reportsaccess'] == 1)
          {
            if ($dauphin_currentmenu == 'reports') { echo '<li class="active">'; }
            else { echo '<li>'; }
            echo '<a href="reports.php"><span>Rapport</span></a></li>';
          }
                
          if ($_SESSION['ds_clientaccess'] == 0 && $_SESSION['ds_hraccess'] == 1)
          {
            if ($dauphin_currentmenu == 'hr') { echo '<li class="active">'; }
            else { echo '<li>'; }
            echo '<a href="hr.php"><span>RH</span></a></li>';
          }
          
          if ($_SESSION['ds_clientaccess'] == 0 && $_SESSION['ds_manage_qr'] == 1)
          {
            if ($dauphin_currentmenu == 'qr') { echo '<li class="active">'; }
            else { echo '<li>'; }
            echo '<a href="qr.php"><span>QR</span></a></li>';
          }
          
          if ($_SESSION['ds_clientaccess'] == 0 && $_SESSION['ds_adminaccess'] == 1)
          {
            if ($dauphin_currentmenu == 'admin') { echo '<li class="active">'; }
            else { echo '<li>'; }
            echo '<a href="admin.php"><span>Admin</span></a></li>';
          }
          
          if ($_SESSION['ds_clientaccess'] == 0 && $_SESSION['ds_customname'] != "" && $_SESSION['ds_userrepresentsclientid'] < 1)
          {
            $customfilename = 'custom/' . mb_strtolower($_SESSION['ds_customname']) . '.php';
            if (file_exists($customfilename))
            {
              if ($dauphin_currentmenu == 'custom') { echo '<li class="active">'; }
              else { echo '<li>'; }
              echo '<a href="custom.php"><span>' . d_output(str_replace(' ','&nbsp;',$_SESSION['ds_customname'])) . '</span></a></li>';
            }
          }
        }
      }
      ?>
      </ul>
      
      <ul class="user-nav column column-auto">     
        <li class="dropdown-toggle">
          <a href="#"><i class="fa fa-user-circle"></i> <?php echo d_output($_SESSION['ds_username']); ?></a>
          <span class="caret"></span>
          <ul class="dropdown">
            <?php
            if ($_SESSION['ds_clientaccess'] == 0 && $_SESSION['ds_systemaccess'] == 1)
            { ?><li><a href="system.php">Système</a></li><?php }
            if ($_SESSION['ds_optionsaccess'] == 1)
            { ?><li><a href="options.php">Options</a></li><?php }
            ?>
            <li class="separator"></li>
            <li><a href="logout.php"><i class="fa fa-power-off"></i> Déconnexion</a></li>
          </ul>
        </li>
        <li class="logo-brand">
        <?php
        $ourlogofile = './custom_available/' . mb_strtolower($_SESSION['ds_customname']) . '.jpg';
        if (file_exists($ourlogofile))
        { echo '<img alt="'.$_SESSION['ds_customname'].'" src="'.$ourlogofile.'">'; }
        else { echo '<b>' . d_output($_SESSION['ds_customname']) . '</b>'; }
        ?>
        </li>
      </ul>
     
    </div>
  </nav>
  <?php
}
elseif ($_SESSION['ds_menustyle'] > 1)
{
  ?><div id='cssmenu'><ul><?php
  if (isset($_SESSION['ds_userid']))
  {
    if ($_SESSION['ds_clientaccess'] == 1)
    {
      if ($dauphin_currentmenu == '') { echo '<li class="active">'; }
      else { echo '<li>'; }
      echo '<a href="clientaccess.php"><span>' . d_output(d_decode($_SESSION['ds_clientname'])) . '</span></a></li>';
    }

    if ($_SESSION['ds_clientaccess'] == 0 && $_SESSION['ds_salesaccess'] == 1)
    {
      if ($dauphin_currentmenu == 'sales') { echo '<li class="active">'; }
      else { echo '<li>'; }
      echo '<a href="sales.php"><span>Vente</span></a></li>';
    }
    
    if ($_SESSION['ds_clientaccess'] == 0 && $_SESSION['ds_usedelivery'] > 0 && $_SESSION['ds_deliveryaccess'] > 0)
    {
      if ($dauphin_currentmenu == 'delivery') { echo '<li class="active">'; }
      else { echo '<li>'; }
      echo '<a href="delivery.php"><span>Livraison</span></a></li>';
    }
    
    if ($_SESSION['ds_clientaccess'] == 0 && $_SESSION['ds_clientsaccess'] == 1)
    {
      if ($dauphin_currentmenu == 'clients') { echo '<li class="active">'; }
      else { echo '<li>'; }
      echo '<a href="clients.php"><span>Clients</span></a></li>';
    }
    
    if ($_SESSION['ds_clientaccess'] == 0 && $_SESSION['ds_usebyaccess'] == 1)
    {
      if ($dauphin_currentmenu == 'products') { echo '<li class="active">'; }
      else { echo '<li>'; }
      echo '<a href="products.php"><span>Produits</span></a></li>';
    }
    
    if ($_SESSION['ds_clientaccess'] == 0 && $_SESSION['ds_warehouseaccess'] == 1)
    {
      if ($dauphin_currentmenu == 'warehouse') { echo '<li class="active">'; }
      else { echo '<li>'; }
      echo '<a href="warehouse.php"><span>Entrepôt</span></a></li>';
    }
    
    if ($_SESSION['ds_clientaccess'] == 0 && $_SESSION['ds_purchaseaccess'] == 1)
    {
      if ($dauphin_currentmenu == 'purchase') { echo '<li class="active">'; }
      else { echo '<li>'; }
      echo '<a href="purchase.php"><span>Achat</span></a></li>';
    }
    
    if ($_SESSION['ds_clientaccess'] == 0 && $_SESSION['ds_accountingaccess'] == 1)
    {
      if ($dauphin_currentmenu == 'accounting') { echo '<li class="active">'; }
      else { echo '<li>'; }
      echo '<a href="accounting.php"><span>Compta</span></a></li>';
    }
    
    if ($_SESSION['ds_clientaccess'] == 0 && $_SESSION['ds_reportsaccess'] == 1)
    {
      if ($dauphin_currentmenu == 'reports') { echo '<li class="active">'; }
      else { echo '<li>'; }
      echo '<a href="reports.php"><span>Rapport</span></a></li>';
    }
          
    if ($_SESSION['ds_clientaccess'] == 0 && $_SESSION['ds_hraccess'] == 1)
    {
      if ($dauphin_currentmenu == 'hr') { echo '<li class="active">'; }
      else { echo '<li>'; }
      echo '<a href="hr.php"><span>RH</span></a></li>';
    }
    
    if ($_SESSION['ds_clientaccess'] == 0 && $_SESSION['ds_manage_qr'] == 1)
    {
      if ($dauphin_currentmenu == 'qr') { echo '<li class="active">'; }
      else { echo '<li>'; }
      echo '<a href="qr.php"><span>QR</span></a></li>';
    }
    
    if ($_SESSION['ds_clientaccess'] == 0 && $_SESSION['ds_adminaccess'] == 1)
    {
      if ($dauphin_currentmenu == 'admin') { echo '<li class="active">'; }
      else { echo '<li>'; }
      echo '<a href="admin.php"><span>Admin</span></a></li>';
    }
    
    if ($_SESSION['ds_clientaccess'] == 0 && $_SESSION['ds_systemaccess'] == 1)
    {
      if ($dauphin_currentmenu == 'system') { echo '<li class="active">'; }
      else { echo '<li>'; }
      echo '<a href="system.php"><span>Système</span></a></li>';
    }
    
    if ($_SESSION['ds_optionsaccess'] == 1)
    {
      if ($dauphin_currentmenu == 'options') { echo '<li class="active">'; }
      else { echo '<li>'; }
      echo '<a href="options.php"><span>Options</span></a></li>';
    }
    
    if ($_SESSION['ds_clientaccess'] == 0 && $_SESSION['ds_customname'] != "" && $_SESSION['ds_userrepresentsclientid'] < 1)
    {
      $customfilename = 'custom/' . mb_strtolower($_SESSION['ds_customname']) . '.php';
      if (file_exists($customfilename))
      {
        if ($dauphin_currentmenu == 'custom') { echo '<li class="active">'; }
        else { echo '<li>'; }
        echo '<a href="custom.php"><span>' . d_output(str_replace(' ','&nbsp;',$_SESSION['ds_customname'])) . '</span></a></li>';
      }
    }
    
    if ($_SESSION['ds_menustyle'] == 3)
    {
      if (isset($_SESSION['ds_userid']))
      {
        echo '<li><a href="logout.php"><span>Déconnexion</span></a></li>';
      }
    }

  }
  ?></ul></div><?php
}
elseif ($_SESSION['ds_menustyle'] == 1)
{
  ?><div class="center"><?php
  if (isset($_SESSION['ds_userid']))
  {
    if ($_SESSION['ds_clientaccess'] == 1)
    {
      echo '<div id="menu-button-2" class="menu-button" style="width:60%;">' . d_output(d_decode($_SESSION['ds_clientname'])) . '</div>';
    }

    if ($_SESSION['ds_clientaccess'] == 0 && $_SESSION['ds_salesaccess'] == 1)
    {
    echo '<A class="button" href="sales.php"><div id="menu-button-1" class="menu-button';
    if ($dauphin_currentmenu == 'sales') { echo '-current'; }
    echo '">Vente</div></A>';
    }

    if ($_SESSION['ds_clientaccess'] == 0 && $_SESSION['ds_usedelivery'] > 0 && $_SESSION['ds_deliveryaccess'] > 0)
    {
    echo '<A class="button" href="delivery.php"><div id="menu-button-1" class="menu-button';
    if ($dauphin_currentmenu == 'delivery') { echo '-current'; }
    echo '">Livraison</div></A>';
    }

    if ($_SESSION['ds_clientaccess'] == 0 && $_SESSION['ds_clientsaccess'] == 1)
    {
    echo '<A class="button" href="clients.php"><div id="menu-button-1" class="menu-button';
    if ($dauphin_currentmenu == 'clients') { echo '-current'; }
    echo '">Clients</div></A>';
    }

    if ($_SESSION['ds_clientaccess'] == 0 && $_SESSION['ds_usebyaccess'] == 1)
    {
    echo '<A class="button" href="products.php"><div id="menu-button-1" class="menu-button';
    if ($dauphin_currentmenu == 'products') { echo '-current'; }
    echo '">Produits</div></A>';
    }

    if ($_SESSION['ds_clientaccess'] == 0 && $_SESSION['ds_warehouseaccess'] == 1)
    {
    echo '<A class="button" href="warehouse.php"><div id="menu-button-1" class="menu-button';
    if ($dauphin_currentmenu == 'warehouse') { echo '-current'; }
    echo '">Entrepôt</div></A>';
    }

    if ($_SESSION['ds_clientaccess'] == 0 && $_SESSION['ds_purchaseaccess'] == 1)
    {
    echo '<A class="button" href="purchase.php"><div id="menu-button-1" class="menu-button';
    if ($dauphin_currentmenu == 'purchase') { echo '-current'; }
    echo '">Achat</div></A>';
    }

    if ($_SESSION['ds_clientaccess'] == 0 && $_SESSION['ds_accountingaccess'] == 1)
    {
    echo '<A class="button" href="accounting.php"><div id="menu-button-1" class="menu-button';
    if ($dauphin_currentmenu == 'accounting') { echo '-current'; }
    echo '">Compta</div></A>';
    }

    if ($_SESSION['ds_clientaccess'] == 0 && $_SESSION['ds_reportsaccess'] == 1)
    {
    echo '<A class="button" href="reports.php"><div id="menu-button-1" class="menu-button';
    if ($dauphin_currentmenu == 'reports') { echo '-current'; }
    echo '">Rapports</div></A>';
    }
          
    if ($_SESSION['ds_clientaccess'] == 0 && $_SESSION['ds_hraccess'] == 1)
    {
      echo '<A class="button" href="hr.php"><div id="menu-button-1" class="menu-button';
      if ($dauphin_currentmenu == 'hr') { echo '-current'; }
      echo '">RH</div></A>';
    }
    
    if ($_SESSION['ds_clientaccess'] == 0 && $_SESSION['ds_manage_qr'] == 1)
    {
      echo '<A class="button" href="hr.php"><div id="menu-button-1" class="menu-button';
      if ($dauphin_currentmenu == 'qr') { echo '-current'; }
      echo '">QR</div></A>';
    }

    if ($_SESSION['ds_clientaccess'] == 0 && $_SESSION['ds_adminaccess'] == 1)
    {
    echo '<A class="button" href="admin.php"><div id="menu-button-1" class="menu-button';
    if ($dauphin_currentmenu == 'admin') { echo '-current'; }
    echo '">Admin</div></A>';
    }

    if ($_SESSION['ds_clientaccess'] == 0 && $_SESSION['ds_systemaccess'] == 1)
    {
    echo '<A class="button" href="system.php"><div id="menu-button-1" class="menu-button';
    if ($dauphin_currentmenu == 'system') { echo '-current'; }
    echo '">Système</div></A>';
    }

    if ($_SESSION['ds_optionsaccess'] == 1)
    {
    echo '<A class="button" href="options.php"><div id="menu-button-1" class="menu-button';
    if ($dauphin_currentmenu == 'options') { echo '-current'; }
    echo '">Options</div></A>';
    }

    if ($_SESSION['ds_clientaccess'] == 0 && $_SESSION['ds_customname'] != "" && $_SESSION['ds_userrepresentsclientid'] < 1)
    {
      $customfilename = 'custom/' . mb_strtolower($_SESSION['ds_customname']) . '.php';
      if (file_exists($customfilename))
      {
        echo '<A class="button" href="custom.php"><div id="menu-button-1" class="menu-button';
        if ($dauphin_currentmenu == 'custom') { echo '-current'; }
        echo '">' . str_replace(' ','&nbsp;',$_SESSION['ds_customname']) . '</div></A>';
      }
    }

  }

  ?></div><br><?php
}
  
?>