<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
if (isset($message)) {
    echo $message;
}
if (isset($custom_error)) {
    echo $custom_error;
}
?>
<h2 align="center">Administración de Cuentas Bancarias</h2>
<br>
<table id="tabla1">
    <thead>
        <tr>
            <th>Cuenta</th>
            <th>Nombre Banco</th>
            <th>Descripción</th>
            <th>Creación</th>
            <th>Conceptos</th>
            <th>Editar</th>
        </tr>
    </thead> 
    <tbody>
        <?php
        if ($cuentas) {
            foreach ($cuentas->result_array as $data) {
                ?> 
                <tr>   
                    <td><?php echo $data['CUENTA'] ?></td>
                    <td><?php echo $data['NOMBREBANCO'] ?></td>
                    <td><?php echo $data['DESCRIPCION'] ?></td>
                    <td><?php echo $data['FECHA_CREACION'] ?></td>
                    <td align="center"><a href="<?php echo base_url()?>index.php/siif/conceptos/<?php echo $data['CUENTA']?>"  class="btn btn-small" title="Ver" data-toggle="modal" data-target="#modal" data-keyboard="false" data-backdrop="static"><i class="fa fa-eye"></i></a></td>
                    <td>Inactivar</td>
                </tr>
        <?php }
        } ?>
    </tbody>      
</table>
<br>
<div style="text-align: center">
    <form id="cuentaadd" method="post" action="adicionarcuenta">
        <input type="submit" class="btn btn-success" value="ADICIONAR CUENTA" />
    </form>
    
</div>

<!-- Modal External-->
<div class="modal hide fade in" id="modal" style="display: none; width: 70%; margin-left: -30%;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Detalle Conceptos</h4>
            </div>
            <div class="modal-body" align="center">
                <img src="<?= base_url() ?>/img/27.gif" width="150px" height="150px" />
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-default" data-dismiss="modal" id="cancelar">Cancelar</a>
                <a href="#" class="btn btn-primary mce-text" id="text-mce_127">Guardar</a>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

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
</script> 