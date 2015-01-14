<div style="background: #5BB75B; color: #ffffff; text-align: center; font-size: 18px; margin-top:5px 0px 0px 10px; "><?php echo $this->data['titulo'] ?></div>
<br>

<table id="tabla" style="width:auto">
    <tr>
        <td><span  style="color:red"> COD PROCESO</span></td>
        <td><span> <input type="text" readonly="readonly" style="text-align: left" value="<?php echo $this->data['datos']['CODIGO_PJ'] ?>"></span></td>
        <td>
            
            <span style="color:red; margin-left: 10px; "> CONCEPTO</span>  </td>
        <td>
            <span  ><input  readonly="readonly" type="text"  value=" <?php echo $this->data['datos']['NOMBRE_CONCEPTO'] ?>"></span>
        </td>
    </tr>
    <tr>
        <td><span  style="color:red; "> INSTANCIA</span></td>
        <td><span> <input type="text" readonly="readonly"  title="<?php echo $this->data['instancia'] ?>" value="<?php echo $this->data['instancia'] ?>"></span></td>
        <td>
            <span style="color:red;  margin-left: 10px; " > ESTADO</span>  </td>
        <td>
            <span  ><input  readonly="readonly" type="text" title="<?php echo $this->data['datos']['NOMBRE_GESTION'] ?>"  value=" <?php echo $this->data['datos']['NOMBRE_GESTION'] ?>"></span>
        </td>

    </tr>
    <tr><td> <span  style="color:red "> IDENTIFICACIÓN EJECUTADO:</span> </td>
        <td>   <span>    <input type="text" readonly="readonly"  value=" <?php echo $this->data['datos']['NIT_EMPRESA'] ?>"></span>
        </td>
        <td>
            <span style="color:red;  margin-left: 10px; "> NOMBRE EJECUTADO:</span>  </td>
        <td>
            <span  ><input  readonly="readonly" type="text" title="<?php echo $this->data['datos']['NOMBRE_EMPRESA'] ?>"  value=" <?php echo $this->data['datos']['NOMBRE_EMPRESA'] ?>"></span>
        </td>
    </tr>
    <tr><td>
            <span  style="color:red"> TELÉFONO:</span>  </td>
        <td>
            <span>    <input type="text" readonly="readonly"  value="<?php echo $this->data['datos']['TELEFONO_FIJO'] ?>"></span>
        </td>
        <td>
            <span style="color:red;  margin-left: 10px; " >DIRECCIÓN:</span>  </td>
        <td>
            <span><input  readonly="readonly" type="text"  value=" <?php echo $this->data['datos']['DIRECCION'] ?>"></span>
        </td>
    </tr>
</table>
</div>