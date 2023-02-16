<?php namespace ttools; 
                                         
// PHP7/HTML5, EDGE/CHROME                         *** KwinGalleryClass.php ***

// ****************************************************************************
// * TPhpTools                 Фрэйм галереи изображений, связанных с текущим *
// *                   материалом (uid) из выбранной (указанной) группы (pid) *
// *                                                                          *
// * v2.0, 28.01.2023                              Автор:       Труфанов В.Е. *
// * Copyright © 2022 tve                          Дата создания:  18.12.2019 *
// ****************************************************************************

/**
 * Класс --------- KwinGallery строит интерфейс для выбора некоторых символа Юникода.
 * Выборка символов осуществляется из одного из подмассивов общего массива 
 * массива $aUniCues. Подмассивы (наборы) созданы из авторских соображений и 
 * имеют свои номера и названия, так 0 - 'Знаки всякие-разные', 1 - 'Символы 
 * валют', 2 - 'Ожидаемые символы' и так далее.
 * 
 * Для взаимодействия с объектами класса должны быть определены константы:
 *
 * articleSite  - тип базы данных (по сайту)
 * pathPhpTools - путь к каталогу с файлами библиотеки прикладных классов;
 * pathPhpPrown - путь к каталогу с файлами библиотеки прикладных функции
 * articleSite  - тип базы данных (по сайту)
 * editdir      - каталог размещения файлов, связанных c материалом
 * imgdir       - каталог файлов служебных для сайта изображений
 *    
 * Пример создания объекта класса:
 * 
 * // Указываем место размещения библиотеки прикладных функций TPhpPrown
 * define ("pathPhpPrown",$SiteHost.'/TPhpPrown/TPhpPrown');
 * // Указываем место размещения библиотеки прикладных классов TPhpTools
 * define ("pathPhpTools",$SiteHost.'/TPhpTools/TPhpTools');
 * // Указываем каталоги размещения файлов
 * define("editdir",'ittveEdit');  // файлы, связанные c материалом
 * define("imgdir",'Images');      // служебные для сайта файлы изображений
 * // Указываем тип базы данных (по сайту) для управления классом ArticlesMaker
 * define ("articleSite",'IttveMe'); 
 * // Cоздаем объект для управления изображениями в галерее, связанной с 
 * // материалами сайта из базы данных
 * $Galli=new ttools\KwinGallery(gallidir,$nym,$pid,$uid);
**/

// Свойства:
//
// --- $FltLead - команда управления передачей данных. По умолчанию fltNotTransmit,
//            то есть данные о загрузке не передаются для контроля ни в кукисы, 
// ни в консоль, а только записываются в LocalStorage. Если LocalStorage,
// браузером не поддерживается, то данные будут записываться в кукисы при 
// установке свойства $FltLead в значение fltSendCookies или fltAll 
// $Page - название страницы сайта;
// $Uagent - браузер пользователя;

// ----------------------------------------------- Режимы работы с галереей ---
define ("mwgViewing", 1);   // просмотр
define ("mwgEditing", 2);   // редактирование

// Подгружаем нужные модули библиотеки прикладных функций
require_once pathPhpPrown."/CommonPrown.php";
require_once pathPhpPrown."/getTranslit.php";
require_once pathPhpPrown."/MakeCookie.php";
require_once pathPhpPrown."/iniConstMem.php";
// Подгружаем нужные модули библиотеки прикладных классов
require_once pathPhpTools."/TArticlesMaker/ArticlesMakerClass.php";
require_once pathPhpTools."/TUploadToServer/UploadToServerClass.php";
require_once pathPhpTools."/CommonTools.php";

class KwinGallery
{
   // ----------------------------------------------------- СВОЙСТВА КЛАССА ---
   protected $Arti;      // Объект по работе с базой данных материалов
   protected $apdo;      // Подключение к базе данных материалов
   protected $gallidir;  // Каталог для размещения файлов галереи и связанных материалов
   protected $imgdir;    // каталог служебных изображений
   protected $classdir;  // Каталог класса

