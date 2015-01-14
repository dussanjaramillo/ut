<?php
if (isset($message)) {
  echo $message;
}
?>
<div class="preload"></div>
<img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<?php
$attributes = array('id' => 'myform');
echo form_open("aplicacionmanualdepago/addpago", $attributes);
?>
<div class="text-center">
  <h2><?php echo $title; ?></h2>
  <?php if (!empty($stitle)) : ?>
    <div class="alert alert-info" style="border: 1px solid grey; overflow: hidden; text-align: justify; width: 94%; max-width: 1280px; min-width: 320px; margin: 0 auto 20px">
      Para realizar el pago manualmente por favor tenga en cuenta los siguientes aspectos:
      <ul>
        <li>Rellene todos los campos requeridos.</li>
        <li>Si el pago a realizar es de cartera ingrese primero la fecha de pago y el valor pagado y luego si seleccione el subconcepto.</li>
        <li>Los pagos de cartera se aplican conforme a la edad de cartera.</li>
      </ul>
    </div>
  <?php endif; ?>
</div>
<?php
if (isset($error)) :
  $class = ($error['status'] == true) ? "success" : "danger";
  ?>
  <div class="alert alert-<?php echo $class ?>">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <?php echo $error['text']; ?>
  </div>
  <?php
endif;
?>

<div style="border: 1px solid grey; overflow: hidden; padding: 9px 0 0" class="center-form-xlarge">
  <span class="span4"><label for="nit"><strong>Número de identificación y/o razón social:</strong></label></span>
  <span class="span3"><?php
    $data = array(
        'name' => 'nit',
        'id' => 'nit',
        'class' => 'validate[required, custom[onlyNumber], minSize[4], maxSize[20]]'
    );
    echo form_input($data);
    ?>
    <img id="preloadmini" src="<?php echo base_url('img/319.gif') ?>" width="28" height="28" />
  </span>
</div>
<br>
<div class="control-group center-form-xlarge" style="border: 1px solid grey; overflow: hidden; padding: 9px 0 0">
  <div class="span"><strong>FECHA ACTUAL:</strong></div>
  <div class="span">
    <input type="text" class="input-small" name="fecha_actual" id="fecha_actual" readonly value="<?php echo date("Y/m/d"); ?>" />
  </div>
  <div class="span"><strong>NIT:</strong></div>
  <div class="span input-medium nit"></div>
  <div class="span"><strong>RAZÓN SOCIAL:</strong></div>
  <div class="span razon">&nbsp;</div>
