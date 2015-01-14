<?php
/**
* Formulario para la busqueda de fiscalizaciones asociadas a una empresa.
*
* @package    Cartera
* @subpackage Views
* @author     jdussan
* @location     application/views/consultar.php
* @last-modified  27/11/2014
*/
if( ! defined('BASEPATH') ) exit('No direct script access allowed');
if (isset($message)){
    echo $message;
}
//ATRIBUTOS FORMULARIO LIQUIDACION
$atributos_formulario = array('class' => 'form-inline', 'id' => 'liquidar');
$atributos_nit = array ('id' => 'nit', 'name' => 'nit', 'type' => 'text', 'class'  => 'input-large', 'placeholder' => 'Nit' ,'maxlength' => '20', 'value' => set_value('nit'));
$atributos_razonSocial = array ('id' => 'razonSocial', 'name' => 'razonSocial', 'type' => 'text', 'class'  => 'input-large', 'placeholder' => 'Razón Social', 'maxlength' => '255', 'value' => set_value('razonSocial'));
$atributos_representante = array ('id' => 'representante', 'name' => 'representante', 'type' => 'text', 'class'  => 'input-large', 'maxlength' => '255', 'placeholder' => 'Representante Legal','value' => set_value('representante'));
$atributos_expediente = array ('id' => 'expediente', 'name' => 'expediente', 'type' => 'text', 'class'  => 'input-large', 'minlength' => '22', 'maxlength' => '22', 'placeholder' => 'Número de Expediente', 'onkeypress' => 'num(this);', 'onblur' => 'num(this);' , 'onkeyup' => 'num(this);', 'value' => set_value('expediente'));
$atributos_concepto_opciones = array ('null' => 'Seleccione');
if(isset($conceptos)):
    foreach($conceptos as $opcion):
        $atributos_concepto_opciones[$opcion['value']] = $opcion['label'] ;
    endforeach;
else:
    $atributos_concepto_opciones['1'] = 'Aportes Parafiscales' ;
    $atributos_concepto_opciones['2'] = 'FIC' ;
endif;
$atributos_consultar = array('id' =>'consultar', 'name' => 'consultar', 'class' => 'btn btn-success', 'content' => '<i class="fa fa-search"></i>  Consultar', 'type' => 'submit');
?>
<!-- Estilos personalizados -->
<style type="text/css">
    .ui-widget-overlay{z-index: 10000;}
    .ui-dialog{z-index: 15000;}
    .ui-autocomplete {max-height: 100px; overflow-y: auto; /* prevent horizontal scrollbar */ overflow-x: hidden;}
    /* IE 6 doesn't support max-height
    * we use height instead, but this forces the menu to always be this tall
    */
    * html .ui-autocomplete {height: 100px;}
    #cabecera table{width: 100%; height: auto; margin: 10px;}
    #cabecera td{text-align: left; vertical-align: middle; width: 25%;}
    .centrado{text-align:center !important;}
    #alerta{display: none;}
</style>
<!-- Fin Estilos personalizados -->
<!-- Cargue de formulario -->
<div class="center-form-large" id="cabecera">
    <?php
        echo validation_errors();
        echo form_open('liquidaciones/mostrarFiscalizaciones', $atributos_formulario);
    ?>
        <div class = "alert alert-error" id = "alerta"></div>
        <h2 class = "text-center">Generar Liquidaciones</h2>
        <br><br>
        <div id = "parametros">
            <table class = "table bordered">
                <tr>
                    <th colspan = "3">Parametros de Busqueda</td>
                    <th colspan = "3">Filtros</td>
                </tr>
                <tr>
                    <td>Nit:</td>
                    <td><?php echo form_input($atributos_nit); ?></td>
                    <td><img id="preloadmini1" src="<?php echo base_url('img/319.gif') ?>" width="28" height="28" /></td>
                    <td>Nro. Expediente:</td>
                    <td><?php echo form_input($atributos_expediente); ?></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>Razón Social:</td>
                    <td><?php echo form_input($atributos_razonSocial); ?></td>
                    <td><img id="preloadmini2" src="<?php echo base_url('img/319.gif') ?>" width="28" height="28" /></td>
                    <td>Concepto:</td>
                    <td><?php echo form_dropdown('concepto', $atributos_concepto_opciones, set_value('concepto')); ?></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>Representante Legal:</td>
                    <td><?php echo form_input($atributos_representante); ?></td>
                    <td><img id="preloadmini3" src="<?php echo base_url('img/319.gif') ?>" width="28" height="28" /></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                    <td colspan = "3" class = "centrado">
                        <a class="btn btn-warning" href="<?php echo site_url(); ?>"><i class="fa fa-minus-circle"></i> Cancelar</a>
                    </td>
                    <td colspan = "3"  class = "centrado">
                        <?php
                        echo form_button($atributos_consultar);
                        ?>
                    </td>
                </tr>
            </table>
        </div>
        <?php
        echo form_close();
        ?>
