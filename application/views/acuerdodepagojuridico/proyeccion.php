
<h3 align="center">ELABORACIÓN DE ACUERDO DE PAGO</h3>
<br>

<br>
<table align="center">
    <tr>
        <td colspan="6" align="center">
            <b>PARA CONSULTAR SU DEUDA Y HACER UNA PROYECCIÓN DE FACILIDAD DE PAGO POR<br>FAVOR INGRESAR EL NO IDENTIFICACIÓN Y/O EJECUTORIA Y EL CONCEPTO</b></td>
    </tr>
    <tr><td><br></td></tr>
    <tr>
        <td colspan="6" align='left'>
            <b> Tasa Superintendencia hoy <?php echo $tasaEfectiva[0]['TASA_SUPERINTENDENCIA']."%" ?></b>
        </td>
    </tr>
    <tr><td><br></td></tr>
    <tr>
        <td>No Liquidación</td>
        <td>
            <input type="text" id="liquidacion" class="oblinit">
            <img style="display:none" id="loadm" src="<?php echo base_url('img/319.gif') ?>" width="28" height="28" />
        </td>
        <td>No Identificación</td>
        <td><input type="text" id="identificacion" class="oblinit" readonly></td>
        <td>Nombre del deudor</td>
        <td><input type="text" id="ejecutoria" class="oblinit" readonly></td>        
    </tr>
    <tr>
        <td>Tipo concepto</td>
        <td>
            <select id="concepto1" class="oblinit" disabled>
                <option value="">-Seleccionar-</option>
                <?php
                foreach ($concepto->result_array as $conc) {
                    ?>
                    <option value="<?= $conc['COD_CPTO_FISCALIZACION'] ?>"><?= $conc['NOMBRE_CONCEPTO'] ?></option>    
                <?php }
                ?>
            </select>
        </td>
        <td style='display:none' id="selectCuotas">Numero de Cuotas</td>
        <td id="CuotasCant">
            
        </td>        
    </tr>
    <tr>
        <td colspan="6">
            <div align="center" id="error" class='alert alert-danger' style="display:none"></div>
        </td>
    </tr>
    <tr>
        <td colspan="6" align="right"><button type="button" id="calcularidentificacion" class="btn btn-success">Consultar</button></td>
    </tr>
    <tr id="alertaidentificacion">
        
    </tr>    
    <tr>
        <td colspan="6" style="background-color: black"></td>
    </tr>    
    <tr>
        <td align="center" colspan="6">
            <b>PARA HACER UNA PROYECCIÓN DE FACILIDAD DE PAGO POR UN MONTO POR <br>FAVOR INGRESAR LOS SIGUIENTES DATOS</b>
        </td>
    </tr>
    <tr>
        <td>Valor a proyectar</td>
        <td><input type="text" style="text-align:right" id="vfinanciar" class="obligatorio restaurar"></td>
        <td>Cuota inicial</td>
        <td><input type="text"  style="text-align:right" id="cuotainicial" name="cuotainicial" class="restaurar obligatorio"></td>
        <td>Fecha Primera Cuota</td>
        <td>
            <?php $fecha = date('d-m-Y') ?>
            <input type="date" value="<?= date("d/m/Y", strtotime($fecha . "+1 month")) ?>" disabled="disabled" id="fpcuota" class=" restaurar"  >
        </td>
    </tr>
    <tr>
        <td>Valor Cuota</td>    
        <td>
            <input style="text-align:right" type="text" id="vcuota" class="restaurar" placeholder="valor cuota" readonly>
        </td>        
        <td>
            Fecha Cuota Inicial
        </td>
        <td>
            <input type="date" style="cursor: pointer" id="finicial" class="date" value="<?= date('d/m/Y') ?>" disabled="disabled" >
        </td>
        <td id="cuotasProy" style="display:none">Numero cuotas</td>
        <td id="numCuotas">
        </td>
    </tr>

    <tr>
        <td colspan="3" align="right">
            <button type="button" id="restaurar" class="btn">Restaurar</button>  
        </td>    
        <td colspan="1" align="right">
            <button type="button" id="consultar" class="btn btn-success" content='fa fa-calculator'> Calcular</button>
        </td>
    </tr>
</table>
<form id="frmtp" method="post">
<input type='hidden' id='acuerdo' name='acuerdo' value='<?= $numAcuerdo ?>'>
<input type='hidden' id='nit' name='nit' value='<?= $nit ?>'>
<input type='hidden' id='fiscalizacion' name='fiscalizacion' value='<?= $fiscalizacion ?>'>
<input type='hidden' id='dato_valida' name='dato_valida' value=''>
<input type='hidden' id='liquidacion' name='liquidacion' value='<?= $liquidacion ?>'>
<input type="hidden" id="datos_envio" name="datos_envio" value=''>
<div style="display:none">
    <textarea id="datos_envio" name="datos_envio" rows="10" cols="50" value=''></textarea>
</div>    

<div id="alerta" align="center"></div>
<div id="alertafecha" align="center"></div>
<br>
<table id="styletable" class="imp">
    <thead>
    <th>PERIODO</th>
    <th>FECHA</th>
    <th>SALDO A CAPITAL</th>
    <th>INTERESES DE MORA</th>
