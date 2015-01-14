<div class="preload"></div>
<img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<?php
if($pagos != false) :
	$attributes = array('id' => 'myform');
	echo form_open("devolucion_pagos/pagos", $attributes);
	$data = array(
			'name'			=> 'cod_devolucion',
			'type'			=> 'hidden',
			'readonly'	=> 'readonly',
			'value'			=> $cod
	);
	echo form_input($data);
endif;
?>
<div class="text-center">
	<h2><?php echo $title ?></h2>
  <h4 class="titulo"><?php echo $stitle ?></h4>
</div>
<?php
if (isset($message)) {
  echo $message;
}
if($pagos != false) :
?>
<div class="control-group" style="border: 1px solid grey; overflow: hidden; padding: 9px 0 0">
	<div class="span"><strong>FECHA ACTUAL:</strong></div>
  <div class="span">
    <input type="text" class="input-small" name="fecha_actual" id="fecha_actual" readonly value="<?php echo date("Y/m/d"); ?>" />
  </div>
  <div class="span"><strong>NIT:</strong></div>
  <div class="span input-medium nit"><?php echo number_format($empresa['CODEMPRESA'], 0, ",", ".") ?></div>
  <div class="span"><strong>RAZÓN SOCIAL:</strong></div>
  <div class="span razon"><?php echo $empresa['NOMBRE_EMPRESA'] ?></div>
</div>
<br>
<div class="control-group" style="border: 1px solid grey; overflow: hidden; padding: 9px 0 0">
	<table class="table table-bordered">
  	<thead class="btn-success">
    	<th align="center">&nbsp;</th>
      <th align="center">Ref.</th>
      <th align="center">Valor pagado</th>
      <th align="center">Saldo para devoluciones</th>
      <th align="center">Devolver todo</th>
      <th align="center">Valor a devolver</th>
    </thead>
    <tbody>
    	<?php $x = 1; foreach($pagos as $pago) : ?>
    	<tr>
      	<td><input type="checkbox" id="check_<?php echo $pago['COD_PAGO'] ?>" name="ref[<?php echo $pago['COD_PAGO'] ?>][devolver]" value="<?php echo $pago['COD_PAGO'] ?>" /></td>
        <td><?php echo $pago['COD_PAGO'] ?></td>
        <td>$ <?php echo number_format($pago['VALOR_PAGADO'], 0, ",", ".") ?></td>
        <td>$ <?php echo number_format($pago['VALOR_PAGADO']-$pago['VALOR_DEVUELTO'], 0, ",", ".") ?></td>
        <td><input type="checkbox" class="todo" data-value="<?php echo $pago['COD_PAGO'] ?>" /></td>
        <td><input type="text" class="devuelto validate[custom[onlyNumberFormat], maxvalue[ref_<?php echo $pago['COD_PAGO'] ?>_saldo]]" data-value="<?php echo $pago['COD_PAGO'] ?>" id="devuelto_<?php echo $pago['COD_PAGO'] ?>" name="ref[<?php echo $pago['COD_PAGO'] ?>][devuelto]" tabindex="<?php echo $x++; ?>" /></td>
        <input type="hidden" name="ref[<?php echo $pago['COD_PAGO'] ?>][pagado]" value="<?php echo $pago['VALOR_PAGADO'] ?>" />
        <input type="hidden" id="ref_<?php echo $pago['COD_PAGO'] ?>_saldo" name="ref[<?php echo $pago['COD_PAGO'] ?>][saldo]" value="<?php echo $pago['VALOR_PAGADO']-$pago['VALOR_DEVUELTO'] ?>" />
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <br>
  <table class="table table-bordered" style="width: 50%" align="center">
  	<tbody>
    	<tr>
      	<td width="50%">Valor total devoluciones</td>
      	<td><?php
            $data = array(
                'name'			=> 'valor_total_devoluciones',
                'id'				=> 'valor_total_devoluciones',
                'type'			=> 'text',
								'readonly'	=> 'readonly',
								'class'			=> 'validate[required, min[1]]'
            );
            echo form_input($data);
          ?>
				</td>
			</tr>
		</tbody>
	</table>
</div>
<div class="control-group">
	<div class="span3">
	<?php
		$data = array(
				'name' => 'button',
				'id' => 'submit-button',
				'value' => 'Reclasificar pago',
				'type' => 'submit',
				'content' => '<i class="fa fa-floppy-o fa-lg"></i> Guardar devoluciones',
				'class' => 'btn btn-primary addpagobtn'
		);

		echo form_button($data);
	?>
  </div>
	<div class="span3">
	<?php
		echo anchor('devolucion/Menu_GestionDevoluciones', '&nbsp;<i class="fa fa-minus-circle"></i> Cancelar', 'class="btn btn-warning btn-lg"');
	?>
  </div>
</div>
<?php
	echo form_close();
