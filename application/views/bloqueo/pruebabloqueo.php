<?php
//ruta prototiposena: application/views/bloqueo/pruebabloqueo.php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script src="../../js/jquery-1.9.1.js"></script>
<script language="Javascript">
    $(".preload, .load").hide();
    $(document).ready(ini);

    function ini() {
        $("#cuac").click(cargarmodal);
        $('#boton').click(function() {
            /*
             * @author: Felipe R. Puerto :: Thomas MTI
             * Parámetros para traer un bloqueo:
             * ruta: url del controlador tiempos, método bloqueo
             * codfiscalizacion: Cód. de fiscalización, se requiere cuando el parámetro fecha no tiene un valor asignado.
             * gestion: Código de la gestión.
             * fecha: fecha en la que empieza el bloqueo si no se envía toma el día de hoy
             * mostrar: cuando está en 'SI' muestra una pantalla sobrepuesta para bloquear el contenido debajo. Cuando está en 'NO' envía un arreglo en json con los resultados
             * si: URL a la que irá al oprimir el botón Desbloquear. Si el valor es '.OCULTAR' no muestra el botón.
             * no: URL a la que irá al oprimir el botón No hubo respuesta. Si el valor es '.OCULTAR' no muestra el botón. 
             * parametros: parámetros que se enviarán a la URL al oprimir Desbloquear o No hubo respuesta. El formato es "nombre1:valor1;nombre2:valor2;"
             * BD: si el valor es también 'BD', guarda en la BD la fecha de comienzo y vencimiento y las consulta para generar el bloqueo
             **/
            var ruta = "<?php echo base_url('index.php/tiempos/bloqueo'); ?>";
            $("#mod").load(ruta, {codfiscalizacion: 301, gestion: 82, fecha: '2013-01-01', mostrar: 'SI', si: 'http://yeah.com', no: 'http://no.com', parametros: 'casa:23C-81A;carro:Hyundai;saludo:Comoestas', BD: ''});
        });
    }

    function cargarmodal() {
        var ruta = "<?php echo base_url('index.php/tiempos/bloqueo'); ?>";
        $("#mod").load(ruta, {codfiscalizacion: 301, gestion: 33, fecha: '19/02/14', mostrar: 'SI', si: 'http://yeah.com', no: 'http://no.com', parametros: 'casa:23C-81A;carro:Hyundai;saludo:Comoestas', BD: ''}, function() {
        });
        //$.post( ruta, {codfiscalizacion:301, gestion:35, fecha:'2013-09-18', mostrar:'NO', si:'http://yeah.com', no:'http://no.com', parametros : 'casa:Calle54#10-39;carro:Mazda;saludo:Buendia', BD:'BD'},function(){$( ".result" ).html( data );} );
        //$("#mod").load(ruta,{codfiscalizacion:301, gestion:33, fecha:'2012-11-29', mostrar:'SI', si:'http://yeah.com', no:'http://no.com', parametros:'casa:23C-81A;carro:Hyundai;saludo:Comoestas', BD:'BD'},function(){});
        //$("#mod").load(ruta,"codfiscalizacion=301&gestion=33&fecha=13/02/15&mostrar=SI&si=http://yeah.com&no=http://no.com&parametros=casa:3;carro:hola;saludo:bienvenidos&BD=BD",function(){});
    }
</script>
<body >
    <h1>A<br>B<br>C<br>D<br>E<br>F<br>G<!--br>H<br>I<br>J<br-->K</h1>z
    <div id="mod"></div>
    <form name="formulario"> 
        <h1>Cronometro Envia Alerta y Correo</h1>
        <input type="button" name="reloj" value=""> 
        <input type="button" id="cuac" value="enviar"> 

    </form>
    <input id="boton" type="button" value="bloquear">
    <div id="mod"></div>
    <div id="bas">...</div>

</body>
</html>    

<?php
echo 'Hola Mundo';
?>