   protected $nym;       // Префикс имен файлов для фотографий галереи и материалов
   protected $pid;       // Идентификатор группы текущего материала
   protected $uid;       // Идентификатор текущего материала

   protected $SiteRoot;  // Корневой каталог сайта
   protected $urlHome;   // Начальная страница сайта

   protected $EditImg;   // Имя загруженного изображения
   protected $EditComm;  // Текст начального комментария перед загрузкой файла
   
   // Свойства текущего изображения
   protected $NamePic;     // заголовок изображения (имя исходного файла без расширения)
   protected $TranslitPic; // транслит заголовка изображения
   protected $Ext;         // расширение файла заголовка изображения
   protected $DatePic;     // дата\время создания изображения
   protected $SizePic;     // размер изображения
   protected $Comment;     // комментарий к изображению
   protected $Pic;         // изображение
   
   protected $DelayedMessage;   // Отложенное сообщение

   // Образец массива элементов галереи
   /*
   protected $galleryX = array(
      "gallidir"     => "ittveEdit",
      "nym"          => "ittve",
      "pid"          => 2,
      "uid"          => 30,
      "gallery" => array(
         array(
         "Comment"  => "Ночная прогулка по Ладоге до рассвета и подъёма настроения.",
         "FileName" => "Подъём-настроения.jpg"
         ),
         array(
         "Comment"  => "На горе Сампо всем хорошо!",
         "FileName" => "На-Сампо.jpg"
         ),
         array(
         "Comment"  => "'С заботой и к мамам' - такой мамочкин хвостик.",
         "FileName" => "С-заботой-и-к-мамам.jpg"
         ),
      )
   );
   */
   // Образец массива элементов галереи
   protected $galleryX = array(
      "gallidir"     => "ittveEdit",
      "nym"          => "ittve",
      "pid"          => 2,
      "uid"          => 30,
      "gallery" => array(
         array(
         "Comment"  => "Ночная прогулка по Ладоге до рассвета и подъёма настроения.",
         "FileName" => "Подъём-настроения.jpg"
         ),
      )
   );
   //
   public function getDelayedMessage()
   {
      return $this->DelayedMessage;
   }
   // ------------------------------------------------------- МЕТОДЫ КЛАССА ---
   public function __construct($gallidir,$nym,$pid,$uid,$SiteRoot,$urlHome,$Arti) 
   {
      // Инициализируем свойства класса
      $this->gallidir=$gallidir;                    // каталог файлов редактирования
      $this->imgdir=imgdir;                         // каталог служебных изображений
      $this->classdir=pathPhpTools.'/TKwinGallery'; // каталог класса
      $this->nym=$nym;                              // префикс сайта (платформы)
      $this->pid=$pid;                              // идентификатор текущей группы статей
      $this->uid=$uid;                              // идентификатор текущей статьи 
      $this->SiteRoot=$SiteRoot; 
      $this->urlHome=$urlHome; 
      // Инициируем отложенное сообщение, то есть сообщение, которое может быть
      // выведено на фазе BODY процесса построения страницы сайта 
      $this->DelayedMessage=imok;
      // Инициируем свойства текущего изображения
      $this->NamePic=NULL;     // заголовок изображения к статье (имя файла без расширения)
      $this->TranslitPic=NULL; // транслит заголовка изображения
      $this->Ext=NULL;         // расширение файла заголовка изображения
      $this->DatePic=NULL;     // дата\время создания изображения
      $this->SizePic=NULL;     // размер изображения
      $this->Comment=NULL;     // комментарий к изображению
      $this->Pic=NULL;         // изображение
      // Регистрируем объект по работе с базой данных материалов
      $this->Arti=$Arti;
      // Подключаемся к базе данных материалов
      $this->apdo=$this->Arti->BaseConnect();
      // Формируем начальный кукис изображения для редактирования
      $this->EditImg=\prown\MakeCookie('EditImg',$this->imgdir.'/sampo.jpg',tStr,true);     
      // Если файл был загружен во временное хранилище, то перегружаем его
      // на сервер. Поднимаем из кукиса имя загруженного изображения.
      $this->EditImg=$this->ifKwinUpload($this->SiteRoot,$this->gallidir,$this->nym,$this->pid,$this->uid);
      // Формируем текст начального комментария перед загрузкой файла
      if ($this->EditImg==$this->imgdir.'/sampo.jpg')
        $this->EditComm="На горе Сампо всем хорошо!";
      else
        $this->EditComm="Текст комментария";
      // Выполняем действия на странице до отправления заголовков страницы: 
      // (устанавливаем кукисы и т.д.)                  
      $this->ZeroEditSpace();
      // Трассируем установленные свойства
      //\prown\ConsoleLog('$this->gallidir='.$this->gallidir); 
   }
   public function __destruct() 
   {
      require_once(pathPhpTools."/JsTools.php");
      ?> 
      <script>
      
      nym="<?php echo $this->nym;?>";
      pid="<?php echo $this->pid;?>";
      uid="<?php echo $this->uid;?>"; 

      // **********************************************************************
      // *      Задать обработчик запроса по сохранению галереи материала     *
      // **********************************************************************
      function SaveStuff()
      {
         GalleryText=$('#KwinGallery').html();
         pathphp="SaveStuff.php";
         // Делаем запрос на сохранение галлереи материала
         $.ajax({
            url: pathphp,
            type: 'POST',
            data: {
               nym:nym, pid:pid, uid:uid,
               area:GalleryText, pathTools:pathPhpTools, pathPrown:pathPhpPrown},
            // Выводим ошибки при выполнении запроса в PHP-сценарии
            error: function (jqXHR,exception) {SmarttodoError(jqXHR,exception)},
            // Обрабатываем ответное сообщение
            success: function(message)
            {
               // Вырезаем из запроса чистое сообщение
               messa=FreshLabel(message);
               // Получаем параметры ответа
               parm=JSON.parse(messa);
               alert(parm.NameArt);
               alert('parm.Piati='+parm.Piati);
            }
         });
      }

      </script>
      <?php
   }
   // *************************************************************************
   // *   Выполнить действия на странице до отправления заголовков страницы:  *
   // *                         (установить кукисы и т.д.)                    *
   // *************************************************************************
   private function ZeroEditSpace()
   {
      // Проверяем, нужно ли заменить файл стилей в каталоге редактирования и,
      // (при его отсутствии, при несовпадении размеров или старой дате) 
      // загружаем из класса 
      CompareCopyRoot('sampo.jpg',$this->classdir,$this->imgdir);
      CompareCopyRoot('SaveStuff.php',$this->classdir);
   }
   // *************************************************************************
   // *                          Представить массив галереи                   *
   // *************************************************************************
   public function ViewGalleryAsArray($galleryIn=NULL)
   { 
      if ($galleryIn===NULL) $gallery=$this->galleryX;
      else $gallery=$galleryIn;
      echo '<pre>';
      var_dump($gallery);
      echo '</pre><br>';
      //echo '<br>';
   }
   // *************************************************************************
   // *         Развернуть изображения галереи и обеспечить их ведение        *
   // *     $GalleryMode - режим вывода галереи: mwgViewing или mwgEditing    *
   // *************************************************************************
   public function ViewGallery($galleryIn=NULL,$GalleryMode=mwgViewing)
   {
      if ($galleryIn===NULL) $gallery=$this->galleryX;
      else $gallery=$galleryIn;
      $pref=$gallery['gallidir'].'/'.$gallery['nym'].$gallery['pid'].'-'.$gallery['uid'].'-';
      $aGallery=$gallery['gallery'];
      for ($i=0; $i<count($aGallery); $i++) 
      {
         // Если есть, то выводим начальное изображение
         $this->GViewImage($pref.$aGallery[$i]["FileName"],$aGallery[$i]["Comment"]);
         // Если задан режим редактирования, то выводим изображение загрузки
         if (($GalleryMode=mwgEditing)&&($i==0)) 
         {
            if (IsSet($_POST["MAX_FILE_SIZE"])) $this->GSaveImgComm();
            else  $this->GLoadImage();
         }
      }
   
      /*
      // $FileName="ittveEdit/ittve2-3-Подъём-настроения.jpg";
      $pref=$this->gallidir.'/'.$this->nym.$this->pid.'-'.$this->uid.'-';
     
      $Comment="Ночная прогулка по Ладоге до рассвета и подъёма настроения.";
      $this->GViewImage($pref.'Подъём-настроения.jpg',$Comment);

      $this->GLoadImage($this->gallidir.'/'.'sampo.jpg');
      //$this->GLoadImage($pref.'Размыло-дорогу.jpg');
      //$Comment="Здесь комментарий.";
      //$this->GViewImage($pref.'Размыло-дорогу.jpg',$Comment);

      $Comment="На горе Сампо всем хорошо!";
      $this->GViewImage($pref.'На-Сампо.jpg',$Comment);

      $Comment="'С заботой и к мамам' - такой мамочкин хвостик.";
      $this->GViewImage($pref.'С-заботой-и-к-мамам.jpg',$Comment);

      // Из галереи задаем режим представления выбранной картинки - "на высоту страницы"
      //$s_ModeImg=prown\MakeSession('ModeImg',vimOnPage,tInt);           
      */
      
      /*
      // Выбираем режим работы с изображениями, как режим редактирования материала
      if ($Dir==$this->editdir)
      {
         // Формируем определяющий массив для базы данных редактируемого материала
         $aCharters=$this->MakeaCharters($apdo);
         // Проверяем существование и создаем базу данных редактируемого материала
         $basename=$_SERVER['DOCUMENT_ROOT'].'/itEdit'; // имя базы без расширения 'db3'
         $username='tve';
         $password='23ety17'; 
         $Arti=new ArticlesMaker($basename,$username,$password);
         // Создаем (или открываем) базу данных для редактируемого материала
         //$Arti->BaseFirstCreate($aCharters);
      }
      else
      {
         \prown\ConsoleLog('НЕ ='.$this->editdir); 
      }
      */
   }
   // *************************************************************************
   // *            Развернуть изображения галереи из базы данных              *
   // *                          и обеспечить их ведение                      *
   // *     $GalleryMode - режим вывода галереи: mwgViewing или mwgEditing    *
   // *************************************************************************
   public function BaseGallery($GalleryMode=mwgViewing)
   {
      // Выбираем все фотографии по идентификатору текущей статьи
      $tableKeys=$this->Arti->SelImgKeys($this->apdo,$this->uid);
         
         //echo '<pre>';
         //print_r($tableKeys);
         //echo '</pre>';
         
      $i=0;
      foreach ($tableKeys as $row)
      {
         //echo $tableKeys[$i]['mime_type'];
         //echo $row['mime_type'].'<br>';
         $uid=$row['uid'];
         $TranslitPic=$row['TranslitPic'];
         $Comment=$row['CommPic'];
         //echo $uid.'->'.$TranslitPic.'<br>';
         
         $table=$this->Arti->SelImgPic($this->apdo,$uid,$TranslitPic);
         //echo '<pre>';
         //print_r($table);
         //echo '</pre>';
         
         $Action='Image';
         echo '<div class="Card">'.
              '<button class="bCard" type="submit" name="'.$Action.'">';
         if ($table != null) 
         echo '<img class="imgCard" src="data:'.$row['mime_type'].';base64,'.base64_encode($table['Pic']).'"/>';
         else echo 'Error loading document';
         echo 
            '</button>';
         echo '<p class="pCard">'.$Comment.'</p>';
         echo 
            '</div>';

         
         // Если задан режим редактирования, то выводим изображение загрузки
         if (($GalleryMode=mwgEditing)&&($i==0)) 
         {
            if (IsSet($_POST["MAX_FILE_SIZE"])) $this->GSaveImgComm();
            else  $this->GLoadImage();
         }
         
         $i++;
         
      } 

   }
   
