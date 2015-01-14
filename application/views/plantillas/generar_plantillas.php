<table id="styletable">
    <thead>
    <th>Cod plantilla</th>
    <th>Nombre Plantilla</th>
    <th>Archivo Plantilla</th>
    <th>Gestionar</th>
</thead>
<?php for ($i = 0; $i < count($informacion); $i++) {
    ?>
    <tr>
        <td><?php echo $informacion[$i]['CODPLANTILLA'] ?></td>
        <td><?php echo $informacion[$i]['NOMBRE_PLANTILLA'] ?></td>
        <td><?php echo $informacion[$i]['ARCHIVO_PLANTILLA'] ?></td>
        <td align="center">
            <a href="<?php echo base_url() ?>index.php/plantillas/editar_plantilla?id=<?php echo $informacion[$i]['CODPLANTILLA'] ?>" class="fa fa-pencil-square-o" title="Nuevo" data-toggle="modal" data-target="#modal" data-keyboard="false" data-backdrop="static"></a>
        </td>
    </tr>
<?php }
?>
</table>
<div class="modal hide fade in" id="modal" style="display: none; width: 60%; margin-left: -30%;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div align="center">
                    <img src="<?= base_url() ?>/img/27.gif" width="150px" height="150px" />  
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-default" data-dismiss="modal" id="cancelar">Volver</a>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<hr>
<center>
    <a href="<?= base_url() ?>index.php/plantillas/nuevaplantilla" class="btn btn-success " title="Nuevo" data-toggle="modal" data-target="#modal" data-keyboard="false" data-backdrop="static">Nuevo</a>
</center>
    <script>
    $('#styletable').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
    });
    $('#nuevo').click(function() {
        var url = '<?php echo base_url("index.php/plantillas/nuevaplantilla") ?>';
        $('#resultado').load(url);
    })
    $('#cancelar').on('click', function() {
        $('.modal-body').html('<div align="center"><img src="<?= base_url() ?>/img/27.gif" width="150px" height="150px" /></div>');
        $('#modal').modal('hide').removeData();
    });
    $('.close').on('click', function() {
        $('.modal-body').html('<div align="center"><img src="<?= base_url() ?>/img/27.gif" width="150px" height="150px" /></div>');
        $('#modal').modal('hide').removeData();
    });
</script>
<style>
    .ui-widget-overlay{z-index: 10000;}
    .ui-dialog{
        z-index: 15000;
    }
</style>