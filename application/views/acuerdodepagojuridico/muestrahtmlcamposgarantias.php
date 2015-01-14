<?php
$i = 0;
foreach ($campos->result_array as $camposgarantias[] => $valor) {
        ?>
        <td>
            <input type="text" placeholder="<?= $valor['NOMBRE_CAMPO'] ?>" name="valorgarantia[]" id="valorgarantia[]<?= $i ?>">                                
        </td>

        <?php
    $i++;
}
echo '<td><input type="hidden" value ="' . count($camposgarantias) . '" id="datos[]" name="datos[]"></td>';
?>

<script>
    $(document).ready(function() {
        setTimeout(function() {
            $("#loadm").hide(); 
        }, 2000);                          
    });    
</script>"