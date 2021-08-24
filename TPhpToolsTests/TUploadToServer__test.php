<?php

// PHP7/HTML5, EDGE/CHROME                     *** TUploadToServer_test.php ***

// ****************************************************************************
// * TPhpPrown      Тест класса TUploadToServer - загрузчика файлов на сервер *
// ****************************************************************************
// *                                                                          *
// * v2.0, 24.08.2021                              Автор:       Труфанов В.Е. *
// * Copyright © 2020 tve                          Дата создания:  03.12.2020 *
// ****************************************************************************

// echo 'Тестируется TUploadToServer_test.php'.'<br>';

MakeTitle("TUploadToServer",'');

$CurrDir='c:\TPhpTools\www';
//chdir($CurrDir);
// Указываем каталог для хранения изображений
$imgDir=$CurrDir.'/'."Gallery";
$destination = $imgDir.'/';
$upload = new ttools\UploadToServer($destination);

if (count($_FILES)>0)
{
   echo $_FILES['image']['name'];
   //print_r($_FILES);
}




$max = 57200;

?>
<form action="" method="post" enctype="multipart/form-data" id="uploadImage">
<p>
<label for="image">Выберите изображение:</label>
<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max; ?>">
<input type="file" name="image" id="image">
</p>
<p>
<input type="submit" name="upload" id="upload" value="Загрузите">
</p>
</form>
<?php




//filemtime('генерируем отладочное исключение'); 
// Начинаем протоколировать тесты
//SimpleMessage();


/*

PointMessage('Проверяется существование и удаляется старый файл базы данных:');  
SimpleMessage(); PointMessage('--- '.'$filename');  
OkMessage();
      
PointMessage('Проверяются настройки целостности по внешним ключам:'); 

// Заново создаем базу данных и подключаем к ней TBaseMaker
$db = new ttools\BaseMaker($pathBase,$username,$password);
// Тестируем Values, Rows методы
test_ValueRow($db,$shellTest);

// PragmaBaseTest($pdo,$shellTest);
OkMessage();
*/
// *********************************************** TUploadToServer_test.php ***
