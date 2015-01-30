<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>

<div id="ajax_load" class="ajax_load" style="display: none">
  <div class="preload" id="preload" ></div>
  <img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
</div>
<div class="center-form-xlarge">
	<?php echo $custom_error; ?>
  <div class="text-center">
    <h2> Cierre y Terminación del Proceso<?php //echo $action_label ?></h2>
  </div>
  <div id="total_form">
    <div id="error"></div>
    <div class="center-form-large">
      <div id="plantilla_list">
        <div class="controls controls-row">
          <div class="span4">
            <?php
//						if($secretario == false and $subir_archivo == false) :
//							echo form_label('Plantilla', 'CODPLANTILLA');
//							$select = array();
//							$select[''] = 'Seleccione';
//							foreach($plantillas as $row) :
//								$select[$row->CODPLANTILLA] = $row->NOMBRE_PLANTILLA;
//							endforeach;
//							echo form_dropdown('CODPLANTILLA', $select, '', 'id="cargo" onchange="getPlantilla(this.value,' . 
//																 $auto->COD_PROCESO_COACTIVO . ')" class="chosen span3" placeholder="seleccione..." ');
//						endif;
            ?>
          </div>
        </div>
      </div>
      <div id="display_form">
        <?php 
				echo form_open(base_url() . 'index.php/verfpagosprojuridicos/save2', 
											'method="post" id="form_projuridicos" onsubmit="return validar()" enctype="multipart/form-data"');
				echo form_hidden('COD_PROCESO_COACTIVO'    , $auto->COD_PROCESO_COACTIVO);
				echo form_hidden('NUM_AUTOGENERADO'     , $auto->NUM_AUTOGENERADO);
				?>
          
          <div style="text-align: center;   ">Código Proceso: <span style="color:red"><?php echo $this->data['proceso']['COD_PROCESOPJ'];    ?>  </span><br><br></div>
        <div class="controls controls-row">
        
          <div class="span4">
            <?php
						echo form_label('Identificación', 'NIT_EMPRESA');
						$data = array('name'        => 'NIT_EMPRESA',
													'id'          => 'NIT_EMPRESA',
													'value'       => $auto->NIT_EMPRESA,
													'class'      => 'span3',
													'readonly'   => 'readonly'
												);
						echo form_input($data);
						?>
          </div>
          <div class="span4">
            <?php
						echo form_label('Ejecutado', 'RAZON_SOCIAL');
						$data = array('name'        => 'RAZON_SOCIAL',
													'id'          => 'RAZON_SOCIAL',
													'value'       => $auto->RAZON_SOCIAL,
													'class'       => 'span3',
													'readonly'    => 'readonly'
										);
						echo form_input($data);
						?>
          </div>
        </div>
        <div class="controls controls-row">
          <div class="span4">
            <?php
						echo form_label('Concepto', 'concepto');
						$data = array('name'        => 'concepto',
													'id'          => 'concepto',
													'value'       => $auto->NOMBRE_CONCEPTO,
													'class'       => 'span3',
													'readonly'    => 'readonly'
										);
						echo form_input($data);
						?>
          </div>
          <div class="span4">
            <?php
						echo form_label('Instancia', 'instancia');
						$data = array('name'        => 'instancia',
													'id'          => 'instancia',
													'value'       => 'Cobro Coactivo',
													'class'       => 'span3',
													'readonly'    => 'readonly'
										);
						echo form_input($data);
					?>
          </div>
        </div>
        <div class="controls controls-row">
          <div class="span4">
            <?php
						echo form_label('Representante Legal', 'representante_legal');
						$data = array('name'        => 'representante_legal',
													'id'          => 'representante_legal',
													'value'       => $auto->REPRESENTANTE_LEGAL,
													'class'       => 'span3',
													'readonly'    => 'readonly'
										);
						echo form_input($data);
						?>
          </div>
          <div class="span4">
            <?php
						echo form_label('Teléfono', 'telefono');
						$data = array('name'        => 'telefono',
													'id'          => 'telefono',
													'value'       => $auto->TELEFONO_FIJO,
													'class'       => 'span3',
													'readonly'    => 'readonly'
										);
						echo form_input($data);
						?>
          </div>
        </div>
        <div class="controls controls-row">
          <div class="span8">
            <?php echo form_label('Documento <span class="required">*</span>', 'documento'); ?>
          </div>
          <div class="span7">
            <?php
						
							$data = array('name'			=> 'documento',
														'id'				=> 'documento',
														'value'			=> $documento,
														'class'			=> 'span7',
														'width'			=> '100%',
														'cols'			=> '70'
										);
							echo form_textarea($data);
						
						?>
          </div>
        </div>
        <br>
        <div class="controls controls-row">
          <div class="span7">
            <?php
						echo form_label('Comentarios', 'COMENTARIOS');
						$data = array('name'			=> 'COMENTARIOS',
													'id'				=> 'COMENTARIOS',
													'value'			=> $auto->COMENTARIOS,
													'class'			=> 'span7',
													'width'			=> '100%',
													'height'		=> '150px',
													'cols'			=> '70'
										);
