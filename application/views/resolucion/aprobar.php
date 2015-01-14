<center><h2><?php echo $titulo ?></h2></center>
<p>
<table id="styletable">
    <thead>
    <th>Numero de Liquidaci&oacute;n</th>
    <th>Fecha Resoluci&oacute;n</th>
    <th>ID Deudor</th>
    <th>Abogado</th>
    <th>Num. Resoluci&oacute;n</th>
    <th>Concepto</th>
    <th>Estado</th>
    <th>Gestionar</th>
</thead>
<tbody>
    <?php
    $cantidad = count($consulta);
    for ($i = 0; $i < $cantidad; ++$i) {
        $informacion = $consulta[$i];
        ?>
        <tr>
            <td><?php echo $consulta[$i]['NUM_LIQUIDACION'] ?></td>
            <td><?php echo $consulta[$i]['FECHA_CREACION'] ?></td>
            <td><?php echo $consulta[$i]['NITEMPRESA'] ?></td>
            <td><?php echo $consulta[$i]['APELLIDOS'] . " " . $consulta[$i]['NOMBRES'] ?></td>
            <td><?php echo $consulta[$i]['NUMERO_RESOLUCION'] ?></td>
            <td><?php echo $consulta[$i]['NOMBRE_CONCEPTO'] ?></td>
            <td><?php echo $consulta[$i]['TIPOGESTION'] ?></td>
            <td>
                <?php 
//                echo $consulta[$i]['COD_ESTADO'];
                switch ($consulta[$i]['COD_ESTADO']) {
                    case 21:
                        ?>
                        <input type="radio" name="resolucion" class="resolucion" onclick="generar_resolucion('<?php echo $consulta[$i]['COD_FISCALIZACION'] ?>', '<?php echo $consulta[$i]['COD_RESOLUCION'] ?>')">
                        <?php
                        break;
                    case 23:
                        ?>
                        <input type="radio" name="resolucion" class="resolucion" onclick="confirmar('<?php echo $consulta[$i]['COD_RESOLUCION'] ?>', '<?php echo $consulta[$i]['NITEMPRESA'] ?>', '<?php echo $consulta[$i]['NITEMPRESA'] ?>', '<?php echo $consulta[$i]['COD_CPTO_FISCALIZACION'] ?>', '<?php echo $consulta[$i]['COD_FISCALIZACION'] ?>')">
                        <?php
                        break;
                    case 29:
                    case 662:
                    case 33:
                    case 34:
                    case 35:
                    case 310:
                    case 311:
                        ?>
                        <!--<input type="radio" name="resolucion" class="resolucion" onclick="confirmar('<?php echo $consulta[$i]['COD_RESOLUCION'] ?>', '<?php echo $consulta[$i]['NITEMPRESA'] ?>', '<?php echo $consulta[$i]['NITEMPRESA'] ?>', '<?php echo $consulta[$i]['COD_CPTO_FISCALIZACION'] ?>', '<?php echo $consulta[$i]['COD_FISCALIZACION'] ?>')">-->
                        <a href="javascript:" title="Realizar Citación" class="fa fa-envelope-o" onclick="cargar('<?php echo $consulta[$i]['COD_RESOLUCION'] ?>', '<?php echo $consulta[$i]['COD_FISCALIZACION'] ?>', '<?php echo $consulta[$i]['NITEMPRESA'] ?>')"></a>
                        <a href="javascript:" title="Ver pdf" class="fa fa-eye" onclick="pdf('<?php echo $consulta[$i]['COD_RESOLUCION']; ?>')"></a>
                        <?php
                        break;
                    case 28:
                        ?>
                        <input type="radio" name="resolucion" class="resolucion" onclick="confirmar('<?php echo $consulta[$i]['COD_RESOLUCION'] ?>', '<?php echo $consulta[$i]['NITEMPRESA'] ?>', '<?php echo $consulta[$i]['NITEMPRESA'] ?>', '<?php echo $consulta[$i]['COD_CPTO_FISCALIZACION'] ?>', '<?php echo $consulta[$i]['COD_FISCALIZACION'] ?>')">
                        <a href="javascript:" class="icon-pencil" title="Modificar" onclick="modificar('<?php echo $consulta[$i]['COD_RESOLUCION']; ?>', '<?php echo $consulta[$i]['COD_FISCALIZACION'] ?>', '<?php echo $consulta[$i]['NITEMPRESA'] ?>')"></a>
                        <?php
                        break;
                    case 27:
                        ?>
                        <input type="radio" name="resolucion" class="resolucion" onclick="confirmar('<?php echo $consulta[$i]['COD_RESOLUCION'] ?>', '<?php echo $consulta[$i]['NITEMPRESA'] ?>', '<?php echo $consulta[$i]['NITEMPRESA'] ?>', '<?php echo $consulta[$i]['COD_CPTO_FISCALIZACION'] ?>', '<?php echo $consulta[$i]['COD_FISCALIZACION'] ?>')">
                        <a href="javascript:" title="Subir archivo de resolución" class="fa fa-file-text" onclick="cargar2('<?php echo $consulta[$i]['COD_RESOLUCION'] ?>', '<?php echo $consulta[$i]['COD_FISCALIZACION'] ?>', '<?php echo $consulta[$i]['NITEMPRESA'] ?>')"></a>
                        <?php
                        break;
                    case 36:
                        ?>
                        <!--<input type="radio" name="resolucion" class="resolucion" onclick="confirmar('<?php echo $consulta[$i]['COD_RESOLUCION'] ?>', '<?php echo $consulta[$i]['NITEMPRESA'] ?>', '<?php echo $consulta[$i]['NITEMPRESA'] ?>', '<?php echo $consulta[$i]['COD_CPTO_FISCALIZACION'] ?>', '<?php echo $consulta[$i]['COD_FISCALIZACION'] ?>')">-->
                        <a href="javascript:" title="Recepcion de la citación" class="fa fa-cloud-upload" onclick="citacion_resep('<?php echo $consulta[$i]['COD_RESOLUCION'] ?>', '<?php echo $consulta[$i]['COD_FISCALIZACION'] ?>', '<?php echo $consulta[$i]['NITEMPRESA'] ?>')"></a>
                        <a href="javascript:" title="Ver pdf" class="fa fa-eye" onclick="pdf('<?php echo $consulta[$i]['COD_RESOLUCION']; ?>')"></a>
                        <?php
                        break;
                    case 30:
                    case 562:
                        ?>
                        <!--<input type="radio" name="resolucion" class="resolucion" onclick="confirmar('<?php echo $consulta[$i]['COD_RESOLUCION'] ?>', '<?php echo $consulta[$i]['NITEMPRESA'] ?>', '<?php echo $consulta[$i]['NITEMPRESA'] ?>', '<?php echo $consulta[$i]['COD_CPTO_FISCALIZACION'] ?>', '<?php echo $consulta[$i]['COD_FISCALIZACION'] ?>')">-->
                        <a href="javascript:" title="Recurso o Ejecutoria" class="fa fa-sun-o" onclick="resolver_recurso('<?php echo $consulta[$i]['COD_RESOLUCION'] ?>', '<?php echo $consulta[$i]['COD_FISCALIZACION'] ?>', '<?php echo $consulta[$i]['NITEMPRESA'] ?>','<?php echo $consulta[$i]['NUM_RECURSO'] ?>')"></a>
                        <a href="javascript:" title="Ver pdf" class="fa fa-eye" onclick="pdf('<?php echo $consulta[$i]['COD_RESOLUCION']; ?>')"></a>
                        <?php
                        break;
                    case 38:
                    case 39:
                    case 40://subir las actas
                        ?>
                        <!--<input type="radio" name="resolucion" class="resolucion" onclick="confirmar('<?php echo $consulta[$i]['COD_RESOLUCION'] ?>', '<?php echo $consulta[$i]['NITEMPRESA'] ?>', '<?php echo $consulta[$i]['NITEMPRESA'] ?>', '<?php echo $consulta[$i]['COD_CPTO_FISCALIZACION'] ?>', '<?php echo $consulta[$i]['COD_FISCALIZACION'] ?>')">-->
                        <a href="javascript:" title="Subir acta" class="fa fa-archive" onclick="subir_acta('<?php echo $consulta[$i]['COD_RESOLUCION'] ?>', '<?php echo $consulta[$i]['NUMERO_CITACION'] ?>',
                                        '<?php echo $consulta[$i]['COD_FISCALIZACION'] ?>', '<?php echo $consulta[$i]['NITEMPRESA'] ?>')"></a>
                        <a href="javascript:" title="Ver pdf" class="fa fa-eye" onclick="pdf('<?php echo $consulta[$i]['COD_RESOLUCION']; ?>')"></a>
                        <?php
                        break;
                    case 47:
                        ?>
                        <!--<input type="radio" name="resolucion" class="resolucion" onclick="confirmar('<?php echo $consulta[$i]['COD_RESOLUCION'] ?>', '<?php echo $consulta[$i]['NITEMPRESA'] ?>', '<?php echo $consulta[$i]['NITEMPRESA'] ?>', '<?php echo $consulta[$i]['COD_CPTO_FISCALIZACION'] ?>', '<?php echo $consulta[$i]['COD_FISCALIZACION'] ?>')">-->
                        <a href="javascript:" title="Resolver recurso" class="fa fa-retweet" onclick="resolver_recurso_gestionar('<?php echo $consulta[$i]['COD_RESOLUCION'] ?>','<?php echo $consulta[$i]['COD_FISCALIZACION'] ?>', '<?php echo $consulta[$i]['NITEMPRESA'] ?>')"></a>
                        <a href="javascript:" title="Ver pdf" class="fa fa-eye" onclick="pdf('<?php echo $consulta[$i]['COD_RESOLUCION']; ?>')"></a>
                        <?php
                        break;
                    case 48:
                        ?>
                        <!--<input type="radio" name="resolucion" class="resolucion" onclick="confirmar('<?php echo $consulta[$i]['COD_RESOLUCION'] ?>', '<?php echo $consulta[$i]['NITEMPRESA'] ?>', '<?php echo $consulta[$i]['NITEMPRESA'] ?>', '<?php echo $consulta[$i]['COD_CPTO_FISCALIZACION'] ?>', '<?php echo $consulta[$i]['COD_FISCALIZACION'] ?>')">-->
                        <a href="javascript:" title="Subir Documentos" class="fa fa-archive" onclick="subir_acta_recurso_archivo('<?php echo $consulta[$i]['COD_RESOLUCION'] ?>', '<?php echo $consulta[$i]['NUMERO_CITACION'] ?>')"></a>
                        <a href="javascript:" title="Ver pdf" class="fa fa-eye" onclick="pdf('<?php echo $consulta[$i]['COD_RESOLUCION']; ?>')"></a>
                        <?php
                        break;
                    case 313:
                    case 560:
                    case 561:
                        ?>
                        <!--<input type="radio" name="resolucion" class="resolucion" onclick="confirmar('<?php echo $consulta[$i]['COD_RESOLUCION'] ?>', '<?php echo $consulta[$i]['NITEMPRESA'] ?>', '<?php echo $consulta[$i]['NITEMPRESA'] ?>', '<?php echo $consulta[$i]['COD_CPTO_FISCALIZACION'] ?>', '<?php echo $consulta[$i]['COD_FISCALIZACION'] ?>')">-->
                        <a href="javascript:" title="Subir Documento" class="fa fa-archive" onclick="subir_citacion('<?php echo $consulta[$i]['COD_RESOLUCION'] ?>', '<?php echo $consulta[$i]['NUMERO_CITACION'] ?>','<?php echo $consulta[$i]['COD_FISCALIZACION'] ?>','<?php echo $consulta[$i]['NITEMPRESA'] ?>')"></a>
                        <a href="javascript:" title="Ver pdf" class="fa fa-eye" onclick="pdf('<?php echo $consulta[$i]['COD_RESOLUCION']; ?>')"></a>
                        <?php
                        break;
                    case 314:
                        ?>
                        <!--<input type="radio" name="resolucion" class="resolucion" onclick="confirmar('<?php echo $consulta[$i]['COD_RESOLUCION'] ?>', '<?php echo $consulta[$i]['NITEMPRESA'] ?>', '<?php echo $consulta[$i]['NITEMPRESA'] ?>', '<?php echo $consulta[$i]['COD_CPTO_FISCALIZACION'] ?>', '<?php echo $consulta[$i]['COD_FISCALIZACION'] ?>')">-->
                        <a href="javascript:" title="Aprobar resolución" class="fa icon-ok" onclick="gestion_resolucion_aprobada('<?php echo $consulta[$i]['COD_RESOLUCION'] ?>', '<?php echo $consulta[$i]['NUMERO_CITACION'] ?>','<?php echo $consulta[$i]['COD_CPTO_FISCALIZACION'] ?>','<?php echo $consulta[$i]['NITEMPRESA'] ?>')"></a>
                        <a href="javascript:" title="Ver pdf" class="fa fa-eye" onclick="pdf('<?php echo $consulta[$i]['COD_RESOLUCION']; ?>')"></a>
                        <?php
                        break;
                    case 315:
                        ?>
                        <!--<input type="radio" name="resolucion" class="resolucion" onclick="confirmar('<?php echo $consulta[$i]['COD_RESOLUCION'] ?>', '<?php echo $consulta[$i]['NITEMPRESA'] ?>', '<?php echo $consulta[$i]['NITEMPRESA'] ?>', '<?php echo $consulta[$i]['COD_CPTO_FISCALIZACION'] ?>', '<?php echo $consulta[$i]['COD_FISCALIZACION'] ?>')">-->
                        <a href="javascript:" title="Subir Documentos" class="fa icon-ok" onclick="gestion_resolucion_aprobada2('<?php echo $consulta[$i]['COD_RESOLUCION'] ?>', '<?php echo $consulta[$i]['NUMERO_CITACION'] ?>','<?php echo $consulta[$i]['COD_CPTO_FISCALIZACION'] ?>','<?php echo $consulta[$i]['NITEMPRESA'] ?>')"></a>
                        <a href="javascript:" title="Ver pdf" class="fa fa-eye" onclick="pdf('<?php echo $consulta[$i]['COD_RESOLUCION']; ?>')"></a>
                        <?php
                        break;
                    case 49:
                        ?>
                        <!--<input type="radio" name="resolucion" class="resolucion" onclick="confirmar('<?php echo $consulta[$i]['COD_RESOLUCION'] ?>', '<?php echo $consulta[$i]['NITEMPRESA'] ?>', '<?php echo $consulta[$i]['NITEMPRESA'] ?>', '<?php echo $consulta[$i]['COD_CPTO_FISCALIZACION'] ?>', '<?php echo $consulta[$i]['COD_FISCALIZACION'] ?>')">-->
                        <a href="javascript:" title="Favorabilidad" class="fa fa-male" onclick="favorabilidad('<?php echo $consulta[$i]['COD_RESOLUCION'] ?>', '<?php echo $consulta[$i]['NUMERO_CITACION'] ?>')"></a>
                        <a href="javascript:" title="Ver pdf" class="fa fa-eye" onclick="pdf('<?php echo $consulta[$i]['COD_RESOLUCION']; ?>')"></a>
                        <?php
                        break;
                    case 54:
                        ?>
                        <!--<input type="radio" name="resolucion" class="resolucion" onclick="confirmar('<?php echo $consulta[$i]['COD_RESOLUCION'] ?>', '<?php echo $consulta[$i]['NITEMPRESA'] ?>', '<?php echo $consulta[$i]['NITEMPRESA'] ?>', '<?php echo $consulta[$i]['COD_CPTO_FISCALIZACION'] ?>', '<?php echo $consulta[$i]['COD_FISCALIZACION'] ?>')">-->
                        <a href="javascript:" title="GENERAR AUTO DE TERMINACION" class="fa fa-file-text-o" onclick="terminacion('<?php echo $consulta[$i]['COD_RESOLUCION'] ?>', '<?php echo $consulta[$i]['COD_ESTADO'] ?>')"></a>
                        <a href="javascript:" title="Ver pdf" class="fa fa-eye" onclick="pdf('<?php echo $consulta[$i]['COD_RESOLUCION']; ?>')"></a>
                        <?php
                        break;
                    case 84:
                        $datos = Resolucion::citacion_pendientes($consulta[$i]['COD_RESOLUCION']);
                        ?>
                        <!--<input type="radio" name="resolucion" class="resolucion" onclick="confirmar('<?php echo $consulta[$i]['COD_RESOLUCION'] ?>', '<?php echo $consulta[$i]['NITEMPRESA'] ?>', '<?php echo $consulta[$i]['NITEMPRESA'] ?>', '<?php echo $consulta[$i]['COD_CPTO_FISCALIZACION'] ?>', '<?php echo $consulta[$i]['COD_FISCALIZACION'] ?>')">-->
                        <a href="javascript:" title="SE PRESENTO ?" class="fa fa-question" onclick="llego('<?php echo $consulta[$i]['COD_RESOLUCION'] ?>',
                                        '<?php echo $datos[0]['TIPOGESTION'] ?>', '<?php echo $datos[0]['FECHA_ENVIO_CITACION'] ?>',
                                        '<?php echo $datos[0]['NUMERO_CITACION'] ?>', '<?php echo $consulta[$i]['COD_FISCALIZACION'] ?>',
                                        '<?php echo $consulta[$i]['NITEMPRESA'] ?>')"></a>
                        <a href="javascript:" title="Ver pdf" class="fa fa-eye" onclick="pdf('<?php echo $consulta[$i]['COD_RESOLUCION']; ?>')"></a>
                        <?php
                        break;
                    case 37:
                        ?>
                        <!--<input type="radio" name="resolucion" class="resolucion" onclick="confirmar('<?php echo $consulta[$i]['COD_RESOLUCION'] ?>', '<?php echo $consulta[$i]['NITEMPRESA'] ?>', '<?php echo $consulta[$i]['NITEMPRESA'] ?>', '<?php echo $consulta[$i]['COD_CPTO_FISCALIZACION'] ?>', '<?php echo $consulta[$i]['COD_FISCALIZACION'] ?>')">-->
                        <a href="javascript:" title="Realizar Acta" class="fa fa-pencil-square-o" onclick="realizar_acta('<?php echo $consulta[$i]['COD_RESOLUCION'] ?>',
                                        '<?php echo $consulta[$i]['NUMERO_CITACION'] ?>', '<?php echo $consulta[$i]['COD_FISCALIZACION'] ?>',
                                        '<?php echo $consulta[$i]['NITEMPRESA'] ?>')"></a>
                        <a href="javascript:" title="Ver pdf" class="fa fa-eye" onclick="pdf('<?php echo $consulta[$i]['COD_RESOLUCION']; ?>')"></a>
                        <?php
                        break;
                    default :
                }
                ?>
            </td>
        </tr>
        <?php
    }
    ?>
