<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />

<h1>Consulta Traza Proceso Judicial</h1>
<br>


<form action="<?php echo base_url('index.php/consultarprocesos/actualizatrazajudicial'); ?>" name="form6" id="form6" method="POST">
    <table cellspacing="20" align="center">
        <tr>
            <td style="margin: 10px; padding: 10px;">
                <label>N&uacutemero Nit</label>
            </td>
            <td style="margin: 10px; padding: 10px;">
                <input name="CODEMPRESA" id="CODEMPRESA" onkeypress="return  soloNumeros(event)">
            </td>

            <td style="margin: 10px; padding: 10px;">
                <label>Ubicaci&oacute;n Bien </label>
            </td>
            <td style="margin: 10px; padding: 10px;">

                <select name="CIUDAD" id="CIUDAD">
                    <option value="">Buscar por Ciudad</option>
                    <?php
                    foreach ($ciudades as $i => $col3)
                        echo '<option value = "' . $i . '">' . $col3 . '</option>';
                    ?>
                </select>

            </td>
        </tr>

        <tr>    
            <td style="margin: 10px; padding: 10px;">
                <label>Raz&oacute;n Social </label>
            </td>

            <td style="margin: 10px; padding: 10px;">
                <input name="NOMEMPRESA" id="NOMEMPRESA" onkeypress="return  soloLetras(event)" style="text-transform:uppercase;">
            </td>

            <td style="margin: 10px; padding: 10px;">
                <label>Regional </label>
            </td>

            <td style="margin: 10px; padding: 10px;">
                <select name="REGIONAL" id="REGIONAL">

                    <option value="">Buscar por Regional</option>

                    <?php
                    foreach ($regionales as $i => $col1)
                        echo '<option value = "' . $i . '">' . $col1 . '</option>';
                    ?>
                </select>

            </td>

        </tr>    

        <tr>
            <td style="margin: 10px; padding: 10px;">
                <label>N&uacutemero Titulo</label>
            </td>
            <td style="margin: 10px; padding: 10px;">
                <input name="TITULO" id="TITULO" onkeypress="return  soloNumeros(event)">
            </td>

            <td style="margin: 10px; padding: 10px;">
                <div id="LABUSER" style='display:none;'>
                    <label>Usuario Por Regional: </label>
                </div>
            </td>

            <td style="margin: 10px; padding: 10px;">
                <div id="USUARIO" style='display:none;'>
                    <select name="selUsuarios" id ="selUsuarios" required="required">
                        <option value="0">Seleccione el Usuario</option>
                    </select>
                </div>

            </td>

        <tr>
        <br>
        <td>
            <label style="padding: 10px; text-align:left;">Usuario: </label>
        </td>

        <td style="margin: 10px; padding: 10px;">
            <input name="USERS" id="USERS" onkeypress="return  soloLetras(event)" style="text-transform:uppercase;">
        </td>

        <td></td>

        <td style="margin: 10px; padding: 10px;text-align:right">
            <button onclick="return valida();"  class="btn btn-success" type="submit" value="Consultar" id="consultatraza"><i class="fa fa-search"> Consultar</i></button>
        </td>


        </tr>


    </table>
</form>    
<br>


<table id="traza" width="100%" style="f">
    <thead>
        <tr>
            <td>N&uacutemero Titulo</td>
            <th>N&uacutemero de Nit</th>
            <th>Nombre Deudor</th>
            <th style="display:none">C&oacute;digo Regional</th>
            <th>Nombre Regional</th>
            <th>Correo Electron&iacute;co</th>
            <th>Direcci&oacute;n</th>
            <th style="display:none">Departamento</th>
            <th>Ubicaci&oacute;n Bien</th>            
            <th>CIIU</th>
<!--            <th>Documentos Fiscalizaci&oacute;n</th>-->
            <th>Ver Proceso</th>
            <th>Exportar Traza a Pdf</th>
        </tr>
    </thead>
    <tbody>

        <?php
        foreach ($datos->result_array as $col) {
            ?>
            <tr>
                <td><?= $col['COD_TITULO'] ?></td>
                <td><?= $col['COD_PROPIETARIO'] ?></td>
                <td><?= $col['NOMBRE_PROPIETARIO'] ?></td>
                <td style="display:none"><?= $col['COD_REGIONAL'] ?></td>
                <td><?= $col['NOMBRE_REGIONAL'] ?></td>
                <td><?= $col['CORREO_PROPIETARIO'] ?></td>
                <td><?= $col['DIRECCION_INMUEBLE'] ?></td>
                <td style="display:none"><?= $col['COD_DEPARTAMENTO'] ?></td>
                <td><?= $col['NOM_DEPARTAMENTO'] ?></td>
                <td><?= $col['CIIU'] ?></td>
    <!--                <td>
                    <form action= "<?php
                $tal = $col['COD_TITULO'];
                echo base_url('/uploads/fiscalizaciones/' . "$tal");
                ?>" method="post" target="_blank">
                        <br>
                        <button class="btn btn-info" type="submit" value="Documentos" id="Documentos" ><i class="fa fa-archive"> Archivos</i></button>
                    </form>
                </td>-->
                <td>
                    <button class="btn btn-info at" id="traza" name="traza" at="<?= $col['COD_TITULO'] ?>" onclick="gestion('<?= $col['COD_TITULO'] ?>')"><i class="fa fa-eye"> Ver</i></button>

                </td>
                <td align="center">
                    <form id="form" name="form" target = "_blank"  method="post" action="<?php echo base_url('index.php/consultarprocesos/pdfjudicial') ?>">
                        <br>
                        <button type="submit" name="pdf" id="pdf" class="btn btn-info"><i class="fa fa-file-pdf-o"> Pdf</i></button>
                        <input type="hidden" name="cod_judicial" id="cod_judicial" value="<?php echo $col['COD_TITULO']; ?>" >
                    </form>
                </td>

            </tr>
            <?php
        }
        ?>

    </tbody>    
