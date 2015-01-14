<?php
$button =   array('name'=>'cancelar_actacontabi','id'=>'cancelar_actacontabi','content'=>'<i class=""></i> Cancelar','class'=>'btn btn-success btn1');
$button1 =   array('name'=>'aceptar_actacontabi','id'=>'aceptar_actacontabi','content'=>'<i class=""></i> Aceptar','class'=>'btn btn-success btn1');
$Cabeza = '<br>
    <div align="center">
    <img src="' . base_url('img/Logotipo_SENA.jpg') . '" width="100" height="100" />
    </div>';
    $logo  = array('name'=>'logo' ,'id'=>'logo','type'=>'hidden','value'=>$Cabeza);
?>
<div align="center">
<img src="<?=  base_url()?>/img/Logotipo_SENA.jpg" align="middle" width="200" height="200">
</div> 
<?php if($tipo=="porcentaje_calculos"){?>
<div style="max-width: 886px;min-width: 320px;padding: 40px;width: 88%;background: none repeat scroll 0 0 #F0F0F0;margin: auto;">
    <textarea name="content" id="targetTextArea" style="width:500px; height: 300px" >
<br>
Bogotá D.C. <?= $hoy = date("d/m/Y");?>
<br>
Señor:
<br>
______________________
<br>
Asunto: Devolucion Nit: <?= $nit?>
<br>
Apreciado Señor: ____________________________
<br><br>
Atentamente le informo que revisada la base de datos se require la devolucion del dinero al NIT <?= $nit?> por los 
<br>
siguientes conceptos:
<br>
<br>
<table border="1" bordercolor="#FC7323" align="center">
        <tr bgcolor="#FC7323">
            <td>Planilla</td>
            <td>Periodo</td>
            <td>Nombre</td>
            <td>Porcentaje</td>
            <td>IBC Errado</td>
            <td>Pago Errado</td>
            <td>IBC Real</td>
            <td>Pago Correcto</td>
            <td>Diferencia</td>
        </tr>
    <?php  foreach ($planilla->result_array as $data) { ?>
        <tr>
            <td><?= $data['COD_PLANILLAUNICA']?></td>
            <td><?= $data['PERIDO_PAGO']?></td>
            <td><?= $data['PRIMER_APELLIDO']?><?= $data['SEGUN_APELLIDO']?><?= $data['PRIMER_NOMBRE']?><?= $data['SEGUN_NOMBRE']?></td>
            <td><?= $porcentaje?></td>
            <td><?= $data['APORTE_OBLIG'] ?></td>
            <td><?= $data['APORTE_OBLIG']*0.02 ?></td>
            <td><?= ($data['APORTE_OBLIG']*0.02)*($cuadro/100) ?></td>
            <td><?= (($data['APORTE_OBLIG']*0.02)*($cuadro/100))*0.02 ?></td>
            <td><?= ($data['APORTE_OBLIG']*0.02)-((($data['APORTE_OBLIG']*0.02)*($cuadro/100))*0.02)?></td>
        </tr>

    <?php } ?>
    </table>
<br><br><br><br>
Coordialmente:
<br><br>
__________________
<br>
Tecnico de Cartera
    </textarea>
    <br><?= form_input($logo)?>
    <div align="center">
        <?= form_button($button1)?>
        <?= form_button($button)?>
    </div>
</div>
<?php }else{ ?>
<div style="max-width: 886px;min-width: 320px;padding: 40px;width: 88%;background: none repeat scroll 0 0 #F0F0F0;margin: auto;">
    <textarea name="content" id="targetTextArea" style="width:500px; height: 300px" >
<div align="center">
<img src="<?=  base_url()?>/img/Logotipo_SENA.jpg" align="middle" width="200" height="200">
</div>  
<br>
Bogotá D.C. <?= $hoy = date("d/m/Y");?>
<br>
Señor:
<br>
______________________
<br>
Asunto: Devolucion Nit: <?= $ibc?>
<br>
Apreciado Señor: ____________________________
<br><br>
Atentamente le informo que revisada la base de datos se require la devolucion del dinero al NIT <?= $nit?> por los 
<br>
siguientes conceptos:
<br>
<br>
<table border="1" bordercolor="#FC7323" align="center">
        <tr bgcolor="#FC7323">
            <td>Planilla</td>
            <td>Periodo</td>
            <td>Nombre</td>
            <td>Porcentaje</td>
            <td>IBC Errado</td>
            <td>Pago Errado</td>
            <td>IBC Real</td>
            <td>Pago Correcto</td>
            <td>Diferencia</td>
        </tr>
    <?php  foreach ($planilla->result_array as $data) { ?>
        <tr>
            <td><?= $data['COD_PLANILLAUNICA']?></td>
            <td><?= $data['PERIDO_PAGO']?></td>
            <td><?= $data['PRIMER_APELLIDO']?><?= $data['SEGUN_APELLIDO']?><?= $data['PRIMER_NOMBRE']?><?= $data['SEGUN_NOMBRE']?></td>
            <td><?= $porcentaje?></td>
            <td><?= $ibc?></td>
            <td><?= $ibc*0.02 ?></td>
            <td></td>
            <td><?= $ibc ?></td>
            <td><?= $ibc*($cuadro/100) ?></td>
        </tr>

    <?php } ?>
    </table>
<br><br><br><br>
Coordialmente:
<br><br>
__________________
<br>
Tecnico de Cartera
    </textarea>
    <div align="center">
        <?= form_button($button1)?>
        <?= form_button($button)?>
    </div>
</div>
<?php }?>
<script type="text/javascript" language="javascript" charset="utf-8">
    function redirect_by_post(purl, pparameters, in_new_tab) {
        pparameters = (typeof pparameters == 'undefined') ? {} : pparameters;
        in_new_tab = (typeof in_new_tab == 'undefined') ? true : in_new_tab;
        var form = document.createElement("form");
        $(form).attr("id", "reg-form").attr("name", "reg-form").attr("action", purl).attr("method", "post").attr("enctype", "multipart/form-data");
        if (in_new_tab) {
            $(form).attr("target", "_blank");
        }
        $.each(pparameters, function(key) {
            $(form).append('<textarea name="' + key + '" >'+this+'</textarea>');
        });
        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    return false;
    }
     
     $('#aceptar_actacontabi').on('click',function(){
         var informacion    = tinymce.get('targetTextArea').getContent();
         var nombre         = "MEMORIAL";
//         var regi_insol     = $("#regimen").val();
//         var motivo         = $("input[name='opcion']:checked").val();
//         var nit            = $("#nit").val();
//         var regimen            = $("#regimen").val();
         var url = "<?=base_url()?>index.php/devolucion/pdf";
//         var url1 = "<?=base_url()?>index.php/insolvencia/elaborar_audiencia";
         var logo   = $('#logo').val();
        
         informacion = logo+informacion;
         redirect_by_post(url, {
            informacion: informacion,
            nombre: nombre,
//            regi_insol:regi_insol,
//            motivo:motivo,
//            viene:'0'
         }, true);
//         redirect_by_post(url1, {
//            nit: nit,
//            regimen:regimen,                       
//         }, false);
          
     });
    $("#cancelar_actacontabi").confirm({
        title:"Confirmacion",
        text:"¿Esta seguro de cancelar?",
        confirm: function(button) {
            location.href="<?=base_url('index.php/devolucion/realizar_calculos')?>";       
        },
        cancel: function(button) {
            
        },
        confirmButton: "SI",
        cancelButton: "NO"
    });
    
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
</script>