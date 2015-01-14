<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
if (isset($message)) {
    echo $message;
}
if (isset($custom_error))
    echo $custom_error;
?>
<div style="width: auto; text-align: center;">
    <h4>Medidas Cautelares Avaluo</h4>
    <div id="ajax_load" class="ajax_load" style="display: none">
        <div class="preload" id="preload" ></div><img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
    </div>
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
                <input type="text" readonly="readonly" name="cod_medidacautelar" id="cod_medidacautelar" value="<?php echo $consulta[0]['MEDIDA_CAUTELAR'] ?>" >                             
            </td>
            <td class="td1"> <br>   Fecha de Medida Cautelar</td>
            <td>   <br> 
                <input type="text" readonly="readonly" name="fecha_medidacautelar" id="fecha_medidacautelar" value="<?php echo $consulta[0]['FECHA_MEDIDAS'] ?>" >                             
            </td>
        </tr>
        <tr>
            <td class="td1"> <br>   Identificación Ejecutado</td>
            <td>             <br>  
                <input  type="text" id="nitempresa" name="nitempresa" readonly="readonly" value="<?php echo $consulta[0]['IDENTIFICACION'] ?>">

            </td>
            <td class="td1"> <br>Ejecutado</td>
            <td>             <br>  
                <input  type="text" id="nombre_empresa" name="nombre_empresa" readonly="readonly" value=" <?php echo $consulta[0]['EJECUTADO'] ?> ">   
            </td>
        </tr>
        <tr>
            <td class="td1"> <br>Teléfono</td>
            <td>             <br>  
                <input  type="text" id="nitempresa" name="nitempresa" readonly="readonly" value="<?php echo $consulta[0]['TELEFONO'] ?>">

            </td>
            <td class="td1"> <br>Dirección</td>
            <td>             <br>  
                <input  type="text" id="nombre_empresa" name="nombre_empresa" readonly="readonly" value=" <?php echo $consulta[0]['DIRECCION'] ?> ">   
            </td>
        </tr>
    </table>
    <input type="hidden" name="id_p" id="id_p" value="<?php echo $post['id'] ?>">
    <input type="hidden" name="detalle" id="detalle" value="<?php echo serialize($post) ?>" readonly="readonly">
    <div id="div_formulario" style="display:block">
        <form name="form1" id="form1" action="<?php echo base_url() ?>index.php/mc_avaluo/guardar_pruebas" method="post" disabled="disabled" >
            <?php echo form_hidden('detalle', serialize($post)); ?>
            <div id="contenido_form" style="display:none"></div>
            <div id="correccion" style="display:none; text-align: center" >
                <table style="text-align:center">
                    <tr>
                        <td><span >Requiere Corrección?</span></td>
                        <td><select name="req_correccion" id="req_correccion">
                                <option value="">Elija una opción</option>
                                <option value="1">SI</option>
                                <option value="2">NO</option>
                            </select>
                        </td>    
                    </tr>
                    <tr>
                        <td>
                            <div id="revision2"></div>
                            <input type="button"  class="btn btn-success"  name="aceptar" id="aceptar" value="Aceptar" content="Aceptar" onclick="valida_cor()" />
<!--                            <input type="button"  class="btn btn-primary"  name="cancelar" id="cancelar" value="Cancelar"  onclick="f_cancelar()"/>-->
                            <input type="button"  class="btn btn-success"  name="salir" id="salir" value="Salir" onclick="f_salir()"/>
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
    <div id="enviar" style="display:none">
        <br>
        <?php
        $data = array('name' => 'aceptar', 'id' => 'aceptar', 'value' => 'Aceptar',
            'type' => 'button', 'content' => 'Aceptar'
            , 'class' => 'btn btn-success', 'style' => 'text-align:right');
        echo form_button($data);
        ?>
    </div>
    <div id="revisar" style="display:none"></div>
    <div id="revision"></div>
    <div id="pruebas" style="display:block">
        <table  border="0" id="tabla2" style="width:100%;text-align: center; ">
            <tr class="sub" bgcolor="#EEEEFF" >
                <td colspan="5" align="center"><h5>Registrar Pruebas</h5></td>
            </tr>
            <tr class="sub"  bgcolor="#CCCCFF" style="text-align:center;">
                <td >Nombre Prueba
                </td>
                <td >
                    Anotación 
                </td>
                <td>
                    Adjuntar <br>Documento  
                </td>
                <td>
                    Documentos 
                </td>
                <td>
                    Eliminar
                </td>
            </tr>
            <tr bgcolor="#DDDDFF">
                <?php $cual = 0; //para la primera fila va a ser 0?>
                <td  class="<?php echo "cont" . $cual; ?>" >
                    <input type="text" name="tipo_prueba[]" id="<?php echo "tipo_prueba" . $cual; ?>"

                </td>
                <td class="<?php echo "cont" . $cual; ?>">
                    <textarea name="anotacion[]" id="<?php echo "anotacion" . $cual; ?>" ></textarea>
                </td>	
                <td class="<?php echo "cont" . $cual; ?>">
                    <form name="<?php echo "myform" . $cual ?>" id="<?php echo "myform" . $cual ?>" method="post" action="mc_avaluo/subir_documentos_pruebas">
                        <input type="hidden" name="id_prueba" id="id_prueba" value="<?php echo $cual ?>">
                        <input type="hidden" name="id_proceso" id="id_proceso" value="<?php echo $post['id'] ?>"  >   
                        <div id="<?php echo "file_source" . $cual; ?>" style="width:320px;"></div>
                        <div class="input-append" id="<?php echo "arch" . $cual; ?>" ></div><br>
                        <div style="width:320px;"  id="file_uploader" class="<?php echo "contar" . $cual ?>"> 
                            <input type="file" name="<?php echo 'archivo00'; ?>" id="<?php echo 'archivo00' ?>"  class='btn btn-success' />
                            <button type="button"  style="background-color: transparent;border:0px;" class="close"  id="<?php echo $cual . $cual; ?>" title="Eliminar Archivo" onclick="deleteFile()"></button>
