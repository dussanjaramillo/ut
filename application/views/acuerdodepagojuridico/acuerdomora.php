
<div id="documentocancelacionuno"></div>
<div id="documentocancelaciondos"></div>
<div align="center"><h3><!--Menor a Dos Meses-->Incumplimiento una cuota</h3></div>
<br>
<table class="mora">
    <thead>
        <th>Cod. Acuerdo de Pago</th>
        <th>Cuota</th>
        <th>No Identificación</th>
        <th>Ejecutoria</th>
        <th>No. Proceso</th>
        <th>Fecha límite de pago</th>
        <th>Días de Atraso</th>
        <th>Documento</th>        
    </thead>
    <tbody>
        <?php 

        foreach ($acuerdomoramenor->result_array as $moramenor) {
            ?>
        <tr>
            <td><?= $moramenor['NRO_ACUERDOPAGO']?></td>
            <td><?= $moramenor['PROYACUPAG_NUMCUOTA']?></td>
            <td><?= $moramenor['NITEMPRESA']?></td>
            <td><?= $moramenor['RAZON_SOCIAL']?></td>
            <td><?= $moramenor['COD_PROCESO_COACTIVO']?></td>
            <td><?= $moramenor['PROYACUPAG_FECHALIMPAGO']?></td>
            <td><?= $moramenor['DIFERENCIA']?></td>
            <td><button acuerdo="<?= $moramenor['NRO_ACUERDOPAGO']?>" cuota="<?= $moramenor['PROYACUPAG_NUMCUOTA']?>" type="button" class="atraso btn btn-success">Documento</button></td>
        </tr>    
            <?php
        }
        ?>
    </tbody>
</table>
<br>
<br>
<div align="center"><h3>Mayor a Dos Meses</h3></div>
<table id="estilotabla">
   <thead>
        <th>Cod. Acuerdo de Pago</th>        
        <th>Cuota</th>
        <th>No Identificación</th>
        <th>Ejecutoria</th>
        <th>No. Proceso</th>
        <th>Fecha límite de pago</th>
        <th>Días de Atraso</th>
        <th align='center'>Auto</th>        
    </thead>
    <tbody>
        <?php echo count($acuerdomoramayor);
        if(count($acuerdomoramayor) >=2):
        foreach ($acuerdomoramayor->result_array as $moramayor) {
            ?>
        <tr>
            <td><?= $moramayor['NRO_ACUERDOPAGO']?></td>
            <td><?= $moramayor['PROYACUPAG_NUMCUOTA']?></td>
            <td><?= $moramayor['NITEMPRESA']?></td>
            <td><?= $moramayor['RAZON_SOCIAL']?></td>
            <td><?= $moramayor['COD_PROCESO_COACTIVO']?></td>
            <td><?= $moramayor['PROYACUPAG_FECHALIMPAGO']?></td>
            <td><?= $moramayor['DIFERENCIA']?></td>
            <td align="center"><input type="radio" data-toggle="modal" data-target="#modal" id="<?= $moramayor['NITEMPRESA'] ?>,<?= $moramayor['RAZON_SOCIAL'] ?>,<?= $moramayor['COD_PROCESO_COACTIVO'] ?>,<?= $moramayor['NRO_ACUERDOPAGO'] ?>" name='rdbNit' onclick='accion(this.id)'></td>            
        </tr>    
            <?php
        }
    endif;
        ?>
    </tbody>
</table>

<div id="dialogo">
    <form action="<?= base_url('index.php/acuerdodepagojuridico/recursojuridico') ?>" method="post" enctype="multipart/form-data" >
         <div id="alertaobservacion" align="center"></div>
     <div id="alertacarga" align="center"></div>
        <table>
        <tr>
            <td style="width: 250px"><b>Acuerdo No</b></td>
            <td class="acuerdo">
                
            </td>
        </tr>
        <tr>
            <td style="width: 250px"><b>Nit</b></td>
            <td class="nit"></td>
        </tr>
        <tr>
            <td style="width: 250px"><b>Empresa</b></td>
            <td class="empresa"></td>
        </tr>
        <tr>
            <td style="width: 250px"><b>Resolución</b></td>
            <td class="resolucion"></td>
        </tr>
        <tr>
            <td style="width: 250px"><b>Liquidación</b></td>
            <td class="liquidacion"></td>
        </tr>
        <tr>
            <td><b>Documento</b></td>
            <td><input type="file" name="archivo" id="file" ></td>
        </tr>
        <tr>
            <td><b>Observación</b></td>
            <td><textarea id="observacion" style="width: 400px; height: 300px" name="observacion"></textarea></td> 
        </tr>
        <tr>
            <td align="right" colspan="2"><input type="submit" id="guardar" value="Guardar" class="btn btn-success"></td>
        </tr>
    </table>
        <input type="text" id="acuerdo1" name="acuerdo">
        <input type="hidden" id="nit1"  name="nit">
        <input type="hidden" id="empresa1" name="empresa">
        <input type="hidden" id="cuota" name="cuota">        
     </form>