<!--    <th>INTERESES CUOTA</th>-->
    <th>INTERESES DE PLAZO</th>
    <th>VALOR CUOTA</th>
    <th>APORTE A CAPITAL</th>
    <th>SALDO FINAL</th>
</thead>
<tbody id="cuerpotabla">

</tbody>
<tbody id="cuerpoExcel">
    
</tbody>
</table>
<br>
<div align="right">
    <button type="button" id="exportar" class="btn btn-success " disabled='true'><i class="fa fa-print"> Imprimir</i></button>   
</div>
</form>
<div id="alertafecha"></div><div id="ajax_load" class="ajax_load" style="display: none">
    <div class="preload" id="preload" >
        <img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
    </div>
</div>

<style>
    .coloralerta{
        background-color: red;
    }
</style>
<script>    
  $("#liquidacion").autocomplete({
        source: "<?php echo base_url("index.php/acuerdodepago/buscar_empresa") ?>",
        minLength: 1,
        search: function(event, ui) {
            $("#loadm").show();
        },
        response: function(event, ui) {
            $("#loadm").hide();
        }
    });    
  
  $("#liquidacion").blur(function() {
        var liquidacion = $("#liquidacion").val();
        var url = "<?php echo base_url("index.php/acuerdodepago/traerempresa") ?>";
        $("#loadm").show();
        $.ajax({
            type: "POST",
            url: url,
            data: {liquidacion: liquidacion},
            success:
                    function(data) {
                        $("#loadm").hide();
                        if (data[0].CODEMPRESA == null){
                            $('#error').show();
                            $('#error').html('<b>No Se Encontraron Registros<b>')        
                        }else{
                            $('#identificacion').val(data[0].CODEMPRESA);
                            $('#ejecutoria').val(data[0].RAZON_SOCIAL);
                            $('#concepto1').val(data[0].COD_CONCEPTO);
                            var nit         = $('#identificacion').val();
                            var ejecutoria  = $('#ejecutoria').val();
                            var concepto    = $('#concepto1').val(); 
                            var url         =  "<?= base_url('index.php/acuerdodepago/cuotas')?>";  
                            var juridico    = '4';
                            if (nit == ''){
                                $('#identificacion').focus();
                                $('#error').show();
                                $('#error').html('<b>Por Favor Inserte Nit<b>')        
                            }else if (ejecutoria == ''){
                                $('#ejecutoria').focus();
                                $('#error').show();
                                $('#error').html('<b>Por Favor Inserte Ejecutoria<b>')
                            }else{
                                $(".ajax_load").show("slow");
                                $('#CuotasCant').load(url,{
                                    identifiacion:nit,
                                    ejecutoria:ejecutoria,
                                    concepto:concepto,
                                    juridico:juridico
                                    },function(data){                            
                                        $('#selectCuotas').show();        
                                        $('#error').hide('slow');
                                        $(".ajax_load").hide("slow");
                                        });        
                            }    
                        }
                    }
        });
    });  
        

$('#vfinanciar').blur(function(){
    var juridico    = '1';
    var vfinanciar  = $('#vfinanciar').val();
    var url         =  "<?= base_url('index.php/acuerdodepago/cuotas')?>"; 
    $('#numCuotas').load(url,{
            juridico:juridico,
            financiar:vfinanciar
        },function(data){                            
            $('#cuotasProy').show();  
            $(".ajax_load").hide("slow");
            });                
})

$('#calcularidentificacion').click(function(){
    $(".ajax_load").show("slow");
    $('#alertaidentificacion').remove();
    $('#vfinanciar').attr('readonly',true);
    $('#cuotainicial').attr('readonly',true);
    $('#concepto').attr('readonly',true);
    $('#vcuota').attr('readonly',true);
    $('#consultar').attr('disabled',true);
    $('#restaurar').attr('disabled',true);
    $('#exportar').attr('disabled',false);
    var ejecutoria  = $('#ejecutoria').val();
    var nit         = $('#identificacion').val();
    var concepto    = $('#concepto1').val(); 
    var cuotas      = $('#cuotasiden').val();
    var liquidacion = $('#liquidacion').val();
    var url         = "<?= base_url('index.php/acuerdodepago/tableacuerdopago') ?>";
    var alerta      = "POR FAVOR INGRESAR LOS DATOS";
    var juridico    = '4';
    
    
    if((ejecutoria != "" || nit != "") && concepto != "" && cuotas != ""){        
            $('#cuerpotabla').load(url, {cuotas : cuotas,juridico : juridico,ejecutoria : ejecutoria, nit : nit, concepto : concepto, liquidacion:liquidacion
            },function(data){
               $(".ajax_load").hide(); 
               $("#datos_envio").val( $("<div>").append( $("#cuerpotabla").eq(0).clone()).html());
            });
    }
    else{
        $('.oblinit').each(function(indice,campo){
            if($(this).val() == ""){
                $(".ajax_load").hide(); 
                $(this).css('background-color','red');}
            else{$(this).css('background-color','white');}
        });
        $('#alertaidentificacion').append('<td colspan="6" align="center" style="color : red"><b>'+alerta+'<b></td>')
    }

    
});

