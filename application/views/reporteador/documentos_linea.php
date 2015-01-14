<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<form id="form1">
    <table width="100%">
    <?php 
        if($post['chek']=="false"):
            echo "<tr><td>Fecha Inicial </td><td align='center'>".$post['fecha_ini']."</td></tr>";
            echo "<tr><td>Fecha Final  </td><td align='center'>".$post['fecha_fin']."</td></tr>";
        endif;
        ?><tr>
            <td>Tipo reporte</td>
            <td>
    <input type="hidden" name="fecha_ini" value="<?php echo ($post['chek']=="false")? $post['fecha_ini']:"" ?>">
    <input type="hidden" name="fecha_fin" value="<?php echo ($post['chek']=="false")? $post['fecha_fin']:"" ?>">
    <input type="hidden" name="chek" value="<?php echo $post['chek']; ?>">
    <select class="select2">
        <option value=""></option>
        <option value="2">Reporte Liquidacion</option>
        <option value="3">Reporte Resolucion</option>
        <option value="4">reporte Cartera presunta y Real</option>
        <option value="5">Reporte Acuerdos pago en persuasivo</option>
        <option value="6">Reporte de contratos FIC</option>
    </select>
    </td></tr>
    </table>
</form>
<script>
    jQuery(".preload, .load").hide();
    $('#resultado').dialog({
        autoOpen: true,
        width: 400,
        modal: true,
        title: "<?php echo $post['titulo']; ?>",
        close: function() {
            $('#resultado *').remove();
        }
    });
    $('.select2').change(function(){
        enviar(this.value);
    });
</script>