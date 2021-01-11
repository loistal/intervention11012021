<?php 
    /**
     * Intervention report with info about the intervention and its lines.
     */

    if (isset($_GET['intervention'])) {
        $intervention_id = $_GET['intervention'];
    }
    else 
    {
        echo '<p>Veuillez fournir un numéro d\'intervention.</p>';
        exit();
    }
    if($intervention_id === '')
    {
        echo '<p>Veuillez fournir un numéro d\'intervention.</p>';
        exit();
    }

    $query = 'select * from intervention where interventionid=?';
    $query_prm = array($intervention_id);
    require('inc/doquery.php');

    if($num_results === 0)
    {
        echo '<p>Pas de résultats pour l\'intervention numéro ' . $intervention_id;
        exit();
    }
    else 
    {
        $my_intervention = $query_result[0];
    }
?>

<?php 
    $full_title = 'Intervention ' . $my_intervention['interventionid'];
    if($my_intervention['interventiondate'] !== '0000-00-00')
    {
        $full_title .= ' - ' . datefix($my_intervention['interventiondate']);
    }
?>
<h2><?php echo $full_title ?></h2>
<div style="max-width: 50%;">
    <?php echo d_table('report') ?>
    <tr>
        <td><strong>Client</strong></td>
        <?php 
            $query = 'select clientname from client where clientid=?';
            $query_prm = array($my_intervention['interventionclientid']);
            require('inc/doquery.php');

            if($num_results === 1) { $clientname = $query_result[0]['clientname']; }
            else { $clientname = ''; }

            if($my_intervention['interventionclientid'] !== '0') 
            {
                $show_client_id = '(' . $my_intervention['interventionclientid'] . ')';
            }
            else { $show_client_id = ''; }
        ?>
        <td><?php echo $clientname . ' ' . $show_client_id ?></td>
    </tr>
    <tr>
        <td><strong>Employé</strong></td>
        <?php 
            $query = 'select employeename, employeefirstname from employee where employeeid=?';
            $query_prm = array($my_intervention['interventionemployeeid']);
            require('inc/doquery.php');

            if($num_results === 1) 
            { 
                $fullname = $query_result[0]['employeename'] . ', ' . $query_result[0]['employeefirstname'];
            }
            else { $fullname = ''; }
        ?>
        <td><?php echo $fullname ?></td>
    </tr>
    <tr>
        <td><strong>Date</strong></td>
        <td><?php echo datefix($my_intervention['interventiondate']) ?></td>
    </tr>
    <tr>
        <td><strong>Titre</strong></td>
        <td><?php echo $my_intervention['interventiontitle'] ?></td>
    </tr>
    <tr>
        <td><strong>Commentaire</strong></td>
        <td><?php echo $my_intervention['interventioncomment'] ?></td>
    </tr>
    <tr>
        <td><strong>Nombre de lignes</strong></td>
        <?php 
            $query = 'select * from interventionitem where interventionid=?';
            $query_prm = array($intervention_id);
            require('inc/doquery.php');
            $intervention_lines = $query_result;

            if($num_results === 0) { $num_lines = 0; }
            else { $num_lines = $num_results; }
        ?>
        <td><?php echo $num_lines ?></td>
    </tr>
    <tr>
        <td><strong>Total heures</strong></td>
        <?php 
            $total_hours = 0;
            $total_minutes = 0;

            $total_elapsed = new DateInterval('PT0H');
            for($i = 0; $i < $num_lines; $i++)
            {
                $time_start = $intervention_lines[$i]['timestart'];
                $time_end = $intervention_lines[$i]['timeend'];

                # Skip the line if information is missing
                if($time_start === '00:00:00' || $time_end === '00:00:00') { continue; }

                $start_datetime = new DateTime($time_start);
                $end_datetime = new DateTime($time_end);

                $hour_start = $start_datetime->format('H');
                $hour_end = $end_datetime->format('H');
                $is_ended_next_day = $hour_end < $hour_start;

                # Special calculations if the intervention was performed across midnight
                if($is_ended_next_day)
                {
                    $hours_to_midnight = 24 - $hour_start;
                    $minute_start =$start_datetime->format('i');
                    if($minute_start !== 0)
                    {
                        $hours_to_midnight = 24 - $hour_start - 1;
                        $minutes_to_midnight = 60 - $minute_start;
                    }
                    else 
                    {
                        $hours_to_midnight = 24 - $hour_start;
                        $minutes_to_midnight = 0;
                    }
                    $total_elapsed->h += $hours_to_midnight;
                    $total_elapsed->i += $minutes_to_midnight;

                    $total_elapsed->h += $end_datetime->format('H');
                    $total_elapsed->i += $end_datetime->format('i');
                }
                else 
                {
                    $line_elapsed = $start_datetime->diff($end_datetime);
                    $total_elapsed->h += $line_elapsed->format('%h');
                    $total_elapsed->i += $line_elapsed->format('%i');
                }

                # Fix because DateIntervals don't work properly
                $total_hours = $total_elapsed->h + floor($total_elapsed->i / 60);
                $total_minutes = $total_elapsed->i % 60;
            }
        ?>
        <td><?php echo $total_hours . ' h ' . $total_minutes . ' min' ?></td>
    </tr>
    <?php if ($_SESSION['ds_term_intervention_value1']) { ?>
        <tr>
            <td>
                <strong>Total <?php echo $_SESSION['ds_term_intervention_value1'] ?></strong>
            </td>
            <?php 
                $total_value1 = 0;
                for($i = 0; $i < $num_lines; $i++)
                {
                    $total_value1 += $intervention_lines[$i]['value1'];
                }
            ?>
            <td><?php echo $total_value1; ?></td>
        </tr>
    <?php } ?>
    <?php if ($_SESSION['ds_term_intervention_value2']) { ?>
        <tr>
            <td>
                <strong>Total <?php echo $_SESSION['ds_term_intervention_value2'] ?></strong>
            </td>
            <?php 
                $total_value2 = 0;
                for($i = 0; $i < $num_lines; $i++)
                {
                    $total_value2 += $intervention_lines[$i]['value2'];
                }
            ?>
            <td><?php echo $total_value2; ?></td>
        </tr>
    <?php } ?>
    <?php if ($_SESSION['ds_term_intervention_value3']) { ?>
        <tr>
            <td>
                <strong>Total <?php echo $_SESSION['ds_term_intervention_value3'] ?></strong>
            </td>
            <?php 
                $total_value3 = 0;
                for($i = 0; $i < $num_lines; $i++)
                {
                    $total_value3 += $intervention_lines[$i]['value3'];
                }
            ?>
            <td><?php echo $total_value3; ?></td>
        </tr>
    <?php } ?>
    <?php if ($_SESSION['ds_term_intervention_value4']) { ?>
        <tr>
            <td>
                <strong>Total <?php echo $_SESSION['ds_term_intervention_value4'] ?></strong>
            </td>
            <?php 
                $total_value4 = 0;
                for($i = 0; $i < $num_lines; $i++)
                {
                    $total_value4 += $intervention_lines[$i]['value4'];
                }
            ?>
            <td><?php echo $total_value4; ?></td>
        </tr>
    <?php } ?>
    <?php echo d_table_end() ?>
