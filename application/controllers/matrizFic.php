                        <form id="form1" method="post">
<?php
$inicio = explode('/', $fechainicio);
$final = explode('/', $fechafinal);
$difanio = $final[2]-$inicio[2];
$total = 0;
$cont = 1;

if ($inicio[2] == $final[2]){
    $cantanio = $difanio+1;
    $cantmeses = $total_mes + 1;
    $valor = $valor / $cantmeses;
}else{    
    $cantanio = $difanio+1;   
    $cantmeses = (($cantanio*12)-(12-$mesfin)-($mesini-1));
    $valor = $valor / $cantmeses;
}

$anio = substr($fechafinal,6,7);
$meses = array(" ", "Enero", "Febrero", "Marzo", "Abil", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre", "TOTAL");
//Ciclo para los titulos
echo "<table border ='0' id='matriz'>";
echo "<tr>";
for ($colTit = 0; $colTit < 14; $colTit++) {
    echo "<th class='item'>$meses[$colTit]</th>";
}
echo "</tr>";

//Ciclo para cargar la informacion
for ($fila = 1; $fila <= $cantanio; $fila++) {
    echo "<tr>";
    for ($col = 1; $col <= 12; $col++) {
        //if que imprime los años que se van a generar
        if ($col == 1 && ($fila == 1 || $fila == 2 || $fila == 3 || $fila == 4 || $fila == 5 || $fila == 6)) {
            echo "<th class='item'>$anio</th>";
            $anio--;
        }//Fin If
                            
        //if que valida en donde inicia y finaliza la insercion de los datos
        if (($fila == $cantanio && $col < $mesini) || ($fila == 1 && $col > $mesfin)){
            $valores = 0; 
        }else{
            $valores = $valor;            
        }//fin if
        
        //Acumulador que suma los valores de la fila
        $total = $valores + $total;
        echo "<td align='right'>
                <input type='text' style='width:80px' value='$ " . round($valores) . "'  readonly='readonly' id='cantidad" . $fila . $col . "' name ='cantidad" . $fila . $col . "[]'>
                <input type='text' style='width:80px' value='$ ' readonly='readonly' id='empleado" . $fila . $col . " '>    
                </td>";   

        //if que imprime la sumatoria de cada una de las filas    
        if ($col == 12 && ($fila == 1 || $fila == 2 || $fila == 3 || $fila == 4 || $fila == 5 || $fila == 6)) {
            ?> <td align="right">
                <input type='text' style='width:80px' value='$&nbsp;<?= round($total) ?>'  readonly='readonly' id='cantidad<?= $fila . $col ?>'>                
            </td>  
            <?php
            //Cuando se termina cada una de las filas el retorna cero
            $total = 0;
        }//fin if               
             
    }
    echo "</tr>";
}//Fin Ciclo que carga la informacion
echo "</table>";
?>
            
            
            <input type="button" size="20" class="btn btn-success" value="Guardar" style="width:103px" id="guardar"  name="guardar" />
                        </form>
<script>
$("#guardar").click(function(){
        var url = "<?= base_url('index.php/mandamientopago/guardar')?>";
        $.post(url,$("#form1").serialize());        
        
});
</script>

                
