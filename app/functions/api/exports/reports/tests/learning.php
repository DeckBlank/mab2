<table style="border: solid 1px #0266D0;">
    <tr>
        <th style="background: #0266D0; color: white;">Usuario</th>
        <th style="background: #0266D0; color: white;">Email</th>
        <th style="background: #0266D0; color: white;">Visual</th>
        <th style="background: #0266D0; color: white;">Auditivo</th>
        <th style="background: #0266D0; color: white;">Kinestésico</th>
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
                    <table>
                        <tr>
                            <th style="background: #F70000; color: white;">Nombres</th>
                            <th style="background: #F70000; color: white;">Rol</th>
                            <th style="background: #F70000; color: white;">Sector</th>
                            <th style="background: #F70000; color: white;">Edad</th>
                            <th style="background: #F70000; color: white;">Teléfono</th>
                            <th style="background: #F70000; color: white;">Celular</th>
                            <th style="background: #F70000; color: white;">Ubicación</th>
                            <th style="background: #F70000; color: white;">Colegio</th>
                            <?php if ($test->user_metas->role == 'tutor') { ?>
                                <th style="background: #F70000; color: white;">Cantidad de hijos</th>
                            <?php } ?>
                        </tr>
                        <tbody>
                            <tr>
                                <td style="background: #F7B4B4;">
                                    <?php
                                        echo ($test->user) ? $test->user->data->user_nicename  : 'anonimo';
                                    ?>
                                </td>
                                <td style="background: #F7B4B4;"><?php echo $test->user_metas->role ?></td>
                                <td style="background: #F7B4B4;"><?php echo $test->user_metas->sector ?></td>
                                <td style="background: #F7B4B4;"><?php echo $test->user_metas->age ?></td>
                                <td style="background: #F7B4B4;"><?php echo $test->user_metas->phone ?></td>
                                <td style="background: #F7B4B4;"><?php echo $test->user_metas->calling_code . ' ' . $test->user_metas->mobile ?></td>
                                <td style="background: #F7B4B4;"><?php echo $test->user_metas->location ?></td>
                                <td style="background: #F7B4B4;"><?php echo $test->user_metas->school ?></td>
                                <?php if ($test->user_metas->role == 'tutor') { ?>
                                    <td style="background: #F7B4B4;"><?php echo $test->user_metas->children ?></td>
                                <?php } ?>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $test->user_email ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $result->visual ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $result->auditive ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $result->kinesthetic ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $test->date_at ?></td>
            </tr>
        <?php $index++; } ?>
    </tbody>
</table>
