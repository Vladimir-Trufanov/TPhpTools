<?php namespace ttools; 

// PHP7/HTML5, EDGE/CHROME                               *** KwinUpload.php ***

// ****************************************************************************
// * TKwinGallery                                Переместить загруженный файл *
// *                                        из временного хранилища на сервер *
// *                                                                          *
// * v1.0, 09.02.2023                              Автор:       Труфанов В.Е. *
// * Copyright © 2022 tve                          Дата создания:  23.02.2022 *
// ****************************************************************************

function ifKwinUpload($SiteRoot,$gallidir,$nym,$pid,$uid)
{
   // Инициируем префикс, имя файла, расширение 
   $pref=$nym.$pid.'-'.$uid.'-'; $NameLoadp='NoDefine'; $Ext='nodef';
   // Ловим момент, когда файл загружен во временное хранилище
   if (IsSet($_POST["MAX_FILE_SIZE"])) 
   {
      // Перебрасываем файл из временного хранилища
      MakeKwinUpload($SiteRoot,$gallidir,$pref,$NameLoadp,$Ext);
      // Отмечаем новое имя загруженного файла
      \prown\MakeCookie('EditImg',$pref.$NameLoadp.'.'.$Ext,tStr);
      // Обновляем изображение
      ?> <script> 
         niname="<?php echo $pref.$NameLoadp;?>";
         $('#imgCardi').attr('src','ittveEdit/'+niname); 
      </script> <?php
   }
}
function MakeKwinUpload($SiteRoot,$gallidir,&$pref,&$NameLoadp,&$Ext)
{
   // Создаем каталог для хранения изображений, если его нет.
   //$modeDir=0777;
   //$isDir=prown\CreateRightsDir($imgDir,$modeDir,rvsReturn);
   // Если с каталогом все в порядке, то будем перебрасывать файл на сервер
   //if ($isDir===true)
   //{
   //}
   
   // Перемещаем оригинальное изображение
   //\prown\ConsoleLog('$SiteRoot='.$SiteRoot);
   //\prown\ConsoleLog('$gallidir='.$gallidir);
   
   $InfoMess='NoDefine'; 
   $imgDir=$SiteRoot.'/'.$gallidir;
   if (MoveFromUpload($imgDir,$pref,$NameLoadp,$Ext,$InfoMess,$FileName,$FileSize))
   {
      //\prown\ConsoleLog('Перемещено!');
   }
   else
   {
     // \prown\ConsoleLog('Ошибка. '.$InfoMess);
   }
   //\prown\ConsoleLog('MakeKwinUpload');
}
// ****************************************************************************
// *       Переместить загруженный файл из временного хранилища на сервер     *
// ****************************************************************************
function MoveFromUpload($imgDir,$pref,&$NameLoadp,&$Ext,&$InfoMess,&$FileName,&$FileSize)
{
   //$one=serialize($_FILES);
   //\prown\ConsoleLog('$one:'.$one);
   //\prown\ConsoleLog('$_FILES["loadimg"]["name"]: '.$_FILES["loadimg"]["name"]);
   //\prown\ConsoleLog('$_FILES["loadimg"]["size"]: '.$_FILES["loadimg"]["size"]);
   $Result=true;
   // Получаем транслит имени загруженного файла и другие сведения
                        //$this->_type=substr($field['type'],strpos($field['type'],'/')+1);

   $FileName=$_FILES["loadimg"]["name"]; $FileName=substr($FileName,0,strpos($FileName,'.'));
   $FileSize=$_FILES["loadimg"]["size"];
   $NameLoadp=\prown\getTranslit($FileName);
   //\prown\ConsoleLog('$FileName='.$FileName);
   //\prown\ConsoleLog('$FileSize='.$FileSize);
   //\prown\ConsoleLog('$NameLoadp='.$NameLoadp);

   // Перебрасываем файл  
   $upload=new UploadToServer($imgDir,$pref.$NameLoadp);
   $InfoMess=$upload->move();
   $Ext=$upload->getExt(); 
   //\prown\ConsoleLog('$Ext='.$Ext);
   unset($upload);
   // Если перемещение завершилось неудачно, то выдаем сообщение
   if ($InfoMess<>imok) $Result=false;
   // Перемещение файла на сервер выполнилось успешно, меняем кукис
   //else $c_FileImg=prown\MakeCookie($NameCookie,$localimg,tStr);
   return $Result; 
}

