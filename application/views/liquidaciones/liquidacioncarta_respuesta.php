<?php
/**
* Formulario para consultar liquidaciones en el proceso de cobro persuasivo
*
* @package    Cartera
* @subpackage Views
* @author     jdussan
* @location     application/views/gestionliquidacion_list.php
* @last-modified  30/10/2014
* @copyright
*/
if(!defined('BASEPATH') ) exit('No direct script access allowed');
if(isset($message))
{
    echo $message;
}
?>
<div class="center-form-large">
    <h2 class="text-center">Carta Remisión de Liquidación</h2>
    <?php
    if (isset($message)):
        echo $message;
    endif;
    ?>
    <textarea id="informacion"  name = "informacion" style="width: 100%;height: 400px">
        <?php
        echo($plantilla);
        ?>
    </textarea>
    <br><br>
    <center>
    <?php
    $data_1 = array(
        'name' => 'button',
        'id' => 'generar',
        'value' => 'seleccionar',
        'content' => '<i class="fa fa-print"></i>  Imprimir',
        'class' => 'btn btn-info'
    );
    echo '<table width = "100%"><tr><td width = "50%" style = "text-align:center !important">';
    echo anchor('liquidaciones/getLiquidacion', '<i class="fa fa-minus-circle"></i> Cancelar', 'class="btn btn-warning"');
    echo '</td><td width = "50%" style = "text-align:center !important">';
    echo form_button($data_1);
    echo '</td></tr></table>';
    ?>
</center>
</div>


<script>
    function redirect_by_post(purl, pparameters, in_new_tab) {
        pparameters = (typeof pparameters == 'undefined') ? {} : pparameters;
        in_new_tab = (typeof in_new_tab == 'undefined') ? true : in_new_tab;
        var form = document.createElement("form");
        $(form).attr("id", "reg-form").attr("name", "reg-form").attr("action", purl).attr("method", "post").attr("enctype", "multipart/form-data");
        if (in_new_tab) {
            $(form).attr("target", "_blank");
        }
        $.each(pparameters, function(key) {
            $(form).append('<textarea name="' + key + '" >' + this + '</textarea>');
        });
        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
        return false;
    }
    $('#generar').click(function() {
        var informacion = base64_encode(tinymce.get('informacion').getContent());
        var nombre = "Carta Remisión de Liquidación";
        var titulo = "Carta Remisión de Liquidación"
        var tipo = '3';

        if (informacion == "" || nombre == "") {
            alert('No se puede continuar hasta que haya realizado el cuerpo del documento');
            return false;
        }

        $(".preload, .load").show();

        var url = '<?php echo base_url('index.php/liquidaciones/pdf') ?>';

        var url = '<?php echo base_url('index.php/liquidaciones/pdf') ?>';
        redirect_by_post (url,
            {html: informacion,
            nombre: nombre,
            titulo: titulo,
            tipo: tipo
            },
            true);
        $(".preload, .load").hide();
    });
    tinymce.init({
        language: 'es',
        selector: "textarea#informacion",
        theme: "modern",
        plugins: [
            "advlist autolink lists link  charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
                    //"insertdatetime media nonbreaking save table contextmenu directionality",
                    //"emoticons template paste textcolor moxiemanager"
        ],
        toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        toolbar2: "print preview media | forecolor backcolor emoticons",
        image_advtab: true,
        templates: [
            {title: 'Test template 1', content: '<b>Test 1</b>'},
            {title: 'Test template 2', content: '<em>Test 2</em>'}
        ],
        autosave_ask_before_unload: false
    });

function base64_encode(data)
{
    var b64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
    var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
    ac = 0,
    enc = '',
    tmp_arr = [];
    if (!data)
    {
        return data;
    }
    do
    {
        o1 = data.charCodeAt(i++);
        o2 = data.charCodeAt(i++);
        o3 = data.charCodeAt(i++);
        bits = o1 << 16 | o2 << 8 | o3;
        h1 = bits >> 18 & 0x3f;
        h2 = bits >> 12 & 0x3f;
        h3 = bits >> 6 & 0x3f;
        h4 = bits & 0x3f;
        tmp_arr[ac++] = b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
    }
    while (i < data.length);
    enc = tmp_arr.join('');
    var r = data.length % 3;
    return (r ? enc.slice(0, r - 3) : enc) + '==='.slice(r || 3);
}
</script>

<!--
/* End of file liquidacioncarta_form.php */
-->