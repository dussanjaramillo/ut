<div align="center">    
    <br><?= $nombreusuario ?>
    <br><?= $cargousuario ?>
</div>
<div id="ajax_load" class="ajax_load" style="display: none">
    <div class="preload" id="preload" >
        <img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
    </div>
</div>
<table align="center">
    <tr>
        <td colspan="3"><h1>Crear Garantía<h1></td>
    </tr> 
    <tr>
        <td><b>Nuevo Tipo Garantía</b></td>
        <td><input type="text" id="garantia" placeholder="Nueva Garantia"></td>
    </tr>
    <tr>
        <td colspan="2" align="right"><button type="button" id="guardargarantia" class="clon btn btn-success">Crear</button></td>
    </tr>
</table>
                    <br><br>
                    <div id="loadgarantias"></div>
                    <img id="preloadmini" src="<?php echo base_url('img/319.gif') ?>" width="28" height="28" />
                    <table id="estilogarantia">
                        <thead>
                        <th>Codigo</th>
                        <th>Tipo Garantía</th>
                        <th>Nombre Garantía</th>
                        </thead>
                        <tbody>
                            <?php foreach ($historiagarantia->result_array as $todasgarantias) { ?>
                                <tr class="prueba">    
                                    <td><?= $todasgarantias['COD_CAMPO'] ?></td>
                                    <td><?= $todasgarantias['NOMBRE_TIPOGARANTIA'] ?></td>
                                    <td class="editarcampos"><?= $todasgarantias['NOMBRE_CAMPO'] ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <div id="editar">
                        <table>
                            <tr>
                                <td>Garantía : </td>
                                <td id="leergarantia">
                                </td>
                            </tr>
                            <tr>
                                <td>Campo : </td>
                                <td><input type="text" id="campoeditar"></td>
                            </tr>
                        </table>
                    </div>
                    <style>
                        #editar{
                            display: none;
                        }
                        .ui-widget-overlay{z-index: 10000;}
                        .ui-dialog{
                            z-index: 15000;
                        }
                    </style>
                    <script>

                        $("#preloadmini").hide();
                        var tabla = $('#estilogarantia').dataTable({
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
                            }
                        }).fnSetColumnVis(0, false);

                        $('#estilogarantia tbody tr td').click(function() {

                            var oTable = $('#estilogarantia').dataTable();
                            var posicion = oTable.fnGetPosition(this);
                            var columnas = oTable.fnGetData(posicion[0]);

                            var editar = columnas[2];
                            //        console.log(this);
                            $('#campoeditar').val(columnas[2]);
                            if ($('garan').length == 0)
                                $('#leergarantia').append('<h5 id="garan" >' + columnas[1] + '<h5>');
                            id = columnas[0];
                            garantia = columnas[1];
                            var encuentro = this;

                            $('#editar').dialog({autoOpen: true,
                                modal: true,
                                width: 350,
                                title: "Editar",
                                height: 200,
                                dialogClass: "no-close",
                                resizable: true,
                                close: function() {
                                    $('#garan').remove();
                                },
                                buttons: [{
                                        text: "Guardar",
                                        id: "guardareditable",
                                        class: "btn btn-success",
                                        click: function() {
                                            encuentro.innerHTML = $('#campoeditar').val();
                                            var url = "<?= base_url("index.php/acuerdodepago/ingresarcambiosgarantias") ?>";
                                            var modificacioncampo = $('#campoeditar').val();
                                            var opcion = 1
                                            $(this).dialog("close");
                                            $.post(url, {id: id, garantia: garantia, modificacioncampo: modificacioncampo, opcion: opcion});
                                            //                                            alert("guardado correctamente");
                                        }
                                    }, {
                                        text: "Eliminar",
                                        id: "elimareditable",
                                        class: "btn btn-success",
                                        click: function() {
                                            //                                            console.log(posicion[0]);
                                            oTable.fnDeleteRow(posicion[0]);
                                            var opcion = 2;
                                            var url = "<?= base_url("index.php/acuerdodepago/ingresarcambiosgarantias") ?>";
                                            $.post(url, {id: id, opcion: opcion});
                                            $(this).dialog("close");
                                        }
                                    }]
                            });
                        });

                        $('.eliminar').click(function() {
                            var id_row = $(this).closest('tr').attr('id');
                            var oTable = $('#estilogarantia').dataTable();
                            oTable.fnUpdate(['a', 'b', 'c', 'd'], oTable.fnGetPosition(this));
                        });
                        var url = "<?= base_url('index.php/acuerdodepago/tablagarantias') ?>";
                        $("#loadgarantias").load(url);
                        $("#guardargarantia").click(function() {
                            $(".ajax_load").show("slow");
                            var tipodeudor = $('#tipodeudor').val();
                            $('#loadgarantias *').remove();
                            var garantia = $('#garantia').val();
                            $("#loadgarantias").load(url, {garantia: garantia, tipodeudor: tipodeudor});
                        });
                    </script>