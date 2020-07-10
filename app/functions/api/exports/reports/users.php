<table style="border: solid 1px #0266D0;">
    <tr>
        <th style="background: #0266D0; color: white;">Tipo</th>
        <th style="background: #0266D0; color: white;">Usuario</th>
        <th style="background: #0266D0; color: white;">Email</th>
        <th style="background: #0266D0; color: white;">Edad</th>
        <th style="background: #0266D0; color: white;">Genero</th>
        <th style="background: #0266D0; color: white;">Teléfono</th>
        <th style="background: #0266D0; color: white;">Celular</th>
        <th style="background: #0266D0; color: white;">Codigo de celular</th>
        <th style="background: #0266D0; color: white;">Tipo de colegio</th>
        <th style="background: #0266D0; color: white;">Colegio de estudios</th>
        <th style="background: #0266D0; color: white;">Grado escolar</th>
        <th style="background: #0266D0; color: white;">Ubicación</th>
    </tr>
    <tbody>
        <?php 
            $index = 0;
            
            foreach($view_logs as $log){ 
                $bg = ( $index % 2 == 0 ) ? '#D2E7FF' : 'white';
        ?>
            <tr>
                <td style="background: <?php echo $bg; ?>;">
                    <?php
                        echo ($log->user) ? $log->user->data->user_nicename  : 'anonimo';
                    ?>
                </td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $log->user_email ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $log->views ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $log->last_topic ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $log->last_date ?></td>
            </tr>
        <?php $index++; } ?>
    </tbody>
</table>