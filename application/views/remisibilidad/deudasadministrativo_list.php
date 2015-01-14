<?php
$attributes = array("id" => "myform");
echo form_open('remisibilidad/Menu_DeudasAdministrativa', $attributes);
?>
<input type="hidden" id="expediente" name="expediente" readonly> 
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<br>
<center>
    <br>
    <h1><?php echo 'Empresas para Solicitar Remisibilidad' ?></h1>
</center>
<br>
<div id="datos">
    <table id="tablaq">
        <thead>
            <tr>        
                <th>NIT</th>
                <th>Razon Social</th>
                <th>Representante Legal</th>
                <th>Direccion</th>
                <th>Telefono</th>                
                <th>Seleccionar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($titulos)) {
                foreach ($titulos as $data) {
                    ?>             
                    <tr>          
                        <td><?php echo $data['IDENTIFICACION']; ?></td>
                        <td><?= $data['EJECUTADO'] ?></td> 
                        <td><?= $data['REPRESENTANTE'] ?></td> 
                        <td><?= $data['DIRECCION'] ?></td> 
                        <td><?= $data['TELEFONO'] ?></td> 
                        <td><input id="list_expediente" name="list_expediente[]" type="radio" onclick="recibir(<?= $data['IDENTIFICACION'] ?>);" value="<?= $data['IDENTIFICACION'] ?>"></td> 
                    </tr>
                    <?php
                }
            }
            ?>
        </tbody>     
    </table>
</div>
<?php echo form_close(); ?>

<script type="text/javascript" language="javascript" charset="utf-8">

    //generación de la tabla mediante json
    jQuery(".preload, .load").hide();
    $('#cancelar').click(function() {
        window.history.back(-1);
    });
    function recibir($id) {
        $(".preload, .load").show();
        $('#expediente').val($id);
        if (confirm("¿Desea Confirmar?")) {
            $('#myform').submit();
        }else{
            $(".preload, .load").hide();
        }
    }
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
        ],
    });
</script> 