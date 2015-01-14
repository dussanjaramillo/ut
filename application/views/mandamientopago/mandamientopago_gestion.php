       
        <form id="frmTmp" method="POST">
        <h2>Estado Mandamiento de pago</h2>        
            <div id='dialog'>
                <center>
                    <p>El Deudor <?php echo $nombre ?> se Presentó?</p>
                </center>                
            </div>       
                <input type='text' id='gestion' name='gestion' value='<?= $gestion ?>'>
                <input type='text' id='nit' name='nit' value='<?= $nit ?>'>
                <input type='text' id='cod_coactivo' name='cod_coactivo' value='<?= $cod_coactivo ?>'>
                <input type='text' id='razon' name='razon' value='<?= $nombre ?>'>
                <input type='text' id='clave' name='clave' value='<?= $mandamiento ?>'>
                <input type='text' id='url1' name='url1' value='<?= $url1 ?>'>
                <input type='text' id='url2' name='url2' value='<?= $url2 ?>'> 
                <input type='text' value='<?php echo $user->IDUSUARIO ?>' name='iduser' id='iduser'>
        </form>
        <div id="ajax_load" class="ajax_load" style="display: none">
            <div class="preload" id="preload" >
                <img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
            </div>
        </div>    

<script>    
    $(document).ready(function() { 
        var gestion = $("#gestion").val();
        var nit = $("#nit").val();
        var mandamiento = $("#clave").val();
        var cod_coactivo = $("#cod_coactivo").val();
        var url1 = $("#url1").val();
        var url2 = $("#url2").val();
//        var iduser = $("#iduser").val();
//        var ruta = "<?php echo base_url("index.php/tiempos/bloqueo"); ?>";
        //alert (gestion+"*****"+nit+"*****"+mandamiento+"*****"+cod_fiscalizacion+"*****"+url1+"*****"+url2);
        $("#dialog").dialog({
                buttons: [{
                    id: "si",
                    text: "Si",
                    click: function() {
                        $(".ajax_load").show("slow");
                        $.post(url2,{gestion : gestion, clave : mandamiento, nit: nit, cod_coactivo:cod_coactivo},function(data){                           
                           $("#frmTmp").attr("action", url2); 
                           $('#frmTmp').submit();
                        });
                    },
                    class: "btn btn-success"
                },
                {
                id: "no",
                text: "No",
                click: function() {
                    $(".ajax_load").show("slow");
                    $.post(url2,{gestion : gestion, clave : mandamiento, nit: nit, cod_coactivo:cod_coactivo},function(data){
                        $("#frmTmp").attr("action", url1); 
                        $('#frmTmp').submit();
                    });
                },
                class: "btn btn-success"
                }
            ]
        });                 
    });    
</script> 