/*
function ifSignaUpload(&$InfoMess,$imgDir,$urlDir,&$c_FileStamp,&$c_FileImg,&$c_FileProba)
{
   // Отрабатываем функцию только тогда, когда нет ошибки
   if ($InfoMess===ajSuccess)
   {
      if (IsSet($_POST["MAX_FILE_SIZE"])) 
      MakeSignaUpload($InfoMess,$imgDir,$urlDir,$c_FileStamp,$c_FileImg,$c_FileProba);
   }
}
function MakeSignaUpload(&$InfoMess,$imgDir,$urlDir,&$c_FileStamp,&$c_FileImg,&$c_FileProba)
{
   // Создаем каталог для хранения изображений, если его нет.
   $modeDir=0777;
   $isDir=prown\CreateRightsDir($imgDir,$modeDir,rvsReturn);
   // Если с каталогом все в порядке, то будем перебрасывать файл на сервер
   if ($isDir===true)
   {
      // Определяем, загрузка какого файла выполнена: оригинального изображения
      // или образца подписи, через имя массива ("loadimg","loadstamp") из $_FILES         
      $NameInput=getLoadKind();
      $field=current($_FILES);
      $type=substr($field['type'],strpos($field['type'],'/')+1);
      // Перемещаем штамп
      if ($NameInput=="loadstamp") 
      {
         // Назначаем префикс имени файла в соответствии с RID и для файла штампа
         $PostFix='stamp';
         $PrefName=prown\MakeNumRID($imgDir,$PostFix,$type,true);
         $NameLoad=$PrefName.$PostFix;
         $localimg=$urlDir.'/'.$NameLoad.'.'.$type;
         $nameimg=$imgDir.'/'.$NameLoad.'.'.$type;
         // Перемещаем загруженный файл из временного хранилища на сервер,
         // записываем кукис                            
         MoveFromUpload($InfoMess,$imgDir,$NameLoad,$c_FileStamp,'FileStamp',$localimg);
      }
      // Перемещаем оригинальное изображение и делаем копию, как подписанное фото
      else if ($NameInput=="loadimg") 
      {
         // Перемещаем оригинальное изображение
         $PostFix='img';
         $PrefName=prown\MakeNumRID($imgDir,$PostFix,$type,true);
         $NameLoad=$PrefName.$PostFix;
         $localimg=$urlDir.'/'.$NameLoad.'.'.$type;
         $nameimg=$imgDir.'/'.$NameLoad.'.'.$type;
         if (MoveFromUpload($InfoMess,$imgDir,$NameLoad,$c_FileImg,'FileImg',$localimg))
         {
            // Создаем копию оригинального изображение для подписи
            // Важно: здесь имена создаем через MakeNumRID, как и для оригинального
            // изображения для того чтобы автоматически удалялся старый файл
            $PostFix='proba';
            $PrefName=prown\MakeNumRID($imgDir,$PostFix,$type,true);
            $NameLoad=$PrefName.$PostFix;
            $localimgp=$urlDir.'/'.$NameLoad.'.'.$type;
            $nameimgp=$imgDir.'/'.$NameLoad.'.'.$type;
            if (copy($nameimg,$nameimgp)) $c_FileProba=prown\MakeCookie('FileProba',$localimgp,tStr);
            else $InfoMess=ajCopyImageNotCreate;
         }
      }
   }
}
// ****************************************************************************
// *       Определить, загрузка какого файла выполнена: оригинального         *
// *     изображение или образца подписи, через имя массива ("loadimg",       *
// *                     "loadstamp") из $_FILES                              *
// ****************************************************************************
// Файлы могут быть загружены из двух форм.
// "loadimg":
//   <form action="SignaPhoto.php" method="POST" enctype="multipart/form-data"> 
//   <input type="hidden" name="MAX_FILE_SIZE" value="3000024"/> 
//   <input type="file"   id="my_hidden_file" accept="image/jpeg,image/png,image/gif" 
//   name="loadimg" onchange="alf2LoadFile();"/>  
//   <input type="submit" id="my_hidden_load" value="">  
//   </form>
// "loadstamp":
//   <form action="SignaPhoto.php" method="POST" enctype="multipart/form-data"> 
//   <input type="hidden" name="MAX_FILE_SIZE" value="3000024"/> 
//   <input type="file"   id="my_shidden_file" accept="image/jpeg,image/png,image/gif" 
//   name="loadstamp" onchange="alf2sLoadFile();"/>  
//   <input type="submit" id="my_shidden_load" value="">  
//   </form>
function getLoadKind()
{
   // Определяем имя подмассива ("loadimg","loadstamp") по $_FILES
   // рассматриваем через сериализацию, когда загружен 1 файл:
   // или оригинальное изображение, или подпись !!! 
   $NameInputFile=serialize($_FILES);
   // Отрезаем часть строки до имени подмассива
   $PatternBefore="/\/\/\sМодуль([0-9a-zA-Zа-яёА-ЯЁ\s\.\$\n\r\(\)-:,=&;]+)\/\/\s---/u";
   $PatternBefore="/^a:1:\{s:[0-9]:\"/u";    // "/\/\/\sМодуль([0-9a-zA-Zа-яёА-ЯЁ\s\.\$\n\r\(\)-:,=&;]+)\/\/\s---/u");
   $Replacement="";
   $NameInputAfter=preg_replace($PatternBefore,$Replacement,$NameInputFile);
   // Отрезаем часть строки после имени подмассива
   $PatternAfter="/\";a:5:\{([\\a-zA-Zа-яёА-ЯЁ\:0-9\";\.\/_\}]*)/u";   
   $NameInput=preg_replace($PatternAfter,$Replacement,$NameInputAfter);
   return $NameInput;
}
// ****************************************************************************
// *       Переместить загруженный файл из временного хранилища на сервер     *
// *                           и записать в кукис                             *
// ****************************************************************************
function MoveFromUpload(&$InfoMess,$imgDir,$NameLoadp,&$c_FileImgx,$NameCookie,$localimg)
{
   $Result=true;
   // Перебрасываем файл  
   $upload=new ttools\UploadToServer($imgDir,$NameLoadp);
   $MessUpload=$upload->move();
   // Если перемещение завершилось неудачно, то выдаем сообщение
   if ($MessUpload<>imok) 
   {
      $InfoMess=$MessUpload;
      $Result=false;
   }
   // Перемещение файла на сервер выполнилось успешно, меняем кукис
   else $c_FileImgx=prown\MakeCookie($NameCookie,$localimg,tStr);
   return $Result; 
}
*/
// ********************************************************* KwinUpload.php ***