</table>

<div id="cargar" name="cargar">
</div>

<script>
    $("#USERS").autocomplete({
        source: "<?php echo base_url("index.php/consultarprocesos/getusuarios") ?>",
        minLength: 3
    });

    $('#CODEMPRESA').change(function() {
        if (isNaN($(this).val())) {
            bootbox.alert('Debe ingresar solo valores numericos');
            $(this).val('');
        }

    });

    $('#TITULO').change(function() {
        if (isNaN($(this).val())) {
            bootbox.alert('Debe ingresar solo valores numericos');
            $(this).val('');
        }

    });

    $('#traza').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
        //"bFilter": false, 

        "oLanguage": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ning&uacute;n dato disponible en esta tabla",
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
        "bLengthChange": false,
        "aoColumns": [
            {"sClass": "center"},
            {"sClass": "center"},
            {"sClass": "center"},
            {"sClass": "center"},
            {"sClass": "center"},
            {"sClass": "center"},
            {"sClass": "center"},
            {"sClass": "center"},
            {"sClass": "center"},
            {"sClass": "center"},
            {"sClass": "center"},
            {"sClass": "center"},
        ]

    });

    function gestion(id) {
        var oculto = document.getElementById('cargar');
        window.scrollTo(0, oculto.offsetTop);
        //document.getElementById("dato1").focus();
//    $(".at").click(function() {

        $(".preload, .load").show();

        var url = "<?= base_url('index.php/consultarprocesos/verprocesojudicial') ?>";

        $('#cargar').load(url, {id: id});


//    });
    }

    function valida() {
        if ($("#CODEMPRESA").val() == "" && $("#NOMEMPRESA").val() == "" && $("#CIUDAD").val() == "" && $("#REGIONAL").val() == "" && $("#TITULO").val() == "" && $("#USERS").val() == "") {
            document.forms.form6.action = "<?php echo base_url("index.php/consultarprocesos/consultatrazajudicial") ?>";
        }

    }


//    $(document).ready(function(){
//        $('#form6 #NOMEMPRESA').addClass('validate[custom[number]]');
//        $('#form6 #NOMEMPRESA').validationEngine();
//    })

    function soloLetras(e) {
        key = e.keyCode || e.which;
        tecla = String.fromCharCode(key).toLowerCase();
        letras = " áéíóúabcdefghijklmnñopqrstuvwxyz&1234567890";
        especiales = "8";

        tecla_especial = false
        for (var i in especiales) {
            if (key == '46') {
                tecla_especial = true;
                break;
            }
            if (key == '8') {
                tecla_especial = true;
                break;
            }
            if (key == '37') {
                tecla_especial = true;
                break;
            }
            if (key == '39') {
                tecla_especial = true;
                break;
            }

            if (key == '9') {
                tecla_especial = true;
                break;
            }
            if (key == '11') {
                tecla_especial = true;
                break;
            }
        }
        if (letras.indexOf(tecla) == -1 && !tecla_especial) {
            return false;
        }
        return true;

    }

    function soloNumeros(e) {
        key = e.keyCode || e.which;
        tecla = String.fromCharCode(key).toLowerCase();
        letras = " 0123456789";
        especiales = "8";

        tecla_especial = false
        for (var i in especiales) {
            if (key == '46') {
                tecla_especial = true;
                break;
            }
            if (key == '8') {
                tecla_especial = true;
                break;
            }
            if (key == '37') {
                tecla_especial = true;
                break;
            }
            if (key == '39') {
                tecla_especial = true;
                break;
            }
            if (key == '67') {
                tecla_especial = true;
                break;
            }
            if (key == '99') {
                tecla_especial = true;
                break;
            }
            if (key == '86') {
                tecla_especial = true;
                break;
            }
            if (key == '118') {
                tecla_especial = true;
                break;
            }

            if (key == '9') {
                tecla_especial = true;
                break;
            }
            if (key == '11') {
                tecla_especial = true;
                break;
            }
        }

        if (key == '46') {
            return false;
        }

        if (letras.indexOf(tecla) == -1 && !tecla_especial) {
            return false;
        }
        return true;

    }

    $('#consultatraza').click(function() {
        $(".preload, .load").show();
    }
    );

    $(".preload, .load").hide();


    function ajaxValidationCallback(status, form, json, options) {
    }

    $('.traza').click(function() {
        $(".preload, .load").show();
    });
    
    $("#NOMEMPRESA").autocomplete({
        source: "<?php echo base_url("index.php/consultarprocesos/getallnombres") ?>",
        minLength: 3
    });

    $("#CODEMPRESA").autocomplete({
        source: "<?php echo base_url("index.php/consultarprocesos/getallcodigos") ?>",
        minLength: 3
    });
    
    $("#REGIONAL").change(function()
    {
        var REGIONAL = $(this).children(":selected").attr("value");
        if (REGIONAL != "")
        {
            document.getElementById('LABUSER').style.display = 'block';
            document.getElementById('USUARIO').style.display = 'block';
            jQuery(".preload, .load").show();
            var url = "<?php echo base_url('index.php/consultarprocesos/traerusuarios'); ?>";
            $.post(url, {REGIONAL: REGIONAL})
                    .done(function(msg) {
                        jQuery(".preload, .load").hide();
                        $('#selUsuarios').html(msg)
                    }).fail(function(msg) {
                jQuery(".preload, .load").hide();
                alert('Error en consulta de base de datos');
            });
        }
    });


</script>