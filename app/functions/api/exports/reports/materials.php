<table style="border: solid 1px #0266D0;">
    <tr>
        <th style="background: #0266D0; color: white;">Nombres</th>
        <th style="background: #0266D0; color: white;">Role</th>
        <th style="background: #0266D0; color: white;">Sector</th>
        <th style="background: #0266D0; color: white;">Edad</th>
        <th style="background: #0266D0; color: white;">Teléfono</th>
        <th style="background: #0266D0; color: white;">Celular</th>
        <th style="background: #0266D0; color: white;">Ubicación</th>
        <th style="background: #0266D0; color: white;">Colegio</th>
        <th style="background: #0266D0; color: white;">Email</th>
        <th style="background: #0266D0; color: white;">PDFs descargados</th>
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
                <td style="background: <?php echo $bg; ?>;"><?php echo $log->user_metas->role ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $log->user_metas->sector ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $log->user_metas->age ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $log->user_metas->phone ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $log->user_metas->calling_code . ' ' . $log->user_metas->mobile ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $log->user_metas->location ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $log->user_metas->school ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $log->user_email ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $log->downloads ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $log->last_topic ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $log->last_date ?></td>
            </tr>
        <?php $index++; } ?>
    </tbody>
</table>
