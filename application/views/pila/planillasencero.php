<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<br>
<?php if (isset($message)) echo $message ?>
<br>
<h2>Generación listado de planillas en cero</h2>
<br>
<form id="planillas" name="planillas" method="post" action="planillasencero">
    <div class="controls controls-row" style="border:1px solid #990000;padding-left:20px;padding-top:40px;padding-bottom:90px;margin:0 0 10px 0; text-align: center;">
        <div class="span2"><strong>Fecha inicial: <span class="required">*</span></strong></div>
        <div class="span3">
            <input type="text" name="fecha_inicio" readonly id="fecha_inicio" class="validate[required]" required="true"/>
        </div>
        <div class="span2"><strong>Fecha Final: <span class="required">*</span></strong></div>
        <div class="span3">
            <input type="text" name="fecha_fin" readonly id="fecha_fin" class="validate[required]" required="true" />
        </div>
        <br>
        <br>
        <br>
        <div class="span2" style="text-align: center; width: 90%;"><input type="submit" value="Generar" class="btn btn-success"/></div>
    </div>
</form>

<script type="text/javascript" language="javascript" charset="utf-8">
    $(document).ready(function() {
        $("#fecha_inicio").datepicker({
            showOn: 'button',
            buttonText: 'Fecha del informe',
            buttonImage: '<?php echo base_url() . "img/calendario.png" ?>',
            buttonImageOnly: true,
            defaultDate: "-1w",
            dateFormat: "yy-mm-dd",
            changeMonth: true,
            maxDate: "0",
            minDate: "-5y",
            changeYear: true,
            onClose: function(selectedDate) {
                $("#fecha_fin").datepicker("option", "minDate", selectedDate);
            }
        });
        $("#fecha_fin").datepicker({
            showOn: 'button',
            buttonText: 'Fecha del informe',
            buttonImage: '<?php echo base_url() . "img/calendario.png" ?>',
            buttonImageOnly: true,
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd",
            onClose: function(selectedDate) {
                $("#fecha_inicio").datepicker("option", "maxDate", selectedDate);
            }
        });
    });
</script>