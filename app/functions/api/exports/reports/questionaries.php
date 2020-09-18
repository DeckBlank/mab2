<table style="border: solid 1px #0266D0;">
    <tr>
        <th style="background: #0266D0; color: white;">Usuario</th>
        <th style="background: #0266D0; color: white;">Email</th>
        <th style="background: #0266D0; color: white;">Resultado</th>
        <th style="background: #0266D0; color: white;">Fecha</th>
    </tr>
    <tbody>
        <?php
            $index = 0;
            
            foreach($questionaries as $questionary){
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
                            <?php if ($questionary->user_metas->role == 'tutor') { ?>
                                <th style="background: #F70000; color: white;">Cantidad de hijos</th>
                            <?php } ?>
                        </tr>
                        <tbody>
                            <tr>
                                <td style="background: #F7B4B4;">
                                    <?php
                                        echo ($questionary->user) ? $questionary->user->data->user_nicename  : 'anonimo';
                                    ?>
                                </td>
                                <td style="background: #F7B4B4;"><?php echo $questionary->user_metas->role ?></td>
                                <td style="background: #F7B4B4;"><?php echo $questionary->user_metas->sector ?></td>
                                <td style="background: #F7B4B4;"><?php echo $questionary->user_metas->age ?></td>
                                <td style="background: #F7B4B4;"><?php echo $questionary->user_metas->phone ?></td>
                                <td style="background: #F7B4B4;"><?php echo $questionary->user_metas->calling_code . ' ' . $questionary->user_metas->mobile ?></td>
                                <td style="background: #F7B4B4;"><?php echo $questionary->user_metas->location ?></td>
                                <td style="background: #F7B4B4;"><?php echo $questionary->user_metas->school ?></td>
                                <?php if ($questionary->user_metas->role == 'tutor') { ?>
                                    <td style="background: #F7B4B4;"><?php echo $questionary->user_metas->children ?></td>
                                <?php } ?>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $questionary->user_email ?></td>
                <td style="background: <?php echo $bg; ?>;">
                    <table style="border: solid 1px #DE0D46;">
                        <tr>
                            <th style="background: #DE0D46; color: white;">Clave</th>
                            <th style="background: #DE0D46; color: white;">Pregunta</th>
                            <th style="background: #DE0D46; color: white;">Respuesta</th>
                        </tr>
                        <tbody>
                            <?php                                
                                $r_index = 0;
                                foreach($questionary->result as $question){
                                    $r_bg = ( $r_index % 2 == 0 ) ? '#FFD1D1' : 'white';                                
                            ?>
                            <tr>
                                <td style="background: <?php echo $r_bg; ?>;"><?php echo $question->key ?></td>
                                <td style="background: <?php echo $r_bg; ?>;"><?php echo $question->title ?></td>
                                <td style="background: <?php echo $r_bg; ?>;"><?php echo $question->value ?></td>
                            </tr>
                            <?php $r_index++; } ?>
                        </tbody>
                    </table>
                </td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $questionary->date_at ?></td>
            </tr>
        <?php $index++; } ?>
    </tbody>
</table>
