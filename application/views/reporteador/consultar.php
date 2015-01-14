<div  id="formulario_final" class='modal hide fade modal-body'>
    <form id="form2" action="<?php echo base_url('index.php/reporteador/importar') ?>" method="POST" ><!--target="_blank"-->
        <table width="100%">
            <tr><td colspan="2" align="center" style="background-color: #5BB75B"><b>Guardar este reporte como:</b></td></tr>
            <tr>
                <td  align="center">Nombre de la Consulta</td>
                <td  align="center"><input type="text" maxlength="30" id="nombre" name="nombre"></td> 
            </tr>
            <tr>
                <td  align="center">Privado</td>
                <td  align="center">General</td>
            </tr>
            <tr>
                <td  align="center"><input type="radio" class="radio" name="guardar" checked="checked" value="1"></td>
                <td  align="center"><input type="radio" class="radio" name="guardar" value="2"></td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <!--<button class="btn btn-success" id="guardar2">Guardar Consultar</button>-->
                    <input type="button" class="btn btn-success" value="Guardar Consultar" id="guardar2"/>
                </td>
            </tr>
        </table>
        <input type="text" id="accion" name="accion" style="display: none">
        <textarea id="consulta_info" name="consulta_info" style="display: none"><?php echo base64_encode($consulta); ?></textarea>
    </form>
</div>

<?php
$i = 0;
$tbody = "";
$thead = "";
$k = 0;
foreach ($general as $detalle) {
    if ($i % 2 == 0)
        $tbody.= '<tr style="background-color: #f9f9f9">';
    else
        $tbody.= '<tr >';
    foreach ($detalle as $key => $value) {
        if ($k < count($detalle)) {
            $thead.="<th bgcolor='#5BB75B' style='border:1px solid #CCC'>" . $key . "</th>";
            $k++;
        }
        $tbody.="<td style='border:1px solid #CCC'>" . $value . "</td>";
    }
    $tbody.= "</tr>";
    $i++;
}
?>
<table width="100%">
    <thead style="color: #ffffff">
        <?php echo $thead; ?>
    </thead>
    <tbody>
        <?php echo $tbody; ?>
        <?php // echo (empty($tbody))?$tbody:$tbody2; ?>
    </tbody>
</table> <br>
<script>
    $('#guardar2').click(function() {
        var nombre=$('#nombre').val();
        if(nombre==""){
            alert('Datos Incompletos');
            return false;
        }
        $('#form2').submit();
    });
</script>
