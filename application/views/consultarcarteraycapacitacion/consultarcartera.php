<?php
// RESPONSABLE:Leonardo Molina
$nejec      = array('name'=> 'nejec','id'=>'nejec','value'=>$ejec,'type'=>'hidden');
$button     = array('name'=> 'button','id' => 'submit-button','value' => 'Continuar','type' => 'submit','content' => '<i class="fa fa-share"></i> Continuar','class' => 'btn btn-success');
$attributes = array('onsubmit' => 'return comprobarextension()', "id" => "myform", 'class'=>'validar_form');
//var_dump($regEjec);
?>

<h1>Consultar Cartera</h1>

<h4 align="center" style="color: orange">Vigencias</h4>
<?=form_open_multipart("consultarcarteraycapacitacion/cargaConsulta");?>
<div class="modal-body">
<?php $i=0;
    if($regEjec->TIPO_CARTERA == 3){//
        echo '<p>Nit empresa: '.$vigencias->ESTADO_NIT_EMPRESA.'</p>'
        . '<table class="table table-bordered">'
        . '<tr><td>Estado de Cuenta SGVA</td><td>$ '.$vigencias->ESTADO_VALOR_FINAL.'</td><tr></table>';
        
    
    }else if($regEjec->TIPO_CARTERA == 1 || $regEjec->TIPO_CARTERA == 2){
        echo '<p>Saldo en cartera: '.number_format($regEjec->SALDO_DEUDA).'</p>';
        $total=  number_format($regEjec->SALDO_DEUDA);
    foreach($vigencias as $row){
        $periodo = $row->PERIODO_PAGADO;
        $periodo2= $row->PERIODOMAX;
        $anio="";
        $mes="";
//        echo $periodo.'************';
        list($anio2, $mes2) = explode("-", $periodo2);
        if(isset($periodo))
        list($anio, $mes)   = explode("-", $periodo);
        $aportes = $anio2 - 5;
        
        if($anio >= $aportes){
            $i++;
            if($i==1){
                $anio1=$anio;
                $cost=0;
                $total=0;
            }
            if($anio==$anio1){
                $cost++;
                if($cost==1){
                    echo '<table class="table table-bordered" style=" width: 210px; position: relative; float: left; margin: 10px;">'
                    .'<caption>Vigencia 01/01/'.$anio.'-31/12/'.$anio.'</caption>';}
                echo '<tr><td>Costo '.$cost.': </td><td>$ '.$row->VALOR_PAGADO.'</td></tr>';
                $total += $row->VALOR_PAGADO;
                $anio1  = $anio;
            }
            else{
                echo '<tr class="success" style="border: 1px solid black;"><td>Total: </td><td>$ '.$total.'</td></tr>';
                $cost  =0;
                $total =0;
                $anio1 =$anio;
                echo "</table>";
            }
        }

    } echo '<tr class="success" style="border: 1px solid black;">
                <td>Total: </td><td>$ '.$total.'</td>
    </tr></table>';
    
    }else if($regEjec->TIPO_CARTERA == 5){
        echo '<p>Nit empresa: '.$vigencias->NIT_EMPRESA.'</p>'
        . '<table class="table table-bordered">'
        . '<tr><td>Valor multa: </td><td>$ '.$vigencias->VALOR.'</td><tr></table>';
    }
?>

</div>

<div class="modal-footer">
    <?= form_input($nejec);?>
    <a href="#" class="btn btn-default" data-dismiss="modal" id="cancelar">Cancelar</a>
    <?= form_button($button);?>
</div>

<?= form_close();?>
<script>
  $('#cancelar').on('click', function() {
        $('.modal-body').html('<div align="center"><img src="<?= base_url() ?>/img/27.gif" width="150px" height="150px" /></div>');
        $('#modal').modal('hide').removeData();
    });
    $('.close').on('click', function() {
        $('.modal-body').html('<div align="center"><img src="<?= base_url() ?>/img/27.gif" width="150px" height="150px" /></div>');
        $('#modal').modal('hide').removeData();
    });
    
</script>