//						if($subir_archivo == true) : $data["disabled"] = "disabled"; endif;
//						echo form_textarea($data);
						?>
          </div>
        </div>
        <div class="controls controls-row">
          <div class="span4">
            <?php
                                                    if($secretario == TRUE) :
							
                                                       if( $cod_respuesta==1135 || $cod_respuesta==382):
                                                            echo form_label('¿Aprueba el Auto?', 'DEVOLVER_A');
                                                        else:
                                                           echo form_label('¿Pre-Aprueba el Auto ?', 'DEVOLVER_A');
                                                        endif;
							$data = array('name'	=> 'DEVOLVER_A',
													'id'			=> 'DEVOLVER_A',
													'value'		=> "S",
													'class'		=> 'pull-left'
										);
							echo form_radio($data);
                                                        if( $cod_respuesta==1135 || $cod_respuesta==382):
                                                            echo form_label('&nbsp;APROBADO&nbsp;', 'DEVOLVER_A', array('class' => "pull-left"));
                                                        else:
                                                            echo form_label('&nbsp;PRE-APROBADO&nbsp;', 'DEVOLVER_A', array('class' => "pull-left"));
                                                        endif;
							
							$data = array('name'	=> 'DEVOLVER_A',
													'id'			=> 'DEVOLVER_A',
													'value'		=> "N",
													'class'		=> 'pull-left'
										);
							echo form_radio($data);
							echo form_label('&nbsp;DEVOLVER', 'DEVOLVER_A', array('class' => "pull-left"));
						endif;
						echo form_error('DEVOLVER_A','<div>','</div>');
//						if($id_grupo == 41 || $secretario == false) :
//							if($subir_archivo == false) :
//								echo '<div style="clear:both">&nbsp;</div>'.form_label('Asignar a ' . $asignar, 'ASIGNADO_A');
//								//$select = array("0" => "Seleccione el funcionario");
//								foreach($secretarios as $row) :
//									$select[$row->IDUSUARIO] = $row->NOMBREUSUARIO;
//								endforeach;
//								echo form_dropdown('ASIGNADO_A', $select, (((isset($auto)) && ($auto != NULL)) ? $auto->ASIGNADO_A : ''),
//																 'id=ASINGNADO_A" class="chosen span3" placeholder="seleccione..." ');
//							endif;
//						endif;
						?>
          </div>
        </div>
        <div class="controls controls-row">
          <div class="span7">
            <?php
						if($subir_archivo == true) :
							echo form_label('Documento Firmado', 'nombre_doc_firmado_upload');
							$data = array('name'	=> 'userfile',
														'id'		=> 'nombre_doc_firmado_upload',
														'class'	=> 'span3',
											);
							echo form_upload($data);
							?>
          </div>
          <div style="clear:both">&nbsp;</div>
          <div class="span4">
          		<?php
							echo form_label('Fecha radicado el documento', 'telefono');
							$data = array('name'        => 'fecha_doc_radicado',
														'id'          => 'fecha_radicado',
														'value'       => '',
														'class'       => 'span3',
														'readonly'    => 'readonly'
											);
							echo form_input($data);
							?>
          </div>
          <div class="span4">
          		<?php
							echo form_label('Número de radicado del documento', 'telefono');
							$data = array('name'        => 'numradicado',
														'id'          => 'num_radicado',
														'value'       => '',
														'class'       => 'span3',
											);
							echo form_input($data);
						endif;
						?>
          </div>
          <div class="span7">
            <?php
						$data = array('name' => 'button',
													'id' => 'submit-button',
													'value' => 'PDF',
													'type' => 'button', 
                                                                                                        'onclick'=>'generar_pdf()',
													'content' => '<i class="fa fa-file fa-lg"></i> PDF',
													'class' => 'btn btn-info'
										);
						echo form_button($data)." ";
//						$data = array('name' => 'button',
//													'id' => 'submit-button',
//													'value' => 'Imprimir',
//													'type' => 'submit',
//													'content' => '<i class="fa fa-print fa-lg"></i> Imprimir',
//													'class' => 'btn btn-info'
//										);
						//echo form_button($data);
						echo '&nbsp;<a href="'.base_url('index.php/bandejaunificada/index').'" class="btn btn-warning btn-lg" role="button"><i class="fa fa-ban fa-lg"></i> Cancelar</a> ';
						$data = array('name' => 'button',
													'id' => 'submit-button',
													'value' => 'Guardar',
													'type' => 'submit',
													'content' => '<i class="fa fa-floppy-o fa-lg"></i> Guardar',
													'class' => 'btn btn-success'
										);
							echo form_button($data);
						?>
          </div>
        </div>
      </div>
    </div>
  </div>
    <input type="hidden" name="cod_respuesta" id="cod_respuesta" value="<?php echo $cod_respuesta?>">
       <?php echo form_close(); ?>
</div><div id="respuesta"></div>
<form id="form_pdf" name="form_pdf" target = "_blank"  method="post" action="<?php echo base_url('index.php/verfpagosprojuridicos/pdf') ?>">
    <textarea id="descripcion_pdf" name="descripcion_pdf" style="width: 100%;height: 300px; display:none"></textarea>  
    <input type="hidden" name="nombre_archivo" id="nombre_archivo">
