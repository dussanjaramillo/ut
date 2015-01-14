<?php 
/**
* Formulario para el acceso a los métodos de liquidaciones de crédito en el proceso jurídico de cobro coactivo
*
* @package    Cartera
* @subpackage Views
* @author     jdussan
* @location     application/views/liquidaciones_credito/liquidaciones_base.php
* @last-modified  07/11/2014
* @copyright	
*/ 
if( ! defined('BASEPATH') ) exit('No direct script access allowed');
if (isset($message)):
    echo $message;
endif;
$datestring = "%d/%m/%Y";
$fecha = mdate($datestring);
?>
<style type="text/css">
    .centrado{text-align:center !important;}
</style>
<div class="center-form-xlarge">
	<h2 class="text-center"><?php echo $titulo ?></h2>
    <div id = "cabecera">
        <table id = "datos" class = "table table-bordered table-striped">
            <tr>
                <th>Nit: </th>
                <th>Razón Social:</th>
                <th>Representante:</th>
                <th>Abogado:</th>
            </tr>
            <tr>
                <td><?php echo $proceso[0]['IDENTIFICACION'];?></td>
                <td><?php echo $proceso[0]['EJECUTADO'];?></td>
                <td><?php echo $proceso[0]['REPRESENTANTE'];?></td>
                <td><?php echo $abogado['ABOGADO'];?></td>
            </tr>
            <tr>
                <th>Instancia: </th>
                <th>Estado:</th>
                <th>Dirección:</th>
                <th>Correo Electrónico:</th>
            </tr>
            <tr>
                <td><?php echo $proceso[0]['PROCESO'];?></td>
                <td><?php echo $proceso[0]['RESPUESTA'];?></td>
                <td><?php echo $proceso[0]['DIRECCION'];?></td>
                <td><?php echo $proceso[0]['CORREO_ELECTRONICO'];?></td>
            </tr>
        </table>

        <table id = "liquidaciones" class = "table table-striped table-hover">
            <thead>
            <tr>
                <th>Expediente</th>
                <th>Concepto</th>
                <th>Liquidación</th>
                <th>Capital</th>
                <th>Interes</th>
                <th>Deuda</th>
                <th>Acción</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $total = 0;
            if(!empty($proceso)):

                foreach($proceso as $liquidacion):

                    $total += 1;
                    ?>
                    <tr>
                        <td><?php echo $liquidacion['NO_EXPEDIENTE']; ?></td>
                        <td><?php echo $liquidacion['CONCEPTO']; ?></td>
                        <td><?php echo $liquidacion['NUM_LIQUIDACION']; ?></td>
                        <td><?php echo "$".number_format($liquidacion['SALDO_CAPITAL'], 0, '.', '.'); ?></td>
                        <td><?php echo "$".number_format($liquidacion['SALDO_INTERES'], 0, '.', '.'); ?></td>
                        <td><?php echo "$".number_format($liquidacion['SALDO_DEUDA'], 0, '.', '.'); ?></td>
                        <td><a class="btn btn-info" <i class="fa fa-eye"></i> Ver</a></td>
                    </tr>
                <?php
                endforeach;

            endif;
            ?>
            </tbody>
        </table>
    </div>
    <div id = "formulario">
        <?php
        $atributos_formulario = array('class' => 'form-inline', 'id' => 'liquidar');
        $atributos_fechaLiquidacion = array ('name' => 'fechaLiquidacion', 'id' => 'fechaLiquidacion', 'class'  => 'input-small uneditable-input', 'value' => $fecha, 'required' => 'required', 'readonly' => 'readonly' );
        $atributos_honorarios = array ('name' => 'honorarios', 'id' => 'honorarios', 'value' => 0, 'class' => 'input-large', 'onkeypress' => 'num(this);', 'onblur' => 'num(this);' , 'onkeyup' => 'num(this);');
        $atributos_transporte = array ('name' => 'transporte', 'id' => 'transporte', 'value' => 0, 'class' => 'input-large', 'onkeypress' => 'num(this);', 'onblur' => 'num(this);' , 'onkeyup' => 'num(this);');
        $atributos_boton = array('id' =>'liquidar', 'name' => 'liquidar', 'class' => 'btn btn-success', 'content' => '<i class="fa fa-dollar"></i> Liquidar', 'type' => 'submit');
        $datos_liquidacion = array('liquidacion' => serialize($proceso));
        $proceso = array('proceso' => $codigoExpediente);
        echo form_open('/liquidaciones_credito/generarLiquidacion', $atributos_formulario);
        echo form_hidden($proceso);
        echo form_hidden($datos_liquidacion);
        ?>
        <table class = "table">
            <tr>
                <td>
                    <?php
                    echo form_label('<strong>Fecha de Liquidación:&nbsp;&nbsp;</strong>','fechaLiquidacion');
                    echo form_input($atributos_fechaLiquidacion);
                    ?>
                </td>
                <td>
                    <?php
                    echo form_label('<strong>Honorarios:&nbsp;&nbsp;</strong>','honorarios');
                    echo '<div class="input-prepend"><span class="add-on">$</span>', form_input($atributos_honorarios), '</div>';
                    ?>
                </td>
                <td>
                    <?php
                    echo form_label('<strong>Gastos Procesales:&nbsp;&nbsp;</strong>','transporte');
                    echo '<div class="input-prepend"><span class="add-on">$</span>', form_input($atributos_transporte), '</div>';
                    ?>
                </td>
            </tr>
        </table>
    </div>
    <table class = "table">
        <tr>
            <td class = "centrado"><a class="btn btn-warning" href="<?php echo site_url(); ?>"><i class="fa fa-minus-circle"></i> Cancelar</a></td>
            <td class = "centrado"><?php echo form_button($atributos_boton) ;?><!--<a class="btn btn-success" href="<?php echo site_url(); ?>/liquidaciones_credito/generarLiquidacion/<?php echo $codigoExpediente; ?>"><i class="fa fa-dollar"></i>  Liquidar</a>--></td>
        </tr>
    </table>
    <?php
    echo form_close();
    ?>
