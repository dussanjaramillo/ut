<?php
if (isset($message)) {
  echo $message;
}
?>
<?php $attributes = array('onsubmit' => 'return comprobarextension()', "id" => "myform");
echo form_open_multipart("aplicacionmanualdepago/subir_extracto", $attributes);
?>
<div style="background: #f0f0f0; width: 470px; margin: auto; overflow: hidden">
  <h3 class="text-center"><?php echo $title;
if (!empty($stitle)) : ?><br><small><?php echo $stitle ?></small><?php endif; ?></h3>
  <div style="overflow: hidden;">
    <div class="span2"><strong>Entidad:</strong></div>
    <div class="span3">
      <select name="banco_extracto" id="banco_extracto" class="validate[required]">
        <option value="" selected="selected">Seleccionar el banco...</option>
        <?php foreach ($bancos as $banco) : ?>
          <option value="<?php echo $banco['IDBANCO'] ?>"><?php echo $banco['NOMBREBANCO'] ?></option>
<?php endforeach; ?>
      </select>
    </div>
  </div>
  <div style="overflow: hidden; clear: both">
    <div class="span2"><strong>Regional:</strong></div>
    <div class="span3">
      <select name="regional" id="regional" class="validate[required]">
        <option value="" selected="selected">Seleccionar la regional...</option>
        <?php foreach ($regionales as $regional) : ?>
          <option value="<?php echo $regional['COD_REGIONAL'] ?>"><?php echo $regional['NOMBRE_REGIONAL'] ?></option>
        <?php endforeach; ?>
      </select>
    </div><br>
  </div>
  <div style="overflow: hidden;">
    <div class="span2"><strong>Cuenta bancaria:</strong></div>
    <div class="span3">
      <select name="cuenta_banco" id="cuenta_banco" class="validate[required]">
        <option value="" selected="selected">Seleccionar la cuenta del banco...</option>
      </select>
    </div>
  </div>
  <div style="overflow: hidden; clear: both">
    <div class="span2"><strong>Periodo de pago:</strong></div>
    <div class="span3">
    	<input type="text" name="periodo_pago" id="periodo_pago" class="validate[required]" readonly="readonly" />
    </div>
  </div>
  <div style="overflow: hidden; clear: both">
    <div class="span2"><strong>Cantidad de registros:</strong></div>
    <div class="span3"><input type="text" name="cant_registros" id="cant_registros" class="validate[required, custom[integer, min[1]]" /></div>
  </div>
  <div style="overflow: hidden; clear: both">
    <div class="span2"><strong>Total del extracto:</strong></div>
    <div class="span3"><input type="text" name="total_extracto" id="total_extracto" class="validate[required, custom[number, min[1]]" /></div>
  </div>
  <div style="overflow: hidden; clear: both; margin: 20px 0; border: 1px solid grey; width: 90%; margin: 0 auto; text-align: center">
    <?php
    $data = array(
        'name' => 'userfile',
        'id' => 'imagen',
        'class' => 'validate[required]'
    );
    echo form_upload($data);
    ?>
  </div>
  <div style="overflow: hidden; clear: both; margin: 20px 0; clear: both;">
    <input type="hidden" name="replace_periodo" id="replace_periodo" value="0" />
    <input type="hidden" name="extracto_id" id="extracto_id" value="" />
    <div class="span2" style="text-align: center"><?php
      $data = array(
          'name' => 'button',
          'id' => 'submit-button',
          'value' => 'Aceptar',
          'type' => 'submit',
          'content' => '<i class="fa fa-floppy-o fa-lg"></i> Aceptar',
          'class' => 'btn btn-success'
      );

      echo form_button($data);
      ?></div>
    <div class="span2" style="text-align: center"><?php echo anchor('aplicacionmanualdepago', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?></div>
  </div>
</div>
<?php echo form_close(); ?>
<script type="text/javascript" language="javascript" charset="utf-8">
  function comprobarextension() {
    if ($("#imagen").val() != "") {
      var archivo = $("#imagen").val();
      var extensiones_permitidas = new Array(".jpg", ".png", ".gif", ".bmp", ".jpeg");
      var mierror = "";
      //recupero la extensión de este nombre de archivo
      var extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase();
      //alert (extension);
      //compruebo si la extensión está entre las permitidas
      var permitida = false;
      for (var i = 0; i < extensiones_permitidas.length; i++) {
        if (extensiones_permitidas[i] == extension) {
          permitida = true;
          break;
        }
      }
      if (!permitida) {
        jQuery("#imagen").val("");
        mierror = "Comprueba la extensión de los archivos a subir en el campo imagen.\nSólo se pueden subir archivos con extensiones: " + extensiones_permitidas.join();
      }
      //si estoy aqui es que no se ha podido submitir
      if (mierror != "") {
        alert(mierror);
        return false;
      }
      return true;
    }
  }
  
  function ajaxValidationCallback(status, form, json, options) {}

  $(document).ready(function() {
		$("#periodo_pago").datepicker({
      dateFormat: "yy-mm",
			minDate: "-5y",
      changeMonth: true,
			changeYear: true
    });
    $("#periodo_pago").change(function() {
      if($("#periodo_pago option:selected").val() != "" && $("#banco_extracto option:selected").val() != "" && $("#regional option:selected").val() != "") {
        jQuery.ajax({
            url:'<?php echo base_url("index.php/aplicacionmanualdepago/validarperiodo") ?>',
            type:'post',
            data:{
                banco     : $("#banco_extracto").val(),
                regional  : $("#regional").val(),
                periodo   : $("#periodo_pago").val()
            }
          }).done(
            function(data) {
              if(data.datos == true) {
                if(confirm("Ya se encuentra registrada la información para el periodo seleccionado. Desea actualizar la información con la información y borrar el archivo cargado anteriormente?") == true) {
                  $("#replace_periodo").val("1");
                  $("#extracto_id").val(data.id);
                }
                else {
                  $("#replace_periodo").val("0");
                  $("#periodo_pago").each(function(){
                    this.selectedIndex=0;
                  });
                }
              }
              else if(data.datos != false) {
                jQuery("#banco_extracto").validationEngine('validate');
                jQuery("#regional").validationEngine('validate');
                $("#periodo_pago").each(function(){
                  this.selectedIndex=0;
                });
                return false;
              }
            }
          );
      }
      else {
        jQuery("#banco_extracto").validationEngine('validate');
        jQuery("#regional").validationEngine('validate');
        $("#periodo_pago").each(function(){
          this.selectedIndex=0;
        });
        return false;
      }
    });
		
		$("#banco_extracto").change(function() {
      if($("#banco_extracto option:selected").val() != "") {
        jQuery.ajax({
            url:'<?php echo base_url("index.php/aplicacionmanualdepago/TraerCuentasBancarias") ?>',
            type:'post',
            data:{
                banco     : $("#banco_extracto").val()
            }
          }).done(
            function(data) {
              if(data != false) {
								var seleccion = '<option value="" selected="selected">Seleccionar la cuenta del banco...</option>';
                for(var x in data) {
									seleccion += '<option value="' + data[x].NUMERO_CUENTA + '">' + data[x].NUMERO_CUENTA + ' - ' + data[x].DESCRIPCION + '</option>' + "\n";
								}
								$("#cuenta_banco").html(seleccion);
              }
            }
          );
      }
      else {
        jQuery("#banco_extracto").validationEngine('validate');
        jQuery("#regional").validationEngine('validate');
        $("#periodo_pago").each(function(){
          this.selectedIndex=0;
        });
        return false;
      }
    });
  });
</script>