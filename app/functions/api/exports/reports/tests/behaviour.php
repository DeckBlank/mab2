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
        <th style="background: #0266D0; color: white;">Perfil</th>
        <th style="background: #0266D0; color: white;">Fecha</th>
    </tr>
    <tbody>
        <?php
            $index = 0;
            
            foreach($tests as $test){ 
                $bg = ( $index % 2 == 0 ) ? '#D2E7FF' : 'white';
                $result = json_decode($test->result);
        ?>
            <tr>
                <td style="background: <?php echo $bg; ?>;">
                    <?php
                        echo ($test->user) ? $test->user->data->user_nicename  : 'anonimo';
                    ?>
                </td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $test->user_metas->role ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $test->user_metas->sector ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $test->user_metas->age ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $test->user_metas->phone ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $test->user_metas->calling_code . ' ' . $test->user_metas->mobile ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $test->user_metas->location ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $test->user_metas->school ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $test->user_email ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $result->title ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $test->date_at ?></td>
            </tr>
        <?php $index++; } ?>
    </tbody>
</table>