<!--                            <span id="loader" style="display: none; float:center; position: relative"><img src="<?= base_url() ?>/img/27.gif" width="40px" height="40px" /></span>-->
                        </div><br>
                        <span id="<?php echo "addImg" . $cual; ?>" class="btn btn-success" onclick="addSource(<?php echo $cual ?>)" ><i class="fa fa-cloud-upload"></i> Agregar mas...</span>
                        <input type="button" id="<?php echo "subir" . $cual ?>"   onclick="enviar(<?php echo $cual ?>)"class='btn btn-success' value="Subir"/>
                    </form>       
                </td>
                <td class="<?php echo "cont" . $cual; ?>"><div id="<?php echo "documentos" . $cual; ?>" style="margin-top:0px;">
                        <input type="hidden" name="ruta_carpeta[]" id="<?php echo 'ruta_carpeta_0_' . $cual; ?>" value="">
                    </div></td>
                <td class="<?php echo "cont" . $cual; ?>">
                    <button type="button"   style="background-color: transparent;border:0px;">
                        <li class="icon-remove" style="background: " onclick="borrar(<?php echo $cual ?>,<?php echo $post['id']; ?>)"></li>
                </td>
            </tr>
        </table> 
    </div>
    <div id="ver_botones" style="display:none">
<!--        <input type="button" id="add"   class='btn btn-primary ' value="Agregar Prueba"/><p>-->
        </p>
        <br>
    </div>
    <div id="ver_botones2">
        <input type="button" name="Guardar1"  class='btn btn-success' id="Guardar1" value="Guardar"  onclick="guardar()"   /> 
        <input type="hidden" name="oculto"  id="oculto" value="1" /><!-- Para la primera fila va a ser 0-->    
    </div>
