<?php 
echo '<center>NOTIFICACIÓN POR AVISO FIC:<br>
            Código Regional'.$consulta['COD_CIUDAD'].'<p></center>
Ciudad,
Doctor (a).
'.$consulta['REPRESENTANTE_LEGAL'].'<br>    
Empresa: '.$consulta['NOMBRE_EMPRESA'].'<br> 
NIT '.$consulta['NITEMPRESA'].'<br>
Dirección '.$consulta['DIRECCION'].'<br>
Ciudad 
Asunto: Notificación  por aviso           
LA DIRECCION DE LA REGIONAL '.$consulta['NOMBRE_REGIONAL'].' COORDINACIÓN DE RELACIONES CORPORATIVAS E INTERNACIONALES DEL SENA

HACE SABER:

Que dentro del término legal establecido en la citación  enviada mediante radicado Nº '.$consulta['NUMERO_RESOLUCION'].'  del '.$consulta['FECHA_CREACION'].', no se presentó el representante legal, autorizado o apoderado de la empresa '.$consulta['REPRESENTANTE_LEGAL'].' identificada con NIT '.$consulta['NITEMPRESA'].' para surtir la notificación personal de la Resolución No. '.$consulta['NUMERO_RESOLUCION'].'  del '.$consulta['FECHA_CREACION'].', por la cual se ordena el pago de una  obligación dineraria a favor del Servicio Nacional de Aprendizaje-SENA por incumplimiento en el pago de FIC, en cumplimiento a lo dispuesto en su artículo segundo, en cumplimiento del Artículo 69 del Código de Procedimiento Administrativo y de lo Contencioso Administrativo, se fija el presente AVISO en un  lugar público  de la Regional XXXX del SENA, por el término de cinco (5) días hábiles contados  a partir del día  (_______)  hasta el día  (_________), en la ciudad de.<p> 
CONSTANCIA DE SURTIDA LA NOTIFICACION PERSONAL: La presente notificación se considera surtida el día  SIGUIENTE A LA FECHA DE DESFIJACION DEL AVISO, ……….. y  en la ciudad de xxxxx

'.$consulta['COORDINADOR_REGIONAL'].'<br>
Coordinador Relaciones Corporativas e Internacionales<br>
Proyectó: '.$info_user.'<br>
Revisó: '.$info_user.'<br>
SENA: UNA ENTIDAD DE CLASE MUNDIAL<br>
Ministerio del Trabajo<br>
SERVICIO NACIONAL DE APRENDIZAJE<br>
REGIONAL '.$consulta['NOMBRE_REGIONAL'].'<p>
';
?>