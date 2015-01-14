<?php
if (isset($message)) {
    echo $message;
}
?>
<br>
<center>
    <h1><div style="color: red">Procesos Judiciales</div></h1>
    <h2><?php echo $titulo; ?></h2>
</center>
<br>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<table id="tablaq">
    <thead>
        <tr>        
            <th>Codigo Regional</th>
            <th>Nombre Regional</th>
            <th>No. Escritura</th>
            <th>Notaria</th>
            <th>Ciudad</th>
            <th>Id. Propietario</th>
            <th>Nombre Propietario</th>
            <th>Estado</th>
            <th>Fecha Cambio de Estado</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // var_dump($titulos_seleccionados);die;

        if (!empty($titulos_seleccionados)) {
            foreach ($titulos_seleccionados as $data) {
                ?> 
                <tr>         
                    <td><div style="cursor: pointer" class="push" at="<?php echo $data->COD_TITULO; ?>" fe="<?php echo $data->FECHA_GESTION; ?>"><?= $data->COD_REGIONAL ?></div></td> 
                    <td><div style="cursor: pointer" class="push" at="<?php echo $data->COD_TITULO; ?>" fe="<?php echo $data->FECHA_GESTION; ?>"><?= $data->NOMBRE_REGIONAL ?></div></td> 
                    <td><div style="cursor: pointer" class="push" at="<?php echo $data->COD_TITULO; ?>" fe="<?php echo $data->FECHA_GESTION; ?>"><?= $data->NUM_ESCRITURA ?></div></td> 
                    <td><div style="cursor: pointer" class="push" at="<?php echo $data->COD_TITULO; ?>" fe="<?php echo $data->FECHA_GESTION; ?>"><?= $data->NOTARIA ?></div></td> 
                    <td><div style="cursor: pointer" class="push" at="<?php echo $data->COD_TITULO; ?>" fe="<?php echo $data->FECHA_GESTION; ?>"><?= $data->NOMBREMUNICIPIO ?></div></td> 
                    <td><div style="cursor: pointer" class="push" at="<?php echo $data->COD_TITULO; ?>" fe="<?php echo $data->FECHA_GESTION; ?>"><?= $data->COD_PROPIETARIO ?></div></td> 
                    <td><div style="cursor: pointer" class="push" at="<?php echo $data->COD_TITULO; ?>" fe="<?php echo $data->FECHA_GESTION; ?>"><?= $data->NOMBRE_PROPIETARIO ?></div></td> 
                    <td><div style="cursor: pointer" class="push" at="<?php echo $data->COD_TITULO; ?>" fe="<?php echo $data->FECHA_GESTION; ?>"><?= $data->NOMBRE_GESTION ?></div></td> 
                    <td><div style="cursor: pointer" class="push" at="<?php echo $data->COD_TITULO; ?>" fe="<?php echo $data->FECHA_GESTION; ?>"><?= $data->FECHA_GESTION ?></div></td> 
                </tr>
                <?php
            }
        }
        ?>
    </tbody>     
</table>
<br>
<center>
    <?php echo anchor('procesojudicial/', '<i class="fa fa-repeat"></i> Regresar', 'class="btn"'); ?>
</center>
<div id="resultado"></div>
<form id="form1" action="<?= base_url('index.php/procesojudicial/Agregar_Resultado_Demanda') ?>" method="post" >
    <input type="hidden" id="cod_titulo" name="cod_titulo" >                
    <?php
    $fecha_hoy = date("Y/m/d H:i:s", strtotime("-6 hour"));
    echo form_hidden('tb_fecha', $fecha_hoy);
    ?>
</form>

<script>
    jQuery(".preload, .load").hide();
//generación de la tabla mediante json
    $('#tablaq').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
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
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "fnInfoCallback": null,
        },
        "sServerMethod": "POST",
        "aoColumns": [
            {"sClass": "center"}, /*id 0*/
            {"sClass": "center"},
            {"sClass": "center"},
            {"sClass": "center"},
            {"sClass": "center"},
            {"sClass": "center"},
            {"sClass": "center"},
            {"sClass": "center"},
            {"sClass": "center"},
        ],
    });

    $('.push').click(function() {
        $(".preload, .load").show();
        var cod_titulo = $(this).attr('at');
        $('#cod_titulo').val(cod_titulo);
        var cod_titulo = $(this).attr('at');
        var fecha_titulo = $(this).attr('fe');
        var url5 = "<?php echo base_url("index.php/tiempos/bloqueo"); ?>";
        $("#resultado").load(url5, {codfiscalizacion: 0, gestion: 113, fecha: fecha_titulo, mostrar: 'SI', si: 'Agregar_Resultado_Demanda', no: 'Lista_Resultado_Demanda', parametros: 'cod_titulo:' + cod_titulo + '', BD: ''}, function() {
            jQuery(".preload, .load").hide();
        });

    });


</script> 
