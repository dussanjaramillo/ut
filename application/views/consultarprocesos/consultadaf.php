<!--
    /**
     * Formulario que muestra los Procesos de Direccion Administrativa y Financiera
     * En este formulario se muestra todos los procesos de direccion Administrativa y Financiera
     * @author Haider Oviedo Martinez :: Thomas MTI
     * @since 26 II 2013 
     */
-->
<h1>Procesos Direcci&oacute;n Administrativa y Financiera</h1>
<br>
<table id="daf" width="100%">
    <thead>
        <tr>
            <td style="display:none">Codigo</td>
            <td>Nombre Proceso</td>
            <td>Flujograma</td>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($datos as $col) {
            ?>
            <tr>
                <td style="display:none"><?php echo $col['COD_TIPO_PROCESO']; ?></td>
                <td id="nombre" name="nombre"><?php echo $col['TIPO_PROCESO']; ?></td>
                <td align="center"><button at="<?php echo $col['COD_TIPO_PROCESO']; ?>" class="btn btn-info"> <i class='fa fa-image' > Ver Imagen</i></button></td>
            </tr>

            <?php
        }
        ?>
    </tbody>    
</table>
<br>
<form action= "<?php echo base_url('index.php/consultarprocesos/'); ?>" method="post">
    <button type='submit' name='daf' id='admon' class='btn btn-default' style="float: right" ><i class='fa fa-arrow-circle-left' > Volver</i></button>    
</form>


<div id ="imge"></div>


