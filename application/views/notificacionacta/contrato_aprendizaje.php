<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<?php if(isset($custom_error)) echo $custom_error ?>
<?php echo form_open("notificacionacta/resoluciones", array("id"=>"myform")); ?>
<div class="bs-docs-grid" style="aling:center" id="formulario">
    <div class="row show-grid">
        <div class="span1">
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
        </div>
        <div class="span3" >
            <?php
            echo form_input($dataid);
            echo form_error('regional', '<div>', '</div>');
            ?>
            <input type="hidden" id="id_regional" name="id_regional" value="<?php echo $query['COD_REGIONAL'];?>" >
            <input type="hidden" id="num_liquidacion" name="num_liquidacion" value="<?php echo $iniciar['NUM_LIQUIDACION'];?>" >
        </div>
        <div class="span1">
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
        <div class="span1">
            <?php
            echo form_label('Razon Social<span class="required">*</span>', 'rozon_social');
            $dataid = array(
                'name' => 'rozon_social',
                'id' => 'rozon_social',
                'value' => $query['RAZON_SOCIAL'],
                'maxlength' => '128',
                'readonly' => 'readonly'
            );
            ?>
        </div>
        <div class="span3">
            <?php
            echo form_input($dataid);
            echo form_error('rozon_social', '<div>', '</div>');
            ?>
        </div>
    </div>
    <div class="row show-grid">
        <div class="span1">
            <?php
            echo form_label('Cuota<span class="required">*</span>', 'cuota');
            $dataid = array(
                'name' => 'cuota',
                'id' => 'cuota',
                'value' => $query['CUOTA_APRENDIZ'],
                'maxlength' => '128',
                'readonly' => 'readonly'
            );
            ?>
        </div>
        <div class="span3" >
            <?php
            echo form_input($dataid);
            echo form_error('cuota', '<div>', '</div>');
            ?>
        </div>
        <div class="span1">
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
        <div class="span1">
            <?php
            echo form_label('Fecha Actual<span class="required">*</span>', 'fecha_actual');
            $dataid = array(
                'name' => 'fecha_actual',
                'id' => 'fecha_actual',
                'value' => date("d-m-Y"),
                'maxlength' => '128',
                'readonly' => 'readonly'
            );
            ?>
        </div>
        <div class="span3">
            <?php
            echo form_input($dataid);
            echo form_error('fecha_actual', '<div>', '</div>');
            ?>
        </div>
    </div>
    <div class="row show-grid">
        <div class="span1">
            <?php
            echo form_label('Numero resolucion cuota<span class="required">*</span>', 'nResolucionCuota');
            $dataid = array(
                'name' => 'nResolucionCuota',
                'id' => 'nResolucionCuota',
                'value' => $iniciar['RESOLUCION_CUOTA'],
                'maxlength' => '128',
                'readonly' => 'readonly'
            );
            ?>
        </div>
        <div class="span3" >
            <?php
            echo form_input($dataid);
            echo form_error('nResolucionCuota', '<div>', '</div>');
            ?>
        </div>
        <div class="span1">
            <?php
            echo form_label('Fecha expedicion<span class="required">*</span>', 'fechaExpedicion');
            $dataid = array(
                'name' => 'fechaExpedicion',
                'id' => 'fechaExpedicion',
                'value' => $iniciar['RESOLUCION_FECHA_RESOLUCION'],
                'maxlength' => '70',
                'readonly' => 'readonly'
            );
            ?>
        </div>
        <div class="span3">
            <?php
            echo form_input($dataid);
            echo form_error('fechaExpedicion', '<div>', '</div>');
            ?>
        </div>
        <div class="span1">
            <?php
            echo form_label('Fecha ejecutoria<span class="required">*</span>', 'fecha_ejecutoria');
            $dataid = array(
                'name' => 'fecha_ejecutoria',
                'id' => 'fecha_ejecutoria',
                'value' => $iniciar['RESOLUCION_FECHA_EJECUTORIA'],
                'maxlength' => '70',
                'readonly' => 'readonly'
            );
            ?>
        </div>
        <div class="span3">
            <?php
            echo form_input($dataid);
            echo form_error('fecha_ejecutoria', '<div>', '</div>');
            ?>
        </div>
    </div>
    <div class="row show-grid"><!--jhkjh-->
        <div class="span1">
            <?php
            echo form_label('Valor resolución<span class="required">*</span>', 'vresolucion');
            $dataid = array(
                'name' => 'vresolucion2',
                'id' => 'vresolucion2',
                'value' => number_format($iniciar['ESTADO_VALOR_FINAL']),
                'maxlength' => '128',
                'readonly' => 'readonly'
            );
            ?>
        </div>
        <div class="span3" >
            <?php
            echo form_input($dataid);
            echo form_error('vresolucion', '<div>', '</div>');
            ?>
        </div>
        <?php
        
        $valor_letras = Notificacionacta::valorEnLetras($iniciar['ESTADO_VALOR_FINAL'], "pesos");
        ?>
        <div class="span1">
            <?php
            echo form_label('Valor en letras<span class="required">*</span>', 'vletras');
            $dataid = array(
                'name' => 'vletras',
                'id' => 'vletras',
                'style'=> 'width:100%',
                'value' => $valor_letras,
                'maxlength' => '70',
                'readonly' => 'readonly'
            );
            ?>
        </div>
        <div class="span7">
            <?php
            echo form_input($dataid);
            echo form_error('vletras', '<div>', '</div>');
            ?>
        </div>
    </div><!--sdfsdfsdf-->
    <hr>
    <div class="row show-grid">
        <div class="span1">
            <?php
            echo form_label('Considerando<span class="required">*</span>', 'considerando');
            ?>
        </div>
        <div class="span3" >
            <button id="conside" class="fa fa-plus-circle btn btn-info"> Agregar</button>
            <textarea id="considerando"  name="considerando" style="display: none"></textarea>
            <?php
            echo form_error('considerando', '<div>', '</div>');
            ?>
        </div>
        <div class="span1">
            <?php
            echo form_label('Resuelve<span class="required">*</span>', 'resuelve');
            ?>
        </div>
        <div class="span3" >
            <button id="resuel" class="fa fa-plus-circle btn btn-info"> Agregar</button>
            <textarea id="resuelve" name="resuelve" style="width: 100%; display: none"></textarea>
            <?php
            echo form_error('resuelve', '<div>', '</div>');
            ?>
        </div>
    </div>
    <hr>
    <div class="row show-grid">
        <div class="span1">
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
            <input type="hidden" id="cod_fis" name="cod_fis" value="<?php echo $iniciar['COD_FISCALIZACION'];?>" >
            <input type="hidden" id="vresolucion" name="vresolucion" value="<?php echo $iniciar['ESTADO_VALOR_FINAL'];?>" >
            <input type="hidden" id="num_proceso" name="num_proceso" value="<?php echo $iniciar['NUM_LIQUIDACION'];?>" >
            <input type="hidden" id="id_director_regional" name="id_director_regional" value="<?php echo $query['CEDULA_DIRECTOR'];?>" >
            <input type="hidden" name="email_regional" id="email_regional" value="<?php echo $query['EMAIL_REGIONAL']?>">
        </div>
        <div class="span3" >
            <?php
            echo form_input($dataid);
            echo form_error('director_regional', '<div>', '</div>');
            ?>
        </div>
        <div class="span1">
            <?php
            echo form_label('Reviso<span class="required">*</span>', 'reviso');
            ?>
        </div>
        <div class="span3">
            <select name="reviso" id="reviso">
                <?php foreach($cordinador as $cord){ ?>
                <option value="<?php echo $cord['CEDULA_COORDINADOR_RELACIONES']; ?> "><?php echo $cord['COORDINADOR_REGIONAL']; ?></option>    
                <?php }?>
            </select>
            <?php
            echo form_error('reviso', '<div>', '</div>');
            ?>
        </div>
        <div class="span1">
            <?php
            echo form_label('Elavora<span class="required">*</span>', 'elavora');
            $dataid = array(
                'name' => 'elavora',
                'id' => 'elavora',
                'value' => $info_user,
                'maxlength' => '70',
                'readonly' => 'readonly'
            );
            ?>
        </div>
        <div class="span3">
            <?php
            echo form_input($dataid);
            echo form_error('elavora', '<div>', '</div>');
            ?>
        </div>
    </div>
    <hr>
    <div class="row show-grid">
        <div class="span1">
            <?php
            echo form_label('Decision:<span class="required">*</span>', 'decision');
            ?>
        </div>
    </div>
    <div class="row show-grid">
        <div class="span1">
            <?php
            echo form_label('Archivo<span class="required">*</span>', 'decision');
            $dataid = array(
                'name' => 'decision',
                'id' => 'decision',
                'type' => 'radio',
                'value'=> '1',
            );
            ?>
        </div>
        <div class="span3" >
            <?php
            echo form_input($dataid);
            echo form_error('decision', '<div>', '</div>');
            ?>
        </div>
    </div>
    <div class="row show-grid">
        <div class="span1">
            <?php
            echo form_label('Sancionatorio<span class="required">*</span>', 'decision');
            $dataid = array(
                'name' => 'decision',
                'id' => 'decision',
                'type' => 'radio',
                'checked' => 'checked',
                'value'=> '2',
            );
            ?>
        </div>
        <div class="span3">
            <?php
            echo form_input($dataid);
            echo form_error('sancionatorio', '<div>', '</div>');
            ?>
        </div>
    </div>
    <div class="row show-grid">
        <div class="span11" style="text-align: center">
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
            ?>
        </div>
    </div>
</div>
<div id="resultado"></div>
<?php echo form_close(); ?>


<script>
    $(".preload, .load").hide();
$('#conside').click(function(){
    $(".preload, .load").show();
    var url='<?php echo base_url('index.php/notificacionacta/considerando'); ?>';
    var consi=$('#considerando').val();
    $('#resultado').load(url,{consi:consi});
    return false;
});

$('#resuel').click(function(){
    $(".preload, .load").show();
    var url='<?php echo base_url('index.php/notificacionacta/resuelve'); ?>';
    var consi=$('#resuelve').val();
    $('#resultado').load(url,{consi:consi});
    return false;
});
</script>
<style>
    .required{
        color: #FC7323;
    }
</style>
