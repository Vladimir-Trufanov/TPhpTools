<?php namespace ttools; 
                                         
// PHP7/HTML5, EDGE/CHROME                         *** TinyGalleryClass.php ***

// ****************************************************************************
// * TPhpTools                Фрэйм области редактирования текущего материала *
// *                 и галереи изображений, связанной с этим материалом (uid) *
// *                                    из выбранной (указанной) группы (pid) *
// *                                                                          *
// * v2.0, 02.01.2022                              Автор:       Труфанов В.Е. *
// * Copyright © 2022 tve                          Дата создания:  18.12.2019 *
// ****************************************************************************

/**
 * Для взаимодействия с объектами класса должны быть определены переменные
 * и константы:
 *    
 * $SiteRoot    - корневой каталог сайта (в файловой системе сервера)
 * $urlHome     - начальная страница сайта
 *
 * pathPhpTools - путь к каталогу с файлами библиотеки прикладных классов;
 * pathPhpPrown - путь к каталогу с файлами библиотеки прикладных функции
 * articleSite  - тип базы данных (по сайту)
 * editdir      - каталог размещения файлов, связанных c материалом
 * 
 * Пример создания объекта класса и порядок работы с ним:
 * 
 * // Указываем место размещения библиотеки прикладных функций TPhpPrown
 * define ("pathPhpPrown",$SiteHost.'/TPhpPrown/TPhpPrown');
 * // Указываем место размещения библиотеки прикладных классов TPhpTools
 * define ("pathPhpTools",$SiteHost.'/TPhpTools/TPhpTools');
 * // Указываем тип базы данных (по сайту) для управления классом ArticlesMaker
 * define ("articleSite",'IttveMe'); 
 * // Указываем каталог размещения файлов, связанных c материалом
 * define("editdir",'ittveEdit');
 * 
 * // Подгружаем нужные модули библиотеки прикладных классов
 * require_once pathPhpTools."/TArticlesMaker/ArticlesMakerClass.php";
 * require_once pathPhpTools."/TTinyGallery/TinyGalleryClass.php";
 * // Подключаем объект для работы с базой данных материалов
 * // (при необходимости создаем базу данных материалов)
 * $basename=$_SERVER['DOCUMENT_ROOT'].'/ittve'; $username='tve'; $password='23ety17';
 * $Arti=new ttools\ArticlesMaker($basename,$username,$password);
 * if (!file_exists($basename.'.db3')) $Arti-> BaseFirstCreate();
 * 
 * // Подключаем объект по редактированию материала - для работы в галерее 
 * // и рабочей области редактирования
 * $WorkTinyHeight='60'; $FooterTinyHeight='35'; $KwinGalleryWidth='30'; $EdIzm='%';
 * $Edit=new ttools\TinyGallery($SiteRoot,$urlHome,
 * $WorkTinyHeight,$FooterTinyHeight,$KwinGalleryWidth,$EdIzm,$Arti);
 * 
 * // Подключаем стили для редактирования материалов
 * echo '<head>';
 *    require_once pathPhpTools."/TArticlesMaker/MenuArticlesMe.php";
 *    $Edit->IniEditSpace();
 * echo '</head>';
 * // Разворачиваем область для редактирования материала
 * echo '
 *    <body> 
 *    <div id="Info">
 * ';
 * $Edit->OpenEditSpace();
 * echo '
 *    </div>
 *    </body>
 * ';
 * 
**/

define ('mmlVernutsyaNaGlavnuyu', 'vernutsya-na-glavnuyu-stranicu');
define ('mmlVybratStatyuRedakti', 'vybrat-statyu-dlya-redaktirovaniya');
define ('mmlNaznachitStatyu',     'naznachit-statyu');
class TinyGallery
{
   // ----------------------------------------------------- СВОЙСТВА КЛАССА ---
   protected $SiteRoot;         // Корневой каталог сайта
   protected $urlHome;          // Начальная страница сайта
   protected $editdir;          // Каталог размещения файлов, связанных c материалом
   protected $classdir;         // Каталог файлов класса
   protected $WorkTinyHeight;   // Высота рабочей области Tiny
   protected $FooterTinyHeight; // Высота подвала области редактирования
   protected $KwinGalleryWidth; // Ширина галереи изображений
   protected $EdIzm;            // Единица измерения заданных параметров
   protected $Arti;             // Объект по работе с базой данных материалов
   protected $contents;         // Текущий материал
   protected $NameGru;          // Заголовок текущей группы материалов
   protected $NameArt;          // Заголовок текущего материала
   protected $DateArt;          // Дата текущего материала
   protected $fileStyle;        // Файл стилей
   protected $apdo;             // Подключение к базе данных материалов
   //protected $nym;      // Префикс имен файлов для фотографий галереи и материалов
   //protected $pid;      // Идентификатор группы текущего материала
   //protected $uid;      // Идентификатор текущего материала

