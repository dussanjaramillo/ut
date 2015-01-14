<!--
    /**
     * Formulario que muestra las consulta de recordatorios o Timers
     * Se muestra la informacion generada por la consulta de Recordatorios o Timers
     * @author Haider Oviedo Martinez :: Thomas MTI
     * @since 26 II 2013 
     */
-->
<!--<pre>
<?php print_r($instancias) ?>
</pre>-->
<h1>Gesti&oacute;n de Instancias</h1>

<table id="notifica" width="100%">
    <thead>
        <tr>
            <th>Codigo Instancia</th>
            <th>Nombre Instancia</th>
            <th>Estado Instancia</th>
            <th>Editar</th>
            <th>Activar e Inactivar</th>

        </tr>
    </thead>
    <tbody>

        <?php
        foreach ($estados->result_array as $est) {
            $oculto = $est['COD_TIPO_INSTANCIA'];
            ?>
            <tr>
                <td id='CODINSTANCIA'><?php echo $est['COD_TIPO_INSTANCIA']; ?></td>
                <td id='NOMINSTANCIA'><?php echo $est['NOMBRE_TIPO_INSTANCIA']; ?></td>
                <?php if ($est['ESTADO'] == 'A') {
                    ?>
                    <td id='ESTINSTANCIA'>Activo</td>
                <?php } else { ?>
                    <td id='ESTINSTANCIA'>Inactivo</td>
                <?php } ?>
                <?php echo '<td align="center"><a href="' . base_url() . 'index.php/gestioninstancias/editarestado/' . $oculto . '" class="btn btn-small" title="Editar" data-toggle="modal" data-target="#modal" data-keyboard="false" data-backdrop="static"><i class="fa fa-edit" id="editar"></i></a></td>'; ?>    
                <?php if ($est['ESTADO'] == 'A') { ?>
                    <td align="center"><a onclick="confirmar1('<?php echo $est['COD_TIPO_INSTANCIA'] ?>')" href="#" class="btn btn-success" title="Inactivar" id="Inactivar"><i class="fa fa-check-square"></i></a></td>
                <?php } else { ?>
                    <td align="center"><a onclick="confirmar2('<?php echo $est['COD_TIPO_INSTANCIA'] ?>')" href="#" class="btn btn-danger" title="Activar" id="Activar"><i class="fa fa-minus-square"></i></a></td>
                <?php } ?>


            </tr>

            <?php
        }
        ?>

    </tbody>    
</table>

<table style="float:right">
    <br>
    <td>
        <form action= "<?php echo base_url('index.php/gestioninstancias/creanestados'); ?>" method="post">
            <button class="btn btn-success" style="height: 30px; width: 100px; display: block; margin: 0 auto 0 auto"><i class="fa fa-plus-circle"> Agregar</i></button>

        </form>
    </td>

    <td>
        <form action= "<?php echo base_url('index.php/'); ?>" method="post">
            <button class="btn btn-normal" style="height: 30px; width: 100px; display: block; margin: 0 auto 0 auto"><i class="fa fa-arrow-circle-left"> Volver</i></button>
        </form>
    </td>    
</table>
<!-- Modal External-->
<div class="modal hide fade in" id="modal" style="display: none; width: 40%; margin-left: -20%;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>-->
                <h4 class="modal-title">Detalle Actualizaci&oacute;n</h4>
            </div>
            <div class="modal-body" align="center">
                <img src="<?php echo base_url(); ?>/img/27.gif" width="150px" height="150px" />
                <!--<progress value="10" max="100" id="progressbar" style="width: 250px; height: 40px; webkit-progress-bar {background-color: gold;} "></progress>-->
            </div>
            <div class="modal-footer">
                <!--<a href="#" class="btn btn-default" data-dismiss="modal" id="cancelar">Cancelar</a>-->
                <!--<a href="#" class="btn btn-primary mce-text" id="text-mce_127">Guardar</a>-->
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
    $('#notifica').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
        //"bFilter": false, 

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
                "sFirst": "|Primero|",
                "sLast": "|Último|",
                "sNext": "|Siguiente|",
                "sPrevious": "|Anterior|"
            },
            "fnInfoCallback": null,
        },
    });

    function confirmar1(id) {
        bootbox.confirm("Esta seguro que desea Inactivar La Instancia?", function(result) {
            if (result == true) {
                var url = "<?php echo base_url() ?>index.php/gestioninstancias/inactivar_estado";
                $.ajax({
                    url: url,
                    data: {"est1": id},
                    type: 'POST',
                }).done(function() {
                    window.location.reload();
                    bootbox.alert('Instancia Inactiva');
                })
            }
        });
    }
    
    function confirmar2(id) {
        bootbox.confirm("Esta seguro que desea Activar La Instancia?", function(result) {
            if (result == true) {
                var url = "<?php echo base_url() ?>index.php/gestioninstancias/activar_estado";
                $.ajax({
                    url: url,
                    data: {"est": id},
                    type: 'POST',
                }).done(function() {
                    window.location.reload();
                    bootbox.alert('Instancia Activa');
                })
            }
        });
    }

    $('#editar').click(function() {
        var progressbar = $('#progressbar'),
                max = progressbar.attr('max'),
                time = (500 / max) * 5,
                value = progressbar.val();
        var loading = function() {
            value += 1;
            addValue = progressbar.val(value);
            $('.progress-value').html(value + '%');
            if (value == max) {
                clearInterval(animate);
            }
        };
        var animate = setInterval(function() {
            loading();
        }, time);
    });

    function ajaxValidationCallback(status, form, json, options) {
    }

</script>

