<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
if (isset($message)) {
    echo $message;
}
//echo "<pre>";
//print_r($consulta);
//echo "</pre>";
if (isset($custom_error))
    echo $custom_error;
?><br><br>
<h4 style="text-align: center">Registro Avalúo Mueble</h4>
<h4>Información Obtenida del Avalúo.</h4>
<div id="ajax_load" class="ajax_load" style="display: none">
    <div class="preload" id="preload" ></div><img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
</div>
<form name="myform2" id="myform2" method="post" action="<?php echo base_url('index.php/mc_avaluo/guardar_registro_avaluo') ?>">
    <input type="hidden" name="tipo_inmueble" id="tipo_inmueble" value="1">
    <input type="hidden" name="cod_tipo_bien" id="cod_tipo_bien" value="1">
    <input type="hidden" name="cod_avaluo" id="cod_avaluo" value="<?php echo $post['cod_avaluo'] ?>">
    <input type="hidden" name="id" id="id" value="<?php echo $post['id'] ?>">
    <div id="id_tabla1">
        <table id="tabla" style="width:80%;">
            <tr>
                <td  style="text-align:left;margin-top:20px;">Descripción del mueble</td> 
                <td  style="text-align:left;margin-top:20px;">
                    <textarea  style="width:500px;height: 100px" name="descripcion_mueble" id="descripcion_mueble"><?php echo $this->data['@$detalle']['DESCRIPCION_MUEBLE'];?></textarea></td> 
            </tr>
            <tr>
                <td  style="text-align:left;margin-top:20px;">Valor Avalúo del Bien</td> 
                <td  style="text-align:left;margin-top:20px;">
                    <input type="text" name="costo_mueble" id="costo_mueble" onkeypress="return soloNumeros(event)" value="<?php echo $this->data['@$detalle']['COSTO_TOTAL'];?>">
                </td> 
            </tr>
            <tr>
                <td  style="text-align:left;margin-top:20px;">Observaciones</td> 
                <td  style="text-align:left;margin-top:20px;">
                    <textarea  style="width:500px;height: 100px" name="observaciones" id="observaciones">
                      <?php echo $this->data['@$detalle']['OBSERVACIONES'];?>
                    </textarea>
                </td> 
            </tr>
            <tr>
                <td  style="text-align:left;" ><br>Elaboro:</td>
                <td>      
                    <input type="text"  class="span4" name="elaboro" id="elaboro" value="<?php echo $consulta[0]['ELABORO']; ?>" >
                </td>
            </tr>
            <tr>
                <td style="text-align:left;"><br>Identificación del Avaluador</td>
                <td> 
                    <input type="text"  class="span4" name="cod_avaluador" id="cod_avaluador" value="<?php echo $consulta[0]['COD_AVALUADOR'];?>" onkeypress="return soloNumeros(event)" >
                </td>
            </tr>
            <tr>
                <td style="text-align:left;"><br>Profesión</td>
                <td>
                    <input type="text"   class="span4" name="profesion" id="profesion" value="<?php echo $consulta[0]['PROFESION']; ?>"></td>
            </tr>
            <tr>
                <td  style="text-align:left;" ><br>Licencia N°:</td>
                <td>   
                    <input type="text"  class="span4" name="num_licencia" id="num_licencia" value="<?php echo $consulta[0]['LICENCIA_NRO']; ?>" >
                </td>
            </tr>
            <tr>
                <td style="text-align:left;" ><br>Dirección</td>
                <td>    
                    <input type="text"  class="span4"  name="direccion_avaluador" id="direccion_avaluador" value="<?php echo $consulta[0]['DIRECCION'];?>">
                </td>
            </tr>
            <tr>
                <td  style="text-align:left;" ><br>Fecha:</td>
                <td>    
                    <input type="text"   class="span4" name="fecha" id="fecha" onkeypress="return prueba(event)" class="requerid" value="<?php echo $consulta[0]['FECHA_AVALUO'];?>"   >
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <br>
                    <div id="div_mensaje" style="text-align:center"></div>
                    <br>
                    <input type="button" class="btn btn-success" name="Guardar" id="Guardar" value="Guardar" onclick="validar()">
                </td>
            </tr>
        </table>
