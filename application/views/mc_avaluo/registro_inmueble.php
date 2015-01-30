<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
if (isset($message)) {
    echo $message;
}

if (isset($custom_error))
    echo $custom_error;
?><br><br>
<div id="mensaje"></div>
<h4 style="text-align: center">Registro Avalúo Inmueble</h4>
<h4>Información Obtenida del Avalúo.</h4>


<form name="myform2" id="myform2" method="post" action="<?php echo base_url('index.php/mc_avaluo/guardar_registro_avaluo') ?>">
    <input type="hidden" name="cod_avaluo" id="cod_avaluo" value="<?php echo $post['cod_avaluo'] ?>">
    <input type="hidden" name="id" id="id" value="<?php echo $post['id'] ?>">
    <input type="hidden" name="cod_tipo_bien" id="cod_tipo_bien" value="2">
    <input type="hidden" name="tipo_inmueble" id="tipo_inmueble" value="2">
    <div id="id_tabla1" style="text-aling:center;">
    </div>
    <table>
        <tr>
            <td class="sub" style="text-align:left; margin-top:25px;  " colspan="4">
                <br /><b> Identificación del bien:<br></b> </td>
        </tr>
        <tr>
            <td  style="padding:0 0 0 15px; " ><span>Ubicación</span></td> 
            <td  style="text-align:left;"> 
                <input type="text"  style="height:30px;" name="ubicacion" id="ubicacion" value="<?php echo $consulta[0]['UBICACION_BIEN'] ?>">
            </td>
            <td  style="text-align:left;">Dirección</td> 
            <td  style="text-align:left;">   
                <input type="text" style="height:30px;" name="direccion" id="direccion" value="<?php echo $consulta[0]['DIRECCION_BIEN'] ?>">
            </td>
        </tr>       <tr>
            <td style="padding:0 0 0 15px; ">Localización</td> 
            <td  style="text-align:left;">  
                <select name="localizacion" id="localizacion">
                    <?php if ($consulta[0]['LOCALIZACION']): ?>
                        <option value="<?php echo $consulta[0]['LOCALIZACION'] ?>"><?php echo $consulta[0]['LOCALIZACION'] ?></option>
                    <?php endif; ?>
                    <option value="URBANO">URBANO</option>
                    <option value="URBANO">RURAL</option>
                </select>
            </td>
            <td class="sub" style="padding:0 0 0 15px;" ><br>Tipo de Inmueble</td>
            <td  style="text-align:left;">   <br>
                <select name="tipo_inmueble" id="tipo_inmueble" class="validate[required]" ><?php
                    echo '<option value="">Elija una opción...</option>';
                    foreach ($tipos_inmuebles as $tipo_inmueble) :
                        echo '<option value="' . $tipo_inmueble['COD_TIPOINMUEBLE'] . '">' . $tipo_inmueble['NOMBRE_INMUEBLE'] . '</option>';
                    endforeach;
                    ?></select>
            </td>
        </tr>
        <tr>
            <td  style="text-align:left; " ><br>Area Total</td>
            <td  style="text-align:left;height:30px;"> <br>  
                <?php
                $data = array('name' => 'area_total_bien', 'id' => 'area_total_bien', 'class' => 'validate[required]', 'maxlength' => '15', 'style' => 'height:30px;',
                    'required' => 'required', 'value' => $consulta[0]['AREA_TOTAL']);
                echo form_input($data);
                ?>
            </td>
            <td  style="text-align:left;margin-top:20px;">Valor Avalúo del Bien</td> 
            <td  style="text-align:left;margin-top:20px;">
                <input type="text" name="costo_total" id="costo_total" value="" onkeypress="return soloNumeros(event),num(this)" onkeyup="num(this)" onblur="num(this)">
            </td> 
        </tr>
        <tr>
            <td  style="text-align:left;margin-top:20px;">Uso del Bien</td> 
            <td  style="text-align:left;margin-top:20px;">
                <select name="uso" id="uso">
                    <option value="COMERCIAL">COMERCIAL</option>
                    <option value="RESIDENCIAL">RESIDENCIAL</option>
                </select>
            </td>
        </tr>


    </table>
