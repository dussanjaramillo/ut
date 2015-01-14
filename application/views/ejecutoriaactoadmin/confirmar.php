<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<table width="100%">
    <tr>
        <td>Facilidad de Pago</td>
        <td><input type="radio" value="Facilidad de Pago" class="accion"></td>
    </tr>
    <tr>
        <td>Cobro Coactivo</td>
        <td><input type="radio" value="Cobro Coactivo" class="accion"></td>
    </tr>
</table>

<script>
    $('#resultado').dialog({
        autoOpen: true,
        modal: true,
        width: 300,
        title: "Enviar"
    })

    $(".preload, .load").hide();

    id = "<?php echo $post['id']; ?>";
    cod_fis = "<?php echo $post['cod_fis']; ?>";
    nit = "<?php echo $post['nit']; ?>";

    $('.accion').click(function() {
        var datos = this.value;
        var r = confirm('Confirma que desea enviarlo a ' + datos);
        if (r == true) {
            if(datos=='Facilidad de Pago'){
               var gestion="80" 
            }else{
               var gestion="427"  
            }
            $(".preload, .load").show();
            var url = "<?php echo base_url('index.php/ejecutoriaactoadmin/envio') ?>";
            $.post(url, {nit:nit,cod_fis:cod_fis,id:id,gestion:gestion})
                    .done(function(){
                        alert('Los Datos Fueron Guardados con Exito');
                        window.location.reload();
//                        $(".preload, .load").hide();
                    }).fail(function(){
                        $(".preload, .load").hide();
                    });
        }
    });
</script>