</div>
<br>
<div id="revision"></div>
<script>
    function f_salir()
    {
        window.location = '<?php echo base_url() ?>' + "index.php/mc_avaluo/abogado";
    }
    function f_cancelar() {
        window.location.reload();
    }
    function valida_cor()
    {
        $("#ajax_load").show();
        var correccion = document.getElementById("req_correccion").value;
        if (correccion == '' || correccion == false)
        {
            $("#ajax_load").hide();
            mierror = '<div   class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Indique si las pruebas requieren corrección o no' + '</div>';
            document.getElementById("revision").innerHTML = mierror;
            $("#revision2").fadeOut(3000);
            return false;

        }
        else
        {
            $("#form1").submit();
        }
    }
    //para eliminar el documento
    function eliminarDD(ruta, documento, id_tr, ruta_prueba) {

        $("#ajax_load").show();

        var res = confirm('Seguro desea eliminar el documento?');
        if (res == true) {
            $("#loader").css("display", "block");
            var ruta = ruta;
            var documento = documento;
            var url = "<?= base_url() ?>index.php/mc_avaluo/deleteFile";
            $.ajax({
                type: "POST",
                url: url,
                data: {"ruta": ruta, "documento": documento},
                success: function(data)
                {
                    $("#ajax_load").hide();
                    $("#" + ruta_prueba).val('');

                    //$("#loader").css('display', 'none');
                    //   $("#revision").html(data);
                    $("#" + id_tr).fadeOut("slow", function() {

                        $("#" + id_tr).remove();
                        //como la ruta ya no existe vacio el input que contenía la ruta


                    });



                }
            });
        }
    }





    function enviar(id) {//recibo el id del formulario que contiene los archivos de la prueba seleccionada para subirlos
        //$("#ajax_load").show();
        var archivo = $("#archivo0" + id).val();
//        var anotacion=$("#anotacion"+ id).val();
//        var tipo_prueba=$("#tipo_prueba"+id).val();
        if (!archivo) {

            mierror = '<div   class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe seleccionar un archivo' + '</div>';
            document.getElementById("revision").innerHTML = mierror;
            $("#revision").fadeOut(15000);
            return false;
        }

//        if(!anotacion) {
//            
//            mierror = '<div style="color:#5BB75B;"  class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe ingresar la anotacion' + '</div>';
//            document.getElementById("revision").innerHTML = mierror;
//            return false;
//        }
//          if(!tipo_prueba) {
//            
//            mierror = '<div style="color:#5BB75B;"  class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe seleccionar un tipo de ' + '</div>';
//            document.getElementById("revision").innerHTML = mierror;
//            return false;
//        }
        else {
            var formData = new FormData($("#myform" + id)[0]);

            $("#ajax_load").show();
            $.ajax({
                url: '<?php echo base_url('index.php/mc_avaluo/subir_documentos_pruebas') ?>',
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                //una vez finalizado correctamente

                success: function(data) {
                    //       $("#revision").html(data);
                    //alert("Se Subio el Documento Con Exito ");
                    $("#documentos" + id).html(data);
                    $("#ajax_load").hide();
                    //borrar los input de los archivos subidos
                    //      $("#subir"+id).css('display','none');                           
                }

                ,
                //si ha ocurrido un error
                error: function(data) {
                    $("#ajax_load").hide();
                    alert("Ha Ocurrido un Error");
                    //   $("#revision").html(data);
                }
            });
        }
        $("#ver_botones").css('display', 'block');

    }

    function guardar()
    {
        var num_anotacion = $("#anotacion").length
        var x = document.getElementById("oculto").value;
        var idproceso = document.getElementById("id_p").value;
        document.getElementById('form1').innerHTML = document.getElementById('form1').innerHTML + '<br>' +
                ' <input type="hidden" name="idproceso" id="idproceso" value="' + idproceso + '">';

        i = 0;
        var valor_ruta = 0;
        for (i = 0; i < x; i++)
        {
            //para las anotaciones
            var anotacion = document.getElementsByName('anotacion[]');
            var valor_anotacion = anotacion[i].value;
            // alert(valor_anotacion);
            //para los tipos de prueba
            var tipo_prueba = document.getElementsByName('tipo_prueba[]');
            var valor_prueba = tipo_prueba[i].value;


//
//            //para la ruta de la carpeta
            //var ruta_carpeta = document.getElementsByName('ruta_carpeta[]');
//            var valor_ruta = ruta_carpeta[i].value;

            //valida que exist minimo un documento adjunto
            var documento_prueba = document.getElementsByName('ruta_carpeta[]');
            var valor_documento_prueba = documento_prueba[i].value;
            //alert( valor_documento_prueba );


            if (valor_prueba == '' || valor_prueba == false || !valor_prueba)
            {

                mierror = '<div   class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe ingresar el nombre de prueba' + '</div>';
                document.getElementById("revision").innerHTML = mierror;
                $("#revision").fadeOut(3000);
                return false;
            }
            else if (valor_anotacion == '' || valor_anotacion == false || !valor_anotacion)
            {

                mierror = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe ingresar la descripción de la anotación' + '</div>';
                document.getElementById("revision").innerHTML = mierror;
                $("#revision").fadeOut(3000);
                return false;
            }
            else if (valor_documento_prueba == '' || valor_documento_prueba == false || !valor_documento_prueba)
            {
                mierror = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe adjuntar el documento de la prueba' + '</div>';
                document.getElementById("revision").innerHTML = mierror;
                $("#revision").fadeOut(3000);
                return false;
            }
            else {
                document.getElementById('contenido_form').innerHTML = document.getElementById('contenido_form').innerHTML + '<br>' + '<textarea name="anotaciones[]" id="anotaciones" >' + valor_anotacion + ' </textarea>  <br>'
                        + valor_anotacion + '</textarea>' + '<select name="tipoprueba[]" id="tipoprueba"><option>' + valor_prueba + '</option></select>' +
                        '<br> <input type="hidden" name="nombre_carpeta[]" id="nombre_carpeta" value="' + valor_ruta + '">'
                        ;
            }


        }
        $('#correccion').css('display', 'block');
        $('#pruebas').css('display', 'none');
        $('#ver_botones').css('display', 'none');
        $('#ver_botones2').css('display', 'none');
    }
    function addSource(id) {
        var numItems = jQuery('.contar' + id).length;
        var template = '<br>' +
                '<div  class="field" id="arch' + numItems + '">' +
                '<div class="input-append"  id="arch"' + numItems + ' >' + '</div>' +
                '<div id="file_uploader" class="contar' + id + '">' +
                '<input type="file" name="archivo' + id + numItems + '" id="archivo' + id + numItems + '"class="btn btn-success">' +
                ' <button type="button" class="close" id="button' + id + numItems + '" onclick="deleteFile(' + numItems + ')">&times;</button>' +
                '</div>' +
                '</div>';

        jQuery(template).appendTo('#file_source' + id);
        console.log(numItems);
        $("#subir" + id).css('display', 'block');
    }
    function deleteFile(elemento) {

        $("#arch" + elemento).fadeOut("slow", function() {
            $("#arch" + elemento).remove();
        });

    }

    cont = 0;
