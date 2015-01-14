<?php  foreach ($registros->result_array as $data) { ?>
<tr class="odd">
    <td class="item">
        <?= $data['COD_MULTAMINISTERIO'] ?>
    </td>
    <td class="item">
        <?= $data['NIT_EMPRESA'] ?>
    </td>
    <td class="item">
        <?= $data['NOMBRE_EMPRESA'] ?>
    </td>
    <td class="item">
        <?= $data['FECHA_CREACION'] ?>
    </td>
    <td class="item">
        <?= $data["VALOR"] ?>
    </td>
    <td class="item">
        <?php if($data["EXIGIBILIDAD_TITULO"]==1) echo 'Exigible'; else echo 'N/A'; ?>
    </td>
    <td class="center">
        <?php if($data["EXIGIBILIDAD_TITULO"]==1){?>
            <a href="#" class="btn btn-small" onclick="generarResolucion(<?= $data['COD_MULTAMINISTERIO'] ?>)"title="Liquidar"><i class="fa fa-pencil-square-o"></i> Liquidar</a>
        <?php }?>
        <a href="<?= base_url()?>index.php/multasministerio/detalle/<?= $data['COD_MULTAMINISTERIO'] ?>" class="btn btn-small" title="Ver" data-toggle="modal" data-target="#modal" data-keyboard="false" data-backdrop="static"><i class="fa fa-eye"></i> Ver</a>
    </td>
</tr>
<?php } ?>
<script type="text/javascript" language="javascript" charset="utf-8">

function generarLiquidacion(cod_multaministerio){
        var url    = "<?=base_url()?>index.php/liquidaciones/getFormMultasMinisterio";
        
        redirect_by_post(url, {
           cod_multaministerio: cod_multaministerio,
           }, false);
    }

    function redirect_by_post(purl, pparameters, in_new_tab) {
            pparameters = (typeof pparameters == 'undefined') ? {} : pparameters;
            in_new_tab = (typeof in_new_tab == 'undefined') ? true : in_new_tab;
            var form = document.createElement("form");
            $(form).attr("id", "reg-form").attr("name", "reg-form").attr("action", purl).attr("method", "post").attr("enctype", "multipart/form-data");
            if (in_new_tab) {
                $(form).attr("target", "_blank");
            }
            $.each(pparameters, function(key) {
                $(form).append('<textarea name="' + key + '" >'+this+'</textarea>');
            });
            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
            return false;
        }
</script>