</div>
<!-- Función validaciones interfaz -->
<script type="text/javascript" language="javascript" charset="utf-8">
    $(document).ready( function () {
        datatable('#liquidaciones');
    } );

    function datatable (div) {
        $(div).dataTable({
            "bJQueryUI": true,
            "bServerSide" : false,
            "bProcessing" : true,
            "bSort" : true,
            "bPaginate" : true,
            "iDisplayStart" : 0,
            "iDisplayLength" : 10,
            "sPaginationType": "full_numbers",
            "oLanguage": {
                "sProcessing":     "Procesando...",
                "sLengthMenu":     "Mostrar _MENU_ registros",
                "sZeroRecords":    "No se encontraron resultados",
                "sEmptyTable":     "Ningún dato disponible en esta tabla",
                "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix":    "",
                "sSearch":         "Buscar:",
                "sUrl":            "",
                "sInfoThousands":  ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst":    "Primero",
                    "sLast":     "Último",
                    "sNext":     "Siguiente",
                    "sPrevious": "Anterior"
                },
                "fnInfoCallback": null
            },
            'iTotalRecords' : <?php echo $total ?>,
            'iTotalDisplayRecords' : <?php echo $total ?>,
            'aLengthMenu' : [10,50],
            'sEcho' : 1,
            "aaSorting": [[ 0, "desc" ]],
            "bLengthChange": false,
            "aoColumns": [
                { "sClass": "center" },
                { "sClass": "center" },
                { "sClass": "center" },
                { "sClass": "center" },
                { "sClass": "center" },
                { "sClass": "center" },
                { "sClass": "center" }
            ]
        });
    }
</script>
<!-- Fin Función validaciones interfaz -->

<!--
/* End of file consultar_respuesta.php */
-->
<!--
/* End of file liquidaciones_base.php */
-->