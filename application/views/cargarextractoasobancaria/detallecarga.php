<?php if(isset($message)) echo $message; ?>

<script type="text/javascript" language="javascript" charset="utf-8">
    $(document).ready(function() {
        $('#tabla2').dataTable({
            "bProcessing": true,
//            "sPaginationType": "full_numbers",
            "bPaginate": false,
            "bScrollCollapse": true
        });
    } );
    
    $('#cancelar').on('click', function() {
        $('.modal-body').html('<img src="<?= base_url() ?>/img/27.gif" width="150px" height="150px" />');
        $('#modal').modal('hide').removeData();
    });
    
    $('.close').on('click', function() {
        $('.modal-body').html('<img src="<?= base_url() ?>/img/27.gif" width="150px" height="150px" />');
        $('#modal').modal('hide').removeData();
    });
</script>
<?php 
$html = ' 
<table id="tabla2">
    <thead>
        <tr>
            <th>Nit</th>
            <th>Nombre</th>
            <th>Número Planilla</th>
            <th>Periodo Pago</th>
            <th>Número Registros</th>
            <th>Valor Planilla</th>
            <th>Hora</th>
        </tr>
    </thead>
    <tbody>';

    foreach ($data1->result_array as $data){  
        $html .= '
        <tr>
            <td align="right">'.$data['COD_APORTANTE'].'</td> 
            <td align="left">'.$data['NOM_APORTANTE'].'</td> 
            <td align="right">'.$data['NRO_PLANILLA'].'</td>
            <td align="right">'.$data['PERIODO_PAGO'].'</td>
            <td align="center">'.number_format($data['NRO_REGISTROS']).'</td>
            <td align="right">'.number_format($data['VALOR_PLANILLA']).'</td>
            <td align="center">'.$data['HORA_MINUTO'].'</td>
        </tr>'; 
    }
    $html .= '
    </tbody>  
</table>';
    echo $html;
?>