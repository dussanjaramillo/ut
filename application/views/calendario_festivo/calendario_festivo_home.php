<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
//YANO// require("esfestivo.php");
//YANO// require_once("../conectar/conectar.php");
//set_time_limit(450);
?>
<html>
    <body style="background: none repeat scroll 0 0 #E6E6E6; padding-top: 0;">
        <div class="espacio">
            <h1>Calendario Festivos</h1>
            <br>
            <script type="text/javascript">
                var BASE_URL = "<?php echo base_url("index.php/calendario_festivo/forzar"); ?>";
            </script>
            <!--script language=javascript src="<?php echo base_url("js/jquery-1.10.2.js"); ?>"></script-->
            <script language=javascript src="<?php echo base_url("js/jquery-1.9.1.js"); ?>"></script>
            <?php
//echo link_tag('../../css/thEstilo.css');
            echo link_tag('css/thEstilo.css');
            echo link_tag('css/bootstrap.css');
            if ($admin != 998) {
                ?>

                <script language=javascript src="<?php echo base_url("js/editarCal.js"); ?>"></script>

                <?php
            }
            $cal = $calendario;
            //echo "TF:".$templatefile;
            //$cal = new Calendario($conn);
            /* //
              if(isset($_GET["anno"])){
              $aaaa = $_GET["anno"];
              }else{
              $aaaa = intval($ahora->format("Y"));
              }
              if(isset($_GET["mes"])){
              $mm = $_GET["mes"];
              }else{
              //$mm = intval($ahora->format("m"));
              $mm = 0;
              }
             *///
            //echo form_open(base_url("index.php/index2"));    
            echo form_open("calendario_festivo/index");
            ?>
            <!--form id=formCalendario method=POST action=?-->
            <table cellspacing="20" align="center" style="border-collapse: separate ;border-spacing: 20px">
                <tr>
                    <td><labe style="font-weight: bold; display: block; margin: 0 auto 0 auto; float:center">A&ntilde;o:</labe></td>
                    <td>
                
                        <input type=number id=anno name=anno value=<?php echo $aaaa; ?> size=4 min=1988 max=2200 style="font-weight: bold; display: block; margin: 0 auto 0 auto; float:center">
                        <!--Mes:<input type=text id=mes name=mes value=<?php echo $mm; ?> size=2>-->
                    </td>
                    <td></td>

                </tr>    
                <tr>
                    <td><label style="font-weight: bold; display: block; margin: 0 auto 0 auto; float:center">Mes:</label></td>
                    <td>
                        
                <!--        <select type=text id=mes name=mes  size=2>
                            <option value="value=<?php echo $mm; ?>"></option>
                        </select>
                        -->
                        <select style="font-weight: bold; display: block; margin: 0 auto 0 auto; float:center" id=mes name=mes value=<?php echo $aaaa; ?> >
                            <option value=0>Seleccione Un Mes</option>
                            <option value=1>Enero</option>
                            <option value=2>Febrero</option>
                            <option value=3>Marzo</option>
                            <option value=4>Abril</option>
                            <option value=5>Mayo</option>
                            <option value=6>Junio</option>
                            <option value=7>Julio</option>
                            <option value=8>Agosto</option>
                            <option value=9>Septiembre</option>
                            <option value=10>Octubre</option>
                            <option value=11>Noviembre</option>
                            <option value=12>Diciembre</option>
                        </select>
                    </td>
                    <td>
                        <input class="btn btn-success" type=submit id=sub name=sub value="&#8594;">
                    </td>
                </tr>
            </table>
            <br>
            <!--button type="submit" name="sub" id="sub" class="btn btn-primary"><i class="icon-arrow-right icon-white"></i>  Continuar</button-->

            <!--</form>-->
            <table style="font-weight: bold;"><tr><td>
                        <?php
                        echo $mostrarmes;
                        //print("<table><tr><td>");
                        /*
                          if($mm==0){
                          for($i=1;$i<=12;$i++){
                          if($i==7){ print("</td></tr><tr><td>");}
                          $cal->mostrarMes($aaaa,$i);
                          }
                          }else{
                          //echo $ahora->format("Y-m-d H:i:s");
                          $cal->mostrarMes($aaaa,$mm);
                          }
                         */
                        ?>
                    </td></tr></table>
        </div>


        <div id="flotante">
            <input type=text id=forzarDia name=forzarDia size=8 readOnly>
            <p><br>
                <span id=opc1>*Forzar que este d&iacute;a sea festivo</span>
            <p><br>
                <span id=opc2>*Forzar que este d&iacute;a sea h&aacute;bil<span>
                        <p><br>
                            <span id=opc3>*No forzar este d&iacute;a</span>
                        <div id=bas style="display:none"></div>
                        </div>

                        <p>
                            </body>
                            </html>    
                            <!--</body>
                            </html>-->