</div>
<br>
<?php if(count($intervention_lines) === 0) { ?>
    <h3>Pas de lignes</h3>
<?php } else { ?>
    <h3>Lignes</h3>
    <table class="report">
        <tr>
            <th>Produit</th>
            <th>Employé</th>
            <th>Heure début</th>
            <th>Heure fin</th>

            <?php if($_SESSION['ds_term_interventionfield1']) { ?>
                <th><?php echo $_SESSION['ds_term_interventionfield1'] ?></th>
            <?php } ?>
            <?php if($_SESSION['ds_term_interventionfield2']) { ?>
                <th><?php echo $_SESSION['ds_term_interventionfield2'] ?></th>
            <?php } ?>
            <?php if($_SESSION['ds_term_interventionfield3']) { ?>
                <th><?php echo $_SESSION['ds_term_interventionfield3'] ?></th>
            <?php } ?>
            <?php if($_SESSION['ds_term_interventionfield4']) { ?>
                <th><?php echo $_SESSION['ds_term_interventionfield4'] ?></th>
            <?php } ?>
            <?php if($_SESSION['ds_term_intervention_tag1']) { ?>
                <th><?php echo $_SESSION['ds_term_intervention_tag1'] ?></th>
            <?php } ?>
            <?php if($_SESSION['ds_term_intervention_tag2']) { ?>
                <th><?php echo $_SESSION['ds_term_intervention_tag2'] ?></th>
            <?php } ?>
            <?php if($_SESSION['ds_term_intervention_value1']) { ?>
                <th><?php echo $_SESSION['ds_term_intervention_value1'] ?></th>
            <?php } ?>
            <?php if($_SESSION['ds_term_intervention_value2']) { ?>
                <th><?php echo $_SESSION['ds_term_intervention_value2'] ?></th>
            <?php } ?>
            <?php if($_SESSION['ds_term_intervention_value3']) { ?>
                <th><?php echo $_SESSION['ds_term_intervention_value3'] ?></th>
            <?php } ?>
            <?php if($_SESSION['ds_term_intervention_value4']) { ?>
                <th><?php echo $_SESSION['ds_term_intervention_value4'] ?></th>
            <?php } ?>
        </tr>
        <?php for ($i = 0; $i < $num_lines; $i++) { ?>
            <tr>
                <td>
                    <?php 
                        $query = 'select productname from product where productid=?';
                        $query_prm = array($intervention_lines[$i]['productid']); 
                        require('inc/doquery.php');

                        if($num_results === 1) { echo $query_result[0]['productname']; }
                    ?>
                </td>
                <td>
                    <?php 
                        $query = 'select employeename, employeefirstname from employee where employeeid=?';
                        $query_prm = array($intervention_lines[$i]['employeeid']); 
                        require('inc/doquery.php');

                        if($num_results === 1) 
                        { 
                            $fullname = $query_result[0]['employeename'] 
                                . ', ' 
                                . $query_result[0]['employeefirstname']; 
                            echo $fullname;
                        }
                    ?>
                </td>
                <td>
                    <?php 
                        if($intervention_lines[$i]['timestart'] !== '00:00:00')
                        {
                            echo substr($intervention_lines[$i]['timestart'], 0, 5);
                        } 
                    ?>
                </td>
                <td>
                    <?php 
                        if($intervention_lines[$i]['timeend'] !== '00:00:00')
                        {
                            echo substr($intervention_lines[$i]['timeend'], 0, 5);
                        } 
                    ?>
                </td>
                <?php if($_SESSION['ds_term_interventionfield1']) { ?>
                    <td><?php echo $intervention_lines[$i]['field1'] ?></td>
                <?php } ?>
                <?php if($_SESSION['ds_term_interventionfield2']) { ?>
                    <td><?php echo $intervention_lines[$i]['field2'] ?></td>
                <?php } ?>
                <?php if($_SESSION['ds_term_interventionfield3']) { ?>
                    <td><?php echo $intervention_lines[$i]['field3'] ?></td>
                <?php } ?>
                <?php if($_SESSION['ds_term_interventionfield4']) { ?>
                    <td><?php echo $intervention_lines[$i]['field4'] ?></td>
                <?php } ?>
                <?php if($_SESSION['ds_term_intervention_tag1']) { ?>
                    <td>
                        <?php 
                            $query = 'select interventionitemtag1name from interventionitemtag1 where interventionitemtag1id=?';
                            $query_prm = array($intervention_lines[$i]['interventiontagid']);
                            require('inc/doquery.php');

                            if($num_results === 1) { echo $query_result[0]['interventionitemtag1name']; }
                        ?>
                    </td>
                <?php } ?>
                <?php if($_SESSION['ds_term_intervention_tag2']) { ?>
                    <td>
                        <?php 
                            $query = 'select interventionitemtag2name from interventionitemtag2 where interventionitemtag2id=?';
                            $query_prm = array($intervention_lines[$i]['interventiontagid2']);
                            require('inc/doquery.php');

                            if($num_results === 1) { echo $query_result[0]['interventionitemtag2name']; }
                        ?>
                    </td>
                <?php } ?>
                <?php if($_SESSION['ds_term_intervention_value1']) { ?>
                    <td><?php echo $intervention_lines[$i]['value1'] ?></td>
                <?php } ?>
                <?php if($_SESSION['ds_term_intervention_value2']) { ?>
                    <td><?php echo $intervention_lines[$i]['value2'] ?></td>
                <?php } ?>
                <?php if($_SESSION['ds_term_intervention_value3']) { ?>
                    <td><?php echo $intervention_lines[$i]['value3'] ?></td>
                <?php } ?>
                <?php if($_SESSION['ds_term_intervention_value4']) { ?>
                    <td><?php echo $intervention_lines[$i]['value4'] ?></td>
                <?php } ?>
            </tr>
        <?php } ?>
    </table>
<?php } ?>