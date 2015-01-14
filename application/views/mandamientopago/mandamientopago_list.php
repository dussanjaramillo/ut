<?php
/**
 * mandamientopago_list -- Vista para listar los mandamientos de pago.
 *
 * @author		Human Tem Technology QA
 * @author		Sergio Amaya - saas02@gmail.com
 * @version		1.0
 * @since		Febrero de 2014
 */
?>
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
?>
<!-- <script language="javascript" src="../../js/dhtmlgoodies_calendar.js?random=20060118" type="text/javascript"></script>
<link href="../../css/dhtmlgoodies_calendar.css" rel="stylesheet" type="text/css"> -->
<?php

$attributes = array('id' => 'pagoFrm', 'name' => 'pagoFrm', 'method' => 'POST');
//echo form_open(base_url()."index.php/mandamientopago/manage",$attributes); 
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
    $fechamandamiento = "";//date("d/m/Y");
    $estado = "";
}
$datosempresa = array(
                   'nit_empresa'  => $post['nit'],
                   'fiscalizacion'=> $post['cod_fiscalizacion'], 
                   'perfiles'=> $post['perfiles'], 
               );
$this->session->set_userdata('datosempresa',$datosempresa);
if ($post['perfiles'] == 41){
    $perfil = 'Secretario';
    $icono = "fa-user";
}else if ($post['perfiles'] == 42){
    $perfil = 'Coordinador';
    $icono = "fa-users";
}else if ($post['perfiles'] == 43){
    $perfil = 'Abogado';
    $icono = "fa-male";
}
;
?>   
    <h4 align="left">
        <i class="fa <?php echo $icono; ?> "> <?php echo $perfil ?></i> 
    </h4>

<h1>Consultar Mandamiento de pago</h1>
<span class="glyphicon glyphicon-search"> </span>
<div class="center-form-large-20">

<div class="controls controls-row">

<br>
<br>
<table cellspacing="0" cellspading="0" border="0" align="center">
    <tr>
        <td>
        <div class="span3">
        <?php
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
            echo form_label('Estado', 'estado_id');
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
            $dataCrear = array(
                   'name' => 'button',
                   'id' => 'add-button',
                   'value' => 'Crear',
                   'type' => 'button',
                   'onclick' => 'window.location=\''.base_url().'index.php/mandamientopago/add\';',
                   'content' => '<i class="fa fa-save"></i> Crear',
                   'class' => 'btn btn-success'
                   );

            echo form_button($dataCrear);
            ?>
        </div>
        </td>
    </tr>
</table>
<?php //echo form_close(); ?>
</div>

<br><br>
<table id="tabla_mandamientos">  
 <thead>
    <tr class="odd sorting_1">
     <th>Mandamiento</th>
     <th>Eliminar</th>
     <th>Editar</th>
     <th>Ver</th>
     <th>Imprimir</th>
     <th>Subir doc.</th>     
     <th>Gestion</th>
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
<form id="frmTmp" method="POST" action="<?php echo base_url('index.php/mandamientopago/edit')?>">
    <input type="hidden" id="clave" name="clave">
    <input type="hidden" id="plantilla" name="plantilla">
    <input type="hidden" id="mandamiento" name="mandamiento">
    <input type="hidden" id="fiscalizacion" name="fiscalizacion">
    <input type="hidden" id="gestion_cobro" name="gestion_cobro">
    <input type="hidden" id="estado_gestion" name="estado_gestion">
    <input type="hidden" id="nit_empresa" name="nit_empresa">
</form>
<div class="modal hide fade in" id="modal" style="display: none; width: 60%; margin-left: -30%;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Visor de Mandamientos de Pago</h4>
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
        <h4 class="modal-title pdf-tittle">Imprimir Mandamientos de Pago</h4>
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
    $('#fechacreacion').datepicker({dateFormat: "dd/mm/yy"});
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
            $('#subtitle').html("<h6>C&oacute;digo Fiscalizaci&oacute;n:  "+clave+"</h6>");
            if (plantilla!="") {
                $(".ajax_load").show("slow");
                $.ajax({
                    type: "POST",
                    url: "load",
                    data: { plantilla: plantilla },
                    success: function(data){
                        $('.conn').html(data);
                        $(".ajax_load").hide("slow");
                    }
                });
            } else {
                $('.conn').html("No hay Datos");
            }
            //$('.conn').html("PDF para el ID "+clave+" "+plantilla+"<br><br><br>");
        });
        $('#upload').on('show', function() {
            var clave = $('#clave').val();
            $('#proceso').val(clave);
        });
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
                    url: "respuesta",
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
    
    
        $(function() {
        $('#pdf').on('show', function() {
            var clave = $('#clave').val();
            var plantilla = $('#plantilla').val();
            $('#subtitle').html("<h6>C&oacute;digo Fiscalizaci&oacute;n:  "+clave+"</h6>");
            if (plantilla!="") {
                $(".ajax_load").show("slow");
                $.ajax({                    
                    type: "POST",
                    url: "loadRes",
                    data: { plantilla: plantilla },
                    success: function(data){
                        $('#mandamiento').val(data);
                        $('.pdf-conn').html(data);
                        $(".ajax_load").hide("slow");
                    }
                });
            } else {
                $('.pdf-conn').html("No hay Datos");
            }
            //$('.conn').html("PDF para el ID "+clave+" "+plantilla+"<br><br><br>");
        });
        $('#upload').on('show', function() {
            var clave = $('#clave').val();
            $('#proceso').val(clave);
        });
    });
    
} );


    $('#buscar').click(function(){
        $(".ajax_load").show("slow");
        var tipoproceso = $('#tipoproceso').val();
        var numproceso = $('#numproceso').val();
        var usuarios = $('#usuarios').val();
        var asignado = $('#asignado').val();
        var fechacreacion = $('#fechacreacion').val();
        //var fechacreacion = "";
        var estado_id = $('#estado_id').val();
        var url = "<?= base_url('index.php/mandamientopago/datatable')?>";
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

    
    $('#tabla_mandamientos').dataTable( {
        bProcessing: true,
        bjQueryUI : true,
        sPaginationType: "full_numbers",
        iDisplayStart : 20,
        //bServerSide: true,
        //sPaginationType: "bootstrap",
        bPaginate :  true
    });
    
     $('#btnpdf').click(function() {
        $('#frmTmp').attr("action", "pdf");
        $('#frmTmp').submit();
        $(".ajax_load").hide("slow");
        $('#frmTmp').attr("action", "add");
    });
</script> 