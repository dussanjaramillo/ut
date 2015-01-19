<br><br>
<center>
    <div style="color:#FC7323"><h2>RECEPCIÓN ESTUDIO DE TITULO Y AVOCA CONOCIMIENTO</h2></div>
    <h2>Estudio de Títulos</h2>
    <br>
</center>

<div class="info" id="info">
    <?php require_once('encabezado.php'); ?>
</div>
<br>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<div class="caja_negra" id="caja_negra" style="width: 90%; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 0">
    <br>
    <div style="width: 60%; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 15px" >
        <?php
        echo '<center><b>El Deudor  Está Registrado Como Persona  ' . $encabezado->TIPO_PERSONA . '</b></center>';
        ?>
    </div>
    <br>
    <div class="Exigibilidades" style="width: 80%; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 0px" >
        <form id="form1" name="form1" method="post" action="">
            <center><h3>Verificar Exigibilidad del Título</h3></center><br>
            <center>
                <?php
                $i = 0;
                foreach ($tipo_exigibilidad as $data) {
                    $opciones[$i] = array(
                        'name' => 'id_opcion' . $i,
                        'id' => 'Opciones_Exigibles' . $i,
                        'value' => $data->COD_TIPOEXIGILIDAD,
                        'checked' => FALSE,
                        'style' => 'margin: 10px',
                        'onclick' => 'checkeador()'
                    );

                    echo form_checkbox($opciones[$i], 'opcion' . $i);
                    ?><?php echo $data->NOMBRE_TIPOEXIGIBILIDAD; ?>

                    <?php
                    $i++;
                }
                ?>
            </center>
        </form>
        <?php
        echo form_label('<b>Comentarios</b><span class="required"></span>', 'lb_comentarios');
        $datacomentarios = array(
            'name' => 'comentarios_exigibilidad',
            'id' => 'comentarios_exigibilidad',
            'maxlength' => '300',
            'class' => 'span9 validate[required]',
            'rows' => '3',
            'required' => 'required'
        );
        echo form_textarea($datacomentarios);
        ?>
        <br><br>
    </div>   
    <br>
    <div class="Reorganizacion" style="width: 80%; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 0px" >
        <form id="form1" name="form1" method="post" action="">
            <center><h3>Verificar Reorganizacion</h3></center><br>
            <table width="100%" border="0" align="center">
                <tr>
                    <td width="23%"><input type="radio" name="radio" id="radio_pc" value="Regimen de Insolvencia" checked="checked" />                        
                        Procesos Concursales</td>
                    <td width="26%"><input type="radio" name="radio" id="radio_ri" value="Regimen de Insolvencia"/>
                        Régimen de Insolvencia</td>
                    <td width="13%"><input type="radio" name="radio" id="radio_otro" value="Regimen de Insolvencia" />                        
                        Otros</td>
                    <td width="38%"><input type="radio" name="radio" id="radio_noreorganizacion" value="No Organizacion" />                        
                        No esta en Proceso de Reorganización</td>
                </tr>
            </table>
            <br>
            <center>
                <div class="Concursal" id="Concursal">
                    <select name="select" id="tipo_concursal">
                        <option value="Regimen de Insolvencia">Liquidacion Forzosa</option>
                        <option value="Regimen de Insolvencia">Concordato</option>
                        <option value="Regimen de Insolvencia">Restructuración</option>
                    </select>
                </div>
                <div class="Otros" id="Otros">¿Cuál? <input type="text" name="txt_otros" id="txt_otros" /></div>
            </center>

        </form>
        <?php
        echo form_label('<b>Comentarios</b><span class="required"></span>', 'lb_comentarios');
        $datacomentarios = array(
            'name' => 'comentarios_reorganizacion',
            'id' => 'comentarios_reorganizacion',
            'maxlength' => '300',
            'class' => 'span9 validate[required]',
            'rows' => '3',
            'required' => 'required'
        );
        echo form_textarea($datacomentarios);
        ?>
        <br><br>
    </div>    
    <br>
    <div class="Prescripcion" style="width: 60%; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 5px 5px 0px">
        <br>
        <form id="form6" name="form4" method="post" action="">
            <center><h3>Prescripción del Título</h3></center><br>
            <center>
                <input type="radio" name="prescrito" id="Si_Prescrito" value="<?php echo TITULO_PRESCRITO; ?>"  style="margin: 5px" /> Título Prescrito                    
                <input type="radio" name="prescrito" id="No_Prescrito" value="<?php echo TITULO_VIGENTE; ?>" style="margin: 5px"/> Título aún Vigente
            </center>
            <center>
                <div class="tiempo_prescripcion" id="tiempo_prescripcion">
                    <b>Tiempo de Prescripción  (años): </b><br>
                    <input class="input-small" type="text" name="txt_tiempo" id="txt_tiempo" />
                </div>
            </center>        
        </form>
    </div>
    <br>
    <div class="Acumulacion" style="width: 60%; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 5px 5px 0px">
        <br>
        <form id="form6" name="form4" method="post" action="">
            <center><h3>Acumulación de Títulos</h3><br>            
                <input type="radio" name="acumulacion" id="acumulacion" value="<?php echo ACUMULACION_TITULOS; ?>" checked=""   style="margin: 5px" /> Avocar Conocimiento Acumulando el Título                   
                <input type="radio" name="acumulacion" id="acumulacion" value="<?php echo NO_ACUMULACION_TITULOS; ?>" style="margin: 5px" />Avocar Conocimiento Sin Acumular el Título
            </center>
            <br>
        </form>
    </div>    
    <br> 
    <div class="Proximo" style="width: 60%; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 5px 5px 0px">
        <br>
        <form id="form6" name="form4" method="post" action="">
            <center><h4>¿Título Próximo a Prescribir?</h4><br>            
                <input type="radio" name="proximo" id="si_proximo" value="1"  style="margin: 5px" checked="" />Título Esta Próximo  a Prescribir                  
                <input type="radio" name="proximo" id="no_proximo" value="0" style="margin: 5px" />Título No Esta Próximo  a Prescribir
            </center>
        </form>
    </div>    
    <br>
    <br> 

