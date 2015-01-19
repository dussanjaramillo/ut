<?php 
if (isset($message)) echo $message; 
$data_observaciones = array('name' => 'observaciones', 'id' => 'observaciones', 'width' => '100%', 'rows' => '5', 'style' => 'width:98%',  'required' => 'true');
?>
<table align="center" width="100%" border="1">
    <tr align="center">
        <td>
            <strong>Tipo Documento: </strong><?= $tipodoc ?><br>
            <strong>Documento: </strong><?= $nit ?>
        </td>
        <td>
            <strong>Razón Social: </strong><?= $razonsocial ?><br>
            <strong>Tipo de Obligación: </strong><?= $concepto ?><br>
            <strong>Expediente: </strong><?= $expediente ?><br>
        </td>
    </tr>
</table>

<div style="text-align: center">
    <h3>Consulta de la existencia de títulos recibidos</h3>
    <h4>(RESOLUCIÓN 1235 DE 2014)</h4>
</div>

<div id="documentos" style="padding-top: 20px;">
    <h5>Lista de chequeo documentos entregados</h5>
</div>

<?php 
$attributes = array("id" => "myform", "name" => "myform");
echo form_open(current_url(), $attributes); 
?>
<?php if (isset($custom_error)) echo $custom_error; ?>
<br />
<input type="hidden" name="nit" id="nit" value="<?= $nit ?>" />
<input type="hidden" name="expediente" id="expediente" value="<?php echo $expediente ?>" />
<div style="width: 90%">
    <table width="100%" align="justify" border="0" cellpadding="8">
        <?php 
        foreach ($controles as $value) {
            echo "
            <tr>
                <td align='right' width='20%'> <input id='list_docs' type='checkbox' name='docs' value='" . $value['value'] . "'  /> </td>
                <td align='justify'> " . form_label($value['label']) . form_error($value['id']). " </td>
            </tr>
            ";
        }
        ?>
    </table>
</div>    
        
<table width="100%" align="center" border="0" cellpadding="8">
    <tr>
        <td colspan="2">
            <?php echo form_label('Observaciones: ', 'observaciones') ?>
            <?php echo form_textarea($data_observaciones); ?>
            <?php echo form_error('observaciones') ?>
        </td>
    </tr>
    <tr align="center">
        <td><?php echo form_submit('aceptar', 'ACEPTAR TITULO', 'class="btn btn-success" onclick="return validarSeleccion();"') ?></td>
        <td><?php echo form_submit('rechazar', 'RECHAZAR TITULO', 'class="btn btn-success"') ?></td>
    </tr>
</table>
<br /><br />

<div class="Comentarios" style="width: 90%; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 0">
    <?php
    echo form_label('<b><h3>Historial de Comentarios</h3></b><span class="required"></span>', 'lb_comentarios');
    echo "<br>";
 if($comentarios){
  for ($i = 0; $i < sizeof($comentarios); $i++) {
//        for ($j = 0; $j < sizeof($cc); $j++) {
            echo '<div class="alert-success" style="border-color: black; border: 1px solid grey;padding: 15px 50px 0">';
            echo '<b>Fecha del Comentario: </b>' . $comentarios[$i]["FECHA_RECIBIDO"] . '<br>';
            //echo '<b>Hecho Por: </b>' . $comentario[$i][$j]["NOMBRES"] . " " . $comentario[$i][$j]["APELLIDOS"] . '<br>';
            echo $comentarios[$i]["OBSERVACIONES"];
            echo "<br><br>";
            echo '</div>';
            echo "<br>"; 
       // }
    }
 }
    ?>
</div>

<script type="text/javascript" language="javascript" charset="utf-8">
    // Verifica que el usuario selecciona todos los documentos del checklist
    function validarSeleccion() {
		 var total = $("input[type='checkbox']:checked").length;
		var cantidad = '<?php echo sizeof($controles); ?>';
		if (total != cantidad) {
            alert("No se han Chequeado todos los documentos. ");
            return false;
        }else{
			return true;			
		}
       /*  var form   = document.myform;
        var valReg = false;
        <?php $datasel= json_encode($controles); ?>
        eval('miObjSel =<?php echo $datasel; ?>');
        for (i in miObjSel) {
            valReg = form.elements["list_docs"][i].checked;
            if (valReg == false) {
                alert("No se han Chequeado todos los documentos. ");
                return false;
            }                
        }
        return true; */
    }
</script>    