//funcion para agregar una fila en la opcion de agregar 
    $(document).ready(function() {
        $("#add").click(function()
        {
            cont = cont + 1;
            var tds = '<tr bgcolor="#DDDDFF">' + '<br>' + '<br>' +
                    '<td class="cont' + cont + '">' + '<br>' + '<select name="tipo_prueba[]" id="tipo_prueba" class="validate[required]" ><?php
                echo '<option value="">Elija una opción...</option>';
                foreach ($tipos_pruebas as $tipo_prueba) :
                    echo '<option value="' . $tipo_prueba['COD_TIPOPRUEBAS'] . '">' . $tipo_prueba['NOMBRE_TIPO_PRUEBAS'] . '</option>';
                endforeach;
                ?></select>    </td> ' +
                    ' <td class="cont' + cont + '">' + '<br>' + ' <textarea name="anotacion[]" id="anotacion"></textarea></td> ' +
                    ' <td class="cont' + cont + '"> ' + '<br>' + ' <form name="myform' + cont + '" id="myform' + cont + '"><input type="hidden" name="id_prueba" id="id_prueba" value="' + cont + '">  <input type="hidden" name="id_proceso" id="id_proceso" value="<?php echo $post['id'] ?>">    <div id="file_source' + cont + '" style="width:320px;" class="contar' + cont + '"></div>     <div style="width:320px;" class="contar' + cont + '0 "   > <input type="file" name="archivo' + cont + '0" id="archivo' + cont + '0"  class="btn btn-primary file_uploader"  /> <button type="button" class="close"  id="' + cont + cont + '" title="Eliminar Archivo" onclick="deleteFile(this.id)"></button>    </div><br> <span id="addImg' + cont + '" class="btn btn-success" onclick="addSource(' + cont + ')"><i class="fa fa-cloud-upload"></i> Agregar mas...</span>        <input type="button" id= "subir' + cont + '"   onclick="enviar(' + cont + ')"class="btn btn-primary" value="Subir"/>  </form> </td> ' +
                    ' <td class="cont' + cont + '"><div id="documentos' + cont + '"></div></td>' +
                    '  <td class="cont' + cont + '"> ' + '<br>' + '<button type="button"  style="background-color: transparent; border=0" onclick="javascript:borrar(' + cont + ',<?php echo $post['id']; ?>);">  <li class="icon-remove" style="background: " ></li> </td>' +
                    '</tr>';
            $("#tabla2").append(tds);
            var abc = document.getElementById("oculto").value;
            a = 1;
            suma = 0;
            var suma = parseInt(abc) + parseInt(a); //menos pero en la funcion de borrar	
            document.getElementById("oculto").value = parseInt(suma);
        });
    })
    //La primera fila de la tabla va a ser 0
    function borrar(cual, id_proceso) {
        //ademas de borrar el tr debe borrar la carpeta creada para los documentos de esa prueba
        $("#ajax_load").show()
        var url = "<?= base_url() ?>index.php/mc_avaluo/eliminar_pruebas";
        $.post(url, {id_prueba: cual, id_proceso: id_proceso}, function(data) {

            $("#ajax_load").hide();
        })
        $("td.cont" + cual).remove();
        oculto1 = document.getElementById("oculto").value
        oculto1 = oculto1 - 1;	//Como elimine una fila y oculto equivale al numero de filas le resto 1 a esa cantidad, la primera fila es 0 para que al momento de enviar los arreglos tanto $i como $oculto sean iguales para el insert
        document.getElementById("oculto").value = oculto1
        return false;
    }




    $(document).ready(function() {




        $('#hacer_anotacion').click(function() {
            $('#div_anotaciones').css("display", "block");
            $('#div_subir').css("display", "none");
            $('#enviar').css("display", "block")

        });
        function validar()
        {
            var tipo_prueba = $('#tipo_prueba').val();
            var anotaciones = $('#anotaciones').val();
            var imagen = $('#imagen').val();
            if (tipo_prueba == '') {
                return false;
            } else if (anotaciones == '')
            {
                return false
            }
            else if (imagen == '') {
                return false;
            }
            else {
                return true;
            }
        }

    });



</script>

<style>
    .hide-close .ui-id-2 { display: none }

</style>