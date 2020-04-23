<table style="border: solid 1px #FF0000;">
    <tr>
        <th style="background: #FF0000; color: white;">Nombres y apellidos</th>
        <th style="background: #FF0000; color: white;">Tipo</th>
        <th style="background: #FF0000; color: white;">Curso</th>
        <th style="background: #FF0000; color: white;">Fecha de inicio</th>
        <th style="background: #FF0000; color: white;">Fecha de vencimiento</th>
    </tr>
    <tbody>
        <?php 
            $index = 0;
            
            foreach($expired_registrations as $consult){ 
                $bg = ( $index % 2 == 0 ) ? '#FFDAD9' : 'white';
                $user_type = '';

                if ($consult->type == 'student') {
                    $user_type = "Estudiante";

                } else if($consult->type == 'teacher') {
                    $user_type = "Profesor";

                } else if($consult->type == 'tutor') {
                    $user_type = "Padre/Tutor";

                }
                
        ?>
            <tr>
                <td style="background: <?php echo $bg; ?>;"><?php echo $consult->fullname ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $user_type ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $consult->course ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $consult->date_start ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $consult->date_end ?></td>
            </tr>
        <?php $index++; } ?>
    </tbody>
</table>
