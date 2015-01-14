
<p><br>
<div align="center"><h1>Datos Para Generar la Resoluci&oacute;n</h1></div>
<br>
<p></p>

<?php
$attributes = array('onsubmit' => 'return comprobar()', "id" => "myform");
echo form_open("notificacionacta/resoluciones", $attributes);
?>
<div class="bs-docs-grid" style="aling:center" id="formulario">
    <div class="row show-grid">
        <div class="span2">
            <?php
            echo form_label('Fecha Creaci&oacute;n<span class="required">*</span>', 'fechacreacion');
            $dataid = array(
                'name' => 'fechacreacion',
                'id' => 'fechacreacion',
                'value' => date("d/m/Y"),
                'maxlength' => '128',
                'readonly' => 'readonly'
            );
            ?>
        </div>
        <div class="span3" >
            <?php
            echo form_input($dataid);
            echo form_error('fechacreacion', '<div>', '</div>');
            ?>
        </div>
        <div class="span2">
            <?php
            echo form_label('Regional<span class="required">*</span>', 'regional');
            $dataid = array(
                'name' => 'regional',
                'id' => 'regional',
                'value' => $query['NOMBRE_REGIONAL'],
                'maxlength' => '128',
                'readonly' => 'readonly'
            );
            ?>
            <input type="hidden" id="id_regional" name="id_regional" value="<?php echo $query['COD_REGIONAL']; ?>">
            <input type="hidden" id="id_resolucion" name="id_resolucion" value="<?php echo $post['id_resolucion']; ?>">
        </div>
        <div class="span3">
            <?php
            echo form_input($dataid);
            echo form_error('regional', '<div>', '</div>');
            ?>
        </div>
    </div>
    <div class="row show-grid">
        <div class="span2">
            <?php
            echo form_label('Nombre Empleador<span class="required">*</span>', 'nombreempleador');
            $dataid = array(
                'name' => 'nombreempleador',
                'id' => 'nombreempleador',
                'value' => $query['NOMBRE_EMPRESA'],
                'maxlength' => '128',
                'readonly' => "readonly"
            );
            ?>
        </div>
        <div class="span3">
            <?php
            echo form_input($dataid);
            echo form_error('nombreempleador', '<div>', '</div>');
            ?>
        </div>
        <div class="span2">
            <?php
            echo form_label('Nit<span class="required">*</span>', 'nit');
            $dataid = array(
                'name' => 'nit',
                'id' => 'nit',
                'value' => $query['CODEMPRESA'],
                'maxlength' => '128',
                'readonly' => 'readonly'
            );
            ?>
        </div>
        <div class="span3">
            <?php
            echo form_input($dataid);
            echo form_error('nit', '<div>', '</div>');
            ?>
        </div>
    </div>
    <div class="row show-grid">
        <div class="span10"><hr></div>
    </div>
    <div class="row show-grid">
        <div class="span2">
            <?php
            echo form_label('Liquidaci&oacute;n<span class="required">*</span>', 'liquidacion');
            $dataid = array(
                'name' => 'liquidacion',
                'id' => 'liquidacion',
                'value' => $liquidacion['NUM_LIQUIDACION'],
                'maxlength' => '128',
                'readonly' => 'readonly'
            );
            ?>
        </div>
        <div class="span3">
            <?php
            echo form_input($dataid);
            echo form_error('liquidacion', '<div>', '</div>');
            ?>
        </div>
        <div class="span2">
            <?php
            echo form_label('Fecha Liquidaci&oacute;n<span class="required">*</span>', 'fecha_liquidacion');
            $dataid = array(
                'name' => 'fecha_liquidacion',
                'id' => 'fecha_liquidacion',
                'value' => $liquidacion['FECHA_LIQUIDACION'],
                'maxlength' => '128',
                'readonly' => 'readonly'
            );
            ?>
        </div>
        <div class="span3">
            <?php
            echo form_input($dataid);
            echo form_error('fecha_liquidacion', '<div>', '</div>');
            ?>
        </div>
    </div>
    <div class="row show-grid">
        <div class="span2">
            <?php
            echo form_label('Per&iacute;odo Desde<span class="required">*</span>', 'periodo_desde');
            $dataid = array(
                'name' => 'periodo_desde',
                'id' => 'periodo_desde',
                'value' => $liquidacion['FECHA_INICIO'],
                'maxlength' => '128',
                'readonly' => 'readonly'
            );
            ?>
        </div>
        <div class="span3">
            <?php
            echo form_input($dataid);
            echo form_error('periodo_desde', '<div>', '</div>');
            ?>
        </div>
        <div class="span2">
            <?php
            echo form_label('Per&iacute;odo hasta<span class="required">*</span>', 'periodo_hasta');
            $dataid = array(
                'name' => 'periodo_hasta',
                'id' => 'periodo_hasta',
                'value' => $liquidacion['FECHA_FIN'],
                'maxlength' => '128',
                'readonly' => 'readonly'
            );
            ?>
        </div>
        <div class="span3">
            <?php
            echo form_input($dataid);
            echo form_error('periodo_hasta', '<div>', '</div>');
            ?>
        </div>
    </div>
    <div class="row show-grid">

        <?php
        
