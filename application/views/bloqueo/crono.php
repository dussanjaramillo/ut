<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>


<script>
    $(".preload, .load").hide();
    var BASE_URL = "<?php echo base_url("index.php/flujo/quitaBloqueo"); ?>";
    $(document).ready(ini);
    function ini(){
        $('#myModal').modal({ backdrop: 'static', keyboard: false });

        
        $("#sinrespuesta").click(function(){
            $('#myModal').modal("hide");
            if($("#fno").attr("action")!="" && $("#fno").attr("action")!="0"){
                $("#fno").submit();
            }

        });
        
        $('#volver').click(function(){
            $('#myModal').modal("hide");
            $( "body" ).append( '<input type=hidden id=blq value=volv>'); 
            //history.reload(-1);
        });
        
        $('#continuar').click(function(){
            if(<?php echo $bloq['id']; ?>==0 || <?php echo $bloq['id']; ?>=='0'){//alert(":-O");
            }else{
                var c = confirm("Se va a eliminar este bloqueo [<?php echo $bloq['id']; ?>]. ");//+BASE_URL
                if(c==false){
                    return false;
                }
            }
            $("#bas").load(BASE_URL,{id: <?php echo $bloq['id']; ?>},function(){
                if($("#fsi").attr("action")!="" && $("#fsi").attr("action")!="0"){
                    $("#fsi").submit();
                }
                $('#myModal').modal("hide");
                $( "body" ).append( '<input type=hidden id=blq value=2>'); 

            });
        });
    }
</script>

<form id="fno" method="post" action="<?php echo $bloq['no']; ?>" >
    <?php echo $parametros; ?>
</form>



<form id="fsi" method="post" action="<?php echo $bloq['si']; ?>" >
    <center>
    <?php echo $parametros; ?>














    </center>
</form>

<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"> 
    <div class="modal-header"> 
        
        <form action="<?php echo "url"; ?>" >
            <center>
            <?php 

            $fecha_vence = strtotime($bloq['vence']);

            $diaSem[1] = 'Lunes'; $diaSem[2] = 'Martes'; $diaSem[3] = 'Miércoles';  $diaSem[4] = 'Jueves'; 
            $diaSem[5] = 'Viernes'; $diaSem[6] = 'Sábado'; $diaSem[0] = $diaSem[7] = 'Domingo';
            $cmz = strtotime($bloq['comienza']);
            $vnc = strtotime($bloq['vence']);

            echo "<h3>La actividad actual se encuentra bloqueada desde el: ".$diaSem[date("N",$cmz)]." ".$bloq['comienza']."</h3>";
            echo "<p><br><em>".$bloq['texto']."</em><br>";
           
            $dvence = $diaSem[date("N",$vnc)];
            if($bloq['vencido']==1){

                echo "<h1>vence el: $dvence ".$bloq['vence']."</h1>";
            }else if($bloq['vencido']==2){

                echo "<h1 style='color:red'>el tiempo venci&oacute; el: $dvence ".$bloq['vence']."</h1>";
            }/* To change this template, choose Tools | Templates and open the template in the editor. */ 

            ?>
            </center>    
        </form>                
    </div>
    

    <div class="modal-footer"> 
        <button id="volver" name="volver" class="btn" data-dismiss="modal">Volver</button>         
        <?php
        if($bloq['vencido']==1 && $bloq['si']!=".OCULTAR"){
        ?>
            <button type='submit' name='continuar' id='continuar' class='btn btn-primary' ><i class='icon-arrow-right icon-white' ></i> Desbloquear</button>
        <?php
        }else if($bloq['vencido']==2 && $bloq['no']!=".OCULTAR"){
        ?>
            <button type='submit' name='sinrespuesta' id='sinrespuesta' class='btn btn-danger' ><i class='icon-minus-sign icon-white' ></i> No hubo respuesta</button>
        <?php
        }
        ?>
        
    </div>
</div>

<div id="bas">...</div>