<?php

$attributes = array("id" => "myform");
echo form_open("remisibilidad/Generar_FichaCondiciones", $attributes);
$fecha_hoy = date("Y/m/d H:i:s", strtotime("-6 hour"));
echo form_hidden('fecha', $fecha_hoy);
echo form_hidden('cod_remisibilidad', $cod_remisibilidad);
$linea_codiciones = '';
for ($i = 0; $i < sizeof($condiciones); $i++) {
    $linea_codiciones = $linea_codiciones . '<tr>';
    $linea_codiciones = $linea_codiciones . '<td height="37" colspan="2"><br>' . $condiciones[$i]["NOMBRE_CONDICION"] . '<br></td>';
    $linea_codiciones = $linea_codiciones . '</tr>';
}
$linea_documentos = '';
for ($i = 0; $i < sizeof($documentos); $i++) {
    $nombre=$documentos[$i]["NOMBRE_DOCUMENTO"];
    $linea_documentos = $linea_documentos . '<a href="'.base_url('uploads/remisibilidad/'.$cod_fiscalizacion.'/Expediente/'.$nombre.'').'"><p>' . $documentos[$i]["NOMBRE_DOCUMENTO"] . '</a></p>';
}
$columna_documentos = '';
for ($i = 0; $i < sizeof($documentos); $i++) {
    $nombre=$documentos[$i]["NOMBRE_DOCUMENTO"];
    $columna_documentos = $columna_documentos . '<tr div align="left">';
    $columna_documentos = $columna_documentos . '<td colspan="2" div align="left"><br>' . $documentos[$i]["NOMBRE_DOCUMENTO"] . '<br></td>';
    $columna_documentos = $columna_documentos . '</tr>';
}
$html = '
 <div align="center" style="background: white;border-color: black; border: 1px solid grey; margin: auto; overflow: hidden">
 <br><br>
  <table width="90%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="14%" rowspan="2"><img src="' . base_url('img/Logotipo_SENA.png') . '" width="248" height="239" /></td>
      <td height="90" colspan="4"><div align="center"><h1>SUBCOMITÉ DE DEPURACION CONTABLE</h1></div></td>
      <td width="10%"><table width="100%" style="border-color:#000" border="1" cellspacing="0" cellpadding="0">
        <tr>
          <td height="26">' . $cod_regional . '</td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td width="15%" height="136"><div align="right">
      <h3 align="center">Regional  </h3></div></td>
      <td width="33%"><table width="100%" style="border-color:#000" border="1" cellspacing="0" cellpadding="0">
        <tr>
          <td height="34">' . $regional . '</td>
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
  <p>&nbsp;</p>
  <table width="90%" border="1" cellspacing="0" cellpadding="0">
    <tr>
      <td height="50" colspan="4" bgcolor="#FDA773"><div align="center"><h3>INFORMACION CONTABLE</h3></div></td>
    </tr>
    <tr>
      <td width="22%" height="40"><b>Nombre de la Instancia</b></td>
      <td colspan="3">' . $instancia . '</td>
    </tr>
    <tr>
      <td height="40"><b>Nombre de la Cuenta Contable</b></td>
      <td width="52%">' . $cuenta_contable . '</td>
      <td width="9%"><div align="right"><b>Codigo:</b></div></td>
      <td width="17%">' . $cod_cuenta_contable . '</td>
    </tr>
    <tr>
      <td height="40"><b>Nombre de la Empresa</b></td>
      <td>' . $razonsocial . '</td>
      <td><div align="right"><b>Nit:</b></div></td>
      <td>' . $nit . '</td>
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
          <td width="66%" height="65"><h3 align="right">VALOR SOLICITADO COMO REMISIBILIDAD</h3></td>
          <td width="3%" height="65">&nbsp;</td>
          <td width="31%"><h2>' . $liquidacion . '</h2></td>
          </tr>
      </table></td>
    </tr>
  </table>
  <table width="90%" border="1" cellpadding="0" cellspacing="0">
    <tr>
      <td height="62" colspan="2" bgcolor="#FDA773"><div align="center"><strong><h3>MOTIVO DE LA REMISIBILIDAD</h3></strong></div></td>
    </tr>
' . $linea_codiciones . '
    <tr>
      <td height="37" colspan="2" bgcolor="#FDA773"><div align="center"><strong><h3>ACCIONES Y DOCUMENTOS ANEXOS QUE SOPORTAN LA SOLICITUD DE REMISIBILIDAD</h3></strong></div></td>
    </tr>
   ' . $columna_documentos . '
    <tr>
      <td width="83%" height="37"><div align="center"><strong>CANTIDAD DE ANEXOS PRESENTADOS, ADJUNTOS A ESTA FICHA:</strong></div></td>
      <td width="17%">' . sizeof($documentos) . '</td>
    </tr>
  </table>
  <br>
</div>';
echo $html;
echo '<br>';
echo '<br>';
echo '<center>';
$data = array(
    'name' => 'boton_aprobar',
    'id' => 'boton_aprobar',
    'value' => 'aprobo',
    'type' => 'submit',
    'content' => '<i class="fa fa-file-text-o"></i> Aprobar',
    'class' => 'btn btn-success'
);
echo form_button($data) . '  ';
$data = array(
    'name' => 'boton_naprobar',
    'id' => 'boton_naprobar',
    'value' => 'no_aprobo',
    'type' => 'submit',
    'content' => '<i class="fa fa-print"></i> No Aprobar',
    'class' => 'btn btn-danger'
);
echo form_button($data);
echo form_close();
echo '</center>';
//remisibilidad::pdf($html,'Ficha_Registro_Condiciones');
?>