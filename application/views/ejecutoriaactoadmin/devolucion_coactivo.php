<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<h1>
    Datos Devueltos en Coactivo
</h1>
<table id='table' >
    <thead>
    <th>IDENTIFICACION</th>
    <th>RESOLUCION</th>
    <th>NOMBRE EJECUTADO</th>
    <th>ESTADO</th>
    <th>ACCI&Oacute;N</th>
</thead>
<?php
//    print_r($datos);
foreach ($datos as $value) {
    echo "<tr><td>" . $value['NITEMPRESA'] . "</td>";
    echo "<td>" . $value['NUMERO_RESOLUCION'] . "</td>";
    echo "<td>" . $value['NOMBRE_EMPRESA'] . "</td>";
    echo "<td>" . $value['NOMBRE_GESTION'] . "</td>";
    echo "<td><button class='btn btn-success' onclick='enviar(\"" . $value['COD_RECEPCIONTITULO'] . "\")'>Responder</button> </td></tr>";
}
?>
</table>
<div id="resultado"></div>
<script>
    $('#table').dataTable({
        "bJQueryUI": true,
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

    function enviar(id) {
        $(".preload, .load").show();
        var url = "<?php echo base_url('index.php/ejecutoriaactoadmin/observaciones') ?>";
        $('#resultado').load(url, {id: id});
    }
    
    $(".preload, .load").hide();
</script>
<style>
    .ui-widget-overlay{z-index: 10000;}
    .ui-dialog{
        z-index: 15000;
    }

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
