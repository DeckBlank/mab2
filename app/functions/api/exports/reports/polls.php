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
        <th style="background: #0266D0; color: white;">Resultado</th>
        <th style="background: #0266D0; color: white;">Fecha</th>
    </tr>
    <tbody>
        <?php
            $index = 0;
            
            foreach($polls as $poll){
                $bg = ( $index % 2 == 0 ) ? '#D2E7FF' : 'white';
        ?>
            <tr>
                <td style="background: <?php echo $bg; ?>;">
                    <?php
                        echo ($poll->user) ? $poll->user->data->user_nicename  : 'anonimo';
                    ?>
                </td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $poll->user_metas->role ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $poll->user_metas->sector ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $poll->user_metas->age ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $poll->user_metas->phone ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $poll->user_metas->calling_code . ' ' . $poll->user_metas->mobile ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $poll->user_metas->location ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $poll->user_metas->school ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $poll->user_email ?></td>
                <td style="background: <?php echo $bg; ?>;">
                    <?php                                
                        $r_index = 0;
                        foreach($poll->result as $question){
                            $r_bg = ( $r_index % 2 == 0 ) ? '#FFD1D1' : 'white';                                
                    ?>
                        <table style="border: solid 1px #DE0D46;">
                            <tbody>
                                <tr>
                                    <td style="background: #DE0D46; color: white;">Clave</td>
                                    <td style="background: <?php echo $r_bg; ?>;"><?php echo $question->key ?></td>
                                </tr>
                                <tr>
                                    <td style="background: #DE0D46; color: white;">Pregunta</td>
                                    <td style="background: <?php echo $r_bg; ?>;"><?php echo $question->title ?></td>
                                </tr>
                                <tr>
                                    <td style="background: #DE0D46; color: white;">Respuesta</td>
                                    <td style="background: <?php echo $r_bg; ?>;"><?php echo $question->value ?></td>
                                </tr>
                            </tbody>
                        </table>
                    <?php $r_index++; } ?>
                </td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $poll->date_at ?></td>
            </tr>
        <?php $index++; } ?>
    </tbody>
</table>
