<br>
<br>
<br>
<br>
<table>
    <tr>
        <td>Tipo</td>
        <td>
            <select id="tipo1">
                <option  value="">-Seleccionar-</option>
                <?php foreach ($tipodocumento->result_array as $tipo) { ?>
                    <option value="<?= $tipo['CODTIPODOCUMENTO'] ?>"><?= $tipo['NOMBRETIPODOC'] ?></option>
                <?php } ?>
            </select>
        </td>
        <td>Documento</td>
        <td><input type="text" name="documento" id="documento1"></td>
        <td>Planilla</td>
        <td><input type="text" name="planilla" id="planilla1"</td>
        <td>Regional</td>
        <td>
            <select id="regional1">
                <option value="">-Seleccionar-</option>
                <?php foreach ($regional->result_array as $region) { ?>
                    <option value="<?= $region['COD_REGIONAL'] ?>"><?= $region['NOMBRE_REGIONAL'] ?></option>
                <?php } ?>
            </select>
        </td>
    </tr>
    <tr>
        <td>Razón Social</td>
        <td colspan="7"><input type="text" name="razon" id="razon2" style="width: 1045px"></td>
    </tr>
    <tr>
        <td>Fecha Inicial</td>
        <td>
            <select id="fechain">
                <option value="">-Seleccionar-</option>
                <?php
                for ($i = 2010; $i <= 2020; $i++) {
                    ?>
                    <option value="<?= $i ?>"><?= $i ?></option>
                    <?php }
                ?>
            </select>
        </td>
        <td>Fecha Final</td>
        <td>
            <select id="fechafi">
                <option value="">-Seleccionar-</option>
                <?php
                for ($i = 2010; $i <= 2020; $i++) {
                    ?>
                    <option value="<?= $i ?>"><?= $i ?></option>
                    <?php }
                ?>
            </select>
        </td>
    </tr>
</table>
<div id="alerta1"></div>
<br>
<table align="center">
    <tr>
        <td style="width: 100px"><button id="consultar1" class="btn btn-success">Consultar</button></td>
        <td style="width: 100px"><button id="cancelar4" class="btn btn-success">Cancelar</button></td>
    </tr>
</table>
<br>
<br>
<table border="3" align="center" id="eliminarempresa">
    <thead id="empresa">
        <tr>
            <th style="width: 200px">NIT</th>
            <th style="width: 200px">RAZON SOCIAL</th>
            <th style="width: 200px">N. PLANILLA</th>
            <th style="width: 200px">ESTADO</th>
            <th style="width: 200px">VALOR</th>
            <th style="width: 200px">REGISTRO 1</th>
            <th style="width: 200px">REGISTRO 2</th>
            <th style="width: 200px">REGISTRO 3</th>
            <th style="width: 200px">REGISTRO BANCO</th>
            <th style="width: 200px">SELECCION</th>
        </tr>
    </thead>
    <tbody id="consultaempresa"></tbody>
</table>
<br>
<br>
<table align="center">
    <tr>
        <td style="width: 100px"><button type="button" id="eliminar1" class="btn btn-success">Eliminar</button></td>
        <td style="width: 100px"><button type="button" id="cancelar3" class="btn btn-success">Cancelar</button></td>
    </tr>
</table>
<script>
    $('#eliminarempresa').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers"
    });
</script>    
