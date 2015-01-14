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
                        echo $fecha;                        
                        ?>
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
                            echo "$".number_format($saldoDeuda); 
                        ?>
                    </td>                    
                </tr>
                <tr>
			<td width="33%">Capital</td>
			<td width="33%">
                            <?php 
                            $totalIntereses = $saldoIntAcuerdo+$saldoIntCorriente;
                            echo "$".number_format($saldoCuota-$totalIntereses); 
                            ?>                            
                        </td>
			<td width="33%">
                            <?php 
                            echo "$".number_format($saldoCuota-$totalIntereses); 
                            ?>
                        </td>
		</tr>
		<tr>
			<td width="33%">Intereses</td>
			<td width="33%">
                            <?php                             
                            echo "$".number_format($totalIntereses); 
                            ?>
                        </td>
			<td width="33%">
                            <?php 
                            echo "$".number_format($totalIntereses);  
                            ?>
                        </td>
		</tr>
		<tr>
			<td width="33%">Total Cuota</td>
			<td width="33%">&nbsp;</td>
			<td width="33%">
                            <?php       
                            echo "$".number_format($saldoCuota); 
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
                  class:'btn btn-success',
                  click: function(){
                    $('#recibo').printArea();   
                    $(".ajax_load").hide("slow");          
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
