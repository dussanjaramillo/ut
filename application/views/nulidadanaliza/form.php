<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
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
<script>
var nCausalidad = 0;
var nColumna    = 0;
var par         = 1;

function subirPantalla(){
    $('html, body').animate({
           scrollTop: '0px'
       },
       1500);
       return false;
}

function addColumn(text){
    nCausalidad = nCausalidad + 1;
    var agregarFila = 0;
    
    if(par == 1 ){
        nColumna = nColumna + 1;
        agregarFila = 1;
        par = 0;
    }else{
        par = 1;
        agregarFila = 0;
    }
    tds = '';
    if(agregarFila == 1){
        tds = '<tr valign="TOP"><td></td><td><input id="nuevoCausalidad' + nCausalidad + '" type="checkbox" checked="checked" value="' + nCausalidad + '" name="nuevoCausales[]"></td><td></td><td>' + text + '<input type="hidden" value="' + text + '" name="nCualsalTex' + nCausalidad + '" /><br><br></td><td>&nbsp;&nbsp;&nbsp;</td><td><div id="nuevoCausalidadcheck' + (nCausalidad + 1) + '"></div></td><td></td><td><div id="nuevoCausalidadtext' + (nCausalidad + 1) + '"></div><br><br></td></tr>'
        $("#tablaCausalidades").append(tds);
    }else{
        var checkNulidad    = '<input id="nuevoCausalidad' + nCausalidad + '" type="checkbox" checked="checked" value="' + nCausalidad + '" name="nuevoCausales[]">';
        var textNulidad     = text + '<input type="hidden" value="' + text + '" name="nCualsalTex' + nCausalidad + '" />';
        
        $('#nuevoCausalidadcheck'    + nCausalidad).html(checkNulidad);
        $('#nuevoCausalidadtext'     + nCausalidad).html(textNulidad);
    }
    
    $('#numCaul').val(nCausalidad);
 
}

function salvar(){
    var causalesIds = new Array();

    $('input[name="causalesIds[]"]:checked').each(function() {
       causalesIds.push($(this).val());
    });
    
    if($('input[name="nuevoCausales[]"]:checked') != null){
        $('input[name="nuevoCausales[]"]:checked').each(function() {
            causalesIds.push($(this).val());
         });
    }
    
    if(causalesIds.length == 0){
        alert('Debe seleccionar 1 o mas causales');
    }else {
        $(".ajax_load").toggle("slow");
        subirPantalla();
        
        var url = "<?php echo base_url()?>index.php/nulidadanaliza/save"; // El script a dónde se realizará la petición.
        $.ajax({
               type: "POST",
               url: url,
               data: $("#formCausales").serialize(), // Adjuntar los campos del formulario enviado.
               success: function(data)
               {
                   if($.trim(data) == ''){
                       $(".ajax_load").toggle("slow");
                        $("#causales").toggle("slow");
                        alert('La nulidad se guardo con exito');
                        location.href = '<?=base_url()?>index.php/nulidadanaliza/listFiscalizaciones/<?php echo $empresa->CODEMPRESA?>';
                    }else{
                        alert('No se puede guardar la nulidad, porfavor cominiquese con su proveedor');
                    }
                   
               }
         });
    }

}


</script>
<div id="ajax_load" class="ajax_load" style="display: none">
<div class="preload" id="preload" ></div><img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
</div>
<div class="center-form-large">
<?php echo $custom_error;?>
<h6><a href="<?=base_url()?>index.php/nulidadanaliza">Empresas</a> / <a href="<?=base_url()?>index.php/nulidadanaliza/listFiscalizaciones/<?php echo $empresa->CODEMPRESA?>">Fiscalizaciones (Empresa <?php echo $empresa->NOMBRE_EMPRESA?>)</a> / <a href="javascript:void(0)">Crear nulidad</a></h6>
<h2>Crear nulidades</h2>
<?php 
echo form_open(base_url() . 'index.php/nulidadanaliza/save', 'method="post" id="formCausales" onsubmit="return validar()" enctype="multipart/form-data"');
?>


