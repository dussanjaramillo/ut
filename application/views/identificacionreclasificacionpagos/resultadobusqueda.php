<div style="overflow: hidden; padding: 9px 0" class="row show-grid">
  <div class="text-center"><h3><?php echo $title ?><?php if (isset($stitle)) : ?><br><small><?php echo $stitle ?></small><?php endif; ?></h3></div>
</div>
<div style="border: 1px solid grey; overflow: hidden; padding: 9px 0" class="row show-grid">
  <span class="span2"><strong>NIT:</strong> <?php echo $empresa['CODEMPRESA'] ?></span>
  <span class="span3"><strong>RAZÓN SOCIAL:</strong> <?php echo $empresa['RAZON_SOCIAL']; ?></span>
  <span class="span3"><strong>DIRECCIÓN:</strong> <?php echo $empresa['DIRECCION']; ?></span>
  <span class="span2"><strong>TELÉFONO:</strong> <?php echo (!empty($empresa['TELEFONO_FIJO'])) ? $empresa['TELEFONO_FIJO'] : $empresa['TELEFONO_CELULAR']; ?></span>
  <?php if (!empty($obligaciones)) : ?>
    <div class="span5" style="border: 1px solid grey; overflow: hidden; padding: 9px 0; margin-top: 15px;" class="row show-grid">
      <table class="table-bordered" style="width: 90%" align="center">
        <caption>Obligaciones</caption>
        <thead>
        <th>Sel</th>
        <th>Concepto</th>
        <th>Período</th>
        <th>Valor</th>
        </thead>
        <tbody>
          <?php
          foreach ($obligaciones as $obligacion) :
            echo "<tr>\n\t";
            echo '<td><input type="radio" class="deuda" data-id="' . $obligacion['NUM_LIQUIDACION'] . '" data-value="' . $obligacion['TOTAL_LIQUIDADO'] . '" name="deuda" value="' . $obligacion['NUM_LIQUIDACION'] . '" /></td>' . "\n\t";
            echo "<td>" . $obligacion['NOMBRE_CONCEPTO'] . "</td>\n\t";
            if (!empty($obligacion['FECHA_INICIO'])) :
              $tmp = explode("/", $obligacion['FECHA_INICIO']);
              $periodo = Identificacion_reclasificacion_pagos::mes($tmp[1]) . "-" . date("Y", strtotime("20" . $tmp[2]));
              $tmp = explode("/", $obligacion['FECHA_FIN']);
              $periodo .= " a " . Identificacion_reclasificacion_pagos::mes($tmp[1]) . "-" . date("Y", strtotime("20" . $tmp[2]));
            else :
              $periodo = "Indefinido";
            endif;
            echo "<td>" . $periodo . "</td>\n\t";
            echo "<td>$ " . number_format($obligacion['TOTAL_LIQUIDADO'], 2, ",", ".") . "</td>\n\t";
            echo "</tr>\n";
          endforeach;
          ?>
        </tbody>
      </table>
    </div>
    <div class="span6" style="border: 1px solid grey; overflow: hidden; padding: 9px 0; margin-top: 15px;" class="row show-grid">
      <table class="table-bordered" style="width: 98%" align="center">
        <caption>Pagos recibidos</caption>
        <thead>
        <th>Sel</th>
        <th>Concepto</th>
        <th>Regional</th>
        <th>Período</th>
        <th>Valor</th>
        <th>Obligacion</th>
        <th>Estado</th>
        </thead>
        <tbody>
          <?php
          if(!empty($pagos)) :
            foreach ($pagos as $pago) :
              echo "<tr>\n\t";
              echo '<td><input type="checkbox" name="pago[]" class="pago" data-value="' . $pago['VALOR_PAGADO'] . '" data-id="' . $pago['COD_PAGO'] . '" /></td>' . "\n\t";
              echo "<td>" . $pago['NOMBRE_CONCEPTO'] . "</td>\n\t";
              echo "<td>" . $pago['NOMBRE_REGIONAL'] . "</td>\n\t";
              if (!empty($pago['PERIODO_PAGADO'])) :
                $tmp = explode("-", $pago['PERIODO_PAGADO']);
                $periodo = Identificacion_reclasificacion_pagos::mes2($tmp[1]) . "-" . $tmp[0];
              else :
                $periodo = "Indefinido";
              endif;
              echo "<td>" . $periodo . "</td>\n\t";
              echo "<td>$ " . number_format($pago['VALOR_PAGADO'], 2, ",", ".") . "</td>\n\t";
              echo "<td></td>\n\t";
              echo "<td>";
              echo ($pago['APLICADO'] == 1) ? "Aplicado" : "Sin aplicar";
              echo "</td>\n\t";
              echo "</tr>\n";
            endforeach;
          else : ?>
          <tr><td colspan="7"><div style="clear: both;" class="alert alert-info">No hay pagos registrados sin aplicar para este NIT</div></td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
    <div class="span11" style="border: 1px solid grey; overflow: hidden; padding: 9px 0; margin-top: 15px;">
      <?php
      $attributes = array("id" => "myformajax");
      echo form_open("identificacion_reclasificacion_pagos/otroscontribuyentes", $attributes);
      $data = array(
          'name' => 'nitorg',
          'id' => 'nitorg',
          'value' => $empresa['CODEMPRESA'],
          'type' => 'hidden'
      );
      echo form_input($data);
      ?>
      <span class="span2"><label for="nit">Identificación:</label></span>
      <span class="span3"><?php
        $data = array(
            'name' => 'nit',
            'id' => 'nit',
            'class' => 'validate[required, custom[onlyNumber], minSize[9], maxSize[9]]'
        );
        echo form_input($data);
        ?>
        <img id="preloadmini" src="<?php echo base_url('img/319.gif') ?>" width="28" height="28" />
      </span>
      <span class="span2"><label for="documento">No. Documento</label></span>
      <span class="span3">
        <?php
        $data = array(
            'name' => 'documento',
            'id' => 'documento',
            'class' => 'validate[custom[onlyNumber]]'
        );
        echo form_input($data);
        ?>
      </span><br><br>
      <span class="span2"><label for="fecha_pago">Fecha pago</label></span>
      <span class="span3"><?php
        $data = array(
            'name' => 'fecha_pago',
            'id' => 'fecha_pago',
            'class' => 'validate[custom[date]]'
        );
        echo form_input($data);
        ?></span>
      <span class="span2"><label for="estado">Estado</label></span>
      <span class="span3">
        <select name="estado" id="estado">
          <option value="">Seleccione el Estado...</option>
          <option value="1">Aplicado</option>
          <option value="0">Sin Aplicar</option>
          <option value="TODOS">TODOS</option>
        </select>
      </span><br><br>
      <span class="span2"><label for="regional">Regionales</label></span>
      <span class="span3">
        <select name="regional" id="regional"><?php
          echo '<option value="">Seleccione la regional...</option>';
          foreach ($regionales as $regional) :
            echo '<option value="' . $regional['COD_REGIONAL'] . '">' . $regional['NOMBRE_REGIONAL'] . '</option>';
          endforeach;
          ?></select>
      </span>
      <span class="span2"><label for="valor">Valor</label></span>
      <span class="span3"><?php
        $data = array(
            'name' => 'valor',
            'id' => 'valor',
            'class' => 'validate[custom[onlyNumber], minSize[1]]'
        );
        echo form_input($data);
        ?></span><br><br>
      <span class="span10 text-center" style="margin-bottom: 10px">
        <?php
        $data = array(
            'name' => 'button',
            'id' => 'submit-button',
            'value' => 'seleccionar',
            'type' => 'submit',
            'content' => 'Buscar',
            'class' => 'btn btn-success'
        );
        echo form_button($data);
        ?></span>
      <?php echo form_close(); ?>
      <div class="resultados">
        <table id="tablaq">
          <thead></thead>
        </table>
      </div>
    </div>
    <?php
      $attributes = array("id" => "myform");
      echo form_open("identificacion_reclasificacion_pagos/reclasificar", $attributes);
    ?>
    <div class="span4" style="border: 1ps solid grey; overflow: hidden; padding: 9px 0; margin-top: 15px;">
      <table class="table-bordered" style="width: 98%" align="center">
        <tbody>
          <tr><td width="50%">Valor Obligaciones</td><td><?php
            $data = array(
                'name' => 'valor_obligaciones',
                'id' => 'valor_obligaciones',
                'type' => 'text'
            );
            echo form_input($data);
          ?></td></tr>
          <tr><td>Valor Ingresos</td><td><?php
            $data = array(
                'name' => 'valor_ingresos',
                'id' => 'valor_ingresos',
                'type' => 'text'
            );
            echo form_input($data);
          ?></td></tr>
          <tr><td>Diferencia</td><td><?php
            $data = array(
                'name' => 'diferencia',
                'id' => 'diferencia',
                'type' => 'text'
            );
            echo form_input($data);
          ?></td>
          </tr>
        </tbody>
      </table>
    </div>
    <?php
      $data = array(
          'name' => 'iddeuda',
          'id' => 'iddeuda',
          'type' => 'hidden'
      );
      echo form_input($data);
      $data = array(
          'name' => 'idpago',
          'id' => 'idpago',
          'type' => 'hidden'
      );
      echo form_input($data);
      $data = array(
          'name' => 'nit',
          'id' => 'nit',
          'type' => 'hidden',
          'value' => $empresa['CODEMPRESA']
      );
      echo form_input($data);
    ?>
    <div class="span4" style="border: 1ps solid grey; overflow: hidden; padding: 9px 0; margin-top: 15px;"><?php
      $data = array(
          'name' => 'button',
          'id' => 'submit-button',
          'value' => 'Reclasificar pago',
          'type' => 'submit',
          'content' => '<i class="fa fa-floppy-o fa-lg"></i> Reclasificar Pago',
          'class' => 'btn btn-primary addpagobtn'
      );

      echo form_button($data);
      ?></div>
    <?php form_close(); ?>
    <!--<div class="span7" style="overflow: hidden; padding: 9px 0; margin-top: 15px;">
      Observaciones<br>
      <textarea name="observaciones" id="observaciones" class="span7" rows="3"></textarea>
    </div>-->
  <?php else : ?>
    <div style="clear: both;" class="alert alert-warning"><?php echo $message; ?></div>
  <?php endif; ?>
