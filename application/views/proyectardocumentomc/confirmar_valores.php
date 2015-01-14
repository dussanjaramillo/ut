<?php echo form_open(base_url("index.php")) ?>
<input type="hidden" id="cod_fis" name="cod_fis" value="<?php echo $post['cod_fis'] ?>">
<input type="hidden" id="COD_FISCALIZACION" name="COD_FISCALIZACION" value="<?php echo $post['cod_fis'] ?>">
<input type="hidden" id="COD_TIPO_AUTO" name="COD_TIPO_AUTO" value="1">
<input type="hidden" id="cod_siguiente" name="cod_siguiente" value="<?php echo $post['cod_siguiente'] ?>">
<input type="hidden" id="id" name="id" value="<?php echo $post['id'] ?>">
<input type="hidden" id="nit" name="nit" value="<?php echo $post['nit'] ?>">
<input type="hidden" id="titulo" name="titulo" value="<?php echo $post['titulo'] ?>">
    <table width="100%">
        <tr align="center">
            <?php if($post['fin']==0){ ?>
            <td>Fraccionamiento de titulo</td>
            <?php } ?>
            <td>Auto de terminacion</td>
        </tr>
        <tr align="center">
            <?php if($post['fin']==0){?>
            <td><input type="radio" class="accion" name="accion" value="<?php echo base_url("index.php/mcinvestigacion/redirigir"); ?>"></td>
            <?php } ?>
            <td><input type="radio" checked="true" class="accion" name="accion" value="<?php echo base_url("index.php/verfpagosprojuridicos/form"); ?>"></td>
        </tr>
        <tr>
            <td colspan="2" align="center"><button type="submit">Enviar</button></td>
        </tr>
    </table>
</form>


<script>
    jQuery(".preload, .load").hide();
    $('#resultado').dialog({
        autoOpen: true,
        width: 500,
        modal: true,
        title: "<?php echo $post['titulo']; ?>",
        close: function() {
            $('#resultado *').remove();
        }
    });
    
    $('.accion').click(function(){
        var url=$(this).val();
        $("form").attr("action", url);
    })
    
</script>