//        if($liquidacion['TOTAL_LIQUIDADO']==$liquidacion['SALDO_DEUDA']){
//            $liquidacion['TOTAL_LIQUIDADO']=$liquidacion['TOTAL_LIQUIDADO'];
//        }else{
//            $valor=  $liquidacion['SALDO_DEUDA']-$liquidacion['TOTAL_LIQUIDADO'];
//            $liquidacion['TOTAL_LIQUIDADO']=$valor+$liquidacion['TOTAL_LIQUIDADO'];
//        }
//        
        $liquidacion['TOTAL_LIQUIDADO']=$liquidacion['SALDO_DEUDA'];
        $valor_letras = utf8_encode(Notificacionacta::valorEnLetras($liquidacion['TOTAL_LIQUIDADO'], "pesos"));
        ?>

        <div class="span2">
            
            <?php
            echo form_label('Valor en pesos<span class="required">*</span>', 'valor_pesos');
            $dataid2 = array(
                'name' => 'valor_pesos2',
                'id' => 'valor_pesos2',
                'value' => $liquidacion['TOTAL_LIQUIDADO'],
                'maxlength' => '128',
                'readonly' => 'readonly',
                'type' => 'hidden'
            );
            $dataid = array(
                'name' => 'valor_pesos',
                'id' => 'valor_pesos',
                'value' => number_format($liquidacion['TOTAL_LIQUIDADO']),
                'maxlength' => '128',
                'readonly' => 'readonly',
            );
            echo form_input($dataid2);
            ?>
        </div>
        <div class="span3">
            <?php
            echo form_input($dataid);
            echo form_error('valor_pesos', '<div>', '</div>');
            ?>
        </div>
        <div class="span2">
            <?php
            echo form_label('Valor en letras<span class="required">*</span>', 'valor_letras');
            $dataid = array(
                'name' => 'valor_letras',
                'id' => 'valor_letras',
                'value' => $valor_letras,
                'maxlength' => '128',
                'readonly' => 'readonly',
                'type' => 'hidden'
            );
            ?>
        </div>
        <div class="span3">
            <textarea readonly="readonly"><?php echo $valor_letras;?></textarea>
            <?php
