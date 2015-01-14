

<?php
//if (!empty($datosgarantia)) {
//    echo "<table border='2' id='table_existe' class='table table-striped'>";
//    foreach ($datosgarantia as $empresa => $idgarantia) {
//        echo "<tr><td style='width : 120px' align='center'><b>Empresa</b></td><td align='center'><b>".$empresa."</b></td></tr>";
//        foreach ($idgarantia as $idgarantiaacuerdo => $nombregarantia) {
//            foreach ($nombregarantia as $garantia => $valoravaluo) {
//                echo "<tr><td align='center'><button garantia='".$idgarantiaacuerdo."' type='button' class='eliminar btn btn-success'>Eliminar</td><td style='width : 120px'><b>Garantía</b></td><td style='width : 120px'>" . $garantia . "</td>";
//                foreach ($valoravaluo as $avaluo => $valorcomercial) {
//                    echo "<td style='width : 120px'><b>Valor Avalúo</b></td><td style='width : 120px'>" . $avaluo . "</td>";
//                    foreach ($valorcomercial as $comercial => $nombrecampo) {
//                        echo "<td style='width : 120px'><b>Valor Comercial</b></td><td style='width : 120px'>" . $comercial . "</td>";
//                        foreach ($nombrecampo as $campo => $nombrecampo) {
//                            echo "<td style='width : 120px'><b>" . $campo . "</b></td><td style='width : 120px'>" . $nombrecampo . "</td>";
//                        }
//                    }
//                }
//                
//            }
//            echo "</tr>";
//        }
//    }
//    echo "</table>";
//}
?>

<form id="formgarantia" method="post">
    <input type="hidden" value="<?= $nit ?>" name="nit" id="nit">    
    <input type="hidden" value="<?= $cod_coactivo ?>" name="cod_coactivo" id="cod_coactivo">    
    <input type="hidden" value="<?= $acuerdo ?>" name="acuerdo" id="acuerdo">  
    <input type="hidden" value="1" name="contador" id="contador">  
    
    <div id="ingresodegarantia">
        <!--<form id="formgarantia">-->
        <table align="center">
            <tr>
                <td colspan="4" align="center" style="width : 1000"><h1>GARANTIAS</h1></td>
            </tr>
            <tr>
                <td align="left"><b>Nit</b></td>
                <td align="right"><?= $nit ?></td>
            </tr>
            <tr>
                    <td><b>El valor de su deuda es:</b></td>
                    <td align="right">
                        <?php
                            if (!empty($totalliquidacion)):
                                echo "$ ".$totalliquidacion;
                            else:
                                echo "0";
                            endif;                            
                        ?>
                    </td>
            </tr>
                <tr>
                    <td><b>El valor deuda en UVT:</b></td>
                    <td align="right">
                        <?php
                            if (!empty($valorTotalUvt)):
                                echo $valorTotalUvt;
                            else:
                                echo "0";
                            endif;                            
                        ?>
                    </td>
                </tr>
        </table>    
        <br>
        <?php if ($tipo == "A") { ?>
            <a href="<?= base_url('index.php/acuerdodepago/ingresoacuerdodepago') ?>">Facilidad de Pago</a>
        <?php } else if ($tipo == "J") { ?>
            <a href="<?= base_url('index.php/acuerdodepagojuridico/ingresaracuerdopagojuridico') ?>">Facilidad de Pago</a>
        <?php } ?>
        <br> 
        <br> 
        <table id="tablagarantias">
            <tr>
                <td></td>
                <th align='center'>Tipo</th>
                <th align='center'>Valor Comercial</th>
                <th align='center'>Valor Límite de medida</th>
            </tr>
            <tr id="clongarantia">
                <td>
                    <img style="display:none" class="preloadminicuota" id="preloadminicuota" src="<?php echo base_url('img/319.gif') ?>" width="28" height="28" />
                </td>                
                <td>    
                    <select class="otrasgarantias estilogarantia" id="garantia" name="garantia[]">
                        <option value="">-Seleccion-</option>
                        <?php foreach ($garantias->result_array as $listadogarantias) { ?>
                            <option value="<?= $listadogarantias['COD_TIPO_GARANTIA'] ?>"><?= $listadogarantias['NOMBRE_TIPOGARANTIA'] ?></option>
                        <?php } ?>
                    </select></td>                
                <td><input type="number" class="estilogarantia comercial" placeholder="Valor Comercial" id="vcomercial[]" name="vcomercial[]" size='15'></td>                
                <td><input type="number" class="estilogarantia valor" placeholder="Valor Avaluo"  id="vavaluo[]" name="vavaluo[]"></td>
                <td><button type="button" class="clon btn btn-success" ><i class="fa fa-arrow-circle-right"></i> Agregar</button></td>
                <td><button type="button" class="quitarclon btn btn-warning" ><i class="fa fa-minus-circle"></i> Quitar</button></td>
            </tr>
            <tr colspan="5">
                <td colspan='5'></td> 
            </tr>
        </table>
        <br><br>   
    </div>
    <br>
    <div style="display:none" id="error" class="alert alert-danger"></div>
    <br>
    <div >
        <button type="button" id="guardar" class="btn btn-success"><i class="fa fa-floppy-o"></i> Guardar</button>
    </div>    
    <div id="ajax_load" class="ajax_load" style="display: none">
        <div class="preload" id="preload" >
            <img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
        </div>
    </div>
