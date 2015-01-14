<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
if (isset($message)) {
    echo $message;
}
if (isset($custom_error))
    echo $custom_error;
?><br><br>

<h4 style="text-align: center">Registro Avalúo Vehículo</h4>
<h4>Información Obtenida del Avalúo.</h4>
<form name="myform2" id="myform2" method="post" action="<?php echo base_url('index.php/mc_avaluo/guardar_registro_avaluo') ?>">
    <input type="hidden" name="tipo_inmueble" id="tipo_inmueble" value="3">
    <input type="hidden" name="cod_tipo_bien" id="cod_tipo_bien" value="3">
    <input type="hidden" name="cod_avaluo" id="cod_avaluo" value="<?php echo $post['cod_avaluo'] ?>">
    <input type="hidden" name="id" id="id" value="<?php echo $post['id'] ?>">
    <table><tr>
            <td style="text-align:left;margin-top:20px;">Placa</td>
            <td style="text-align:left;margin-top:20px;">
                <input type="text" name="placa" id="placa" value="<?php echo $this->data['@$detalle']['PLACA_VEHICULO'] ?>">
            </td>
        </tr>
        <tr>
            <td style="text-align:left;margin-top:20px;">Marca</td>
            <td style="text-align:left;margin-top:20px;">
                <input type="text" name="marca" id="marca" value="<?php echo $this->data['@$detalle']['MARCA_VEHICULO'] ?>">
            </td>
        </tr>
        <tr>
            <td style="text-align:left;margin-top:20px;">Número Chasis</td>
            <td style="text-align:left;margin-top:20px;">
                <input type="text" name="numero_chasis" id="numero_chasis" value="<?php echo $this->data['@$detalle']['NUMERO_CHASIS'] ?>">
            </td>
        </tr>
        <tr>
            <td style="text-align:left;margin-top:20px;">Modelo</td>
            <td style="text-align:left;margin-top:20px;">
                <input type="text" name="modelo_vehiculo" id="modelo_vehiculo"  maxlength="4" value="<?php echo $this->data['@$detalle']['MODELO_VEHICULO'] ?>" onkeypress="return soloNumeros(event)" >
            </td>
        </tr>
        <tr>
            <td style="text-align:left;margin-top:20px;">Servicio</td>
            <td style="text-align:left;margin-top:20px;">
                <input type="text" name="servicio_vehiculo" id="servicio_vehiculo" value="<?php echo $this->data['@$detalle']['SERVICIO_VEHICULO'] ?>">
            </td>
        </tr>
        <tr>
            <td style="text-align:left;margin-top:20px;">Color</td>
            <td style="text-align:left;margin-top:20px;">
                <input type="text" name="color_vehiculo" id="color_vehiculo" value="<?php echo $this->data['@$detalle']['COLOR_VEHICULO'] ?>">
            </td>
        </tr> <tr>
            <td style="text-align:left;margin-top:20px;">Tipo de Vehículo</td>
            <td style="text-align:left;margin-top:20px;">
                <select name="cod_tipovehiculo" id="cod_tipovehiculo" class="validate[required]" ><?php
//                    echo '<option value="">Elija una opción...</option>';

                    foreach ($tipos_vehiculos as $tipo_vehiculo) :
                        ?>
                        <option value="<?php echo $tipo_vehiculo['COD_TIPOVEHICULO'] ?>"><?php echo $tipo_vehiculo['NOMBRE_TIPO'] ?></option>';
                    <?php endforeach;
                    ?></select>

            </td>
        </tr>
        <tr>
            <td  style="text-align:left;margin-top:20px;">Valor Avalúo del Bien</td> 
            <td  style="text-align:left;margin-top:20px;">
                <input type="text" name="costo_total" id="costo_total" onkeypress="return soloNumeros(event)" value="<?php echo $this->data['@$detalle']['COSTO_TOTAL'] ?>">
            </td> 
        </tr>
        <tr>
            <td  style="text-align:left;margin-top:20px;">Observaciones</td> 
            <td  style="text-align:left;margin-top:20px;">
                <textarea  style="width:500px;height: 100px" name="observaciones" id="observaciones"><?php echo $this->data['@$detalle']['OBSERVACIONES'] ?></textarea>
            </td> 
        </tr>
        <tr>
            <td  style="text-align:left;" ><br>Elaboró:</td>
            <td>      

                <input type="text"  class="span4" name="elaboro" id="elaboro"  value="<?php echo $consulta[0]['ELABORO']; ?>" >
            </td>
        </tr>
        <tr>
            <td style="text-align:left;"><br>Identificación del Avaluador</td>
            <td> 
                <input type="text"  class="span4" name="cod_avaluador" id="cod_avaluador" value="<?php echo $consulta[0]['COD_AVALUADOR'] ?>" onkeypress="return soloNumeros(event)" >
            </td>

        </tr>
        <tr>
            <td style="text-align:left;"><br>Profesión</td>
            <td>
                <input type="text"   class="span4" name="profesion" id="profesion" value="<?php echo $consulta[0]['PROFESION'] ?>"></td>
        </tr>
        <tr>
            <td  style="text-align:left;" ><br>Licencia N°:</td>
            <td>   
                <input type="text"  class="span4" name="num_licencia" id="num_licencia" value="<?php echo $consulta[0]['LICENCIA_NRO'] ?>">
            </td>
        </tr>
        <tr>

            <td style="text-align:left;" ><br>Dirección</td>
            <td>    
                <input type="text"  class="span4"  name="direccion_avaluador" id="direccion_avaluador" value="<?php echo $consulta[0]['DIRECCION'] ?>">
            </td>
        </tr>
        <tr>
            <td  style="text-align:left;" ><br>Fecha:</td>
            <td>    
                <input type="text" name="fecha" id="fecha" style="height:30px;" value="<?php echo $consulta[0]['FECHA_AVALUO'] ?>" onkeypress="return prueba(event)">
            </td>
        </tr>
        <tr><td colspan="2"><br>
                <div id="div_mensaje"></div><br>
                <input type="button" name="Guardar" id="Guardar" value="Guardar" class="btn btn-success" onclick="enviar()" >

            </td></tr>

    </table>
