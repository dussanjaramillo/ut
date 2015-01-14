<div style="overflow: hidden; padding: 9px 0" class="row show-grid">
  <div class="text-center">
    <h2><?php echo $title ?></h2>
    <?php if (isset($stitle)) : ?>
      <h3><?php echo $stitle ?></h3>
    <?php endif; ?>
  </div>
</div>
<div style="border: 1px solid grey; overflow: hidden; padding: 9px 0" class="row show-grid center-form-xlarge"> <span class="span2"><strong>NIT:</strong> <?php echo number_format($empresa['CODEMPRESA'], 0, ",", ".") ?></span> <span class="span3"><strong>RAZÓN SOCIAL:</strong> <?php echo $empresa['RAZON_SOCIAL']; ?></span> <span class="span3"><strong>DIRECCIÓN:</strong> <?php echo $empresa['DIRECCION']; ?></span> <span class="span2"><strong>TELÉFONO:</strong> <?php echo (!empty($empresa['TELEFONO_FIJO'])) ? $empresa['TELEFONO_FIJO'] : $empresa['TELEFONO_CELULAR']; ?></span>
  <?php if (!empty($obligaciones)) : ?>
    <div class="span11 row show-grid" style="border: 1px solid grey; overflow: hidden; padding: 9px 0; margin-top: 15px;">
      <table class="table-bordered" style="width: 98%" align="center">
        <caption><h4>Obligaciones</h4></caption>
        <thead>
        <th>Sel</th>
        <th>Concepto</th>
        <th>Período</th>
        <th>Total liquidado</th>
        <th>Saldo de la deuda</th>
        </thead>
        <tbody>
          <?php
          foreach ($obligaciones as $obligacion) :
            echo "<tr>\n\t";
            echo '<td><input type="radio" class="deuda" data-id="' . $obligacion['NUM_LIQUIDACION'] . '" data-value="' . $obligacion['SALDO_DEUDA'] . '" name="deuda" value="' . $obligacion['NUM_LIQUIDACION'] . '" /></td>' . "\n\t";
            echo "<td>" . $obligacion['NOMBRE_CONCEPTO'] . " - " . $obligacion['TIPO_PROCESO'] . "</td>\n\t";
            if (!empty($obligacion['FECHA_INICIO'])) :
              $tmp = explode("-", $obligacion['FECHA_INICIO']);
              $periodo = reclasificacion_pagos::mes($tmp[1]) . "-" . $tmp[0];
              $tmp = explode("-", $obligacion['FECHA_FIN']);
              $periodo .= " a " . Reclasificacion_pagos::mes($tmp[1]) . "-" . $tmp[0];
            else :
              $periodo = "Indefinido";
            endif;
            echo "<td>" . $periodo . "</td>\n\t";
            echo "<td>$ " . number_format($obligacion['TOTAL_LIQUIDADO'], 2, ",", ".") . "</td>\n\t";
            echo "<td>$ " . number_format($obligacion['SALDO_DEUDA'], 2, ",", ".") . "</td>\n\t";
            echo "</tr>\n";
          endforeach;
          ?>
        </tbody>
      </table>
    </div>
    <div class="span11 row show-grid" style="border: 1px solid grey; overflow: hidden; padding: 9px 0; margin-top: 15px;">
      <table class="table-bordered" style="width: 98%" align="center">
        <caption><h4>Pagos recibidos</h4></caption>
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
          if (!empty($pagos)) :
            foreach ($pagos as $pago) :
              echo "<tr>\n\t";
              echo '<td><input type="checkbox" name="pago[]" class="pago" data-value="' . $pago['VALOR_PAGADO'] . '" data-id="' . $pago['COD_PAGO'] . '" /></td>' . "\n\t";
              echo "<td>" . $pago['NOMBRE_CONCEPTO'] . "</td>\n\t";
              echo "<td>" . $pago['NOMBRE_REGIONAL'] . "</td>\n\t";
              if (!empty($pago['PERIODO_PAGADO'])) :
                $tmp = explode("-", $pago['PERIODO_PAGADO']);
                $periodo = Reclasificacion_pagos::mes2($tmp[1]) . "-" . $tmp[0];
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
          else :
            ?>
            <tr>
              <td colspan="7"><div style="clear: both;" class="alert alert-info">No hay pagos registrados sin aplicar para este NIT</div></td>
            </tr>
  <?php endif; ?>
        </tbody>
      </table>
    </div>
    <div class="span11" style="border: 1px solid grey; overflow: hidden; padding: 9px 0; margin-top: 15px;">
      <?php
      $attributes = array("id" => "myformajax");
      echo form_open("reclasificacion_pagos/otroscontribuyentes", $attributes);
      $data = array(
          'name' => 'nitorg',
          'id' => 'nitorg',
          'value' => $empresa['CODEMPRESA'],
          'type' => 'hidden'
      );
      echo form_input($data);
      ?>
      <span class="span2">
        <label for="nit">Identificación:</label>
      </span> <span class="span3">
        <?php
        $data = array(
            'name' => 'nit',
            'id' => 'nit',
            'class' => 'validate[required, custom[onlyNumber], minSize[6], maxSize[20]]'
        );
        echo form_input($data);
        ?>
        <span class="required">*</span> <img id="preloadmini" src="<?php echo base_url('img/319.gif') ?>" width="28" height="28" /> </span> <span class="span2">
        <label for="documento">No. Documento</label>
      </span> <span class="span3">
        <?php
        $data = array(
            'name' => 'documento',
            'id' => 'documento',
            'class' => 'validate[custom[onlyNumber]]'
        );
        echo form_input($data);
        ?>
      </span><br>
      <br>
      <span class="span2">
        <label for="fecha_pago">Fecha pago</label>
      </span> <span class="span3">
        <?php
        $data = array(
            'name' => 'fecha_pago',
            'id' => 'fecha_pago',
            'readonly' => 'readonly'
        );
        echo form_input($data);
        ?>
      </span> <span class="span2">
        <label for="estado">Estado</label>
      </span> <span class="span3">
        <select name="estado" id="estado">
          <option value="">Seleccione el Estado...</option>
          <option value="1">Aplicado</option>
          <option value="0">Sin Aplicar</option>
          <option value="TODOS">TODOS</option>
        </select>
      </span><br>
      <br>
      <span class="span2">
        <label for="regional">Regionales</label>
      </span> <span class="span3">
        <select name="regional" id="regional">
          <?php
          echo '<option value="">Seleccione la regional...</option>';
          foreach ($regionales as $regional) :
            echo '<option value="' . $regional['COD_REGIONAL'] . '">' . $regional['NOMBRE_REGIONAL'] . '</option>';
          endforeach;
          ?>
        </select>
      </span> <span class="span2">
        <label for="valor">Valor</label>
      </span> <span class="span3">
        <?php
        $data = array(
            'name' => 'valor',
            'id' => 'valor',
            'class' => 'validate[custom[onlyNumber], minSize[1]]'
        );
        echo form_input($data);
        ?>
      </span><br>
      <br>
      <span class="span10 text-center" style="margin-bottom: 10px">
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
        ?>
      </span> <?php echo form_close(); ?>
      <div class="resultados">
        <table id="tablaq">
          <thead>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
    <?php
    $attributes = array("id" => "myform", "onSubmit" => "VerificarPago()");
    echo form_open("reclasificacion_pagos/reclasificar", $attributes);
    ?>
    <div class="span5" style="border: 1ps solid grey; overflow: hidden; padding: 9px 0; margin-top: 15px;">
      <table class="table-bordered" style="width: 100%" align="center" cellpadding="3">
        <tbody>
          <tr>
            <td width="60%">Valor Obligaciones</td>
            <td>
              <div class="input-prepend">
                <span class="add-on">$</span>
                <?php
                $data = array(
                    'name' => 'valor_obligaciones',
                    'id' => 'valor_obligaciones',
                    'type' => 'text',
                    'class' => 'form-control'
                );
                echo form_input($data);
                ?>
              </div>
            </td>
          </tr>
          <tr>
            <td>Valor Ingresos</td>
            <td>
              <div class="input-prepend">
                <span class="add-on">$</span>
                <?php
                $data = array(
                    'name' => 'valor_ingresos',
                    'id' => 'valor_ingresos',
                    'type' => 'text'
                );
                echo form_input($data);
                ?>
              </div>
            </td>
          </tr>
          <tr>
            <td>Diferencia</td>
            <td>
              <div class="input-prepend">
                <span class="add-on">$</span>
                <?php
                $data = array(
                    'name' => 'diferencia',
                    'id' => 'diferencia',
                    'type' => 'text',
                    'class' => 'validate[min[0]]'
                );
                echo form_input($data);
                ?>
              </div>
            </td>
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
    <div class="span5" style="border: 1ps solid grey; overflow: hidden; padding: 9px 0; margin-top: 15px;">
    	<table class="table-bordered" style="width: 100%" align="center" cellpadding="3">
        <tbody>
          <tr>
            <td width="60%">Responsable reclasificación</td>
            <td>
							<?php
							$user = $this->ion_auth->user()->row();
              $data = array(
                  'name' => 'responsable',
                  'id' => 'responsable',
                  'type' => 'text',
                  'class' => 'validate[required]',
									'value' => $user->IDUSUARIO,
									'readonly' => 'readonly'
              );
              echo form_input($data);
              ?>
            </td>
          </tr>
          <tr>
            <td>Nro. Radicado OnBase</td>
            <td>
							<?php
              $data = array(
                  'name' => 'radicado_onbase',
                  'id' => 'radicado_onbase',
                  'type' => 'text',
                  'class' => 'validate[required]'
              );
              echo form_input($data);
              ?>
            </td>
          </tr>
          <tr>
            <td>Fecha de radicado OnBase</td>
            <td>
							<?php
              $data = array(
                  'name' => 'fecha_onbase',
                  'id' => 'fecha_onbase',
                  'type' => 'text',
                  'class' => 'validate[required]',
                  'readonly' => 'readonly'
              );
              echo form_input($data);
              ?>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="span11 row show-grid" style="border: 1ps solid grey; overflow: hidden; padding: 9px 0; margin-top: 15px; text-align: center">
    	<?php
      $data = array(
          'name' => 'button',
          'id' => 'submit-button',
          'value' => 'Reclasificar pago',
          'type' => 'submit',
          'content' => '<i class="fa fa-floppy-o fa-lg"></i> Reclasificar Pago',
          'class' => 'btn btn-primary addpagobtn'
      );

      echo form_button($data);
      ?> 
      <?php echo anchor('reclasificacion_pagos', '<i class="fa fa-minus-circle"></i> Cancelar', 'class="btn btn-warning btn-lg"'); ?>
    </div>
  <?php form_close(); ?>
    <!--<div class="span7" style="overflow: hidden; padding: 9px 0; margin-top: 15px;">
          Observaciones<br>
          <textarea name="observaciones" id="observaciones" class="span7" rows="3"></textarea>
        </div>-->
