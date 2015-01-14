<?php
if (isset($message)) {
    echo $message;
}
?>
<span> <h4 style="color:#5bb75b; text-align:center;">Asignar Abogado de Cobro Coactivo</h4></span>
<?php
?>
<form name="form1" method="post" id="form1" action="<?php echo $ruta ?>">
    <table id="tabla1">
        <thead>
            <tr><th></th>
                <th>Expediente</th>
                <th>Fecha </th>
                <th>Concepto</th>
                <th>Identificación</th>
                <th>Ejecutado</th>
<!--                <th>Telefono</th>
                <th>Dirección</th>-->
                <th>Regional</th>
<!--                <th>Abogado Asignado</th>-->
            </tr>
        </thead> 
        <tbody>

            <?php
            if ($consulta) {
                foreach ($consulta as $data) {
                    ?> 
                    <tr>   
                        <td>
                            <?php echo form_hidden('cod_recepcion', $data['COD_RECEPCIONTITULO']); ?>

                            <input type="checkbox" name="asignar[]" id="asignar"  value="<?php echo $data['COD_RECEPCIONTITULO'] ?>"></td>
                        <td><?php echo $data['COD_PROCESO'] ?><br></td>
                        <td><?php echo $data['FECHA_CONSULTAONBASE'] ?></td>
                        <td><?php echo $data['NOMBRE_CONCEPTO'] ?></td>
                        <td><?php echo $data['IDENTIFICACION'] ?></td>
                        <td><?php echo $data['NOMBRE'] ?></td>
                        <td><?php echo $data['NOMBRE_REGIONAL'] ?></td>
        <!--                    <td><?php //echo $data['NOMBRES'] . " " . $data['APELLIDOS']   ?></td>-->
                    </tr>
                <?php }
            }
            ?>


        </tbody>      
    </table>
<?php  if ($consulta) {?>
    <div id="abogados">
        <BR>
        <h4> Abogados </h4>
        <div style="margin-left:0px;">
            <select name="abogado" id="abogado" ><?php
                echo '<option value="">Seleccione el abogado</option>';
                foreach ($this->data['abogados'] as $abogado) :
                    ?> <option value="<?php echo $abogado['IDUSUARIO'] ?>"><?php echo strtoupper($abogado['NOMBRES'] . " " . $abogado['APELLIDOS']) ?></option>
                <?php endforeach;
                ?></select>
        </div>

        <input type="submit" name="Guardar" id="Guardar" value="Asignar"  class="btn btn-success">

    </div>
</form>
<?php }?>
<?php  if ($consulta) {?>
<form id="form2" name="form2"  method="post" action="<?php echo $ruta_bandeja; ?>">
    <input type="hidden" name="cod_coactivo" id="cod_coactivo" value="<?php echo $data['COD_RECEPCIONTITULO'] ?>">
    <input type="submit" name="cancelar" id="cancelar" class="btn btn-warning"value=" Cancelar">
</form>
<?php }?>
<script type="text/javascript" language="javascript" charset="utf-8">

    $('#tabla1').dataTable({
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
    });


    function f_buscar()
    {
        var num_documento = $("#num_documento").val();
        var num_proceso = $("#num_proceso").val();

    }
</script> 