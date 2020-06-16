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
                    <?php
                        echo ($questionary->user) ? $questionary->user->data->user_nicename  : 'anonimo';
                    ?>
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