<?php
echo 'CONSTANCIA DE NOTIFICACIÓN POR AVISO APORTES PARAFISCALES:<p>

Señores Coordinadores: Este aviso que se fija en la Regional, aplica para los procesos de aportes y FIC que se iniciaron bajo la vigencia de la Ley 1437 de 2011.<p>
<br>
LA DIRECCION DE LA REGIONAL '.$consulta['NOMBRE_REGIONAL'].' COORDINACIÓN DE RELACIONES CORPORATIVAS E INTERNACIONALES DEL SENA


HACE SABER:

Que dentro del término legal establecido en la citación  enviada mediante radicado Nº '.$consulta['NUMERO_RESOLUCION'].'  del '.$consulta['FECHA_CREACION'].', no se presentó el representante legal, autorizado o apoderado de la empresa '.$consulta['REPRESENTANTE_LEGAL'].' identificada con NIT '.$consulta['NITEMPRESA'].' para surtir la notificación personal de la Resolución No. Xxxx del xxxx de 20xxx, por la cual se ordena el pago de una  obligación dineraria a favor del Servicio Nacional de Aprendizaje-SENA por incumplimiento en el pago de Aportes Parafiscales, en cumplimiento a lo dispuesto en su artículo segundo, en cumplimiento del Artículo 69 del Código de Procedimiento Administrativo y de lo Contencioso Administrativo, se fija el presente AVISO en un  lugar público  de la Regional '.$consulta['NOMBRE_REGIONAL'].' del SENA, por el término de cinco (5) días hábiles contados  a partir del día  (____________)  hasta el día  (______, en la ciudad de xxxxx.<p> 
CONSTANCIA DE SURTIDA LA NOTIFICACION PERSONAL: La presente notificación se considera surtida el día  SIGUIENTE A LA FECHA DE DESFIJACION DEL AVISO, ……….. y  en la ciudad de xxxxx<p>

'.$consulta['COORDINADOR_REGIONAL'].'<br>
Coordinador Relaciones Corporativas e Internacionales<p>

Proyectó: '.$info_user.'<br>
Revisó: '.$info_user.'<br>
    ';
?>