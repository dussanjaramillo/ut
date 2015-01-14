<!-- Responsable: Leonardo Molina-->
<?php
    $Cabeza = '<br>
    <div align="center">
    <img src="' . base_url('img/Logotipo_SENA.jpg') . '" width="100" height="100" />
    </div>';
    
    if(!isset($registros->COD_FISCALIZACION)){
        echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>No existe una fiscalizacion para este numero de estado de cuenta: '.$estado_cuenta.'</div>';
        die;
    }
    
    $ecuenta = array('name'=>'ecuenta' ,'id'=>'ecuenta','type'=>'hidden','value'=>$estado_cuenta);
    $nit  = array('name'=>'nit'  ,'id'=>'nit','type'=>'hidden','value'=>$registros->CODEMPRESA); //echo $data['NOMBRE_CONCEPTO;
    $logo = array('name'=>'logo' ,'id'=>'logo','type'=>'hidden','value'=>$Cabeza);
    $cfisc= array('name'=>'cfisc','id'=>'cfisc','type'=>'hidden','value'=>$registros->COD_FISCALIZACION);
    
    $attributes = array("id" => "formCargos"); 
?>

<?= form_open(base_url('index.php/autocargos/addAutocargos'),$attributes)?>

<?= form_input($ecuenta)?>
<?= form_input($nit)?>
<?= form_input($logo)?>
<?= form_input($cfisc)?>
<?=$Cabeza?>  

