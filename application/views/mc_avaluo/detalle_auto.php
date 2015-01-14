<div class="Gestion" style="background: #f0f0f0;  width: 95%; margin: auto; overflow: hidden">
    <table width="100%" border="0" id="tabla_inicial"  >
        <tr>
            <td class="td1"> <br>   N° Proceso Coactivo</td>
            <td class="color">    <br>                                       
                <?php echo $consulta[0]['COD_PROCESOPJ'] ?>   
            </td>
        </tr>
        <tr>
            <td class="td1"> <br>  N° de Medida Cautelar</td>
            <td>   <br>   
                <label><?php echo $consulta[0]['MEDIDA_CAUTELAR'] ?></label>                        
            </td>
            <td class="td1"> <br>   Fecha de Medida Cautelar</td>
            <td>   <br> 
                <label><?php echo $consulta[0]['FECHA_MEDIDAS'] ?></label>      

            </td>
        </tr> 
        <tr>
            <td class="td1"> <br>   Identificación Ejecutado</td>
            <td>             <br>  
                <label><?php echo $consulta[0]['IDENTIFICACION'] ?></label>
            </td>
            <td class="td1"> <br>Ejecutado</td>
            <td>             <br> 
                <label> <?php echo $consulta[0]['EJECUTADO'] ?></label>

            </td>
        </tr>
        <tr>
            <td class="td1"> <br>Teléfono</td>
            <td>             <br>  
                <label><?php echo $consulta[0]['TELEFONO'] ?></label>


            </td>
            <td class="td1"> <br>Dirección</td>
            <td>             <br>  
                <label>              
                     <label><?php echo $consulta[0]['DIRECCION'] ?></label>
                

            </td>
        </tr>

    </table>
</div>
<br><br>