</div>
<div id="myModal" class="modal hide fade">
  <div class="modal-header">
    <h3>Alerta</h3>
  </div>
  <div class="modal-body">
    <p>No ha seleccionado la obligación a la cual va a aplicar el pago. Por favor Seleccione una obligación y luego proceda a seleccionar el/los pago(s).</p>
  </div>
  <div class="modal-footer">
    <a href="#" data-dismiss="modal" class="btn">Close</a>
  </div>
</div>
<script type="text/javascript" language="javascript" charset="utf-8">
  $(document).ready(function() {
    $(".addpagobtn").attr("disabled", true);
    $(".preload, .load, #preloadmini, #preloadmini2, #tablaq").hide();
    $("#fecha_pago").datepicker({
      defaultDate: "-1w",
      dateFormat: "dd/mm/yy",
      changeMonth: true,
      maxDate: "0",
      minDate: "-5y",
      changeYear: true
    });    
    $(".deuda").click(function() {
      $("#valor_obligaciones").val($(this).attr("data-value"));
      var iddeuda = $(this).attr("data-id");
      $("#iddeuda").val(iddeuda);
    });
    $(".pago").click(function() {
      $.each($(".deuda"), function(indice, campo) {
        if($(this).is(':checked') == false) {
          $('#myModal').modal();
          $.each($(".pago"), function(indice, campo) {
            if($(this).is(':checked')) $(this).attr('checked', false);
          });
          $(".addpagobtn").attr("disabled", true);
        }
        else {
          var pagos = 0;
          var idpago = "";
          $.each($(".pago"), function(indice, campo) {
            if($(this).is(':checked')) {
              pagos += parseFloat($(this).attr("data-value"));
              idpago = $("#idpago").val();
              if(idpago == "") idpago += $(this).attr("data-id");
              else idpago+","+$(this).attr("data-id");
              $("#idpago").val(idpago);
            }
          });
          if(pagos == 0) {
            $("#valor_ingresos, #diferencia").val("");
            $(".addpagobtn").attr("disabled", true);
          }
          else {
            $("#valor_ingresos").val(pagos);
            $("#diferencia").val($("#valor_obligaciones").val()-pagos);
            $(".addpagobtn").attr("disabled", false);
          }
        }
      });
    });
    $("#nit").autocomplete({
      source: "<?php echo base_url("index.php/identificacion_reclasificacion_pagos/traernits?nit=") ?>" + $("#nitorg").val(),
      minLength: 3,
      search: function(event, ui) {
        $("#preloadmini").show();
      },
      response: function(event, ui) {
        $("#preloadmini").hide();
      }
    });
  });

  // Called once the server replies to the ajax form validation request
  function ajaxValidationCallback(status, form, json, options) {
    if (status === true) {
      if (json.status == "error") {
        var tabla = $("#tablaq").dataTable();
        tabla.fnDestroy();
        $("#tablaq").hide();
        alert(json.data);
      } else {
        var cols = "";
        cols = "<tr>";
        $.each(json.cols, function(key, val) {
          cols += "<th>" + val.replace("_", " ") + "</th>\n";
        });
        cols += "</tr>";
        $("#tablaq thead").html(cols);
        $("#tablaq").show();
        var tabla = $('#tablaq').dataTable({
          "bProcess": true,
          "bFilter": false,
          "bSort": false,
          "bDestroy": true,
          "sPaginationType": "full_numbers",
          "aaData": json.aaData,
          "aoColumns": json.cols,
        });
      }
    }
    else {
      alert('error de consulta de datos');
    }
  }
</script>