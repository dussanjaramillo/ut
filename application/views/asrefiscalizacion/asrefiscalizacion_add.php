<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<link href="/css/bootstrap.css" rel="stylesheet">
<?php 
if (isset($message)){
    echo $message;
   }
?>
<div class="center-form-large">
        <?php     

        echo form_open(current_url()); ?>
        <?php echo $custom_error; ?>
        <!--<?php //echo form_hidden('id',$result->CODEMPRESA) ?>-->
        
       
        <h2>Asignar - Reasignar Manualmente Una Fiscalizacion</h2>
        <div class="controls controls-row">
        <div class="span3">
        <?php     

           echo form_label('NIT<span class="required">*</span>', 'nit');
           $data = array(
                      'name'        => 'nit',
                      'id'          => 'nit',
                      'value'       => '',
                      'maxlength'   => '12',
                      'class'       => 'span3',
                      'readonly'    => 'readonly'
                    );

           echo form_input($data);
           echo form_error('nit','<div>','</div>');
        ?>
        </div>
        <div class="span3">
        <?php     

           echo form_label('Ciudad<span class="required">*</span>', 'ciudad');
           $data = array(
                      'name'        => 'ciudad',
                      'id'          => 'ciudad',
                      'value'       => '',
                      'maxlength'   => '22',
                      'class'       => 'span3',
                      'readonly'    => 'readonly'
                    );

           echo form_input($data);
           echo form_error('ciudad','<div>','</div>');
        ?>
        </div>
        <div class="controls controls-row">
        <div class="span3">
        <?php     

           echo form_label('Razón Social<span class="required">*</span>', 'razonsocial');
           $data = array(
                      'name'        => 'razonsocial',
                      'id'          => 'razonsocial',
                      'value'       => '',
                      'maxlength'   => '12',
                      'class'       => 'span3',
                      'readonly'    => 'readonly'
                    );

           echo form_input($data);
           echo form_error('razonsocial','<div>','</div>');
        ?>
        </div>
        <div class="span3">
        <?php     

           echo form_label('Dirección<span class="required">*</span>', 'direccion');
           $data = array(
                      'name'        => 'direccion',
                      'id'          => 'direccion',
                      'value'       => '',
                      'maxlength'   => '100',
                      'class'       => 'span3',
                      'readonly'    => 'readonly'
                    );

           echo form_input($data);
           echo form_error('direccion','<div>','</div>');
        ?>
        </div>
        <div class="controls controls-row">
        <div class="span3">
        <?php     

           echo form_label('CIIU<span class="required">*</span>', 'ciiu');
           $data = array(
                      'name'        => 'ciiu',
                      'id'          => 'ciiu',
                      'value'       => '',
                      'maxlength'   => '12',
                      'class'       => 'span3',
                      'readonly'    => 'readonly'
                      
                    );

           echo form_input($data);
           echo form_error('ciiu','<div>','</div>');
        ?>
        </div>
        <div class="span3">
        <?php     

           echo form_label('Empresa<span class="required">*</span>', 'empresa');
           $data = array(
                      'name'        => 'empresa',
                      'id'          => 'empresa',
                      'value'       => '',
                      'maxlength'   => '12',
                      'class'       => 'span3',
                      'readonly'    => 'readonly'
                    );

           echo form_input($data);
           echo form_error('empresa','<div>','</div>');
        ?>
        </div>
        <div class="controls controls-row">
        <div class="span3">
          <?php
         echo form_label('Regional<span class="required">*</span>', 'regional_id');  
               $selectr = array(
                            '' => 'Seleccione...'
                        );
              foreach($regional as $row) {
                  $selectr[$row->COD_REGIONAL] = $row->NOMBRE_REGIONAL;
               }
          echo form_dropdown('regional_id', $selectr,'','id="regional_id" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('regional_id','<div>','</div>');
        ?>
        </div>
        <div class="span3">
        <?php
          echo form_label('Fiscalizador<span class="required">*</span>', 'fiscalizadorr');                               
          //foreach($fiscalizadores as $row) {
              //$selectf[$row->IDUSUARIO] = $row->NOMBRES.' '.$row->APELLIDOS;
              
            //}
            //echo form_dropdown('fiscalizador', $selectf,'','id="fiscalizador" class="chosen" placeholder="seleccione..." ');

            
            //echo form_error('fiscalizador','<div>','</div>');
        ?>
        <select name="fiscalizador" id="fiscalizador">
            <option value="">Seleccione...</option>
        </select>
        </div>
        <div class="controls controls-row">
        <div class="span3">
        <?php
          echo form_label('Motivo Reasignacion<span class="required">*</span>', 'motivo');                               
          foreach($motivos as $row) {
              $selectm[$row->COD_MOTIVO_REASIGNACION] = $row->NOMBRE_MOTIVO;
           }
            $js = 'id="motivo" onclick="seleccion();"';
            echo form_dropdown('motivo', $selectm,'id="motivo" class="chosen" placeholder="seleccione..." ',$js);

 
            echo form_error('motivo','<div>','</div>');
        ?>
        </div>
        <div class="controls controls-row"></div>
        <div class="controls controls-row">
        <p align="center">
        
                <?php  echo anchor('asrefiscalizacion', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
                <?php 
                $data = array(
                       'name' => 'button',
                       'id' => 'submit-button',
                       'value' => 'Guardar',
                       'type' => 'submit',
                       'content' => '<i class="fa fa-floppy-o fa-lg"></i> Guardar',
                       'class' => 'btn btn-success'
                       );

                echo form_button($data);    
                ?>
                
        </p>
        </div>
            <?php echo form_close(); ?>
      </div>
      </div>



<!-- Se inicializa la ventana modal UGPP -->
    <div id="ugpp" class="modal hide fade" tabindex="-1" data-width="760" style="">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h2>Datos UGPP</h2>
  </div>
  <div class="modal-body">
    <div class="row-fluid">
      <div class="span10">
          
         <?php     

           echo form_label('Hallazgo', 'hallazgo');
           $data = array(
                      'name'        => 'hallazgo',
                      'id'          => 'hallazgo',
                      'value'       => set_value('hallazgo'),
                      'maxlength'   => '20',
                      'class'       => 'span10',
                      
                    );

           echo form_input($data);
           echo form_error('hallazgo','<div>','</div>');
        ?>
        <?php     

           echo form_label('Radicado', 'radicado');
           $data = array(
                      'name'        => 'radicado',
                      'id'          => 'radicado',
                      'value'       => set_value('radicado'),
                      'maxlength'   => '20',
                      'class'       => 'span10',
                      
                    );

           echo form_input($data);
           echo form_error('radicado','<div>','</div>');
        ?>

      </div>
    </div>
  </div>
  <div class="modal-footer">
    <p align="center">
      
      <button type="Reset" data-dismiss="modal" class="btn" onclick="destruir();">Cancelar</button>
      <button type="button" class="btn btn-success" onclick="ocultar();">Aceptar</button>
    </p>
  </div>
</div>
  
</div>

<!--modal generico para cargar contenido ayax-->
<!--<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
      <h3>Modal header</h3>
  </div>
  <div class="modal-body">
    <p>My modal content here…</p>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal">Close</button>
  </div>
</div>-->


<!-- Se inicializa la ventana modal OnBase -->
    <div id="onbase" class="modal hide fade" tabindex="-1" data-width="760" style="">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h2>Datos De Enlace Con ONBASE</h2>
  </div>
  <div class="modal-body">
    <div class="row-fluid">
      <div class="span10">
         <?php     

           echo form_label('Numero De Radicacion<span class="required">*</span>', 'radicacion');
           $data = array(
                      'name'        => 'radicacion',
                      'id'          => 'radicacion',
                      'value'       => set_value('radicacion'),
                      'maxlength'   => '12',
                      'class'       => 'span10',
                      
                    );

           echo form_input($data);
           echo form_error('radicacion<span class="required">*</span>','<div>','</div>');
        ?>
        <?php     

           echo form_label('NIS<span class="required">*</span>', 'nis');
           $data = array(
                      'name'        => 'nis',
                      'id'          => 'nis',
                      'value'       => set_value('nis'),
                      'maxlength'   => '12',
                      'class'       => 'span10',
                      
                    );

           echo form_input($data);
           echo form_error('nis','<div>','</div>');
        ?>
        <?php     

           echo form_label('Fecha De Radicado<span class="required">*</span>', 'fechar');
           $data = array(
                      'name'        => 'fechar',
                      'id'          => 'fechar',
                      'value'       => set_value('fechar'),
                      'maxlength'   => '10',
                      'placeholder' => 'dd/mm/aaaa',
                      'class'       => 'span10',
                      
                    );

           echo form_input($data);
           echo form_error('fechar','<div>','</div>');
        ?>
        <?php     

           echo form_label('Enviado Por<span class="required">*</span>', 'enviado');
           $data = array(
                      'name'        => 'enviado',
                      'id'          => 'enviado',
                      'value'       => set_value('enviado'),
                      'maxlength'   => '80',
                      'class'       => 'span10',
                      
                    );

           echo form_input($data);
           echo form_error('enviado','<div>','</div>');
        ?>
        <?php
         echo form_label('Cargo<span class="required">*</span>', 'cargo_id');  
              foreach($cargos as $row) {
                  $selectc[$row->IDCARGO] = $row->NOMBRECARGO;
               }
          echo form_dropdown('cargo_id', $selectc,'','id="cargo_id" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('cargo_id','<div>','</div>');
        ?>
         <?php     

           echo form_label('Observaciones<span class="required">*</span>', 'observaciones');
           $data = array(
                      'name'        => 'observaciones',
                      'id'          => 'observaciones',
                      'value'       => set_value('observaciones'),
                      'maxlength'   => '500',
                      'rows'        => '8',
                      'class'       => 'span10',
                      
                    );

           echo form_textarea($data);
           echo form_error('observaciones','<div>','</div>');
        ?>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <p align="center">
      <button type="button" data-dismiss="modal" class="btn" onclick="destruir();">Cancelar</button>
      <button type="button" class="btn btn-success" onclick="ocultar();">Aceptar</button>
    </p>
  </div>
</div>
  
</div>
 <script type="text/javascript">

  function fnc(v) {
    alert(v);
  } 

  //style selects
     function format(state) {
    if (!state.id) return state.text; // optgroup
         return "<i class='fa fa-home fa-fw fa-lg'></i>" + state.id.toLowerCase() + " " + state.text;
    }
    $(".chosen0").select2({
    formatResult: format,
    formatSelection: format,
    escapeMarkup: function(m) { return m; }
    });


    $(document).ready(function() { $(".chosen").select2();

     });

 </script>
<!-- Scrip que permite traer el valor del form_dropdown motivo -->
<script type="text/javascript">
   function ocultar(){
     $("#ugpp").modal('hide');
     $("#onbase").modal('hide');
   }

   function destruir(){
     $("#radicacion").val(null);
     $("#nis").val(null);
     $("#fechar").val(null);
     $("#enviado").val(null);
     $("#cargo_id").val(null);
     $("#observaciones").val(null);
     $("#hallazgo").val(null);
     $("#radicado").val(null);

             $('#ugpp').on('hidden', function() 
                  {
                       $(this).removeData('modal');
                  });
   }

   function seleccion(){
   $("#motivo").change(function () {
            selection = $(this).val();
            if (selection == 7) {
              //alert("el evento onclick esta llamando la funcion, ugpp"); 
              $("#ugpp").modal('show');
                  $("#radicacion").val(null);
                  $("#nis").val(null);
                  $("#fechar").val(null);
                  $("#enviado").val(null);
                  $("#cargo_id").val(null);
                  $("#observaciones").val(null);
            }else{
              if (selection == 6){
                <?php $sel = 6; ?>
                 //alert("el evento onclick esta llamando la funcion, onbase");
                 $("#onbase").modal('show');
                    $("#hallazgo").val(null);
                    $("#radicado").val(null); 

                    /*
                     *llamar contenido de las vistas via ajax
                    $('.modal-body').load('<?php echo base_url(); ?>index.php/bancos/add',function(result){
                        $('#myModal').modal({show:true});
                    });*/
              }
            }
        });
 }
</script>
<!--Script para select dependientes-->
<script type="text/javascript">
        $(document).ready(function() {
            $("#regional_id").change(function() {
                $("#regional_id option:selected").each(function() {
                    regional_id = $('#regional_id').val();
                    $.post("<?php echo base_url(); ?>index.php/asrefiscalizacion/llenarcombo", {
                        regional_id : regional_id
                    }, function(data) {
                        $("#fiscalizador").html(data);
                    });
                });
            })
        });
    </script>