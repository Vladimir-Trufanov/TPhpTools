<?php namespace ttools; 
                                         
// PHP7/HTML5, EDGE/CHROME                         *** TinyGalleryClass.php ***

// ****************************************************************************
// * TPhpTools                Фрэйм области редактирования текущего материала *
// *                 и галереи изображений, связанной с этим материалом (uid) *
// *                                    из выбранной (указанной) группы (pid) *
// *                                                                          *
// * v2.0, 10.02.2022                              Автор:       Труфанов В.Е. *
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
 * stylesdir    - каталог стилей элементов разметки и фонтов
 * jsxdir       - каталог размещения файлов javascript
 * 
 * nym          - префикс имен файлов для фотографий галереи и материалов
 * 
 * Пример создания объекта класса и порядок работы с ним:
 * 
 * // Указываем место размещения библиотеки прикладных функций TPhpPrown
 * define ("pathPhpPrown",$SiteHost.'/TPhpPrown/TPhpPrown');
 * // Указываем место размещения библиотеки прикладных классов TPhpTools
 * define ("pathPhpTools",$SiteHost.'/TPhpTools/TPhpTools');
 * // Указываем тип базы данных (по сайту) для управления классом ArticlesMaker
 * define ("articleSite",'IttveMe'); 
 * // Указываем каталоги размещения файлов
 * define("editdir",'ittveEdit');  // файлы, связанные c материалом
 * define("stylesdir",'Styles');   // стили элементов разметки и фонты
 * define("jsxdir",'Jsx');         // файлы javascript
 * // Указываем префикс имен файлов для фотографий галереи и материалов
 * define('nym','ittve');
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
 *    $WorkTinyHeight,$FooterTinyHeight,$KwinGalleryWidth,$EdIzm,$Arti);
 * 
 * echo '<head>';
 *    // Подключаем jquery и jquery-ui скрипты 
 *    echo ' 
 *       <script src="jquery-1.11.1.min.js"></script>
 *       <script src="jquery-ui.min.js"></script>
 *    ';
 *    // Подключаем стили для редактирования материалов
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

require_once pathPhpTools."/TMenuLeader/MenuLeaderClass.php";
require_once pathPhpTools."/CommonTools.php";

define ("classdir", pathPhpTools.'/TTinyGallery'); // путь к каталогу файлов класса

define ('mmlVernutsyaNaGlavnuyu', 'vernutsya-na-glavnuyu-stranicu');
define ('mmlVybratStatyuRedakti', 'vybrat-statyu-dlya-redaktirovaniya');
define ('mmlNaznachitStatyu',     'naznachit-statyu');
define ('mmlUdalitMaterial',      'udalit-material');

define ("ttMessage", 1);  // вывести информационное сообщение
define ("ttError",   2);  // вывести сообщение об ошибке
 
define ("NoDefine", -1);  // Признак того, что группа материалов не выбрана

class TinyGallery
{
   // ----------------------------------------------------- СВОЙСТВА КЛАССА ---
   protected $SiteRoot;         // Корневой каталог сайта
   protected $urlHome;          // Начальная страница сайта
   protected $editdir;          // Каталог размещения файлов, связанных c материалом

   protected $WorkTinyHeight;   // Высота рабочей области Tiny
   protected $FooterTinyHeight; // Высота подвала области редактирования
   protected $KwinGalleryWidth; // Ширина галереи изображений
   protected $EdIzm;            // Единица измерения заданных параметров
   
   protected $Arti;             // Объект по работе с базой данных материалов
   protected $contents;         // Текущий материал
   protected $NameGru;          // Заголовок текущей группы материалов
   protected $NameArt;          // Заголовок текущего материала
   protected $DateArt;          // Дата текущего материала
   protected $fileStyle;        // Файл стилей элементов класса
   protected $apdo;             // Подключение к базе данных материалов
   protected $menu;             // Управляющее меню в подвале
   
