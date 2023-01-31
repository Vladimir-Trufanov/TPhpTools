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
 *    
 * Пример создания объекта класса:
 * 
 * // Указываем место размещения библиотеки прикладных функций TPhpPrown
 * define ("pathPhpPrown",$SiteHost.'/TPhpPrown/TPhpPrown');
 * // Указываем место размещения библиотеки прикладных классов TPhpTools
 * define ("pathPhpTools",$SiteHost.'/TPhpTools/TPhpTools');
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
// Подгружаем нужные модули библиотеки прикладных классов
require_once pathPhpTools."/TArticlesMaker/ArticlesMakerClass.php";
require_once pathPhpTools."/CommonTools.php";

class KwinGallery
{
   // ----------------------------------------------------- СВОЙСТВА КЛАССА ---
   protected $gallidir;  // Каталог для размещения файлов галереи и связанных материалов
   protected $classdir;  // Каталог класса
   protected $nym;       // Префикс имен файлов для фотографий галереи и материалов
   protected $pid;       // Идентификатор группы текущего материала
   protected $uid;       // Идентификатор текущего материала

   protected $SiteRoot;  // Корневой каталог сайта
   protected $urlHome;   // Начальная страница сайта

   // Образец массива элементов галереи
   protected $galleryX = array(
      "gallidir"     => "ittveEdit",
      "nym"          => "ittve",
      "pid"          => 2,
      "uid"          => 3,
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
   // ------------------------------------------------------- МЕТОДЫ КЛАССА ---
   public function __construct($gallidir,$nym,$pid,$uid,$SiteRoot,$urlHome) 
   {
      // Инициализируем свойства класса
      $this->gallidir=$gallidir;                    // каталог файлов редактирования
      $this->classdir=pathPhpTools.'/TKwinGallery'; // каталог класса
      $this->nym=$nym;                              // префикс сайта (платформы)
      $this->pid=$pid;                              
      $this->uid=$uid;

      $this->SiteRoot=$SiteRoot; 
      $this->urlHome=$urlHome; 

      // Выполняем действия на странице до отправления заголовков страницы: 
      // (устанавливаем кукисы и т.д.)                  
      $this->ZeroEditSpace();
      // Трассируем установленные свойства
      //\prown\ConsoleLog('$this->gallidir='.$this->gallidir); 
      //\prown\ConsoleLog('$this->nym='.$this->nym); 
      //\prown\ConsoleLog('$this->pid='.$this->pid); 
      //\prown\ConsoleLog('$this->uid='.$this->uid); 
   }
   public function __destruct() 
   {
      ?> 
      <script>
      // **********************************************************************
      // *      Задать обработчик запроса по сохранению галереи материала     *
      // **********************************************************************
      function SaveStuff(Uid)
      {
         alert('SaveStuff(Uid) 101');
         iuy=$('#KwinGallery').html();
         alert(iuy);
         /*
         pathphp="getNameCue.php";
         // Делаем запрос на определение наименования раздела материалов
         $.ajax({
            url: pathphp,
            type: 'POST',
            data: {idCue:Uid, pathTools:pathPhpTools, pathPrown:pathPhpPrown},
            // Выводим ошибки при выполнении запроса в PHP-сценарии
            error: function (jqXHR,exception) {SmarttodoError(jqXHR,exception)},
            // Обрабатываем ответное сообщение
            success: function(message)
            {
               // Вырезаем из запроса чистое сообщение
               messa=FreshLabel(message);
               // Получаем параметры ответа
               parm=JSON.parse(messa);
               $('#Message').html(parm.NameGru+': Указать название и дату для новой статьи');
               $('#nsCue').attr('value',Uid);
               $('#nsGru').attr('value',parm.NameGru);
            }
         });
         */
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
      CompareCopyRoot('sampo.jpg',$this->classdir,$this->gallidir);
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
         $this->GViewImage($pref.$aGallery[$i]["FileName"],$aGallery[$i]["Comment"]);
         if ($GalleryMode=mwgEditing) 
         {
            if ($i==0) $this->GLoadImage($this->gallidir.'/'.'sampo.jpg');
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
   
   protected function GViewImage($FileName,$Comment,$Action='Image')
   {
      /*
      echo 
         '<div class="Card">'.
         '<button class="bCard" type="submit" name="'.$Action.'" value="'.$FileName.'">'.
         '<img class="imgCard" src="'.$FileName.'" alt="'.$FileName.'">'.
         '</button>';
      */
      echo 
         '<div class="Card">'.
         '<button class="bCard" type="submit" name="'.$Action.'">'.
         '<img class="imgCard" src="'.$FileName.'">'.
         '</button>';
      echo '<p class="pCard">'.$Comment.'</p>';
      echo 
         '</div>';
   }
   
   protected function GLoadImage($FileName)
   {
      /**
       * Размещаем в форме поле для загрузки файла, а перед ним (иначе не будет
       * работать) поле для контроля размера загружаемого файла. 
       * Преимущество скрытого поля с именем MAX_FILE_SIZE в том, что PHP остановит
       * процесс загрузки файла при превышении размера
       * 
       * При нажатии на кнопку 'submit' запрос страницы с четырьмя параметрами:
       * http: ... .php ?MAX_FILE_SIZE=xx1 &IMG=aa2.jpg &AREAM=aa3 &SUBMI=aa4
      **/
      /*
      echo '
      <div class="Card">
      <form method="get" enctype="multipart/form-data">
      <input type="hidden"     name="MAX_FILE_SIZE" id="inhCard" value="1600000">
      <input type="file"       name="IMG"           id="infCard"
         accept="image/jpeg,image/png,image/gif" 
         onchange="alf2LoadFile(this);">
      <img id="imgCardi" src="'.$FileName.'" alt="FileName">
      <textarea class="taCard" name="AREAM">Текст комментария к картинке</textarea>
      <input type="submit"     name="SUBMI"     id="insCard" value="Загрузить">
      </form>
      </div>
      ';
      */
      echo '
      <div class="Card">
      <form method="get" enctype="multipart/form-data">
      <input type="hidden"     name="MAX_FILE_SIZE" id="inhCard" value="1600000">
      <input type="file"       name="IMG"           id="infCard"
         accept="image/jpeg,image/png,image/gif" 
         onchange="readFile(this);">
      <img id="imgCardi" src="'.$FileName.'" alt="FileName">
      <textarea class="taCard" name="AREAM">Текст комментария</textarea>
      <input type="submit"     name="SUBMI"     id="insCard" value="Загрузить">
      </form>
      </div>
      ';
   }

   
   // *************************************************************************
   // *     Сформировать определяющий массив для базы данных редактируемого   *
   // *     материала по образцу (выбирая данные из базы данных материалов):  *
   //  
   // $aCharters=[                                                          
   //   [ 1, 0,-1,'ittve.me',        '/',                acsAll,'20',''],
   //   [16, 1,-1,'Прогулки',        'progulki',         acsAll,'20',''],
   //   [17,16, 0,'Охота на медведя','ohota-na-medvedya',acsAll,'2011.05.06',''],
   //   [21, 0,-1,'ittve.end',       '/',                acsAll,'20','']
   // ]; 
   //      
   // *************************************************************************
   protected function MakeaCharters($apdo)
   {
      $t1=SelRecord($apdo,$this->pid);
      $t2=SelRecord($apdo,$this->uid);
      /*
      echo '<pre>';
      print_r($t1);
      echo '</pre>';
      */
      $aCharters=[                                                          
         [            1,             0,              -1,        'ittve.me',                '/', acsAll,             '20',''],
         [$t1[0]['uid'], $t1[0]['pid'], $t1[0]['IdCue'], $t1[0]['NameArt'], $t1[0]['Translit'], acsAll,$t1[0]['DateArt'],''],
         [$t2[0]['uid'], $t2[0]['pid'], $t2[0]['IdCue'], $t2[0]['NameArt'], $t2[0]['Translit'], acsAll,$t2[0]['DateArt'],''],
         [           21,             0,              -1,       'ittve.end',                '/', acsAll,             '20','']
      ];       
      return $aCharters;
   }
   // *************************************************************************
   // *         Развернуть изображения галереи и обеспечить их ведение        *
   // *     $GalleryMode - режим вывода галереи: mwgViewing или mwgEditing    *
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
} 

// *************************************************** KwinGalleryClass.php ***
