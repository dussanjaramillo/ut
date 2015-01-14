<div align="center"><h3 align="center">INGRESO ACUERDO DE PAGO</h3>
    <br><?= $nombreusuario ?>
    <br><?= $cargousuario ?></div>
<table id="estilotabla">
    <thead>
    <th>No Identificación</th>
    <th>Ejecutoria</th>
    <th>Resolución</th>
    <th>Liquidación</th>
    <th>Garantías</th>
    <!--<th>Autorizacion Sin<br>Garantías</th>-->
    <!--<th>Plazo Meses</th>-->
    <th>Generar Acuerdo</th>
</thead>
<tbody><?php foreach ($func->result_array as $resolucion) { ?>
    <tr>
        <td align='center'><?= $resolucion['NITEMPRESA'] ?></td>
        <td align='center'><?php if (!empty($resolucion['NOMBRE_EMPRESA'])) echo $resolucion['NOMBRE_EMPRESA']; ?></td>
        <td align='center'><?= $resolucion['NUMERO_RESOLUCION'] ?></td>
        <td align='center'><?= $resolucion['NUM_LIQUIDACION'] ?></td>
        <td align='center'><button fiscalizacion="<?= $resolucion['COD_FISCALIZACION'] ?>"  nit="<?= $resolucion['NITEMPRESA'] ?>" type="button" class="garantias btn btn-success" >Garantías</button></td>
        <!--<td><button fiscalizacion="<?= $resolucion['COD_FISCALIZACION'] ?>"  nit="<?= $resolucion['NITEMPRESA'] ?>" type="button" class="singarantia btn btn-success" >Sin Garantía</button></td>-->
        <!--<td><button fiscalizacion="<?= $resolucion['COD_FISCALIZACION'] ?>" type="button" class="meses btn btn-success">Plazo en Meses</button></td>-->
        <td align="center"><input class="seleccion" type='radio' numresolucion="<?= $resolucion['NUMERO_RESOLUCION'] ?>" name="liquidacion" nit="<?= $resolucion['NITEMPRESA'] ?>" numliquidacion="<?= $resolucion['NUM_LIQUIDACION'] ?>" ></td>
    </tr>
    <?php } ?>
</tbody>
</table>
<br>
<div id="alerta"></div>
<div align="center">
    <button type="button" id="enviarregistro" class="btn btn-success">INGRESAR</button>
</div>
<br>
<div id="envioautorizar" align="center">¿Desea enviar autorizar para <br>crear acuerdo de pago sin garantía?
    <br>
    <br>
    <table id="dialogos">
        <tr>
            <td><b>Observación</b></td>
        </tr>
        <tr>    
            <td><textarea id="observacionexcepcion" style="width: 338px; height: 75px;"></textarea></td>
        </tr>
    </table>
</div>
<div id="maximoplazomeses">
    El plazo máximo para el acuerdo de pago es:&nbsp;<span id="agregarmeses"></span>&nbsp; Meses
    <br>
    <br>
    <table id="maximomesesautorizar">
        <tr>
            <td>
                Enviar para autorizar más meses
            </td>
        </tr>    
        <tr>
            <td>
                <select id="maximomeses">
                    <option value="">-Seleccionar-</option>
                    <option value="1">Si</option>
                    <option value="2">No</option>
                </select>
            <td>
        </tr>
    </table>
</div>
<form id="f1"  method="post" >
    <input type="hidden" id="nit1" name="nitg" >
    <input type="hidden" id="tipo" name="tipo" value="A">
    <input type="hidden" id="resolucion" name="resolucion">
    <input type="hidden" id="fiscalizacion" name="fiscalizacion">
    <input type="hidden" id="liquidacion" name="liquidacion">
</form>
<script>

//-------------------------------------------------------------------------------
//Aplazar Meses
//-------------------------------------------------------------------------------    
    $('#maximoplazomeses').hide();
    $('.meses').click(function() {
        $('#maximomeses').val('');
        var fiscalizacion = $(this).attr('fiscalizacion');
        var url = "<?= base_url('index.php/acuerdodepago/calcularcuota') ?>"
        $('.opcionmeses').remove();
        $('#agregarmeses *').remove();
        
        $.post(url,{fiscalizacion : fiscalizacion },function(data){
            $('#agregarmeses').text(data.PLAZOMESES);
        

        $('#maximoplazomeses').dialog({
            autoOpen: true,
            title : "Autorizacion de Plazo",
            modal: true,
            width: 500,
            buttons: [{
                    id: 'guardarmeses',
                    class: 'btn btn-success',
                    text: 'Guardar',
                    click: function() {
                        if ($('#maximomeses').val() == 1 && $('#observacionesmeses').val() != "") {
                            if ($('#alertaobservacion').length != 0)
                                $('#alertaobservacion').remove();
                            var observacion = $('#observacionesmeses').val();
                            var url = "<?= base_url('index.php/acuerdodepago/excepcion') ?>";
                            var tipo = "A";
                            $.post(url, {tipo : tipo,fiscalizacion: fiscalizacion,observacion : observacion}, function(data) {
                                        
                            });
                        } else if ($('#maximomeses').val() == 1 && $('#observacionesmeses').val() == "") {
                            if ($('#alertaobservacion').length != 0)
                                $('#alertaobservacion').remove();
                            $('#maximomesesautorizar').append('<tr id="alertaobservacion" class="alert alert-danger"><td align="center"><h4>Por favor ingresar observación</h4></td></tr>');
                        } else if ($('#maximomeses').val() == "") {
                            if ($('#alertaobservacion').length != 0)
                                $('#alertaobservacion').remove();
                            if ($('#alertaobservacion').length == 0)
                                $('#maximomesesautorizar').append('<tr id="alertaobservacion" class="alert alert-danger"><td align="center"><h4>Por favor seleccionar si autoriza mas meses</h4></td></tr>');
                        }
                        if($('#maximomeses').val() == 2){
                            $('#maximoplazomeses').dialog('close');
                        }
                    }
                }, {
                    id: 'cerrar',
                    class: 'btn',
                    text: 'cerrar',
                    click: function() {
                        $('#maximoplazomeses').dialog('close');
                    }
                }]
        });
        });
    });
