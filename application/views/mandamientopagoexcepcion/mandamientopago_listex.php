
<style>
    div.preload{
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        background-color: white;
        opacity: 0.8;
        z-index: 10000;
    }
    div img.load{
        position: absolute;
        left: 50%;
        top: 50%;
        margin-left: -64px;
        margin-top: -64px;
        z-index: 15000;
    }
</style>
<?php
if (isset($message)){
    echo $message;
   }

if($_POST){
    @$tipo = $_POST['tipoproceso'];
    @$numproceso = $_POST['numproceso'];
    @$creado = $_POST['usuarios'];
    @$referenciado = $_POST['asignado'];
    @$fechamandamiento = $_POST['fechacreacion'];
    @$estado = $_POST['estado_id'];
} else {
    $tipo = "";
    $numproceso = "";
    $creado = "";
    $referenciado = "";
    $fechamandamiento = "";
    $estado = "";
}
    
if ($perfil == 41){
    $perfiles = 'Secretario';
    $icono = "fa-user";
}else if ($perfil == 42){
    $perfiles = 'Coordinador';
    $icono = "fa-users";
}else if ($perfil == 43){
    $perfiles = 'Abogado';
    $icono = "fa-male";
}
?>

<h1>Consultar Resoluci&oacute;n ordenando seguir adelante</h1>
<div class="center-form-large-20">

    <div class="alert alert-info">
    <h4 align="left">
        <i class="fa <?php echo @$icono; ?> "> <?php echo @$perfiles ?></i> 
    </h4>
    </div>
