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
        <?php
        for ($i = 0; $i < sizeof($titulos_acumulados); $i++) {
            ?>
            <tr>
                <td><?php echo $titulos_acumulados[$i]['COD_EXPEDIENTE_JURIDICA']; ?></td>
                <td><?php echo $titulos_acumulados[$i]['CONCEPTO']; ?></td>                
                <td><?php echo "$" . number_format($titulos_acumulados[$i]['SALDO_CAPITAL'], 0, '.', '.'); ?></td>
                <td><?php echo "$" . number_format($titulos_acumulados[$i]['SALDO_INTERES'], 0, '.', '.'); ?></td>
                <td><?php echo "$" . number_format($titulos_acumulados[$i]['TITULO_SANCION'], 0, '.', '.'); ?></td>
                <td><?php echo "$" . number_format($titulos_acumulados[$i]['TITULO_SANCION'], 0, '.', '.'); ?></td>
                <td><?php echo "$" . number_format($titulos_acumulados[$i]['SALDO_DEUDA'], 0, '.', '.'); ?></td>
            </tr>
            <?php
        }
        ?>
    </table>
    <br>
</div>
 <br>