</form>
<div class="alert alert-success" id="alert" style="display: none">
  <button class="close" data-dismiss="alert" type="button">×</button>
  El auto se <?php echo ( ($auto == NULL) ? ' almaceno' : 'actualizo' )?> con exito. </div>
<script type="text/javascript" language="javascript" charset="utf-8">
$(document).ready(function() {
	$("#fecha_radicado").datepicker({
		dateFormat: "yy/mm/dd",
		changeMonth: true,
		maxDate: "0"
	});
});
function base64_encode(data)
        {
            var b64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
            var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
                    ac = 0,
                    enc = '',
                    tmp_arr = [];
            if (!data)
            {
                return data;
            }
            do
            {
                o1 = data.charCodeAt(i++);
                o2 = data.charCodeAt(i++);
                o3 = data.charCodeAt(i++);
                bits = o1 << 16 | o2 << 8 | o3;
                h1 = bits >> 18 & 0x3f;
                h2 = bits >> 12 & 0x3f;
                h3 = bits >> 6 & 0x3f;
                h4 = bits & 0x3f;
                tmp_arr[ac++] = b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
            }
            while (i < data.length);
            enc = tmp_arr.join('');
            var r = data.length % 3;
            return (r ? enc.slice(0, r - 3) : enc) + '==='.slice(r || 3);
        }
        function generar_pdf()
        {
            var informacion = tinymce.get('documento').getContent();
            if (informacion == '' || informacion == false)
            {
                mierror = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe ingresar la informaciÃ³n del Requerimiento de Acercamiento en el Texto Enriquecido' + '</div>';
                document.getElementById("respuesta").innerHTML = mierror;
                $("#respuesta").fadeOut(3000);
                return false;
            }
            document.getElementById("descripcion_pdf").value = base64_encode(informacion);
            $("#form_pdf").submit();
        }
function subirPantalla() {
	$('html, body').animate({
			scrollTop: '0px'
		}, 1500);
	return false;
}

function toggle(id) {
	var ele = document.getElementById(id);
	if(ele.style.display == "block") {
		ele.style.display = "none";
	} else {
		ele.style.display = "block";
	}
}

function openDiv(id) {
	var ele = document.getElementById(id);
	ele.style.display = "block";
}

function closeDiv(id) {
	var ele = document.getElementById(id);
	ele.style.display = "none";
}

function save() {
	tinyMCE.triggerSave();//asigna el contenido
	var url = "<?php echo base_url()?>index.php/verfpagosprojuridicos/save2"; // El script a dónde se realizará la petición.
	$.ajax({
		type: "POST",
		url: url,
		data: $("#form_projuridicos").serialize(), // Adjuntar los campos del formulario enviado.
		success: function(data) {
			$("#total_form").toggle();
			$("#alert").toggle();
			$(".ajax_load").toggle("slow");
		}
	});
}

function validar() {
	subirPantalla();
	openDiv('ajax_load');

	var ed = tinyMCE.get('documento');
	var data_documento = ed.getContent();

	if($.trim(data_documento) == '') {
		alert('En el campo "Documento" se encuentra vacio');
		$(".ajax_load").toggle();
			return false;
		}
//		if($.trim($('#COMENTARIOS').val()) == '') {
//			alert('El campo "Comentario" se encuentra vacio');
//			$(".ajax_load").toggle();
//			return false;
   // }
}

function getPlantilla(id_plantilla, id_fiscalizacion) {
	//openDiv('ajax_load');
	//$(".ajax_load").toggle();
	//openDiv('load');
	if(id_plantilla == '') {
		alert('No a seleccionado una plantilla');
	} else {
		$(".ajax_load").toggle("slow");
		$.get('<?php echo base_url()?>index.php/verfpagosprojuridicos/getAjaxPlantilla/' + id_plantilla + '/' + id_fiscalizacion, function(data) {
			var ed = tinyMCE.get('documento');
			ed.setContent(data);
			openDiv('display_form');
			$(".ajax_load").toggle("slow");
		});
	}
	//closeDiv('preload');
	//closeDiv('load');
}

tinymce.init({
	language : 'es',
	selector: "textarea#documento",
	theme: "modern",
	width: "670px",
	height: "600px",
	plugins: ["advlist autolink lists link  charmap print preview hr anchor pagebreak", 
						"searchreplace wordcount visualblocks visualchars code fullscreen",
						//"insertdatetime media nonbreaking save table contextmenu directionality",
						//"emoticons template paste textcolor moxiemanager"
					 ],
	toolbar1: "insertfile undo redo | styleselect | bold italic underline strikethrough | superscript subscript | print preview media | forecolor backcolor emoticons | alignleft aligncenter alignright alignjustify | link | bullist numlist outdent indent | image",
	image_advtab: true,
	templates: [{title: 'Test template 1', content: '<b>Test 1</b>'}, {title: 'Test template 2', content: '<em>Test 2</em>'}],
	autosave_ask_before_unload: false
});
</script> 