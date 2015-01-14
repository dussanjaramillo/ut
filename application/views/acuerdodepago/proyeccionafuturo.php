
<h3 align="center">ELABORACIÓN DE FACILIDAD DE PAGO</h3>
<br>

<br>
<table align="center">
     <tr>
        <td colspan="6" align='left'>
            <b> Tasa Superintendencia hoy <?php echo $tasaEfectiva[0]['TASA_SUPERINTENDENCIA']."%" ?></b>
        </td>
    </tr>
    <tr><td><br></td></tr>
    <tr>
        <td>No Identificación</td>
        <td><input type="text" id="identificacion" value='<?= $nit ?>' class="oblinit" readonly></td>
        <td>Ejecutoria</td>
        <td><input type="text" id="ejecutoria" value='<?= $razon ?>' class="oblinit" readonly></td>
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
    </tr>
    <tr style='display:none' id="selectCuotas">
        <td>Numero de Cuotas</td>
        <td id="CuotasCant">
            
        </td>            
    </tr>
    <tr>
        <td colspan="6">
            <div align="center" id="error" class='alert alert-danger' style="display:none"></div>
        </td>
    </tr>
     <tr>
        <td colspan="6" style="background-color: black"></td>
    </tr>
    <tr><td><br></td></tr>
    <tr>
        <td colspan="6" align="right"><button type="button" id="calcularidentificacion" class="btn btn-success">Consultar</button></td>
    </tr>
    <tr id="alertaidentificacion">
        
    </tr>
    
</table>
<form id="form" method="post">
<input type='hidden' id='acuerdo' name='acuerdo' value='<?= $numAcuerdo ?>'>
<input type='hidden' id='nit' name='nit' value='<?= $nit ?>'>
<input type='hidden' id='razon' name='razon' value='<?= $razon ?>'>
<input type='hidden' id='fiscalizacion' name='fiscalizacion' value='<?= $fiscalizacion ?>'>
<input type='hidden' id='liquidacion' name='liquidacion' value='<?= $liquidacion ?>'>
<input type='hidden' id='dato_valida' name='dato_valida' value=''>
<input type="hidden" id="datos_envio" name="datos_envio" value="">
<div id="alerta" align="center"></div>
<div id="alertafecha" align="center"></div>
<br>
<table id="styletable" class="imp">
    <thead>
    <th>PERIODO</th>
    <th>FECHA</th>
    <th>SALDO A CAPITAL</th>
    <th>INTERESES</th>
    <th>INTERESES CUOTA</th>
    <th>INTERESES ACUERDO</th>
    <th>VALOR CUOTA</th>
    <th>APORTE A CAPITAL</th>
    <th>SALDO FINAL</th>
</thead>
<tbody id="cuerpotabla">

</tbody>
</table>
<br>
<div align="right">
    <button type="button" id="aprobar" class="btn btn-success" onclick="datosActivo()" disabled='true'>Aprobar</button>   
    <button type="button" id="no_aprobar" class="btn btn-danger" onclick="datosInactivo()" disabled='true'>No Aprobar</button>   
    <button type="button" id="exportar" class="btn btn-success " disabled='true'>Imprimir</button>   
    <button type="button" id="atras" class="btn btn-warning" onclick="window.location='<?= base_url('index.php/acuerdodepago/gestionarAcuerdo') ?>'">Atras</button>   
