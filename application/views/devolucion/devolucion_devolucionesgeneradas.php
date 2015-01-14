<?php
if (isset($message)){
    echo $message;
   }
?>           
<div style="max-width: 686px;min-width: 1090px;padding: 40px;width: 88%;background: none repeat scroll 0 0 #F0F0F0;margin: auto;">
<ul class="nav nav-tabs">
  <h2>Consultar Devolución</h2>
</ul>
</div>
<div class="modal hide fade in" id="modal" style="display: none; width: 60%; margin-left: -30%;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Devolución</h4>
        <div id="subtitle" name="subtitle">
            <a href="<?php echo base_url();?>index.php/downloads/grant.pdf" target="_blank"></a>
        </div>
      </div>
      <div class="modal-body conn">
        Cargando datos...
      </div>
      <div class="modal-footer">
          <a href="#" class="btn btn-default" data-dismiss="modal"><i class="fa fa-minus-circle"></i> Cerrar</a>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<table id="tablaq">            
            <thead>                   
              <tr>                
                <th>Nit</th>
                <th>R. Social</th>
                <th>N. Radicado</th>
                <th>F. Radicado</th>
                <th>Motivo</th>
                <th>Concepto</th>
                <th>Informante</th>
                <th>Cargo</th>       
                <th >Seleccion</th>
              </tr>
            </thead>
            <tbody>
            <?php   
            if (@$registros != ''){
            foreach (@$registros as $value) {                
                ?>
            <tr>                
                <td align="center"><?= $value['NIT'] ?></td>
                <td align="center"><?= $value['NOMBRE_EMPRESA'] ?></td>            
                <td align="center"><?= $value['NRO_RADICACION'] ?></td>            
                <td align="center"><?= $value['FECHA_RADICACION'] ?></td>            
                <td align="center"><?= $value['NOMBRE_MOTIVO'] ?></td>
                <td align="center"><?= $value['NOMBRE_CONCEPTO'] ?></td>
                <td align="center"><?= $value['INFORMANTE'] ?></td>
                <td align="center"><?= $value['CARGO'] ?></td>
                <td align="center"><input type="radio" data-toggle="modal" data-target="#modal" id="<?= $value['COD_RESPUESTA'] ?>,<?= $value['COD_DEVOLUCION'] ?>,<?= $value['NIT'] ?>,<?= $value['NOMBRE_EMPRESA'] ?>,<?= $value['TIPO_DEVOLUCION']?>,<?= $value['VERIFICA_SOPORTES']?>" class="opcion" name='rdbNit' onclick='accion(this.id)'></td>
            </tr>
                    <?php                
                }            
              }
                ?>
        </tbody> 
</table> 
<div id="ajax_load" class="ajax_load" style="display: none">
        <div class="preload" id="preload" >
            <img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
        </div>
</div>
<form id="frmTmp" method="POST">
    <input type="hidden" id="respuesta" name="respuesta">
    <input type="hidden" id="devolucion" name="devolucion">
    <input type="hidden" id="nit" name="nit">
    <input type="hidden" id="razon" name="razon">
    <input type="hidden" id="tipo_devolucion" name="tipo_devolucion">  
    <input type="hidden" id="soportes" name="soportes">    
</form>
<script>
     $('#tablaq').dataTable({
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
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            }
        }
   });
   
   function accion (id){
       var datos = id.split(',');
       var respuesta        = datos[0];
       var devolucion       = datos[1];
       var nit              = datos[2];
       var razon            = datos[3];
       var tipo_devolucion  = datos[4];
       var soportes         = datos[5];
        $('#respuesta').val(respuesta);
        $('#devolucion').val(devolucion);
        $('#nit').val(nit);
        $('#razon').val(razon);
        $('#tipo_devolucion').val(tipo_devolucion);
        $('#soportes').val(soportes);
            if (respuesta == 1183 || respuesta == 1187 || respuesta == 1189 || respuesta == 1190 || respuesta == 1188 || respuesta == 1196 || respuesta == 1235){
                 $(".ajax_load").show("slow");                 
                 $.ajax({
                    type: "POST",
                    url: "verificar_comunicacion",
                    data: {respuesta: respuesta, nit: nit, devolucion: devolucion, razon: razon, 
                    tipo_devolucion: tipo_devolucion,soportes: soportes},
                    success: 
                        function(data){
                        $('.conn').html(data);
                        $(".ajax_load").hide("slow");
                    }
                });      
            }else if (respuesta == 1185 || respuesta == 1184 || respuesta == 1188 || respuesta == 1192){
                $(".ajax_load").show("slow");
                $("#frmTmp").attr("action", "<?= base_url('index.php/devolucion/crear_documentos')?>");
                $('#frmTmp').submit();
            }else if (respuesta == 1186 || respuesta == 1199 || respuesta == 1254 || respuesta == 1198){
                $(".ajax_load").show("slow");
                $("#frmTmp").attr("action", "<?= base_url('index.php/devolucion/editar_documentos')?>");
                $('#frmTmp').submit();
            }else if (respuesta == 1200 || respuesta == 1205){
                 $('.conn').html("<b>Devolución Enviada a Dirección General</b>");
            }else if (respuesta == 1191 || respuesta == 1193 || respuesta == 1197){
                 $('.conn').html("<b>Proceso Cerrado</b>");
            }else if (respuesta == 1192){
                $(".ajax_load").show("slow");
                $("#frmTmp").attr("action", "<?= base_url('index.php/devolucion/editar_recurso')?>");
                $('#frmTmp').submit();
            }
   }      
   
   $(function() {
        $('#modal').on('hidden', function() {
            $('.conn').val("");
            $('#modal').modal('hide').removeData();
        });
    });
</script>    
    