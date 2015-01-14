<div style="overflow: hidden; padding: 9px 0" class="row show-grid">
  <div class="text-center"><h2><?php echo $title ?><h2></h3></div>
</div>
<div style="border: 1px solid grey; overflow: hidden; padding: 9px 0" class="row show-grid">
  <span class="span2"><strong>NIT:</strong> <?php echo number_format($empresa['CODEMPRESA'], 0, ",", ".") ?></span>
  <span class="span3"><strong>RAZÓN SOCIAL:</strong> <?php echo $empresa['NOMBRE_EMPRESA']; ?></span>
  <span class="span3"><strong>DIRECCIÓN:</strong> <?php echo $empresa['DIRECCION']; ?></span>
  <span class="span3"><strong>TELÉFONO:</strong> <?php echo (!empty($empresa['TELEFONO_FIJO']))?$empresa['TELEFONO_FIJO']:$empresa['TELEFONO_CELULAR']; ?></span>
</div><br>
<?php if(!empty($datos) and $datos == true) : ?>
<table class="table table-bordered">
  <thead class="btn-success" style="font-size:80%">
    <th align="center">AÑO</th>
    <th align="center">MES</th>
    <?php
      if(isset($resolucion) and !empty($resolucion)) :
        echo '<th align="center">'.mb_strtoupper($resolucion[0]['NOMBRE_TIPO']).'</th>';
        $tc = 3;
      else :
        foreach($conceptos as $concepto) :
          echo '<th align="center">'.$concepto['NOMBRE_TIPO'].'</th>';
        endforeach;
        $tc = 2 + count($conceptos);
      endif;
    ?>
  </thead>
  <tbody>
    <?php
    $tot = array();
    foreach($conceptos as $concepto) :
      $tot[$concepto['NOMBRE_TIPO']] = 0;
    endforeach;
    for($x = $year['inicio']; $x <= $year['fin']; ++$x) :
      echo '<tr><td colspan="'.$tc.'" style="background-color: #f9f9f9;">'.$x.'</td></tr>'."\n\t\t";
      for($y = $mes[$x]['mesinicio']; $y <= $mes[$x]['mesfin']; ++$y) :
        echo '<tr><td></td><td>'.Consultarpagos::mes2($y).'</td>';
        foreach($conceptos as $concepto) :
          echo '<td>';
          $total = 0;
          if(!empty($resultados)) :
            foreach($resultados as $resultado) :
              $month = explode("/", $resultado['FECHA_PAGO']);
              if($resultado['COD_CONCEPTO'] == $concepto['COD_TIPOCONCEPTO'] and $month[1] == $y and $x == $month[2]) :
								$periodo_inicio = ($y==$mes[$x]['mesinicio'])?$diainicio:"01";
								$periodo_inicio .= "-".$y."-".$x;
								$periodo_fin = ($y==$mes[$x]['mesfin'])?$diafin:cal_days_in_month(CAL_GREGORIAN, $y, $x);
								$periodo_fin .= "-".$y."-".$x;
                $total += $resultado['VALOR_RECIBIDO'];
                $cod_concepto = $resultado['COD_CONCEPTO'];
                $tot[$concepto['NOMBRE_TIPO']] += $resultado['VALOR_RECIBIDO'];
              else :
                $tot[$concepto['NOMBRE_TIPO']] += 0;
              endif;              
            endforeach;
          endif;
          if($total > 0) :
            echo '<a href="'.base_url('index.php/consultarpagos/detallepago/'.$empresa['CODEMPRESA']."/".$cod_concepto)."/".$periodo_inicio."/".$periodo_fin.'">';
            echo '$ <div style="float: right">'.number_format($total, 0, ',', '.').'</div>';
            echo '</a>';
          endif;
          echo '</td>';
        endforeach;
      endfor;
      echo '<tr><td colspan="2" style="background-color: #f9f9f9;">TOTAL</td>'."\n\t\t";
      $tc2 = $tc - 2;
      foreach($tot as $val) :
        if($val > 0) :
          echo '<td style="background-color: #f9f9f9;">$ <div style="float: right">'.number_format($val, 0, ',', '.').'</div></td>';
        else :
          echo '<td style="background-color: #f9f9f9;">$ <div style="float: right">0</div></td>';
        endif;
      endforeach;
      echo '</tr>'."\n\t\t";
    endfor;
    ?>
  </tbody>
</table>
<a class="btn btn-default" href="<?php echo base_url('index.php/consultarpagos/manage') ?>"><i class="fa fa-arrow-circle-left fa-lg"></i> Regresar</a>
<?php else : ?>
<div style="overflow: hidden; width: 90%; margin: 0 auto">
  <div class="alert alert-warning"><?php echo $message; ?></div>
</div>
<?php endif; ?>
<script type="text/javascript" language="javascript" charset="utf-8">
  $(document).ready(function() {
  });  
  // Called once the server replies to the ajax form validation request
  function ajaxValidationCallback(status, form, json, options) {}
</script>