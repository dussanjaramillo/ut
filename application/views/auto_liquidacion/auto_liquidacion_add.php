
<?php    
if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<?php
$cRuta = $rutaTemporal;                    
if (is_dir($cRuta)) {
    $handle = opendir($cRuta);
        while ($file = readdir($handle)) {
            if (is_file($cRuta.$file)) {
                $plantilla = read_template($cRuta.$file);
                $datanotificacion = array('name'=> 'notificacion','id'=> 'notificacion','value'=>$plantilla,'width'=> '80%','heigth'=> '50%'); 
            } 
        }
}else{
    $datanotificacion = array('name'=> 'notificacion','id'=> 'notificacion','value'=>@$filas2,'width'=> '80%','heigth'=> '50%'); 
}
$data           =   array('name'=>'comentarios','id'=>'comentarios','value'=> set_value('comentarios'),'width'=> '100%','heigth'=>'5%','style'=> 'width:98%','required'=>'true');
$dataPDF        =   array('name'=>'pdf','id' => 'pdf','value' => 'Generar PDF','type' => 'button','content' => '<i class="fa fa-file"></i> Generar PDF','class' => 'btn btn-success',);
$dataFisca      =   array('name'=>'fiscalizacion','id'=>'fiscalizacion','type'=>'hidden','value'=>$fiscalizacion);
$dataNit        =   array('name'=>'nitempresa','id'=>'nitempresa','type'=>'hidden','value'=>$nitempresa);
$dataTipo       =   array('name'=>'tipo','id'=>'tipo','type'=>'hidden','value'=>$tipo);
$dataNum        =   array('name'=>'auto_num','id'=>'auto_num','type'=>'hidden','value'=>@$auto_num);
$dataTemp       =   array('name'=>'temporal','id'=>'temporal','type'=>'hidden');
$dataLiquid     =   array('name'=>'liquidacion','id'=>'liquidacion','type'=>'hidden');
$dataguardar    =   array('name'=>'guardar','id' => 'guardar','value' => 'Guardar','type' => 'button','content' => '<i class="fa fa-floppy-o fa-lg"></i> Guardar','class' => 'btn btn-success',);                        
$dataenviar     =   array('name'=>'tmp','id' => 'tmp','value' => 'Enviar','type' => 'button','content' => '<i class="fa fa-file-text-o fa-lg"></i> Enviar','class' => 'btn btn-success');
$datacancel     =   array('name'=>'button','id' => 'cancel-button','value' => 'Cancelar','type' => 'button','onclick' => 'window.location=\''.base_url().'index.php/auto_liquidacion/autos_info\';','content' => '<i class="fa fa-undo"></i> Cancelar','class' => 'btn btn-success');

$attributes = array('class'=>'form-inline','id'=>'LiquidacionAdd','name'=>'LiquidacionAdd','method'=>'POST');
echo form_open_multipart(current_url(),$attributes); 
//echo form_open_multipart("auto_liquidacion/guardar_auto",$attributes);
echo @$custom_error;
echo form_input($dataFisca);
echo form_input($dataNit);
echo form_input($dataNum);
echo form_input($dataTipo);
echo form_input($dataTemp);
echo form_input($dataLiquid);
?>
        
    <table cellspacing="0" cellspading="0" border="0" align="center">
        <tr>
            <td>
            <?php
            $this->load->view('auto_liquidacion/cabecera',$this->data); 
            ?>
            </td>
        </tr>
        <tr>
        <td><?= form_textarea($datanotificacion); ?></td>
        </tr><tr><td><br></td></tr>
        <tr>            
            <td>                
                <?php
                echo form_label('Asignar a&nbsp;<span><font color="red"><b>*</b></font></span>', 'asignando');?> 
                <select name="asignado" id="asignado" required>
                    <option value=''>--Seleccione--</option>
                        <?php foreach($asignado as $row){
                        echo '<option value="'.$row->IDUSUARIO."-".$row->IDCARGO.'">'.$row->NOMBRES.' '.$row->APELLIDOS.'</option>';}?>
                </select>            
            </td>            
        </tr><tr><td><br></td></tr>
        <tr>
            <p>
            <td><?= 'Comentarios&nbsp;<span class="required"><font color="red"><b>*</b></font></span>'.form_textarea($data);?></td>                    
            </p>            
        </tr><tr><td><br><br><br><br></td></tr> 
        <tr>
            <td align='center'>
                <div style="display:none" id="error" class="alert alert-danger"></div>
            </td>
        </tr>
        <tr align ='center'>
            <td>
                <?php
                echo form_button($dataguardar)."&nbsp;&nbsp;";
                echo form_button($dataenviar)."&nbsp;&nbsp;";
                echo form_button($dataPDF)."&nbsp;&nbsp;";
                echo form_button($datacancel);
                ?>
            </td>
        </tr>

    </table>
        <div id="ajax_load" class="ajax_load" style="display: none">
            <div class="preload" id="preload" >
                <img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
            </div>
        </div>
<?php echo form_close(); ?>

<script type="text/javascript">   
    tinymce.init({
        language : "es",
        selector: "textarea#notificacion",
        theme: "modern",
        plugins: [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace visualblocks wordcount visualchars code fullscreen",
            "insertdatetime media nonbreaking save table contextmenu directionality",
            //"emoticons template paste textcolor moxiemanager"
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
      
$(document).ready(function() {    
    window.history.forward(-1);
    tinyMCE.triggerSave();
});

$('#guardar').click(function() {
        $('#error').html("");
        var coment = $('#comentarios').val();
        var asigna = $('#asignado').val();
        var tipo = $('#tipo').val();
        
        var url = '<?php echo base_url('index.php/auto_liquidacion/guardar_auto')?>';
        
        var valida = true;
        if (coment == "") {
            $('#error').show();
            $('#error').html("<font color='red'><b>Ingrese Comentarios</b></font>");
            valida = false;
        }
        else if (asigna == "") {
            $('#error').show();
            $('#error').html("<font color='red'><b>Seleccione Asignar a</b></font>");
            valida = false;
        }
        if (valida == true) {
            $(".ajax_load").show("slow");
            $('#guardar').prop("disabled",true);
            $("#LiquidacionAdd").attr("action", url);
            $('#LiquidacionAdd').removeAttr('target');
            $('#LiquidacionAdd').submit();
        }
});
    

$('#pdf').click(function() {
        tinyMCE.triggerSave();
        var notify = $('#notificacion').val();
        $('#liquidacion').val(notify);
        $("#LiquidacionAdd").attr("action", "<?= base_url('index.php/auto_liquidacion/pdf')?>");
        $('#LiquidacionAdd').attr('target', '_blank');
        $('#LiquidacionAdd').submit();
    });

$('#tmp').click(function() {
        tinyMCE.triggerSave();
        var temp = $('#notificacion').val();
        var tipo = $('#tipo').val();
        $(".ajax_load").show("slow");
        $('#temporal').val(temp);
        $('#tipo').val(tipo);
        $('#LiquidacionAdd').attr("action", "temp");
        $('#LiquidacionAdd').removeAttr('target');
        $('#LiquidacionAdd').submit();
    });
    
</script>