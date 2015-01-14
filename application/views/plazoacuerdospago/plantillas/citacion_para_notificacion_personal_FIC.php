<?php 
echo '<center>CITACIÓN NOTIFICACIÓN PERSONAL FIC:
	Código Regional  '.$consulta['COD_CIUDAD'].'<br>
	Ciudad,   </center><br>
'.$consulta['REPRESENTANTE_LEGAL'].'<br>
'.$consulta['NOMBRE_EMPRESA'].'<br>       
Nit. '.$consulta['NITEMPRESA'].'<br>
Domicilio
Nº FAX Nº '.$post['fax'].'
Correo '.$post['correo'].'
Ciudad<br>
	<center>Asunto: CITACIÓN para Notificación Personal  </center><p>


Respetado  Señor(a) '.$consulta['REPRESENTANTE_LEGAL'].':<p>

De la manera más atenta le solicitamos comparecer dentro de los cinco (5)  días  hábiles siguientes, contados a partir del dia hábil siguiente a la fecha de recibo de la presente citación, con el fin de notificarse personalmente  ante el Grupo de Relaciones Corporativas e Internacionales de la Regional '.$consulta['NOMBRE_REGIONAL'].' ubicada  en la (escribir dirección exacta) de la Resolución  No. '.$consulta['NUMERO_RESOLUCION'].'  del '.$consulta['FECHA_CREACION'].'.<p>
 
Para notificarse debe cumplir los siguientes requisitos:<p>

1.	Si es Representante Legal: Certificado de la Cámara de Comercio con vigencia no mayor a 3 meses.<br>
2.	Si es Apoderado(a): Presentar respectivo poder y la tarjeta profesional de abogado.<br>
3.	Si es Autorizado: La respectiva autorización, acompañada del Certificado de la Cámara de Comercio en donde se acredite la calidad de quien otorga la autorización. <br>
En caso de no comparecer a esta citación, al cabo de los cinco (5) días siguientes del envío de la presente comunicación, el Servicio Nacional de Aprendizaje procederá a notificar la mencionada resolución mediante Aviso, en cumplimiento a lo dispuesto en el artículo 69 del Código de Procedimiento Administrativo y de lo Contencioso Administrativo.-C.P.A.C.A.( Ley 1437 de 2011).<p>
Cordial saludo,<p>
<br>
Coordinador/a  '.$consulta['COORDINADOR_REGIONAL'].'<br>
Grupo de Relaciones Corporativas  e Internacionales<p>

Elaborado por: '.$info_user.'<br>
Revisado por: '.$info_user.'<br>

';

?>