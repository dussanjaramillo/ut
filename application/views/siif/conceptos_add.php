<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//echo "<pre>";
////print_r($conceptos->result_array());
//print_r($conceptosxcuenta->result_array());
//echo "</pre>";

$html = '<select id="conceptos" name="conceptos" MULTIPLE>';
foreach ($conceptos->result_array() as $value) {
    $html .= '<option value="'.$value['COD_CONCEPTO_RECAUDO'].'">'.$value['NOMBRE_TIPO'].' - '.$value['NOMBRE_CONCEPTO'].'</option>';
}
$html .= '</select>';

echo $html;
       