<div align="center" id="causales" style="background: none repeat scroll 0 0 #FFFFFF;">
    <p ><h3>Informacion de la Empresa</h3></p>
    <div class="controls controls-row">
    <div class="span4" align="left">
    <?php
     echo form_label('Nit', 'NIT_EMPRESA');
       $data = array(
                  'name'        => 'NIT_EMPRESA',
                  'id'          => 'NIT_EMPRESA',
                  'value'       => $fiscalizacion->NIT_EMPRESA,
                   'class'      => 'span3',
                   'readonly'   => 'readonly'
                );

       echo form_input($data);
    ?>
    </div>
    <div class="span4" align="left">
    <?php
    echo form_label('Razon Social', 'RAZON_SOCIAL');
    $data = array(
               'name'        => 'RAZON_SOCIAL',
               'id'          => 'RAZON_SOCIAL',
               'value'       => $fiscalizacion->RAZON_SOCIAL,
               'class'       => 'span3',
               'readonly'    => 'readonly'
             );

       echo form_email($data);
    ?>
    </div>
    </div>
    <div class="controls controls-row">
    <div class="span4" align="left">
    <?php
    echo form_label('Consepto', 'consepto');
    $data = array(
               'name'        => 'consepto',
               'id'          => 'consepto',
               'value'       => $fiscalizacion->NOMBRE_CONCEPTO,
               'class'       => 'span3',
               'readonly'    => 'readonly'
             );

       echo form_email($data);
    ?>
    </div>    

    <div class="span4" align="left">
    <?php
    echo form_label('Instancia', 'instancia');
    $data = array(
               'name'        => 'instancia',
               'id'          => 'instancia',
               'value'       => 'Cobro Coactivo',
               'class'       => 'span3',
               'readonly'    => 'readonly'
             );

       echo form_email($data);
    ?>
    </div>
    </div>
    <div class="controls controls-row">
    <div class="span4" align="left">
    <?php
    echo form_label('Representante Legal', 'representante_legal');
    $data = array(
               'name'        => 'representante_legal',
               'id'          => 'representante_legal',
               'value'       => $fiscalizacion->REPRESENTANTE_LEGAL,
               'class'       => 'span3',
               'readonly'    => 'readonly'
             );

       echo form_email($data);
    ?>
    </div>

    <div class="span4" align="left">
    <?php
    echo form_label('Telefono', 'telefono');
    $data = array(
               'name'        => 'telefono',
               'id'          => 'telefono',
               'value'       => $fiscalizacion->TELEFONO_FIJO,
               'class'       => 'span3',
               'readonly'    => 'readonly'
             );

       echo form_email($data);
    ?>
    </div>
    </div>
    <div class="controls controls-row">
    <div class="span4" align="left">
    <?php
    echo form_label('Codigo de la fiscalizacion', 'cod_gestion');
    $data = array(
               'name'        => 'cod_gestion',
               'id'          => 'cod_gestion',
               'value'       => $fiscalizacion->COD_FISCALIZACION,
               'class'       => 'span3',
               'readonly'    => 'readonly'
             );

       echo form_email($data);
    ?>
    </div>

    <div class="span4" align="left">
    <?php
    echo form_label('Codigo codigo gestion cobro ', 'cod_gestion_cobro');
    $data = array(
               'name'        => 'cod_gestion_cobro',
               'id'          => 'cod_gestion_cobro',
               'value'       => $fiscalizacion->COD_GESTIONACTUAL,
               'class'       => 'span3',
               'readonly'    => 'readonly'
             );

       echo form_email($data);
    ?>
    </div>
    </div>

    <div class="controls controls-row">
    <div class="span4" align="left">
    <?php 
     echo form_label('Secretario <span class="required">*</span>', 'ASIGNADO_A');                            
          $select = array();
          foreach($secretarios as $row) {
              $select[$row->IDUSUARIO] = $row->NOMBREUSUARIO;
           }
     echo form_dropdown('ASIGNADO_A', $select, '','id=ASINGNADO_A"  class="chosen span3" placeholder="seleccione..." ');
     echo form_error('ASIGNADO_A','<div>','</div>');

    ?>
    </div>
    </div>    
    <p ><h3>Causales de nulidad</h3></p>
    <input type="hidden" name="numCaul" id="numCaul" value=""/>
    <input type="hidden" name="ID_GESTION" id="ID_GESTION" value="<?php echo $id_gestion_cobro?>"/>
    <table  border="0" id="tablaCausalidades">
    <?php 
    for($count = 0; $count < count($causales); $count = $count + 2){
        $causal = $causales[$count];
    ?>
    <tr valign="TOP">
        <td>&nbsp;&nbsp;&nbsp;</td>
        <td>
            <?=form_checkbox(array('name'=>'causalesIds[]','id'=>'causal' . $causal->COD_CAUSAL_NULIDAD, 'value' => $causal->COD_CAUSAL_NULIDAD));?>
        </td>
        <td>&nbsp;</td>
        <td  align="justify" width="45%">
            <?php echo $causal->NOMBRE_CAUSAL?>
            <br>
            <br>
        </td>
        <td>&nbsp;&nbsp;&nbsp;</td>
        <?php 
        if(isset($causales[$count + 1])){
            $causal = $causales[$count + 1];
        ?>
        <td>
            <?=form_checkbox(array('name'=>'causalesIds[]','id'=>'causal' . $causal->COD_CAUSAL_NULIDAD, 'value' => $causal->COD_CAUSAL_NULIDAD));?>
        </td>
        <td>&nbsp;</td>
        <td  align="justify" width="45%">
            <?php echo $causal->NOMBRE_CAUSAL?>
            <br>
            <br>
        </td>
        <?php 
        }else{
            ?>
            <td></td>
            <td></td>
            <?php
        }
        ?>
        <td>&nbsp;&nbsp;&nbsp;</td>
    </tr>  
    <?php 
    }
    ?>
    </table>


    <div class="controls controls-row">
        <div class="span1">
            <?php 
            $data = array(
                'name' => 'aceptar',
                'id' => 'aceptar',
                'value' => 'Aceptar',
                'type' => 'button',
                'content' => 'Aceptar',
                'onclick' => 'salvar()'
                
            );

            echo form_button($data);
            ?>
        </div>
        <div class="span3">
            <?php 
            $data = array(
                'name' => 'nuevaCasualidad',
                'id' => 'nuevaCasualidad',
                'value' => 'nuevaCasualidad',
                'type' => 'button',
                'content' => 'Ingresar nueva causalidad',
                'data-target' => '#myModal',
                'data-toggle' => 'modal'
            );

            echo form_button($data);
            ?>
        </div>
        <div class="span1">
            <?php 
            $data = array(
                'name' => 'validar',
                'id' => 'validar',
                'value' => 'validar',
                'type' => 'button',
                'content' => 'Validar',
                'onclick' => 'validar()',
                'disabled' => 'true'
                
            );

            echo form_button($data);
            ?>
        </div>
        <div class="span1">
            <?php 
            $data = array(
                'name' => 'cancelar',
                'id' => 'cancelar',
                'value' => 'cancelar',
                'type' => 'cancelar',
                'content' => 'cancelar',
                'onclick' => 'location.href="' . base_url() . 'index.php/nulidadanaliza/listFiscalizaciones/' . $empresa->CODEMPRESA . '"'
            );
            echo form_button($data);
            ?>
        </div>
        
    </div>
    <br>
    <div class="controls controls-row">
        <div class="alert alert-error" id="alert_error" style="display: none">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Error!</strong> Debe seleccionar por lo menos una causal.
        </div> 
    </div>
       
    
