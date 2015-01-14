
<table id="estilotabla" border='2' align='center' style="border-color: #008000">
    <thead style="background: #008000; color: #ffffff">
        <tr>
    <th>Tipo Doc</th>
    <th>N.Doc</th>
    <th>Cod.Tipo Cot.</th>
    <th>Primer Apellido</th>
    <th>Segundo Apellido</th>
    <th>Primer Nombre</th>
    <th>Segundo Nombre</th>
    <th>Tarifa Aporte</th>
    <th>Valor Aporte</th>
    <th>Salario</th>
    <th>IBC</th>
    <th>Dias trabajados</th>
    <th>Depto</th>
    <th>Municipio</th>
    <th>Novedad Ingreso</th>
    <th>Novedad Retiro</th>
    <th>Novedad Variacion Salario</th>
    <th>Novedad Variacion Temporal</th>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($consulta->result_array as $datos) { ?>
        <tr><td><?php if(!empty($datos['TIPO_IDENT_COTIZ'])) echo$datos['TIPO_IDENT_COTIZ']; ?></td>
            <td ><?php if(!empty($datos['N_IDENT_COTIZ'])) echo $datos['N_IDENT_COTIZ']; ?></td>
            <td><?php if(!empty($datos['TIPO_COTIZ']))echo $datos['TIPO_COTIZ']; ?></td>
            <td><?php if(!empty($datos['PRIMER_APELLIDO'])) echo $datos['PRIMER_APELLIDO']; ?></td>
            <td><?php if(!empty($datos['SEGUN_APELLIDO']))echo $datos['SEGUN_APELLIDO'];?></td>
            <td><?php if(!empty($datos['PRIMER_NOMBRE']))echo $datos['PRIMER_NOMBRE'];?></td>
            <td><?php if(!empty($datos['SEGUN_NOMBRE']))echo $datos['SEGUN_NOMBRE']; ?></td>
            <td><?php  if(!empty($datos['TARIFA'])) echo $datos['TARIFA'];?></td>
            <td><?php if(!empty($datos['APORTE_OBLIG']))echo number_format($datos['APORTE_OBLIG']);?></td>
            <td><?php echo number_format($datos['SALARIO_BASICO']); ?></td>
            <td><?php if(!empty($datos['ING_BASE_COTIZ']))echo number_format($datos['ING_BASE_COTIZ']);?></td>
            <td><?php if(!empty($datos['DIAS_COTIZ']))echo $datos['DIAS_COTIZ']; ?></td>
            <td><?php if(!empty($datos['COD_DEPARTA_UBI_LAB']))echo $datos['COD_DEPARTA_UBI_LAB'];?></td>
            <td><?php if(!empty($datos['COD_MUNI_UBI_LAB_']))echo $datos['COD_MUNI_UBI_LAB_']; ?></td>
            <td><?php  if(!empty($datos['ING'])){echo $datos['ING'];}?></td>
            <td><?php  if(!empty($datos['RET'])){echo $datos['RET'];}?></td>
            <td><?php  if(!empty($datos['VST'])){echo $datos['VST'];}?></td>
            <td><?php if(!empty($datos['VARIACIONES'])){echo $datos['VARIACIONES'];} ?> </td></tr>
        <?php } ?>
    </tbody>
</table>