//------------------------------------------------------------------------------
//observaciones de aplazamiento meses    
//------------------------------------------------------------------------------    
    $('#maximomeses').change(function() {
        if ($('#alertaobservacion').length != 0)
            $('#alertaobservacion').remove();

        var valor = $(this).val();
        if (valor == 1) {

            if ($('#opcionmeses').length == 0)
                $('#maximomesesautorizar').append('<tr class="opcionmeses"><td><b>Observaciones</b></td></tr><tr class="opcionmeses" id="opcionmeses"><td align="center"><textarea id="observacionesmeses" style="width: 456px; height: 71px;"></textarea></h4><td></tr>');
        }
        else {
            if ($('#opcionmeses').length != 0)
                $('.opcionmeses').remove();
        }

    });

//-------------------------------------------------------------------------------
//Creacion de Garantías    
//-------------------------------------------------------------------------------    
    $('#envioautorizar').hide();
    $('.singarantia').click(function() {

        if ($('#alertaobservacion').length != 0)
            $('#alertaobservacion').remove();
        $('#observacionexcepcion').val('');
        var fiscalizacion = $(this).attr('fiscalizacion');
        var nit = $(this).attr('nit');


        $('#envioautorizar').dialog({
            autoOpen: true,
            modal: true,
            width: 400,
            title: 'Sin Garantía',
            dialogClass: "no-close",
            buttons: [{
                    id: 'guardarautorizacion',
                    text: 'AUTORIZAR',
                    class: 'btn btn-success',
                    click: function() {
                        if ($('#observacionexcepcion').val() != "") {
                            var observacion = $('#observacionexcepcion').val();
                            $('#envioautorizar').dialog('close');
                            var url = "<?= base_url('index.php/acuerdodepago/guardaexcepcion') ?>"
                            $.post(url, {fiscalizacion: fiscalizacion, observacion: observacion}, function() {

                            });
                        } else {
                            if ($('#alertaobservacion').length == 0)
                                $('#dialogos').append("<tr id='alertaobservacion' class='alert alert-danger'><td align='center'><h4>Por favor ingresar observación</h4></td></tr>");
                        }
                    }
                }, {
                    id: 'cerrar', 
                    text: 'Cerrrar',
                    class: 'btn',
                    click: function() {
                        $('#envioautorizar').dialog('close');
                    }
                }]
        });

    });

//-------------------------------------------------------------------------------
//Creacion de Garantías    
//-------------------------------------------------------------------------------    
    $('.garantias').click(function() {
        var fiscalizacion = $(this).attr('fiscalizacion');
        $('#fiscalizacion').val(fiscalizacion);
        var nit = $(this).attr('nit');
        $('#nit1').val(nit);
        var url = "<?= base_url('index.php/acuerdodepago/modificaciongarantias') ?>";
        $('#f1').attr('action', url);
        $('#f1').submit();
    });


    $('.seleccion').click(function() {
        var resolucion = $(this).attr('numresolucion');
        var liquidacion = $(this).attr('numliquidacion');
        var nit = $(this).attr('nit');

        var url = "<?= base_url('index.php/acuerdodepago/validaciongarantias') ?>";
        $('#f1').attr('action', url);
        $('#nit1').val(nit);
        $('#resolucion').val(resolucion);
        $('#liquidacion').val(liquidacion);
    });

    $('#enviarregistro').click(function() {
        if ($('#nit1').val() != '' && $('#resolucion').val() != '' && $('#liquidacion').val() != '') {
            $('#alerta *').remove();
            $('#f1').submit();
        }
        else {
            if ($('#mensajealerta').length == 0)
                $('#alerta').append('<h4 id="mensajealerta" class="alert alert-danger" align="center">Por favor escoger un acuerdo de pago </h4>');
        }
    });
    $('#consultar').click(function() {
        $("#consultar").hide();
        $("#preloadmini").show();


        var nit = $('#nit').val();
        var url = "<?= base_url("index.php/acuerdodepago/resolucion") ?>";
        $('#liquidaciones').load(url, {nit: nit}, function(data) {
            $("#preloadmini").hide();
            $("#consultar").show();
        });
    });
//   $('.date').datepicker({dateFormat: "dd-mm-yy"});
    $('#estilotabla').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
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
        }
    });
</script>    