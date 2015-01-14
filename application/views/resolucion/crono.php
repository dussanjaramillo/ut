

<script>
	//$(".preload, .load").hide();
//    var BASE_URL = "<?php // echo base_url("index.php/flujo/quitaBloqueo"); ?>";
    $(document).ready(ini);
    function ini(){
        $('#resultado2').modal({ backdrop: 'static', keyboard: false });
        //$("#resultado").click();
        
    }
</script>

<form id="fno" action="<?php //echo $bloq['no']; ?>" >
</form>



<div id="resultado2" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="resultadoLabel" aria-hidden="true"> 
    <div class="modal-header"> 
        
        <form action="<?php echo "url"; ?>" >
            <center>
            <?php //$bloq['id'];
            $fecha_vence = strtotime($bloq['vence']);

            $diaSem[1] = 'Lunes'; $diaSem[2] = 'Martes'; $diaSem[3] = 'Miércoles';  $diaSem[4] = 'Jueves'; 
            $diaSem[5] = 'Viernes'; $diaSem[6] = 'Sábado'; $diaSem[0] = $diaSem[7] = 'Domingo';
            $cmz = strtotime($bloq['comienza']);
            $vnc = strtotime($bloq['vence']);
            
            echo "<h3>La actividad actual se encuentra bloqueada desde el: ".$diaSem[date("N",$cmz)]." ".$bloq['comienza']."</h3>";
            echo "<p><br><em>".$bloq['texto']."</em><br>";
            $dvence = $diaSem[date("N",$vnc)];
            if($fecha_vence>strtotime(date("Y-m-d"))){
                echo "<h1>vence el: $dvence ".$bloq['vence']."</h1>";
            }else{
                echo "<h1 style='color:red'>el tiempo venci&oacute; el: $dvence ".$bloq['vence']."</h1>";
            }/* To change this template, choose Tools | Templates and open the template in the editor. */ 
            
            ?>
            </center>    
        </form>                
    </div> 
    <div class="modal-footer"> 
        <button id="volver" name="volver" class="btn" data-dismiss="modal">Volver</button>         
        <?php
        if($fecha_vence>strtotime(date("Y-m-d"))){
        ?>
            <button type='submit' name='continuar' id='continuar' class='btn btn-primary' ><i class='icon-arrow-right icon-white' ></i> Desbloquear</button>
        <?php
        }else if($fecha_vence<=strtotime(date("Y-m-d"))){
        ?>
            <button type='submit' name='sinrespuesta' id='sinrespuesta' class='btn btn-danger' ><i class='icon-minus-sign icon-white' ></i> No hubo respuesta</button>
        <?php
        }
        ?>
        
    </div>
</div>

<div id="bas">...</div>