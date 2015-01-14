<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
// Responsable: Leonardo Molina

?>

<table class="table table-striped table-hover">
    <thead>
    <tr>
        <th>#</th>
        <th>Información</th>
    </tr>
    </thead>
    <tbody>
        <tr><th>Codigo Multa</th><td><?=$registros->COD_MULTAMINISTERIO;?></td></tr>
        <tr><th>Nit Empresa</th><td><?=$registros->NIT_EMPRESA;?></td></tr>
        <tr><th>Nombre Empresa</th><td><?=$registros->NOMBRE_EMPRESA;?></td></tr>
        <tr><th>Numero Radicado</th><td><?=$registros->NRO_RADICADO;?></td></tr>
        <tr><th>Numero Resolución</th><td><?=$registros->NRO_RESOLUCION;?></td></tr>
        <tr><th>NIS</th><td><?=$registros->NIS;?></td></tr>
        <tr><th>Valor Multa</th><td><?=$registros->VALOR;?></td></tr>
        <tr><th>Responsable</th><td><?=$registros->RESPONSABLE;?></td></tr>
        <tr><th>Fecha Creacion</th><td><?=$registros->FECHA_CREACION;?></td></tr>
        <tr><th>Fecha Ejecutoria</th><td><?=$registros->FECHA_EJECUTORIA;?></td></tr>
        <tr><th>Periodo Inicial</th><td><?=$registros->PERIODO_INICIAL;?></td></tr>
        <tr><th>Periodo Final</th><td><?=$registros->PERIODO_FINAL;?></td></tr>
        <tr><th>Exigibilidad</th><td><?php if($registros->EXIGIBILIDAD_TITULO==1) echo 'Exigible'; else echo 'N/A'; ?></td></tr>
        <tr><th>Observaciones</th><td><?=$registros->OBSERVACIONES;?></td></tr>
        <tr><th>Numero Comunicado</th><td><?=$registros->NUMERO_COMUNICACION;?></td></tr>
    </tbody>
</table>

<h4 style="text-align: center;">Resolución</h4>

<?php if(isset($resolucion->NUMERO_RESOLUCION)){?>
<table class="table table-striped table-hover">
    <thead>
    </thead>
    <tbody>
        <tr><td>Número: <?= $resolucion->NUMERO_RESOLUCION?></td><td><a href="<?=base_url()?>uploads/resolucion/COD_<?= $resolucion->NUMERO_RESOLUCION?>/<?= $resolucion->RUTA_DOCUMENTO_FIRMADO?>" target="_blank"><?= $resolucion->RUTA_DOCUMENTO_FIRMADO?></a></td></tr>
    </tbody>
</table>
<?php } else {echo "No se encontraron resoluciones para esta Multa";}?>

<h4 style="text-align: center;">Documentación</h4>
<table class="table table-striped table-hover">
    <thead>
    </thead>
    <tbody>
        <?php foreach($documentos as $row){?>
        <tr><td><a href="<?=base_url()?>uploads/multas/<?= $row->NOMBRE_DOCUMENTO?>" target="_blank"><?= $row->NOMBRE_DOCUMENTO?></a></td></tr>
        <?php }?>
    </tbody>
</table>