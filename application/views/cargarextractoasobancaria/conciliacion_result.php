<?php if (isset($message)) echo $message; ?>

<script type="text/javascript" language="javascript" charset="utf-8">
    $(document).ready(function() {
        $('#tablaq').dataTable({
            "bProcessing": true
        });
    });
</script>

<div style="padding-top: 20px;">
    <h5>Conciliación de pagos</h5>
</div>

<table id="tablaq">
    <thead>
    <tr>
        <th>ESTADO</th>
        <th>REGISTROS</th>
        <th>VALOR CONCILIADO</th>
    </tr>
    </thead>
    <tbody>
<?php
foreach ($resumen->result_array as $data2) {
    $estado = ($data2["ESTADO"] == '1') ? 'CONCILIADO' : 'NO CONCILIADO' ;
    echo '
        <tr>
            <td align="center">' . $estado . '</td> 
            <td align="center">' . number_format($data2["REGISTROS"]) . '</td> 
            <td align="center">$ ' . number_format($data2["MONTO"]) . '</td>
        </tr>';
}
echo '</body>'
. '</table><br><br>';
?>
 
<?php echo form_open('cargarextractoasobancaria/exportarconciliacion') ?>        
    <table align="center">
        <tr>
            <td colspan="3" align="center" style="padding-top:20px;">
                <button type="submit" class="btn btn-success">Exportar Detalle</button>
            </td>
        <tr>
    </table>
<?php echo form_close() ?>
