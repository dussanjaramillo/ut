<?php if (!defined('BASEPATH')) exit('No direct script access allowed') ?>
<?php if (isset($message)) echo $message; ?>

<style>
    .data{
        float: left;
        padding: 20px;
    }
</style>
<br><br>
<h2 align="center">Generación informe SIIF</h2>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<div style="border:1px solid #990000;padding-left:20px;padding-top:40px;padding-bottom:90px;margin:0 0 10px 0; text-align: center;">
    <?php echo form_open('siif/exportar'); ?>
    <div class="data">
        <label for="fecha">FECHA DEL REPORTE</label> <?php
        $data = array(
            'name' => 'fecha',
            'id' => 'fecha',
            'style' => 'width: 100px;',
            'required' => 'true',
            'readonly' => 'true',
        );
        echo form_input($data);
        ?>
    </div>
    <div class="data">
        <label for="responsable">RESPONSABLE DEL CARGUE</label> <?php
        $data = array(
            'name' => 'responsable',
            'id' => 'responsable',
            'style' => 'width: 150px;',
            'required' => 'true',
        );
        echo form_input($data);
        ?>
    </div>
    <div class="data">
        <label for="documento">DOCUMENTO RECAUDO</label> <?php
        $data = array(
            'name' => 'documento',
            'id' => 'documento',
            'style' => 'width: 150px;',
            'required' => 'true',
        );
        echo form_input($data);
        ?>
    </div>
    <div class="data">
        <label for="cuenta">CUENTA A GENERAR INFORME</label> 
        <select required="true" name="cuenta" id="cuenta" style="width: 300px;">
            <option value="">...Seleccione una opción...</option>
            <?php
            foreach ($cuentas as $cuenta) {
                echo '<option value="' . $cuenta["cuenta"] . '">' . $cuenta["cuenta"]." ".$cuenta["descripcion"] . '</option>';
            }
            ?>
        </select>
    </div>
    
    <div class="data" style="text-align: center;">
        <label for="reciproca">RECIPROCAS</label> 
        <input type="checkbox" name="reciprocas" id="reciprocas" value="1" />
    </div>

    <div style="clear: both; text-align: center;">
        <?php
            echo form_submit('enviar', 'GENERAR INFORME', 'class="btn btn-success"');
            echo form_close();
        ?>
    </div>
</div>

<script type="text/javascript" language="javascript" charset="utf-8">
    $(document).ready(function() {
        jQuery(".preload, .load").hide();

        $("#fecha").datepicker({
            showOn: 'button',
            buttonText: 'Fecha del informe',
            buttonImage: '<?php echo base_url() . "img/calendario.png" ?>',
            buttonImageOnly: true,
            numberOfMonths: 1,
            maxDate: "-1",
            minDate: "-5y",
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd/mm/yy',
        });
    });


</script>