<textarea name="informacion" id="informacion" style="width:100%; height: 300px">      
<p align="center">Regional Distrito Capital</p>

        <p align="center">11-1010-1</p>
        <p align="center">AUTO</p>
        <p align="center">&ldquo;Por el cual se Formulan Cargos &rdquo;</p>
        <p>El Director del Servicio Nacional de Aprendizaje SENA Regional Distrito Capital,&nbsp; en uso de sus facultades legales y en especial las conferidas por el art&iacute;culo 33 de la Ley 789 de 2002 y de conformidad con el procedimiento sancionatorio establecido en la Ley 1437 de 2011 C&oacute;digo de Procedimiento Administrativo y de lo Contencioso Administrativo y,</p>
        <p  >&nbsp;</p>
        <p  >&nbsp;</p>
        <p  >CONSIDERANDO</p>
        <p  >&nbsp;</p>
        <p>Que mediante de escrito radicado en el SENA el d&iacute;a <?= $dia?> del mes <?= $mes?> del a&ntilde;o <?= $ano?>, Nis xxxxxxxxxxxx de fecha xxxxxxxxxx, la empresa <?= $registros->NOMBRE_EMPRESA;?> con nit <?= $registros->CODEMPRESA;?>, procedi&oacute; a informar al SENA las matrices de sus empleados conforme al listado de oficios y ocupaciones establecido en el Decreto 620 de 2005, con el fin que le fuera regulada la cuota de aprendices se&ntilde;alada en la Ley 789 de 2002 y dem&aacute;s normas concordantes y complementarias.</p>
        <p>&nbsp;</p>
        <p>Que por Resoluci&oacute;n No. xxxx del xxxxxxx, la cual qued&oacute; debidamente ejecutoriada el xxxxxxxxxxxxxxx se le fij&oacute; al empleador <?= $registros->NOMBRE_EMPRESA;?>, una cuota de xxx (xxx) aprendiz, para ser contratados a nivel Nacional.</p>
        <p>&nbsp;</p>
        <p>Que el d&iacute;a xx de xx 2012, el fiscalizador xxxx funcionario del SENA realiz&oacute; seguimiento y control de contrato de aprendizaje al empleador <?= $registros->NOMBRE_EMPRESA;?>, con el prop&oacute;sito de verificar el cumplimiento de la cuota de aprendices fijada y de ser pertinente, elaborar el correspondiente estado de cuenta.</p>
        <p>Que de conformidad con lo anterior y con base en el estado de cuenta en mención, se adelantara la correspondiente investigación de carácter sancionatorio contra el empleador <?= $registros->NOMBRE_EMPRESA;?> identificado con nit <?= $registros->CODEMPRESA;?>.</p>
        <p>(Para cada caso los hechos serán más largos o no)
        <p>&nbsp;</p>
        <p>2. DISPOSICIONES PRESUNTAMENTE VIOLADAS:</p>
        <p>&nbsp;</p>
        <p>Que la Ley 789 de 2002  en su artículo 32 establece; “Artículo 32. Empresas obligadas a la vinculación de aprendices. Las empresas privadas, desarrolladas por personas naturales o jurídicas, que realicen cualquier tipo de actividad económica diferente de la construcción, que ocupen un número de trabajadores no inferior a quince (15), se encuentran obligadas a vincular aprendices para los oficios u ocupaciones que requieran formación académica o profesional metódica y completa en la actividad económica que desempeñan.
        <p>Las empresas industriales y comerciales del Estado y las de Econom&iacute;a mixta del orden Nacional, departamental, distrital y municipal, estar&aacute;n obligadas a la vinculaci&oacute;n de aprendices en los t&eacute;rminos de esta ley. Las dem&aacute;s entidades p&uacute;blicas no estar&aacute;n sometidas a la cuota de aprendizaje, salvo en los casos que determine el Gobierno Nacional&rdquo;. (&hellip;)</p>
        <p>&nbsp;</p>
        <p>Que el art&iacute;culo 33 de la Ley 789 de 2002 establece: &ldquo;La determinaci&oacute;n del n&uacute;mero m&iacute;nimo obligatorio de aprendices para cada empresa obligada la har&aacute; la regional del Servicio Nacional de Aprendizaje, SENA, del domicilio principal de la empresa, en raz&oacute;n de un aprendiz por cada 20 trabajadores y uno adicional por fracci&oacute;n de diez(10) o superior que no exceda de veinte. Las Empresas que tengan entre quince (15) y veinte (20) trabajadores, tendr&aacute;n un aprendiz. &ldquo;</p>
        <p>&nbsp;</p>
        <p>La cuota se&ntilde;alada por el SENA deber&aacute; notificarse previamente al representante legal de la respectiva empresa, quien contar&aacute; con el t&eacute;rmino de 5 d&iacute;as h&aacute;biles para objetarla, en caso de no ce&ntilde;irse a los requerimientos de mano de obra calificada demandados por la misma. Contra el acto administrativo que fije la cuota proceder&aacute;n los recursos de ley.</p>
        <p>&nbsp;</p>
        <p>PAR&Aacute;GRAFO. Cuando el contrato de aprendizaje incluida dentro de la cuota m&iacute;nima se&ntilde;alada por el SENA termine por cualquier causa, la empresa deber&aacute; reemplazar al aprendiz para conservar la proporci&oacute;n que le haya sido asignada. </p>
        <p>&nbsp;</p>
        <p>Que el art&iacute;culo 8&deg; del Decreto 933 de 2003 dispone que &ldquo;Terminada la relaci&oacute;n de aprendizaje por cualquier causa, la empresa patrocinadora deber&aacute; remplazar al aprendiz para conservar la proporcionalidad&rdquo;, debiendo informar inmediatamente al SENA la contrataci&oacute;n del nuevo aprendiz.</p>
        <p>&nbsp;</p>
        <p>Que el art&iacute;culo 14 del Decreto 933 de 2003, se&ntilde;ala que el SENA &ldquo;impondr&aacute; multas mensuales hasta por un salario m&iacute;nimo mensual legal vigente, conforme a lo establecido en el art&iacute;culo 13, numeral 13 de la Ley 119 de 1994, cuando el empleador incumpla con la vinculaci&oacute;n o monetizaci&oacute;n de la cuota m&iacute;nima de aprendices de conformidad con lo previsto en el presente decreto&rdquo;.</p>
        <p>&nbsp;</p>
        <p>Que la Ley 789 de 2002 y el Decreto 933 de 2003 le otorgan al empleador la opci&oacute;n de escoger entre contratar aprendices o monetizar total o parcialmente la cuota, disponiendo el par&aacute;grafo del art&iacute;culo 12 del Decreto 933 de 2003 que: &ldquo;En ning&uacute;n caso el cambio de decisi&oacute;n por parte del patrocinador conllevar&aacute; el no pago de la cuota de monetizaci&oacute;n o interrupci&oacute;n en la contrataci&oacute;n de aprendices frente al cumplimiento de las obligaciones&rdquo;</p>
        <p>&nbsp;</p>
        <p>3. FORMULACI&Oacute;N DE CARGOS:</p>
        <p>&nbsp;</p>
        <p>Que de la evaluaci&oacute;n de las averiguaciones preliminares contra la empresa, <?= $registros->NOMBRE_EMPRESA;?> Nit, <?= $registros->CODEMPRESA;?> con domicilio en <?= $registros->DIRECCION;?>, conduce a la formulaci&oacute;n de cargos estando demostrado objetivamente el incumplimiento en la contrataci&oacute;n de aprendices que le fueron regulados, tomando lo se&ntilde;alado en el Art&iacute;culo 14 del Decreto 933 de 2003, que consagra &ldquo;Art&iacute;culo 14&deg;. Incumplimiento de la cuota de aprendizaje o monetizaci&oacute;n. El Servicio Nacional de Aprendizaje -SENA impondr&aacute; multas mensuales hasta por un salario m&iacute;nimo mensual legal vigente, conforme a lo establecido en el art&iacute;culo 13 numeral 13 de la Ley 119 de 1994, cuando el empleador incumpla con la vinculaci&oacute;n o monetizaci&oacute;n de la cuota m&iacute;nima de aprendices, de conformidad con lo previsto en el presente Decreto.</p>
        <p>&nbsp;</p>
        <p>El incumplimiento en el pago de la cuota mensual de monetizaci&oacute;n dentro del t&eacute;rmino se&ntilde;alado en el art&iacute;culo 130 (Sic), la norma se refiere es al art&iacute;culo 13 del presente Decreto, cuando el patrocinador haya optado por la monetizaci&oacute;n total o parcial de la cuota de aprendices, dar&aacute; lugar al pago de intereses moratorias diarios, conforme la tasa m&aacute;xima prevista por la Superintendencia Bancaria, los cuales deber&aacute;n liquidarse hasta la fecha en que se realice el pago correspondiente.</p>
        <p>&nbsp;</p>
        <p>Par&aacute;grafo. La cancelaci&oacute;n de la multa no exime al patrocinador del pago del valor equivalente a la monetizaci&oacute;n por cada una de las cuotas dejadas de cumplir.</p>
        <p>R E S U E L V E</p>
        <p>&nbsp;</p>
        <p>

            3 pagina <p>TERCERO: Dentro de los quince (15) d&iacute;as h&aacute;biles siguientes a la notificaci&oacute;n de la presente providencia el Representante Legal o Apoderado de la empresa <?= $registros->NOMBRE_EMPRESA;?> con nit <?= $registros->CODEMPRESA;?><span style="font-size: 8.0pt; font-family: 'Tahoma','sans-serif'; mso-fareast-language: ES-CO;">, podr&aacute; presentar sus descargos por escrito, aportar, controvertir o solicitar la pr&aacute;ctica de pruebas que a su costa considere pertinentes y que sean conducentes de conformidad con el Art&iacute;culo 47 de la Ley 1437 de 2011.</p>
        <p>&nbsp;</p>
        <p  >CUARTO: Contra&nbsp;la presente decisi&oacute;n no procede recurso alguno.</p>
        <p  >&nbsp;</p>
        <p  >L&iacute;brense las comunicaciones pertinentes.</p>
        <p  align="center">NOTIFIQUESE, COMUN&Iacute;QUESE Y C&Uacute;MPLASE</p>
        <p  align="center">&nbsp;</p>
        <p  align="center">&nbsp;</p>
        <p  align="center">JAIME GARCIA DIMOTOLI</p>
        <p  align="center">Director Regional (e) Distrito capital</p>
        <p >&nbsp;</p>
        <p >Proyecto:</p>
        <p >Elaboro:</p>
        <p>(n&uacute;mero de radicaci&oacute;n del expediente)</p>
        <p>&nbsp;</p>
        <p >&nbsp;</p> 
    </textarea>
    
    <div class="modal-footer">
        <a href="#" class="btn btn-default" id="cancelar" data-dismiss="modal">Cancelar</a>
        <a href="#" class="btn btn-primary" id="generarPdf" onclick="enviar_pdf()"><li class="fa fa-download"></li> Descargar Auto</a>
    </div>
