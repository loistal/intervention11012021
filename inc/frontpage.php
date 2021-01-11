<!DOCTYPE html>
<html lang="fr">
<head>
  <meta http-equiv=content-type content="text/html; charset=UTF-8">
  <link rel="icon" href="pics/temico.png" type="image/png">
  <title>TEM</title>
  <link href="style5.css" rel="stylesheet" />
</head>

<body class="bg-light">

  <main role="main" class="nl-main">
    <div class="container">
      <div class="row content-center">
        <div class="column column-md-50 column-lg-40 ">

          <div class="text-center">
            <img src="pics/logo.png" class="mb-2" style="height: 80px;" />
          </div>
          
          <div class="box">
          
            <form method="post" action="index.php">
              <fieldset>
                <label autofocus for="">Nom :</label>
                <input autofocus type="text" autocomplete="off" name="username">

                <label for="">Mot de passe :</label>
                <input autocomplete="off" type="password" name="password">

                <?php
                if (isset($enterprisename))
                {
                  echo '<label for="">Entreprise :</label>';
                  if (isset($enterprisename[2]))
                  {
                    d_sortarray($enterprisename);
                    if(isset($_COOKIE['instancecounter'])) { $lastinstancecounter = $_COOKIE['instancecounter']; }
                    else { $lastinstancecounter = 0; }
                    echo '<select name="instancecounter">';
                    $counter = 0;
                    foreach ($enterprisename as $counter => $name)
                    {
                      echo '<option value="' . $counter . '"';
                      if ($counter == $lastinstancecounter) { echo ' selected'; }
                      echo '>' . $name . '</option>';
                    }
                    echo '</select>';
                  }
                  else
                  {
                    echo '<label for="">' . $enterprisename[1] . '</label>';
                    echo '<input type=hidden name="instancecounter" value=1>';
                  }
                }
                ?>
                <br><br>
                <div class="float-right">
                  <label class="label-inline"><a href="mailto:contact@temtahiti.com">Mot de passe oublié ?</a></label>
                </div>

                <input class="button-primary" type="submit" value="Connexion">
              </fieldset>
            </form>
          </div>
        </div>
      </div>
      <?php
      if (count($enterprisename) == 1)
      {
        $query = 'select publicpage from globalvariables where primaryunique=1';
        $query_prm = array();
        require ('inc/doquery.php');
        if ($query_result[0]['publicpage'] != "")
        {
          echo '<br><div class="myblock" style="width:90%;margin:auto;">';
          echo d_output($query_result[0]['publicpage'], TRUE);
          echo '</div><br><br>';
        }
      }
      ?>
    </div>
  </main>
  <?php require('inc/copyright.php'); ?>
</body>
</html>