<table id="estilotabla">
    <thead>
        <td>No Identificación</td>
        <td>Ejecutoria</td>
        <td>No Liquidación</td>
        <td>Crear Acuerdo de pago</td>
    </thead>
    <tbody>
        <?php foreach($acuerdos as $totalacuerdos){ ?>
        <tr>
            <td><?= $totalacuerdos['CODEMPRESA'] ?></td>
            <td><?= $totalacuerdos['RAZON_SOCIAL'] ?></td>
            <td><?= $totalacuerdos['NUM_LIQUIDACION'] ?></td>
            <td ><input class="ingresoacuerdo" liquidacion="<?= $totalacuerdos['NUM_LIQUIDACION'] ?>" type="radio" name="crear" id="crear"></td>
        </tr>
        <?php }?>
    </tbody>
</table>
<div align="center">
    <button type="button" class="btn btn-success" id="ingresar">Ingresar</button>
</div>
<form method="post">
    <input type="hidden" id="liquidacion" name="liquidacion">
</form>
<script>
    
    $('.ingresoacuerdo').click(function(){
        
        var liquidacion = $(this).attr('liquidacion');
        
        
        
        $('#liquidacion').val(liquidacion);
        if(liquidacion != ""){
        $('form').attr('action',"<?= base_url('index.php/acuerdonomisional/acuerdo') ?>");
        $('form').submit();
         }
    });
    
    $('document').ready(function(){
    
    $('#estilotabla').dataTable({
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
            }
    });
    });
</script>   
    