   protected function GViewImage($FileName,$Comment,$Action='Image')
   {
      
      echo 
         '<div class="Card">'.
         '<button class="bCard" type="submit" name="'.$Action.'">'.
         '<img class="imgCard" src="'.$FileName.'">'.
         '</button>';
      echo '<p class="pCard">'.$Comment.'</p>';
      echo 
         '</div>';
      
      /*
      echo 
         '<div class="Card">'.
         '<button class="bCard" type="submit" name="'.$Action.'">'.
         '<img class="imgCard" src="data:image/jpeg;base64,'.base64_encode(file_get_contents("test.jpg")).'"/>'.
         '</button>';
      echo '<p class="pCard">'.$Comment.'</p>';
      echo 
         '</div>';
      */
   }
   
   protected function GLoadImage()
   {
      /**
       * Размещаем в форме поле для загрузки файла, а перед ним (иначе не будет
       * работать) поле для контроля размера загружаемого файла. 
       * Преимущество скрытого поля с именем MAX_FILE_SIZE в том, что PHP остановит
       * процесс загрузки файла при превышении размера
       * 
       * При нажатии на кнопку 'submit' происходит запрос с параметрами:
       * http:... .php ?MAX_FILE_SIZE=3000024 &loadimg=... .jpg &AREAM= ... 
      **/
      echo '<div class="Card">';
      echo '
         <button id="bLoadImg"  class="navButtons" onclick="alf1FindFile()"  
         title="Загрузить изображение">Загрузить изображение
        </button>
      ';
      echo '
         <form method="post" enctype="multipart/form-data"> 
         <input type="hidden" name="MAX_FILE_SIZE" value="3000024"/> 
         <input type="file"   name="loadimg"  id="infCard"
            accept="image/jpeg,image/png,image/gif" 
            onchange="alf2LoadFile();"/>  
         <img id="imgCardi" src="'.$this->EditImg.'" alt="'.$this->EditImg.'">
         <p class="pCard">'.$this->EditComm.'</p>
         <input type="submit" id="insCard">  
      ';
      echo '</form>';
      echo '</div>';
   }
   
