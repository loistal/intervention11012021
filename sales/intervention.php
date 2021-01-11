<style>
    td { padding: 5px; }
    .removeLink { cursor: pointer; }
</style>

<script>
    /**
     * Removes the data of the intervention line at @index 
     */
    function removeData(index) 
    {
        // Remove the product text description
        var productText = $('#producttd' + index).first().contents().filter(function() {
            return this.nodeType == 3; // Only return text 
        })
        productText.remove();
        
        $('input[name="product' + (index + 1) + '"]').val("");
        $('select[name="employee' + index + 'id"]').prop('selectedIndex',0);

        $('input[name="line' + index + 'starttime"]').val("");
        $('input[name="line' + index + 'endtime"]').val("");

        $('input[name="line' + index + 'field1"]').val("");
        $('input[name="line' + index + 'field2"]').val("");
        $('input[name="line' + index + 'field3"]').val("");
        $('input[name="line' + index + 'field4"]').val("");

        $('select[name="interventionitemtag1line' + index + 'id"]').prop('selectedIndex',0);
        $('select[name="interventionitemtag2line' + index + 'id"]').prop('selectedIndex',0);

        $('input[name="line' + index + 'value1"]').val("");
        $('input[name="line' + index + 'value2"]').val("");
        $('input[name="line' + index + 'value3"]').val("");
        $('input[name="line' + index + 'value4"]').val("");

        $('input[name="line' + index + 'description"]').val("");
    }
</script>