</div>
<!-- Fin cargue de formulario -->
<!-- Función validaciones interfaz -->
<script type="text/javascript" language="javascript" charset="utf-8">
$(document).ready(function()
{
    $("#preloadmini1").hide();
    $("#preloadmini2").hide();
    $("#preloadmini3").hide();
    var max_chars = 22; //cantidad de caracteres máximos
    var exito = 0; //criterios de éxito

    $('#expediente').keydown(function()
    {
        var chars = $(this).val().length; //cantidad de caracteres digitados

        if (chars > max_chars) //verificar la cantidad de caracteres
        {
            var data = $(this).val().substring(0, max_chars);
            $('#alerta').css('display: block;');
            $('#alerta').html('<p class="text-warning"><i class="fa fa-warning"></i> El expediente no puede exceder los 22 digitos<p>');
            $(this).val(data);
        }
        else
        {
            var data = $(this).val().substring(0, max_chars);
            $('#alerta').css('display: none;');
            $('#alerta').html('');
            $(this).val(data);
        }
    });

    $( "#nit" ).autocomplete({
        source: "<?php echo site_url("liquidaciones/consultarNit") ?>",
        minLength: 3,
        search: function( event, ui ) {
            $("#preloadmini1").show();
        },
        response: function( event, ui ) {
        $("#preloadmini1").hide();
        }
    });

    $( "#razonSocial" ).autocomplete({
        source: "<?php echo site_url("liquidaciones/consultarRazonSocial") ?>",
        minLength: 3,
        search: function( event, ui ) {
            $("#preloadmini2").show();
        },
        response: function( event, ui ) {
        $("#preloadmini2").hide();
        }
    });

    $( "#representante" ).autocomplete({
        source: "<?php echo site_url("liquidaciones/consultarRepresentante") ?>",
        minLength: 3,
        search: function( event, ui ) {
            $("#preloadmini3").show();
        },
        response: function( event, ui ) {
        $("#preloadmini3").hide();
        }
    });

    $('#consultar').click(function(){

        if (($("#nit").val().length < 1) && ($("#razonSocial").val().length < 1) && ($("#representante").val().length < 1)&& ($("#expediente").val().length < 1)) //verificar que los campos minimo de consulta no esten vacios
        {
            $('#alerta').css({display: "block"});
            $('#alerta').html('<button type = "button" class = "close" data-dismiss="alert">&times;</button><i class = "fa fa-warning"></i> No hay datos suficientes para realizar una consulta');
        }
        else
        {
            exito++;
        }

        if (exito == 1) //verificación  de criterios de éxito
        {
            $('#liquidar').submit();
        }
        else
        {
            return false;
        }
    });
});
</script>
<!-- Fin Función validaciones interfaz -->

<!-- Función solo números -->
<script type="text/javascript" language="javascript" charset="utf-8">
    function num(c){
        c.value=c.value.replace(/[A-z-\/\*\,\.\ \;\:\{\}\[\]\^\`\¨\´\+\~\°\|\¬\!\¡\'\?\¿\=\)\(\&\%\$\#\"\<\>\Ñ\ñ\+]/, '');
    }
</script>
<!-- Fin Función solo números -->

<!--
/* End of file consultar.php */
-->
