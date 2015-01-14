
<?php

$body = '<table align="center" style="border: 3px solid; width: 900px; height: 80px">';
$body .= '<tr><td><br><br>';
$body .= '<div align="center">';
$body .= '<img src="'. base_url("img/sena.jpg") .'" style="width: 90px;height: 90px">';
$body .= '<br>'. $moramenor->result_array[0]["NOMBRE_REGIONAL"].'</div>';
$body .= '<br><div align="center">11-1080<br>Bogota D.C.,</div><br><br>';
$body .= '<div style="margin: 30px; ">';
$body .= 'Doctor(a)<br>';                           
$body .= $moramenor->result_array[0]["REPRESENTANTE_LEGAL"];
$body .= '<br> REPRESENTANTE LEGAL <br>';
$body .=  $moramenor->result_array[0]["DIRECCION"];
$body .= '<br>Bogota D.C.</div>';
$body .= '<div align="right" style="margin: 30px; "><b>Asunto:</b>	Incumplimiento Acuerdo de Pago No. '.$moramenor->result_array[0]["NRO_ACUERDOPAGO"].'</div>';
$body .= '<div style="text-align: justify; margin: 30px; ">';
$body .= 'Apreciado doctor '. $moramenor->result_array[0]["REPRESENTANTE_LEGAL"].' :<br><br>';
$body .= 'Atentamente Ie informo que revisada la base de datos de Cartera a la fecha aparece pendiente por
    cancelar un saldo por valor de '. $info .' M/CTE. ('. $moramenor->result_array[0]["PROYACUPAG_CAPITALDEBE"].' ) del Acuerdo de Pago No.
    '. $moramenor->result_array[0]["NRO_ACUERDOPAGO"].', suscrito y firmado por el Representante Legal y el Director de la Regional Distrito Capital,
    correspondiente a la resolucion '. $moramenor->result_array[0]["NRO_RESOLUCION"] .', firmado el dia '. date("d").' del  mes de '. date("m") .' de '. date("Y") .'
    <p>';
$body .= 'Por lo tanto, solicitamos remitir las copias de las consignaciones efectuadas a la XXXXX o al correo
    electrónico '. $moramenor->result_array[0]["CORREOELECTRONICO"] .' dentro de los cinco (5) días siguientes al recibo de esta comunicacion
    <p>';
$body .= 'En caso contrario se dara cumplimiento a la Clausula Aceleratoria del Acuerdo de Pago la cual reza:
    "Clausula Aceleratoria. En el evento en que el señor '. $moramenor->result_array[0]["REPRESENTANTE_LEGAL"] .' en su calidad de representante legal de la
    empresa '. $moramenor->result_array[0]["NOMBRE_EMPRESA"].' , no pague oportunamente dos (2) o mas cuotas fijadas, no pague la totalidad de las
    mismas, o no acredite el pago dentro de las fechas señaladas, el Servicio Nacional de Aprendizaje _
    SENA, Direccion '. $moramenor->result_array[0]["NOMBRE_REGIONAL"].', declarara el incumplimiento del presente acuerdo de pago y hara
    efectiva la garantia presentada por el deudor, continuando el cobro coactivo, de no ser satisfecha en su
    totalidad la obligación por el pago del garante".
    <p>';
$body .= 'El pago debera efectuarse ingresando a la pagina Web del SENA (www.sena.edu.co)  link pagos en linea
    y escogiendo la modalidad de pago puede ser por IMPRESION DE CUPON Y PAGO POR VENTANILLA
    O PAGO ELECTRONICO POR INTERNET a nombre del Servicio Nacional de Aprendizaje - SENA.
    <p>';
$body .= 'Cualquier duda favor comunicarse con la señora '. $moramenor->result_array[0]["NOMBRES"] ." ". $moramenor->result_array[0]["APELLIDOS"].', al telefono '. $moramenor->result_array[0]["TELEFONO_REGIONAL"].' o
    al correo electronico '. $moramenor->result_array[0]["EMAIL_REGIONAL"].'  dentro de los cinco (5) días siguientes a la presente comunicacion.
    <p>';
$body .= 'La falta de Interes e incumplimiento sera entendida como una negativa a conciliar y un aceleramiento a la
    via coactiva.
    <p>'; 
$body .= 'Cordialmente,
</div>';
$body .= '<div align="right" style="margin: 30px; ">
    XXXXXXXXXXXXXXXXXXXXXXXXXXX<br>
    Coordinador de Relaciones Corporativas e<br>
    Internacionales<br>
</div>';
$body .= '<br>
<div style="margin: 30px; "><h6>Proyecto: XXXXXXXXXXXXXXXXXX</h6></div>
<br>
<br>';
$body .= '<div align="center" style="margin: 30px; ">
    SENA: de Clase Mundial<br>
    Ministerio del Trabajo<br>
    SERVICIO NACIONAL DE APRENDIZAJE<br>
    Dirección: xxxxxxxxxxxx    Apartado xxxx  PBX  xxxx   xxx   Bogotá, D.C. – Colombia<br><br>

</div>';
$body .= '</td>
</tr>
</table>';


if(empty($existenacia)){

$correousuario = 'javierbr12@hotmail.com';
$mensaje = 'Hola';
$asunto = 'ACUERDO DE PAGO SENA - CUOTA PASADA';
$copia = '';
$adjunto ='';
$this->load->helpers('traza_fecha_helper');
enviarcorreosena($correousuario, $body, $asunto, $copia, $adjunto);
}else{
    echo $body;
}

?>