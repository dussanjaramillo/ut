<!-- Responsable: Leonardo Molina-->
<script type="text/javascript">
 tinymce.init({
    
    selector: "textarea",
    theme:    "modern",
 plugins: [
  "advlist autolink lists link image charmap print preview hr anchor pagebreak",
  "searchreplace wordcount visualblocks visualchars code fullscreen",
  "insertdatetime media nonbreaking save table contextmenu directionality",
  "emoticons template paste textcolor moxiemanager"
 ],
 toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
 toolbar2: "print preview media | forecolor backcolor emoticons",
 image_advtab: true,
 templates: [
  {title: 'Test template 1', content: '<b>Test 1</b>'},
  {title: 'Test template 2', content: '<em>Test 2</em>'}
 ],
 autosave_ask_before_unload: false
     });
     // para reemplazar el contenido desde un jscript
     //tinyMCE.activeEditor.setContent("pepe");
</script>



<form method="post" action="somepage">
    <textarea name="content" id="targetTextArea" style="width:100%; height: 300px">



<p class="MsoHeader" style="text-align: center;" align="center"><span style="font-size: 8.0pt;"><img src="<?php echo base_url(); ?>img/senaPdf.png"/></span></p>

        <p class="MsoHeader" style="text-align: center;" align="center"><span style="font-size: 8.0pt;">Regional Distrito Capital</span></p>
        <p class="MsoNormal"><span style="font-size: 8.0pt;">&nbsp;</span></p>
        <p class="MsoBodyText3" style="text-align: center;" align="center"><span style="font-family: 'Tahoma','sans-serif';">11-1010-1</span></p>
        <p class="MsoBodyText3" style="text-align: center; tab-stops: 212.65pt;" align="center"><strong><span style="font-family: 'Tahoma','sans-serif';">AUTO</span></strong></p>
        <p class="MsoBodyText3" style="text-align: center; line-height: 200%; tab-stops: 212.65pt;" align="center"><strong><span style="font-family: 'Tahoma','sans-serif';">&ldquo;Por el cual se Formulan Cargos</span></strong><span style="font-family: 'Tahoma','sans-serif';">&rdquo;</span></p>
        <p class="MsoBodyText3" style="text-align: justify; tab-stops: 212.65pt;"><span style="font-family: 'Tahoma','sans-serif';">&nbsp;</span></p>
        <p class="MsoNormal" style="text-align: justify;"><span style="font-size: 8.0pt; font-family: 'Tahoma','sans-serif';">El Director del Servicio Nacional de Aprendizaje SENA Regional Distrito Capital,&nbsp; en uso de sus facultades legales y en especial las conferidas por el art&iacute;culo 33 de la Ley 789 de 2002 y de conformidad con el procedimiento sancionatorio establecido en la Ley 1437 de 2011 C&oacute;digo de Procedimiento Administrativo y de lo Contencioso Administrativo y,</span></p>
        <p class="MsoNormal" style="text-align: justify; mso-hyphenate: none; tab-stops: -36.0pt;"><span lang="ES-MX" style="font-size: 8.0pt; font-family: 'Tahoma','sans-serif'; letter-spacing: -.15pt; mso-ansi-language: ES-MX;">&nbsp;</span></p>
        <p class="MsoNormal" style="text-align: justify; mso-hyphenate: none; tab-stops: -36.0pt;"><span lang="ES-MX" style="font-size: 8.0pt; font-family: 'Tahoma','sans-serif'; letter-spacing: -.15pt; mso-ansi-language: ES-MX;">&nbsp;</span></p>
        <p class="MsoNormal" style="text-align: center; mso-hyphenate: none; tab-stops: -36.0pt;" align="center"><strong><span style="font-size: 8.0pt; font-family: 'Tahoma','sans-serif'; letter-spacing: -.15pt;">CONSIDERANDO</span></strong></p>
        <p class="MsoNormal" style="text-align: justify; mso-hyphenate: none; tab-stops: -36.0pt;"><span lang="ES-MX" style="font-size: 8.0pt; font-family: 'Tahoma','sans-serif'; letter-spacing: -.15pt; mso-ansi-language: ES-MX;">&nbsp;</span></p>
        <p class="MsoNormal" style="text-align: justify;"><span style="font-size: 8.0pt; font-family: 'Tahoma','sans-serif';">Que mediante de escrito radicado en el SENA el d&iacute;a <?= $dia?> del mes <?= $mes?> del a&ntilde;o <?= $ano?>, Nis xxxxxxxxxxxx de fecha xxxxxxxxxx, la empresa <strong><?= $empresa->NOMBRE_EMPRESA;?></strong> con nit <strong><?= $empresa->CODEMPRESA;?></strong>, procedi&oacute; a informar al SENA las matrices de sus empleados conforme al listado de oficios y ocupaciones establecido en el Decreto 620 de 2005, con el fin que le fuera regulada la cuota de aprendices se&ntilde;alada en la Ley 789 de 2002 y dem&aacute;s normas concordantes y complementarias.</span></p>
        <p class="MsoNormal" style="text-align: justify;"><span style="font-size: 8.0pt; font-family: 'Tahoma','sans-serif';">&nbsp;</span></p>
        <p class="MsoNormal" style="text-align: justify;"><span style="font-size: 8.0pt; font-family: 'Tahoma','sans-serif';">Que por Resoluci&oacute;n No. xxxx del xxxxxxx, la cual qued&oacute; debidamente ejecutoriada el xxxxxxxxxxxxxxx se le fij&oacute; al empleador <strong><?= $empresa->NOMBRE_EMPRESA;?></strong>, una cuota de xxx (xxx) aprendiz, para ser contratados a nivel Nacional.</span></p>
        <p class="MsoNormal" style="text-align: justify;"><span style="font-size: 8.0pt; font-family: 'Tahoma','sans-serif';">&nbsp;</span></p>
        <p class="MsoNormal" style="text-align: justify;"><span style="font-size: 8.0pt; font-family: 'Tahoma','sans-serif';">Que el d&iacute;a xx de xx 2012, el fiscalizador xxxx funcionario del SENA realiz&oacute; seguimiento y control de contrato de aprendizaje al empleador <strong><?= $empresa->NOMBRE_EMPRESA;?></strong>, con el prop&oacute;sito de verificar el cumplimiento de la cuota de aprendices fijada y de ser pertinente, elaborar el correspondiente estado de cuenta.</span></p>
        <p class="MsoNormal" style="text-align: justify;"><span style="font-size: 8.0pt; font-family: 'Tahoma','sans-serif';">Que de conformidad con lo anterior y con base en el estado de cuenta en mención, se adelantara la correspondiente investigación de carácter sancionatorio contra el empleador <strong><?= $empresa->NOMBRE_EMPRESA;?></strong> identificado con nit <strong><?= $empresa->CODEMPRESA;?></strong>.</span></p>

        <p class="MsoNormal" style="text-align: justify;"><span style="font-size: 8.0pt; font-family: 'Tahoma','sans-serif';">(Para cada caso los hechos serán más largos o no)

        <p class="MsoNormal" style="text-align: justify;"><strong><span style="letter-spacing: .3pt;">&nbsp;</span></strong></p>
        <p class="MsoNormal" style="text-align: justify;"><strong><span style="letter-spacing: .3pt;">2. DISPOSICIONES PRESUNTAMENTE VIOLADAS:</span></strong></p>
        <p class="MsoNormal" style="text-align: justify;"><span style="letter-spacing: .3pt;">&nbsp;</span></p>

        <p class="MsoNormal" style="text-align: justify;"><span style="font-size: 8.0pt; font-family: 'Tahoma','sans-serif';">Que la <strong>Ley 789 de 2002</strong>  en su artículo 32 establece; “<strong>Artículo 32</strong>. Empresas obligadas a la vinculación de aprendices. Las empresas privadas, desarrolladas por personas naturales o jurídicas, que realicen cualquier tipo de actividad económica diferente de la construcción, que ocupen un número de trabajadores no inferior a quince (15), se encuentran obligadas a vincular aprendices para los oficios u ocupaciones que requieran formación académica o profesional metódica y completa en la actividad económica que desempeñan.

        <p class="MsoNormal" style="text-align: justify;">Las empresas industriales y comerciales del Estado y las de Econom&iacute;a mixta del orden Nacional, departamental, distrital y municipal, estar&aacute;n obligadas a la vinculaci&oacute;n de aprendices en los t&eacute;rminos de esta ley. Las dem&aacute;s entidades p&uacute;blicas no estar&aacute;n sometidas a la cuota de aprendizaje, salvo en los casos que determine el Gobierno Nacional&rdquo;. (&hellip;)</p>
        <p class="MsoNormal" style="text-align: justify;">&nbsp;</p>
        <p class="MsoNormal" style="text-align: justify;"><span lang="ES">Que el art&iacute;culo 33 de la Ley 789 de 2002 establece: &ldquo;</span><span lang="ES">La determinaci&oacute;n del n&uacute;mero m&iacute;nimo obligatorio de aprendices para cada empresa obligada la har&aacute; la regional del Servicio Nacional de Aprendizaje, SENA, del domicilio principal de la empresa, en raz&oacute;n de un aprendiz por cada 20 trabajadores y uno adicional por fracci&oacute;n de diez(10) o superior que no exceda de veinte. Las Empresas que tengan entre quince (15) y veinte (20) trabajadores, tendr&aacute;n un aprendiz. &ldquo;</span></p>
        <p class="MsoNormal" style="text-align: justify;"><span lang="ES">&nbsp;</span></p>
        <p class="MsoNormal" style="text-align: justify;"><span lang="ES">La cuota se&ntilde;alada por el SENA deber&aacute; notificarse previamente al representante legal de la respectiva empresa, quien contar&aacute; con el t&eacute;rmino de 5 d&iacute;as h&aacute;biles para objetarla, en caso de no ce&ntilde;irse a los requerimientos de mano de obra calificada demandados por la misma. Contra el acto administrativo que fije la cuota proceder&aacute;n los recursos de ley.</span></p>
        <p class="MsoNormal" style="text-align: justify;"><span lang="ES">&nbsp;</span></p>
        <p class="MsoNormal" style="text-align: justify;"><span lang="ES">PAR&Aacute;GRAFO.</span><span lang="ES">Cuando el contrato de aprendizaje incluida dentro de la cuota m&iacute;nima se&ntilde;alada por el SENA termine por cualquier causa, la empresa deber&aacute; reemplazar al aprendiz para conservar la proporci&oacute;n que le haya sido asignada. </span></p>
        <p class="MsoNormal" style="text-align: justify;">&nbsp;</p>
        <p class="MsoNormal" style="text-align: justify;">Que el <strong>art&iacute;culo 8&deg; del Decreto 933 de 2003</strong> dispone que &ldquo;Terminada la relaci&oacute;n de aprendizaje por cualquier causa, la empresa patrocinadora deber&aacute; remplazar al aprendiz para conservar la proporcionalidad&rdquo;, debiendo informar inmediatamente al SENA la contrataci&oacute;n del nuevo aprendiz.</p>
        <p class="MsoNormal" style="text-align: justify;">&nbsp;</p>
        <p class="MsoNormal" style="text-align: justify;">Que el art&iacute;culo <strong>14 del Decreto 933 de 2003</strong>, se&ntilde;ala que el SENA &ldquo;impondr&aacute; multas mensuales hasta por un salario m&iacute;nimo mensual legal vigente, conforme a lo establecido en el art&iacute;culo 13, numeral 13 de la Ley 119 de 1994, cuando el empleador incumpla con la vinculaci&oacute;n o monetizaci&oacute;n de la cuota m&iacute;nima de aprendices de conformidad con lo previsto en el presente decreto&rdquo;.</p>
        <p class="MsoNormal" style="text-align: justify;">&nbsp;</p>
        <p class="MsoNormal" style="text-align: justify;">Que la <strong>Ley 789 de 2002 y el Decreto 933 de 2003</strong> le otorgan al empleador la opci&oacute;n de escoger entre contratar aprendices o monetizar total o parcialmente la cuota, disponiendo el par&aacute;grafo del art&iacute;culo 12 del Decreto 933 de 2003 que: &ldquo;En ning&uacute;n caso el cambio de decisi&oacute;n por parte del patrocinador conllevar&aacute; el no pago de la cuota de monetizaci&oacute;n o interrupci&oacute;n en la contrataci&oacute;n de aprendices frente al cumplimiento de las obligaciones&rdquo;</p>
        <p class="MsoNormal" style="text-align: justify;"><strong><span style="letter-spacing: .3pt;">&nbsp;</span></strong></p>
        <p class="MsoNormal" style="text-align: justify;"><strong><span style="letter-spacing: .3pt;">3. FORMULACI&Oacute;N DE CARGOS:</span></strong></p>
        <p class="MsoNormal" style="text-align: justify;"><span style="letter-spacing: .3pt;">&nbsp;</span></p>
        <p class="MsoNormal" style="text-align: justify;"><span style="letter-spacing: .3pt;">Que de la evaluaci&oacute;n de las averiguaciones preliminares contra la empresa, <strong><?= $empresa->NOMBRE_EMPRESA;?></strong> Nit, <strong><?= $empresa->CODEMPRESA;?></strong> con domicilio en <strong><?= $empresa->DIRECCION;?></strong>, conduce a la <strong>formulaci&oacute;n de cargos</strong> estando demostrado objetivamente el incumplimiento en la contrataci&oacute;n de aprendices que le fueron regulados, tomando lo se&ntilde;alado en el </span>Art&iacute;culo 14 del Decreto 933 de 2003, que consagra &ldquo;<strong>Art&iacute;culo 14&deg;. Incumplimiento de la cuota de aprendizaje </strong>o monetizaci&oacute;n. El Servicio Nacional de Aprendizaje -SENA impondr&aacute; multas mensuales hasta por un salario m&iacute;nimo mensual legal vigente, conforme a lo establecido en el art&iacute;culo 13 numeral 13 de la Ley 119 de 1994, cuando el empleador incumpla con la vinculaci&oacute;n o monetizaci&oacute;n de la cuota m&iacute;nima de aprendices, de conformidad con lo previsto en el presente Decreto.</p>
        <p class="MsoNormal" style="text-align: justify;">&nbsp;</p>
        <p class="MsoNormal" style="text-align: justify;">El incumplimiento en el pago de la cuota mensual de monetizaci&oacute;n dentro del t&eacute;rmino se&ntilde;alado en el art&iacute;culo 130 (Sic), la norma se refiere es al art&iacute;culo 13 del presente Decreto, cuando el patrocinador haya optado por la monetizaci&oacute;n total o parcial de la cuota de aprendices, dar&aacute; lugar al pago de intereses moratorias diarios, conforme la tasa m&aacute;xima prevista por la Superintendencia Bancaria, los cuales deber&aacute;n liquidarse hasta la fecha en que se realice el pago correspondiente.</p>
        <p class="MsoNormal" style="text-align: justify;">&nbsp;</p>
        <p class="MsoNormal" style="text-align: justify;"><strong>Par&aacute;grafo. </strong>La cancelaci&oacute;n de la multa no exime al patrocinador del pago del valor equivalente a la monetizaci&oacute;n por cada una de las cuotas dejadas de cumplir.</p>
        <p class="MsoNormal" style="text-align: justify;"><strong>R E S U E L V E</strong></p>
        <p class="MsoNormal" style="text-align: justify;"><strong><span style="letter-spacing: .3pt;">&nbsp;</span></strong></p>
        <p class="MsoNormal" style="text-align: justify;">

            3 pagina <p class="MsoNormal" style="text-align: justify;"><strong><span style="font-size: 8.0pt; font-family: 'Tahoma','sans-serif'; letter-spacing: -.15pt;">TERCERO:</span></strong><span style="font-size: 8.0pt; font-family: 'Tahoma','sans-serif'; letter-spacing: -.15pt;"> Dentro de los quince (15) d&iacute;as h&aacute;biles siguientes a la notificaci&oacute;n de la presente providencia el Representante Legal o Apoderado de la empresa <strong><?= $empresa->NOMBRE_EMPRESA;?></strong> con nit <strong><?= $empresa->CODEMPRESA;?></strong></span><span style="font-size: 8.0pt; font-family: 'Tahoma','sans-serif'; mso-fareast-language: ES-CO;">, podr&aacute; presentar sus descargos por escrito, aportar, controvertir o solicitar la pr&aacute;ctica de pruebas que a su costa considere pertinentes y que sean conducentes de conformidad con el Art&iacute;culo 47 de la Ley 1437 de 2011.</span></p>
        <p class="MsoNormal" style="text-align: justify;"><span style="font-size: 11.0pt; font-family: 'Tahoma','sans-serif';">&nbsp;</span></p>
        <p class="MsoNormal" style="text-align: justify; mso-hyphenate: none; tab-stops: -36.0pt;"><strong><span style="font-size: 8.0pt; font-family: 'Tahoma','sans-serif'; letter-spacing: -.15pt;">CUARTO: </span></strong><span style="font-size: 8.0pt; font-family: 'Tahoma','sans-serif'; letter-spacing: -.15pt;">Contra&nbsp;la presente decisi&oacute;n no procede recurso alguno.</span></p>
        <p class="MsoNormal" style="text-align: justify; mso-hyphenate: none; tab-stops: -36.0pt;"><span style="font-size: 8.0pt; font-family: 'Tahoma','sans-serif'; letter-spacing: -.15pt;">&nbsp;</span></p>
        <p class="MsoNormal" style="text-align: justify; mso-hyphenate: none; tab-stops: -36.0pt;"><strong><span style="font-size: 8.0pt; font-family: 'Tahoma','sans-serif'; letter-spacing: -.15pt;">QUINTO: </span></strong><span style="font-size: 8.0pt; font-family: 'Tahoma','sans-serif'; letter-spacing: -.15pt;">L&iacute;brense las comunicaciones pertinentes.</span></p>
        <p class="MsoNormal" style="text-align: justify;"><strong><span lang="ES" style="font-size: 8.0pt; font-family: 'Tahoma','sans-serif'; letter-spacing: .3pt; mso-ansi-language: ES;">&nbsp;</span></strong></p>
        <p class="MsoBodyText" style="text-align: center;" align="center"><span style="font-size: 8.0pt; font-family: 'Tahoma','sans-serif';">&nbsp;</span></p>
        <p class="MsoBodyText3" style="text-align: center; tab-stops: 212.65pt;" align="center"><strong><span style="font-family: 'Tahoma','sans-serif';">NOTIFIQUESE, COMUN&Iacute;QUESE Y C&Uacute;MPLASE</span></strong></p>
        <p class="MsoBodyText3" style="text-align: center; tab-stops: 212.65pt;" align="center"><span style="font-family: 'Tahoma','sans-serif';">&nbsp;</span></p>
        <p class="MsoBodyText3" style="text-align: center; tab-stops: 212.65pt;" align="center"><span style="font-family: 'Tahoma','sans-serif';">&nbsp;</span></p>
        <p class="MsoBodyText3" style="text-align: center; tab-stops: 212.65pt;" align="center"><strong><span style="font-family: 'Tahoma','sans-serif';">JAIME GARCIA DIMOTOLI</span></strong></p>
        <p class="MsoBodyText3" style="text-align: center; tab-stops: 212.65pt;" align="center"><span style="font-family: 'Tahoma','sans-serif';">Director Regional (e) Distrito capital</span></p>
        <p class="MsoBodyText3" style="text-align: justify; tab-stops: 7.0cm 212.65pt;"><span style="font-family: 'Tahoma','sans-serif';">&nbsp;</span></p>
        <p class="MsoBodyText"><span style="font-size: 8.0pt; font-family: 'Tahoma','sans-serif';">Proyecto:</span></p>
        <p class="MsoBodyText"><span style="font-size: 8.0pt; font-family: 'Tahoma','sans-serif';">Elaboro:</span></p>
        <p class="MsoNormal" style="text-align: justify;"><span style="font-size: 8.0pt; font-family: 'Tahoma','sans-serif';">(n&uacute;mero de radicaci&oacute;n del expediente)</span></p>
        <p>&nbsp;</p>
        <p class="MsoNormal">&nbsp;</p> 

    </textarea>
    
    
</form>
        