</tbody>
</table>
<div id="resultado"></div>
<div id="formulario1"></div>
<p><br></p>
<div id="cargar_pdf"></div>

<form id="form12" action="<?php echo base_url('index.php/notificacionacta/manage'); ?>" method="POST">
    <input type="hidden" name="cod_fiscalizacion" value="" id="cod_fiscalizacion">
    <input type="hidden" name="id_resolucion" value="" id="id_resolucion">
</form>

<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<style>
    .ui-widget-overlay{z-index: 10000;}
    .ui-dialog{
        z-index: 15000;
    }
</style>
<script>
    function llego(id, gestion, fecha, num_citacion, cod_fis, nit) {
        $(".preload, .load").show();
        var ruta = "<?php echo base_url("index.php/tiempos/bloqueo"); ?>";
        $.post(ruta, {gestion: gestion, fecha: fecha,codfiscalizacion: cod_fis})
                .done(function(msg) {
//                    var datos = eval(msg);
//                    alert(msg.vencido);
                    var texto = msg.texto;
                    var comienza = msg.comienza;
                    var vence = msg.vence;
                    if (msg.bandera == "0") {
                        vence = "Recordatorio Pendiente";
                        comienza = "Recordatorio Pendiente";
                        texto = "Recordatorio Pendiente";
                    }
                    if ($.trim(msg.vencido) == "1") {
                        var url = "<?php echo base_url("index.php/resolucion/bloqueo"); ?>";
                        $('#resultado').load(url, {id: id, cod_fis: cod_fis, nit: nit, gestion: gestion, fecha: fecha, num_citacion: num_citacion, texto: texto, comienza: comienza, vence: vence});
                    } else {
                        var url = "<?php echo base_url('index.php/resolucion/bloqueo_por_time'); ?>";
                        $('#resultado').load(url, {id: id, cod_fis: cod_fis, nit: nit, gestion: gestion, fecha: fecha, num_citacion: num_citacion, texto: texto, comienza: comienza, vence: vence});
                    }
                }).fail(function(msg) {
            alert("ERROR");
        })
    }
    function citacion_resep(id, cod_fis, nit) {
        $(".preload, .load").show();
        var url = "<?php echo base_url('index.php/resolucion/desicion') ?>";
        $('#formulario1').load(url, {id: id, cod_fis: cod_fis, nit: nit});
    }
    function terminacion(id) {
        $(".preload, .load").show();
        var url = "<?php echo base_url('index.php/resolucion/terminacion') ?>";
        $('#resultado').load(url, {id: id});
    }
    function realizar_acta(id, num_citacion, cod_fis, nit) {
        $(".preload, .load").show();
        var url = "<?php echo base_url('index.php/resolucion/realizar_acta') ?>";
        $('#formulario1').load(url, {id: id, cod_fis: cod_fis, nit: nit});
    }
    function pdf(id) {
        $(".preload, .load").show();
        var url = "<?php echo base_url('index.php/resolucion/ver_documentos') ?>";
        $('#resultado').load(url, {id: id});
    }
    function modificar(id, cod_fis, nit) {
        $(".preload, .load").show();
        var url = "<?php echo base_url('index.php/resolucion/modificar') ?>";
        $('#resultado').load(url, {id: id, cod_fis: cod_fis, nit: nit});
    }
    function resolver_recurso_gestionar(id,cod_fis,nit) {
        $(".preload, .load").show();
        var url = "<?php echo base_url('index.php/resolucion/resolver_recurso_gestionar') ?>";
//        var url = "<?php // echo base_url('index.php/Zautocargos/bloqueo')                      ?>";
        $('#resultado').load(url, {id: id,cod_fis:cod_fis,nit:nit});
    }

    $('#styletable').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
         "oLanguage": {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":    "",
            "sSearch":         "Buscar:",
            "sUrl":            "",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
            "sFirst":    "Primero",
            "sLast":     "Último",
            "sNext":     "Siguiente",
            "sPrevious": "Anterior"
             },
    "fnInfoCallback": null,
            },
    });
    function confirmar(id, nit, reviso, concepto, cod_fis) {
        $(".preload, .load").show();
        var url = "<?php echo base_url('index.php/resolucion/resolucion2') ?>";
        $('#resultado').load(url, {id: id, nit: nit, reviso: reviso, concepto: concepto, cod_fis: cod_fis});
    }
    function resolver_recurso(id, cod_fis, nit,nro_recurso) {
        $(".preload, .load").show();
        var url = "<?php echo base_url('index.php/resolucion/resolver_recurso') ?>";
        $('#resultado').load(url, {id: id, cod_fis: cod_fis, nit: nit,nro_recurso:nro_recurso});
    }
    function cargar(id, cod_fis, nit) {
        $(".preload, .load").show();
        var url = "<?php echo base_url('index.php/resolucion/citacion') ?>";
        $('#resultado').load(url, {id: id, cod_fis: cod_fis, nit: nit});
    }
    function subir_acta(id, num_citacion, cod_fis, nit) {
        $(".preload, .load").show();
        var url = "<?php echo base_url('index.php/resolucion/subir_acta') ?>";
        $('#resultado').load(url, {id: id, num_citacion: num_citacion, cod_fis: cod_fis, nit: nit});
    }
    function subir_acta_recurso_archivo(id, num_citacion) {
        $(".preload, .load").show();
        var url = "<?php echo base_url('index.php/resolucion/subir_acta_recurso') ?>";
        $('#resultado').load(url, {id: id, num_citacion: num_citacion});
    }
    function subir_citacion(id, num_citacion,cod_fis, nit) {
        $(".preload, .load").show();
        var url = "<?php echo base_url('index.php/resolucion/subir_citacion') ?>";
        $('#resultado').load(url, {id: id, num_citacion: num_citacion,cod_fis: cod_fis, nit: nit});
    }
    function gestion_resolucion_aprobada(id, num_citacion,cod_fis, nit) {
        $(".preload, .load").show();
        var url = "<?php echo base_url('index.php/resolucion/gestion_resolucion_aprobada') ?>";
        $('#resultado').load(url, {id: id, num_citacion: num_citacion,cod_fis: cod_fis, nit: nit});
    }
    function gestion_resolucion_aprobada2(id, num_citacion,cod_fis, nit) {
        $(".preload, .load").show();
        var url = "<?php echo base_url('index.php/resolucion/resolver_recurso_gestionar2') ?>";
        $('#resultado').load(url, {id: id, num_citacion: num_citacion,cod_fis: cod_fis, nit: nit});
    }
    function favorabilidad(id, num_citacion) {
        $(".preload, .load").show();
        var url = "<?php echo base_url('index.php/resolucion/favorabilidad') ?>";
        $('#resultado').load(url, {id: id, num_citacion: num_citacion});
    }
    $(".preload, .load").hide();
    function cargar2(id, cod_fis, nit) {
        $(".preload, .load").show();
        var url = "<?php echo base_url('index.php/resolucion/subir_archivo') ?>";
        $('#resultado').load(url, {id: id, cod_fis: cod_fis, nit: nit});
    }
    function generar_resolucion(cod_fis, id_resolucion) {
        $('#cod_fiscalizacion').val(cod_fis);
        $('#id_resolucion').val(id_resolucion);
        $('#form12').submit();
    }
</script>
<style>
    div.preload{
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        background-color: white;
        opacity: 0.8;
        z-index: 10000;
    }

    div img.load{
        position: absolute;
        left: 50%;
        top: 50%;
        margin-left: -64px;
        margin-top: -64px;
        z-index: 15000;
    }
</style>