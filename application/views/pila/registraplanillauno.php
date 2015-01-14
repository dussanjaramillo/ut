<div align="center"><h5>Datos de la empresa</h5></div>
<table align="center" style="border: 2px solid; border-color: gray">
    <tr>
        <td>Nombre o Razón Social</td>   
        <td colspan="3"><input type="text" readonly="readonly" value="<?= $consultarempresa->result_array[0]['NOM_APORTANTE'] ?>" disabled="disabled" name="nombre" id="razonsociallectura"  style="width: 590px"></td>   
    </tr>
    <tr>
        <td>Tipo Documento</td> 
        <td><input type="text" readonly="readonly" value="<?= $consultarempresa->result_array[0]['TIPO_DOC_APORTANTE'] ?>" disabled="disabled" name="documento" id="documento"></td>
        <td>Numero de Identificación</td>
        <td><input type="text" readonly="readonly" value="<?= $consultarempresa->result_array[0]['N_INDENT_APORTANTE'] ?>" disabled="disabled" name="identificacion" id="identificacion"></td>
    </tr>
    <tr>
        <td>Clase de Aportante</td>
        <td><input type="text" readonly="readonly" value="<?= $consultarempresa->result_array[0]['TIPO_APORTANTE'] ?>" disabled="disabled" name="aportante" id="aportante"></td>
        <td>Naturaleza Jurídica</td>
        <td><input type="text" readonly="readonly" value="<?= $consultarempresa->result_array[0]['NAT_JURIDICA'] ?>"  disabled="disabled" name="juridica" id="juridica"></td>
    </tr>
    <tr>
        <td>Tipo Persona</td>
        <td><input type="text" readonly="readonly" value="<?= $consultarempresa->result_array[0]['TIPO_APORTANTE'] ?>" disabled="disabled" name="persona" id="persona"></td>
        <td>Forma de Presentación</td>
        <td><input type="text" readonly="readonly" value="<?= $consultarempresa->result_array[0]['FORMA_PRESENTACION'] ?>" disabled="disabled" name="presentacion" id="presentacion"></td>
    </tr>
    <tr>
        <td>Dir Correspondencia</td>
        <td colspan="3"><input type="text" readonly="readonly" value="<?= $consultarempresa->result_array[0]['DIREC_CORRESPONDENCIA'] ?>" disabled="disabled" name="correspondencia" id="correspondencia" style="width: 590px"></td>
    </tr>
    <tr>
        <td>Código Ciudad</td>  
        <td><input type="text" readonly="readonly" value="<?= $consultarempresa->result_array[0]['COD_CIU_O_MUN'] ?>" disabled="disabled" name="cuidad" id="ciudad"></td>  
        <td>Código Departamento</td>  
        <td><input type="text" readonly="readonly" value="<?= $consultarempresa->result_array[0]['COD_DEPARTAMENTO'] ?>" disabled="disabled" name="departamento" id="departamento"></td>  
    </tr>
    <tr>
        <td>Código DANE</td>  
        <td><input type="text" readonly="readonly" value="<?= $consultarempresa->result_array[0]['COD_DANE'] ?>" disabled="disabled" name="codigo" id="codigo"></td>  
        <td>Teléfono</td>  
        <td><input type="text" readonly="readonly" value="<?= $consultarempresa->result_array[0]['TELEFONO'] ?>" disabled="disabled" name="telefono" id="telefono"></td>  
    </tr>
    <tr>
        <td>Fax</td> 
        <td><input type="text" readonly="readonly"  value="<?= $consultarempresa->result_array[0]['FAX'] ?>" disabled="disabled" name="fax" id="fax"></td> 
        <td>Correo Electrónico</td> 
        <td><input type="text" readonly="readonly"  value="<?= $consultarempresa->result_array[0]['CORREO_ELECTRO'] ?>" disabled="disabled" name="correo" id="correo"></td> 
    </tr>
</table >
<div align="center"><h5>Datos del representante</h5></div>
<table align="center" style="border: 2px solid; border-color: gray">
    <tr>
        <td>ID Representante</td>
        <td><input type="text" readonly="readonly" value="<?= $consultarempresa->result_array[0]['N_REGISTROAPORTANTE'] ?>" disabled="disabled" name="representante" id="representante"></td>
        <td>Digito Verificación</td>
        <td><input type="text" disabled="disabled" value="<?= $consultarempresa->result_array[0]['DIG_VERIF_NIT'] ?>" readonly="readonly" name="verificacion" id="verificacion" style="width: 30px"></td>
        <td>Tipo de Identificación</td>
        <td style="width: 20px"><input type="tipoiden" value="<?= $consultarempresa->result_array[0]['TIPO_DOC_APORTANTE']?>"  readonly="readonly" disabled="disabled" name="fax" id="tipoinden" style="width: 30px"></td>
    </tr>
    <tr>
        <td>Nombre del representante</td>
        <td colspan="5"><input type="text" readonly="readonly" value="<?= $consultarempresa->result_array[0]['NOM_APORTANTE'] ?>"  disabled="disabled" name="apellido1" id="apellido1" style="width: 600px"></td>
        
</table>
<div align="center"><h5>Datos adicionales</h5></div>
<table align="center" style="border: 2px solid; border-color: gray">
    <tr>
        <td>Fecha Concordato cese</td>
        <td><input type="text" readonly="readonly"  value="<?= $consultarempresa->result_array[0]['FECHA_INICIO'] ?>" disabled="disabled" name="concordato" id="concordato"></td>
        <td>Tipo Acción</td>
        <td><input type="text" readonly="readonly"  value="<?= $consultarempresa->result_array[0]['TIPO_ACCION'] ?>" disabled="disabled" name="accion" id="accion"></td>
    </tr>
    <tr>
        <td>Fecha Fin Actividades</td>
        <td><input type="text" readonly="readonly" value="<?= $consultarempresa->result_array[0]['FECHA_TERMINO'] ?>" disabled="disabled" name="actividades" id="actividades"></td>
        <td>Código Operador</td>
        <td><input type="text" readonly="readonly" value="<?= $consultarempresa->result_array[0]['COD_OPERADOR'] ?>" disabled="disabled" name="operador" id="operador"></td>
    </tr>
    <tr>
        <td>Período de Pago</td>
        <td><input type="text" readonly="readonly" value="<?= $consultarempresa->result_array[0]['PERIDO_PAGO'] ?>" disabled="disabled" name="periodopago" id="periodopago"></td>
        <td>Tipo Aportante</td>
        <td><input type="text" readonly="readonly" value="<?= $consultarempresa->result_array[0]['TIPO_APORTANTE'] ?>" disabled="disabled" name="tipoaportante" id="tipoaportante"></td>
    </tr>
</table>