</div>
<div id="id_tabla2" style="display:block; margin-top: 0px;">   
    <table id="tabla">
        <tr>
            <td class="sub" style="text-align:left;  " colspan="4"><b>Avaluo</b></td>
        </tr> 
        <td  style="text-align:left; margin-top:0px;   " colspan="1" ><br>Tipo Propiedad<br></td>  
        <td  style="text-align:left; margin-top:0px;   " colspan="1" >
            <select name="tipo_propiedad" id="tipo_propiedad" class="validate[required]" ><?php
                echo '<option value="">Seleccione una opción...</option>';
                foreach ($tipos_propiedades as $tipo_propiedad) :
                    echo '<option value="' . $tipo_propiedad['COD_TIPOPROPIEDAD'] . '">' . $tipo_propiedad['NOMBRE_PROPIEDAD'] . '</option>';
                endforeach;
                ?></select>   
            </tr>
    </table>
    <div>
        <div id="id_tabla3" style="display:block">
            <table id="tabla">
                <tr>
                    <td class="sub" colspan="1" style="text-align:left;"><br>Observaciones</td>
                    <td colspan="3"><br>
                        <textarea id="observaciones" name="observaciones" style="width:500px;height: 100px"><?php echo $consulta[0]['OBSERVACIONES'] ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td  style="text-align:left;" ><br>Elaboro:</td>
                    <td>      
                        <input type="text" name="elaboro" id="elaboro" style="height:30px;"  value="<?php echo $consulta[0]['ELABORO'] ?>">
                    </td>
                    <td style="text-align:left;"><br>Identificación del Avaluador</td>
                    <td> 
                        <input type="text" name="cod_avaluador" id="cod_avaluador" style="height:30px;" value="<?php echo $consulta[0]['COD_AVALUADOR'] ?>" onkeypress="return soloNumeros(event)" >
                    </td>

                </tr>
                <tr>
                    <td  style="text-align:left;" ><br>Licencia N°:</td>
                    <td>   
                        <input type="text" name="num_licencia" id="num_licencia" style="height:30px;" value="<?php echo $consulta[0]['LICENCIA_NRO'] ?>" >
                    </td>
                    <td style="text-align:left;" ><br>Dirección</td>
                    <td>    
                        <input type="text" name="direccion_avaluador" id="direccion_avaluador" style="height:30px;" value="<?php echo $consulta[0]['DIRECCION'] ?>" >
                    </td>
                </tr>
                <tr>
                    <td  style="text-align:left;" ><br>Fecha:</td>
                    <td>    
                        <input type="text" name="fecha" id="fecha" style="height:30px;" value="<?php echo $consulta[0]['FECHA_AVALUO'] ?>" onkeypress="return prueba(event)">
                    </td>
                    <td style="text-align:left;"><br>Profesión</td>
                    <td>
                        <input type="text" name="profesion" id="profesion" style="height:30px;" value="<?php echo $consulta[0]['PROFESION'] ?>"></td>
                </tr>
                <tr>
                    <td colspan="4" >
                        <?php
                        $data = array(
                            'name' => 'button',
                            'id' => 'cancelar',
                            'value' => 'Cancelar',
                            'type' => 'button',
                            'style' => 'margin-left:120px;',
                            'content' => 'Cancelar',
                            'class' => 'btn btn-success',
                        );
                        echo form_button($data);
                        ?>
                        <input type="button" name="Aceptar" id="Aceptar" value="Guardar" class="btn btn-success" onclick="validar()" >
                    </td>
                </tr>
            </table>
        </div>

        </form>
        <div id="div_mensaje"></div>
        <!--        <div id="ajax_load" class="ajax_load" style="display:none">
                            <div class="preload" id="preload" ></div><img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
                        </div>-->
        <script>
            $(document).ready(function() {
                $("#fecha").datepicker({
                    dateFormat: "dd/mm/yy",
                    changeMonth: true,
                    maxDate: "0",
                    changeYear: true
                });
            });

            $(document).ready(function() {
                $("#ajax_load").hide();
                $('#resultado').dialog({
                    autoOpen: true,
                    width: 800,
                    height: 450,
                    modal: true,
                    title: "Registrar Detalle Avalúo Inmueble ",
                    close: function() {
                        $('#resultado *').remove();
                    }
                });
            });

            function tipopropiedad()
            {

                var tipo_propiedad = $('#tipo_propiedad').val();
                if (tipo_propiedad == 1) {
                    $('#tabla_construccion').css('display', 'none');
                    $('#tabla_terreno').css('display', 'block');
                }
                else if (tipo_propiedad == 2)
                {
                    $('#tabla_construccion').css('display', 'block');
                    $('#tabla_terreno').css('display', 'none');
                }
            }
            function ver()
            {
                $('#id_tabla1').css('display', 'none');
                $('#id_tabla2').css('display', 'block');
            }

            $('#siguiente_2').click(function()
            {
                $('#id_tabla1').css('display', 'none');
                $('#id_tabla2').css('display', 'none');
                $('#id_tabla3').css('display', 'block');
            })
            $('#atras_1').click(function()
            {
                $('#id_tabla1').css('display', 'block');
                $('#id_tabla2').css('display', 'none');
                $('#id_tabla3').css('display', 'none');
            })

            function validar()
            {
                $(".ajax_load").show();
                var ubicacion = $("#ubicacion").val();

                var direccion = $("#direccion").val();
                var localizacion = $("#localizacion").val();
                var uso = $("#uso").val();
                var tipo_inmueble = $("#tipo_inmueble").val();
                var observaciones = $("#observaciones").val();
                var elaboro = $("#elaboro").val();
                var cod_avaluador = $("#cod_avaluador").val();
                var direccion_avaluador = $("#direccion_avaluador").val();

                var tipo_propiedad = $("#tipo_propiedad").val();



                if (ubicacion == '' || ubicacion == false)
                {
                    mierror = '<div  class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe ingresar la información de la ubicación del bien' + '</div>';
                    document.getElementById("div_mensaje").innerHTML = mierror;
                    $("#div_mensaje").fadeIn("slow");
                    return false;
                }
                else if (direccion == '' || direccion == false)
                {
                    mierror = '<div  class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe ingresar la información de la dirección del bien' + '</div>';
                    document.getElementById("div_mensaje").innerHTML = mierror;
                    $("#div_mensaje").fadeIn("slow");
                    return false;
                }
                else if (localizacion == '' || localizacion == false)
                {
                    mierror = '<div   class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe ingresar la información de la localización del bien' + '</div>';
                    document.getElementById("div_mensaje").innerHTML = mierror;
                    $("#div_mensaje").fadeIn("slow");
                    return false;
                }
                else if (uso == '' || uso == false)
                {
                    mierror = '<div   class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe ingresar la información del uso del bien' + '</div>';
                    document.getElementById("div_mensaje").innerHTML = mierror;
                    $("#div_mensaje").fadeIn("slow");
                    return false;
                }
                else if (tipo_inmueble == '' || tipo_inmueble == false)
                {
                    mierror = '<div  class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe seleccionar el tipo de inmueble' + '</div>';
                    document.getElementById("div_mensaje").innerHTML = mierror;
                    $("#div_mensaje").fadeIn("slow");
                    return false;
                }
//                else if (observaciones == '' || observaciones == false)
//                {
//                    mierror = '<div  class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe ingresar las observaciones' + '</div>';
//                    document.getElementById("div_mensaje").innerHTML = mierror;
//                    $("#div_mensaje").fadeIn("slow");
//                    return false;
//                }
                else if (elaboro == '' || elaboro == false)
                {
                    mierror = '<div   class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe ingresar quien elaboro el avaluo' + '</div>';
                    document.getElementById("div_mensaje").innerHTML = mierror;
                    $("#div_mensaje").fadeIn("slow");
                    return false;
                }
                else if (cod_avaluador == '' || cod_avaluador == false)
                {
                    mierror = '<div  class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe ingresar la identificación del avaluador' + '</div>';
                    document.getElementById("div_mensaje").innerHTML = mierror;
                    $("#div_mensaje").fadeIn("slow");
                    return false;
                }
                else if (direccion_avaluador == '' || direccion_avaluador == false)
                {
                    mierror = '<div   class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe ingresar la dirección del Avaluador' + '</div>';
                    document.getElementById("div_mensaje").innerHTML = mierror;
                    $("#div_mensaje").fadeIn("slow");
                    return false;
                }

                else if (tipo_propiedad == '' || tipo_propiedad == false)
                {
                    mierror = '<div  class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe seleccionar el tipo de propiedad' + '</div>';
                    document.getElementById("div_mensaje").innerHTML = mierror;
                    $("#div_mensaje").fadeIn("slow");
                    return false;
                }
                else
                {
                    enviar();
                }

            }



            function ocultar()
            {
                // document.getElementById("div_mensaje").innerHTML = '';
            }
            setTimeout('ocultar()', 2000);
            function enviar() {
                var formData = new FormData($("#myform2")[0]);
                //$("#revision").remove();
                $(".ajax_load,.preload").show();
                $.ajax({
                    url: '<?php echo base_url('index.php/mc_avaluo/guardar_registro_avaluo') ?>',
                    type: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    //una vez finalizado correctamente
                    success: function(data) {
                        alert('Se guardo la información con éxito');
                        $("#div_mensaje").html(data);
                        $(".ajax_load").hide();
                        $('#resultado').dialog('close');
                        $('#resultado *').remove();
                    },
                    //si ha ocurrido un error
                    error: function() {
                        $(".ajax_load").hide();
                        alert("Ha Ocurrido un Error");
                    }
                });
            }
            function regresar()
            {
                window.location.href = "<?php echo base_url('index.php/mc_avaluo/abogado') ?>";
            }


            $(document).ready(function() {
                $('#cancelar').click(function() {
                    //   $('#div_contenido').dialog('close');
                    window.location.href = "<?php echo base_url('index.php/mc_avaluo/abogado') ?>";
                });
            });



            function soloNumeros(e)
            {
                var keynum = window.event ? window.event.keyCode : e.which;
                if ((keynum == 8) || (keynum == 46))
                    return true;
                return /\d/.test(String.fromCharCode(keynum));
            }

            //No permite editar el campo fecha_radicado
            function prueba(e) {
                tecla = (document.all) ? e.keyCode : e.which;
                if (tecla == 8)
                    return true; // backspace
                if (tecla == 32)
                    return true; // espacio
                if (e.ctrlKey && tecla == 86) {
                    return true;
                } //Ctrl v
                if (e.ctrlKey && tecla == 67) {
                    return true;
                } //Ctrl c
                if (e.ctrlKey && tecla == 88) {
                    return true;
                } //Ctrl x

                patron = /[a-zA-Z]/; //patron

                te = String.fromCharCode(tecla);
                return patron.test(te); // prueba de patron
            }
            	function num(c){
		c.value = c.value.replace(/[A-z-\/\*\,\ \;\:\{\}\[\]\^\`\¨\´\+\~\°\|\¬\!\¡\'\?\¿\=\)\(\&\%\$\#\"\<\>\Ñ\ñ\+]/, '');
		// x = c.value;
  		// c.value = x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
  		var num = c.value.replace(/\./g,'');
		if(!isNaN(num))
		{
			num = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
			num = num.split('').reverse().join('').replace(/^[\.]/,'');
			c.value = num;
		}
	}
        </script>

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