<hr>
</div>


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Agregar Causal Temporal</h4>
      </div>
      <div class="modal-body">
          <div class="controls controls-row">
                  <?php
                    echo form_label('Causalidad Temporal', 'nulidadTemporal');
                      $data = array(
                                 'name'        => 'nulidadTemporal',
                                 'id'          => 'nulidadTemporal',
                                 'value'       => '',
                                 'class'       => 'span3',
                                 'style'        => 'width: 517px; height: 226px;'
                               );

                      echo form_textarea($data);
                   ?>
          </div>
        
      </div>
      <div class="modal-footer">    
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" id="agregar" class="btn btn-primary">Agregar</button>
      </div>
    </div>
  </div>
</div>



    
</div>
<?php 
echo form_close();
?>
<div class="alert alert-success" id="alert" style="display: none">
<button class="close" data-dismiss="alert" type="button">×</button>
El auto se <?php echo ( ($auto == null) ? ' almaceno' : 'actualizo' )?> con exito.
</div>

 <script>
$("#agregar").click(function() {
    var texto = $.trim($('#nulidadTemporal').val());
    
    if(texto == ''){
        alert('El campo se encuentra vacio');
    }else{
        addColumn(texto);
        $('#myModal').modal('hide');
        $('#nulidadTemporal').val('');
    }
    
    
});
</script>