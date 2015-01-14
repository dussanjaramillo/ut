<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<h3>Asignaci&oacute;n de Abogado</h3>
<br>
<table width="100%" id="tablaq">
    <thead>
    <th>Numero de Liquidaci&oacute;n</th>
    <th>Nit</th>
    <th>Empresa</th>
    <th>Deuda</th>
    <th>Fecha Liquidacion</th>
    <th>Concepto</th>
    <th>Gestionar</th>
</thead>
<tbody>
    <?php foreach ($asignar as $abogados) { ?>
        <tr>
            <td><?php echo $abogados['NUM_LIQUIDACION'] ?></td>
            <td><?php echo $abogados['NIT_EMPRESA'] ?></td>
            <td><?php echo $abogados['NOMBRE_EMPRESA'] ?></td>
            <td><?php $pago =$abogados['DEUDA2'];
            echo number_format($pago);
            ?></td>
            <td align="center"><?php echo $abogados['FECHA_LIQUIDACION'] ?></td>
            <td align="center"><?php echo $abogados['NOMBRE_CONCEPTO'] ?></td>
            <td><input type="radio" name="asignar" onclick="activar('<?php echo $abogados['COD_FISCALIZACION'] ?>',
                            '<?php echo $abogados['NIT_EMPRESA'] ?>', '<?php echo $abogados['NUM_LIQUIDACION'] ?>',
                            '<?php echo $abogados['COD_CONCEPTO'] ?>','<?php echo $abogados['COD_REGIONAL'] ?>')">
                            </td>
        </tr>
    <?php } ?>
</tbody>
</table>
<div id="resultado"></div>
<script>
    $('#tablaq').dataTable({
        "bJQueryUI": true,
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
    "fnInfoCallback": null,
            },
    });
    jQuery(".preload, .load").hide();
    function activar(cod_fis, nit, num_liquidacion,concepto,regional) {
        jQuery(".preload, .load").show();
        var url = "<?php echo base_url('index.php/notificacionacta/asignar_abogado') ?>"
        $('#resultado').load(url, {cod_fis: cod_fis, num_liquidacion: num_liquidacion, nit: nit,regional:regional,concepto:concepto});
    }
</script> 
<style>
    .ui-widget-overlay{z-index: 10000;}
    .ui-dialog{
        z-index: 15000;
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
</style>