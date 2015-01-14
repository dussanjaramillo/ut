<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />

<h1>Consulta Traza Por Entidades Proceso No Misional</h1>
<br>


<form action="<?php echo base_url('index.php/consultarprocesos/actualizatrazanomisional'); ?>" name="form6" id="form6" method="POST">
    <table cellspacing="20" align="center">
        <tr>
            <td style="margin: 10px; padding: 10px;">
                <label>N&uacutemero de Nit </label>
            </td>
            <td style="margin: 10px; padding: 10px;">
                <input name="CODEMPRESA" id="CODEMPRESA" onkeypress="return  soloNumeros(event)">
            </td>

            <td style="margin: 10px; padding: 10px;">
                <label>N&uacute;mero de Proceso No Misional </label>
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


<table id="traza" width="100%" style="f">
    <thead>
        <tr>
            <th>N&uacute;mero de Proceso No Misional</th>            
            <th>N&uacutemero de Nit</th>
            <th>Raz&oacute;n Social</th>
            <th>Tel&eacute;fono</th>
            <th>Direcci&oacute;n</th>
<!--            <th style="display:none">Departamento</th>
            <th>Nombre Departamento</th>-->
            <th style="display:none">C&oacute;digo Regional</th>
            <th>Nombre Regional</th>
            <th style="display:none">C&oacute;digo R&eacute;gimen</th>
            <td style="display:none">C&oacute;digo Fiscalizaci&oacute;n</td>
<!--            <th>CIIU</th>
            <th>Fecha Ingreso Cobro Coactivo</th>
            <th>Fecha Ejecutoria</th>
            <th>Fecha de Resoluci&oacute;n</th>-->
            <th>Documentos No Misional</th>

            <th>Ver Proceso</th>
            <th>Exportar Traza a Pdf</th>
        </tr>
    </thead>
    <tbody>

        <?php
        if($datos){
        foreach ($datos->result_array as $col) {
            ?>
<!--COD_CARTERA_NOMISIONAL AS CODCARTERA, 
            COD_EMPRESA AS CODEMPRESA, 
            CNM_EMPRESA.RAZON_SOCIAL AS NOMEMPRESA, 
            CNM_EMPRESA.TELEFONO AS TELEFONO,
            CNM_EMPRESA.DIRECCION AS DIRECCION,
            CNM_EMPRESA.COD_REGIONAL AS CODREGIONAL,
            CNM_EMPRESA.CORREO_ELECTRONICO AS EMAIL,
            REGIONAL.NOMBRE_REGIONAL AS NOMREGIONAL-->
            <tr>
                <td><?= $col['CODCARTERA'] ?></td>
                <td><?= $col['CODEMPRESA'] ?></td>
                <td><?= $col['NOMEMPRESA'] ?></td>
                <td><?= $col['TELEFONO'] ?></td>
                <td><?= $col['DIRECCION'] ?></td>
                <td style="display:none"><?= $col['CODREGIONAL'] ?></td>
                <td><?= $col['NOMREGIONAL'] ?></td>
                <td style="display:none"><?= $col['EMAIL'] ?></td>
                <td style="display:none"><?= $col['CODCARTERA'] ?></td>
                <td>
                    <form action= "<?php
                    // $fiscaliza = $col['COD_PROCESO_COACTIVO'];
                    $coactivo = $col['CODCARTERA'];
                    $nit = $col['CODEMPRESA'];
                    echo base_url('index.php/expedientes/documentos_proceso');
                    ?>" method="post" target="_blank">
                        <input id="codFiscalizacion" type="hidden" value="<?= $coactivo ?>" name="codFiscalizacion">
                        <input id="cod_empresa" type="hidden" value="<?= $nit ?>" name="cod_empresa">
                        <input id="codigo_pj" type="hidden" value="<?= $coactivo ?>" name="codigo_pj">
                        <br>
                        <button class="btn btn-info" type="submit" value="Documentos" id="Documentos" ><i class="fa fa-archive"> Archivos</i></button>
                    </form>
                </td>
                <td>
                    <button class="btn btn-info at" id="traza" name="traza" at="<?= $col['CODCARTERA'] ?>" onclick="gestion('<?= $col['CODCARTERA'] ?>')"><i class="fa fa-eye"> Ver</i></button>

                </td>

                <td align="center">
                    <form id="form" name="form" target = "_blank"  method="post" action="<?php echo base_url('index.php/consultarprocesos/pdfnomisional') ?>">
                        <br>
                        <button type="submit" name="pdf" id="pdf" class="btn btn-info"><i class="fa fa-file-pdf-o"> Pdf</i></button>
                        <input type="hidden" name="cod_fiscalizacion" id="cod_fiscalizacion" value="<?php echo $col['CODCARTERA']; ?>" >
                        
                    </form>
                </td>

            </tr>
            <?php
        }
        }
        ?>

    </tbody>    
</table>

<div id="cargar" name="cargar">
</div>

<script>
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
            
        ]

    });

    $("#USERS").autocomplete({
        source: "<?php echo base_url("index.php/consultarprocesos/getusuarios") ?>",
        minLength: 3
    });

    $("#NOMEMPRESA").autocomplete({
        source: "<?php echo base_url("index.php/consultarprocesos/getallnombres") ?>",
        minLength: 3
    });

    $("#CODEMPRESA").autocomplete({
        source: "<?php echo base_url("index.php/consultarprocesos/getallcodigos") ?>",
        minLength: 3
    });
    
    $("#FISCALIZACION").autocomplete({
        source: "<?php echo base_url("index.php/consultarprocesos/getcoactivos") ?>",
        minLength: 3
    });
    
    function gestion(id) {
        var oculto = document.getElementById('cargar');
        window.scrollTo(0, oculto.offsetTop);
        //document.getElementById("dato1").focus();
//    $(".at").click(function() {

        $(".preload, .load").show();

        var url = "<?= base_url('index.php/consultarprocesos/verprocesonomisional') ?>";

        $('#cargar').load(url, {id: id});


//    });
    }

    function valida() {
        if ($("#CODEMPRESA").val() == "" && $("#NOMEMPRESA").val() == "" && $("#CIUDAD").val() == "" && $("#REGIONAL").val() == "" && $("#FISCALIZACION").val() == "") {
            document.forms.form6.action = "<?php echo base_url("index.php/consultarprocesos/consultatrazajuridico") ?>";
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