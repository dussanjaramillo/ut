
<div align='center'><h3 >AUTORIZACION FACILIDAD DE PAGO</h3>
    <br><?= @$message ?>
    <br><?= $nombreusuario ?>
    <br><?= @$cargousuario ?></div>
<br>

<table id='estiloacuerdo'>
    <thead>
        <td align='center'>Nit</td>
        <td align='center'>Razón Social</td>
        <td align='center'>Resolución</td>
        <td align='center'>Liquidación</td>
        <td align='center'>Documento</td>
    </thead>
    <tbody>
        <?php foreach($acuerdosautorizar->result_array as $resolucion){?>
        <tr>
           <td align='center'><?= $resolucion['NITEMPRESA'] ?></td> 
           <td align='center'><?= $resolucion['NOMBRE_EMPRESA'] ?></td> 
           <td align='center'><?= $resolucion['NUMERO_RESOLUCION'] ?></td> 
           <td align='center'><?= $resolucion['NUM_LIQUIDACION'] ?></td> 
           <td align="center"><input type="radio" data-toggle="modal" data-target="#modal" id="<?= $resolucion['NITEMPRESA'] ?>,<?= $resolucion['NOMBRE_EMPRESA'] ?>,<?= $resolucion['NUMERO_RESOLUCION'] ?>,<?= $resolucion['NUM_LIQUIDACION'] ?>,<?= $resolucion['COD_FISCALIZACION']?>,<?= $resolucion['COD_CONCEPTO']?>" name='rdbNit' onclick='accion(this.id)'></td>
        </tr>   
        <?php } ?>
    </tbody>
</table>
<div id='cargaarchivo'>
 <form id='cargar' action="<?= base_url('index.php/acuerdodepago/subirDocumento') ?>" method="post" enctype="multipart/form-data" >
     <input type="hidden" id="nit1" name="nit1" > 
     <input type="hidden" id="resolucion1" name="resolucion1" > 
     <input type="hidden" id="liquidacion" name="liquidacion" > 
     <input type="hidden" id="fiscalizacion" name="fiscalizacion" > 
     <input type="hidden" id="concepto" name="concepto" >
    <table id='archivo'>
        <tr>
            <td style='width: 200px'><b>Nit</b></td>
            <td id='nit' style='width: 200px'></td>
        </tr>
        <tr>
            <td style='width: 200px'><b>Razón Social</b></td>
            <td style='width: 200px' id='razon'></td>
        </tr>
        <tr>
            <td style='width: 200px'><b>Resolución</b></td>
            <td style='width: 200px' id='resolucion'></td>
        </tr>
        <tr>
            <td style='width: 200px'><b>Concepto</b></td>
            <td style='width: 200px' id='concepto'>           
                <select id="conceptos" name="conceptos" disabled>
                    <option>---Seleccione---</option>
                    <?php
                    foreach ($concepto->result_array as $conc) {?>
                        <option value="<?= $conc['COD_CPTO_FISCALIZACION'] ?>"><?= $conc['NOMBRE_CONCEPTO'] ?></option>    
                    <?php }?>
                </select>
            </td>
        </tr>
        <tr>
            <td><b>Documento</b></td>
            <td><input type="file" name="file" id="file" ></td>
        </tr>
        <tr>
            <td colspan="2" align="right"><input type="button" name="guardar" id="guardar" value="Guardar" class="btn btn-success"></td>
        </tr>
    </table>
     </form>
    <div align='center' style="display:none" id="error" class="alert alert-danger"></div>
</div>  
<div id="ajax_load" class="ajax_load" style="display: none">
        <div class="preload" id="preload" >
            <img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
        </div>
</div>
<script>       
    $('#guardar').click(function(){                        
       if($('#file').val() != ""){
            var extensiones_permitidas = new Array(".pdf"); 
            var archivo = $('#file').val();
            var extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase();
            var permitida = false;

            for (var i = 0; i < extensiones_permitidas.length; i++) {
                if (extensiones_permitidas[i] == extension) {
                permitida = true;
                break;
                }
            } 
                if (!permitida) {           
                    $('#file').val('');
                    $('#error').show();
                    $('#error').html("<font color='red'><b>Comprueba la extensi�n de los archivos a subir. \nS�lo se pueden subir archivos con extensiones: " + extensiones_permitidas.join()+"</b></font>");
                    $("#file").focus();
                }else{
                    $(".ajax_load").show("slow");                                               
                    $('#error').show();
                    $('#error').html("<font color='red'><b>Archivo Subido Correctamente</b></font>");
                    $('#cargar').submit();
                    $(".ajax_load").hide();      
                } 
     }else{
           if($('#alertadocumento').length == 0)
           $('#error').show();
           $('#error').html("<font color='red'><b>Por favor ingresar Documento</b></font>");           
           return false;
       }
    });
 
    $('#cargaarchivo').hide();    
    var oTable = $('#estiloacuerdo').dataTable({
        "bJQueryUI": true,
        "bPaginate": true,
        "oLanguage": {
            "sProcessing": "Procesando...",
            "bServerSide": true,
            "sServerMethod": "POST", 
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
            "sPaginationType": "full_numbers",
            "iDisplayLength": 25,
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            }
        }
    });
    
    function accion (id){
       var datos = id.split(',');
       var nit              = datos[0];
       var razon            = datos[1];
       var resolucion       = datos[2];       
       var liquidacion      = datos[3];    
       var fiscalizacion    = datos[4];
       var concepto         = datos[5];
       
       $('#nit').text(nit);
       $('#razon').text(razon);
       $('#resolucion').text(resolucion);
       $('#nit1').val(nit);        
       $('#liquidacion').val(liquidacion);        
       $('#fiscalizacion').val(fiscalizacion); 
       $('#resolucion1').val(resolucion);              
       $('#concepto').val(concepto);            
       $('#conceptos').val(concepto);            
       $('#cargaarchivo').dialog({
            autoOpen : true,
            title : "AUTORIZAR ACUERDO DE PAGO",
            modal : true,
            width : 600,
            close : function(){
                $('#cargaexterna').remove();                       
            }
        });
       
    }

</script>    
    