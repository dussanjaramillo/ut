<?php
$valor_deuda = 0;
for ($i = 0; $i < sizeof($info_deudor); $i++) {
    $valor_deuda = $valor_deuda + $info_deudor[$i]['VALOR_DEUDA'];
}

$attributes = array("id" => "myform");
echo form_open("remisibilidad/pdf_sin_pie", $attributes);
$linea_codiciones = '';
for ($i = 0; $i < sizeof($condiciones); $i++) {
    $linea_codiciones = $linea_codiciones . '<tr>';
    $linea_codiciones = $linea_codiciones . '<td height="37" colspan="2">' . $condiciones[$i] . '<br></td>';
    $linea_codiciones = $linea_codiciones . '</tr>';
}
$linea_documentos = '';
$columna_documentos = '';
if ($documentos != '') {
    for ($i = 0; $i < sizeof($documentos); $i++) {
        $linea_documentos = $linea_documentos . '<p>' . $documentos[$i] . '</p>';
    }
    for ($i = 0; $i < sizeof($documentos); $i++) {
        $columna_documentos = $columna_documentos . '<tr div align="left">';
        $columna_documentos = $columna_documentos . '<td colspan="2" div align="left"><br>' . $documentos[$i] . '<br></td>';
        $columna_documentos = $columna_documentos . '</tr>';
    }
    $cantidad_documentos = sizeof($documentos);
} else {
    $cantidad_documentos = 0;
    $linea_documentos = 'Ninguno';
}
$html = '<div align="center" style="background: white;  border-color: black; border: 1px solid grey; margin: auto; overflow: hidden">
    <br><br>
  <table width="90%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="14%" rowspan="2"><img src="' . base_url('img/Logotipo_SENA.png') . '" width="248" height="239" /></td>
      <td height="90" colspan="4"><div align="center"><h1>'.$titulo.'</h1></div></td>
      <td width="10%"><table width="100%" style="border-color:#000" border="1" cellspacing="0" cellpadding="0">
        <tr>
          <td height="26">' . $info_deudor[0]['COD_REGIONAL'] . '</td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td width="15%" height="136"><div align="right">
      <h3 align="center">Regional  </h3></div></td>
      <td width="33%"><table width="100%" style="border-color:#000" border="1" cellspacing="0" cellpadding="0">
        <tr>
          <td height="34">' . $info_deudor[0]['NOMBRE_REGIONAL'] . '</td>
        </tr>
      </table></td>
      <td width="15%"><div align="center"><h3>Ficha N°</h3></div></td>
      <td colspan="2"><table width="100%" style="border-color:#000" border="1" cellspacing="0" cellpadding="0">
        <tr>
          <td height="37">' . $num_consecutivo . '</td>
          </tr>
      </table></td>
    </tr>
  </table>
  <br>
  <table width="90%" border="1" cellspacing="0" cellpadding="0">
    <tr>
      <td height="50" colspan="4" bgcolor="#E6E6E6"><div align="center"><h3>INFORMACION CONTABLE</h3></div></td>
    </tr>
    <tr>
      <td height="40"><b>Nombre de la Cuenta Contable</b></td>
      <td width="52%">' . $cuenta_contable . '</td>
      <td width="9%"><div align="right"><b>Codigo:</b></div></td>
      <td width="17%">' . $cod_cuenta_contable . '</td>
    </tr>
    <tr>
      <td height="40"><b>Nombre de la Empresa</b></td>
      <td>' . $info_deudor[0]['EJECUTADO'] . '</td>
      <td><div align="right"><b>Nit:</b></div></td>
      <td>' . $info_deudor[0]['IDENTIFICACION'] . '</td>
    </tr>
    <tr>
      <td height="40"><b>No. del Asiento Contable y Fecha</b></td>
      <td colspan="3">' . $asiento . '  -  ' . $fecha_asiento . '</td>
    </tr>
    <tr>
      <td height="65"><b>Documentos que soportan la solicitud</b></td>
      <td colspan="3">
      ' . $linea_documentos . '
      </td>
      
    </tr>
  </table>
  <table width="90%"  border="1" cellspacing="0" cellpadding="0">
    <tr>
      <td height="37"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="66%" height="50"><h3 align="right">VALOR SOLICITADO COMO REMISIBILIDAD </h3></td>
          <td width="31%"><h2>$'  . number_format($valor_deuda, 0, '.', '.') . '</h2></td>
          </tr>
      </table></td>
    </tr>
  </table>
  <table width="90%" border="1" cellpadding="0" cellspacing="0">
    <tr>
      <td height="62" colspan="2" bgcolor="#E6E6E6"><div align="center"><strong><h3>MOTIVO DE LA REMISIBILIDAD</h3></strong></div></td>
    </tr>
' . $linea_codiciones . '
    <tr>
      <td height="37" colspan="2" bgcolor="#E6E6E6"><div align="center"><strong><h3>ACCIONES Y DOCUMENTOS ANEXOS QUE SOPORTAN LA SOLICITUD DE REMISIBILIDAD</h3></strong></div></td>
    </tr>
   ' . $columna_documentos . '
    <tr>
      <td width="83%" height="37"><div align="center"><strong>CANTIDAD DE ANEXOS PRESENTADOS, ADJUNTOS A ESTA FICHA:</strong></div></td>
      <td width="17%">' . $cantidad_documentos . '</td>
    </tr>
  </table><br>
</div>';
echo $html;
echo '<br>';
echo '<br>';
echo '<center>';
$data = array(
    'name' => 'button',
    'id' => 'Generar',
    'value' => 'seleccionar',
    'type' => 'submit',
    'content' => '<i class="fa fa-file-text-o"></i> Generar PDF',
    'class' => 'btn btn-success generar'
);
echo form_button($data) . '  ';
$data = array(
    'name' => 'button',
    'id' => 'Imprimir',
    'value' => 'seleccionar',
    'content' => '<i class="fa fa-print"></i> Imprimir',
    'class' => 'btn btn-success generar',
    'onClick' => 'window.print();'
);
echo form_button($data);
echo form_hidden('html', $html);
echo form_hidden('nombre', 'Ficha_Registro_Condiciones' . $num_consecutivo);
echo form_close();
echo '</center>';
?>