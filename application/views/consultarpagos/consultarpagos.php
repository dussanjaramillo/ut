<?php
if (isset($message)) {
  echo $message;
}
?>
<div style="border: 1px solid grey; overflow: hidden; padding: 9px 0 0">
  <div class="text-center"><h2><?php echo $title ?></h2></div>
  <?php $attributes = array("id" => "myform");
  echo form_open("consultarpagos/buscar", $attributes); ?>
  <span class="span2"><label for="nit">Número de identificación:</label></span>
  <span class="span3"><?php
    $data = array(
        'name' => 'nit',
        'id' => 'nit',
        'class' => 'validate[required, custom[onlyNumber], minSize[4], maxSize[20]]'
    );
    echo form_input($data);
    ?> <span class="required"> * </span>
    <img id="preloadmini" src="<?php echo base_url('img/319.gif') ?>" width="28" height="28" />
  </span>
  <span class="span2"><label for="regionales">Regionales</label></span>
  <span class="span3">
    <select name="regional" id="regional"><?php
      echo '<option value="">Seleccione la regional...</option>';
      foreach ($regionales as $regional) :
        echo '<option value="' . $regional['COD_REGIONAL'] . '">' . $regional['NOMBRE_REGIONAL'] . '</option>';
      endforeach;
      ?></select>
  </span><br><br>
  <span class="span2"><label for="conceptos">Conceptos SENA</label></span>
  <span class="span3">
    <select name="concepto" id="concepto"><?php
      echo '<option value="">Seleccione el concepto SENA...</option>';
      foreach ($conceptos as $concepto) :
        echo '<option value="' . $concepto['COD_TIPOCONCEPTO'] . '">' . $concepto['NOMBRE_TIPO'] . '</option>';
      endforeach;
      echo '<option value="TODOS">TODOS</option>';
      ?></select>
    <img id="preloadmini2" src="<?php echo base_url('img/319.gif') ?>" width="28" height="28" />
  </span>
  <span class="span2"><label for="periodo_inicio">Periodo inicio</label></span>
  <span class="span3"><?php
    $data = array(
        'name' => 'periodo_inicio',
        'id' => 'periodo_inicio',
        'class' => 'validate[required, custom[date]]',
		'readonly' => 'readonly'
    );
    echo form_input($data);
    ?> <span class="required"> * </span></span><br><br>
  <span class="span2"><label for="concepto2">Sub concepto</label></span>
  <span class="span3">
    <select name="concepto2" id="concepto2"><option value="">Seleccione el sub concepto...</option></select>
  </span>
  <span class="span2"><label for="periodo_fin">Periodo fin</label></span>
  <span class="span3"><?php
    $data = array(
        'name' => 'periodo_fin',
        'id' => 'periodo_fin',
        'class' => 'validate[required, custom[date]]',
		'readonly' => 'readonly'
    );
    echo form_input($data);
    ?> <span class="required"> * </span></span><br><br>
  <!--<span class="span2"><label for="resolucion">Número de resolución</label></span>
  <span class="span3"><?php
    $data = array(
        'name' => 'resolucion',
        'id' => 'resolucion'
    );
    echo form_input($data);
    ?></span>-->
  <span class="span11 text-center">
    <?php
    $data = array(
        'name' => 'button',
        'id' => 'submit-button',
        'value' => 'seleccionar',
        'type' => 'submit',
        'content' => '<i class="fa fa-search fa-lg"></i> Buscar',
        'class' => 'btn btn-success'
    );
    echo form_button($data);
    ?></span><br>
<?php echo form_close(); ?>
</div>
<script type="text/javascript" language="javascript" charset="utf-8">
  $(document).ready(function() {
    $("#preloadmini, #preloadmini2").hide();
    $("#periodo_inicio").datepicker({
			buttonText: "Selecciona una fecha",
			buttonImage: "<?php echo base_url() . "img/calendario.png" ?>",
			buttonImageOnly: true,
      defaultDate: "-1w",
      dateFormat: "dd/mm/yy",
      changeMonth: true,
      maxDate: "0",
      minDate: "-5y",
      changeYear: true,
      onClose: function(selectedDate) {
        $("#periodo_fin").datepicker("option", "minDate", selectedDate);
      }
    });
    $("#periodo_fin").datepicker({
			buttonText: "Selecciona una fecha",
			buttonImage: "<?php echo base_url() . "img/calendario.png" ?>",
			buttonImageOnly: true,
      changeMonth: true,
      changeYear: true,
      maxDate: "0",
      dateFormat: "dd/mm/yy",
      onClose: function(selectedDate) {
        $("#periodo_inicio").datepicker("option", "maxDate", selectedDate);
      }
    });
    $("#concepto").change(function() {
      if ($(this).val() != "" && $(this).val() != "TODOS") {
        $("#preloadmini2").show();
        jQuery.ajax({
          url: '<?php echo base_url("index.php/consultarpagos/traerconceptos") ?>',
          type: 'post',
          data: {
            concepto: $(this).val()
          }
        }).done(
                function(data) {
                  $("#concepto2").html(data);
                  $("#concepto2").addClass("validate[required]");
                  $("#preloadmini2").hide();
                }
        );
        $("#preloadmini2").show();
      }
      else {
        $("#concepto2").html('<option value="">Seleccione el sub concepto...</option');
        $("#concepto2").removeClass("validate[required]");
      }
    });
    $("#nit").autocomplete({
      source: "<?php echo base_url("index.php/consultarpagos/traernits") ?>",
      minLength: 4,
      search: function( event, ui ) {
        $("#preloadmini").show();
      },
      response: function( event, ui ) {
        $("#preloadmini").hide();
      }
    });
  });

  // Called once the server replies to the ajax form validation request
  function ajaxValidationCallback(status, form, json, options) {
  }
</script>