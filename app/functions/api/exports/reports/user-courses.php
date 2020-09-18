<table style="border: solid 1px #0266D0;">
    <tr>
        <th style="background: #0266D0; color: white;">Usuario</th>
        <th style="background: #0266D0; color: white;">Email</th>
        <th style="background: #0266D0; color: white;">Curso</th>
        <th style="background: #0266D0; color: white;">Videos vistos</th>
        <th style="background: #0266D0; color: white;">Descargas</th>
        <th style="background: #0266D0; color: white;">Cuestionarios</th>
        <th style="background: #0266D0; color: white;">Respuestas</th>
        <th style="background: #0266D0; color: white;">Ultima actividad</th>
    </tr>
    <tbody>
        <?php 
            $index = 0;
            
            foreach($user_course_logs as $log){ 
                $bg = ( $index % 2 == 0 ) ? '#D2E7FF' : 'white';
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
                            <?php if ($log->user_metas->role == 'tutor') { ?>
                                <th style="background: #F70000; color: white;">Cantidad de hijos</th>
                            <?php } ?>
                        </tr>
                        <tbody>
                            <tr>
                                <td style="background: #F7B4B4;">
                                    <?php
                                        echo ($log->user) ? $log->user->data->user_nicename  : 'anonimo';
                                    ?>
                                </td>
                                <td style="background: #F7B4B4;"><?php echo $log->user_metas->role ?></td>
                                <td style="background: #F7B4B4;"><?php echo $log->user_metas->sector ?></td>
                                <td style="background: #F7B4B4;"><?php echo $log->user_metas->age ?></td>
                                <td style="background: #F7B4B4;"><?php echo $log->user_metas->phone ?></td>
                                <td style="background: #F7B4B4;"><?php echo $log->user_metas->calling_code . ' ' . $log->user_metas->mobile ?></td>
                                <td style="background: #F7B4B4;"><?php echo $log->user_metas->location ?></td>
                                <td style="background: #F7B4B4;"><?php echo $log->user_metas->school ?></td>
                                <?php if ($log->user_metas->role == 'tutor') { ?>
                                    <td style="background: #F7B4B4;"><?php echo $log->user_metas->children ?></td>
                                <?php } ?>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $log->user_email ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $log->course ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $log->topic_views ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $log->material_downloads ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $log->test_count ?></td>
                <td style="background: <?php echo $bg; ?>;">
                    Correctos: <?php echo $log->right_answers ?> - Incorrectos: <?php echo $log->wrong_answers ?>
                </td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $log->last_date ?></td>
            </tr>
        <?php $index++; } ?>
    </tbody>
</table>
