<?php

$linea1 = '<b>RESOLUCION No.	' . $consulta[0]['NUMERO_RESOLUCION'] . '	 DEL ' . $consulta[0]['FECHA_CREACION'] . '</b>';
$linea2 = '<b>POR MEDIO DE LA CUAL SE ORDENA EL PAGO DE UNA  OBLIGACION DINERARIA A FAVOR DEL FONDO NACIONAL DE FORMACIÓN PROFESIONAL DE LA INDUSTRIA DE LA CONSTRUCCIÓN-FIC-SENA POR INCUMPLIMIENTO EN EL PAGO DE LA CONTRIBUCION FIC.</b>';
$linea3 = 'El (la) Director(a) de la Regional ' . $informacion['NOMBRE_REGIONAL'] . ' del Servicio Nacional de Aprendizaje - SENA en uso de sus facultades legales previstas en numeral 9 del artículo 24 del Decreto 249 de 2004 y las delegadas por el Director General mediante Resolución 770 del 11 de Julio de 2001, y';
$linea4 = '<b>CONSIDERANDO:</b>';
$linea5 = '<p>Que el artículo 6 del Decreto 2375 de 1974 por el cual se dictan medidas destinadas a combatir el desempleo, establece: “Exonerase a la industria de la construcción de la obligación que, conforme a las disposiciones vigentes, tiene de contratar aprendices. // En su lugar, créase el Fondo Nacional de Formación Profesional de la Industria de la Construcción a cargo de los empleadores de ese ramo de la actividad económica, quienes deberán contribuir mensualmente al mismo con una suma igual a una vez el salario mínimo por cada cuarenta (40) trabajadores que laboren bajo sus órdenes.// El Fondo será administrado por el Servicio Nacional de Aprendizaje con la asesoría de la Cámara Colombiana de la Construcción y con cargo a él se atenderá el pago de la proporción salarial que corresponda a los aprendices que reciben formación profesional en los diversos oficios de la industria de la construcción”<p>';
$linea6 = 'Que el artículo 7  del Decreto 083 de 1976, que reglamenta el Decreto-Ley  2375 de 1974, señala: “Entiéndase por personas dedicadas a la industria de la construcción para los efectos del Decreto 2375 de 1974, quienes ocasional o permanentemente, por su cuenta o la de un tercero, erigen o levantan estructuras inmuebles como construcción de casas o edificios, vías de comunicación, oleoductos, gasoductos, canalización, alcantarillado, acueducto, pavimentos, obras de desecación, riego y embalses, instalaciones eléctricas y mecánicas y demás construcciones civiles no mencionadas y quienes trabajan en el mantenimiento y reparación de dichas obras. <p>';
$linea7 = 'Que el artículo 8 del precitado Decreto consagra que: “Entiéndase por "valor de las obras" para los efectos del Artículo 4 del Decreto 2375 de 1974, el que resulte de la suma de todos los pagos que con cargo a una obra determinada hagan su propietario o el contratista. // Anualmente los propietarios de obras, ya sean personas naturales, jurídicas o entidades de derecho público, certificarán con destino al Servicio Nacional de Aprendizaje, SENA, el valor total que con cargo a cada obra, cualquiera que sea su naturaleza, fue pagado al contratista o constructor durante el año fiscal. <p>';
$linea8 = 'Este valor no podrá ser inferior a aquel por el cual se solicitó licencia de construcción. Exceptúense los gastos de financiación, impuestos e indemnizaciones a terceros, lo mismo que el valor del lote sobre el cual se levanta la construcción. // Serán responsables, ante el Servicio Nacional de Aprendizaje, SENA, del pago de los aportes de que trata este Artículo, el propietario de la obra en las construcciones por el sistema de administración delegada y los contratistas o constructores principales de la misma en los contratos a precio alzado o a precios unitarios fijos”<br>';
$linea9 = 'Que el Decreto 1047 de 1983, por medio del cual se reglamenta parcialmente el  Decreto 2375 de 1974 en lo relacionado con el  funcionamiento del Fondo Nacional de Formación Profesional de la Industria de la Construcción FIC, en su artículo 3 establece que: “El Servicio Nacional de Aprendizaje -SENA-, como administrador del Fondo, queda facultado para establecer los procedimientos necesarios relacionados con la liquidación, recaudo y control de los valores correspondientes al FIC, así como también para regular la administración, funcionamiento y destinación específica del mismo”<p>';
$linea10 = 'Que en virtud del literal a) del artículo 29 de la Resolución 770 del 11 de Julio de 2001 el Director General del Servicio Nacional de Aprendizaje SENA, delegó en los Directores Regionales y Seccionales la facultad de: “Ordenar las acciones que sean necesarias, para obtener el pago de las sumas de capital e intereses adeudados al SENA por los empleadores del sector público o privado, de acuerdo con las liquidaciones que individualmente hayan sido efectuadas por concepto de aportes a la Entidad y contribuciones al Fondo Nacional de Formación Profesional de la Industria de la Construcción- FIC-, administrado por el SENA, expidiendo las resoluciones correspondientes y resolviendo los recursos que contra ellos procedan por la vía gubernativa”<p>'
        . 'Que la Resolución 1449 de 2012 expedida por el Director General del SENA, regula el funcionamiento del Fondo Nacional de Formación Profesional de la Industria de la Construcción.<p>';