</div>
   
<div id="editardocumentoresolucion" data-keyboard="false"  class="modal hide fade modal-body">
    <textarea id="editar"></textarea><br>
    <button type="button" id="guardarresolucion" class="btn btn-success">Guardar</button>
</div>  
<div id="atraso30">
    
</div>    

<form id="frmTmp" method="POST">
    <input type="hidden" id="nit" name="nit">
    <input type="hidden" id="razon" name="razon">
    <input type="hidden" id="acuerdo" name="acuerdo">    
    <input type="hidden" id="cod_coactivo" name="cod_coactivo">    
</form>
<div id="ajax_load" class="ajax_load" style="display: none">
        <div class="preload" id="preload" >
            <img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
        </div>
</div>
<script>
    function accion (id){
        var datos           = id.split(',');
        var nit             = datos[0];
        var razon           = datos[1];
        var cod_coactivo    = datos[2];
        var acuerdo         = datos[3];
        $(".ajax_load").show("slow");
        $("#nit").val(nit);
        $("#acuerdo").val(acuerdo);
        $("#razon").val(razon);
        $("#cod_coactivo").val(cod_coactivo);            
        $("#frmTmp").attr("action", "<?= base_url('index.php/acuerdopagojuridicodoc/Crear_AutoMotivado')?>");
        $('#frmTmp').submit();          
    }            
    
    $('.atraso').click(function(){
        $(".ajax_load").show("slow");
        var nit = <?= $nit ?>;
        var cod_coactivo = '<?= $cod_coactivo ?>';
        $('#atraso30 *').remove();
        
        var cuota = $(this).attr('cuota');
        var acuerdo = $(this).attr('acuerdo');
        var url = "<?= base_url('index.php/acuerdodepagojuridico/acuerdoenviodocumentocancelacion30') ?>";
        var titulo = "Acuerdo "+acuerdo+" y Número de cuota "+cuota+" atraso menor a dos meses";
        
        $.post(url,{acuerdo : acuerdo},function(data){
            $('#atraso30').append(data);
            $(".ajax_load").hide("slow");
        })
        
        $('#atraso30').dialog({
            autoOpen : true,
            width : 1200,
            title : "Incumplimiento de Acuerdo de Pago",
            modal : true,
            buttons:{
                "imprimir":{
                  text:'Imprimir',
                  class:'btn btn-success',
                  click : function(){
                         $(".ajax_load").show("slow");                 
                        $.ajax({
                           type: "POST",
                           url: "<?= base_url('index.php/acuerdodepagojuridico/guardar_verificacion')?>",
                           data: {nit:nit,acuerdo:acuerdo,cod_coactivo:cod_coactivo,tipo:'voluntad_pago'},
                           success: 
                               function(data){
                               $('.conn').html(data);                               
                               $(".ajax_load").hide("slow");
                               $('#atraso30').dialog('close');
                               window.location = "<?= base_url('index.php/bandejaunificada/procesos')?>";                               
                           }
                       }); 
                    }
                },
                "salir":{
                  text:'Salir',
                  class:'btn btn-warning',
                  click: function(){
                        $(".ajax_load").show("slow");
                        location.reload();
                        //jQuery('#recibo').dialog('close');                        
                  }
                }
            }
        });
        
    });
    
    
    
    $('#editardocumentoresolucion').hide();
    
        tinymce.init({
        language: 'es',
        selector: "textarea#editar",
        theme: "modern",
        plugins: [
            "advlist autolink lists link  charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
                //    "insertdatetime media nonbreaking save table contextmenu directionality",
                  //  "emoticons template paste textcolor moxiemanager"
        ],
        toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        toolbar2: "print preview media | forecolor backcolor emoticons",
        image_advtab: true,
        templates: [
            {title: 'Test template 1', content: '<b>Test 1</b>'},
            {title: 'Test template 2', content: '<em>Test 2</em>'}
        ],
        autosave_ask_before_unload: false
    });
    
    
    $('#guardarresolucion').click(function(){        
        var documento = tinymce.get('editar').getContent();
        var acuerdo = $(this).attr('acuerdo');        
        var url = "<?= base_url('index.php/acuerdodepagojuridico/guardadocumentoresolucion') ?>"; 
        $.post(url,{documento : documento,acuerdo : acuerdo},function(data){
//             $('#editardocumentoresolucion').dialog('close');
        });
    });
    
    
    

    $('#dialogo').hide();
         var table =  $('.mora').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
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
    "fnInfoCallback": null
            }
    });
    
var oTable =  $('#estilotabla').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
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
    "fnInfoCallback": null
            }
    });
    

        
  
</script>