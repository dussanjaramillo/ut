<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div id="ajax_load" class="ajax_load" style="display: none">
    <div class="preload" id="preload" ></div><img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
</div>
    <?php
if (isset($message)) {
    echo $message;
}
if (isset($custom_error))
    echo $custom_error;
?>

<div>
    <div id="proceso" style="text-align:center" ><br>
        <table id="tabla1" >
            <tr>
                <td><br><br><span ><b>Deudor Objeto?</b></span></td>
                <td><br><br><select name="res_objeto" id="res_objeto" onchange="validar()">
                        <option value="">Elija una opción</option>
                        <option value="1">SI</option>
                        <option value="2">NO</option>
                    </select>
                </td>    
            </tr>
        </table>
        <div id="objeto_no" style="display:none">
            <?php
            $data = array(
                'name' => 'button',
                'id' => 'salir',
                'value' => 'Salir',
                'type' => 'button',
                'style' => 'margin-left:60px;',
                'content' => 'Salir',
                'class' => 'btn btn-success',
            );
            echo form_button($data);
            ?>
            <?php
            $data = array(
                'name' => 'button',
                'id' => 'Continuar',
                'value' => 'Continuar',
                'type' => 'button',
                'style' => 'margin-left:120px;',
                'content' => 'Continuar',
                'class' => 'btn btn-success',
            );
            echo form_button($data);
            ?>
        </div>
        <div id="objeto_si" style="display:none">
            <table>
                <tr>
                    <td colspan="1" style="text-align: left;" >
                        Fecha Objecion</td>
                    <td><?php
                        $data = array('name' => 'fecha', 'id' => 'fecha', 'required' => 'required',);
                        echo form_input($data);
                        ?>   </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: left;" >
                        Observaciones</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: left;" > 
                        <?php
                        $datadesc = array('name' => 'observaciones', 'id' => 'observaciones', 'required' => 'required', 'rows' => '4',
                            'cols' => '80', 'style' => 'margin: 0px 0px 10px; width:600px; height: 70px;');
                        echo form_textarea($datadesc);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: left;" >  Solicita Pruebas ?: 
                        <select name="decreta_pruebas" id="decreta_pruebas" >
                            <option value="">Elija una opción</option>
                            <option value="S">SI</option>
                            <option value="N">NO</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div id="divrespuesta"></div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <?php
                        $data = array('name' => 'button', 'id' => 'cancelar', 'value' => 'Cancelar', 'type' => 'button', 'style' => 'margin-left:60px;', 'content' => 'Cancelar', 'class' => 'btn btn-success',);
                        echo form_button($data);
                        $data = array('name' => 'button', 'id' => 'aceptar_pruebas', 'value' => 'Aceptar', 'type' => 'button', 'style' => 'margin-left:60px;', 'content' => 'Aceptar', 'class' => 'btn btn-success',);
                        echo form_button($data);
                        ?>
                    </td>       
                    </div>
                    </div>
                    </div>
                <script>
                    $("#ajax_load").css("display", "none");
                    $('#resultado').dialog({
                        autoOpen: true,
                        width: 800,
                        height: 450,
                        modal: true,
                        title: "Registrar Objeción",
                        close: function() {
                            $('#resultado *').remove();
                            $("#ajax_load").css("display", "none");
                        }
                    });

                    $(document).ready(function() {

                        $("#fecha").datepicker({
                            dateFormat: "dd/mm/yy",
                            changeMonth: true,
                            maxDate: "0",
                            changeYear: true,
                        });
                    });
                    function soloNumeros(e)
                    {
                        var keynum = window.event ? window.event.keyCode : e.which;
                        if ((keynum == 8) || (keynum == 46))
                            return true;

                        return /\d/.test(String.fromCharCode(keynum));
                    }
                    $(document).ready(function() {
                        $('#salir').click(function() {
                            $('#resultado').dialog('close');
                            $('#resultado *').remove();

                        });

                        $('#cancelar').click(function() {
                            $('#resultado').dialog('close');
                            $('#resultado *').remove();
                        });
                        $('#Continuar').click(function() {//Se envia para generar el auto que declara firmeza cuando el usuario no objeto
                            $("#ajax_load").css("display", "block");
                            var url = "<?php echo base_url('index.php/mc_avaluo/no_objeto') ?>";
                            var id = '<?php echo $post['id'] ?>';
                            var detalle = '<?php echo serialize($post) ?>';
                            $.post(url, {id: id, detalle: detalle}, function(data) {
                                $("#div_smensaje").html(data);
                                $('#resultado').dialog('close');
                                $('#resultado *').remove();
                                $("#ajax_load").css("display", "none");
                                location.reload();
                            })
                        });

                        //cuando el usuario objeto
                        $('#aceptar_pruebas').click(function() {//Se envia para generar el auto que declara firmeza cuando el usuario no objeto

                            var url = "<?php echo base_url('index.php/mc_avaluo/objeto') ?>";
                            var id = '<?php echo $post['id'] ?>';
                            var decreta_pruebas = $('#decreta_pruebas').val();
                            var fecha = $('#fecha').val();
                            var detalle = '<?php echo serialize($post) ?>';

                            var observaciones = $('#observaciones').val();

                            if (fecha == '') {
                                mierror = '<div   class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe seleccionar una fecha' + '</div>';
                                document.getElementById("divrespuesta").innerHTML = mierror;
                                return false;
                            }
                            else if (observaciones == '')
                            {
                                mierror = '<div   class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe ingresar las observaciones' + '</div>';
                                document.getElementById("divrespuesta").innerHTML = mierror;
                                return false;
                            }
                            else if (decreta_pruebas == '')
                            {
                                mierror = '<div "  class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe seleccionar si decreta pruebas' + '</div>';
                                document.getElementById("divrespuesta").innerHTML = mierror;
                                return false;
                            }
                            else
                            {
                                $("#ajax_load").css("display", "block");
                                $.post(url, {id: id, decreta_pruebas: decreta_pruebas, fecha: fecha, observaciones: observaciones, detalle: detalle},
                                function(data) {
                                    location.reload();
                                })
                            }
                        });

                    });

                    function validar() {
                        var var_objeto = document.getElementById("res_objeto").value;
                        if (var_objeto == 1) {
                            $("#objeto_si").show();
                            $("#objeto_no").hide();
                        }
                        else {
                            $("#objeto_si").hide();
                            $("#objeto_no").show();
                        }
                    }


                </script>
                <style>
                    .ui-widget-overlay{z-index: 10000;}
                    .ui-dialog{
                        z-index: 15000;
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
                </style>
