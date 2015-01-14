<?php

$i = 0;
$tbody = "";
$thead = "";
$k = 0;
foreach ($general as $detalle) {
    if ($i % 2 == 0)
        $tbody.= '<tr style="background-color: #f9f9f9">';
    else
        $tbody.= '<tr >';
    foreach ($detalle as $key => $value) {
        if ($k < count($detalle)) {
            $thead.="<td bgcolor='#5BB75B' style='color: #ffffff;border:1px solid #CCC'>" . $key . "</td>";
            $k++;
        }
        $tbody.="<td style='border:1px solid #CCC'>" . $value . "</td>";
    }
    $tbody.= "</tr>";
    $i++;
}
$consulta = '<table><tr style="background-color: #5BB75B">' . $thead . '</tr>' . $tbody . '</table> ';
if ($post['accion'] == 1) {
    Reporteador::pdf($consulta);
} else {
    $filename = "excelreport.xls";
    ob_clean();
    header('Content-type: application/ms-excel');
    header('Content-Disposition: attachment; filename=' . $filename);
    echo utf8_decode($consulta);
}