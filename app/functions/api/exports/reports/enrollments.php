<table style="border: solid 1px #FF0000;">
    <tr>
        <th style="background: #FF0000; color: white;">Usuario</th>
        <th style="background: #FF0000; color: white;">Email</th>
        <th style="background: #FF0000; color: white;">Curso</th>
        <th style="background: #FF0000; color: white;">Estado</th>
        <th style="background: #FF0000; color: white;">Fecha de inscripción</th>
        <th style="background: #FF0000; color: white;">Fecha de vencimiento</th>
        <th style="background: #FF0000; color: white;">Ultima actividad</th>
    </tr>
    <tbody>
        <?php 
            $index = 0;
            
            foreach($expired_enrollments as $enrollment){ 
                $bg = ( $index % 2 == 0 ) ? '#FFDAD9' : 'white';
        ?>
            <tr>
                <td style="background: <?php echo $bg; ?>;">
                    <?php 
                        echo $enrollment->user->data->user_nicename;
                    ?>
                </td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $enrollment->user_email ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $enrollment->course ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $enrollment->state ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $enrollment->date_at ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $enrollment->date_end ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $enrollment->last_date ?></td>
            </tr>
        <?php $index++; } ?>
    </tbody>
</table>