   protected function GSaveImgComm()
   {
      echo '<div class="Card">';
      echo '
         <button id="bLoadComm"  class="navButtons" onclick="alf3SaveImgComm()"  
         title="Записать с комментарием">Записать с комментарием
         </button>
      ';
      echo '
         <form method="POST"> 
         <img id="imgCardi" src="'.$this->EditImg.'" alt="'.$this->EditImg.'">
         <textarea class="taCard" name="AREAM">'.$this->EditComm.'</textarea>
         <input type="submit" id="insCard">  
      ';
      echo '</form>';
      echo '</div>';
   }
   /*
   // Обновляем изображение, если была загрузка изображения
   //if (IsSet($_POST["MAX_FILE_SIZE"])) 
   //{
   //   ?> <script> 
   //      niname="<?php echo $this->EditImg;?>";
   //      $('#imgCardi').attr('src',niname); 
   //   </script> <?php
   //}
   */
   // *************************************************************************
   // *************************************************************************
   public function UpdateImg($pdo)
   {
      /*
      // Выбираем текущую страницу
      $html=curl($this->urlHome,$varerr);
      PutString('$varerr ='.$varerr.'<br><br>','proba.txt');
      PutString('$html ='.$html.'<br><br>','proba.txt');
      */
      //$pref=$gallery['gallidir'].'/'.$gallery['nym'].$gallery['pid'].'-'.$gallery['uid'].'-';
   }
   
