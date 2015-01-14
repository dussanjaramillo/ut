<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

?>
<style>
    button.btn.btn-success{
        height: 35px; 
        width: 100px;
        display: block;
        margin: 0 auto 0 auto; 
        float:right;
        margin-right: 1em;
    }
    
    input.btn.btn-success{
        height: 35px; 
        width: 100px;
        display: block;
        margin: 0 auto 0 auto; 
        float:right;
    }
</style>

<html>
    <head>
        <title> Parámetros correo </title>
        <script language="Javascript" src="../js/jquery-1.10.2.js"></script>
        <script language="Javascript">
            $(document).ready(ini);
            function ini(){
                $("#Cancelar").click(anterior);
                //$("#proceso").change(traeActividades);
                //$("#Cancelar2").click(anterior);
            }
            
            function traeActividades(){
                //$("#acts").load("ejecutar.php","m=21&proc="+$(this).val());
            }
            
            function anterior(){
                window.history.back(-1);
            }
        </script>
        
    <body>
        <?php 
        /*
        //Indicador de acción realizada
        if($_REQUEST['rtrn']=='vlvr'){
            $msj=$_REQUEST['msj'];
            $cuando=$_REQUEST['cuando'];
            ?>
            <div style='background:darkblue;color:whitesmoke;font-size:-2em;position:absolute;top:0px;left:0px;'>Listo:<?php echo $cuando; ?> ".<?php echo $msj; ?>." </div>
            <?php
        }*/?>
        <h1>Parametrizacion Correo Electronico</h1>
        <br>
        <form id="vN" name="vN" action=<?php echo base_url('index.php/correo/parametros') ?> method="POST">
            <table align="center">
                <tr>
                    <td>Correo electr&oacute;nico:</td>
                    <td>
                    <?php 
                    $data = array(
                            'name'        => 'correo',
                            'id'          => 'correo',
                            'value'       => $matriz['CORREO_ELECTRONICO']
                        );

                        echo form_input($data);
                        /*$input->text("correo",$matriz[0][0],"normal");*/
                    ?>
                    </td>
                </tr>
                
                <tr>
                    <td>Correo saliente SMTP:</td>
                    <td>
                    <?php 
                        $data = array(
                            'name'  => 'smtp',
                            'id'    => 'smtp',
                            'value' => $matriz['SERVIDOR_SMTP']
                        );
                        echo form_input($data); 
                        /*$input->text("smtp",$matriz[0][1],"normal");*/
                         ?>
                    </td>
                </tr>
                <tr>
                    <td>Puerto:</td>
                    <td>
                    <?php 
                        $data = array(
                            'name'        => 'puerto',
                            'id'          => 'puerto',
                            'value'       => $matriz['PUERTO_SMTP'],
                            'class'   => 'input-small',
                        );
                        echo form_input($data);
                        //$input->text("puerto",$matriz[0][2],"normal"," size=4 maxlength=4 ");
                    ?>
                    </td>
                </tr>
                
                <!--tr>
                    <td>Nombre de usuario:</td>
                    <td>
                    <?php                     
                        $data = array(
                            'name'        => 'usuario',
                            'id'          => 'usuario',
                            'value'       => $matriz['NOMBRE_REMITENTE'],
                            'maxlength'   => '4',
                            'size'        => '4'
                        );
                        echo form_input($data);
                    ?>
                    </td>
                </tr-->
                
                <tr>
                    <td>Contrase&ntilde;a:</td>
                    <td>
                    <?php 
                        $data = array(
                            'name'        => 'contrasena',
                            'id'          => 'contrasena',
                            'value'       => $matriz['PASSWORD']
                        );
                        echo form_password($data);
                        //$input->password("contrasena",$matriz[0][4],"normal");
                    ?>
                    </td>
                </tr>
                
                <!--tr>
                    <td>SSL</td>
                    <td>
                    <?php
                        /*
                        $data = array(
                            'name'        => 'ssl',
                            'id'          => 'ssl',
                            'value'       => $matriz['REQUIERE_SSL']
                        );
                        //, 'checked'     => $check
                        //echo form_checkbox($data);
                        //$check[0] = 0;$input->checkbox("ssl",$matriz[0][5],"normal",$check);*/
                    ?>
                    </td>
                </tr --!>
                
                <tr>
                    <td colspan="2" nowrap>
                <?php                
                    
                    //echo form_hidden($data);
                    echo form_hidden("m","parametrosCorreo");
                    //$input->hidden("m",'parametrosCorreo');
                    echo '<br>';
                    $attributes = 'class = "btn btn-success"';
                    echo form_submit('Guardar', 'Guardar',$attributes);
                    //$input->submit("Guardar");
                    
                    /*$data = array(
                        'name' => 'Cancelar',
                        'id' => 'Cancelar',
                        'value' => 'Cancelar',
                    );*/
                    //echo form_button($data);
                    echo form_button('Cancelar','Cancelar',$attributes);
                    //$input->button("Cancelar");
                ?>
                    </td>
                </tr>
            </table>
        </form>
            <?php 
                /*$data = array(
                    'name'  => 'id',
                    'id'    => 'id',
                    'value' => $cod
                );
                echo form_hidden($data);*/
                //$input->hidden("id",$cod);
            ?>
    </body>
</html>