<?php 
    /**
     * Creates the names of all the variables in intervention lines
     *
     * @param int $i The index of the intervention line
     * @return array The names of all the variables in the line
     **/
    function build_variables($i)
    {
        $employee_var = 'employee' . $i . 'id';
        $product_var = 'product' . ($i + 1);
        $starttime_var = 'line' . $i . 'starttime';
        $endtimetime_var = 'line' . $i . 'endtime';
        $field1_var = 'line' . $i . 'field1';
        $field2_var = 'line' . $i . 'field2';
        $field3_var = 'line' . $i . 'field3';
        $field4_var = 'line' . $i . 'field4';
        $tag1_var = 'interventionitemtag1line' . $i . 'id';
        $tag2_var = 'interventionitemtag2line' . $i . 'id';
        $value1_var = 'line' . $i . 'value1';
        $value2_var = 'line' . $i . 'value2';
        $value3_var = 'line' . $i . 'value3';
        $value4_var = 'line' . $i . 'value4';
        $description_var = 'line' . $i . 'description';
        $intervention_item_id_var = 'line' . $i . 'interventionitemid';

        return array(
            $employee_var,
            $product_var,
            $starttime_var,
            $endtimetime_var,
            $field1_var,
            $field2_var,
            $field3_var,
            $field4_var,
            $tag1_var,
            $tag2_var,
            $value1_var,
            $value2_var,
            $value3_var,
            $value4_var,
            $description_var,
            $intervention_item_id_var
        );
    }

    $PA['has_selected_action'] = 'uint';
    $PA['interventionid'] = 'uint';
    $PA['filledmainform'] = 'uint';
    $PA['employeemainid'] = 'uint';
    $PA['client'] = 'client';
    $PA['interventiondate'] = 'date';
    $PA['interventioncomment'] = '';
    $PA['interventiontitle'] = '';
    require('inc/readpost.php');

    # Are we about to fill the form to create a new intervention?
    $is_creating = (!$interventionid && !$filledmainform && $has_selected_action);

    # Have we filled the form and clicked "Validate" to UPDATE an intervention ?
    $is_updating = ($interventionid !== 0 && $filledmainform);

    # Have we filled the form and clicked "Validate" to CREATE a new intervention ?
    $is_saving = (!$interventionid && $filledmainform);

    # Are we about to fill the form to modify an existing intervention? 
    $is_modifying = ($interventionid !== 0 && !$filledmainform);

    # Are we trying to modify an intervention that does not exist?
    $is_modifying_incorrect_id = 0;
    if($is_modifying)
    {
        $query = 'select * from intervention where interventionid=?';
        $query_prm = array($interventionid);
        require('inc/doquery.php');
        if($num_results === 0) { 
            $is_modifying_incorrect_id = 1; 
            $is_modifying = 0;
        }
    }

    if($is_creating) { $numberoflines = 1; } 
    elseif($is_saving) { $numberoflines = 2; } 
    elseif($is_updating || $is_modifying) 
    { 
        $query = 'select * from intervention where interventionid=?';
        $query_prm = array($interventionid);
        require('inc/doquery.php');
        
        $query = 'select * from interventionitem where interventionid=?';
        $query_prm = array($interventionid);
        require('inc/doquery.php');

        $numberoflines = $num_results + 1; # Also include a new line
    }

    if($is_saving || $is_updating)
    {
        for($i = 0; $i < $numberoflines; $i++)
        {
            list($employee_var, $product_var, $starttime_var, $endtimetime_var, 
                $field1_var, $field2_var, $field3_var, $field4_var, $tag1_var, $tag2_var,
                $value1_var, $value2_var, $value3_var, $value4_var, $description_var,
                $intervention_item_id_var) = build_variables($i);

            $PA[$employee_var] = 'uint';
            $PA[$product_var] = 'uint';
            $PA[$starttime_var] = '';
            $PA[$endtimetime_var] = '';
            $PA[$field1_var] = '';
            $PA[$field2_var] = '';
            $PA[$field3_var] = '';
            $PA[$field4_var] = '';
            $PA[$tag1_var] = 'uint';
            $PA[$tag2_var] = 'uint';
            $PA[$value1_var] = 'decimal';
            $PA[$value2_var] = 'decimal';
            $PA[$value3_var] = 'decimal';
            $PA[$value4_var] = 'decimal';
            $PA[$description_var] = '';
            $PA[$intervention_item_id_var] = 'uint';
        }

        require('inc/readpost.php');
    }

    if($is_modifying)
    {
        $query = 'select * from intervention where interventionid=?';
        $query_prm = array($interventionid);
        require('inc/doquery.php');

        $interventionid = $query_result[0]['interventionid'];
        $employeemainid = $query_result[0]['interventionemployeeid'];
        $clientid = $query_result[0]['interventionclientid'];
        $interventiondate = $query_result[0]['interventiondate'];
        $interventioncomment = $query_result[0]['interventioncomment'];
        $interventiontitle = $query_result[0]['interventiontitle'];
        
        # Get the name of the client
        if($clientid != 0) 
        {
            $query = 'select clientname from client where clientid=?';
            $query_prm = array($clientid);
            require('inc/doquery.php');
            
            if($num_results === 0)
            {
                echo '<p class="alert">Nous n\'avons pas trouvé le nom du client numéro ' . $clientid . '</p>'; 
            }

            $client = $query_result[0]['clientname'];
        }
        
    }
    elseif($is_updating) 
    {
        # $client can be the id, or the the name of the client. Make sure we have the id.
        $is_client_number = preg_match("/^\d+$/", $client);
        if (!$is_client_number && $client != '') 
        {
            $query = 'select clientid from client where clientname=?';
            $query_prm = array($client);
            require('inc/doquery.php');

            if($num_results === 1)
            {
                $client = $query_result[0]['clientid'];
            }
            else { echo '<p class="alert">Le client n\'a pas été sauvegardé</p>'; }
        }

        $query = 'update intervention set 
                  interventionemployeeid=?, 
                  interventiontitle=?, 
                  interventionclientid=?, 
                  interventiondate=?, 
                  interventioncomment=? 
                  where interventionid=?';
        $query_prm = array($employeemainid, 
                           $interventiontitle, 
                           $client, 
                           $interventiondate,
                           $interventioncomment, 
                           $interventionid);
        require('inc/doquery.php'); 

        for($i = 0; $i < $numberoflines; $i++) 
        {
            $line_var_names = list(
                $employee_var, 
                $product_var, 
                $starttime_var, 
                $endtimetime_var,
                $field1_var, 
                $field2_var, 
                $field3_var, 
                $field4_var, 
                $tag1_var, 
                $tag2_var,
                $value1_var, 
                $value2_var, 
                $value3_var, 
                $value4_var, 
                $description_var,
                $intervention_item_id_var) = build_variables($i);

            $is_last_line = ($i === ($numberoflines - 1));
            if($is_last_line)
            {
                # If the user hasn't filled out any field, don't insert the line
                $is_empty = 1;
                foreach ($line_var_names as $var_name) 
                {
                    if(!strpos($var_name, 'interventionitemid') 
                        && $$var_name != '' 
                        && $$var_name != '0')
                    {
                        $is_empty = 0;
                    }
                }

                if(!$is_empty)
                {
                    $query = 'insert into interventionitem (employeeid, productid, timestart, 
                    timeend, interventiontagid,interventiontagid2, field1, field2, field3, field4, 
                    value1, value2,value3, value4, description, interventionid) 
                    values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
                    $query_prm = array($$employee_var, $$product_var, $$starttime_var, 
                    $$endtimetime_var, $$tag1_var, $$tag2_var, $$field1_var, $$field2_var, 
                    $$field3_var, $$field4_var, $$value1_var, $$value2_var, $$value3_var, 
                    $$value4_var, $$description_var, $interventionid);
                    require('inc/doquery.php');
                }
            }
            else 
            {
                $is_empty = 1;
                foreach ($line_var_names as $var_name) 
                {
                    if (!strpos($var_name, 'interventionitemid') 
                        && $$var_name != '' 
                        && $$var_name != '0') { $is_empty = 0; }
                }

                if($is_empty)
                {
                    $query = 'delete from interventionitem where interventionitemid=?';
                    $query_prm = array($$intervention_item_id_var);
                    require('inc/doquery.php');

                    if ($num_results === 0) 
                    {
                        echo '<p class=alert">La ligne ' . $$intervention_item_id_var 
                        . 'n\'a pas été effacée.</p>';
                    }
                }
                else 
                {
                    $query = 'update interventionitem set employeeid=?, productid=?, timestart=?, 
                    timeend=?, interventiontagid=?,interventiontagid2=?, field1=?, field2=?, 
                    field3=?, field4=?, value1=?, value2=?, value3=?, value4=?, description=?, 
                    interventionid=? where interventionitemid=?';
                    $query_prm = array($$employee_var, $$product_var, $$starttime_var, 
                    $$endtimetime_var, $$tag1_var, $$tag2_var, $$field1_var, $$field2_var, 
                    $$field3_var, $$field4_var, $$value1_var, $$value2_var, $$value3_var, 
                    $$value4_var, $$description_var, $interventionid, $$intervention_item_id_var);
                    require('inc/doquery.php');
                }
            }
        }

        echo '<p>Intervention mise à jour.</p>';
    }
    elseif ($is_saving) 
    {
        $query = 'insert into intervention (interventiontitle, interventionemployeeid, 
        interventionclientid, interventioncomment, interventiondate) values (?, ?, ?, ?, ?)';
        $query_prm = array($interventiontitle, $employeemainid, $clientid, $interventioncomment, 
        $interventiondate);
        require('inc/doquery.php');
        $interventionid = $query_insert_id;

        if($num_results === 0) 
        {
            echo '<p class="alert">L\'intervention n\'a pas été créée.';
        }
        else 
        {
            $line_var_names = list($employee_var, $product_var, $starttime_var, $endtimetime_var, 
            $field1_var, $field2_var, $field3_var, $field4_var, $tag1_var, $tag2_var, $value1_var, 
            $value2_var, $value3_var, $value4_var, $description_var,$intervention_item_id_var) 
            = build_variables(0);
            
            $is_empty = 1;
            foreach ($line_var_names as $var_name) 
            {
                if (!strpos($var_name, 'interventionitemid') 
                    && $$var_name != '' 
                    && $$var_name != '0') { $is_empty = 0; }
            }

            if(!$is_empty)
            {
                $query = 'insert into interventionitem (employeeid, productid, timestart, timeend, 
                interventiontagid,interventiontagid2, field1, field2, field3, field4, value1, 
                value2,value3, value4, description, interventionid) 
                values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
                $query_prm = array($$employee_var, $$product_var, $$starttime_var, 
                $$endtimetime_var, $$tag1_var, $$tag2_var, $$field1_var, $$field2_var, $$field3_var,
                $$field4_var, $$value1_var, $$value2_var, $$value3_var, $$value4_var, 
                $$description_var, $interventionid);
                require('inc/doquery.php');

                if($num_results === 0)
                {
                    echo '<p class="alert">L\'intervention a été créée, mais la ligne 
                    d\'intervention n\'a pas été créée.</p>';
                }
                else { echo '<p>L\'intervention et sa ligne d\'intervention ont été créées.</p>'; }
            }
            else 
            {
                echo '<p>L\'intervention ' . $interventionid . ' a été créée.</p>';
            }
        }
    }
