    <style>
        #tablaq{
            font-size: 12px !important;
        }
    </style>
    <h2>Administración Checklist Recepción Títulos</h2>
    <?php
    if (isset($message))
        echo $message;

    $html = ' 
    <table id="tablaq">
        <thead>
            <tr>
                <th>Codigo</th>
                <th>Tipo Cartera</th>
                <th>Identificador</th>
                <th>Descripción</th>
                <th>Orden</th>
                <th>Fecha Creación</th>
                <th>Editar</th>
            </tr>
        </thead>
        <tbody>';

    foreach ($listado->result_array as $data) {
        $hidden = array('concepto' => $data["ID_CONCEPTO"], 'descripcion' => $data["DESCRIPCION"]);
        $html .= '
            <tr>
                <td align="left">' . $data["ID_CONCEPTO"] . '</td> 
                <td align="left">' . $data["NOMBRE_CONCEPTO"] . '</td> 
                <td align="left">' . $data["DESCRIPCION"] . '</td> 
                <td align="left">' . $data["TEXTO"] . '</td>
                <td align="center">' . $data["ORDEN"] . '</td>
                <td align="center">' . $data["FECHA_CREACION"] . '</td>
                <td align="center">' . form_open("checklist/edit", '', $hidden) . '<input type="submit" class="at"  id="cod_asign" name="cod_asign" class="btn btn-success" value="Editar"></form></td> 
            </tr>';
    }

    $html .= '
        </tbody>  
    </table>';

    $html .= '<br>
    <table align="center">
        <tr>
            <td align="center">' . form_open("checklist/add", '', '') . '<input type="submit" class="at"  id="new" name="new" class="btn btn-success" value="Nuevo"></form></td> 
        </tr>
    </table>';

    echo $html;
    ?>

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