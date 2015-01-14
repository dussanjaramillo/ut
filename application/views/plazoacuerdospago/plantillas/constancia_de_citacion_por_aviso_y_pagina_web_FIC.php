<?php 
echo 'CONSTANCIA DE NOTIFICACIÓN POR AVISO FIC:<p>

Señores Coordinadores: Este aviso que se fija en la Regional, aplica para los procesos de Aportes y FIC  que se iniciaron bajo la vigencia de la Ley 1437 de 2011.


LA DIRECCION DE LA REGIONAL '.$consulta['NOMBRE_REGIONAL'].'<p> COORDINACIÓN DE RELACIONES CORPORATIVAS E INTERNACIONALES DEL SENA

HACE SABER:

Que dentro del término legal establecido en la citación  enviada mediante radicado Nº de  fecha xxxxxxxx, no se presentó el representante legal, autorizado o apoderado de la empresa '.$consulta['NOMBRE_EMPRESA'].' identificada con NIT '.$consulta['NITEMPRESA'].' para surtir la notificación personal de la Resolución No. '.$consulta['NUMERO_RESOLUCION'].'  del '.$consulta['FECHA_CREACION'].', por la cual se ordena el pago de una  obligación dineraria a favor del Servicio Nacional de Aprendizaje-SENA por incumplimiento en el pago de FIC, en cumplimiento a lo dispuesto en su artículo segundo, en cumplimiento del Artículo 69 del Código de Procedimiento Administrativo y de lo Contencioso Administrativo, se fija el presente AVISO en un  lugar público  de la Regional '.$consulta['NOMBRE_REGIONAL'].' del SENA, por el término de cinco (5) días hábiles contados  a partir del día  (_____)  hasta el día  (_____), en la ciudad de .<p> 
CONSTANCIA DE SURTIDA LA NOTIFICACION PERSONAL: La presente notificación se considera surtida el día  SIGUIENTE A LA FECHA DE DESFIJACION DEL AVISO, ……….. y  en la ciudad de xxxxx

'.$consulta['COORDINADOR_REGIONAL'].'<br>
Coordinador Relaciones Corporativas e Internacionales

Proyectó: '.$info_user.'<br>
Revisó: '.$info_user.'<p>


                        SENA: UNA ENTIDAD DE CLASE MUNDIAL
Ministerio del Trabajo

';
?>