endif;
?>
<script type="text/javascript" language="javascript" charset="utf-8">
  $(document).ready(function() {
    $(".preload, .load").hide();
		
		$(".todo").on("change", function(){
			if($(this).is(":checked") == true) {
				$("#devuelto_"+$(this).attr("data-value")).val($("#ref_"+$(this).attr("data-value")+"_saldo").val()).attr("readonly", "readonly");
				$("#check_"+$(this).attr("data-value")).prop("checked", true);
			}
			else {
				$("#devuelto_"+$(this).attr("data-value")).val("").attr("readonly", false);
				$("#check_"+$(this).attr("data-value")).prop("checked", false);
			}
			var total = 0;
			$.each($(".devuelto"), function(indice, campo) {
				if($(this).val() != "") {
					valor = $(this).val().replace(/\D/g, "");
	        total += parseInt(valor);
				}
			});
			$("#valor_total_devoluciones").val(numero(total));
			var num = $("#devuelto_"+$(this).attr("data-value")).val();
			$("#devuelto_"+$(this).attr("data-value")).val(numero(num));
		});
		
		$(".devuelto").on({
			"keyup": function() {
				if($(this).val() != "") {
					if($("#check_"+$(this).attr("data-value")).is(":checked") == false) {
						$("#check_"+$(this).attr("data-value")).prop("checked", true);
					}
				}
				else {
					if($("#check_"+$(this).attr("data-value")).is(":checked") == true) {
						$("#check_"+$(this).attr("data-value")).prop("checked", false);
					}
				}
				var total = 0;
				$.each($(".devuelto"), function(indice, campo) {
					if($(this).val() != "") {
						valor = $(this).val().replace(/\D/g, "");
						total += parseInt(valor);
					}
				});
				$("#valor_total_devoluciones").val(numero(total));
				var num = $("#devuelto_"+$(this).attr("data-value")).val();
				$("#devuelto_"+$(this).attr("data-value")).val(numero(num));
			},
			'onkeypress': function() {
				if($(this).val() != "") {
					if($("#check_"+$(this).attr("data-value")).is(":checked") == false) {
						$("#check_"+$(this).attr("data-value")).prop("checked", true);
					}
				}
				else {
					if($("#check_"+$(this).attr("data-value")).is(":checked") == true) {
						$("#check_"+$(this).attr("data-value")).prop("checked", false);
					}
				}
				var total = 0;
				$.each($(".devuelto"), function(indice, campo) {
					if($(this).val() != "") {
						valor = $(this).val().replace(/\D/g, "");
						total += parseInt(valor);
					}
				});
				$("#valor_total_devoluciones").val(numero(total));
				var num = $("#devuelto_"+$(this).attr("data-value")).val();
				$("#devuelto_"+$(this).attr("data-value")).val(numero(num));
			},
			'onblur': function() {
				if($(this).val() != "") {
					if($("#check_"+$(this).attr("data-value")).is(":checked") == false) {
						$("#check_"+$(this).attr("data-value")).prop("checked", true);
					}
				}
				else {
					if($("#check_"+$(this).attr("data-value")).is(":checked") == true) {
						$("#check_"+$(this).attr("data-value")).prop("checked", false);
					}
				}
				var total = 0;
				$.each($(".devuelto"), function(indice, campo) {
					if($(this).val() != "") {
						valor = $(this).val().replace(/\D/g, "");
						total += parseInt(valor);
					}
				});
				$("#valor_total_devoluciones").val(numero(total));
				var num = $("#devuelto_"+$(this).attr("data-value")).val();
				$("#devuelto_"+$(this).attr("data-value")).val(numero(num));
			},
			'onkeyup': function() {
				if($(this).val() != "") {
					if($("#check_"+$(this).attr("data-value")).is(":checked") == false) {
						$("#check_"+$(this).attr("data-value")).prop("checked", true);
					}
				}
				else {
					if($("#check_"+$(this).attr("data-value")).is(":checked") == true) {
						$("#check_"+$(this).attr("data-value")).prop("checked", false);
					}
				}
				var total = 0;
				$.each($(".devuelto"), function(indice, campo) {
					if($(this).val() != "") {
						valor = $(this).val().replace(/\D/g, "");
						total += parseInt(valor);
					}
				});
				$("#valor_total_devoluciones").val(numero(total));
				var num = $("#devuelto_"+$(this).attr("data-value")).val();
				$("#devuelto_"+$(this).attr("data-value")).val(numero(num));
			},
			"change": function() {
				if($(this).val() != "") {
					if($("#check_"+$(this).attr("data-value")).is(":checked") == false) {
						$("#check_"+$(this).attr("data-value")).prop("checked", true);
					}
				}
				else {
					if($("#check_"+$(this).attr("data-value")).is(":checked") == true) {
						$("#check_"+$(this).attr("data-value")).prop("checked", false);
					}
				}
				var total = 0;
				$.each($(".devuelto"), function(indice, campo) {
					if($(this).val() != "") {
						valor = $(this).val().replace(/\D/g, "");
						total += parseInt(valor);
					}
				});
				$("#valor_total_devoluciones").val(numero(total));
				var num = $("#devuelto_"+$(this).attr("data-value")).val();
				$("#devuelto_"+$(this).attr("data-value")).val(numero(num));
			},
		});
		
		setTimeout(
			function(){
				document.getElementById( "myform" ).reset();
			}, 5);
	});
        
	function numero(valor) {
		var nums = new Array();
    var simb = "."; //Éste es el separador
    valor = valor.toString();
    valor = valor.replace(/\D/g, "");   //Ésta expresión regular solo permitira ingresar números
    nums = valor.split(""); //Se vacia el valor en un arreglo
    var long = nums.length - 1; // Se saca la longitud del arreglo
    var patron = 3; //Indica cada cuanto se ponen las comas
    var prox = 2; // Indica en que lugar se debe insertar la siguiente coma
    var res = "";
 
    while (long > prox) {
        nums.splice((long - prox),0,simb); //Se agrega la coma
        prox += patron; //Se incrementa la posición próxima para colocar la coma
    }
 
    for (var i = 0; i <= nums.length-1; i++) {
        res += nums[i]; //Se crea la nueva cadena para devolver el valor formateado
    }
 
    return res;
	}
	
	function ajaxValidationCallback() {}
</script>