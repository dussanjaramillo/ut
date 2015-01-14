<?php

$linea1 = '<b>RESOLUCION No. __________________ DEL ______________ de _______________</b>';
$linea2 = '<b>POR MEDIO DE LA CUAL SE ORDENA EL PAGO DE UNA OBLIGACION DINERARIA A FAVOR DEL SERVICIO NACIONAL DE APRENDIZAJE-SENA POR INCUMPLIMIENTO EN EL PAGO DE APORTES PARAFISCALES.</b>';
$linea3 = 'El (la) Director(a) de la Regional ' . $informacion['NOMBRE_REGIONAL'] . ' del Servicio Nacional de Aprendizaje - SENA, en uso de sus facultades legales y en especial las previstas en el numeral 9 del artículo 24 del Decreto 249 de 2004 y las delegadas por el Director General mediante Resolución 770 del 11 de Julio de 2001, y';
$linea4 = '<b>CONSIDERANDO:</b>';
$linea5 = '<p>Que el artículo 7 de la Ley 21 de 1982 por la cual se modifica el régimen del subsidio familiar y se dictan otras disposiciones, establece que: “Están obligados a pagar el subsidio familiar y a efectuar aportes para el Servicio Nacional de Aprendizaje Sena (SENA): // 4. Los empleadores que ocupen uno o más trabajadores permanentes”<p>';
$linea6 = 'Que el artículo 14 de la precitada ley, señala que: “para efectos del régimen del subsidio familiar se entenderá por empleador toda persona natural o jurídica que tenga trabajadores a su servicio y se encuentre dentro de la enumeración hecha en el artículo 7o. de la presente Ley”.<p>';
$linea7 = 'Que el artículo 17 de la misma norma consagra que: “Para efectos de la liquidación de los aportes al (…) Servicio Nacional de Aprendizaje, SENA, (…) se entiende por nómina mensual de salarios la totalidad de los pagos hechos por concepto de los diferentes elementos integrantes del salario en los términos de la Ley Laboral, cualquiera que sea su denominación y además, los verificados por descansos remunerados de Ley y convencionales o contractuales”<p>';
$linea8 = 'Que de conformidad con lo dispuesto en el numeral 4 del artículo 30 de la Ley 119 de 1994: “Los aportes de los empleadores para la inversión en el desarrollo social y técnico de los trabajadores, recaudados por las cajas de compensación familiar o directamente por el SENA, así: // El aporte del dos por ciento (2%) que dentro de los diez (10) primeros días de cada mes deben hacer los empleadores particulares, los establecimientos públicos, las empresas industriales y comerciales del Estado y las sociedades de economía mixta, sobre los pagos que efectúen como retribución por concepto de salarios”<br>';
$linea9 = 'Que conforme con el artículo 4 del Decreto 562 de 1990, por el cual se establecen mecanismos para asegurar el pago de los aportes para la Seguridad Social: “Los funcionarios autorizados por el (…) Servicio Nacional de Aprendizaje (…), podrán verificar en las empresas la afiliación correcta y oportuna de los trabajadores a las respectivas entidades, como también las bases de liquidación y el pago oportuno de los aportes a que se refieren las disposiciones correspondientes y este Decreto, conforme a las normas legales vigentes”<p>';
$linea10 = 'Que en virtud del literal a) del artículo 29 de la Resolución 770 del 11 de Julio de 2001, el Director General del Servicio Nacional de Aprendizaje SENA, delegó en los Directores Regionales y Seccionales la facultad de: “Ordenar las acciones que sean necesarias, para obtener el pago de las sumas de capital e intereses adeudados al SENA por los empleadores del sector público o privado, de acuerdo con las liquidaciones que individualmente hayan sido efectuadas por concepto de aportes a la Entidad (…) expidiendo las resoluciones correspondientes y resolviendo los recursos que contra ellos procedan por la vía gubernativa”.<p>';
$linea11 = 'Que el empleador ' . $consulta[0]['NOMBRE_EMPLEADOR'] . ' con Nit ' . $informacion['CODEMPRESA'] . ', con domicilio en la ciudad de ' . $ciudad['NOMBREMUNICIPIO'] . ', se encuentra obligado a pagar el (los) concepto(s) de Aportes Parafiscales, con destino al Servicio Nacional de Aprendizaje – SENA conforme con la liquidación número ' . $consulta[0]['NUM_LIQUIDACION'] . ' de fecha ' . $consulta[0]['FECHA_LIQUIDACION'] . ', la cual es soporte integral de la presente Resolución, y que fue realizada por la entidad dentro del respectivo proceso de fiscalización, se relacionan a continuación:  <p>';
$linea12 = 'Que los intereses se liquidarán al momento del pago, de conformidad con lo dispuesto en los artículos 3 de la Ley 1066 de 2006, 635 del Estatuto Tributario, modificado por el artículo 12 de la Ley 1066 de 2006, así como lo establecido en los artículos 11 y 12 de la Ley 21 de 1982.<p>';
$linea13 = 'Que en mérito de lo anterior resuelve:';
$linea14 = '<b>RESUELVE:</b>';
$linea15 = '<b>ARTÍCULO PRIMERO:</b> Librar Orden de Pago a favor del SERVICIO NACIONAL DE APRENDIZAJE - SENA, y a cargo del empleador ' . $consulta[0]['NOMBRE_EMPLEADOR'] . ' con Nit ' . $informacion['CODEMPRESA'] . ', <b>por la suma de  ' . $consulta[0]['VALOR_LETRAS'] . ' Pesos M.Cte ($ ' . $consulta[0]['VALOR_TOTAL'] . ')</b>, valor <br>
correspondiente a aportes parafiscales dejados de cancelar por los períodos  señalados en la parte considerativa, más los intereses de mora que se causen desde su vencimiento hasta su pago total.<br>
<b>ARTÍCULO SEGUNDO.</b> Citar al deudor o a su representante legal para que en el término de cinco (5) días contados a partir de la recepción de la citación, comparezca a notificarse personalmente de la presente orden de pago, y si no comparece se procederá a notificar la mencionada resolución mediante Aviso, en cumplimiento a lo dispuesto en el artículo 69 del Código de Procedimiento Administrativo y de lo Contencioso Administrativo. (C.P.A.C.A.). <br>
<b>ARTÍCULO TERCERO:</b> Advertir al deudor que contra la misma procede únicamente el recurso de reposición ante el Director Regional del SENA- ' . $informacion['DIRECTOR_REGIONAL'] . ', el cual podrá interponerse dentro de los  diez (10) días hábiles siguientes a su notificación, de acuerdo a lo dispuesto en el artículo 76 o proceder a su pago en las entidades bancarias establecidas por el Servicio Nacional de Aprendizaje - SENA para tal fin..<br>
<b>ARTÍCULO CUARTO:</b> La presente Resolución presta mérito ejecutivo a partir de su firmeza.';
$linea16 = '<b>NOTIFIQUESE Y CUMPLASE</b>';
$linea17 = 'Proyectó: ' . $elaboro['APELLIDOS'] . ' ' . $elaboro['NOMBRES'] . '<p>';
$linea18 = 'Revisó: ' . $reviso[0]['COORDINADOR_REGIONAL'] . '<br>';
$linea19 = 'Dada en ' . $ciudad['NOMBREMUNICIPIO'] . ', a los <br>';
$linea20 = '' . $informacion['DIRECTOR_REGIONAL'] . '<br>';
$linea21 = 'Director Regional';
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
            ' . $linea19 . '
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