<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php if (isset($message)) {echo $message;}?>
<!-- -->
<p><br>
<p>
<center>
    <h3><center>PERFIL MANDAMIENTO PAGO</center></h3>
    <div id="ajax_load" class="ajax_load" style="display: none">
        <div class="preload" id="preload" >
            <img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
        </div>
    </div>
    <table id="styletable">
        <thead>
        <th>PERFIL</th>
        <th>SELECCIONE</th>
        </thead>
        <tbody>
            <?php
            $dataperfil = array(
            'name'        => 'perfil',
            'id'          => 'perfil',
            'checked'     => FALSE,
            'style'       => 'margin:10px'
            );
            foreach ($permiso as $per) {
                if ($per['IDGRUPO'] == ABOGADO) {
                    ?>
                            <tr>
                                <td>
                                    REQUERIMIENTOS ABOGADO
                                </td>
                                <td>
                                    <?php
                                    echo form_radio($dataperfil,"43");
                                    ?>
                                </td>
                            </tr>
                <?php } ?>
                <?php if ($per['IDGRUPO'] == SECRETARIO) { ?>
                        <tr>
                             <td>
                             REQUERIMIENTOS SECRETARIO
                             </td>
                             <td>
                                <?php
                                echo form_radio($dataperfil,"41");
                                ?>
                             </td>
                        </tr>
                <?php } ?>
                <?php if ($per['IDGRUPO'] == COORDINADOR) { ?>
                        <tr>
                            <td>
                               REQUERIMIENTOS COORDINADOR
                            </td>
                                <td>
                                    <?php
                                    echo form_radio($dataperfil,"42");
                                    ?>
                                </td>
                        </tr>
                <?php }
               }
            ?>
        </tbody>
    </table>
    <br>
    <div id="error" ></div>
    <br>
<form id="frmTmp" method="POST" action="<?= base_url('index.php/mandamientopago/nits')?>">
    <input type="hidden" id="perfiles" name="perfiles">    
</form>
        
<div align="center">
    <?php
    $data = array(
            'name' => 'button',
            'id' => 'seleccionar',
            'value' => 'Seleccionar',
            'type' => 'button',
            'content' => '<i class="fa fa-search"></i> Seleccionar',
            'class' => 'buscar btn btn-success'
            );

            echo form_button($data)."&nbsp;&nbsp;";
    ?>
</div>

</center>   
    <div id="ajax_load" class="ajax_load" style="display: none">
    <div class="preload" id="preload" ></div><img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
    </div><br><br>
          <div id="div_respuesta2"></div>           
<style>

    .primario{

        font-weight: bold;
        margin-left:0px;
        font-size: 14px;
        width:150px; 
    }
    div.preload{
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        background-color: white;
        opacity: 0.8;
        z-index: 10000;
    }

    div img.load{
        position: absolute;
        left: 50%;
        top: 50%;
        margin-left: -64px;
        margin-top: -64px;
        z-index: 15000;
    }
     .sub{
        font-weight: bold;
        margin-left:0px;
        font-size: 14px;
    }
</style>
<script type="text/javascript" language="javascript" charset="utf-8">        
     $('#styletable').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers"
    });
    
    $('#seleccionar').click(function(){
        $('#error').html("");
        var valida = true;
        var rdbNit = $('input[name="perfil"]').is(":checked");    
        if (rdbNit == false) {
            $('#error').html("<font color='red'><b>Seleccione el Perfil Correspondiente</b></font>");
            valida = false;
        }
        if (valida == true) {
            var perfil = $('input[name="perfil"]:checked').val();
            $('#perfiles').val(perfil);
            $(".ajax_load").show("slow");
            $('#frmTmp').submit();
            $(".ajax_load").hide("slow");
        }
    });
</script>


