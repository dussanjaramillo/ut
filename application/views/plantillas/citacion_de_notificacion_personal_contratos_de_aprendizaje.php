<?php
if(!isset($post['fax']))
    $post['fax']='';
if(!isset($post['correo']))
    $post['correo']='';
echo '<center>Código Regional '.$consulta['COD_CIUDAD'].'<br> 
	    Ciudad,</center> <br>  
Señor (a):<br>
'.$consulta['REPRESENTANTE_LEGAL'].'<br>                           
'.$consulta['NOMBRE_EMPRESA'].'<br>                                                 
NIT '.$consulta['NITEMPRESA'].'<br>
Dirección '.$consulta['DIRECCION'].'<br>
Teléfono: '.$consulta['TELEFONO_FIJO'].'<br>
Correo: '.$post['correo'].'<br>                                                                      
'.$consulta['NOMBRE_REGIONAL'].'<p>
    
Asunto:  Citación para Notificación Personal cuota de aprendices<p>
Respetado Señor: '.$consulta['REPRESENTANTE_LEGAL'].'<p> 

Queremos invitarlo a iniciar el proceso de vinculación al proyecto de responsabilidad social más importante de nuestra institución “El Contrato de Aprendizaje” y empezar a ser parte de las empresas beneficiarias, fortaleciendo su capital humano, dando oportunidad a los jóvenes de nuestro país y apoyando al gobierno en su política estratégica de empleo. <p>

El Servicio Nacional de Aprendizaje SENA, le agradece presentarse en nuestras oficinas ubicadas en la '.$consulta['DIRECCION_REGIONAL'].' Piso __________; Oficina de Relaciones Corporativas e Internacionales de esta ciudad, en el horario de 8 am a 5 pm de lunes a viernes, dentro de los cinco (5) días siguientes al recibo de esta comunicación, lo anterior con el fin de notificarlo de la Résolucion  No. '.$consulta['NUMERO_RESOLUCION'].'  del '.$consulta['FECHA_CREACION'].', mediante la cual se regula la cuota de aprendices a la empresa que Usted representa.<br>
Para notificarse debe cumplir los siguientes requisitos:<br>
1.	Si es Representante Legal: Certificado de la Cámara de Comercio con vigencia no mayor a 3 meses.
2.	Si es Apoderado(a): Presentar respectivo poder y la tarjeta profesional de abogado.
3.	Si es Autorizado: La respectiva autorización, acompañada del Certificado de la Cámara de Comercio en donde se acredite la calidad de quien otorga la autorización. 
Si la empresa que usted representa es una entidad Estatal deberá aportar el decreto o resolución de nombramiento, el acta de posesión y el certificado de vigencia del mismo.  Adicionalmente debe presentar el documento de identificación personal, es preciso aclarar que para las personas naturales como para los representantes legales de cualquier tipo de empresa, es la cédula de ciudadanía.
 
En caso de no comparecer a esta citación, al cabo de los cinco (5) días siguientes del envío de la presente comunicación, el Servicio Nacional de Aprendizaje procederá a notificar la mencionada resolución mediante Aviso, en cumplimiento a lo dispuesto en el artículo 69 del Código de Procedimiento Administrativo y de lo Contencioso Administrativo.-C.P.A.C.A. ( Ley 1437 de 2011).<p>

Cordial saludo, <br>
'.$consulta['COORDINADOR_REGIONAL'].'<br>
Coordinador /a Relaciones Corporativas e Internacionales<br>
<p>

Elaborado por: '.$info_user.'<br>
Revisado por: '.$info_user.'<br>

';
?>