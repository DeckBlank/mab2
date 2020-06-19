<table style="border: solid 1px #0266D0;">
    <tr>
        <th style="background: #0266D0; color: white;">Usuario</th>
        <th style="background: #0266D0; color: white;">Email</th>
        <th style="background: #0266D0; color: white;">Cuestionarios</th>
        <th style="background: #0266D0; color: white;">Respuestas correctas</th>
        <th style="background: #0266D0; color: white;">Respuestas incorrectas</th>
        <th style="background: #0266D0; color: white;">Ultima tema</th>
        <th style="background: #0266D0; color: white;">Ultima actividad</th>
    </tr>
    <tbody>
        <?php
            $index = 0;
            
            foreach($material_logs as $log){ 
                $bg = ( $index % 2 == 0 ) ? '#D2E7FF' : 'white';
        ?>
            <tr>
                <td style="background: <?php echo $bg; ?>;">
                    <?php
                        echo ($log->user) ? $log->user->data->user_nicename  : 'anonimo';
                    ?>
                </td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $log->user_email ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $log->test_count ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $log->right_answers ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $log->wrong_answers ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $log->last_topic ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $log->last_date ?></td>
            </tr>
        <?php $index++; } ?>
    </tbody>
</table>