</div>
</form>
<div id="excel"></div>
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
$(document).ready(function(){
    $('#concepto1').val(<?= $idConcepto ?>);
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
            identificacion:nit,
            ejecutoria:ejecutoria,
            concepto:concepto,
            juridico:juridico
            },function(data){                            
                $('#selectCuotas').show();        
                $('#error').hide('slow');
                $(".ajax_load").hide("slow");
                });        
    }    
})
     function recibo(id){
         $("#preloadminicuota").show();
         var datos      = id.split(',');
         var valorcuota     = datos[0];
         var fecha          = datos[1];
         var saldocapital   = datos[2];
         var saldofinal     = datos[3];
         var cuota          = datos[4];
         var intereses      = datos[5];
         var interesesCuota = datos[6];
         var acuerdo        = <?= $numAcuerdo ?>;
         var nit            = <?= $nit ?>;
         var razonsocial    = '<?= $razon ?>';
         var url = "<?= base_url('index.php/acuerdodepago/generarrecibodepagocuotainicial') ?>";
            $('#load').load(url,{nit : nit,
                razonsocial : razonsocial, 
                valorcuota : valorcuota,
                fecha : fecha, 
                saldocapital : saldocapital,
                saldofinal : saldofinal,
                cuota : cuota,
                acuerdo:acuerdo,
                intereses : intereses,
                interesesCuota : interesesCuota},function(data){                            
                 $(".preloadminicuota").hide();
                 $(".generar" ).show();
                });
     }

    
    function datosActivo(){
    $("#preloadminicuota").show();
        $(".ajax_load").show("slow");
        var url     = "<?= base_url('index.php/acuerdodepago/guardarAcuerdo')?>";  
        $('#dato_valida').val('0');
        $(".generar" ).show();
        $.post(url,$('form').serialize({}))
        .done(function(msg){
            $(".ajax_load").hide();
            $("#preloadminicuota").hide();
            $("#aprobar").attr('disabled',true);
            $("#no_aprobar").attr('disabled',true);
            $("#exportar").attr('disabled',true);
            $("#calcularidentificacion").attr('disabled',true);
        })
        .fail(function(msg){
            $(".ajax_load").hide();
            $("#preloadminicuota").show(); 
        });
    };
    
     function datosInactivo(){
        $(".ajax_load").show("slow");
        var url     = "<?= base_url('index.php/acuerdodepago/guardarAcuerdo')?>";  
        $('#dato_valida').val('2');
        $.post(url,$('form').serialize({}))
        .done(function(msg){
            window.location = '<?= base_url('index.php/acuerdodepago/gestionarAcuerdo')?>'
        })
        .fail(function(msg){
            $(".ajax_load").hide();
        });
    };

//------------------------------------------------------------------------------
//SE CALCULA LA PROYECCION A LA FACILIDAD POR NIT.    
//------------------------------------------------------------------------------ 
$('#concepto1').change(function(){    
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
            identificacion:nit,
            ejecutoria:ejecutoria,
            concepto:concepto,
            juridico:juridico
            },function(data){                            
                $('#selectCuotas').show();        
                $('#error').hide('slow');
                $(".ajax_load").hide("slow");
                });        
    }    
});



$('#calcularidentificacion').click(function(){
    $(".ajax_load").show("slow");
    $('#alertaidentificacion').remove();
    $('#vfinanciar').attr('readonly',true);
    $('#cuotainicial').attr('readonly',true);
    $('#concepto').attr('readonly',true);
    $('#vcuota').attr('readonly',true);
    $('#consultar').attr('disabled',true);
    $('#restaurar').attr('disabled',true);
    $('#aprobar').attr('disabled',false);
    $('#no_aprobar').attr('disabled',false);
    $('#exportar').attr('disabled',false);
    var ejecutoria  = $('#ejecutoria').val();
    var nit         = $('#identificacion').val();
    var concepto    = $('#concepto1').val(); 
    var cuotas      = $('#cuotasiden').val();
    var cuotas      = $('#cuotasiden').val();
    var liquidacion = '<?= $liquidacion ?>';
    var url         = "<?= base_url('index.php/acuerdodepago/tableacuerdopago') ?>";
    var alerta      = "POR FAVOR INGRESAR LOS DATOS";
    var juridico    = '4';
    
    if((ejecutoria != "" || nit != "") && concepto != "" && cuotas != ""){        
            $('#cuerpotabla').load(url, {cuotas : cuotas,juridico : juridico,ejecutoria : ejecutoria, nit : nit, concepto : concepto, liquidacion:liquidacion
            },function(data){
               $(".ajax_load").hide(); 
               $("#datos_envio").val( $("<div>").append( $("#styletable").eq(0).clone()).html());
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

    
    
    $("#exportar").click(function() {
        $(".ajax_load").show("slow");
        var url     = "<?= base_url('index.php/acuerdodepago/exportaExcel')?>"; 
        $("#datos_envio").val( $("<div>").append( $("#cuerpotabla").eq(0).clone()).html());
        $("#form").attr("action", url);       
        $("#form").submit();
        $(".ajax_load").hide();
    });    

//  Valores Numericos
    $('#cuotainicial').change(function() {
        if (isNaN($(this).val())) {
            $(this).val('');
        }
    })  

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
