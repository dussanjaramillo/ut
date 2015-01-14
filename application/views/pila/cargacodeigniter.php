<form enctype="multipart/form-data" class="jNice" accept-charset="utf-8" method="post" action="<?= base_url('index.php/pila/add_estates') ?>">
    <fieldset>      
            <label>Title * : </label>                       
            <input type="text" class="text-long" value="" name="title">

            <label>Image : </label>                     
            <input type="file" multiple="" name="images[]">                             

            <button class="button-submit" type="submit" name="save" id="">Save</button>
    </fieldset>         
</form>