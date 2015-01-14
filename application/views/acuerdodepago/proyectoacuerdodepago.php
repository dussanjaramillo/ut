<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /></head>
<div id="imprimir" name='imprimir' style ="background: white" >
<table align="center" style="border: black 5px double " class="imp" id='tabla' name='tabla'>
    <tr>
        <td>
            <div style="margin: 30px;"><h6>ACUERDO DE PAGO No. <?= $proyecto->result_array[0]['NRO_ACUERDOPAGO']?></h6></div><br>
             <div align="center"><img src="<?= base_url('img/sena.jpg') ?>" style="width: 90px;height: 90px"></div><br>
             <div align="center"><h6>Servicio Nacional de Aprendizaje-SENA<br><?= $proyecto->result_array[0]['NOMBRE_REGIONAL']?></h6></div><br>
             <div align="center" style="margin: 30px;">
                 <b>  A C U E R D O   D E   P A G O No. <?= $proyecto->result_array[0]['NRO_ACUERDOPAGO']?> de <?= date('Y')?> </b>
             </div>
             <div style="text-align: justify; margin: 30px;">
                 Siendo las <?= date('H')?>:<?= date('i')?> horas, del d&iacute;a <?= date('d')?> del mes de <?= date('m')?> de <?= date('Y')?>, se reunieron en las 
                 instalaciones de la Direcci&oacute;n Regional del SENA <?= $proyecto->result_array[0]['NOMBRE_REGIONAL']?>, los suscritos,  <?= $proyecto->result_array[0]['REPRESENTANTE_LEGAL']?>  
                 identificado con cedula de ciudadan&iacute;a No. <?= $proyecto->result_array[0]['COD_REPRESENTANTELEGAL']?> de XXXXX (XXXXX), en calidad  de 
                 Representante Legal de la sociedad denominada <?= $proyecto->result_array[0]['NOMBRE_EMPRESA']?>. identificada con el 
                 NIT: No. <?= $proyecto->result_array[0]['NITEMPRESA']?>, quien en adelante se denominar&aacute; EL DEUDOR por una parte, y por 
                 otra el Dr. <?= @$proyecto->result_array[0]['DIRECTOR_REGIONAL']?>, identificado con la c&eacute;dula de ciudadan&iacute;a No. <?= $proyecto->result_array[0]['CEDULA_DIRECTOR']?> 
                 expedida en XXXXXXX, en su calidad de Director Regional del SENA-<?= $proyecto->result_array[0]['NOMBRE_REGIONAL']?>,
                 cargo en el que fue nombrado mediante resoluci&oacute;n <?= $proyecto->result_array[0]['NUMERO_RESOLUCION']?> del <?= $proyecto->result_array[0]['FECHA_CREACION']?> y 
                 se posesion&oacute; con el acta XXXX del XX de XX de 20XX en uso de sus atribuciones legales,
                 en especial las conferidas por el art&iacute;culo 2 de la Ley 1066 de 2006, los 
                 art&iacute;culos 3 y 4 del Decreto 4473 del 2006 y por el art&iacute;culo 61 de la 
                 Resoluci&oacute;n No. 000210 del 15 de febrero de 2007, y
             </div>
             <br>
             <div align="center">
                 <b>
                     C O N S I D E R A N D O:
                 </b>
             </div>
             <div style="text-align: justify; margin: 30px;">
                 <b> 1.</b>	Que mediante oficios de fecha <?= date('d')?> de <?= date('m')?> de  <?= date('Y')?>, radicado interno con el No. XXXXXX 
                 con el NIS: XXXXXXX,  en la Direcci&oacute;n Regional <?= $proyecto->result_array[0]['NOMBRE_REGIONAL']?>, <b> EL DEUDOR</b>, solicit&oacute; la suscripci&oacute;n de 
                 un acuerdo de pago, derivado de la  obligaci&oacute;n contenida en la  Resoluci&oacute;n  <?= $proyecto->result_array[0]['NUMERO_RESOLUCION']?> del 
                 <?= $proyecto->result_array[0]['FECHA_CREACION']?>, por concepto de <?= $proyecto->result_array[0]['NOMBRE_CONCEPTO']?>, respectivamente de conformidad con las condiciones enunciadas 
                 en el mencionado oficio, que hace parte integrante del presente acuerdo de pago en lo que le sea 
                 ajustable a nuestra reglamentaci&oacute;n de recaudo de cartera. 
                 <br>
                 <br>
                 <b> 2.</b>	Que el Se&ncaron;or(a) <?= $proyecto->result_array[0]['REPRESENTANTE_LEGAL']?>, cancel&oacute;
                 la suma de <?= $valor ?> ($<?= $proyecto->result_array[0]['PROYACUPAG_VALORCUOTA']?>) correspondiente a m&aacute;s 30% del 
                 total de la obligaci&oacute;n a favor de la entidad por concepto de <?= $proyecto->result_array[0]['NOMBRE_CONCEPTO']?> contenida en la Resoluci&oacute;n 
                 <?= $proyecto->result_array[0]['NUMERO_RESOLUCION']?> del <?= $proyecto->result_array[0]['FECHA_CREACION']?>, tal y como se constata en la copia del la Transferencia mediante cup&oacute;n
                 de pago Referencia No.17828516  en la cuenta de Recaudo Regional SENA de Banco De Colombia, de 
                 fecha 03 de Julio de 2013,  dando cumplimiento al art&iacute;culo 62 de la Resoluci&oacute;n No. 000210 de 2007.  
                 <br>
                 <br>
                 <b>3.</b>	Que el <b>DEUDOR</b> solicit&oacute; acuerdo de pago con t&eacute;rmino inferior a XX, XX (XX) cuotas mensuales 
                 de plazo para cancelar el saldo insoluto de la obligaci&oacute;n y cumpli&oacute; con los requisitos exigidos por 
                 el art&iacute;culo 65; de la Resoluci&oacute;n No. 000210 de 2007.
                 <br>
                 <br>
                 <b>4.</b>	Que se suscribe acuerdo de pago sin garant&iacute;a, de conformidad con el literal d) del art&iacute;culo 
                 66 de la Resoluci&oacute;n SENA 000210 del 15 de febrero de 2007, de la Ley 1066 de 2006, lo previsto en 
                 el par&aacute;grafo 4; del art&iacute;culo 4; del decreto 4473 de 2006 y el art&iacute;culo 814 del Estatuto Tributario.
                 <br>
                 <br>
                 <b>5.</b>	Que consultado la raz&oacute;n social <?= $proyecto->result_array[0]['NOMBRE_EMPRESA']?>. identificada con el NIT: No. <?= $proyecto->result_array[0]['NITEMPRESA']?>  en la p&aacute;gina
                 Web (www.contaduria.gov.co) de la Contadur&iacute;a General de la Naci&oacute;n en el link del Bolet&iacute;n de Deudores 
                 Morosos del Estado -BDME-; Incumplimiento de Acuerdos de pago, No ha incumplido acuerdos de pago de
                 conformidad con lo establecido en el numeral 5; del art&iacute;culo 2; de la ley 1066 de 2006. 
                 <br>
                 <br>
                 Por lo expuesto, las partes arriba enunciadas, 
             </div>
             <div align="center">
                 <b>
                     A C U E R D A N
                 </b>
             </div>
             <div style="text-align: justify; margin: 30px;">
                 <b> 1.</b>	Que el Servicio Nacional de Aprendizaje-SENA, Direcci&oacute;n Regional <?= $proyecto->result_array[0]['NOMBRE_REGIONAL']?>, <b>APRUEBA EL ACUERDO 
                     DE PAGO</b> y <b>LA DENUNCIA DE BIENES</b>, ofrecida por el Deudor <?= $proyecto->result_array[0]['REPRESENTANTE_LEGAL']?>  identificada con cedula de ciudadan&iacute;a
                 No. <?= $proyecto->result_array[0]['COD_REPRESENTANTELEGAL']?> de XXXXX (XXXXX), en calidad  de Representante Legal de la sociedad denominada <?= $proyecto->result_array[0]['NOMBRE_EMPRESA']?>. 
                 identificada con el NIT: No. <?= $proyecto->result_array[0]['NITEMPRESA']?>, consistente en XXXX XXXX, camioneta de servicio particular 
                 modelo 2006, marca Chevrolet, placa QHI842, 2400 cilindraje ,  Color Gris Granito como consta en 
                 licencia de transito N. 0121570 por valor aproximado  de VEINTINUEVE MILLONES DE PESOS ML ($29.000.000).
                 <br>
                 <br>
                 <b> 2.</b>	Que el Servicio Nacional de Aprendizaje-SENA, Direcci&oacute;n <?= $proyecto->result_array[0]['NOMBRE_REGIONAL']?> <b>CONCEDE</b> a la empresa 
                 <?= $proyecto->result_array[0]['NOMBRE_EMPRESA']?>.  un plazo de XXX (XX) meses para cancelar el saldo insoluto de la obligaci&oacute;n correspondiente
                 a la suma de <?= $cuota ?> (<?= $proyecto->result_array[0]['VALOR_CUOTA']?>), m&aacute;s intereses de mora y de financiaci&oacute;n <?= $info ?> ($<?= $proyecto->result_array[0]['VALOR_TOTAL_FINANCIADO']?>), por concepto
                 de <?= $proyecto->result_array[0]['NOMBRE_CONCEPTO']?>, contenida en la  Resoluci&oacute;n  XXX del XX de XXX de 20XX.
                 <br>
                 <br>
                 <b>3.</b>	El pago de la suma enunciada en el numeral que antecede, deber&aacute; realizarse en xxx (xx) 
                 cuotas mensuales, pagaderas  de cada mes y se imputar&aacute; al valor de la obligaci&oacute;n de la siguiente forma: 

             </div>
             <table border="3" align="center" style="width: 800px">
                 <tr align="center" >    
                        <th>PERIODO</th>
                        <th>FECHA</th>
                        <th>SALDO A CAPITAL</th>
                        <th>INTERESES</th>
                        <th>INTERESES CUOTA</th>
                        <th>INTERESES ACUERDO</th>
                        <th>VALOR CUOTA</th>
                        <th>APORTE A CAPITAL</th>
                        <th>SALDO FINAL</th>                  
                 </tr>
                 <?php
               
                 foreach ($datostabla->result_array as $datos) {

                 ?>
                 <tr>
                    <td><?= $datos['PROYACUPAG_NUMCUOTA'] ?></td>
                    <td><?= $datos['PROYACUPAG_FECHALIMPAGO'] ?></td>
                    <td><?= "$ ".number_format ($datos['PROYACUPAG_CAPITALDEBE']) ?></td>
                    <td><?= "$ ".number_format ($datos['PROYACUPAG_INTCORRIENTE']) ?></td>                    
                    <td><?= "$ ".number_format ($datos['PROYACUPAG_SALDO_INTCORRIENTE']) ?></td>
                    <td><?= "$ ".number_format ($datos['PROYACUPAG_SALDO_INTACUERDO']) ?></td>
                    <td><?= "$ ".number_format ($datos['PROYACUPAG_VALORCUOTA']) ?></td>
                    <td><?= "$ ".number_format ($datos['PROYACUPAG_CAPITAL_CUOTA']) ?></td>                    
                    <td>
                         <?php
                         if ($datos['PROYACUPAG_NUMCUOTA'] == '0'):
                             echo "$ ".number_format ($datos['PROYACUPAG_CAPITALDEBE']-$datos['PROYACUPAG_SALDOCAPITAL']-$datos['PROYACUPAG_INTCORRIENTE']);
                             else:
                             echo "$ ".number_format ($datos['PROYACUPAG_CAPITALDEBE']-$datos['PROYACUPAG_SALDOCAPITAL']);        
                         endif;
                         
                                 
                                 ?>
                    </td>                                        
                 </tr>
                 <?php } ?>
             </table>
             <br>
             <div style="text-align: justify; margin: 30px;">
                 <b> 4.</b>	El pago deber&aacute; efectuarse a m&aacute;s tardar en la fecha de vencimiento enunciada para cada cuota,
                 &uacute;nicamente a trav&eacute;s de la p&aacute;gina Web del SENA (http://www.sena.edu.co) seleccionando el Link -Pagos
                 en L&iacute;nea-, registrarse de acuerdo a lo solicitado por el sistema  y optar por la opci&oacute;n 
                 (Pagos electr&oacute;nicos y/o impresi&oacute;n de cup&oacute;n de pago), una vez cancelado, reportar el pago a en la
                 oficina de Relaciones Corporativas e Internacionales de la Direcci&oacute;n <?= $proyecto->result_array[0]['NOMBRE_REGIONAL']?> ubicada en la 
                 XXXXXXXX, en la ciudad de <?= $proyecto->result_array[0]['NOMBREMUNICIPIO']?>- Colombia, dentro de los cinco (5) d&iacute;as h&aacute;biles siguientes a la
                 fecha de vencimiento de la cuota.
                 <br><br>
                 <b>5.</b>	Cl&aacute;usula Aceleratoria. En el evento en que la se&ncaron;or(a) <?= $proyecto->result_array[0]['REPRESENTANTE_LEGAL']?>, identificada con cedula
                 de ciudadan&iacute;a No. <?= $proyecto->result_array[0]['COD_REPRESENTANTELEGAL']?> de XXXXXX (XXXXXX), en calidad de Representante Legal de la sociedad
                 denominada <?= $proyecto->result_array[0]['NOMBRE_EMPRESA']?>. identificada con el NIT: No. <?= $proyecto->result_array[0]['NITEMPRESA']?>  no pague oportunamente dos (2) 
                 o m&aacute;s cuotas fijadas, no pague la totalidad de las mismas, o no acredite el pago dentro de las 
                 fechas se&ncaron;aladas, el Servicio Nacional de Aprendizaje-SENA, Direcci&oacute;n Regional <?= $proyecto->result_array[0]['NOMBRE_REGIONAL']?> declarar&oacute; 
                 el incumplimiento del presente acuerdo de pago y har&aacute; efectiva la garant&iacute;a prestada por el deudor,
                 inici&oacute;ndose el Cobro Coactivo, de no ser satisfecha en su totalidad la obligaci&oacute;n por el pago del
                 garante. 
                 <br><br>
                 Se firma en la ciudad de <?= $proyecto->result_array[0]['NOMBREMUNICIPIO']?>, a los <?= date('d')?> d&iacute;as del mes de <?= date('M')?> de (<?= date('Y')?>).

             </div>
             <div style="margin: 30px;">
                 <table>
                   <tr><td style="width: 700px">  
                 <b><?= $proyecto->result_array[0]['REPRESENTANTE_LEGAL']?></b><br>
                 Representante Legal<br>
                 CC.  <?= $proyecto->result_array[0]['COD_REPRESENTANTELEGAL']?> de XXXXXX (XXXXXX)<br>
                 Direcci&oacute;n: <?= $proyecto->result_array[0]['DIRECCION']?><br>   	                                        
                 Tel&eacute;fono <?= $proyecto->result_array[0]['TELEFONO_FIJO']?><br>                  				                                                  
                 <b>Deudor</b>
                    </td>
                    <td align="right"><div style="width: 120px;height: 100px;border:2px solid #a1a1a1;"  ></div></td>
                 </tr>
                 </table>
             </div>
             <div style="text-align: justify; margin: 30px;">
                 <b> <?= @$proyecto->result_array[0]['DIRECTOR_REGIONAL']?></b><br>
                 C.C. No.  <?= $proyecto->result_array[0]['CEDULA_DIRECTOR']?> de XXXXXXXX<br>
                 Director <?= $proyecto->result_array[0]['NOMBRE_REGIONAL']?>  <br>
             <br><br>
             VoBo<br>
             Coordinador de Promoci&oacute;n y Relaciones Corporativas<br>
             <?= $proyecto->result_array[0]['NOMBRE_REGIONAL']?> SENA.<br>
            <br><br>
            Proyect&oacute;:<br>   
            Abogado de Relaciones Corporativas<br>
            <?= $proyecto->result_array[0]['NOMBRE_REGIONAL']?>  SENA.<br>
            </div>
        </td>
    </tr>
</table>
</div><br><br><br>
<div align="center">
    <button type="button" id="exportar" class="btn btn-success" onclick='imprimirDoc("imprimir")'>Imprimir</button> 
    <button type="button" id="atras" class="btn btn-warning" onclick="window.location='<?= base_url('index.php/acuerdodepago/gestionarAcuerdo') ?>'">Atras</button>   
</div>

<form id="form1" name="form1" action='' method="post">    
    <input type="hidden" id="nit" name="nit" value="<?= $proyecto->result_array[0]['NITEMPRESA']?>">
    <input type="hidden" id="acuerdo" name="acuerdo" value="<?= $proyecto->result_array[0]['NRO_ACUERDOPAGO']?>">    
    <input type="hidden" id="resolucion" name="resolucion" value="<?= $proyecto->result_array[0]['NRO_RESOLUCION']?>">    
    <input type="hidden" id="tipo" name="tipo" value="proyecto_acuerdo">  
</form>

<script>   
    function imprimirDoc (imprimir){ 
        var ficha=document.getElementById(imprimir);
        var ventimp=window.open(' ','popimpr');
        ventimp.document.write(ficha.innerHTML);
        ventimp.document.close();
        ventimp.print();
        ventimp.close();
        document.getElementById('form1').action = "<?= base_url('index.php/acuerdodepago/guardar_verificacion')?>";
        document.getElementById('form1').submit();
    }
</script>
