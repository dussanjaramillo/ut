<?php
if (isset($message)) {
    echo $message;
}
?>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<br>
<center>
    <h1><div>Procesos Judiciales</div></h1>
    <h2><?php echo $titulo; ?></h2>
</center>
<br>
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
            <th>Gestionar</th>
        </tr>
    </thead>
    <tbody>
        <?php   
        //var_dump($titulos_seleccionados);die;
        if (!empty($titulos_seleccionados)) {
            foreach ($titulos_seleccionados as $data) { //print_r($data);exit;
                ?> 
                <tr>            
                    <td><div style="cursor: pointer" class="push" at="<?= $data->COD_TITULO ?>"><?= $data->COD_REGIONAL ?></div></td>
                    <td><div style="cursor: pointer" class="push" at="<?= $data->COD_TITULO ?>"><?= $data->NOMBRE_REGIONAL ?></div></td> 
                    <td><div style="cursor: pointer" class="push" at="<?= $data->COD_TITULO ?>"><?= $data->NUM_ESCRITURA ?></div></td> 
                    <td><div style="cursor: pointer" class="push" at="<?= $data->COD_TITULO ?>"><?= $data->NOTARIA ?></div></td> 
                    <td><div style="cursor: pointer" class="push" at="<?= $data->COD_TITULO ?>"><?= $data->NOMBREMUNICIPIO ?></div></td> 
                    <td><div style="cursor: pointer" class="push" at="<?= $data->COD_TITULO ?>"><?= $data->COD_PROPIETARIO ?></div></td> 
                    <td><div style="cursor: pointer" class="push" at="<?= $data->COD_TITULO ?>"><?= $data->NOMBRE_PROPIETARIO ?></div></td> 
                    <td><div style="cursor: pointer" class="push" at="<?= $data->COD_TITULO ?>"><?= $data->NOMBRE_GESTION ?></div></td> 
                    <td><div style="cursor: pointer" class="push" at="<?= $data->COD_TITULO ?>"><?= $data->FECHA_GESTION ?></div></td>
                    <td><input type="radio" onclick="enviar(<?php echo $data->COD_TITULO; ?>, '<?php echo base_url() . $ruta; ?>')"></td>
                </tr>
                <?php
            }
        }
        ?>
    </tbody>     
</table>
<form id="form1" action="<?= base_url($ruta) ?>" method="post" >
    <input type="hidden" id="cod_titulo" name="cod_titulo" >      
    <?php
    $fecha_hoy = date("Y/m/d H:i:s", strtotime("-6 hour"));
    echo form_hidden('tb_fecha', $fecha_hoy);
    ?>
</form>
<br>
<center>
    <?php echo anchor('procesojudicial/', '<i class="fa fa-repeat"></i> Regresar', 'class="btn"'); ?>
</center>

<script type="text/javascript" language="javascript" charset="utf-8">

//generación de la tabla mediante json
    jQuery(".preload, .load").hide();

    $('#tablaq').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
        "sServerMethod": "POST",
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
            {"sClass": "center"},   
        ],
    });
    $('.push').click(function() {
        $(".preload, .load").show();
        var cod_titulo = $(this).attr('at');
        $('#cod_titulo').val(cod_titulo);
        $('#form1').submit();
        setTimeout(function() {
            jQuery(".preload, .load").hide();
        }, 2000);
    });

    function enviar(cod_titulo, ruta) { 
        $(".preload, .load").show();
        $('#cod_titulo').val(cod_titulo);
        $('#form1').attr("action", ruta);
        $("#form1").submit();
        setTimeout(function() {
            jQuery(".preload, .load").hide();
        }, 2000);
    }
</script> 
