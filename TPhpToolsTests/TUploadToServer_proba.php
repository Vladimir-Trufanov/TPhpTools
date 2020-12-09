<?php

// Создаем каталог для хранения изображений, если его нет.
// И отдельно (чтобы сработало на старых Windows) задаем права
$imgDir = "Gallery";
if (!is_dir($imgDir))
{
   mkdir($imgDir);      
   chmod($imgDir,0777);
}

// set the maximum upload size in bytes
$max = 57200;
if (isset($_POST['upload'])) 
{
   // define the path to the upload folder
   $destination = $imgDir.'/';
   //$destination ='C:/upload_test/';
   try 
   {
   
      $upload = new ttools\UploadToServer($destination);
      $upload->move();
      $upload->setMaxSize($max);
      $result = $upload->getMessages();
   } 
   catch (Exception $e) 
   {
      echo $e->getMessage();
   }
}

// Выводим сообщения по итогам загрузки файла
if (isset($result)) 
{
   echo '<ul>';
   foreach ($result as $message) 
   {
      echo "<li>$message</li>";
   }
   echo '</ul>';
}

?>
<form action="" method="post" enctype="multipart/form-data" id="uploadImage">
<p>
<label for="image">Upload images:</label>
<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max; ?>">
<input type="file" name="image" id="image">
</p>
<p>
<input type="submit" name="upload" id="upload" value="Upload">
</p>
</form>
<?php