?>

<?php 
    if ($has_selected_action && $is_modifying_incorrect_id)  
    { 
        echo '<p class="alert">L\'intervention ' . $interventionid . ' n\'existe pas.</p>'; 
    }
?>

<?php if(!$has_selected_action || $is_modifying_incorrect_id) { ?>
    <h2>Ajouter / modifier une intervention</h2>
    <form method="post" action="sales.php">
        <table>
            <tbody>
                <tr>
                    <td>Numéro: </td>
                    <td>
                        <input style="width: 250px;" placeholder="Laissez vide pour ajouter" 
                        type="number" name="interventionid" min="1">
                    </td>
                </tr>
            </tbody>
        </table>
        <br>
        <div class="center">
            <input type="hidden" name="salesmenu" value="intervention">
            <input type="hidden" name="has_selected_action" value="1">
            <input type="hidden" name="filledmainform" value="0">
            <input name="save" type="submit" value="Valider">
        </div>
    </form>
    <br>
    <br>
    <h2>Rapport d'interventions:</h2>
    <form method="post" action="reportwindow.php" target="_blank">
        <input type="hidden" name="report" value="interventionreport">
        <table>
            <tbody>
                <tr>
                    <td>Date de début: </td>
                    <td>
                        <input type="date" name="startdate">
                    </td>
                </tr>
                <tr>
                    <td>Date de fin: </td>
                    <td>
                        <input type="date" name="enddate">
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php 
                            require('inc/selectclient.php'); 
                        ?>
                    </td>
                </tr>
                <tr>
                    <?php
                        $dp_description = 'Employé';
                        $dp_itemname = 'employee';
                        $dp_addtoid = 'main'; # Why doesn't it work without this? 
                        if ($employeemainid !== 0) { $dp_selectedid = $employeemainid; }
                        require('inc/selectitem.php');
                    ?>
                </tr>
                <tr>
                    <td>Titre</td>
                    <td>
                        <input type="text" name="title">
                    </td>
                </tr>
                <tr>
                    <td>Commentaire</td>
                    <td>
                        <input type="text" name="comment">
                    </td>
                </tr>
            </tbody>
        </table>
        <br>
        <div class="center">
            <input type="hidden" name="salesmenu" value="intervention">
            <input name="save" type="submit" value="Valider">
        </div>
    </form>

    <br>
    <?php 
        # Report configuration
        # require('reportwindow/interventionreport_cf.php');
        # require('inc/configreport.php');
    ?>
<?php } ?>