   // ------------------------------------------------------- МЕТОДЫ КЛАССА ---
   public function __construct($SiteRoot,$urlHome,
      $WorkTinyHeight,$FooterTinyHeight,$KwinGalleryWidth,$EdIzm,$Arti) 
   {
      // Инициализируем свойства класса
      $this->SiteRoot=$SiteRoot; 
      $this->urlHome=$urlHome; 
      $this->editdir=editdir; 
      // Принимаем путь к каталогу файлов класса
      $this->classdir=pathPhpTools.'/TTinyGallery';
      // Регистрируем объект по работе с базой данных материалов
      // и инициируем транслит выбранного материала
      $this->Arti=$Arti;
      // Считываем предопределенные размеры частей рабочей области редактирования
      $this->WorkTinyHeight=$WorkTinyHeight;
      $this->FooterTinyHeight=$FooterTinyHeight;
      $this->KwinGalleryWidth=$KwinGalleryWidth;
      $this->EdIzm=$EdIzm;
      // Подключаемся к базе данных материалов
      $this->apdo=$this->Arti->BaseConnect();
      // Выполняем действия на странице до отправления заголовков страницы: 
      // (устанавливаем кукисы и т.д.)                  
      $this->ZeroEditSpace();
      // Трассируем установленные свойства
      /*
      \prown\ConsoleLog('$this->editdir=' .$this->editdir); 
      \prown\ConsoleLog('$this->classdir='.$this->classdir); 
      \prown\ConsoleLog('$this->WorkTinyHeight='.$this->WorkTinyHeight); 
      \prown\ConsoleLog('$this->FooterTinyHeight='.$this->FooterTinyHeight); 
      \prown\ConsoleLog('$this->KwinGalleryWidth='.$this->KwinGalleryWidth); 
      \prown\ConsoleLog('$this->EdIzm='.$this->EdIzm); 
      */
   }
   public function __destruct() 
   {
   }
   // *************************************************************************
   // *   Выполнить действия на странице до отправления заголовков страницы:  *
   // *                         (установить кукисы и т.д.)                    *
   // *************************************************************************
   private function ZeroEditSpace()
   {
      // Если выбран новый материал, то устанавливаем кукис на данный материал
      // материал мог быть выбран при выполнении методов:
      //    $apdo=$this->Arti->BaseConnect();
      //    $this->Arti->GetPunktMenu($apdo);
      $getArti=\prown\getComRequest('arti');
      if ($getArti<>NULL)
      {
         $this->Arti->cookieGetPunktMenu($getArti); 
      }
      // Проверяем, нужно ли заменить файл стилей в каталоге редактирования и,
      // (при его отсутствии, при несовпадении размеров или старой дате) 
      // загружаем из класса 
      $fileStyle=$this->editdir.'/WorkTiny.css';
      clearstatcache($fileStyle);
      $filename=$this->classdir.'/WorkTiny.css';
      clearstatcache($filename);
      if ((!file_exists($fileStyle))||
      (filesize($filename)<>filesize($fileStyle))||
      (filemtime($filename)>filemtime($fileStyle))) 
      {
         if (!copy($filename,$fileStyle))
         \prown\Alert('Не удалось скопировать файл стилей '.$filename); 
      } 
   }
   // *************************************************************************
   // *        Установить стили пространства редактирования материала         *
   // *************************************************************************
   public function IniEditSpace()
   {
      // Настраиваемся на файлы стилей
      $this->Arti->IniEditSpace();
      $this->fileStyle=$this->editdir.'/WorkTiny.css';
      echo '<link rel="stylesheet" type="text/css" href="'.$this->fileStyle.'">';
      // Настраиваем размеры частей рабочей области редактирования
      echo '
      <style>
      #KwinGallery
      {
         width:'.$this->KwinGalleryWidth.$this->EdIzm.';'.'
         height:'.($this->WorkTinyHeight+$this->FooterTinyHeight).$this->EdIzm.';'.'
      }
      #WorkTiny,#FooterTiny
      {
         width:'.(100-$this->KwinGalleryWidth).$this->EdIzm.';'.'
      }
      #WorkTiny
      {
         height:'.$this->WorkTinyHeight.$this->EdIzm.';'.'
      }
      #FooterTiny
      {
         top:'.$this->WorkTinyHeight.$this->EdIzm.';'.'
         height:'.$this->FooterTinyHeight.$this->EdIzm.';'.'
      }
      </style>
      ';
      if (\prown\isComRequest(mmlVybratStatyuRedakti))
      {
         $this->IniEditSpace_mmlVybratStatyuRedakti();
      }
      else if (\prown\isComRequest(mmlNaznachitStatyu))
      {
         $this->IniEditSpace_mmlNaznachitStatyu();
      }
      // В обычном режиме
      else
      {
         $this->IniEditSpace_main();
      }
   }
   // *************************************************************************
   // *              Открыть пространство редактирования материала            *
   // *************************************************************************
   public function OpenEditSpace()
   {
   // id=WorkTiny ------------------------------- id=KwinGallery --------------
   // .------------------------------------------.                            .                        
   // . id=NameGru                    id=NameArt .                            .
   // .------------------------------------------.                            .
   // .id=frmTinyText ---------------------------.                            .
   // .                                          .                            .
   // . id=mytextarea                            .                            .
   // .                                          .                            .
   // .                                          .                            .
   // id=FooterTiny -----------------------------.                            .
   // . id=UlTiny                                .                            .
   // .                                          .                            .
   // -------------------------------------------------------------------------

   // Вытаскиваем материал для редактирования
   $table=$this->Arti->SelUidPid
      ($this->apdo,$this->Arti->getArti,$pidEdit,$uidEdit,$NameGru,$NameArt,$DateArt,$contents);
   // Если был выбран режим сохранения отредактированного материала, 
   // то выбираем его из запроса и сохраняем    
   $contentNews=\prown\getComRequest('mytextarea');
   if ($contentNews<>NULL)
   {
      $this->Arti->UpdateByTranslit($apdo,$this->Arti->getArti,$contentNews);
      $table=$this->Arti->SelUidPid
      ($this->apdo,$this->Arti->getArti,$pidEdit,$uidEdit,$NameGru,$NameArt,$DateArt,$contents);
   }
   // Запоминаем в объекте текущий материал
   $this->contents=$contents;
   $this->NameGru=$NameGru;
   $this->NameArt=$NameArt;
   $this->DateArt=$DateArt;
   // Включаем в разметку див галереи изображений 
   echo '<div id="KwinGallery">'; 
      if (\prown\isComRequest(mmlVybratStatyuRedakti))
      {
         $this->KwinGallery_mmlVybratStatyuRedakti();
      }
      else if (\prown\isComRequest(mmlNaznachitStatyu))
      {
         $this->KwinGallery_mmlNaznachitStatyu();
      }
      // В обычном режиме
      else
      {
         $this->KwinGallery_main();
      }
      // В обычном режиме
      //echo '$_SERVER["SCRIPT_NAME"]='.$_SERVER["SCRIPT_NAME"].'<br>';
      //echo 'KwinGallery<br>';
      /*
      //$basename=$_SERVER['DOCUMENT_ROOT'].'/ittve'; $username='tve'; $password='23ety17'; 
      //$Arti=new ArticlesMaker($basename,$username,$password);
      //$apdo=$Arti->BaseConnect();
      //$Galli->ViewGallery(gallidir,$apdo);
      // Cоздаем объект для управления изображениями в галерее, связанной с 
      // материалами сайта из базы данных
      $Galli=new ttools\KwinGallery(editdir,nym,$pid,$uid);
      $Galli->ViewGallery(editdir,$apdo);
      $pref=editdir.nym.pid.'-'.uid.'-';
      $Comment="Ночная прогулка по Ладоге до рассвета и подъёма настроения.";
      GViewImage($pref.'Подъём-настроения.jpg',$Comment);
      GLoadImage("ittveEdit/sampo.jpg");
      $Comment="На горе Сампо всем хорошо!";
      GViewImage($pref.'На-Сампо.jpg',$Comment);
      */   
   echo '</div>'; 
   // Включаем в разметку рабочую область редактирования
   echo '<div id="WorkTiny">';
      if (\prown\isComRequest(mmlVybratStatyuRedakti))
      {
         $this->WorkTiny_mmlVybratStatyuRedakti();
      }
      else if (\prown\isComRequest(mmlNaznachitStatyu))
      {
         $this->WorkTiny_mmlNaznachitStatyu();
      }
      // В обычном режиме
      else
      {
         $this->WorkTiny_main();
      }
   echo '</div>';
   
   // Обустраиваем подвал области редактирования
   echo '<div id="FooterTiny">';
      // Формируем префикс вызова страниц из меню на сайте и localhost
      $cPref='?Com=';
      // Выводим управляющееменю
      echo '
         <ul class="uli">
         <li class="ili"><a class="ali" href="'.$cPref.mmlVernutsyaNaGlavnuyu.'">На главную</a></li>
         <li class="ili"><a class="ali" href="'.$cPref.mmlNaznachitStatyu.    '">Назначить статью</a></li>
         <li class="ili"><a class="ali" href="'.$cPref.mmlVybratStatyuRedakti.'">Выбрать материал</a></li>
         <li class="ili">'.'<input type="submit" value="Сохранить материал" form="frmTinyText">'.'</li>
         </ul>   
      ';
   echo '</div>';
   // <li class="ili">'.'<input type="submit" name="enter" value="Сохранить материал" form="frmTinyText">'.'</li>
   }

