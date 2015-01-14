
<div id="pila"><h1>ADMINISTRACIÓN DE PILA</h1></div>
<div id="letrero" align="center"> </div>
<form id="fpila">
    <table id="tpila" align="center">
        <tr>
            <td>Nombre</td>
            <td><input type="text" name="nombre[]" class="nombre" style="width: 500px;"></td>
            <td>Desde</td>
            <td><input type="text" name="desde[]" class="desde" style="width: 30px;"></td>
            <td>Hasta</td>
            <td><input type="text" name="hasta[]" class="hasta"style="width: 30px;"></td>
            <td>Tipo</td>
            <td>
                <select id="tipo" name="tipo[]">
                    <option value="">-Seleccionar-</option>
                    <option value="12345">Tipo 0</option>
                    <option value="00000">Tipo 1</option>
                     <option value="00001">Tipo 2</option>
                    <option value="00031">Tipo 3 Aportes</option>
                    <option value="00036">Tipo 3 Mora</option>
                    <option value="00039">Tipo 3 Total</option>
                    <option value="08">PRUEBA</option>
                </select>
            </td>
            <td><button type="button" id="agregar" class="agregar btn btn-success" >Agregar</button></td>
            <td><button type="button" id="eliminar" class="eliminar btn btn-success" >Eliminar</button></td>
        </tr>
      
    </table>
    
    <table id="tapila" align="center">
        <tr>
            <td colspan="7" align="center"><button type="button" id="guardar" class="guardar btn btn-success">GUARDAR</button></td>
        </tr>
    </table>
</form>

<table align='center' border='1' id="estilotabla">
    <thead><tr>
        <th style='width: 200px'>ID</th>    
        <th style='width: 200px'>NOMBRE DE CAMPO</th>
        <th style='width: 200px'>LONGITUD</th>
        <th style='width: 200px'>DESDE</th>
        <th style='width: 200px'>HASTA</th>
        <th style='width: 200px'>TIPO</th>
        <th style='width: 200px'>OBLIGATORIO</th>
        <th style='width: 200px'>PALABRA CLAVE</th>
     </tr></thead>
    <?php foreach($campos->result_array as $campostabla){?>
    <tr>
        <td><?= $campostabla['COD_CAMPOARCHIVO'] ?></td>
        <td><?= $campostabla['NOMBRE_CAMPO'] ?></td>
        <td><?= $campostabla['LONGITUD'] ?></td>
        <td><?= $campostabla['DESDE'] ?></td>
        <td><?= $campostabla['HASTA'] ?></td>
        <td><?= $campostabla['TIPO_ARCHIVO'] ?></td>
        <td align="center" class="obligatorio"><?= ($campostabla['OBLIGATORIO'] == 1)?" X ":""; ?></td>
        <td align="center"><?= $campostabla['PALABLA_CLAVE'] ?></td>
     </tr>
    <?php } ?>
    
     <div align='center'><h4>-Dar clcik en la fila correspondiente para modificar-</h4></div>    
</table>
<div id='editar' style="display: none;">
    <table border='2' align='center'>
        <tr>
            <td>NOMBRE</td>
            <td ><input type='text' name='nombre1' id='nombre1' class='editarc' disabled="disabled"></td>
        </tr>
        <tr>
            <td>LONGITUD</td>
            <td><input type='text' name='longitud' id='longitud' class='editarc vacio' disabled="disabled"></td>
        </tr>
        <tr>
            <td>DESDE</td>
            <td><input type='text' name='desde1' id='desde1' class='editarc vacio' disabled="disabled"></td>
        </tr>
        <tr>
            <td>HASTA</td>
            <td><input type='text' name='hasta1' id='hasta1' class='editarc vacio' disabled="disabled"></td>
        </tr>
        <tr>
            <td>OBLIGARIO</td>
            <td align="center"><input type="checkbox" name="obligatorio" class='vacio' id="obligatorio" ></td>
        </tr>
        <tr>
            <td>PALABRA CLAVE</td>
            <td align="center"><input type="text" name="palabraobligatoria" class='vacio' id="palabraobligatoria" ></td>
        </tr>
    </table>
