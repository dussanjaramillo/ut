<form id="camposgarantias">
    <table id="tablagarantias">
        <tr id="nuevagarantia">
            <td>Tipo Garantía</td>
            <td><select id="listagarantia" name="listagarantia[]">
                    <option value="">-Seleccionar-</option>
                    <?php foreach ($garantias->result_array as $idgarantias) { ?>
                        <option value="<?= $idgarantias['COD_TIPO_GARANTIA'] ?>"><?= $idgarantias['NOMBRE_TIPOGARANTIA'] ?></option>
                    <?php } ?>
                </select>
            </td>
            <td>Nombre Garantía</td><td><input type="text" placeholder="Campo Garantia" name="campogarantia[]" id="campogarantia"></td> 
            <td><button id="agregargarantia" type="button" class="clon btn btn-success">Agregar Campo</button></td>
            <td><button id="quitargarantia" type="button" class="quitarclon btn btn-success">Quitar Garantía</button></td>
        </tr>
    </table>
    <br><br>    
    <div align="center"><button type="button" id="guardarcampos" class="btn btn-success">GUARDAR</button><img id="preloadgarantia" src="<?php echo base_url('img/319.gif') ?>" width="28" height="28" /></div>
    <div id="alerta"></div>
</form>
<div id="alertaexistente" align="center"></div>
<script>
    $(document).ready(function() {
       $(".ajax_load").hide();
    });    
    
    $('#preloadgarantia').hide();
    
//    alert(alertaexistente);
    
    var alertaexistente = "<?= $tipoexistente ?>";
    
    if(alertaexistente != 1){
        if($('#existencia').length == 0) $('#alertaexistente').append('<h4 id="existencia" style="color : red">'+alertaexistente+'<h4>');
        $('#alertaexistente').dialog({
            autoOpen : true,
            modal : true,
            buttons : [{
                    id : 'cerrar',
                    class : 'btn btn-success',
                    text : 'Cerrar',
                    click : function(){
                        $('#alertaexistente').dialog('close');
                    }
            }]
        });
    }
    
    $('.clon').click(function() {
        
        $(this).parents('tr:last').clone(':button').appendTo($('#tablagarantias'));
         
    });
    $('#guardarcampos').click(function() {

         
        var count = 0;
        $('#tablagarantias input,select').each(function(indice,campo){
            var valor = $(this).val();
            if (valor == ""){
                count++;
                $(this).css({
                    'background-color': '#fcefa1',
                    'border-color' : 'red'
                });}else($(this).removeAttr('style'));});
        if(count == 0){
             $('#alerta *').remove();
         $('#preloadgarantia').show();
         $('#guardarcampos').hide();
            var url = "<?= base_url("index.php/acuerdodepago/guardacamposgarantias") ?>";   
            $.post(url, $('#camposgarantias').serialize(),function(data){
                         $('#preloadgarantia').hide();
                            $('#guardarcampos').show();
                                 $("#preloadmini").show();
     
                    var url = "<?= base_url('index.php/acuerdodepago/totalgarantias') ?>";
                    $('#estilogarantia').dataTable().fnClearTable();
                    var actualizar = "";
                    $.post(url,{actualizar : actualizar},function(data){

                        $.each(data,function(key,val){
                            $('#estilogarantia').dataTable().fnAddData([
                                val.COD_CAMPO,
                                val.NOMBRE_TIPOGARANTIA,
                                val.NOMBRE_CAMPO
                            ]);
                        });
                        $("#preloadmini").hide();
                    });
            });
        }
        else{
            if($('#alertadatos').length == 0)
           $('#alerta').append('<h3 align="center" id="alertadatos">Por favor Ingresar datos en los campos subrayados</h3>');
        }
    });
    $('#quitargarantia').click(function(){
        if($('#tablagarantias tr').length > 1 ){
            $(this).parents('tr:last').remove(); 
        }
    });

</script>    