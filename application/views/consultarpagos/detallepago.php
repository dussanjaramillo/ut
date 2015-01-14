<div style="border: 1px solid grey; overflow: hidden; padding: 9px 0" class="row show-grid">
  <span class="span2"><strong>NIT:</strong> <?php echo $empresa['CODEMPRESA'] ?></span>
  <span class="span3"><strong>RAZÓN SOCIAL:</strong> <?php echo $empresa['NOMBRE_EMPRESA']; ?></span>
  <span class="span3"><strong>DIRECCIÓN:</strong> <?php echo $empresa['DIRECCION']; ?></span>
  <span class="span2"><strong>TELÉFONO:</strong> <?php echo (!empty($empresa['TELEFONO_FIJO'])) ? $empresa['TELEFONO_FIJO'] : $empresa['TELEFONO_CELULAR']; ?></span>
</div><br>
<div style="border: 1px solid grey; overflow: hidden; padding: 9px 0" class="row show-grid">
  <div class="text-center">
  	<h2><?php echo $title; ?></h2>
  	<?php
      if (isset($stitle)):
        ?>
		<h3 class="titulo"><?php echo $stitle ?></h3>
		<?php endif; ?>
  </div>
  <?php
  $x = 1;
  foreach ($pagos as $pago) :
    ?>
    <table class="table-bordered table-striped" width="50%" align="center" style="margin: 20px auto;">
      <tr>
        <td style="padding: 3px;">Número de soporte de pago</td><td style="padding: 3px;"><?php echo $pago['NUM_DOCUMENTO'] ?></td>
      </tr>
      <tr>
        <td style="padding: 3px;">Valor del pago</td><td style="padding: 3px;"><a href="#ver_detalle" data-id="<?php echo $x; ?>" class="ver_detalle" data-target="#detalle<?php echo $x; ?>">$ <div style="float: right"><?php echo number_format($pago['VALOR_RECIBIDO'], 0, ",", ".") ?></div></a></td>
      </tr>
      <tr>
        <td style="padding: 3px;">Fecha Aplicación de pago</td><td style="padding: 3px;"><?php echo $pago['FECHA_APLICACION'] ?></td>
      </tr>
      <tr>
        <td style="padding: 3px;">Fecha Real de pago</td><td style="padding: 3px;"><?php echo $pago['FECHA_PAGO'] ?></td>
      </tr>
      <tr>
        <td style="padding: 3px;">Forma de pago</td><td style="padding: 3px;"><?php echo $pago['NOMBE_FORMAPAGO'] ?></td>
      </tr>
      <tr>
        <td style="padding: 3px;">Concepto del pago</td><td style="padding: 3px;"><?php echo $pago['NOMBRE_TIPO'] ?></td>
      </tr>
      <tr>
        <td style="padding: 3px;">Sub Concepto del pago</td><td style="padding: 3px;"><?php echo $pago['NOMBRE_CONCEPTO'] ?></td>
      </tr>
      <tr>
        <td style="padding: 3px;">Medio de pago</td><td style="padding: 3px;"><?php echo $pago['PROCEDENCIA'] ?></td>
      </tr>
    </table>
    <?php if ($pdf == false) : ?>
    <div id="detalle<?php echo $x; $x++; ?>" class="modal hide fade">
      <div class="modal-body">
        <div style="overflow: hidden;">
          <div class="span2"><strong>Concepto</strong></div>
          <div class="span2" id="detconcepto"><?php echo $pago['NOMBRE_TIPO'] ?></div>
        </div>
        <div style="overflow: hidden;">
          <div class="span2"><strong>Sub Concepto</strong></div>
          <div class="span2" id="detconcepto"><?php echo $pago['NOMBRE_CONCEPTO'] ?></div>
        </div>
        <div style="overflow: hidden;">
          <div class="span2"><strong>No. de referencia</strong></div>
          <div class="span2" id="detreferencia"><?php echo $pago['NUM_LIQUIDACION'] ?></div>
        </div>
        <div style="overflow: hidden;">
          <div class="span2"><strong>Total</strong></div>
          <div class="span2"><?php echo number_format($pago['VALOR_RECIBIDO'], 2, ",", "."); ?></div>
        </div>
        <div style="overflow: hidden; margin-top: 20px">
          <table cellpading="0" cellspacing="0" border="0" class="table-bordered table" style="margin: 0 auto">
            <thead>
              <tr class="btn-success">
                <th style="text-align: center">Componente Concepto</th>
                <th style="text-align: center">Valor abonado</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Capital</td>
                <td><?php echo number_format(str_replace(",", ".", $pago['DISTRIBUCION_CAPITAL']), 2, ",", "."); ?></td>
              </tr>
              <?php if(!empty($pago['DISTRIBUCION_INTERES'])) : ?>
              <tr>
                <td>Interes Corriente</td>
                <td><?php echo number_format(str_replace(",", ".", $pago['DISTRIBUCION_INTERES']), 2, ",", "."); ?></td>
              </tr>
              <?php endif; ?>
              <?php if(!empty($pago['DISTRIBUCION_INTERES_MORA'])) : ?>
              <tr>
                <td>Interes de mora</td>
                <td><?php echo number_format(str_replace(",", ".", $pago['DISTRIBUCION_INTERES_MORA']), 2, ",", "."); ?></td>
              </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <?php endif; ?>
    <?php
  endforeach;
  ?>
<?php if ($pdf == false) : ?>
    <div class="text-center">
      <a class="btn btn-info exportar" data-id="excel" href="<?php echo base_url('index.php/consultarpagos/detallepago/' . $empresa['CODEMPRESA'] . "/" . $pagos[0]['COD_CONCEPTO'] . "/" . $periodo_inicio . "/" . $periodo_fin . "/excel") ?>">
        <i class="fa fa-file-excel-o fa-lg"></i> Exportar a Excel
      </a>
      <a class="btn btn-info exportar" data-id="pdf" href="<?php echo base_url('index.php/consultarpagos/detallepago/' . $empresa['CODEMPRESA'] . "/" . $pagos[0]['COD_CONCEPTO'] . "/" . $periodo_inicio . "/" . $periodo_fin . "/pdf") ?>">
        <i class="fa fa-file-pdf-o fa-lg"></i> Exportar a PDF
      </a>
      <a class="btn btn-default" href="<?php echo base_url('index.php/consultarpagos/manage') ?>"><i class="fa fa-arrow-circle-left fa-lg"></i> Regresar</a>
    </div>
<?php endif; ?>
</div>
<?php if ($pdf == false) : ?>
  <script type="text/javascript" language="javascript" charset="utf-8">
    $(document).ready(function() {
      //$(".ver_Detalle").each(function() {
      $(".ver_detalle").click(function() {
        var id = $(this).attr("data-target");
        $(id).modal();
      });
      //});
    });
    // Called once the server replies to the ajax form validation request
    function ajaxValidationCallback(status, form, json, options) {
    }
  </script>
<?php endif; ?>