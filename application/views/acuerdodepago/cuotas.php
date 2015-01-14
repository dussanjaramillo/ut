<?php
$juridicoVal = array('id'=>'juridico','name'=>'juridico','value'=>$juridico,'type'=>'hidden');
echo form_input($juridicoVal);
?>

    <select id="cuotasiden" class="oblinit">    
        <option value="">-Seleccionar-</option>
        <?php         
            for($i = 1; $i <= @$TotalCuota ; $i++){ ?>
            <option value="<?= $i ?>"><?= $i ?> </option>       
        <?php } ?>
            </option>   
    </select>


<script>
    $('#cuotasiden').change(function() {
        $(".ajax_load").show("slow");      
        var ncuotas         = $('#cuotasiden').val();
        var saldocapital    = $('#vfinanciar').val();        
        var url             = "<?= base_url('index.php/acuerdodepago/consultaTasa')?>";         
        $.post(url,{cuotas:ncuotas,proyectar:saldocapital})
        .done(function(data){
            $('#vcuota').val((data.cuota).toFixed(2));
            $(".ajax_load").hide();      
        }).fail(function(data){
            
        });     
    });

</script>