   protected $Galli;            // Объект управления изображениями в галерее
   protected $pidEdit;          // Идентификатор группы материалов в базе данных
   protected $uidEdit;          // Идентификатор материала
   
   protected $DelayedMessage;   // Отложенное сообщение

   // ------------------------------------------------------- МЕТОДЫ КЛАССА ---
   public function __construct($SiteRoot,$urlHome,
      $WorkTinyHeight,$FooterTinyHeight,$KwinGalleryWidth,$EdIzm,$Arti) 
   {
      // Инициализируем свойства класса
      $this->SiteRoot=$SiteRoot; 
      $this->urlHome=$urlHome; 
      $this->editdir=editdir; 
      $this->pidEdit=-1; 
      $this->uidEdit=-1; 
      // Регистрируем объект по работе с базой данных материалов
      $this->Arti=$Arti;
      // Считываем предопределенные размеры частей рабочей области редактирования
      $this->WorkTinyHeight=$WorkTinyHeight;
      $this->FooterTinyHeight=$FooterTinyHeight;
      $this->KwinGalleryWidth=$KwinGalleryWidth;
      $this->EdIzm=$EdIzm;
      // Подключаемся к базе данных материалов
      $this->apdo=$this->Arti->BaseConnect();
      // Инициируем отложенное сообщение, то есть сообщение, которое может быть
      // выведено на фазе BODY процесса построения страницы сайта 
      $this->DelayedMessage=imok;
      // Выполняем действия на странице до отправления заголовков страницы: 
      // (устанавливаем кукисы и т.д.)                  
      $this->ZeroEditSpace();
      // Трассируем установленные свойства
      //\prown\ConsoleLog('$this->WorkTinyHeight='.$this->WorkTinyHeight); 
   }
   public function __destruct() 
   {
   }
      
   // ------------------------------------------------------------------------------------------------ ZERO ---

   // *************************************************************************
   // *   Выполнить действия на странице до отправления заголовков страницы:  *
   // *                         (установить кукисы и т.д.)                    *
   // *************************************************************************
   private function ZeroEditSpace()

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

   {
      // Проверяем, нужно ли заменить файл стилей в каталоге редактирования и,
      // (при его отсутствии, при несовпадении размеров или старой дате) 
      // загружаем из класса 
      CompareCopyRoot('CommonTools.js',pathPhpTools,jsxdir);
      CompareCopyRoot('WorkTiny.css',classdir,stylesdir);
      CompareCopyRoot('WorkTiny.js',classdir,jsxdir);
      // Если выбран материал (транслит) для редактирования, то готовим 
      // установку кукиса на данный материал. Материал мог быть выбран при 
      // выполнении методов:
      //    $apdo=$this->Arti->BaseConnect();
      //    $this->Arti->GetPunktMenu($apdo);
      $getArti=\prown\getComRequest('arti'); // выбрали транслит 
      // Если было назначение нового материала/статьи, 
      // то делаем запись в базу данных и готовим транслит статьи для установки
      // кукиса и начала редактирования материала
      if ((\prown\getComRequest('nsnCue')<>NULL)&&
      (\prown\getComRequest('nsnName')<>NULL)&&
      (\prown\getComRequest('nsnGru')<>NoDefine)&&
      (\prown\getComRequest('nsnDate')<>NULL))
      {
         // Делаем новую запись в базе данных
         $apdo=$this->Arti->BaseConnect();
         $NameArt=\prown\getComRequest('nsnName');
         $DateArt=\prown\getComRequest('nsnDate');
         $pid=\prown\getComRequest('nsnCue');
         $Translit=\prown\getTranslit($NameArt);
         $NameGru=\prown\getComRequest('nsnGru');
         $contents='Новый материал';
         $this->Arti->InsertByTranslit($apdo,$Translit,$pid,$NameArt,$DateArt,$contents);
         // Готовим кукис текущего материала
         $getArti=$Translit;
      }
      // Если был выбран режим сохранения отредактированного материала, 
      // то сохраняем его    
      $contentNews=\prown\getComRequest('mytextarea');
      if ($contentNews<>NULL)
      {
         $this->Arti->UpdateByTranslit($this->apdo,$this->Arti->getArti,$contentNews);
      }
      // Устанавливаем кукис на новый или выбранный материал
      if ($getArti<>NULL)
      {
         $this->Arti->cookieGetPunktMenu($getArti); 
      }
      // Вытаскиваем материал для редактирования
      $this->DelayedMessage=$this->Arti->SelUidPid
         ($this->apdo,$this->Arti->getArti,$pidEdit,$uidEdit,$NameGru,$NameArt,$DateArt,$contentsIn);
      // Запоминаем в объекте текущий материал
      $this->contents=html_entity_decode($contentsIn);
      $this->NameGru=$NameGru;
      $this->NameArt=$NameArt;
      $this->DateArt=$DateArt;
      $this->pidEdit=$pidEdit; 
      $this->uidEdit=$uidEdit; 
      // Если нет отложенного сообщения, то создаем галерею 
      // и выбираем другое возможное отложенное сообщение 
      if ($this->DelayedMessage==imok)
      {
         // Cоздаем объект для управления изображениями в галерее, связанной с 
         // материалами сайта из базы данных
         $this->Galli=new KwinGallery(editdir,nym,$pidEdit,$uidEdit,$this->SiteRoot,$this->urlHome,$this->Arti);
         $this->DelayedMessage=$this->Galli->getDelayedMessage();
      }
      // Подключаем управляющее меню в подвале
      $this->menu=new MenuLeader(kwintiny,$this->urlHome);
   }
   
