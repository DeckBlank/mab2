<table style="border: solid 1px #0266D0;">
    <tr>
        <th style="background: #0266D0; color: white;">Autor</th>
        <th style="background: #0266D0; color: white;">Comentario</th>
        <th style="background: #0266D0; color: white;">Respuestas</th>
        <th style="background: #0266D0; color: white;">Tema</th>
        <th style="background: #0266D0; color: white;">Fecha</th>
    </tr>
    <tbody>
        <?php
            $index = 0;
            
            foreach($comments as $comment){
                if(isset($comment)) {
                    $bg = ( $index % 2 == 0 ) ? '#D2E7FF' : 'white';
        ?>
            <tr>
                <td style="background: <?php echo $bg; ?>;"><?php echo $comment->author ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $comment->body ?></td>
                <td style="background: <?php echo $bg; ?>;">
                    <table style="border: solid 1px #DE0D46;">
                        <tr>
                            <th style="background: #DE0D46; color: white;">Autor</th>
                            <th style="background: #DE0D46; color: white;">Respuesta</th>
                            <th style="background: #DE0D46; color: white;">Fecha</th>
                        </tr>
                        <tbody>
                            <?php                                
                                $r_index = 0;
                                foreach($comment->answers as $answer){
                                    $r_bg = ( $r_index % 2 == 0 ) ? '#FFD1D1' : 'white';                                
                            ?>
                            <tr>
                                <td style="background: <?php echo $r_bg; ?>;"><?php echo $answer->comment_author ?></td>
                                <td style="background: <?php echo $r_bg; ?>;"><?php echo $answer->comment_content ?></td>
                                <td style="background: <?php echo $r_bg; ?>;"><?php echo $answer->comment_date ?></td>
                            </tr>
                            <?php $r_index++; } ?>
                        </tbody>
                    </table>
                </td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $comment->post->post_title ?></td>
                <td style="background: <?php echo $bg; ?>;"><?php echo $comment->date ?></td>
            </tr>
        <?php $index++; }} ?>
    </tbody>
</table>