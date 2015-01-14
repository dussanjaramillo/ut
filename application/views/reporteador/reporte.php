<?php
//echo "<pre>";
//print_r($informacion);
//echo "</pre>";
//echo count($informacion);
//die();
$i = 1;
$titulo = array();
if (count($informacion) != 0 && $informacion[0] != "0") {
    foreach ($informacion as $infor) {
        if ($i == 1) {
            foreach ($infor as $key => $value) {
                $titulo[] = $key;
            }
            $i++;
        }
    }
    $html = '<div style="clear: both">
        <br>';
    if ($post['accion'] == 2) {//pdf
        $html.= '<table border="1" id="table">';
        $stilo1 = 'bgcolor="#5BB75B"';
        $stilo2 = "style='border:1px solid #CCC;color:#FFF'";
    } else {
        $html.= '<table id="table2" width="100%">';
        $stilo1 = "";
        $stilo2 = "";
    }
    $html.= '<thead ><tr  bgcolor="#5BB75B"' . $stilo1 . '>'; //bgcolor="#5BB75B"
    foreach ($titulo as $infor) {
        $infor = Reporteador::reemplazar($infor);
        $html.= "<td " . $stilo2 . " align='center'>" . $infor . "</td>"; //style='border:1px solid #CCC;color:#FFF'
    }
    $html.='</tr>
            </thead>
            <tbody>';
    $cantidad = count($informacion);
    for ($i = 0; $i < $cantidad; $i++) {
        $cantidad2 = count($titulo);
        if ($i % 2 == 0)
            $html.= "<tr bgcolor='#f9f9f9'>";
        else
            $html.= "<tr>";
        foreach ($titulo as $key => $value) {
        	//echo $value;
            if ($value == "TIPO_CARTERA") {
                if ($informacion[$i][$value] == 5)
                    $informacion[$i][$value] = "Presunta";
                else
                    $informacion[$i][$value] = "Real";
                $html.= "<td style='border:1px solid #CCC'>" . $informacion[$i][$value] . "</td>";
            }else {
                if ($value == "ORIGEN_CARTERA" || $value == "TOTAL_LIQUIDADO" ||
                        $value == "TOTAL_INTERESES" || $value == "SALDO_DEUDA" ||
                        $value == "VALOR_TOTAL" || $value == "VALOR_CAPITAL_FECHA" ||
                        $value == "VALOR_TOTAL_FINANCIADO" || $value == "VALO_RINTERESES_CAPITAL" ||
                        $value == "ESTADO_VALOR_FINAL" ||
                        $value == "VALOR_INTERES_INICIAL" ||
                        $value == "VALOR_INTERESES_INICIAL" ||
                        $value == "SALDO_PENDIENTE_CAPITAL" ||
                        $value == "SALDO_PENDIENTE_INTERESES" ||
                        $value == "VALOR_RESOLUCION" ||
                        $value == "VALOR_LIQUIDADA" ||
                        $value == "VALOR_INICIAL_MINISTERIO" ||
                        $value == "VALOR_DEL_PROCESO" ||
                        $value == "APORTES" ||
                        $value == "VALOR_LIQUIDADO" ||
                        $value == "TOTAL_PAGADO" ||
                        $value == "INGRESOS_RECIBIDOS" ||
                        $value == "FIC" ||
                        $value == "VALOR_ABONOS" ||
                        $value == "SALDO_ACTUAL_CARTERA" ||
                        $value == "TOTAL" ||
                        $value == "IBC" ||
                        $value == "VALOR_TOTAL_NOMINA" ||
                        $value == "NUMERO_TRABAJADORES" ||
                        $value == "TOTAL_OBLIGACION" ||
                        $value == "VALOR_TOTAL_DE_CARTERA" ||
                        $value == "TOTAL_DEVUELTO" ||
                        $value == "SALDO_CUENTA_POR_COBRAR" ||
                        $value == "2011" ||
                        $value == "2012" ||
                        $value == "2013" ||
                        $value == "2014" ||
                        $value == "2015" ||
                        $value == "2016" ||
                        $value == "2017" ||
                        $value == "VALOR_TOTAL_ARCHIVO" ||
                        $value == "'ECOLLECT'" ||
                        $value == "'MANUAL'" ||
                        $value == "'ASOBANCARIA'" ||
                        $value == "VALOR_PAGADO" ||
                        $value == "SALDO_PENDIENTE_INTERES" ||
                        $value == "TOTAL_GARANTIA" ||
                        $value == "VALOR" ||
                        $value == "SALDO_ANTERIOR" ||
                        $value == "SALDO_ACTUAL_DEUDA" ||
                        $value == "PAGOS_EFECTUADOS" ||
                        $value == "VALOR_ABONO" ||
                        $value == "VALOR_CAPITAL_INICIA_RESOLUCION" ||
                        $value == "VALOR_CAPITAL_VENCIDA_0_30" ||
                        $value == "VALOR_INTERES_VENCIDA_0_30" ||
                        $value == "VALOR_CAPITAL_VENCIDA_31_60" ||
                        $value == "VALOR_INTERES_VENCIDA_31_60" ||
                        $value == "VALOR_CAPITAL_VENCIDA_61_90" ||
                        $value == "VALOR_INTERES_VENCIDA_61_90" ||
                        $value == "VALOR_CAPITAL_VENCIDA_>_90" ||
                        $value == "VALOR_INTERES_VENCIDA_>_90" ||
                        $value == "VALOR_CORRIENTE" ||
                        $value == "VALOR_NO_CORRIENTE" ||
                        $value == "MULTAS_MINISTERIO_INTERES" ||
                        $value == "CONTRATO_APRENDIZAJE_CAPITAL" ||
                        $value == "CONTRATO_APRENDIZAJE_INTERES" ||
                        $value == "CONTRATO_INTERES" ||
                        $value == "FIC_INTERES" ||
                        $value == "APORTES_INTERES" ||
                        $value == "VALOR_INICIAL" ||
                        $value == "APORTES_CAPITAL" ||
                        $value == "FIC_CAPITAL" ||
                        $value == "MULTAS_MINISTERIO_CAPITAL" ||
                        $value == "ABONOS_EFECTUADOS" ||
                        $value == "VALOR_TOTAL_CARTERA" ||
                        $value == "VALOR_DEUDA" ||
                        $value == "TOTAL_PAGOS" ||
                        $value == "SALDO_ACTUAL" ||
                        $value == "TOTAL_RECAUDO" ||
                        $value == "TOTAL_SALDO" ||
                        $value == "MULTAS_MINISTERIO" ||
                        $value == "CONTRATO_APRENDIZAJE" ||
                        $value == "SALDO_RESOLUCION" ||
                        $value == "VALOR_DE_LA_CARTERA" ||
                        $value == "PAGO_ABONOS" ||
                        $value == "VALOR_CONTRATO" ||
                        $value == "VALOR_A_PAGAR_FIC" ||
                        $value == "INTERESES" ||
                        $value == "VALOR_INICIAL_LIQUIDACION" ||
                        $value == "SALDO_PENDIENTE_TOTAL" ||
                        $value == "VALOR_CAPITAL_INICIA" ||
                        $value == "TOTAL_CAPITAL" ||
                        $value == "VALOR_CAPITAL_INICIAL" ||
                        $value == "SALDO_DEUDA_DE_CAPITAL" ||
                        $value == "INTERESES_ACOMULADOS_NO_PAGO" ||
                        $value == "VALOR_TOTAL_DEUDA" ||
                        $value == "TOTAL_SALDOS" ||
                        $value == "VALOR_ANT_CAPITAL" ||
                        $value == "CUOTA_PACTADA" ||
                        $value == "CUOTA_CANCELADA" ||
                        $value == "CESANTIAS_APLICADAS" ||
                        $value == "INTERES_CORRIENTE_CANCELADO" ||
                        $value == "AMORTIZACION_A_CAPITAL" ||
                        $value == "NUEVO_SALDO_CAPITAL" ||
                        $value == "SALDO_INTERESES_NO_PAGOS_ACUM" ||
                        $value == "CUOTAS_MES_MORA" ||
                        $value == "TOTAL_ADEUDADO_POR_MOROSOS" ||
                        $value == "VALOR_CUOTA_INICIAL" || $value == "VALOR_CUOTA")
                    $html.= "<td style='border:1px solid #CCC;text-align:right'>" . number_format($informacion[$i][$value],0,',','.') . "</td>";
                else if ($value == "CANTIDAD_PAGOS")
                    $html.= "<td style='border:1px solid #CCC;text-align:center'>" . number_format($informacion[$i][$value],0,',','.') . "</td>";
                else
                    $html.= "<td style='border:1px solid #CCC'>" . $informacion[$i][$value] . "</td>";
            }
        }
        $html.= "</tr>";
    }
    $html.="</tbody>
        </table>
    </div>
</html>
";
//echo $html;
}else {
//    print_r($informacion);
    $td = "";
    $html = '<div style="clear: both">
        <br>';
    $html.= '<table border="1" id="table2"><thead>';
    for ($i = 1; $i < count($informacion); $i++) {
        $html.= "<th align='center'>" . $informacion[$i] . "</th>";
//        $td.="<td>0</td>";
    }

    $html.="</thead>"
//            . "<tbody><tr>".$td."</tr></tbody>"
            . "</table>
    </div>";
    if ($post['accion'] == 2)
        $html = utf8_decode($html);
}
if ($post['accion'] == 2) {//pdf
//    echo $html;
//    die;
    Reporteador::pdf($html);
//    echo $html;
} else if ($post['accion'] == 1) {
    if (isset($post['name_reporte'])) {
        $filename = str_replace(' ', '_', $post['name_reporte']) . ".xls";
    } else
        $filename = "excelreport.xls";
    ob_clean();
    header('Content-type: application/ms-excel');
    header('Content-Disposition: attachment; filename=' . $filename);
    $html1 = "<br><table><tr>"
            . "<td rowspan='2'  style='height:10;width:10'>"
            . "<img src='" . base_url('img/Logotipo_SENA2.jpg') . "' style='width:10px'><p><br><p>"
            . "</td>"
            . "<td>Servicion Nacional de Aprendizaje SENA</td></tr><tr><td>Reporte: " . $post['name_reporte'] . "   " . date('d/m/y') . "</td>"
            . "</tr></table><br>";
    echo utf8_decode($html1 . $html);
}else {
    echo $html;
    ?>

    <script>

        $('#table2').dataTable({
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "iDisplayLength": 10,
            "oLanguage": {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "Buscar:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "fnInfoCallback": null,
            },
        });
        $('.footer').hide();
    </script>
    <?php

}
?>