   // --------------------------------------------------------------------------------------- HEAD and LAST ---

   // *************************************************************************
   // *        Установить стили пространства редактирования материала         *
   // *************************************************************************
   public function IniEditSpace()
   {
      // Настраиваемся на файлы стилей и js-скрипты
      $this->Arti->IniEditSpace();
      // <script src="/Jsx/CommonTools.js"></script>
      echo '<script src="/'.jsxdir.'/CommonTools.js"></script>';
      // <script src="/Jsx/WorkTiny.js"></script>
      echo '<script src="/'.jsxdir.'/WorkTiny.js"></script>';
      // <link rel="stylesheet" type="text/css" href="/Styles/WorkTiny.css">
      echo '<link rel="stylesheet" type="text/css" href="/'.stylesdir.'/WorkTiny.css">';
      $this->Galli->Head();
      
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
      // Если было назначение статьи без указания выбранного раздела, 
      // то инициализируем страницу "Назначить статью"
      if ((\prown\getComRequest('nsnCue')==-1)&&
      (\prown\getComRequest('nsnName')<>NULL)&&
      (\prown\getComRequest('nsnDate')<>NULL))
      {
         $this->IniEditSpace_mmlNaznachitStatyu();
      }
      else if (\prown\isComRequest(mmlVybratStatyuRedakti))
      {
         $this->IniEditSpace_mmlVybratStatyuRedakti();
      }
      else if (\prown\isComRequest(mmlNaznachitStatyu))
      {
         $this->IniEditSpace_mmlNaznachitStatyu();
      }
      else if (\prown\isComRequest(mmlUdalitMaterial))
      {
         $this->IniEditSpace_mmlUdalitMaterial();
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
   // Включаем в разметку див галереи изображений 
   echo '<div id="KwinGallery">'; 
      if (\prown\isComRequest(mmlVybratStatyuRedakti))
      {
         $this->KwinGallery_mmlVybratStatyuRedakti();
      }
      else if (\prown\isComRequest(mmlNaznachitStatyu))
      {
         $this->KwinGallery_mmlNaznachitStatyu('');
      }
      else if (\prown\isComRequest(mmlUdalitMaterial))
      {
         $this->KwinGallery_mmlUdalitMaterial();
      }
      // В обычном режиме
      else
      {
         $this->KwinGallery_main($this->pidEdit,$this->uidEdit);
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
      // Если было назначение статьи без указания выбранного раздела, 
      // то перезапускаем страницу "Назначить статью"
      if ((\prown\getComRequest('nsnCue')==-1)&&
      (\prown\getComRequest('nsnName')<>NULL)&&
      (\prown\getComRequest('nsnDate')<>NULL))
      {
         $this->WorkTiny_mmlNaznachitStatyu('Указать группу материалов для новой статьи');
      }
      else if (\prown\isComRequest(mmlVybratStatyuRedakti))
      {
         $this->WorkTiny_mmlVybratStatyuRedakti();
      }
      else if (\prown\isComRequest(mmlNaznachitStatyu))
      {
         $this->WorkTiny_mmlNaznachitStatyu('Указать название, дату и группу материалов для новой статьи');
      }
      else if (\prown\isComRequest(mmlUdalitMaterial))
      {
         $this->WorkTiny_mmlUdalitMaterial();
      }
      // В обычном режиме
      else
      {
         $this->WorkTiny_main();
      }
   echo '</div>';
   
   // Обустраиваем подвал области редактирования
   echo '<div id="FooterTiny">';
      // Подключаем управляющее меню в подвале
      $this->menu->Menu(); 
   echo '</div>';
   }

   // --------------------------------------------------- ВНУТРЕННИЕ МЕТОДЫ ---
   
   // *************************************************************************
   // *                         Вывести заголовок статьи                      *
   // *************************************************************************
   private function MakeTitle($NameGru,$NameArt,$DateArt='')
   {
      if ($NameArt==ttMessage) echo '<div id="Message">'.$NameGru.'</div>'; 
      else if ($NameArt==ttError) echo '<div id="NameError">'.$NameGru.'</div>'; 
      else
      {
         echo '<div id="TopLine">'; 
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
         echo '</div>'; 
      }
   }
   // *************************************************************************
   // *    Выполнить действия на странице в обычном режиме редактирования     *
   // *************************************************************************
   private function IniEditSpace_main()
   {
      // Готовим рабочую область Tiny <!-- theme: "modern", -->
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
      
      echo '
      <script>
      </script>
      ';
      // Включаем рождественскую версию шрифтов и полосок меню
      $this->IniFontChristmas();
   }
   private function IniEditSpace_mmlVernutsyaNaGlavnuyu()
   {
      \prown\ConsoleLog('IniEditSpace_mmlVernutsyaNaGlavnuyu'); 
   }
   private function IniEditSpace_mmlUdalitMaterial()
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
      
      echo '
      <script>
      </script>
      ';
      // Включаем рождественскую версию шрифтов и полосок меню
      $this->IniFontChristmas();
   }
   private function IniEditSpace_mmlVybratStatyuRedakti()
   {
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
   // *     Открыть рабочую область и обеспечить редактирование материала     *
   // *************************************************************************
   private function WorkTiny_main()
   {
      //phpinfo();

      // Выводим заголовок статьи
      if ($this->DelayedMessage==imok)
         $this->MakeTitle($this->NameGru,$this->NameArt,$this->DateArt);
      else 
         $this->MakeTitle($this->DelayedMessage,ttError);
      $SaveAction=$_SERVER["SCRIPT_NAME"];
      echo '
         <form id="frmTinyText" method="post" action="'.$SaveAction.'">
         <textarea id="mytextarea" name="mytextarea">
      '; 
      if ($this->contents<>NULL)
      {
         echo $this->contents;
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
   // *************************************************************************
   // *                       Выполнить действия при назначении новой статьи: *
   // * на первой странице,  когда требуется выбрать группу статей, к которой *
   // * будет относиться новая статья;                                        *
   // * на второй странице, когда задаются название и дата статьи             *
   // *************************************************************************
   private function WorkTiny_mmlNaznachitStatyu($messa)
   {
   
      if (\prown\getComRequest('nsnGru')==NoDefine)
      {
         ?> <script> 
            $(document).ready(function() {Error_Info('Группа материалов не назначена!');})
         </script> <?php
      }
      // Проверяем и учитываем уже выбранные данные
      if (\prown\getComRequest('nsnName')==NULL) $nsnName='';
      else $nsnName='value="'.\prown\getComRequest('nsnName').'"';
      if (\prown\getComRequest('nsnDate')==NULL) $nsnDate='';
      else $nsnDate='value="'.\prown\getComRequest('nsnDate').'"';
      // Выводим заголовочное сообщение
      $this->MakeTitle($messa,ttMessage);
      // Выбираем название и дату новой статьи
      $SaveAction=$_SERVER["SCRIPT_NAME"];
      echo '
         <div id="nsGroup">
         <form id="frmNaznachitStatyu" method="post" action="'.$SaveAction.'">
      ';
      echo '
         <input id="nsName" type="text" name="nsnName" '.$nsnName.' placeholder="Название нового материала" required>
         <input id="nsDate" type="date" name="nsnDate" '.$nsnDate.' required>
         <input id="nsCue"  type="hidden" name="nsnCue" value="'.NoDefine.'">
         <input id="nsGru"  type="hidden" name="nsnGru" value="'.NoDefine.'">
      ';
      echo '
         </form>
         </div>
      ';
      // Выбираем группу материалов для которой создается новая статья
      echo '<div id="AddArticle">';
         $this->Arti->MakeUniMenu($this->apdo,'SelMatiSection');
      echo '</div>';
   }
   private function WorkTiny_mmlVernutsyaNaGlavnuyu()
   {
      echo 'WorkTiny_mmlVernutsyaNaGlavnuyu<br>';
   }
   private function WorkTiny_mmlUdalitMaterial()
   {
      // Выводим заголовочное сообщение
      $this->MakeTitle('Выбрать материал для удаления',ttMessage);
      // Строим меню
      $this->Arti->MakeUniMenu($this->apdo,'','UdalitMater');
   }
   // *************************************************************************
   // *                     Выбрать статью для редактирования                 *
   // *             (дополнительно проверить целостность базы данных)         *
   // *************************************************************************
   private function WorkTiny_mmlVybratStatyuRedakti()
   {
      // Выводим заголовочное сообщение
      $this->MakeTitle('Выбрать статью для редактирования',ttMessage);
      // Выбираем статью для редактирования и, дополнительно,
      // проверяем целостность базы данных
      $this->Arti->getPunktMenu($this->apdo); 
   }
   // *************************************************************************
   // *        Обеспечить просмотр и редактирование фотографий в галерее      *
   // *************************************************************************
   private function KwinGallery_main($pidEdit,$uidEdit)
   {
      // Если нет отложенного сообщения, то показываем галерею изображений
      if ($this->DelayedMessage==imok)
      {
         //$this->Galli->ViewGalleryAsArray();
         //$this->Galli->ViewGallery(NULL,mwgEditing);
         $this->DelayedMessage=$this->Galli->BaseGallery(mwgEditing);
      }
   }
   private function KwinGallery_mmlNaznachitStatyu()
   {
      echo 'KwinGallery_mmlNaznachitStatyu<br>';
   }
   private function KwinGallery_mmlUdalitMaterial()
   {
      echo 'KwinGallery_mmlUdalitMaterial<br>';
   }
   private function KwinGallery_mmlVernutsyaNaGlavnuyu()
   {
      echo 'KwinGallery_mmlVernutsyaNaGlavnuyu<br>';
   }
   private function KwinGallery_mmlVybratStatyuRedakti()
   {
      echo 'KwinGallery_mmlVybratStatyuRedakti<br>';
   }

} 
// *************************************************** TinyGalleryClass.php ***