<script>
      
    $('#daf').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
        //"bFilter": false,
        "aaSorting": [[ 1, "asc" ]],
        "bLengthChange": false,
        "aLengthMenu": [
            [25, 50, 100, 200, -1],
            [25, 50, 100, 200, "All"]
        ],
        "iDisplayLength": -1,
        "oLanguage": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "|Primero|",
                "sLast": "|Último|",
                "sNext": "|Siguiente|",
                "sPrevious": "|Anterior|"
            },
            "fnInfoCallback": null,
        },
    });

    $(".btn").click(function() {
        var btn = $(this).attr("at");


       switch (btn)
        {
            //FISCALIZACION
            case '1':
                $('#myModal').modal();
                $('#imgff').attr('src', '<?php echo base_url(); ?>img/procesoswf/Administrativa/5Fiscalizacion_5.1.png');
                $('a').prop('href', '<?php echo base_url(); ?>img/procesoswf/Administrativa/5Fiscalizacion_5.1.png');

                break;
                //ACUERDO DE PAGO    
            case '2':
                $('#myModal').modal();
                $('#imgff').attr('src', '<?php echo base_url(); ?>img/procesoswf/Administrativa/9Acuerdo_de_Pago_5.1.png');
                $('a').prop('href', '<?php echo base_url(); ?>img/procesoswf/Administrativa/9Acuerdo_de_Pago_5.1.png');
                break;
            case '3':
                $('#myModal').modal();
                $('#imgff').attr('src', '<?php echo base_url(); ?>img/procesoswf/Administrativa/7.1Notificacion_Acto_Administrativo_AP___FIC_5.2.png');
                $('a').prop('href', '<?php echo base_url(); ?>img/procesoswf/Administrativa/7.1Notificacion_Acto_Administrativo_AP___FIC_5.2.png');
                break;
            case '4':
                $('#myModal').modal();
                $('#imgff').attr('src', '<?php echo base_url(); ?>img/procesoswf/Administrativa/7.2Notificacion_Acto_Administrativo_CA_5.2.png');
                $('a').prop('href', '<?php echo base_url(); ?>img/procesoswf/Administrativa/7.2Notificacion_Acto_Administrativo_CA_5.2.png');
                break

            case '5':
                $('#myModal').modal();
                $('#imgff').attr('src', '<?php echo base_url(); ?>img/procesoswf/Administrativa/6Liquidacion_5.1.png');
                $('a').prop('href', '<?php echo base_url(); ?>img/procesoswf/Administrativa/6Liquidacion_5.1.png');
                break;
            case '6':
                $('#myModal').modal();
                $('#imgff').attr('src', '<?php echo base_url(); ?>img/procesoswf/Administrativa/8.1Ejecucion_Acto_Admin___Apor__Contrato_Apren___FIC_5.1.png');
                $('a').prop('href', '<?php echo base_url(); ?>img/procesoswf/Administrativa/8.1Ejecucion_Acto_Admin___Apor__Contrato_Apren___FIC_5.1.png');
                break;
            case '7':
                $('#myModal').modal();
                $('#imgff').attr('src', '<?php echo base_url(); ?>img/procesoswf/Administrativa/8.2Ejecucion_Acto_Admin___Multas_Ministerio_Trabajo_5.1.png');
                $('a').prop('href', '<?php echo base_url(); ?>img/procesoswf/Administrativa/8.2Ejecucion_Acto_Admin___Multas_Ministerio_Trabajo_5.1.png');
                break;

            case '8':
                $('#myModal').modal();
                $('a').prop('href', '<?php echo base_url(); ?>img/procesoswf/Juridica/1.Recepcion__Estudio_de_Titulo_y_Avoca_Conocimiento_del_Expediente_1.0.png');
                $('#imgff').attr('src', '<?php echo base_url(); ?>img/procesoswf/Juridica/1.Recepcion__Estudio_de_Titulo_y_Avoca_Conocimiento_del_Expediente_1.0.png');
                break;

            case '9':
                $('#myModal').modal();
                $('#imgff').attr('src', '<?php echo base_url(); ?>img/procesoswf/Juridica/2.Acercamiento_Persuasivo_en_Coactivo_5.1.png');
                $('a').prop('href', '<?php echo base_url(); ?>img/procesoswf/Juridica/2.Acercamiento_Persuasivo_en_Coactivo_5.1.png');
                break;

            case '10':
                $('#myModal').modal();
                $('#imgff').attr('src', '<?php echo base_url(); ?>img/procesoswf/Juridica/5.Elaboracion__Notificacion_y_Firmeza_Mandamiento_de_Pago_5.3.png');
                $('a').prop('href', '<?php echo base_url(); ?>img/procesoswf/Juridica/5.Elaboracion__Notificacion_y_Firmeza_Mandamiento_de_Pago_5.3.png');
                break;

            case '11':
                $('#myModal').modal();
                $('#imgff').attr('src', '<?php echo base_url(); ?>img/procesoswf/Juridica/6.1.Medidas_Cautelares___Investigacion_de_Bienes_1.2.png');
                $('a').prop('href', '<?php echo base_url(); ?>img/procesoswf/Juridica/6.1.Medidas_Cautelares___Investigacion_de_Bienes_1.2.png');
                break;

            case '12':
                $('#myModal').modal();
                $('#imgff').attr('src', '<?php echo base_url(); ?>img/procesoswf/Juridica/6.2.Medidas_Cautelares___Avaluo_1.0.png');
                $('a').prop('href', '<?php echo base_url(); ?>img/procesoswf/Juridica/6.2.Medidas_Cautelares___Avaluo_1.0.png');
                break;

            case '13':
                $('#myModal').modal();
                $('#imgff').attr('src', '<?php echo base_url(); ?>img/procesoswf/Juridica/Procesos_Judiciales_5.1.png');
                $('a').prop('href', '<?php echo base_url(); ?>img/procesoswf/Juridica/Procesos_Judiciales_5.1.png');
                break;


            case '14':
                $('#myModal').modal();
                $('#imgff').attr('src', '<?php echo base_url(); ?>img/procesoswf/Juridica/7.Liquidacion_de_Credito_5.2.png');
                $('a').prop('href', '<?php echo base_url(); ?>img/procesoswf/Juridica/7.Liquidacion_de_Credito_5.2.png');
                break;


            case '15':
                $('#myModal').modal();
                $('#imgff').attr('src', '<?php echo base_url(); ?>img/procesoswf/Juridica/8.Nulidades_5.3.png');
                $('a').prop('href', '<?php echo base_url(); ?>img/procesoswf/Juridica/8.Nulidades_5.3.png');
                break;

            case '16':
                $('#myModal').modal();
                $('#imgff').attr('src', '<?php echo base_url(); ?>img/procesoswf/Juridica/9.Remisibilidad_5.1.png');
                $('a').prop('href', '<?php echo base_url(); ?>img/procesoswf/Juridica/9.Remisibilidad_5.1.png');
                break;

            case '17':
                $('#myModal').modal();
                $('#imgff').attr('src', '<?php echo base_url(); ?>img/procesoswf/Juridica/6.3.Medidas_Cautelares___Remate_1.1.png');
                $('a').prop('href', '<?php echo base_url(); ?>img/procesoswf/Juridica/6.3.Medidas_Cautelares___Remate_1.1.png');
                break;

            case '18':
                $('#myModal').modal();
                $('#imgff').attr('src', '<?php echo base_url(); ?>img/procesoswf/Juridica/4.Acuerdo_de_Pago_en_Coactivo_5.2.png');
                $('a').prop('href', '<?php echo base_url(); ?>img/procesoswf/Juridica/4.Acuerdo_de_Pago_en_Coactivo_5.2.png');
                break;

            case '19':
                $('#myModal').modal();
                $('#imgff').attr('src', '<?php echo base_url(); ?>img/procesoswf/Juridica/3.Verificacion_de_Pagos_en_Coactivo_5.3.png');
                $('a').prop('href', '<?php echo base_url(); ?>img/procesoswf/Juridica/3.Verificacion_de_Pagos_en_Coactivo_5.3.png');
                break;

            case '20':
                $('#myModal').modal();
                $('#imgff').attr('src', '<?php echo base_url(); ?>img/procesoswf/Juridica/RI - Reorganización.png');
                $('a').prop('href', '<?php echo base_url(); ?>img/procesoswf/Juridica/RI - Reorganización.png');
                
                break;

            case '21':
                $('#myModal').modal();
                $('#imgff').attr('src', '<?php echo base_url(); ?>img/procesoswf/Juridica/RI-LiquidacionObligatoria.png');
                $('a').prop('href', '<?php echo base_url(); ?>img/procesoswf/Juridica/RI-LiquidacionObligatoria.png');
                break;

            default:
                error("El valor de variable es desconocido.");
                break;

        }
    });
    
    function ajaxValidationCallback(status, form, json, options) {
    }

</script>  

<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width: 1250px;height: 600px; top: 10px; left: 350px; overflow: auto" data-keyboard="false" data-backdrop="static"> 

    <div class="modal-header">
        <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>-->
        <h4 class="modal-title">Flujograma del Procesos</h4>
    </div>

    <div class="modal-body"> 
        <form action="<?php echo "url"; ?>" >
            <center>
                <a href="index.php" target="_blank"><img id="imgff" src="<?php echo base_url(); ?>img/ff.png" id="imgff"></a>
            </center> 
    </div>

    <div class="modal-footer">

        <button type="submit" href="#" onclick="location.href = '<?php echo base_url('index.php/consultarprocesos/consultadaf'); ?>';" class="btn btn-warning" data-dismiss="modal" id="cancelar"><i class="fa fa-reply-all"> Cerrar</i></button>

    </div>
</form>                