   // --------------------------------------------------- ВНУТРЕННИЕ МЕТОДЫ ---
   
   // *************************************************************************
   // *                         Вывести заголовок статьи                      *
   // *************************************************************************
   private function MakeTitle($NameGru,$NameArt,$DateArt)
   {
      if ($NameArt=='')
      { 
         echo '<div id="NameGru">'.$NameGru.'</div>'; 
         echo '<div id="NameArt">'.'</div>'; 
      }
      else
      {
         echo '<div id="NameGru">'.$NameGru.':'.'</div>'; 
         echo '<div id="NameArt">'.$NameArt.' ['.$DateArt.']'.'</div>'; 
      } 
   }
   // *************************************************************************
   // *   ----Выполнить действия на странице до отправления заголовков страницы:  *
   // *   ----                      (установить кукисы и т.д.)                    *
   // *************************************************************************
   private function IniEditSpace_main()
   {
      // Готовим рабочую область Tiny <!-- theme: "modern", -->
      //echo '<link rel="stylesheet" type="text/css" href="Styles/Gallery.css">';
      //echo '<link rel="stylesheet" type="text/css" href="Styles/">';
      // Подключаем TinyMCE
      echo '
         <script src="/TinyMCE5-8-1/tinymce.min.js"></script>
         <script> tinymce.init
         ({
            selector: "#mytextarea",'.
            //setup: function(editor) 
            //{
            //   editor.on("init", function(e) 
            //   {
            //      console.log("The Editor has initialized.");
            //   });
            //},'.
            //height: 180,'.
            //width:  780,'.
            'content_css: "'.$this->fileStyle.'",'.
            'plugins:
            [ 
               "advlist autolink link image imagetools lists charmap print preview hr anchor",
               "pagebreak spellchecker searchreplace wordcount visualblocks",
               "visualchars code fullscreen insertdatetime media nonbreaking",'.
               // "contextmenu",  // отключено для TinyMCE5-8-1
               // "textcolor",    // отключено для TinyMCE5-8-1'.
               '"save table directionality emoticons template paste"
            ],
        
            language: "ru",
            toolbar:
            [
               "| link image | forecolor backcolor emoticons"
            ],'.
            //
            // toolbar:
            // [
            //    "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons"
            // ],.
            'image_list: [
               {title: "My image 1", value: "ittveEdit/proba.jpg"},
               {title: "My image 2", value: "http://www.moxiecode.com/my2.gif"}
            ],
            a_plugin_option: true,
            a_configuration_option: 400
         });
         </script>
      ';
   }
   private function IniEditSpace_mmlNaznachitStatyu()
   {
      // Отключаем разворачивание аккордеона
      // в случае, когда создаем заголовок новой статьи. 
      echo '
      <style>
      .accordion li .sub-menu 
      {
         height:100%;
      }
      </style>
      ';
      // Включаем рождественскую версию шрифтов и полосок меню
      $this->IniFontChristmas();
   }
   private function IniEditSpace_mmlVernutsyaNaGlavnuyu()
   {
      \prown\ConsoleLog('IniEditSpace_mmlVernutsyaNaGlavnuyu'); 
   }
   private function IniEditSpace_mmlVybratStatyuRedakti()
   {
      \prown\ConsoleLog('IniEditSpace_mmlVybratStatyuRedakti'); 
      // Включаем рождественскую версию шрифтов и полосок меню
      $this->IniFontChristmas();
   }
   // *************************************************************************
   // *    Настроить размеры шрифтов и полосок меню (рождественская версия)   *
   // *************************************************************************
   private function IniFontChristmas()
   {
      echo '
      <style>
      .accordion li > a, 
      .accordion li > i 
      {
         font:bold .9rem/1.8rem Arial, sans-serif;
         padding:0 1rem 0 1rem;
         height:2rem;
      }
      .accordion li > a span, 
      .accordion li > i span 
      {
         font:normal bold .8rem/1.2rem Arial, sans-serif;
         top:.4rem;
         right:0;
         padding:0 .6rem;
         margin-right:.6rem;
      }
      </style>
      ';
   }

   // *************************************************************************
   // *   ----Выполнить действия на странице до отправления заголовков страницы:  *
   // *   ----                      (установить кукисы и т.д.)                    *
   // *************************************************************************
   private function KwinGallery_main()
   {
      echo 'KwinGallery_main<br>';
   }
   private function KwinGallery_mmlNaznachitStatyu()
   {
      echo 'KwinGallery_mmlNaznachitStatyu<br>';
   }
   private function KwinGallery_mmlVernutsyaNaGlavnuyu()
   {
      echo 'KwinGallery_mmlVernutsyaNaGlavnuyu<br>';
   }
   private function KwinGallery_mmlVybratStatyuRedakti()
   {
      echo 'KwinGallery_mmlVybratStatyuRedakti<br>';
   }
   // *************************************************************************
   // *   ----Выполнить действия на странице до отправления заголовков страницы:  *
   // *   ----                      (установить кукисы и т.д.)                    *
   // *************************************************************************
   private function WorkTiny_main()
   {
      // Выводим заголовок статьи
      $this->MakeTitle($this->NameGru,$this->NameArt,$this->DateArt);
      $SaveAction=$_SERVER["SCRIPT_NAME"];
      echo '
         <form id="frmTinyText" method="post" action="'.$SaveAction.'">
         <textarea id="mytextarea" name="mytextarea">
      '; 
      if ($this->contents<>NULL)
      {
         echo htmlspecialchars($this->contents);
      }
      else
      {
         echo '';
      }
      echo '
         </textarea>
         </form>
      '; 
   }
   private function WorkTiny_mmlNaznachitStatyu()
   {
      $this->Arti->MakeTitlesArt($this->apdo);
      //$this->Arti->ShowProbaMenu(); 
   }
   private function WorkTiny_mmlVernutsyaNaGlavnuyu()
   {
      echo 'WorkTiny_mmlVernutsyaNaGlavnuyu<br>';
   }
   private function WorkTiny_mmlVybratStatyuRedakti()
   {
      $this->Arti->GetPunktMenu($this->apdo); 
      //$this->Arti->ShowSampleMenu(); 
   }
} 

// *************************************************** TinyGalleryClass.php ***
