<form id='frmtp' name='frmtp' method='post'>
<div id="recibo" align="center">
    <input type="hidden" id="datos_envio" name="datos_envio" value="">
    <div class="center-form-large" id="cabecera">
        <table class = "table table-bordered table-striped" width = "100%">
            <tr>
                    <td width = "50%" rowspan = "2" class = "text-center" style = "text-align:center; vertical-align:middle;"><img src="<?php echo base_url() . 'img/Logotipo.png'; ?>"></td>
                    <td width = "50%" class = "text-left">
<!--                        <h3><?php echo 'Recibo de pago N° ' . $acuerdo; ?></h3>-->
                        <h3><?= 'Emisión Consulta Valor a Pagar N° '. $acuerdo ?></h3>
                    </td>
            </tr>
            <tr>
                    <td width = "50%" class = "text-left"><strong>Fecha de Vencimiento:</strong> 
                        <?php 
                        $finicial = date('d-m-Y');
                        $fpcuota = date("d/m/Y", strtotime($finicial . "+1 month"));
                        echo $fpcuota;                        
                        ?>
                        <input type='hidden' name="fpcuota" id='fpcuota' value='<?= $fpcuota ?>'>
                    </td>
            </tr>
            <tr>
                    <td width = "50%" class = "text-left"><strong>Razón Social:</strong> <?php echo $razon; ?></td>
                    <td  width = "50%" class = "text-left"><strong>Nit:</strong> <?php echo $nit; ?></td>
            </tr>
            <tr>
                    <td  width = "50%" class = "text-left"><strong>Fecha:</strong> <?php echo date("d-m-Y");; ?></td>
                    <td  width = "50%" class = "text-left"><strong>Porcentaje:</strong> <?= $porcentajeInicial[0]["PORCENTAJE"]."%" ?></td>
            </tr>
            <tr>
                    <td  width = "50%" class = "text-left"><strong>Concepto:</strong></td>
                    <td  width = "50%" class = "text-left"><?= $datos_recibido[0]['NOMBRE_CONCEPTO'] ?></td>
            </tr>
            <tr>
                    <td  width = "50%" class = "text-left"><strong>Títulos</strong></td>
                    <td  width = "50%" class = "text-left">
                        <?php
                        $valores = 0;
                        $i=0;                   
                            for ($i=0;$i<count($datos_recibido);$i++){
                                echo $datos_recibido[$i]['NO_EXPEDIENTE']."<br>";
                            }                                                    
                        ?>
                    </td>
            </tr>
            <tr>
                    <td  width = "50%" class = "text-left"><strong>Saldo a Capital</strong></td>
                    <td  width = "50%" class = "text-left"><?= "$".number_format($capital); ?></td>
            </tr>
        </table>
        <table class="table table-bordered table-striped" width="100%">
		<tr>
			<th width="33%">Detalle</th>
			<th width="33%">Valor Total Deuda</th>
			<th width="33%">Valor Cuota</th>
		</tr>
                <tr>
                    <td width="33%">Saldo Deuda</td>
                    <td width="33%" colspan ="2">
                        <?php 
                            echo "$".number_format($deuda); 
                        ?>
                    </td>                    
                </tr>
                <tr>
			<td width="33%">Valor Cuota</td>
			<td width="33%">
                            <?php 
                            echo "$".number_format($capital); 
                            ?>                            
                        </td>
			<td width="33%">
                            <?php 
                            $totalPagar = ($capital)*($porcentajeInicial[0]['PORCENTAJE']/100);
                            echo "$".number_format($totalPagar); 
                            ?>
                        </td>
		</tr>
		<tr>
			<td width="33%">Intereses</td>
			<td width="33%">
                            <?php 
                            $totalIntereses = $intereses;
                            echo "$".number_format($totalIntereses); 
                            ?>
                        </td>
			<td width="33%">
                            <?php 
                            $pagarIntereses = $intereses;
                            echo "$".number_format($pagarIntereses); 
                            ?>
                        </td>
		</tr>
		<tr>
			<td width="33%">Total Cuota</td>
			<td width="33%">&nbsp;</td>
			<td width="33%">
                            <?php
                            $totalcuota = $pagarIntereses+$totalPagar;
                            echo "$".number_format($totalcuota); 
                            ?>
                        </td>
		</tr>
	</table>
    </div>                
</div>
</form>

    <script> 
    $('#recibo').dialog({
            autoOpen : true,
            modal : false,
            width : 1000,
            height : 590,
            close : function(){
                $('#recibo *').remove();
            },
            buttons:{
                "imprimir":{
                  text:'Imprimir',
                  id:'imprimir',
                  class:'btn btn-success',
                  click: function(){
                    $('#recibo').printArea();   
                    $(".ajax_load").show("slow");
                        var urlguarda = "<?= base_url('index.php/acuerdodepagojuridico/guardar_cuotaCero')?>";                            
                        $.post(urlguarda,{
                            acuerdo:<?= $acuerdo ?>,fpcuota:'<?= $fpcuota ?>',totaldeuda:<?= $deuda ?>,
                            totalCapital:<?= $capital ?>,totalPagar:<?= $totalPagar ?>,totalIntereses:<?= $totalIntereses ?>,
                            pagarIntereses:<?= $pagarIntereses ?>,totalcuota:<?= $totalcuota ?>,numcuota:0,porcentaje:<?= $porcentajeInicial[0]['PORCENTAJE'] ?>,
                            superintendencia:<?= round($superintendencia) ?>,cod_coactivo:'<?= $cod_coactivo ?>',nit:<?= $nit ?>,procedencia:<?= $procedencia ?>
                        })
                        .done(function(data){                                                                    
                            window.location = "<?= base_url().'index.php/bandejaunificada/procesos' ?>";
                            //jQuery('#recibo').dialog('close');
                        }).fail(function (){
                            alert('Hubo Un Error al Insertar la Cuota, Intente de Nuevo');
                            $(".ajax_load").hide("slow");
                            window.location = "<?= base_url().'index.php/bandejaunificada/procesos' ?>";
                        })                                                        
                  }
                },
                "salir":{
                  text:'Salir',
                  class:'btn btn-warning',
                  click: function(){
                        $(".ajax_load").show("slow");
                        window.location = "<?= base_url().'index.php/bandejaunificada/procesos' ?>";
                        //jQuery('#recibo').dialog('close');                        
                  }
                }
            }
    });
    </script>
    <div>    