<br>
<br>
<table cellspacing="0" cellspading="0" border="0" align="center">
    <tr>
        <td>
        <div class="span3">
        <?php
                  echo form_label('Tipo proceso', 'tipoproceso');
                  /*foreach($tipo as $row) {
                      $select[$row->CODPROCESO] = $row->NOMBREPROCESO;
                  }*/
                  $select=array(
                    "-- Seleccione --",
                    "Acuerdo de Pago",
                    "Liquidacion",
                    "Resolucion");
                  echo form_dropdown('tipoproceso', $select, $tipo,'id="tipoproceso" class="chosen" placeholder="-- Seleccione --" ');

                  echo form_error('tipoproceso','<div>','</div>');
        ?>
        </div>
        </td>
        <td>
        <div class="span3">
        <?php     

            echo form_label('Numero proceso', 'numproceso');
            $datanumproceso = array(
                       'name'        => 'numproceso',
                       'id'          => 'numproceso',
                       'value'       => $numproceso,
                       'maxlength'   => '10',
                     );
            echo form_input($datanumproceso);
            echo form_error('numproceso','<div>','</div>');
        ?>
        </div>
        </td>
        <td>
        <div class="span3">
        <?php
                  $select = array();
                  echo form_label('Creado por', 'usuarios');
                  $select[''] = "-- Seleccione --";
                  foreach($usuarios as $row) {
                      $select[$row->IDUSUARIO] = $row->NOMBRES." ".$row->APELLIDOS;
                   }
                    echo form_dropdown('usuarios', $select,$creado,'id="usuarios" class="chosen" placeholder="-- Seleccione --" ');

                    echo form_error('usuarios','<div>','</div>');
        ?>
        </div>
        </td>
    </tr>
    <tr>
        <td>
        <div class="span3">
        <?php
                  $select = array();
                  echo form_label('Asignado a', 'asignado');
                  $select[''] = "-- Seleccione --";
                  foreach($asignado as $row) {
                      $select[$row->IDUSUARIO] = $row->NOMBRES." ".$row->APELLIDOS;
                   }
                    echo form_dropdown('asignado', $select,$referenciado,'id="asignado" class="chosen" placeholder="-- Seleccione --" ');

                    echo form_error('asignado','<div>','</div>');
        ?>
        </div>
        </td>
        <td>
        <div class="span3">    
        <?php
            echo form_label('Fecha Creacion mandamiento', 'fechacreacion');
            $dataFecha = array(
                      'name'        => 'fechacreacion',
                      'id'          => 'fechacreacion',
                      'value'       => $fechamandamiento,
                      'maxlength'   => '12',
                      'readonly'    => 'readonly',
                      'size'        => '15'
                    );

           echo form_input($dataFecha);
           echo form_error('fechacreacion','<div>','</div>');
           
           echo form_hidden('fechacreaciond',date("d/m/Y"));
        ?>
        </div>
        </td>
        <td>
        <div class="span3">
        <?php
            echo form_label('Estado&nbsp;&nbsp;<span class="required"><font color="red"><b>*</b></font></span>', 'estado_id');
            $selecte[''] = "-- Seleccione --";
            foreach($estados as $row) {
                $selecte[$row->NOMBREESTADO] = $row->NOMBREESTADO;
            }
          echo form_dropdown('estado_id', $selecte,$estado,'id="estado_id" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('estado_id','<div>','</div>');
        ?>
        </div>    
        </td>
    </tr>
    <tr>
        <td align="right" colspan="3">
        <div class="controls controls-row">
            <?php 
            $data = array(
                   'name' => 'button',
                   'id' => 'buscar',
                   'value' => 'Consultar',
                   'type' => 'button',
                   'content' => '<i class="fa fa-search"></i> Buscar',
                   'class' => 'buscar btn btn-success'
                   );

            echo form_button($data)."&nbsp;&nbsp;";
            $dataLimpiar = array(
                   'name' => 'button',
                   'id' => 'limpiar',
                   'value' => 'Limpiar',
                   'type' => 'button',
                   'content' => '<i class="fa fa-trash-o"></i> Limpiar',
                   'class' => 'buscar btn btn-success'
                   );

            echo form_button($dataLimpiar)."&nbsp;&nbsp;";
            ?>
        </div>
        </td>
    </tr>
</table>
<?php //echo form_close(); ?>
</div>

<br><br>
<table id="tabla_resoluciones"> <!-- class="dataTables_wrapper dataTable" -->
 <thead>
    <tr class="odd sorting_1">
<!--     <th>Editar</th>-->
     <th>Ver</th>
     <th>Imprimir</th>
     <th>Subir doc.</th>     
     <th>Tipo proceso</th>
     <th>N. Proceso</th>
     <th>Creado por</th>
     <th>Asignado a</th>
     <th>F. Creacion</th>
     <th>Ver Estado</th>
   </tr>
 </thead>
 <tbody id="tblData">
     <div id="ajax_load" class="ajax_load" style="display: none">
        <div class="preload" id="preload" >
            <img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
        </div>
    </div>
 </tbody> 
</table>
<form id="frmTmp" method="POST">
    <input type="hidden" id="clave" name="clave">
    <input type="hidden" id="plantilla" name="plantilla">
    <input type="hidden" id="mandamiento" name="mandamiento">
    <input type="hidden" id="fiscalizacion" name="fiscalizacion">
    <input type="hidden" id="gestion_cobro" name="gestion_cobro">
    <input type="hidden" id="estado_gestion" name="estado_gestion">
    <input type="hidden" id="nit_empresa" name="nit_empresa">
    <input type="hidden" id="nit" name="nit">
    <input type="hidden" id="perfil" name="perfil" value="<?php echo $perfil ?>">
</form>
<div class="modal hide fade in" id="modal" style="display: none; width: 60%; margin-left: -30%;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Visor de Resoluciones</h4>
        <div id="subtitle" name="subtitle"></div>
      </div>
      <div class="modal-body conn">
        Cargando datos...
      </div>
      <div class="modal-footer">
          <a href="#" class="btn btn-default" data-dismiss="modal">Cerrar</a>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal hide fade in" id="upload" style="display: none; width: 60%; margin-left: -30%;">
  <div class="modal-dialog upload-dialog">
    <div class="modal-content upload-content">
      <div class="modal-header upload -header">
        <button class="close" aria-hidden="true" data-dismiss="modal" type="button">×</button>
        <h4 class="modal-title upload-tittle">Cargar PDF</h4>
      </div>
      <div class="modal-body upload-body">
          Cargando datos...
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal hide fade in" id="pdf" style="display: none; width: 60%; margin-left: -30%;">
  <div class="modal-dialog pdf-dialog">
    <div class="modal-content pdf-content">
      <div class="modal-header pdf-header">
        <button class="close" aria-hidden="true" data-dismiss="modal" type="button">×</button>
        <h4 class="modal-title pdf-tittle">Imprimir Resoluci&oacute;n Seguir Adelante</h4>
         <div id="subtitle pdf" name="subtitle pdf"></div>
      </div>
      <div class="modal-body pdf-conn">
        Cargando datos...
      </div>
      <div class="modal-footer pdf-footer">
          
        <?php 
            $dataPDF = array(
                   'name' => 'btnpdf',
                   'id' => 'btnpdf',
                   'value' => 'Generar PDF',
                   'type' => 'button',
                   'content' => '<i class="fa fa-print"></i> Imprimir',
                   'class' => 'btn btn-success'
                   );
            echo form_button($dataPDF)."&nbsp;&nbsp;";
        ?>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal hide fade in" id="viewGestion" style="display: none; width: 60%; margin-left: -30%;">
  <div class="viewGestion-dialog">
    <div class="viewGestion-content">
      <div class="viewGestion-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <div id="subtitle" name="subtitle"></div>
      </div>
      <div class="viewGestion-body conn">
        Cargando datos...
      </div>
      <div align="right" class="viewGestion-footer">
          <a href="#" class="btn btn-success" data-dismiss="modal"><i class="fa fa-trash-o"></i> Cerrar</a>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script type="text/javascript" language="javascript" charset="utf-8">
//generación de la tabla mediante json
$(document).ready(function() {
    $('#fechanotificacion').datepicker({dateFormat: "dd/mm/y"});
    $('#fechaonbase').datepicker({dateFormat: "dd/mm/y"});
});   
    $(function() {
        $('#modal').on('hidden', function() {
            $('.conn').val("");
            $('#modal').modal('hide').removeData();
        });
    });
    
    $(function() {
        $('#modal').on('show', function() {
            var clave = $('#clave').val();
            var plantilla = $('#plantilla').val();
            var gestion = $('#gestion').val();
            $('#subtitle').html("<h6>C&oacute;digo Fiscalizaci&oacute;n:  "+clave+"</h6>");
            if (plantilla!="") {
                if (gestion == 208){
                    $('#btnpdf').hide();
                }
                $(".ajax_load").show("slow");
                $.ajax({
                    type: "POST",
                    url: "loadRes",
                    data: { plantilla: plantilla,plantilla:plantilla,gestion:gestion },
                    success: function(data){
                        $('.conn').html(data);
                        $(".ajax_load").hide("slow");
                    }
                });
            } else {
                $('.conn').html("No hay Datos");
            }
        });
        $('#upload').on('show', function() {
            var clave = $('#clave').val();
            $('#proceso').val(clave);
        });
    });    
    
    $('.print').click(function(){
       var clave = $(this).attr('clave');
       var plantilla = $(this).attr('plantilla');
       var gestion = $('#gestion').val();
       $('#clave').val(clave);
       $('#plantilla').val(plantilla);
       $('#gestion').val(gestion);
    });
        
    $(function() {
        $('#viewGestion').on('show', function() {
            var clave = $('#clave').val();
            var estado_gestion = $('#estado_gestion').val();
            $('#subtitle').html("<h6>C&oacute;digo Mandamiento:  "+clave+"</h6>");
            if (clave!="") {
                $(".ajax_load").show("slow");
                $.ajax({
                    type: "POST",
                    url: "respuestaRes",
                    data: { estado_gestion: estado_gestion },
                    success: function(data){
                        $('.conn').html(data);
                        $(".ajax_load").hide("slow");
                    }
                });
            } else {
                $('.conn').html("No hay Datos");
            }
        });
        $('#upload').on('show', function() {
            var clave = $('#clave').val();
            $('#proceso').val(clave);
        });
    });
   
    $('#buscar').click(function(){
        $(".ajax_load").show("slow");
        var tipoproceso = $('#tipoproceso').val();
        var numproceso = $('#numproceso').val();
        var usuarios = $('#usuarios').val();
        var asignado = $('#asignado').val();
        var fechacreacion = $('#fechacreacion').val();
        //var fechacreacion = "";
        var estado_id = $('#estado_id').val();
        var url = "<?= base_url('index.php/mandamientopago/datatableRes')?>";
        $('#tblData').load(url, {tipoproceso: tipoproceso, numproceso: numproceso, usuarios: usuarios,
            asignado: asignado, fechacreacion: fechacreacion, estado_id: estado_id},function(data){
            //alert(data);
            $(".ajax_load").hide("slow");
            });

    });

    $('#limpiar').click(function(){
        $('#tipoproceso').val("");
        $('#numproceso').val("");
        $('#usuarios').val("");
        $('#asignado').val("");
        $('#fechacreacion').val("");
        $('#estado_id').val("");

    });
    

    
    $('#tabla_resoluciones').dataTable( {
        "bJQueryUI": true,
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
            }
        }
    });
</script>