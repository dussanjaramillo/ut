<?php


$i = 0;

foreach ($campos->result_array as $camposgarantias[]=>$valor) {
       ?>
            <td>
<!--                <input class="textogarantia" type="text" id="<?= $valor['COD_CAMPO'] ?>" placeholder="<?= $valor['NOMBRE_CAMPO'] ?>" name="<?= $valor['COD_TIPO_GARANTIA'] ?>//<?= $valor['COD_CAMPO'] ?>">-->
                                <input class="textogarantia" type="text" id="valorgarantia<?= $i ?>[]" placeholder="<?= $valor['NOMBRE_CAMPO'] ?>" name="valorgarantia<?= $i ?>[]">
                                
            </td>
            
       <?php 
       $i++;
}
echo '<td><input type="hidden" value ="'.count($camposgarantias).'" id="datos[]" name="datos[]"></td>';
?>

<script>
    $(document).ready(function() {
        setTimeout(function() {
            $("#loadm").hide(); 
        }, 2000);                          
    });    
</script>"