</div>
<br>
<div style="border: 1px solid grey; overflow: hidden; padding: 9px 0 0" class="center-form-xlarge">
  <div style="overflow: hidden;">
    <div class="span2"><strong>Fecha de pago: <span class="required">*</span></strong></div>
    <div class="span3">
      <input type="text" name="fecha_pago" readonly id="fecha_pago" class="validate[required]" />
    </div>
    <div class="span2"><strong>Valor recibido: <span class="required">*</span></strong></div>
    <div class="span3">
		<div class="input-prepend">
			<span class="add-on">$</span>
				<input type="text" name="valor_recibido" id="valor_pagado" class="validate[required,custom[onlyNumber],min[1]]" />
			</span>
		</div>
    </div>
  </div>
  <div style="overflow: hidden;">
    <div class="span2"><strong>Concepto: <span class="required">*</span></strong></div>
    <div class="span3">
      <select name="concepto" id="concepto" class="validate[required]">
        <option value="" selected="selected">Por favor seleccione el concepto...</option>
        <?php foreach ($conceptos as $concepto) : ?>
          <option value="<?php echo $concepto['COD_TIPOCONCEPTO'] ?>"><?php echo $concepto['NOMBRE_TIPO'] ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="span2"><strong>Sub Concepto: <span class="required">*</span></strong></div>
    <div class="span3">
      <select name="subconcepto" id="subconcepto" class="validate[required]">
        <option value="" selected="selected">Por favor seleccione el subconcepto...</option>
      </select>
    </div>
  </div>
  <div style="overflow: hidden;">
    <div class="span2"><strong>Entidad de recaudo <span class="required">*</span></strong></div>
    <div class="span3">
      <select name="banco_recaudo" id="banco_recaudo" class="validate[required]">
        <option value="" selected="selected">Seleccionar el banco...</option>
        <?php foreach ($bancos as $banco) : ?>
          <option value="<?php echo $banco['IDBANCO'] ?>"><?php echo $banco['NOMBREBANCO'] ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="span2"><strong>Regional: <span class="required">*</span></strong></div>
    <div class="span3">
      <select name="regional" id="regional" class="validate[required]">
        <option value="">Seleccione la regional...</option>
        <?php foreach ($regionales as $regional) : ?>
          <option value="<?php echo $regional['COD_REGIONAL'] ?>"><?php echo $regional['NOMBRE_REGIONAL'] ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>
  <div style="overflow: hidden; clear: both;">
    <div class="span2"><strong>No. de documento: <span class="required">*</span></strong></div>
    <div class="span3">
      <input type="text" name="documento" id="documento" class="validate[required, custom[integer], min[1], maxSize[20]]" />
    </div>
    <div class="span2"><strong>Forma de pago: <span class="required">*</span></strong></div>
    <div class="span3">
      <select name="forma_pago" id="forma_pago" class="validate[required]">
        <option value="" selected="selected">Seleccionar la forma de pago...</option>
        <?php foreach ($formaspago as $formapago) : ?>
          <option value="<?php echo $formapago['COD_FORMAPAGO'] ?>"><?php echo $formapago['NOMBE_FORMAPAGO'] ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>
  <div class="addpago"></div>
  <div style="overflow: hidden; margin: 20px 0; clear: both;">
    <div class="span5" style="text-align: center">
      <?php
      $data = array(
          'name' => 'button',
          'id' => 'submit-button',
          'value' => 'Adicionar pago',
          'type' => 'submit',
          'content' => '<i class="fa fa-bank fa-lg"></i> Registrar Pago',
          'class' => 'btn btn-success addpagobtn'
      );

      echo form_button($data);
      ?>
    </div>
    <div class="span5" style="text-align: center"><?php echo anchor('aplicacionmanualdepago', '<i class="fa fa-minus-circle"></i> Cancelar', 'class="btn btn-warning btn-lg"'); ?></div>
  </div>
