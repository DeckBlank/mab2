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
        <th style="background: #0266D0; color: white;">
            <table>
                <tr>
                    <td style="background: #0266D0; color: white; text-align: center;" colspan="<?php echo $studentColumns->count + $tutorColumns->count; ?>">
                        Categorias
                    </td>
                </tr>
                <tr>
                    <td style="background: #FF0143; color: white; text-align: center;" colspan="<?php echo $studentColumns->count; ?>">
                        Estudiante
                    </td>
                    <td style="background: #FFEF00; text-align: center;" colspan="<?php echo $tutorColumns->count; ?>">
                        Tutor/Padre
                    </td>
                </tr>
                <tr>
                    <?php foreach($studentColumns->list as $column) { ?>
                        <td style="background: #FF0143; color: white; text-align: center;"><?php echo $column; ?></td>
                    <?php } ?>
                    <?php foreach($tutorColumns->list as $column) { ?>
                        <td style="background: #FFEF00; text-align: center;"><?php echo $column; ?></td>
                    <?php } ?>
                </tr>
            </table>        
        </th>
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
                    <?php
                        echo ($questionary->user) ? $questionary->user->data->user_nicename  : 'anonimo';
                    ?>
                </td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $questionary->user_metas->role ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $questionary->user_metas->sector ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $questionary->user_metas->age ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $questionary->user_metas->phone ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $questionary->user_metas->calling_code . ' ' . $questionary->user_metas->mobile ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $questionary->user_metas->location ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $questionary->user_metas->school ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $questionary->user_email ?></td>
                <td style="background: <?php echo $bg; ?>;">
                    <table style="border: solid 1px #DE0D46;">
                        <tbody>
                            <tr>
                                <?php
                                    if($questionary->user_metas->role == 'Estudiante') {
                                        $r_index = 0;
                                        foreach($questionary->result as $question){
                                            $r_bg = ( $r_index % 2 == 0 ) ? '#FFD1D1' : 'white';                                
                                ?>
                                        <td style="background: <?php echo $r_bg; ?>;"><?php echo $question->value ?></td>
                                    <?php
                                        $r_index++; }
                                        foreach (range(1, $tutorColumns->count) as $n) {
                                    ?>
                                        <td></td>
                                    <?php } ?>
                                <?php } else { ?>
                                    <?php foreach (range(1, $studentColumns->count) as $n) { ?>
                                        <td></td>
                                    <?php
                                        }
                                        $r_index = 0;
                                        foreach($questionary->result as $question){
                                            $r_bg = ( $r_index % 2 == 0 ) ? '#fdfbe0' : 'white';                                
                                    ?>
                                        <td style="background: <?php echo $r_bg; ?>;"><?php echo $question->value ?></td>
                                    <?php
                                        $r_index++; }
                                    ?>                                   
                                <?php } ?>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $questionary->date_at ?></td>
            </tr>
        <?php $index++; } ?>
    </tbody>
</table>
