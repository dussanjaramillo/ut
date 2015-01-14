<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />

<h1>Consulta Traza Por Entidades Proceso  Administrativo</h1>
<br>


<form action="<?php echo base_url('index.php/consultarprocesos/actualizatrazaadmin'); ?>" name="form6" id="form6" method="POST">
    <table cellspacing="20" align="center">
        <tr>
            <td style="margin: 10px; padding: 10px;">
                <label>N&uacutemero de Nit </label>
            </td>
            <td style="margin: 10px; padding: 10px;">
                <input name="CODEMPRESA" id="CODEMPRESA" onkeypress="return  soloNumeros(event)">
            </td>

            <td style="margin: 10px; padding: 10px;">
                <label>N&uacute;mero del Expediente  </label>
            </td>
            <td style="margin: 10px; padding: 10px;">
                <input name="FISCALIZACION" id="FISCALIZACION">
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
                <label>Usuario: </label>
            </td>

            <td style="margin: 10px; padding: 10px;">
                <input name="USERS" id="USERS" onkeypress="return  soloLetras(event)" style="text-transform:uppercase;">
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

        </tr>    


        <tr>
            <td>

            </td>
            <td>

            </td>
            <td>

            </td>

            <td style="margin: 10px; padding: 10px;text-align:right">
                <br>
                <button onclick="return valida();"  class="btn btn-success" type="submit" value="Consultar" id="consultatraza"><i class="fa fa-search"> Consultar</i></button>
            </td>
        </tr>

    </table>
</form>    
<br>


<table id="traza" width="100%" style="table-layout: fixed;">
    <thead>
        <tr>
            <th>N&uacutemero del Expediente</th>
            <th>N&uacutemero Nit</th>
            <th>Raz&oacute;n Social</th>
            <th>Tel&eacute;fono</th>
            <th>Direcci&oacute;n</th>
            <th style="display:none">Departamento</th>
            <th>Nombre Departamento</th>
            <th style="display:none">C&oacute;digo Regional</th>
            <th>Nombre Regional</th>
            <th style="display:none">C&oacute;digo R&eacute;gimen</th>
            <th style="display:none">N&uacute;mero de Proceso en Coactivo</th>
            <th>CIIU</th>
            <th>Documentos Fiscalizaci&oacute;n</th>

            <th>Ver Proceso</th>
            <th>Exportar Traza Pdf</th>
        </tr>
    </thead>
    <tbody>

        <?php
        foreach ($datos->result_array as $col) {
            ?>

            <tr>
                <td style="word-wrap: break-word;"><?= $col['COD_FISCALIZACION'] ?></td>
                <td style="word-wrap: break-word;"><?= $col['CODEMPRESA'] ?></td>
                <td style="word-wrap: break-word;"><?= $col['RAZON_SOCIAL'] ?></td>
                <td style="word-wrap: break-word;"><?= $col['TELEFONO_FIJO'] ?></td>
                <td style="word-wrap: break-word;"><?= $col['DIRECCION'] ?></td>
                <td style="display:none"><?= $col['COD_DEPARTAMENTO'] ?></td>
                <td style="word-wrap: break-word;"><?= $col['NOM_DEPARTAMENTO'] ?></td>
                <td style="display:none"><?= $col['COD_REGIONAL'] ?></td>
                <td style="word-wrap: break-word;"><?= $col['NOMBRE_REGIONAL'] ?></td>
                <td style="display:none; word-wrap: break-word;"><?= $col['COD_REGIMEN'] ?></td>
                <td style="display:none; word-wrap: break-word;"><?= $col['CODIGO_PJ'] ?></td>
                <td style="word-wrap: break-word;"><?= $col['CIIU'] ?></td>
                <td style="word-wrap: break-word;">
                    <form action= "<?php
                    $tal = $col['COD_FISCALIZACION'];
                    echo base_url('/uploads/fiscalizaciones/' . "$tal");
                    ?>" method="post" target="_blank">
                        <br>
                        <button class="btn btn-info" type="submit" value="Documentos" id="Documentos" ><i class="fa fa-archive"> Archivos</i></button>
                    </form>
                </td>
                <td>
                    <button class="btn btn-info at" id="traza" name="traza" at="<?= $col['COD_FISCALIZACION'] ?>" onclick="gestion('<?= $col['COD_FISCALIZACION'] ?>')"><i class="fa fa-eye"> Ver</i></button>

                </td>
                <td align="center">
                    <form id="form" name="form" target = "_blank"  method="post" action="<?php echo base_url('index.php/consultarprocesos/pdfadmin') ?>">
                        <br>
                        <button type="submit" name="pdf" id="pdf" class="btn btn-info"><i class="fa fa-file-pdf-o"> Pdf</i></button>
                        <input type="hidden" name="cod_fiscalizacion" id="cod_fiscalizacion" value="<?php echo $col['COD_FISCALIZACION']; ?>" >
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

    $('#FISCALIZACION').change(function() {
        if (isNaN($(this).val())) {
            bootbox.alert('Debe ingresar solo valores numericos');
            $(this).val('');
        }

    });


    $('#CODEMPRESA').change(function() {
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
            {"sClass": "center"},
            {"sClass": "center"},
            {"sClass": "center"},
        ]

    });



    $("#NOMEMPRESA").autocomplete({
        source: "<?php echo base_url("index.php/consultarprocesos/getnombres") ?>",
        minLength: 3
    });

    $("#CODEMPRESA").autocomplete({
        source: "<?php echo base_url("index.php/consultarprocesos/getcodigos") ?>",
        minLength: 3
    });
    function gestion(id) {
        //document.getElementById("dato1").focus();
//    $(".at").click(function() {
        var oculto = document.getElementById('cargar');
        window.scrollTo(0, oculto.offsetTop);

        $(".preload, .load").show();

        var url = "<?= base_url('index.php/consultarprocesos/verprocesoadmin') ?>";

        $('#cargar').load(url, {id: id});


//    });
    }

    function pdf(id) {
        //document.getElementById("dato1").focus();
//    $(".at").click(function() {

        // $(".preload, .load").show();

        var url = "<?= base_url('index.php/consultarprocesos/pdf') ?>";
        $.post(url, {id: id}, function(data) {
            $("#resultado").html(data);

        })
        //  window.open(url,'_BLANK')
        //alert(id);

        //$('#pdf').load(url, {id: id});


//    });
    }

    function valida() {
        if ($("#CODEMPRESA").val() == "" && $("#NOMEMPRESA").val() == "" && $("#CIUDAD").val() == "" && $("#REGIONAL").val() == "" && $("#FISCALIZACION").val() == "") {
            document.forms.form6.action = "<?php echo base_url("index.php/consultarprocesos/consultatrazaadmin") ?>";
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