$linea11 = 'Que el empleador ' . $consulta[0]['NOMBRE_EMPLEADOR'] . ' con Nit ' . $informacion['CODEMPRESA'] . ', con domicilio en la ciudad de ' . $ciudad['NOMBREMUNICIPIO'] . ', se encuentra obligado a pagar el (los) concepto (s) de la contribución al Fondo Nacional de Formación Profesional de la Industria de la Construcción –FIC-, con destino al Servicio Nacional de Aprendizaje – SENA conforme con la liquidación número ' . $consulta[0]['NUM_LIQUIDACION'] . ' de fecha ' . $consulta[0]['FECHA_LIQUIDACION'] . ', la cual es soporte integral de la presente Resolución, y que fue realizada por la entidad dentro del respectivo proceso de fiscalización, más los intereses causados hasta el pago total, las cuales se relacionan a continuación:  <p>';
$linea12 = 'Que los intereses se liquidarán conforme lo dispuesto en la normatividad vigente, desde que se causó el vencimiento de la obligación hasta el pago total.<p>';
$linea13 = 'Que en mérito de lo expuesto,<p>';
$linea14 = '<b>RESUELVE:</b><p>';
$linea15 = 'ARTICULO PRIMERO: Librar Orden de Pago a favor del <b>FONDO NACIONAL DE FORMACIÓN PROFESIONAL DE LA INDUSTRIA DE LA CONSTRUCCIÓN-FIC- </b>SENA, y a cargo del empleador ' . $consulta[0]['NOMBRE_EMPLEADOR'] . ' con Nit ' . $informacion['CODEMPRESA'] . ', por la suma de  ' . $consulta[0]['VALOR_LETRAS'] . ' Pesos M.Cte ($ ' . $consulta[0]['VALOR_TOTAL'] . '), valor correspondiente a la contribución FIC dejados de cancelar por los períodos  señalados en la parte considerativa, más los intereses de mora que se causen desde su vencimiento hasta su pago total.<br>
ARTÍCULO SEGUNDO. Citar al deudor o a su representante legal para que en el término de cinco (5) días contados a partir de la recepción de la citación, comparezca a notificarse personalmente de la presente orden de pago, y si no comparece se procederá a notificar la mencionada resolución mediante Aviso, en cumplimiento a lo dispuesto en el artículo 69 del Código de Procedimiento Administrativo y de lo Contencioso Administrativo. (C.P.A.C.A.).<p>
ARTÍCULO TERCERO: Advertir al deudor que contra la misma procede únicamente el recurso de reposición ante el Director Regional del SENA- ' . $informacion['DIRECTOR_REGIONAL'] . ', el cual podrá interponerse dentro de los  diez (10) días hábiles siguientes a su notificación, de acuerdo a lo dispuesto en el artículo 76 o proceder a su pago en las entidades bancarias establecidas por el Servicio Nacional de Aprendizaje - SENA para tal fin.<p>
ARTÍCULO CUARTO: La presente Resolución presta mérito ejecutivo a partir de su firmeza.<p>';
$linea16 = '<b>NOTIFIQUESE Y CUMPLASE</b>';
$linea17 = 'Dada en ' . $ciudad['NOMBREMUNICIPIO'] . ', a los ' . $consulta[0]['FECHA_CREACION'] . ' <p>';
$linea20 = '<p>' . $informacion['DIRECTOR_REGIONAL'] . '<br>';
$linea21 = 'Director Regional';
$linea18 = 'Proyectó: ' . $elaboro['APELLIDOS'] . ' ' . $elaboro['NOMBRES'] . '<p>';

$html = '<div class="bs-docs-grid" >
    <div class="row show-grid" style="margin: 10px auto; text-align:center">
        ' . $linea1 . '
    </div>
    <div class="row show-grid" style="margin: 10px auto;">
        <div  style="margin: 10px auto;text-align:center">
            ' . $linea2 . '
        </div>
        <div  style="margin: 10px auto;text-align:center">
            ' . $linea3 . '
        </div>
        <div  style="margin: 10px auto;text-align:center">
            ' . $linea4 . '
        </div>
        <div  style="margin: 10px auto;text-align:justify">
            ' . $linea5 . '
            ' . $linea6 . '
            ' . $linea7 . '
            ' . $linea8 . '
            ' . $linea9 . '
            ' . $linea10 . '
            ' . $linea11 . '

            <table width="100%" border="1">
            <tr>
                    <td>No Liquidacion</td>
                    <td>Fecha</td>
                    <td>Periodo</td>
                    <td>Concepto de Obligación</td>
            </tr>
            <tr>
                    <td>' . $consulta[0]['NUM_LIQUIDACION'] . '</td>
                    <td>' . $consulta[0]['FECHA_LIQUIDACION'] . '</td>
                    <td>' . $consulta[0]['PERIODO_INICIAL'] . '-' . $consulta[0]['PERIODO_FINAL'] . '</td>
                    <td>' . $concepto['NOMBRE_CONCEPTO'] . '</td>
            </tr>

            </table><br>
            ' . $linea12 . '
            ' . $linea13 . '
            
        </div>
        <div  style="margin: 10px auto;text-align:center">
            ' . $linea14 . '
        </div>
        <div  style="margin: 10px auto;text-align:justify">
            ' . $linea15 . '
        </div>
    </div>
    <div  style="margin: 10px auto;text-align:center">
            ' . $linea16 . '
        </div>
    <div  style="margin: 10px auto;text-align:justify">
            ' . $linea17 . '
            ' . $linea18 . '
        </div>
    <div  style="margin: 10px auto;text-align:center">
    ' . $linea20 . '
    ' . $linea21 . '
        </div>
</div>
';
?>
<div id="cuerpo">
        <?php
//Aprobar_resolucion::pdf($html);
        if (!empty($_REQUEST['pdf'])) {
            Resolucion::pdf($html);
        } else {
            ?><div class="resolucion"><?php
            echo $html;
            ?></div><?php
        }
            ?>
        </div>