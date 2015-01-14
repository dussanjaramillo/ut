<!--Registran los datos básicos del ejecutado-->
<table id="tabla" style="width:auto">
        <tr>
        <td><span  style="color:red; "> CÓDIGO DE PROCESO</span></td>
        <td><span> <input type="text" readonly="readonly"  title="" value="<?php echo $cabecera['COD_PROCESOPJ']?>"></span></td>
        <td>
            <span style="color:red;  margin-left: 10px; " > FECHA AVOCA</span>  </td>
        <td>
            <span  ><input  readonly="readonly" type="text" title=""  value=" <?php echo $cabecera['FECHA_AVOCA']; ?>"></span>
        </td>

    </tr>
    <tr>
        <td><span  style="color:red; "> INSTANCIA</span></td>
        <td><span> <input type="text" readonly="readonly"  title="" value="<?php echo $cabecera['PROCESO']?>"></span></td>
        <td>
            <span style="color:red;  margin-left: 10px; " > ESTADO</span>  </td>
        <td>
            <span  ><input  readonly="readonly" type="text" title=""  value=" <?php echo $cabecera['RESPUESTA']; ?>"></span>
        </td>

    </tr>
    <tr><td> <span  style="color:red "> IDENTIFICACIÓN EJECUTADO:</span> </td>
        <td>   <span>    <input type="text" readonly="readonly"  value=" <?php echo $cabecera['IDENTIFICACION'] ?>"></span>
        </td>
        <td>
            <span style="color:red;  margin-left: 10px; "> NOMBRE EJECUTADO:</span>  </td>
        <td>
            <span  ><input  readonly="readonly" type="text" title=""  value=" <?php echo $cabecera['EJECUTADO'] ?>"></span>
        </td>
    </tr>
    <tr><td>
            <span  style="color:red"> TELÉFONO:</span>  </td>
        <td>
            <span>    <input type="text" readonly="readonly"  value="<?php echo $cabecera['TELEFONO'] ?>"></span>
        </td>
        <td>
            <span style="color:red;  margin-left: 10px; " >DIRECCIÓN:</span>  </td>
        <td>
            <span><input  readonly="readonly" type="text"  value=" <?php echo $cabecera['DIRECCION'] ?>"></span>
        </td>
    </tr>
</table>
</div><?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

