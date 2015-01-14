<div align="center"><h2>REPORTES MULTAS DEL MISNISTERIO</h2></div>
<br>
<table id="estilotabla">
    <thead>
        <th>Nombre o Razón Social</th>
        <th>Ciudad</th>
        <th>Dirección</th>
        <th>Tipo de Documento</th>
        <th>Número de Documento</th>
        <th>Reprsentante Legal</th>
        <th>Tipo de Documento del Representante</th>
        <th>Número de Documeto</th>
        <th>Hechos y Pruebas Incluidas</th>
        <th>Normas Infringidas y Hechos Probados</th>
        <th>Multas en Salarios Minimos</th>
        <th>Multa en Valor</th>
</thead>
<tbody></tbody>
</table>
<script>
    $(document).ready(function() {
     $('#estilotabla').dataTable({
                    "sDom": 'T<"clear">lfrtip',
                    "oTableTools": {
                        "sSwfPath": "<?= base_url('/swf/copy_csv_xls_pdf.swf') ?>",
                        "sRowSelect": "multi"
                    }
                });  
                });  
</script>