</div>
<center>
    <br>
    <?php
    $data_5 = array(
        'name' => 'button',
        'id' => 'info_boton',
        'value' => 'info',
        'content' => '<i class="fa fa-eye"></i> Información del Ejecutado',
        'class' => 'btn btn-primary info_boton'
    );
    $data = array(
        'name' => 'button',
        'id' => 'Enviar',
        'value' => 'Confirmar',
        'type' => '',
        'content' => '<i class="fa fa-floppy-o fa-lg"></i> Confirmar',
        'class' => 'btn btn-success'
    );
    echo form_button($data_5) . "  " . form_button($data);
    ?>
</center>
<br>
<?php
echo form_close();
?>

<div id="consulta" ></div>
<script>
    $(".info").hide();
    $(".Tipo_Juridico").hide();
    $(".preload, .load").hide();
    $(".Concursal").hide();
    $(".Otros").hide();
    $(".Prescripcion").hide();
    $(".Acumulacion").hide();
    $(".Reorganizacion").hide();
    $(".tiempo_prescripcion").hide();
    $(".Proximo").hide();
    $('#info_boton').click(function() {
        $(".info").show();
    });
    function checkeador() {
        var check = $("input[type='checkbox']:checked").length;
        var cantidad = <?php echo sizeof($tipo_exigibilidad); ?>;
        if (cantidad == check) {
            $(".Prescripcion").hide();
            $(".Acumulacion").hide();
            $(".Reorganizacion").show();
            alert('Titulos Exigibles');
        } else {
            $(".Prescripcion").hide();
            $(".Acumulacion").hide();
            $(".Reorganizacion").show();
        }
    }
    $('#radio_otro').click(function() {
        $(".Otros").show();
        $(".Concursal").hide();
        $(".Prescripcion").hide();
    });
    $('#radio_noreorganizacion').click(function() {
        $(".Otros").hide();
        $(".Concursal").hide();
        $(".Prescripcion").show();
    });
    $('#radio_ri').click(function() {
        $(".Otros").hide();
        $(".Concursal").hide();
        $(".Prescripcion").hide();
    });
    $('#radio_pc').click(function() {
        $(".Otros").hide();
        $(".Concursal").show();
        $(".Prescripcion").hide();
    });
    $('#Si_Prescrito').click(function() {
        $(".Acumulacion").hide();
        $(".Proximo").hide();
        $(".tiempo_prescripcion").show();
    });
    $('#No_Prescrito').click(function() {
        $(".Acumulacion").show();
        $(".Proximo").show();
        $(".tiempo_prescripcion").hide();
    });
    $('#No_Exigible').click(function() {
        var persona = $("input[name='Persona']:checked").val();
        if (persona == 'PN') {
            $(".Razones_NoExe").hide();
            $(".Titulo_Prescribir").show();
        } else {
            $(".Razones_NoExe").show();
            $(".Titulo_Prescribir").show();
        }

    });
    $('#Si_Exigible').click(function() {
        $(".Razones_NoExe").hide();
        $(".Titulo_Prescribir").show();
    });
    $('#Enviar').click(function() {
        $(".preload, .load").show();
        var cod_respuesta = '';
        var reorganizacion = '';
        var tiempo = '';
        var comentarios_reorganizacion = '';
        var comentarios_exigibilidad = '';
        var proximo_prescribir = '0';
        var check = $("input[type='checkbox']:checked").length;
        var cantidad = <?php echo sizeof($tipo_exigibilidad); ?>;
        var opcion_1 = $("input[name='radio']:checked").val();
            if (opcion_1 == 'Regimen de Insolvencia') {
                comentarios_exigibilidad = $("#comentarios_exigibilidad").val();
                comentarios_reorganizacion = $("#comentarios_reorganizacion").val();
                tiempo = 0;
                cod_respuesta = '<?php echo TITULO_REGIMEN_INSOLVENCIA ?>';
            } else {
                if (opcion_1 == 'No Organizacion') {
                    comentarios_exigibilidad = $("#comentarios_exigibilidad").val();
                    comentarios_reorganizacion = $("#comentarios_reorganizacion").val();
                    var opcion_2 = $("input[name='prescrito']:checked").val();
                    if (opcion_2 == <?php echo TITULO_PRESCRITO; ?>) {
                        tiempo = $("#txt_tiempo").val();
                        cod_respuesta = <?php echo TITULO_PRESCRITO ?>;
                    } else {
                        tiempo = 0;
                        cod_respuesta = $("input[name='acumulacion']:checked").val();
                        proximo_prescribir = $("input[name='proximo']:checked").val();
                    }
                } else {
                    cod_respuesta = <?php echo TITULO_REORGANIZACION ?>;
                    comentarios_exigibilidad = $("#comentarios_exigibilidad").val();
                    comentarios_reorganizacion = $("#comentarios_reorganizacion").val();
                    if (opcion_1 == 'Procesos Concursales') {
                        reorganizacion = $("#tipo_concursal").val();
                    } else if (opcion_1 == 'Otros') {
                        reorganizacion = $("#txt_otros").val();
                    } else {
                        reorganizacion = opcion_1;
                    }
                }
            }
			var cod_titulo = "<?php echo $cod_titulo; ?>";
			var url = '<?php echo base_url('index.php/recepciontitulos/Guardar_ExigibilidadTitulo') ?>';
			$.post(url, {cod_titulo: cod_titulo, cod_respuesta: cod_respuesta, proximo_prescribir: proximo_prescribir, tiempo: tiempo, reorganizacion: reorganizacion, comentarios_exigibilidad: comentarios_exigibilidad, comentarios_reorganizacion: comentarios_reorganizacion})
					.done(function(msg) {
						$(".preload, .load").hide();
						alert('Se ha verificado el estado de los títulos');
						location.href = '<?php echo base_url('index.php/bandejaunificada') ?>';
					}).fail(function(smg, fail) {
				$(".preload, .load").hide();
				alert('Se ha verificado el estado de los títulos');
				$(".preload, .load").hide();
			});
        
			

    });

</script> 