   // --------------------------------------------------- ВНУТРЕННИЕ МЕТОДЫ ---

   // *************************************************************************
   // *   Если файл был загружен во временное хранилище, то перегрузить его   *
   // *       на сервер. Поднять из кукиса имя загруженного изображения.      *
   // *************************************************************************
   protected function ifKwinUpload($SiteRoot,$gallidir,$nym,$pid,$uid)
   {
      $Result=\prown\MakeCookie('EditImg');
      // Инициируем префикс, имя файла, расширение 
      $pref=$nym.$pid.'-'.$uid.'-'; $NameLoadp='NoDefine'; $Ext='nodef';
      // Ловим момент, когда файл загружен во временное хранилище
      if (IsSet($_POST["MAX_FILE_SIZE"])) 
      {
         // Перебрасываем файл из временного хранилища
         $this->DelayedMessage=$this->MakeKwinUpload($SiteRoot,$gallidir,$pref,$NameLoadp,$Ext);
         // Отмечаем новое имя загруженного файла
         if ($this->DelayedMessage==imok)
         $Result=\prown\MakeCookie('EditImg',$gallidir.'/'.$pref.$NameLoadp.'.'.$Ext,tStr);
      }
      if (IsSet($_POST["AREAM"])) 
      {
         $aFileImg=unserialize(\prown\MakeCookie('cFileImg'));
         
         $this->DelayedMessage=$this->Arti->InsertImgByTranslit
            ($this->apdo,$this->uid,$aFileImg["NamePic"],$aFileImg["TranslitPic"],
            $aFileImg["Ext"],$aFileImg["mime_type"],$aFileImg["DatePic"],$aFileImg["SizePic"],$_POST['AREAM']);
         if ($this->DelayedMessage<>imok) \prown\Alert($this->DelayedMessage.'[All-]'); 
         else $this->DelayedMessage=$this->Arti->UpdatePicByTranslit($this->apdo,$aFileImg["FileSpec"],$aFileImg["TranslitPic"]);
         if ($this->DelayedMessage<>imok) \prown\Alert($this->DelayedMessage.'[Pic]'); 
      }
      return $Result;
   }
   // *************************************************************************
   // *      Переместить загруженный файл из временного хранилища на сервер   *
   // *************************************************************************
   protected function MakeKwinUpload($SiteRoot,$gallidir,$pref,&$NameLoadp,&$Ext)
   {
      $DelayedMessage=imok;
      $imgDir=$SiteRoot.'/'.$gallidir;
      $FileName=$_FILES["loadimg"]["name"]; 
      $mime_type=$_FILES["loadimg"]["type"]; 
      $FileName=substr($FileName,0,strpos($FileName,'.'));
      $NameLoadp=\prown\getTranslit($FileName);
      // Перебрасываем файл  
      $upload=new UploadToServer($imgDir,$pref.$NameLoadp);
      $DelayedMessage=$upload->move();
      $Ext=$upload->getExt();
      // Если переброска была успешной, 
      // то переопределяем свойства текущего изображения
      if ($this->DelayedMessage==imok)
      {
         // Готовим массив свойств загруженного файла
         $FileSpec=$imgDir.'/'.$pref.$NameLoadp.'.'.$Ext;
         $aFileImg = array(
            "NamePic"     => $FileName,
            "TranslitPic" => $NameLoadp,
            "Ext"         => $Ext,
            "mime_type"   => $mime_type,
            "SizePic"     => $_FILES["loadimg"]["size"],
            "DatePic"     => date('d.m.Y',filectime($FileSpec)),
            "FileSpec"    => $FileSpec,
         );
         // Складываем массив в кукис
         $cFileImg=serialize($aFileImg); 
         \prown\MakeCookie('cFileImg',$cFileImg,tStr);
      }
      unset($upload);
      return $DelayedMessage; 
   }
} 

// *************************************************** KwinGalleryClass.php ***
