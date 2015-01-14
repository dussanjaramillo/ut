<br><br>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<br><br>
<?php
$attributes = array("id" => "myform", "class" => "myform");
echo form_open_multipart("mcremate/Gestion_FraccionarTitulos", $attributes);
$fecha_hoy = date("Y/m/d H:i:s");
echo form_hidden('cod_avaluo', $cod_avaluo);
echo form_hidden('cod_coactivo', $cod_coactivo);
echo form_hidden('respuesta', $respuesta);
$usuario = $this->ion_auth->user()->row();
$abogado = $usuario->IDUSUARIO;
?>
<div class="informacion" id="informacion">
    <?php require_once('encabezado.php'); ?>
</div>
<br> 

<br>
<div  style="width: 90%; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 15px">
    <center><label  for="cuenta_contable"><h2><b>Solicitar Fraccionamiento de Titulos</b></h2></label></center>
    <br>
    <span class="span2"><label style="color:#FC7323" for="cuenta_contable"><b><?php echo $Entidad; ?></b></label></span>
    <span class="span3"><label for="cuenta_contable">-</label></span>
    <span class="span2"><label style="color:#FC7323" for="cuenta_contable"><b><?php echo $Persona; ?></b></label></span>
    <span class="span3"><label for="cuenta_contable">-</label></span>
    <span class="span2"><label for="cuenta_contable">Monto:</label></span>
    <span class="span3"><?php
        $data = array(
            'name' => 'monto_sena',
            'id' => 'monto_sena',
            'class' => 'validate[required]',
            'required => required'
        );
        echo form_input($data);
        ?></span> 
    <span class="span2"><label for="cuenta_contable">Monto:</label></span>
    <span class="span3"><?php
        $data = array(
            'name' => 'monto_deudor',
            'id' => 'monto_deudor',
            'class' => 'validate[required]',
            'required => required'
        );
        echo form_input($data);
        ?></span> 

    <span class="span2"><label for="cuenta_contable">Nro Titulo:</label></span>
    <span class="span3"><?php
        $data = array(
            'name' => 'titulo_sena',
            'id' => 'titulo_sena',
            'class' => 'validate[required]',
            'required => required'
        );
        echo form_input($data);
        ?></span> 
    <span class="span2"><label for="cuenta_contable">Nro Titulo:</label></span>
    <span class="span3"><?php
        $data = array(
            'name' => 'titulo_deudor',
            'id' => 'titulo_deudor',
            'class' => 'validate[required]',
            'required => required'
        );
        echo form_input($data);
        ?></span><br><br>


</div>
<center>
    <br>
    <?php
    $data_3 = array(
        'name' => 'button',
        'id' => 'info',
        'value' => 'seleccionar',
        'content' => '<i class="fa fa-eye"></i> Informacion del Avaluo',
        'class' => 'btn  btn-success info'
    );
    $data_1 = array(
        'name' => 'button',
        'id' => 'enviar',
        'value' => 'seleccionar',
        'content' => '<i class="fa fa-paperclip"></i>Solicitar Fraccionamiento',
        'class' => 'btn  btn-warning enviar'
    );
    ?>

    <div class="Boton_Agregar">
        <?php
        echo form_button($data_3) . "  " . form_button($data_1). "  ";
        echo anchor('bandejaunificada/', '<i class="fa fa-minus-circle"></i> Atras', 'class="btn btn-warning"');
        ?>
    </div>
    <?php
    echo form_close();
    ?>
    <br>
</center>
<br>
<div id="consulta" ></div>
<script>
    $(".Boton_PDF").hide();
    $(".informacion").hide();
    $(".preload, .load").hide();
    $("#info").click(function() {
        $(".informacion").show();
    });
    $("#ir").click(function() {
        $(".preload, .load").show();
        $('#cod_respuesta').val('511');
        $("#form1").submit();
        //$(".preload, .load").hide();
    });
    $("#enviar").click(function() {
        $(".preload, .load").show();
        var monto_sena = $("#monto_sena").val();
        var monto_deudor = $("#monto_deudor").val();
        var titulo_sena = $("#titulo_sena").val();
        var titulo_deudor = $("#titulo_deudor").val();
        if (monto_sena == '' || monto_deudor == '' || titulo_sena == '' || titulo_deudor == '') {
            alert('Debe completar todos los campos')
        } else {
            $("#myform").submit();            
        }
    });
    $(".Boton_PDF").hide();
    $("#preloadmini").hide();
</script> 