</div>
<?php echo form_close(); ?>
<script type="text/javascript" language="javascript" charset="utf-8">
  $(document).ready(function() {
    $(".preload, .load, #preloadmini").hide();
    $("#periodo_pago, #referencia, #valor_adeudado, #saldo_deuda, .agregarpago").hide();
    $(".addpagobtn").attr("disabled", true);
    $("#fecha_pago").datepicker({
      dateFormat: "yy-mm-dd",
      maxDate: "0",
      minDate: "-5y",
      changeMonth: true,
      changeYear: true
    });
    $("#nit").autocomplete({
      source: "<?php echo base_url("index.php/aplicacionmanualdepago/buscar") ?>",
      minLength: 4,
      search: function(event, ui) {
        $("#preloadmini").show();
        $('#concepto').val('');
        $('#subconcepto').val('');
        $(".addpago").html("");
      },
      response: function(event, ui) {
        $("#preloadmini").hide();
      },
      select: function(event, ui) {
        if (ui.item) {
          $(".nit").html(ui.item.nit);
          $(".razon").html(ui.item.razon);
          $("#regional").val(ui.item.regional);
        }
        console.log(ui.item ?
                "Selected: " + ui.item.nit + " :: " + ui.item.razon :
                "Nothing selected, input was " + this.value);
      }
    });
    $("#concepto").change(function() {
      if ($(this).val() != "") {
        $(".addpago").html("");
        $('#myform').validationEngine('hideAll');
        jQuery.ajax({
          url: '<?php echo base_url("index.php/aplicacionmanualdepago/subcarteras") ?>',
          type: 'post',
          data: {
            concepto: $("#concepto option:selected").val(),
            nit: $("#nit").val(),
            op: 1
          },
          beforeSend: function() {
            $(".preload, .load").show();
          }
        }).done(
                function(data) {
                  if (data == null || data == "") {
                    alert("El CONCEPTO no tiene carteras para recaudo");
                    $(".addpagobtn").attr("disabled", true);
                  }
                  else {
                    var options = '<option value="">Por favor seleccione el subconcepto...</option>';
                    $.each(data, function(i, item) {
                      options += '<option value="' + item.COD_CONCEPTO_RECAUDO + '">' + item.NOMBRE_CONCEPTO + '</option>' + "\n";
                    });
                    $("#subconcepto").addClass("validate[required]").html(options).show();
                    $(".addpagobtn").attr("disabled", false);
                  }
                }
        ).always(function() {
          $(".preload, .load").hide();
        });
      }
      else {
        $(".addpago").html("");
      }
    });
    $("#subconcepto").change(function() {
      jQuery("#addpago").validationEngine('hide');
      if ($(this).val() != "") {
        var sconcepto = $("#subconcepto option:selected").val();
        $("#submit-button").attr("disabled", false);
        switch (sconcepto) {
          case '80' :
          case '81' :
          case '82' :
          case '83' :
          case '84' :
          case '85' :
          case '86' :
          case '87' :
          case '88' :
          case '89' :
          case '90' :
          case '91' :
          case '92' :
          case '93' :
            if ($("#fecha_pago").validationEngine("validate") == false &&
                    $("#valor_pagado").validationEngine("validate") == false) {
              $(".addpago").html("");
              $('#myform').validationEngine('hideAll');
              jQuery.ajax({
                url: '<?php echo base_url("index.php/aplicacionmanualdepago/cargar_template") ?>',
                type: 'post',
                dataType: 'html',
                data: {
                  concepto: $("#concepto option:selected").val(),
                  subconcepto: $("#subconcepto option:selected").val(),
                  nit: $("#nit").val(),
                  fecha_pago: $("#fecha_pago").val(),
                  valor_pago: $("#valor_pagado").val()
                },
                beforeSend: function() {
                  $(".preload, .load").show();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                  // silent: Log the error
                  console.info(errorThrown);
                  // loud: Throw Exception
                  throw errorThrown;
                }
              }).done(function(data) {
                $(".addpago").html(data);
                $(".preload, .load").hide();
              }).always(function() {
                $(".preload, .load").hide();
              });
            }
            else {
              $('#myform').validationEngine('hideAll');
              $('#subconcepto').val('');
              $("#fecha_pago").validationEngine("showPrompt");
              $("#valor_pagado").validationEngine("showPrompt");
              alert("Por favor ingrese la fecha de pago y el valor recibido para continuar.");
              $(".addpago").html("");
            }
            break;
          default :
            $(".addpago").html("");
            jQuery.ajax({
              url: '<?php echo base_url("index.php/aplicacionmanualdepago/cargar_template") ?>',
              type: 'post',
              data: {
                concepto: $("#concepto option:selected").val(),
                subconcepto: $("#subconcepto option:selected").val(),
                nit: $("#nit").val()
              },
              beforeSend: function() {
                $(".preload, .load").show();
              },
              error: function(jqXHR, textStatus, errorThrown) {
                // silent: Log the error
                console.info(errorThrown);
                // loud: Throw Exception
                throw errorThrown;
              }
            }).done(
                    function(data) {
                      $(".addpago").html(data);
                    }
            ).always(function() {
              $(".preload, .load").hide();
            });
            break;
        }
        /*setTimeout(function() {
         jQuery(".preload, .load").hide();
         }, 2000);*/
      }
      else {
        $(".addpago").html("");
      }
    });
    $("#valor_pagado").on({
      "keyup": function() {
        valor_pago();
      },
      'onkeypress': function() {
        valor_pago();
      },
      'onblur': function() {
        valor_pago();
      },
      'onkeyup': function() {
        valor_pago();
      }
    });
  });

  function valor_pago() {
    $("#submit-button").attr("disabled", true);
    $('#subconcepto').val('');
    $(".addpago").html("");
  }

  // Called once the server replies to the ajax form validation request
  function ajaxValidationCallback(status, form, json, options) {
    if (window.console)
      console.log(status);

    if (status === true) {
    }
    else {
    }
  }

  function num(c) {
    c.value = c.value.replace(/[A-z-\/\*\,\ \;\:\{\}\[\]\^\`\¨\´\+\~\°\|\¬\!\¡\'\?\¿\=\)\(\&\%\$\#\"\<\>\Ñ\ñ\+]/, '');
    var num = c.value.replace(/\./g, '');
    if (!isNaN(num)) {
      num = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g, '$1.');
      num = num.split('').reverse().join('').replace(/^[\.]/, '');
      c.value = num;
    }
  }
</script>