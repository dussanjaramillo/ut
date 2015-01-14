<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<table id="mueble" width="100%" style="background: #FC7323">
    <thead>
    <th class="mas">
        <a href="javascript:" class="fa fa-plus-square" ></a>
    </th>
    <th class="mas">
    <div><?php echo $post['detalle']; ?></div>
</th>
<th><button class="btn btn-success" id="guardar" >Guardar</button></th>
</thead>
<!--vehiculo -> Nro de matricula
inmuebles -> Nro de Matricula Inmobiliaria-->
<tbody>
</tbody>
</table>
<form id="form">
    <input type="hidden" id="dato" name="dato" value="<?php echo $post['dato']; ?>">
    <input type="hidden" id="cod_fis" name="cod_fis" value="<?php echo $post['cod_fis']; ?>">
    <input type="hidden" id="id" name="id" value="<?php echo $post['id']; ?>">
    <input type="hidden" id="nit" name="nit" value="<?php echo $post['nit']; ?>">
    <table width="100%" border="0" id="tablee">
        <thead>
        <th><?php
            //echo $post['detalle'];
            if ($post['detalle'] == "Muebles")
                echo "Muebles";
            else if ($post['detalle'] == "Inmuebles")
                echo "Nro de matricula Inmobiliaria";
            else if ($post['detalle'] == "Vehiculo")
                echo "Nro de Matricula ";
            ?></th>
        <th><div style="display: none">Valor</div></th>
        <th>Observaciones</th>
        <th>
        <?php if ($post['dato'] != "8") { ?><div id="oculto" style="display: none"><?php
        } else {
            echo "<div>";
        }
        ?>Prioridad</div>
        </th>
        <th>
        <?php if ($post['dato'] != "8") { ?><div id="oculto" style="display: none"><?php
        } else {
            echo "<div>";
        }
        ?>Banco</div>
        </th>
        <th>Acci&oacute;n</th>
        </thead>   
        <tbody id="clonar">
            <tr id="trr" align="center">
                <td><input type="text" class="infor_table" name="mueble[]" maxlength="32" style="width: 100px;"></td>
                <td><div style="display: none"><input type="text" class="num infor_table" name="valor[]" maxlength="10" style="width: 70px;"></div></td>
                <td><input type="text" class="infor_table" name="observacion[]"></td>
                <td>
                    <?php if ($post['dato'] != "8") { ?><div id="oculto" style="display: none"><?php
                    } else {
                        echo "<div>";
                    }
                    ?>
                        <select name="id_prioridad[]" style="width: 100px;">
                            <option value=""></option>
                            <?php foreach ($tipo_prioridad as $prioridad) { ?>
                                <option value="<?php echo $prioridad['COD_PRIORIDAD'] ?>"><?php echo $prioridad['NOMBRE_PRIORIDAD'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </td>
                <td>
                    <?php if ($post['dato'] != "8") { ?><div id="oculto" style="display: none"><?php
                    } else {
                        echo "<div>";
                    }
                    ?>
                        <select name="id_banco[]" style="width: 100px;">
                            <option value=""></option>
                            <?php foreach ($info_bancos as $banco) { ?>
                                <option value="<?php echo $banco['IDBANCO'] ?>"><?php echo $banco['NOMBREBANCO'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </td>
                <td></td>
            </tr>
            <?php echo $prioridad2;?>
        </tbody>
    </table>
</form>
<script>
    jQuery(".preload, .load").hide();
    $('#resul').dialog({
        autoOpen: true,
        width: 800,
        height: 300,
        modal: true,
        title: "<?php echo $post['titulo']; ?>",
        close: function() {
            $('#resul *').remove();
        }
    });

    $('#guardar').click(function() {
        var i = 0;
        $('.infor_table').each(function(indice, campo) {
            if ($(this).val() == "" && $(this).attr('name') != 'valor[]')
            {
                $(this).css('border-color', 'blue');
                i++
            }
            else {
                $(this).removeAttr('style');
//                if ($(this).attr('name') == 'valor[]') {
//                    $(this).css({
//                        'border': '',
//                        "width": '70px'
//                    });
//                }
                if ($(this).attr('name') == 'mueble[]' || $(this).attr('name') == 'id_prioridad[]' || $(this).attr('name') == 'id_banco[]') {
                    $(this).css({
                        'border': '',
                        "width": '100px'
                    });
                }
            }

        });
        if (i > 0) {
            alert('Datos Imcompletos');
            return false;
        }
        jQuery(".preload, .load").show();
        var url = "<?php echo base_url('index.php/mcinvestigacion/accion_de') ?>";
        $.post(url, $('#form').serialize())
                .done(function() {
                    window.location.reload();
                }).fail(function() {
            jQuery(".preload, .load").hide();
            alert("<?php echo ERROR; ?>");
        })
    });



    $('.mas').click(function() {
    var trs = $("#mueble tr").length;
//    console.log($('#clonar tr td input').val());
//        console.log($('#trr').html());
//        var numItems = jQuery('.contar' + id).length;
//        $('#trr').clone(':button').appendTo('#clonar');
        $('#clonar').append("<tr align='center' id='tr"+trs+"'>"+
                '<td><input type="text" class="infor_table" name="mueble[]" style="width: 100px;"></td>'+
                '<td><div style="display: none"><input type="text" class="num infor_table" name="valor[]" maxlength="10" style="width: 70px;"></div></td>'+
                '<td><input type="text" class="infor_table" name="observacion[]"></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td><button type="button" id="del" onclick="elim('+trs+')" class="eliminar btn btn-primary">Eliminar</button></td></tr>');


//var texto='<tr id="trr" align="center"><td><input type="text" class="infor_table" name="mueble[]" style="width: 100px;"></td><td><input type="text" class="num infor_table" name="valor[]" maxlength="10" style="width: 70px;"></td>               <td><input type="text" class="infor_table" name="observacion[]"></td>                <td>                            <?php if ($post['dato'] == "1") { ?><div id="oculto" style="display: none"><?php
                            } else {
                                echo "<div>";
                            }
                            ?>                        <select name="id_prioridad[]" style="width: 100px;">                            <option value=""></option>                    <?php foreach ($tipo_prioridad as $prioridad) { ?>                                <option value="<?php echo $prioridad['COD_PRIORIDAD'] ?>"><?php echo $prioridad['NOMBRE_PRIORIDAD'] ?></option>                    <?php } ?>                        </select>                    </div>                </td>                <td>                            <?php if ($post['dato'] == "1") { ?><div id="oculto" style="display: none"><?php
                            } else {
                                echo "<div>";
                            }
                            ?>                        <select name="id_banco[]" style="width: 100px;">                            <option value=""></option><?php foreach ($info_bancos as $banco) { ?>                                <option value="<?php echo $banco['IDBANCO'] ?>"><?php echo $banco['NOMBREBANCO'] ?></option><?php } ?>                        </select>                    </div>                </td>                <td><button type="button" class="eliminar btn btn-primary">Eliminar</button></td>            </tr>';  
//$('#clonar').append(texto)
    });
    $('#eliminar').click(function() {
        console.log("!");
        var contador = $('#clonar tr').length;
        if (contador != 1)
        {
            $(this).parents('tr:last').remove();
        }
    });
    $('.num').keypress(function(e) {
        var keynum = window.event ? window.event.keyCode : e.which;
//        console.log(keynum);
        if ((keynum == 8) || (keynum == 0))
            return true;
        return /\d/.test(String.fromCharCode(keynum));

    });
    function elim(u){
        $("#tr"+u).remove();
    }
    function eliminar_col(dato){
        $("#trr"+dato).remove();
    }

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


