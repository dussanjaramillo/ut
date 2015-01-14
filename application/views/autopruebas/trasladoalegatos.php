<?php 
// Responsable: Leonardo Molina
$DataRadio  = array ('id' => 'resp1','name'=>'resp1','onclick'=> 'generar()','value'=> 'Generar');
$DataRadio1 = array ('id' => 'resp1', 'name'=>'resp1','onclick'=> 'cargar()','value'=> 'Cargar');
$fecha      = array('name'=>'fecha');
$nis        = array('name'=>'nis');
$radicado   = array('name'=>'radicado');
$file       = array('name'=> 'userfile','id' => 'imagen','class' => 'validate[required]');
$button     = array('name'=> 'button','id' => 'trasladoA','value' => 'Continuar','onclick' => 'enviar_pdf()','type' => 'button','content' => '<i class="fa fa-pencil"></i> Continuar','class' => 'btn btn-success');
$button2    = array('name'=> 'button','id' => 'submit-button','value' => 'Guardar','type' => 'submit','content' => '<i class="fa fa-save fa-lg"></i> Guardar','class' => 'btn btn-success');

    $Cabeza = '<br>
    <div align="center">
    <img src="' . base_url('img/Logotipo_SENA.jpg') . '" width="100" height="100" />
    </div>';

    $nAuto = array('name'=>'nAuto' ,'id'=>'nAuto','type'=>'hidden','value'=>$respauto->NUM_AUTOGENERADO);
    $nit   = array('name'=>'nit'   ,'id'=>'nit','type'=>'hidden','value'=>$empresa->CODEMPRESA); //echo $data['NOMBRE_CONCEPTO;
    $logo  = array('name'=>'logo'  ,'id'=>'logo','type'=>'hidden','value'=>$Cabeza);
    $cfisc = array('name'=>'cfisc' ,'id'=>'cfisc','type'=>'hidden','value'=>$respauto->COD_FISCALIZACION);
    
    
    $nEmpresa   = $empresa->NOMBRE_EMPRESA;
    $nitEmpresa = $empresa->CODEMPRESA;
    $dirEmpresa = $empresa->DIRECCION;
    
$attributes = array("id" => "formData"); 

if(isset($respauto->TRASLADO_ALEGATO)){
    if($respauto->TRASLADO_ALEGATO== 'S'){
        echo '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button> El auto se ha trasladado a ALEGATOS en la fecha: '.$respauto->FECHA_TRASLADO_ALEGATO.'</div>';
?>
<?= form_open_multipart(base_url('index.php/autopruebas/enviarAlegatos'))?>
<table>
    <tr>
        <td>Cargar Colilla:</td>
        <td><div class="input-append" id="arch0">
                <input type="file" name="archivo0" id="archivo0" onchange="habboton()" class="btn btn-primary file_uploader">
            </div>
        </td>
    </tr>
    <tr>
        <td><a href="#" class="btn btn-default cancelar" data-dismiss="modal" >Cancelar</a>
            <?= form_input($nAuto)?>
            <?= form_input($nit)?>
            <?= form_input($cfisc)?>
            <?= form_button($button2);?>
        </td>
    </tr>
</table>
<?= form_close()?>

<?php
    }
}else{
?>


        <?= form_open(base_url('index.php/autopruebas/generarAlegatos'),$attributes)?>
        <?= form_input($nAuto)?>
        <?= form_input($nit)?>
        <?= form_input($logo)?>
        <?= form_input($cfisc)?>


        <textarea name="textinfo" id="textinfo" style="width:100%; height: 300px">

        <p>Doctor (a)</p>
        <p><strong>REPRESENTANTE LEGAL</strong></p>
        <p><strong><?= $nEmpresa;?></strong></p>
        <p><strong><?= $dirEmpresa;?></strong>,</p>
        <p>Bogot&aacute; D.C.</p>
        <p><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Asunto: Traslado para Alegar</strong></p>
        <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>NIT: </strong><strong><?=$nitEmpresa;?></strong></p>
        <p>Respetado Doctor:</p>
        <p>&nbsp;</p>
        <p>De manera atenta, de conformidad con los art&iacute;culos 47 y 48 del C&oacute;digo de Procedimiento Administrativo y de lo Contencioso administrativo, me permito allegar <strong>AUTO &ldquo;<em>Por medio del cual se traslada para que alegar</em>&rdquo;</strong>. Para lo cual cuenta con un t&eacute;rmino de diez (10) d&iacute;as al recibo de la presente comunicaci&oacute;n, seg&uacute;n actuaci&oacute;n administrativa adelantada en aplicaci&oacute;n a la Ley anteriormente citada.</p>
        <p>&nbsp;</p>
        <p>Una vez vencido el t&eacute;rmino anteriormente se&ntilde;alado, se proceder&aacute; a proferir el ACTO ADMINISTRATIVO DEFINITIVO, en donde se estudiaran los descargos y alegatos presentados por usted.</p>
        <p>&nbsp;</p>
        <p>Cordialmente,</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p><strong>EDUARDO EFRAIN ROSERO</strong></p>
        <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Coordinador (A) Grupo de Relaciones</p>
        <p>Corporativas E Internacionales</p>
        <p>L. Guerrero.</p>
        <p>&nbsp;</p>

         </textarea>

        <?= form_close();?>

        <div class="modal-footer">
        <a href="#" class="btn btn-default cancelar" data-dismiss="modal" >Cancelar</a>
                        <?=form_button($button);?>
        </div>
<?php
}
?>

<form id="form" name="form" target = "_blank"  method="post" action="<?php echo base_url('index.php/autopruebas/pdf') ?>">
    <textarea id="informacion" name="informacion" style="width: 100%;height: 300px; display:none"></textarea>  
    <input type="hidden" name="nombre" id="nombre" value="">
</form>

<script type="text/javascript">
    
     function enviar_pdf(){
            var informacion = tinymce.get('textinfo').getContent();
            var nit    = $('#nit').val();
            var nAuto   = $('#nAuto').val();
            var nombre = "Alegato"+nit+"_"+nAuto;
            document.getElementById("nombre").value = nombre;
            document.getElementById("informacion").value = informacion;
            $("#form").submit();
            enviar();
        }
        
        function enviar(){
            alert("Se traslada a alegatos.");
             $('#formData').submit();
        }
        
    tinymce.init({
    
    selector: "textarea#textinfo",
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
</script>