</div>
<div id='editarcampo'></div> 
<style>
    .ui-widget-overlay{z-index: 10000;}
    .ui-dialog{
        z-index: 15000;
    }
</style>
<script>
    
    $('#obligatorio').change(function(){
        if($('#obligatorio').is(':checked')){$('#obligatorio').val(1) }
        else{$('#obligatorio').val(0) }
    });
  
    $('#estilotabla tbody .obligatorio').click(function(){
        var tabla = $('#estilotabla').dataTable({
            "bJQueryUI": true,
        "sPaginationType": "full_numbers",
        "sServerMethod": "POST",
        "oLanguage": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "fnInfoCallback": null,
        },
        });
        var posicion = tabla.fnGetPosition(this);
        var columna = tabla.fnGetData(posicion[0]);
//        console.log(columna[1]);
        var nombre = columna[1];
        var longitud = columna[2];
        var desde = columna[3];
        var hasta = columna[4];
        var obligatorio = columna[7];
        
        console.log(obligatorio);
        
        $('#nombre1').val(nombre);
        $('#longitud').val(longitud);
        $('#desde1').val(desde);
        $('#hasta1').val(hasta);
        $('#palabraobligatoria').val(obligatorio);
        if(obligatorio == "X"){ $('#obligatorio').attr('checked',true);$('#obligatorio').val(1);}
        else {$('#obligatorio').attr('checked',false);$('#obligatorio').val(0);}
        
        var apuntador = this;
        console.log(apuntador);
        
    $('#editar').dialog({
        autoOpen : true,
        width : 400,
        height : 400,
        modal : true,
        buttons : [{
                id: 'guardarmodificado',
                text: 'Guardar',
                class: 'btn btn-success',
                align : 'center',
                click: function(){
                    if($('#obligatorio').val() == 1){ var valor = "X"; }else{ var valor = "";}
                    apuntador.innerHTML = valor ;
                    var nombre = $('#nombre1').val();
                    var longitud = $('#longitud').val();
                    var desde = $('#desde1').val();
                    var hasta = $('#hasta1').val();
                    var palabraobligatoria = $('#palabraobligatoria').val();
                    var obligatorio = $('#obligatorio').val();
                    var url = "<?= base_url('index.php/pila/guardarcampospila') ?>";
                    
                    $.post(url,{palabraobligatoria : palabraobligatoria,nombre : nombre,longitud : longitud, desde : desde, hasta : hasta, obligatorio : obligatorio},function(data){
//                        $(this).dialog('close');
//                          $('.vacio').val('');  
                    });
                    
                }
        },{
            id: 'eliminar',
            text: 'Eliminar',
            class: 'btn btn-success',
             align : 'center',
            click: function(){
                var confirmar = confirm('Esta seguro que desea eliminar esta fila');
                if(confirmar == true){ 
                            tabla.fnDeleteRow(posicion[0]);        
                            $(this).dialog('close');
                        }
            }
        }],
        close: function(){
            $('.vacio').val(''); 
            $(this).dialog('close');
        }
    }); 
        
        
    });
  

    
    $('#agregar').click(function() {
       var tpila = $('#tpila tr').length;
     
       
        $(this).parents('tr:last').clone(':button').appendTo($('#tpila'));
    });
     $('.eliminar').click(function() {
        var tpila = $('#tpila tr').length;
       
        if(tpila!=1){
                    $(this).parents('tr:last').remove();

        }
        
    });
    $('#guardar').click(function() {
        var i = 0;
        $('.nombre,.desde,.hasta,#tipo').each(function(indice, campo) {
            if ($(this).val() == "")
            {
                $(this).css('border-color', 'red');
                i++
            }
            else  $(this).removeAttr('style');
        });
         if (i == 0) {
                var url = "<?= base_url('index.php/pila/ingresarcampospila') ?>";
                $.post(url,$('#fpila').serialize());
                $('#alerta').remove();
                alert('Sus datos han sido guardados exitosamente');
            }
            else {
                var contador = $('#alerta').length; 
                if(contador == 0){
                $('#letrero').append('<b id="alerta">Por favor ingresar Todos los Datos</b>');
                }
                
            }
            

    });


</script>

