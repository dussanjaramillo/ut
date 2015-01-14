<form id="form1" action="<?= base_url('index.php/mcremate/Registrar_Posturas') ?>" method="post" >
    <input type="hidden" id="cod_avaluo" name="cod_avaluo" value="<?php echo $cod_avaluo; ?>">   
    <input type="hidden" id="cod_coactivo" name="cod_coactivo" value="<?php echo $cod_coactivo; ?>">   
    <input type="hidden" id="cod_respuesta" name="cod_respuesta">  
</form>
<br><br>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<div class="info" id="info">
    <?php require_once('encabezado.php');
    ?>
</div>
<br>
<div  style="width: 90%; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 15px">
    <div style="color: #FC7323"><center><label for="cuenta_contable"><h2><b>Registrar Posturas:</b></h2></label></center></div>
    <br>
    <?php
    $attributes = array("id" => "myform", "class" => "myform");
    echo form_open("mcremate/Registrar_Posturas", $attributes);
    echo form_hidden('cod_avaluo', $cod_avaluo);
    echo form_hidden('cod_coactivo', $cod_coactivo);
    ?>
    <span class="span2"><label for="cuenta_contable">Nombre o Pseudónimo:</label></span>
    <span class="span3"><?php
        $data = array(
            'name' => 'nombre_postura',
            'id' => 'nombre_postura',
            'class' => 'validate[required]',
            'required => required'
        );
        echo form_input($data);
        ?></span> 
    <span class="span2"><label for="cuenta_contable">Número de Identificación:</label></span>
    <span class="span3"><?php
        $data = array(
            'name' => 'identificacion_postura',
            'id' => 'identificacion_postura',
        );
        echo form_input($data);
        ?></span> 
    <?php
//    <span class="span2"><label for="cuenta_contable">Fecha:</label></span>
//    <span class="span3"><?php
//        $data = array(
//            'name' => 'fecha_postura',
//            'id' => 'fecha_postura',
//            'required => required'
//        );
//        echo form_input($data);
//        </span> 
//    
//    <span class="span2"><label for="cuenta_contable">T�tulo del Dep�sito:</label></span>
//    <span class="span3"><?php
//        $data = array(
//            'name' => 'titulo_deposito',
//            'id' => 'titulo_deposito',
//            'class' => 'validate[required]',
//            'required => required'
//        );
//        echo form_input($data);
//        </span>
//    <span class="span2"><label for="cuenta_contable">Valor del Titulo:</label></span>
//    <span class="span3">//<?php
//        $data = array(
//            'name' => 'valor_titulo',
//            'id' => 'valor_titulo',
//            'class' => 'validate[required, custom[onlyNumber], maxSize[12]]',
//            'required' => 'required'
//        );
//        echo form_input($data);
//        </span>
//    <span class="span2"><label for="cuenta_contable">Valor Postura:</label></span>
//    <span class="span3">//<?php
//        $data = array(
//            'name' => 'valor_postura',
//            'id' => 'valor_postura',
//            'class' => 'validate[required]',
//            'required => required'
//        );
//        echo form_input($data);
//        </span>
    ?>
    <br>
    <span class="span7"><label for="cuenta_contable">Comentarios:</label></span>
    <span class="span6">
        <?php
        $datacomentarios = array(
            'name' => 'comentarios',
            'id' => 'comentarios',
            'maxlength' => '300',
            'class' => 'span10 comentarios validate[required]',
            'rows' => '3',
            'required' => 'required'
        );
        echo form_textarea($datacomentarios);
        ?></span><br>
    <center> 
        <?php
//        <span class="span10"><label for="cuenta_contable"><b>Soporte de Postura:</b></label></span>
//        <span class="span10" style="alignment-adjust: central">
//
//            <?php
//            $data = array(
//                'multiple' => '',
//                'name' => 'userfile[]',
//                'id' => 'imagen',
//                'class' => 'validate[required]'
//            );
//            echo form_upload($data);
//            
//        </span></center><br><br>
        ?>
        <?php
        echo form_close();
        ?>
</div>
<center>
    <br>
    <?php
    $data_1 = array(
        'name' => 'button',
        'id' => 'enviar',
        'value' => 'seleccionar',
        'content' => '<i class="fa fa-paperclip"></i> Registrar Postura',
        'class' => 'btn  btn-success enviar'
    );
    $data_2 = array(
        'name' => 'button',
        'id' => 'ir',
        'value' => 'seleccionar',
        'content' => '<i class="fa fa-repeat"></i>Continuar con el Proceso',
        'class' => 'btn btn-success ir'
    );
    $data_5 = array(
        'name' => 'button',
        'id' => 'info_boton',
        'value' => 'info',
        'content' => '<i class="fa fa-eye"></i>Información del Avaluo',
        'class' => 'btn btn-info'
    );
    ?>

    <div class="Boton_Agregar">
        <?php
        echo form_button($data_5) . "  " . form_button($data_1) . "  " . form_button($data_2) . " ";
        echo anchor('bandejaunificada', '<i class="fa fa-minus-circle"></i> Atras', 'class="btn  btn-warning enviar"');
        ?>
    </div>
    <input type="hidden" id="Cabeza" value='<?php echo $Cabeza ?>'/>

    <br>
</center>
<br>
<div id="consulta" ></div>
<script>
    $(".info").hide();
    $(".Boton_PDF").hide();
    $(".preload, .load").hide();
    $('#info_boton').click(function() {
        $(".info").show();
    });
    $("#cargar").click(function() {
        caja = document.createElement("input");
        caja.setAttribute("multiple", "");
        caja.setAttribute("type", "file");
        caja.setAttribute("id", "imagen");
        caja.setAttribute("name", "userfile[]");
        document.getElementById("carga_archivo").appendChild(caja);
    });


    $("#fecha_postura").datepicker({
        dateFormat: "yy/mm/dd",
        changeMonth: true,
        maxDate: "0",
        changeYear: true,
    });

    $("#ir").click(function() {
        $(".preload, .load").show();
        $('#cod_respuesta').val('511');
        $("#form1").submit();
        //$(".preload, .load").hide();
    });
    $("#enviar").click(function() {
        $(".preload, .load").show();
        var Nombre = $("#nombre_postura").val();
        var Identificacion = $("#identificacion_postura").val();
        var Fecha = $("#fecha_postura").val();
        var Titulo_Deposito = $("#titulo_deposito").val();
        var Valor_Titulo = $("#valor_titulo").val();
        var Valor_Postura = $("#valor_postura").val();
        if (Nombre == '') {
            alert('Debe Diligenciar Como Mínimo El Nombre O Pseudónimo Del Postor');
            $(".preload, .load").hide();
        } else {
            var formData = new FormData($(".myform")[0]);
            $.ajax({
                url: '<?php echo base_url('index.php/mcremate/Registrar_Posturas') ?>',
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                //una vez finalizado correctamente
                success: function(data) {
                    alert("Se ha Registrado Exitosamente la Postura, Desea Incluir Otra?");
                    $('#nombre_postura').val('');
                    $('#identificacion_postura').val('');
                    $('#fecha_postura').val('');
                    $('#titulo_deposito').val('');
                    $('#valor_titulo').val('');
                    $('#valor_postura').val('');
                    $('#comentarios').val('');
                    $('#imagen').val('');
                    $(".preload, .load").hide();
                },
                //si ha ocurrido un error
                error: function() {
                    alert("Ha Ocurrido un Error");
                    $(".preload, .load").hide();
                }
            });
        }
    });
    $(".Boton_PDF").hide();
    $("#preloadmini").hide();
</script> 