<?php else : ?>
    <div style="clear: both;" class="alert alert-warning"><?php echo $message; ?></div>
    <a class="btn btn-default" href="<?php echo base_url('index.php/reclasificacion_pagos/index') ?>"><i class="fa fa-arrow-circle-left fa-lg"></i> Regresar</a>
<?php endif; ?>
</div>
<div id="myModal" class="modal hide fade">
  <div class="modal-header">
    <h3>Alerta</h3>
  </div>
  <div class="modal-body">
    <p>No ha seleccionado la obligación a la cual va a aplicar el pago. Por favor Seleccione una obligación y luego proceda a seleccionar el/los pago(s).</p>
  </div>
  <div class="modal-footer"> <a href="#" data-dismiss="modal" class="btn">Close</a> </div>
</div>
<div id="myModal2" class="modal hide fade">
  <div class="modal-header">
    <h3>Alerta</h3>
  </div>
  <div class="modal-body">
    <p>No existen pagos asociados a este número de identificación.</p>
  </div>
  <div class="modal-footer"> <a href="#" data-dismiss="modal" class="btn">Close</a> </div>
</div>
<script type="text/javascript" language="javascript" charset="utf-8">
  $(document).ready(function() {
    $(".addpagobtn").attr("disabled", true);
    $(".preload, .load, #preloadmini, #preloadmini2, #tablaq").hide();
    $("#fecha_pago, #fecha_onbase").datepicker({
      buttonText: "Selecciona una fecha",
      buttonImage: "<?php echo base_url() . "img/calendario.png" ?>",
      buttonImageOnly: true,
      defaultDate: "-1w",
      dateFormat: "yy-mm-dd",
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
    $("#nit").autocomplete({
      source: "<?php echo base_url("index.php/reclasificacion_pagos/traernits?nit=") ?>" + $("#nitorg").val(),
      minLength: 3,
      search: function(event, ui) {
        $("#preloadmini").show();
      },
      response: function(event, ui) {
        $("#preloadmini").hide();
      }
    });
    $(".pago").click(function() {
      //alert($(this).attr("data-value"));
      var tc = 0;
      $.each($(".deuda"), function(indice, campo) {
        if ($(this).is(':checked') == true) {
          tc += 1;
        }
        else {
        }
      });
      if (tc == 0) {
        $('#myModal').modal();
        $.each($(".pago"), function(indice, campo) {
          if ($(this).is(':checked'))
            $(this).attr('checked', false);
        });
        $(".addpagobtn").attr("disabled", true);
      }
      else {
        var pagos = x = 0;
        var idpago = new Array();
        $.each($(".pago"), function(indice, campo) {
          if ($(this).is(':checked')) {
            //alert($(this).attr("data-id"));
            pagos += parseFloat($(this).attr("data-value"));
            idpago[x] = $(this).attr("data-id");
            x++;
          }
        });
        if (pagos == 0) {
          $("#valor_ingresos, #diferencia").val("");
          $(".addpagobtn").attr("disabled", true);
        }
        else {
          $("#valor_ingresos").val(pagos);
          $("#diferencia").val($("#valor_obligaciones").val() - pagos);
          idpago = idpago.toString();
          $("#idpago").val(idpago);
          $(".addpagobtn").attr("disabled", false);
        }
      }
    });
  });

  // Called once the server replies to the ajax form validation request
  function ajaxValidationCallback(status, form, json, options) {
    if (json.status === true) {
      if (json.status == "error") {
        var tabla = $("#tablaq").dataTable();
        tabla.fnDestroy();
        $("#tablaq").hide();
        //alert(json.data);
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
        $(".pago").click(function() {
          //alert($(this).attr("data-value"));
          var tc = 0;
          $.each($(".deuda"), function(indice, campo) {
            if ($(this).is(':checked') == true) {
              tc += 1;
            }
            else {
            }
          });
          if (tc == 0) {
            $('#myModal').modal();
            $.each($(".pago"), function(indice, campo) {
              if ($(this).is(':checked'))
                $(this).attr('checked', false);
            });
            $(".addpagobtn").attr("disabled", true);
          }
          else {
            var pagos = x = 0;
            var idpago = new Array();
            $.each($(".pago"), function(indice, campo) {
              if ($(this).is(':checked')) {
                //alert($(this).attr("data-id"));
                pagos += parseFloat($(this).attr("data-value"));
                idpago[x] = $(this).attr("data-id");
                x++;
              }
            });
            if (pagos == 0) {
              $("#valor_ingresos, #diferencia").val("");
              $(".addpagobtn").attr("disabled", true);
            }
            else {
              $("#valor_ingresos").val(pagos);
              $("#diferencia").val($("#valor_obligaciones").val() - pagos);
              idpago = idpago.toString();
              $("#idpago").val(idpago);
              $(".addpagobtn").attr("disabled", false);
            }
          }
        });
      }
    }
    else {
      $('#myModal2').modal();
    }
  }
</script>