</form>

<script>
    $(document).ready(function() {
        $(".ajax_load,.preload").css('display','none');
        $("#fecha").datepicker({
            dateFormat: "yy/mm/dd",
            changeMonth: true,
            maxDate: "0",
            changeYear: true
        });
        $('#resultado').dialog({
            autoOpen: true,
            width: 900,
            height: 450,
            modal: true,
            title: "Registrar Detalle Avaluo",
            close: function() {
                $('#resultado *').remove();
            }

        });
    });
    function soloNumeros(e)
    {
        var keynum = window.event ? window.event.keyCode : e.which;
        if ((keynum == 8) || (keynum == 46))
            return true;
        return /\d/.test(String.fromCharCode(keynum));
    }
    function validar()
    {

        var descripcion_mueble = $("#descripcion_mueble").val();
        var costo_mueble = $("#costo_mueble").val();
        var elaboro = $("#elaboro").val();
        var cod_avaluador = $("#cod_avaluador").val();
        var profesion = $("#profesion").val();
        var num_licencia = $("#num_licencia").val();
        var direccion_avaluador = $("#direccion_avaluador").val();
        var fecha = $("#fecha").val();
      
        if (!descripcion_mueble)
        {
            mierror = '<div  class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe ingresar la información de la descripción del mueble' + '</div>';
            document.getElementById("div_mensaje").innerHTML = mierror;
            $("#div_mensaje").fadeOut(3000);
            return false;
        } else if (!costo_mueble) {
            mierror = '<div  class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe ingresar la información del costo del mueble' + '</div>';
            document.getElementById("div_mensaje").innerHTML = mierror;
            $("#div_mensaje").fadeOut(3000);
            return false;
        }
        else if (!elaboro) {
            mierror = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe ingresar la información de quien elaboro el avaluo' + '</div>';
            document.getElementById("div_mensaje").innerHTML = mierror;
            $("#div_mensaje").fadeOut(3000);
            return false;
        }
        else if (!cod_avaluador) {
            mierror = '<div  class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe ingresar la identificación del avaluador' + '</div>';
            document.getElementById("div_mensaje").innerHTML = mierror
            $("#div_mensaje").fadeOut(3000);
            return false;
        }
        else {
            f_enviar();
            
        }

    }
    function f_enviar()
    {
        var formData = new FormData($("#myform2")[0]);
        $(".ajax_load,.preload").css('display','block');
       
        $.ajax({
            url: '<?php echo base_url('index.php/mc_avaluo/guardar_registro_avaluo') ?>',
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            //una vez finalizado correctamente
            success: function(data) {
                $("#mensaje_registro").html(data);
               // alert(data);
                $(".ajax_load,.preload").css('display','none');
                $('#resultado').dialog('close');
                $('#resultado *').remove();

            },
            //si ha ocurrido un error
            error: function() {
                $(".ajax_load").hide();
                alert("Datos Incompletos");
            }
        });

    }//No permite editar el campo fecha_radicado
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

</script>

<style>
    #tabla{
        font-size: 14px; 
    }

    #titulo{
        font-family: Geneva, Arial, Helvetica, sans-serif; 
        background-color: #5BB75B;
        width:auto;
        text-align:center;
        color:#FFFFFF;
        height:30px;

    }

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
    .tabla1
    {  border-color: rgba(82, 168, 236, 0.8);

       text-align: left;
       border: 1px;


    }
    .tabla2
    { border-color: rgba(82, 168, 236, 0.8);

      text-align: left;
      width:auto;
      font-family: Geneva, Arial, Helvetica, sans-serif; 
      font-size: 12px;
      border: 1px;


    }
    .sub{
        font-weight: bold;

        font-size: 12px;
    }
    td{
        border-color: rgba(82, 168, 236, 0.8);
    }

</style>





