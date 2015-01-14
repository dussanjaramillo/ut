<div align="center"><h3 align="center">AUTORIZACIÓN ACUERDO DE PAGO SIN GARANTÍA</h3>
    <br><?= $nombreusuario ?>
    <br><?= $cargousuario ?></div>
<br>
<h5 align='center' style='color : red'>Para autorizar el acuerdo de pago por favor dar click en autorizar</h5>
<table id="estilotabla">
    <thead> 
    <th>No Identificación</th>
    <th>Ejecutado</th>
    <th>No Liquidación</th>
    <th>Autorización</th>    
    <th>Fiscalización</th>
</thead>
<tbody>
    <?php foreach ($autorizaciones->result_array as $auto) { ?>
        <tr>
            <td><?= $auto['CODEMPRESA'] ?></td>
            <td><?= $auto['RAZON_SOCIAL'] ?></td>
            <td><?= $auto['NUM_LIQUIDACION'] ?></td>
            <td codexcepcion="<?= $auto['COD_EXCEPCIONACUERDO'] ?>" class="autorizacion" style="cursor: pointer" align='center' ><button type="button" id="ingresarautorizacion" class="btn btn-success"><i class="fa fa-bolt"></i>&nbsp;<b>Autorizar</b></button></td>
            <td><?= $auto['COD_FISCALIZACION'] ?></td>
        </tr>
    <?php } ?>
</tbody>
</table>
<div id="aceptacion" >
    <div id="alertadatos" align="center"></div>
    <table>
        <tr><td><b>No Identificación:</b></td><td id="acnit"></td></tr>
        <tr><td><b>Ejecutado:</b></td><td id="acrazon"></td></tr>
        <tr><td><b>Liquidación:</b></td><td id="acliqui"></td></tr>
        <tr><td><b>Autoriza</b></td><td><select id='seleccionautoriza'><option value=''>-Seleccionar-</option><option value='3'>SI</option><option value='4'>NO</option></select></td></tr>
        <tr><td><b>Observación</b></td><td><textarea id='observacion' style="width: 344px; height: 222px;"></textarea></td></tr>
    </table>
    
</div>
<!--<style>
    .autorizacion:hover{
        color: white;
        background-color: green;
    }
</style>    -->
<script>
    $('#aceptacion').hide();
//------------------------------------------------------------------------------    
// Autorizaciones
//------------------------------------------------------------------------------    

    $('#estilotabla tbody tr .autorizacion').click(function() {
        
        var codexcepcion = $(this).attr('codexcepcion');
        
        $('#observacion').val('');
        $('#seleccionautoriza').val('');

        var oTable = $('#estilotabla').dataTable();
        var posicion = oTable.fnGetPosition(this);
        var columnas = oTable.fnGetData(posicion[0]);

        var editar = columnas[2];


        console.log(columnas);
//        console.log(this);
        $('#campoeditar').val(columnas[2]);

        var nit = columnas[0];
        var razonsocial = columnas[1];
        var liquidacion = columnas[2];
        var fiscalizacion = columnas[4];
        
        $('#acnit').text(nit);
        $('#acrazon').text(razonsocial);
        $('#acliqui').text(liquidacion);
        
        var encuentro = this;

        $('#aceptacion').dialog({
            autoOpen: true,
            modal: true,
            width: 600,
            title: "AUTORIZACION",
            height: 500,
            buttons : [{
                    text : "Guardar",
                    id : "guardar",
                    class: "btn btn-success",
                    click : function(){
                        $('#alertadatos *').remove();
                        if($('#observacion').val() != "" &&  $('#seleccionautoriza').val() != ""){
                            
                            var url = "<?= base_url('index.php/acuerdodepago/guardaautorizacion') ?>";
                            var opcion = $('#seleccionautoriza').val();
                            var observacion = $('#observacion').val();
                            var tipo = "A";
                            $.post(url,{codexcepcion : codexcepcion,tipo: tipo,fiscalizacion : fiscalizacion,opcion : opcion, observacion : observacion  });
                            oTable.fnDeleteRow( posicion[0] ); 
                            $('#aceptacion').dialog('close');
                        }else{
                            if( $('#observacion').val() == "" ){
                            $('#alertadatos').append('<h4 style="color: red" id="alertaobservacion" >Por favor ingresar la observación</h4>');
                            }
                            else if($('#seleccionautoriza').val() == ""){
                            $('#alertadatos').append('<h4 style="color: red" id="autoriza" >Por favor ingresar la opción "Autoriza"</h4>');
                            }
                        }
                    }
            }]
        });
//        console.log(encuentro);


//        aData[ aPos[1] ] = 'clicked';
//        aPos.val();
//        console.log(aPos);
    });

//------------------------------------------------------------------------------    
    $('#estilotabla').dataTable({
       "bJQueryUI": true,
        "sPaginationType": "full_numbers",
        "bFilter": false,
        "bInfo": false,
        "sClass": "right",
         "oLanguage": {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":    "",
            "sSearch":         "Buscar:",
            "sUrl":            "",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
            "sFirst":    "Primero",
            "sLast":     "Último",
            "sNext":     "Siguiente",
            "sPrevious": "Anterior"
             },
    "fnInfoCallback": null,
            }
    }).fnSetColumnVis( 4, false );;
//------------------------------------------------------------------------------    

</script>