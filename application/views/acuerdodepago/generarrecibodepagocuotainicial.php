
<div id="recibo" align="center">
    <div class="center-form-large" id="cabecera">
        <table class = "table table-bordered table-striped" width = "100%">
            <tr>
                    <td width = "50%" rowspan = "2" class = "text-center" style = "text-align:center; vertical-align:middle;"><img src="<?php echo base_url() . 'img/Logotipo.png'; ?>"></td>
                    <td width = "50%" class = "text-left"><h3><?php echo 'Recibo de pago N° ' . $acuerdo; ?></h3></td>
            </tr>
            <tr>
                    <td width = "50%" class = "text-left"><strong>Fecha de Vencimiento:</strong> <?php echo $fecha; ?></td>
            </tr>
            <tr>
                    <td width = "50%" class = "text-left"><strong>Razón Social:</strong> <?php echo $razonsocial; ?></td>
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
			<td width="33%">Capital</td>
			<td width="33%"><?php echo "$".number_format($saldocapital, 0, '.', '.'); ?></td>
			<td width="33%"><?php echo "$".number_format($saldocapital, 0, '.', '.'); ?></td>
		</tr>
		<tr>
			<td width="33%">Intereses</td>
			<td width="33%"><?php echo "$".number_format($interes, 0, '.', '.'); ?></td>
			<td width="33%"><?php echo "$".number_format($interes, 0, '.', '.'); ?></td>
		</tr>
                <tr>
			<td width="33%">Intereses Corriente</td>
			<td width="33%"><?php echo "$".number_format($interesesCuota, 0, '.', '.'); ?></td>
			<td width="33%"><?php echo "$".number_format($interesesCuota, 0, '.', '.'); ?></td>
		</tr>
                <tr>
			<td width="33%">Abono a Capital</td>
			<td width="33%"><?php echo "$".number_format($valorcuota-$interes, 0, '.', '.'); ?></td>
			<td width="33%"><?php echo "$".number_format($valorcuota-$interes, 0, '.', '.'); ?></td>
		</tr>
		<tr>
			<td width="33%">Total Cuota</td>
			<td width="33%">&nbsp;</td>
			<td width="33%"><?php echo "$".number_format($valorcuota+$interesesCuota, 0, '.', '.'); ?></td>
		</tr>
	</table>
    </div>
<div id="ajax_load" class="ajax_load" style="display: none">
        <div class="preload" id="preload" >
            <img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
        </div>
</div>    
        
    
</div>
    <script>                
        if($('#acuerdodepagonumero').length != 0){
        var dato = $('#acuerdodepagonumero').val();
        var acuerdo = $('#acuerdono').html(dato);
        }
        $('#recibo').dialog({
            autoOpen : true,
            modal : true,
            width : 1000,
            height : 590,
            close : function(){
                $('#recibo *').remove();
            },
            buttons : [{
                    id : "imprimir",
                    text : "Imprimir",
                    click : function(){
                        $(".ajax_load").show("slow"); 
                        $('#recibo').printArea();
                        window.location = '<?= base_url('index.php/acuerdodepago/gestionarAcuerdo')?>';
                    },
                    class : "btn btn-success"
            }]
        });
    </script>
    <div>    