//            echo $valor_letras;
            echo form_input($dataid);
            echo form_error('valor_letras', '<div>', '</div>');
            ?>
        </div>
    </div>
    <div class="row show-grid">
        <div class="span2">
            <?php
            echo form_label('Concepto<span class="required">*</span>', 'concepto');
            $dataid = array(
                'name' => 'concepto',
                'id' => 'concepto',
                'value' => $liquidacion['NOMBRECONCEPTO'],
                'maxlength' => '128',
                'readonly' => 'readonly'
            );
            ?>
            <input type="hidden" id="id_concepto" name="id_concepto" value="<?php echo $liquidacion['COD_CONCEPTO']; ?>"/>
            <input type="hidden" id="cod_fiscalizacion" name="cod_fiscalizacion" value="<?php echo $iniciar['COD_FISCALIZACION']; ?>"/>
        </div>
        <div class="span3">
            <?php
            echo form_input($dataid);
            echo form_error('concepto', '<div>', '</div>');
            ?>
        </div>
        <div class="span2">
            <?php
            echo form_label('Elabor&oacute;<span class="required">*</span>', 'elaboro');
            $dataid = array(
                'name' => 'elaboro',
                'id' => 'elaboro',
                'value' => $info_user,
                'maxlength' => '128',
                'readonly' => 'readonly'
            );
            ?>
        </div>
        <div class="span3">
            <?php
            echo form_input($dataid);
            echo form_error('elaboro', '<div>', '</div>');
            ?>
        </div>
    </div>
    <div class="row show-grid">
        <div class="span2" style="display: none">
            <?php
            form_label('Reviso<span class="required">*</span>', 'reviso');
            ?>
        </div>
        <div class="span3" style="display: none">
            <select name="reviso" id="reviso">
                <option value="<?php echo $cord['COD_REGIONAL']; ?> "><?php echo $cord['COORDINADOR_REGIONAL']; ?></option>    
            </select>
            <?php
            ?>
        </div>
        <div class="span2">
            <?php
            echo form_label('Ciudad<span class="required">*</span>', 'ciudad');
            $dataid = array(
                'name' => 'ciudad',
                'id' => 'ciudad',
                'value' => $ciudad['NOMBREMUNICIPIO'],
                'maxlength' => '128',
                'readonly' => 'readonly'
            );
            ?>
        </div>
        <div class="span3">
            <?php
            echo form_input($dataid);
            echo form_error('ciudad', '<div>', '</div>');
            ?>
        </div>
    </div>
    <div class="row show-grid">
        <div class="span10"><hr></div>
    </div>
    <div class="row show-grid">
        <div class="span2">
            <?php
             form_label('Abogado<span class="required">*</span>', 'abogado');
            ?>
        </div>
        <div class="span3">
            <select name="abogado" id="abogado" style="display: none">
                <?php foreach ($abogados as $abogado) { ?>
                    <option value="<?php echo $abogado['IDUSUARIO']; ?>"><?php echo $abogado['APELLIDOS'] . " " . $abogado['NOMBRES']; ?></option>    
                <?php } ?>
            </select>
            <?php
            echo form_error('abogado', '<div>', '</div>');
            ?>
        </div>
    </div>
    <div class="row show-grid">
        <div class="span2">
            <?php
            echo form_label('Coordinador<span class="required">*</span>', 'coordinador');
            $dataid = array(
                'name' => 'coordinador',
                'id' => 'coordinador',
                'value' => $query['COORDINADOR_REGIONAL'],
                'maxlength' => '128',
                'readonly' => 'readonly'
            );
            ?>
            <input type="hidden" name="id_coordinador" id="id_coordinador" value="<?php echo $query['CEDULA_COORDINADOR_RELACIONES'] ?>">
        </div>
        <div class="span2">
            <?php
            echo form_input($dataid);
            echo form_error('coordinador', '<div>', '</div>');
            ?>
        </div>
    </div>
    <div class="row show-grid">
        <div class="span2">
            <?php
            echo form_label('Director Regional<span class="required">*</span>', 'director_regional');
            $dataid = array(
                'name' => 'director_regional',
                'id' => 'director_regional',
                'value' => $query['DIRECTOR_REGIONAL'],
                'maxlength' => '128',
                'readonly' => 'readonly'
            );
            ?>
            <input type="hidden" name="id_director_regional" id="id_director_regional" value="<?php echo $query['CEDULA_DIRECTOR'] ?>">
            <input type="hidden" name="email_regional" id="email_regional" value="<?php echo $query['EMAIL_REGIONAL'] ?>">
        </div>
        <div class="span3">
            <?php
            echo form_input($dataid);
            echo form_error('director_regional', '<div>', '</div>');
            ?>
        </div>
        <div class="span2">
            <?php
            echo form_label('Fecha Actual<span class="required">*</span>', 'fecha_actual');
            $dataid = array(
                'name' => 'fecha_actual',
                'id' => 'fecha_actual',
                'value' => date("d/m/Y"),
                'maxlength' => '128',
                'readonly' => 'readonly'
            );
            ?>
        </div>
        <div class="span2">
            <?php
            echo form_input($dataid);
            echo form_error('fecha_actual', '<div>', '</div>');
            ?>
        </div>
    </div>
    <div class="row show-grid">
        <div class="span10"><hr></div>
    </div>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <div class="row show-grid" style="align:center">
        <center>
            <?php
            $data = array(
                'name' => 'button',
                'id' => 'submit-button',
                'value' => 'Continuar',
                'type' => 'submit',
                'content' => 'Continuar',
                'class' => 'btn btn-success'
            );
            echo form_button($data);
            echo "&nbsp;&nbsp;&nbsp;&nbsp;";
            $data = array(
                'name' => 'button',
                'id' => 'submit-button',
                'value' => 'Continuar',
                'type' => 'submit',
                'content' => '<i class="fa fa-arrow-circle-left"></i>Atras',
                'class' => 'btn btn-default'
            );
            echo form_button($data);
            ?>
        </center>
</div>
    </div>
        
<div id="resolucion">
</div>
<?php echo form_close(); ?>
<script>
    function comprobar() {
        if ($('#nombreempleador').val() == "") {
            $('#nombreempleador').focus();
            $('#resolucion').html("<div style='color:red'><h2>Datos Imcompletos</h2></div>");
            return false;
        } else if ($('#valor_letras').val() == "") {
            $('#valor_letras').focus();
            $('#resolucion').html("<div style='color:red'><h2>Datos Imcompletos</h2></div>");
            return false;
        } else if ($('#abogado').val() == "") {
            $('#abogado').focus();
            $('#resolucion').html("<div style='color:red'><h2>Datos Imcompletos</h2></div>");
            return false;
            } else {
        return true;
        }
            }
            function atras(){
                window.history.back();
                return false;
            }
</script>
