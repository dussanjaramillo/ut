<div class="titulos_acumulados" id="titulos_acumulados" style="width: 90%; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 0">
    <br>
    <table width="431" border="1" align="center" class="table table-bordered table-striped">
        <tr>
            <td width="34"><div align="center"><b>No. Expediente</b></div></td>
            <td width="60"><div align="center"><b>Concepto</b></div></td>            
            <td width="89"><div align="center"><b>Capital a Cobrar</b></div></td>
            <td width="103"><div align="center"><b>Interes a Cobro</b></div></td>
            <td width="103"><div align="center"><b>Sancion a Cobrar</b></div></td>
            <td width="103"><div align="center"><b>Multa a Cobrar</b></div></td>
            <td width="111"><div align="center"><b>Valor Obligación</b></div></td>
        </tr>
        <tr>
            <td><?php echo $titulos_acumulados['NO_EXPEDIENTE']; ?></td>
            <td><?php echo $titulos_acumulados['CONCEPTO']; ?></td>                
            <td><?php echo "$" . number_format($titulos_acumulados['SALDO_CAPITAL'], 0, '.', '.'); ?></td>
            <td><?php echo "$" . number_format($titulos_acumulados['SALDO_INTERES'], 0, '.', '.'); ?></td>
            <td><?php echo "$" . number_format($titulos_acumulados['TITULO_SANCION'], 0, '.', '.'); ?></td>
            <td><?php echo "$" . number_format($titulos_acumulados['TITULO_SANCION'], 0, '.', '.'); ?></td>
            <td><?php echo "$" . number_format($titulos_acumulados['SALDO_DEUDA'], 0, '.', '.'); ?></td>
        </tr>
    </table>
    <br>
</div>
<br>