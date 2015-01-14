<?php if (isset($message)) echo $message; ?>

<div style="padding-top: 20px;">
    <h5>Lista de Cargues</h5>
</div>

<?php
$html = ' 
<table id="tablaq">
    <thead>
        <tr>
            <th>Codigo Cargue</th>
            <th>Banco</th>
            <th>Fecha Cargue</th>
            <th>Fecha Archivo</th>
            <th>Filas Cargadas</th>
            <th>Filas Archivo</th>
            <th>Valor Cargue</th>
            <th>Usuario</th>
            <th>Log</th>
            <th>Archivo</th>
        </tr>
    </thead>
    <tbody>';

foreach ($data1->result_array as $data) {
    $oculto = array('archivo' => $data["ARCHIVO"]);
    $oculto2 = $data["COD_ASOBANCARIA"];
    $html .= '
        <tr>
            <td align="right">' . $data["COD_ASOBANCARIA"] . '</td> 
            <td>' . $data["BANCO"] . '</td> 
            <td align="center">' . $data["FECHA_CARGUE"] . '</td>
            <td align="center">' . $data["FECHA_RECAUDO"] . '</td>
            <td align="right">' . number_format($data["TOTAL_PLANILLAS"]) . '</td>
            <td align="right">' . number_format($data["NRO_FILAS_CARGADAS"]) . '</td>
            <td align="right">' . number_format($data["TOTAL_RECAUDADO"]) . '</td>
            <td>' . $data["USUARIO"] . '</td>
            <td align="center">' . form_open("cargarextractoasobancaria/exporta", '', $oculto) . '<input type="submit" class="at"  id="cod_asign" name="cod_asign" class="btn btn-success" value="Exportar"></form></td> 
            <td align="center"><a href="' . base_url() . 'index.php/cargarextractoasobancaria/archivo/' . $oculto2 . '" class="btn btn-small" title="Ver" data-toggle="modal" data-target="#modal" data-keyboard="false" data-backdrop="static"><i class="fa fa-eye"></i></a></td> 
        </tr>';
}
$html .= '
    </tbody>  
</table>';
echo $html;
?>

<!-- Modal External-->
<div class="modal hide fade in" id="modal" style="display: none; width: 70%; margin-left: -30%;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Detalle Cargue</h4>
            </div>
            <div class="modal-body" align="center">
                <img src="<?= base_url() ?>/img/27.gif" width="150px" height="150px" />
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-default" data-dismiss="modal" id="cancelar">Cancelar</a>
                <!--<a href="#" class="btn btn-primary mce-text" id="text-mce_127">Guardar</a>-->
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript" language="javascript" charset="utf-8">
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
    });
</script>