<?= form_close()?>

<form id="form" name="form" target = "_blank"  method="post" action="<?php echo base_url('index.php/autocargos/pdf') ?>">
    <textarea id="descripcion_pdf" name="descripcion_pdf" style="width: 100%;height: 300px;display: none "></textarea>  
    <input type="hidden" name="nombre" id="nombre" value="">
</form>
     
    <script type="text/javascript">
        
 tinymce.init({
    
    selector: "textarea#informacion",
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
     
       function enviar_pdf(){
            var informacion = tinymce.get('informacion').getContent();
            var ecuenta   = $('#ecuenta').val();
            var nit    = $('#nit').val();
            var logo   = $('#logo').val();
            var cfisc  = $('#cfisc').val();
            var nombre = "Auto"+ecuenta+"_"+nit;
            document.getElementById("nombre").value = nombre;
            document.getElementById("descripcion_pdf").value = informacion;
            $("#form").submit();
            enviar();
        }
        
        
        function enviar(){
            alert("Auto de cargos generado.");
             $('#formCargos').submit();
        }
        
        $('#cancelar').on('click', function() {
        $('.modal-body').html('<div align="center"><img src="<?= base_url() ?>/img/27.gif" width="150px" height="150px" /></div>');
        $('#modal').modal('hide').removeData();
    });
    $('.close').on('click', function() {
        $('.modal-body').html('<div align="center"><img src="<?= base_url() ?>/img/27.gif" width="150px" height="150px" /></div>');
        $('#modal').modal('hide').removeData();
    });
    </script>
</form>
        

