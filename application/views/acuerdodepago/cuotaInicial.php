<form id='frmtp' name='frmtp' method='post'>
<div id="recibo" align="center">
    <input type="hidden" id="datos_envio" name="datos_envio" value="">
    <div class="center-form-large" id="cabecera">
        <table class = "table table-bordered table-striped" width = "100%">
            <tr>
                    <td width = "50%" rowspan = "2" class = "text-center" style = "text-align:center; vertical-align:middle;"><img src="<?php echo base_url() . 'img/Logotipo.png'; ?>"></td>
                    <td width = "50%" class = "text-left">
                        <h3><?php echo 'Recibo de pago N° ' . $acuerdo; ?></h3>
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
                    <td  width = "50%" class = "text-left"></td>
            </tr>
        </table>
        <table class="table table-bordered table-striped" width="100%">
		<tr>
			<th width="33%">Detalle</th>
			<th width="33%">Valor</th>
			<th width="33%">Valor a pagar</th>
		</tr>
                <tr>
                    <td width="33%">Saldo Deuda</td>
                    <td width="33%" colspan ="2">
                        <?php 
                            $totalDeuda = $totaldeuda['capital']+$totaldeuda['intereses'];
                            echo "$".number_format($totalDeuda); 
                        ?>
                    </td>                    
                </tr>
                <tr>
			<td width="33%">Capital</td>
			<td width="33%">
                            <?php 
                            $totalCapital = $totaldeuda['capital'];
                            echo "$".number_format($totalCapital); 
                            ?>                            
                        </td>
			<td width="33%">
                            <?php 
                            $totalPagar = ($totaldeuda['capital'])*($porcentajeInicial[0]['PORCENTAJE']/100);
                            echo "$".number_format($totalPagar); 
                            ?>
                        </td>
		</tr>
		<tr>
			<td width="33%">Intereses</td>
			<td width="33%">
                            <?php 
                            $totalIntereses = $totaldeuda['intereses'];
                            echo "$".number_format($totalIntereses); 
                            ?>
                        </td>
			<td width="33%">
                            <?php 
                            $pagarIntereses = $totaldeuda['intereses']*($porcentajeInicial[0]['PORCENTAJE']/100);
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
            modal : true,
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
                        var urlguarda = "<?= base_url('index.php/acuerdodepago/guardar_cuotaCero')?>";                            
                        $.post(urlguarda,{
                            acuerdo:<?= $acuerdo ?>,fpcuota:'<?= $fpcuota ?>',totaldeuda:<?= $totalDeuda ?>,
                            totalCapital:<?= $totalCapital ?>,totalPagar:<?= $totalPagar ?>,totalIntereses:<?= $totalIntereses ?>,
                            pagarIntereses:<?= $pagarIntereses ?>,totalcuota:<?= $totalcuota ?>,numcuota:0,porcentaje:<?= $porcentajeInicial[0]['PORCENTAJE'] ?>,
                            superintendencia:<?= round($superintendencia) ?>,fiscalizacion:'<?= $fiscalizacion ?>',nit:<?= $nit ?>
                        })
                        .done(function(data){                                                                    
                            jQuery('#recibo').dialog('close');
                            $(".ajax_load").hide("slow");                            
                            location.reload();                                            
                        }).fail(function (){
                            alert('Hubo Un Error al Insertar la Cuota, Intente de Nuevo');
                            $(".ajax_load").hide("slow");
                            location.reload();
                        })                                                        
                  }
                },
                "salir":{
                  text:'Salir',
                  class:'btn btn-warning',
                  click: function(){
                        $(".ajax_load").show("slow");
                        location.reload();
                        //jQuery('#recibo').dialog('close');                        
                  }
                }
            }
    });
    </script>
    <div>    