<?php if($has_selected_action && !$is_modifying_incorrect_id) { ?>
    <?php
        if($is_creating) { echo '<h2>Ajouter une intervention</h2>'; }
        else { echo '<h2>Modifier l\'intervention ' . $interventionid . '</h2>'; }
    ?>
    <form method="post" action="sales.php">
        <table>
            <tr>
                <td>
                    <?php 
                        require('inc/selectclient.php'); 
                    ?>
                </td>
            </tr>
            <tr>
                <td>Employé: </td>
                <?php
                    $dp_itemname = 'employee';
                    $dp_addtoid = 'main'; # Why doesn't it work without this? 
                    if ($employeemainid !== 0) { $dp_selectedid = $employeemainid; }
                    require('inc/selectitem.php');
                ?>
            </tr>
            <tr>
                <td>
                    Date:
                </td>
                <td>
                    <?php 
                        if($interventiondate !== '') { $selecteddate = $interventiondate; }
                        $datename = 'interventiondate';
                        $dp_datepicker_min = '2000-01-01'; 
                        require('inc/datepicker.php');
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    Titre:
                </td>
                <td>
                    <input name="interventiontitle" type="text" 
                    value="<?php echo $interventiontitle ?>">
                </td>
            </tr>
            <tr>
                <td>
                    Commentaire:
                </td>
                <td>
                    <input type="text" name="interventioncomment" size="80" 
                    value="<?php echo $interventioncomment ?>">
                </td>
            </tr>
        </table>
        <br>
        <table class="detailinput">
            <thead>
                <tr>
                    <td>Ligne</td>
                    <td>Produit</td>
                    <td>Employé</td>
                    <td>Heure début</td>
                    <td>Heure fin</td>
                    <?php 
                        if($_SESSION['ds_term_interventionfield1'] !== '') 
                        { 
                            echo '<td>' . $_SESSION['ds_term_interventionfield1'] . '</td>';
                        } 
                        if($_SESSION['ds_term_interventionfield2'] !== '') 
                        { 
                            echo '<td>' . $_SESSION['ds_term_interventionfield2'] . '</td>';
                        } 
                        if($_SESSION['ds_term_interventionfield3'] !== '') 
                        { 
                            echo '<td>' . $_SESSION['ds_term_interventionfield3'] . '</td>';
                        } 
                        if($_SESSION['ds_term_interventionfield4'] !== '') 
                        { 
                            echo '<td>' . $_SESSION['ds_term_interventionfield4'] . '</td>';
                        } 
                        if($_SESSION['ds_term_intervention_tag1'] !== '') 
                        { 
                            echo '<td>' . $_SESSION['ds_term_intervention_tag1'] . '</td>';
                        } 
                        if($_SESSION['ds_term_intervention_tag2'] !== '') 
                        { 
                            echo '<td>' . $_SESSION['ds_term_intervention_tag2'] . '</td>';
                        } 
                        if($_SESSION['ds_term_intervention_value1'] !== '') 
                        { 
                            echo '<td>' . $_SESSION['ds_term_intervention_value1'] . '</td>';
                        } 
                        if($_SESSION['ds_term_intervention_value2'] !== '') 
                        { 
                            echo '<td>' . $_SESSION['ds_term_intervention_value2'] . '</td>';
                        } 
                        if($_SESSION['ds_term_intervention_value3'] !== '') 
                        { 
                            echo '<td>' . $_SESSION['ds_term_intervention_value3'] . '</td>';
                        } 
                        if($_SESSION['ds_term_intervention_value4'] !== '') 
                        { 
                            echo '<td>' . $_SESSION['ds_term_intervention_value4'] . '</td>';
                        } 
                    ?>
                    <td>Effacer ligne</td>
                </tr>
            </thead>
            <tbody>
                <?php 
                    if(!$is_creating) 
                    {
                        $query = 'select * from interventionitem where interventionid=?';
                        $query_prm = array($interventionid);
                        require('inc/doquery.php');
                        if($num_results > 0) { $intervention_lines = $query_result; }
                    }

                    # Create an empty line that will be displayed after the actual lines
                    $empty_line = array(
                        'interventionitemid' => 0,
                        'employeeid' => 0,
                        'productid' => 0,
                        'timestart' => '',
                        'timeend' => '',
                        'description' => '',
                        'interventiontagid' => 0, 
                        'interventiontagid2' => 0, 
                        'field1' => '', 
                        'field2' => '', 
                        'field3' => '', 
                        'field4' => '', 
                        'value1' => 0, 
                        'value2' => 0, 
                        'value3' => 0, 
                        'value4' => 0
                    ); 

                    if(!isset($intervention_lines)) { $intervention_lines[0] = $empty_line; } 
                    else { array_push($intervention_lines, $empty_line); }

                    $number_lines = count($intervention_lines);
                    for($i = 0; $i < $number_lines; $i++)
                    {
                ?>
                    <input type="hidden" name="line<?php echo $i ?>interventionitemid" 
                    value="<?php echo $intervention_lines[$i]['interventionitemid'] ?>">
                    <tr>
                        <td>
                            <?php echo ($i + 1) # Line number ?> 
                        </td>
                        <td id="producttd<?php echo $i ?>">
                            <?php
                                $product = $intervention_lines[$i]['productid'];
                                if($product == 0) { unset($product); }
                                
                                # add 1 because fp_counter == 0 causes issues in selectproduct.php
                                $fp_counter = $i + 1; 
                                require ('inc/selectproduct.php'); 
                                
                            ?>
                        </td>
                        <?php
                            $dp_itemname = 'employee'; 
                            $dp_addtoid = $i;
                            $dp_selectedid = $intervention_lines[$i]['employeeid'];
                            require('inc/selectitem.php');
                        ?>
                        <td>
                            <?php 
                                $name = 'line' . $i . 'starttime';
                                $time_start = $intervention_lines[$i]['timestart'];
                            ?>
                            <input name="<?php echo $name ?>" type="time" 
                            value="<?php echo $time_start ?>">
                        </td>
                        <td>
                            <?php 
                                $name = 'line' . $i . 'endtime';
                                $time_end = $intervention_lines[$i]['timeend'];
                            ?>
                            <input name="<?php echo $name ?>" type="time" 
                            value="<?php echo $time_end ?>">
                        </td>
                        <?php 
                            if($_SESSION['ds_term_interventionfield1'] !== '') 
                            { 
                                $name = 'line' . $i . 'field1';
                                echo '<td><input name="' . $name . '" type="text" 
                                value="' . $intervention_lines[$i]['field1'] . '"></td>';
                            } 
                            if($_SESSION['ds_term_interventionfield2'] !== '') 
                            { 
                                $name = 'line' . $i . 'field2';
                                echo '<td><input name="' . $name . '" type="text" 
                                value="' . $intervention_lines[$i]['field2'] . '"></td>';
                            } 
                            if($_SESSION['ds_term_interventionfield3'] !== '') 
                            { 
                                $name = 'line' . $i . 'field3';
                                echo '<td><input name="' . $name . '" type="text" 
                                value="' . $intervention_lines[$i]['field3'] . '"></td>';
                            } 
                            if($_SESSION['ds_term_interventionfield4'] !== '') 
                            { 
                                $name = 'line' . $i . 'field4';
                                echo '<td><input name="' . $name . '" type="text" 
                                value="' . $intervention_lines[$i]['field4'] . '"></td>';;
                            } 
                            if($_SESSION['ds_term_intervention_tag1'] !== '') 
                            { 
                                $dp_itemname = 'interventionitemtag1';
                                $dp_addtoid = 'line' . $i;
                                $dp_selectedid = $intervention_lines[$i]['interventiontagid'];
                                require('inc/selectitem.php');
                            } 
                            if($_SESSION['ds_term_intervention_tag2'] !== '') 
                            { 
                                $dp_itemname = 'interventionitemtag2';
                                $dp_addtoid = 'line' . $i;
                                $dp_selectedid = $intervention_lines[$i]['interventiontagid2'];
                                require('inc/selectitem.php');
                            } 
                            if($_SESSION['ds_term_intervention_value1'] !== '') 
                            { 
                                $name = 'line' . $i . 'value1';
                                echo '<td><input name="' . $name . '" type="number" step="1" 
                                value="' . $intervention_lines[$i]['value1'] . '"></td>';
                            } 
                            if($_SESSION['ds_term_intervention_value2'] !== '') 
                            { 
                                $name = 'line' . $i . 'value2';
                                echo '<td><input name="' . $name . '" type="number" step="1" 
                                value="' . $intervention_lines[$i]['value2'] . '"></td>';
                            } 
                            if($_SESSION['ds_term_intervention_value3'] !== '') 
                            { 
                                $name = 'line' . $i . 'value3';
                                echo '<td><input name="' . $name . '" type="number" step="1" 
                                value="' . $intervention_lines[$i]['value3'] . '"></td>';
                            } 
                            if($_SESSION['ds_term_intervention_value4'] !== '') 
                            { 
                                $name = 'line' . $i . 'value4';
                                echo '<td><input name="' . $name . '" type="number" step="1" 
                                value="' . $intervention_lines[$i]['value4'] . '"></td>';
                            } 
                        ?>
                        <td>
                            <a class="removeLink" onclick="removeData(<?php echo $i ?>)">Effacer</a>
                        </td>
                    </tr>
                    <tr>
                        <td>Description:</td>
                        <td colspan="15">
                            <?php $name = 'line' . $i . 'description'; ?>
                            <input rows="5" type="text" name="<?php echo $name ?>" size="80" 
                            value="<?php echo $intervention_lines[$i]['description'] ?>">
                        </td>
                    </tr>
                <?php } ?>

            </tbody>
        </table>
        <br>
        <div class="center">
            <input type="hidden" name="salesmenu" value="intervention">
            <input type="hidden" name="interventionid" value="<?php echo $interventionid ?>">
            <input type="hidden" name="filledmainform" value="1">
            <input type="hidden" name="has_selected_action" value="1">
            <input name="save" type="submit" value="Valider">
        </div>
    </form>
<?php } ?> 