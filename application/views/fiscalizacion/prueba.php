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
  <div style="overflow: hidden; clear: both">
    <div class="span2"><strong>Periodo de pago:</strong></div>
    <div class="span3">
      <select name="periodo_pago" id="periodo_pago" class="validate[required]">
        <option value="" selected="selected">Seleccionar el periodo de pago...</option>
        <?php foreach ($periodos as $periodo) : ?>
          <option value="<?php echo $periodo ?>"><?php echo $periodo ?></option>
        <?php endforeach; ?>
      </select>
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
                jQuery("#regional").validationEngine('validate..
function subir_extracto() {
    if ($this->ion_auth->logged_in()) {
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicacionmanualdepago/traerpagos')) {
        $this->form_validation->set_rules('banco_extracto', 'Banco', 'required');
        $this->form_validation->set_rules('regional', 'Regional', 'required');
        $this->form_validation->set_rules('periodo_pago', 'Periodo de pago', 'required');
        $this->form_validation->set_rules('cant_registros', 'Cantidad de registros', 'required|integer');
        $this->form_validation->set_rules('total_extracto', 'Total extracto', 'required|regex_match[/^-?[0-9]*([,\.][0-9]*)?$/]');
        $file = $this->do_upload();
        if(isset($file['error']) || $this->form_validation->run() == FALSE) {
          $this->template->set('title', 'Aplicación de Pagos');
          $this->data['title'] = 'CARGUE EXTRACTOS';
          $this->data['stitle'] = 'Fecha Actual: '.date("d-m-Y");
          $this->data['class'] = 'error';
          $this->data['message'] = $this->session->flashdata('message');
          $this->data['mensaje'] = validation_errors();
          $this->data['error'] = true;
          $this->data['file_error'] = $file;
          $this->template->load($this->template_file, 'aplicacionmanualdepago/respuestaextracto', $this->data);
        }
        else {
          $datos['COD_ENTIDAD'] = $this->input->post('banco_extracto');
          $datos['COD_REGIONAL'] = $this->input->post('regional');
          $datos['PERIODO_EXTRACTO'] = $this->input->post('periodo_pago');
          $datos['CANT_REGISTROS'] = $this->input->post('cant_registros');
          $datos['TOTAL_EXTRACTO'] = number_format($this->input->post('total_extracto'), 0);
          $datos['NOMBRE_ARCHIVO'] = $file['upload_data']['file_name'];
          $replace = $this->input->post('replace_periodo');
          $extracto_id = $this->input->post('extracto_id');
          if($replace == 1 and !empty($extracto_id)) :
            $datos['COD_EXTRACTO'] = $extracto_id;
          endif;
          $this->aplicacionmanualdepago_model->extracto($datos);
          $this->template->set('title', 'Aplicación de Pagos');
          $this->data['title'] = 'CARGUE EXTRACTOS';
          $this->data['class'] = 'success';
          $this->data['stitle'] = 'Fecha Actual: '.date("d-m-Y");
          $this->data['mensaje'] = "Se ha adicionado exitosamente el extracto de la entidad, y la información del mismo";
          $this->data['error'] = false;
          $this->data['file'] = $file;
          $this->data['message'] = $this->session->flashdata('message');
          $this->template->load($this->template_file, 'aplicacionmanualdepago/respuestaextracto', $this->data);
        }
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/inicio');
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }
private function do_upload() {
    $config['upload_path'] = './uploads/aplicacionmanualdepagoextractos/';
    $config['allowed_types'] = 'gif|jpg|png';
    $config['max_size'] = '2048';
    $config['encrypt_name'] = TRUE;

    $this->load->library('upload', $config);

    if (!$this->upload->do_upload()) {
      return $error = array('error' => $this->upload->display_errors());
    } else {
      return $data = array('upload_data' => $this->upload->data());
    }
  }