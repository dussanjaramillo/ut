<?php if (isset($message)) echo $message; ?>

<div style="text-align: center">
    <h2>Títulos para enviar a reparto</h2>
</div>

<table id="tabla1">
    <thead>
        <tr>
            <th>Identificación</th>
            <th>Empresa</th>
            <th>No Expediente</th>
            <th>Concepto</th>
            <th>Tipo Gestión</th>
            <th>Ver</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($data1->result_array as $data) {
            ?>
            <tr><td><?php echo $data["IDENTIFICACION"] ?></td> 
                <td><?php echo $data["EJECUTADO"] ?></td> 
                <td><?php echo $data["EXPEDIENTE"] ?></td> 
                <td><?php echo $data["CONCEPTO"] ?></td> 
                <td><?php echo $data["TIPOGESTION"] ?></td> 
                <td align="center"><a href="fisico/<?php echo $data["COD_RECEPCION"] ?>/expediente/<?php echo $data["EXPEDIENTE"] ?>" class="btn btn-small" title="Ver" data-toggle="modal" data-target="#modal" data-keyboard="false" data-backdrop="static"><i class="fa fa-eye"></i></a></td> 
            </tr>
        <?php } ?>

    </tbody>  
</table>

<!-- Modal External-->
<div class="modal hide fade in" id="modal" style="display: none; width: 70%; margin-left: -30%;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Enviar Título a Reparto</h4>
            </div>
            <div class="modal-body" align="center">
                <img src="<?= base_url() ?>/img/27.gif" width="150px" height="150px" />
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-default" data-dismiss="modal" id="cancelar">Cancelar</a>
                <a href="#" class="btn btn-success mce-text" id="reparto">Estudio de Títulos</a>
                <a href="#" class="btn btn-warning mce-text" id="rechazo">Rechazar</a>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript" language="javascript" charset="utf-8">
    $('#tabla1').dataTable({
        "bJQueryUI": true,
        "sServerMethod": "POST",
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
            }}
    });

    $('#reparto').click(function() {
        $('#accion').val('1');
        if ($('#fisico').prop('checked') == false) {
            alert("Para enviar a reparto debe existir el titulo en fisico");
            $('#fisico').focus();
            return false;
        }else{
            $('#titulofisico').submit();
        }
    });
    
    $('#rechazo').click(function() {
        $('#accion').val('2');
        if ($('#observaciones').val() == ''){
            alert("Debe escribir el motivo del rechazo");
            $('#observaciones').focus();
            return false;
        }else{
            $('#titulofisico').submit();
        }
    });
</script>