</form> 
<style>
    .datos{
        background-color: #ff8800;
    }
</style>   
<script>
// -------------------------------------------------------------------------------
// Eliminar   
// -------------------------------------------------------------------------------   

$('.eliminar').click(function(){
    $(".ajax_load").show("slow");
    $(this).parents('tr:last').remove();
    var garantia = $(this).attr('garantia');            
    var url = "<?= base_url('index.php/acuerdodepagojuridico/eliminargarantia')  ?>";
    $.post(url,{garantia : garantia},function(data){
        $(".ajax_load").hide();
    });
    
});

//---------------------------------------------------------------------------------

    $('#guardar').click(function() {
        $(".ajax_load").show("slow");        
            var i = 0;
            $('.comercial, .valor, .otrasgarantias').each(function(indice, campo) {
                if ($(this).val() == "")
                {
                    $(".ajax_load").hide();
                    $(this).css('border-color', 'blue');
                    i++
                    return;
                }
                else
                    $(".ajax_load").hide();
                    $(this).removeAttr('style');
            });
            
            if(i == 0){
                var total = 0;
                var liquidacionTotal = <?= $totalliquidacion ?>;                
                var url = "<?= base_url("index.php/acuerdodepagojuridico/guardagarantiaingreso") ?>";
               // alert (url);
                     $('.valor').each(function(indice, campo) {
                        var valor = $(this).val();
                        total = parseInt(total) + parseInt(valor);
                      });
//                  if (total >= (liquidacionTotal + liquidacionTotal)){
//                        $('#error').show();
//                        $('#error').html('El Valor De la Garantia Es mayor al doble de la deuda');
//                  }else if (total < liquidacionTotal){
//                        $('#error').show();
//                        $('#error').html('El Valor De la Garantia Es menor de la deuda');
//                  }else {
                    $(".ajax_load").show();
                    $('#guardar').attr('disabled',true);
                    $.post(url, $('#formgarantia').serialize())
                        .done(function(msg){
                            $(".ajax_load").hide();
                            window.location = "<?= base_url().'index.php/bandejaunificada/procesos' ?>";
                        })
                        .fail(function(msg){
                        $(".ajax_load").hide();
                        }); 
                //  }    
                     
            }else {
                $('#error').show();
                $('#error').html('Por Favor Ingresar los Campos requeridos');
            }                                  
    });

    $('.quitarclon').on("click", function() {
        $(this).parents('tr:last').next('tr').remove();
        $(this).parents('tr:last').remove();
        var contador = $('#contador').val();
        contador = parseInt(contador) - 1;
        if (contador <= 0){
            $('#contador').val('0');    
        }else {
            $('#contador').val(contador);
        }
    });
    $('.clon').on("click", function() {
        $(this).parents('tr:last').clone(':button').appendTo($('#tablagarantias'));
        $(this).parents('tr:last').next().clone(':button').appendTo($('#tablagarantias'));
        var contador = $('#contador').val();
        contador = parseInt(contador) +1;
        if (contador <= 0){
            $('#contador').val('0');    
        }else {
            $('#contador').val(contador);
        }
    });
    
    $('.otrasgarantias').on("change", function() {
        $("#preloadminicuota").show();
        var garantia = $('#garantia').val();        
        var idgarantia = $(this).val();
        var url = "<?= base_url("index.php/acuerdodepagojuridico/muestrahtmlcamposgarantias") ?>";
        //$(this).parents('tr:last').next('tr').load(url, {idgarantia: idgarantia}); 
        $(this).parents('tr:last').next('tr').load( url, { idgarantia: idgarantia }, function() {
            $("#preloadminicuota").hide();
        });
    });
  
</script>    