</form>

<div id="ajax_load" class="ajax_load" style="display: none">
    <div class="preload" id="preload" ></div><img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
</div>
<script>
    $(document).ready(function() {
        $("#ajax_load").hide();
        $('#resultado').dialog({
            autoOpen: true,
            width: 900,
            height: 450,
            modal: true,
            title: "Registrar Detalle Avalúo Vehículo",
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


    function enviar()
    {

        var placa = $("#placa").val();
        var marca = $("#marca").val();
        var numero_chasis = $("#numero_chasis").val();
        var modelo_vehiculo = $("#modelo_vehiculo").val();
        var servicio_vehiculo = $("#servicio_vehiculo").val();
        var color_vehiculo = $("#color_vehiculo").val();
        var cod_tipovehiculo = $("#cod_tipovehiculo").val();
        var costo_total = $("#costo_total").val();
        //  var documento_soporte = $("#archivo0").val();
        var observaciones = $("#observaciones").val();

        if (!placa)
        {
            mierror = '<div   class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe ingresar la información de la placa del vehiculo' + '</div>';
            document.getElementById("div_mensaje").innerHTML = mierror;

            return false;

        } else if (!marca) {
            mierror = '<div   class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe ingresar la información de la marca del vehiculo' + '</div>';
            document.getElementById("div_mensaje").innerHTML = mierror;

            return false;
        }
        else if (!numero_chasis) {
            mierror = '<div style="color:#5BB75B;"  class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe ingresar la información del número de chasis del vehiculo' + '</div>';
            document.getElementById("div_mensaje").innerHTML = mierror;

            return false;
        }
        else if (!modelo_vehiculo) {
            mierror = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe ingresar la información del modelo del vehiculo' + '</div>';
            document.getElementById("div_mensaje").innerHTML = mierror;

            return false;
        }
        else if (!servicio_vehiculo) {
            mierror = '<div  class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe ingresar la información del servicio del vehiculo' + '</div>';
            document.getElementById("div_mensaje").innerHTML = mierror;

            return false;
        }
        else if (!color_vehiculo) {
            mierror = '<div   class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe ingresar la información del color del vehiculo' + '</div>';
            document.getElementById("div_mensaje").innerHTML = mierror;

            return false;
        }
        else if (!cod_tipovehiculo) {
            mierror = '<div   class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe ingresar la información del tipo de vehiculo' + '</div>';
            document.getElementById("div_mensaje").innerHTML = mierror;

            return false;
        }
        else if (!costo_total) {
            mierror = '<div   class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe ingresar la información del costo de vehiculo' + '</div>';
            document.getElementById("div_mensaje").innerHTML = mierror;

            return false;
        }

        var formData = new FormData($("#myform2")[0]);
        $.ajax({
            url: '<?php echo base_url('index.php/mc_avaluo/guardar_registro_avaluo') ?>',
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            //una vez finalizado correctamente
            success: function(data) {
                $("#div_mensaje").html(data); 
                $("#mensaje_registro").html(data);
                
                $(".ajax_load").hide();
                $('#resultado').dialog('close');
                $('#resultado *').remove();

            },
            //si ha ocurrido un error
            error: function() {
                $(".ajax_load").show();
                alert("Datos Incompletos");
            }
        });
    }

    $(document).ready(function() {
        $("#fecha").datepicker({
            dateFormat: "dd/mm/yy",
            changeMonth: true,
            maxDate: "0",
            changeYear: true,
        });
    });
</script>
<style>
    div img.load{
        position: absolute;
        left: 50%;
        top: 50%;
        margin-left: -64px;
        margin-top: -64px;
        z-index: 15000;
    }

</style>