//------------------------------------------------------------------------------
//CAMPOS NUMERICOS    
//------------------------------------------------------------------------------    

    $('#vcuota').number(true, 2);
    $('#vfinanciar').number(true, 0);
    $('#cuotainicial').number(true, 0);

//------------------------------------------------------------------------------
//FECHAS     
//------------------------------------------------------------------------------    

    $('#fpcuota').datepicker({
        dateFormat: "dd/mm/yy",
        minDate: "0d",
        maxDate: "60d"
    });


    $('#finicial').datepicker({
        dateFormat: "dd/mm/yy",
        maxDate: "0d",
        minDate: "0d"
    });
// -----------------------------------------------------------------------------
// ********************RESTAURAR DE NUEVO LOS VALORES***************************  
// -----------------------------------------------------------------------------  

    $('#restaurar').click(function() {
        $("#vfinanciar").val('');
        $("#cuotainicial").val('');
        $("#vcuota").val('');
        $("#concepto").val('');
        $("#cuotasiden").val('');
        $('#styletable').dataTable().fnClearTable();
    });
    

//  Valores Numericos
    $('#cuotainicial').change(function() {
        if (isNaN($(this).val())) {
            $(this).val('');
        }
    })  

// Boton Consultar

    $("#consultar").click(function() {
        var i = 0;
        $('.obligatorio').each(function(indice, campo) {
            if ($(this).val() == "") {
                $(this).css('background-color', '#FC7323');
                i++;
                if ($('#alertamensaje').length == 0) {
                    $('#alerta').append('<h4 style="color : red" id="alertamensaje">Por favor ingresar información</h4>');
                }
            } else {
                $('#alerta *').remove();
            }
        });
        if (i == 0 && $('#alerfechacuota').length == 0) {
            $(".ajax_load").show("slow");
            $('#exportar').attr('disabled',false);
            $('#alerfechacuota').remove();
            var ncuotas = $('#cuotasiden').val();
            var saldocapital = $('#vfinanciar').val();
            var vinicuota = $('#cuotainicial').val();
            var tipoconcepto = $('#concepto').val();
            var valorcuota = $('#vcuota').val();
            var finicial = $('#finicial').val();
            var fpcuota = $('#fpcuota').val();
            var proyeccion = 1;
            var url = "<?= base_url("index.php/acuerdodepago/tableacuerdopago") ?>";
            $('#cuerpotabla').load(                
                url, {valorcuota: valorcuota, fpcuota: fpcuota,               
                finicial: finicial, saldocapital: saldocapital, vinicuota: vinicuota, tipoconcepto: tipoconcepto,
                ncuotas: ncuotas, proyeccion: proyeccion
            },function(){                        
                 $(".ajax_load").hide("slow");
                });
            

            $("#datos_envio").val( $("<div>").append( $("#styletable").eq(0).clone()).html());            
        }

    });

//------------------------------------------------------------------------------
//Calculo de valor a financiar
//------------------------------------------------------------------------------
    $('#vfinanciar').change(function() {
        if (isNaN($(this).val())) {
            $(this).val('');
        } else {
            $(".ajax_load").show("slow");
            $('#identificacion').attr('disabled', true);
            $('#ejecutoria').attr('disabled', true);
            $('#concepto1').attr('disabled', true);
            $('#exportar').attr('disabled',false);
            $('#calcularidentificacion').attr('disabled', true);
            $('#cuotasiden *').remove();
            $('#cuotainicial').val('');
            var concepto = $('#concepto').val();
            var valorfinanciar = $(this).val();            
            var url = "<?= base_url('index.php/acuerdodepago/consultadatatableproyeccion') ?>";
            $.post(url, {concepto: concepto, valorfinanciar: valorfinanciar}, function(data) {
                var cuotainicial = (valorfinanciar * data);
                $('#cuotainicial').val(cuotainicial);
                $('.obligatorio').attr('disabled', false);
            }).done(function(data){
                        $(".ajax_load").hide("slow");
                    }).fail(function (){
                        //alert('Error en la consulta')
                        $(".ajax_load").hide("slow");
                    })
            $('#cuotainicial').val();
        }
    });

$("#exportar").click(function() {
        $(".ajax_load").show("slow");
        var url     = "<?= base_url('index.php/acuerdodepago/exportaExcel')?>"; 
        $("#datos_envio").val( $("<div>").append( $("#cuerpotabla").eq(0).clone()).html());
        $("#frmtp").attr("action", url);       
        $("#frmtp").submit();
        $(".ajax_load").hide();
    });   
//------------------------------------------------------------------------------
//DATATABLE    
//------------------------------------------------------------------------------   

    var oTable = $('#styletable').dataTable({
        "bJQueryUI": true,
        "bFilter": false,
        "oTableTools": {
            "aButtons": [
                "copy",
                "print",
                {
                    "sExtends":    "collection",
                    "sButtonText": "Save",
                    "aButtons":    [ "csv", "xls", "pdf" ]
                }
            ]
        },     
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

//------------------------------------------------------------------------------    
</script>    
