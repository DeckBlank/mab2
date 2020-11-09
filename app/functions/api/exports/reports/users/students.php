<table style="border: solid 1px #0266D0;">
    <tr>
        <th style="background: #0266D0; color: white;">Tipo</th>
        <th style="background: #0266D0; color: white;">Usuario</th>
        <th style="background: #0266D0; color: white;">Email</th>
        <th style="background: #0266D0; color: white;">Edad</th>
        <th style="background: #0266D0; color: white;">Genero</th>
        <th style="background: #0266D0; color: white;">Teléfono</th>
        <th style="background: #0266D0; color: white;">Celular</th>
        <th style="background: #0266D0; color: white;">Codigo de celular</th>
        <th style="background: #0266D0; color: white;">Tipo de colegio</th>
        <th style="background: #0266D0; color: white;">Colegio de estudios</th>
        <th style="background: #0266D0; color: white;">Grado escolar</th>
        <th style="background: #0266D0; color: white;">Ubicación</th>
        <th style="background: #0266D0; color: white;">Fecha de registro (creación)</th>
    </tr>
    <tbody>
        <?php 
            $index = 0;
            
            foreach($users as $user){ 
                $bg = ( $index % 2 == 0 ) ? '#D2E7FF' : 'white';
        ?>
            <tr>
                <td style="background: <?php echo $bg; ?>;"><?php echo $user->role ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $user->firstname ?> <?php echo $user->lastname ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $user->email ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $user->age ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $user->gender ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $user->phone ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $user->mobile ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $user->calling_code ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $user->school_type ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $user->school ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $user->grade ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $user->location ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $user->created ?></td>
            </tr>
        <?php $index++; } ?>
    </tbody>
</table>
