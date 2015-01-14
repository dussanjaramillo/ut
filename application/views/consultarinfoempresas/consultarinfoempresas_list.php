<?php
if (isset($message)) {
    echo $message;
}
?>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<div class="center-form-large">
    <div class="text-center"><h2>Consultar empresas</h2><h3 class="titulo">Filtros de búsqueda</h3></div>
    <fieldset>
        <?php $attributes = array('class' => 'form-inline', 'id' => 'myformajax');
        echo form_open("consultarinfoempresas/buscar", $attributes);
        ?>
        <div style="overflow:hidden">
            <span class="span3"><label for="nit">Número de identificación y/o Razón Social:</label></span>
            <span class="span5"><?php
                $data = array(
                    'name' => 'nit',
                    'id' => 'nit',
                    'class' => 'validate[custom[onlyNumber], minSize[4], maxSize[20]]'
                );
                echo form_input($data);
                ?>
                <img id="preloadmini" src="<?php echo base_url('img/319.gif') ?>" width="28" height="28" />
            </span>
        </div><br>
        <div style="overflow:hidden">
            <span class="span3"><label for="ciudad">Ciudad:</label></span>
            <span class="span5">
                <select name="ciudad" id="ciudad">
                    <option value="">Seleccione la ciudad...</option>
                    <?php
                    foreach ($ciudad as $key => $value) :
                        ?>
                        <option value="<?php echo $value->CODMUNICIPIO . "::" . $value->COD_DEPARTAMENTO; ?>"><?php echo $value->NOMBREMUNICIPIO . " - " . $value->NOM_DEPARTAMENTO ?></option>
                        <?php
                    endforeach;
                    ?>
                </select>
            </span>
        </div><br>
        <div style="overflow:hidden">
            <span class="span3"><label for="razon">Regional</label></span>
            <span class="span5">
                <select name="regional" id="regional">
                    <option value="">Seleccione la regional...</option>
                    <?php
                    foreach ($regional as $key => $value) :
                        ?>
                        <option value="<?php echo $value->COD_REGIONAL; ?>"><?php echo $value->NOMBRE_REGIONAL ?></option>
                        <?php
                    endforeach;
                    ?>
                </select>
            </span>
        </div><br>
        <div class="text-center">
            <?php
            $data = array(
                'name' => 'button',
                'id' => 'submit-button',
                'value' => 'seleccionar',
                'type' => 'submit',
                'content' => '<i class="fa fa-search"></i> Buscar',
                'class' => 'btn btn-success'
            );

            echo form_button($data);
            ?>
        </div>
<?php echo form_close(); ?>
    </fieldset>
</div>
<br>
<div class="resultados">
    <h3 class="text-center">Resultados de la búsqueda</h3>
    <table id="tablaq">
        <thead>
            <tr>
                <th>ID empresa</th>
                <th>Razón social</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <th>Ciudad</th>
                <th>Regional</th>
                <th>Empresa</th>
                <th>CIIU</th>
                <th>No empleados</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tbody"></tbody>     
    </table>
</div>

<script type="text/javascript" language="javascript" charset="utf-8">
//generación de la tabla mediante json
    $(document).ready(function() {
        $("#tablaq").hide();
        $(".preload, .load").hide();
        $("#preloadmini, #preloadmini2").hide();
        $("#nit").autocomplete({
            source: "<?php echo base_url("index.php/consultarinfoempresas/traernits") ?>",
            minLength: 4,
            search: function(event, ui) {
                $("#preloadmini").show();
            },
            response: function(event, ui) {
                $("#preloadmini").hide();
            }
        });
    });
// Called once the server replies to the ajax form validation request
    function ajaxValidationCallback(status, form, json, options) {
        if (window.console)
            console.log(status);

        if (status === true) {
            $("#tablaq, .aplicapago").show();
            var oTable = $('#tablaq').dataTable({
                "bJQueryUI": true,
                "bProcessing": true,
                "bServerSide": false,
                "bProcess": true,
                "bFilter": false,
                "bSort": true,
                "bDestroy": false,
                "bRetrieve": true,
                "bPaginate": true,
                "sPaginationType": "full_numbers",
                /*"aaData": json.aaData,*/
                "iDisplayStart": 0,
                "iDisplayLength": 10,
                "bLengthChange": true,
                "iTotalRecords": json.iTotalRecords,
                "iTotalDisplayRecords": json.iTotalDisplayRecords,
                "sEcho": 1,
                "oLanguage": {
                    "sProcessing": "<i class='fa fa-spinner fa-spin'></i>",
                    "sLengthMenu": "Mostrar _MENU_ registros",
                    "sZeroRecords": "No se encontraron resultados",
                    "sEmptyTable": "Ningún dato disponible en esta tabla",
                    "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                    "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                    "sInfoPostFix": "",
                    "sSearch": 'Buscar:',
                    "sUrl": "",
                    "sInfoThousands": ",",
                    "sLoadingRecords": "Cargando...",
                    "oPaginate": {
                        "sFirst": "Primero",
                        "sLast": "Último",
                        "sNext": "Siguiente",
                        "sPrevious": "Anterior"
                    },
                    "oAria": {
                        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                    }
                },
                "aoColumns": [
                    {"bSortable": true, "bSearchable": false, "sClass": "center"},
                    {"bSortable": true, "bSearchable": false, "sClass": "item"},
                    {"bSortable": false, "bSearchable": false, "sClass": "center"},
                    {"bSortable": false, "bSearchable": false, "sClass": "center"},
                    {"bSortable": false, "bSearchable": false, "sClass": "center"},
                    {"bSortable": false, "bSearchable": false, "sClass": "center"},
                    {"bSortable": true, "bSearchable": false, "sClass": "item"},
                    {"bSortable": false, "bSearchable": false, "sClass": "item"},
                    {"bSortable": true, "bSearchable": false, "sClass": "center"},
                    {"bSortable": false, "bSearchable": false, "bSortable": false, "sClass": "center", "sWidth": "1%"}
                ],
            });
            oTable.fnClearTable();
            oTable.fnAddData(json.aaData);
            oTable.fnDraw();
        }
        else {
            alert('error de consulta de datos');
        }
        setTimeout(function() {
            jQuery(".preload, .load").hide();
        }, 2000);
    }

    function datatable(aaData, iTotalRecords, iTotalDisplayRecords) {

    }
</script>
