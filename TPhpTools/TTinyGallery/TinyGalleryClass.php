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
 * define("editdir",'ittveEdit/');
 * 
 * --// Cоздаем объект для управления изображениями в галерее, связанной с 
 * --// материалами сайта из базы данных
 * -----$Galli=new ttools\KwinGallery(gallidir,$nym,$pid,$uid);
 * 
 * 
 * 
 * --// Готовим объект для работы с изображениями, где $SiteRoot - корневой каталог
 * --// сайта, $urlHome - начальная страница сайта
 * --require_once $SiteRoot."/_ImgAjaxSqlite/TImgAjaxSqlite/ImgAjaxSqliteClass.php";
 * --$Imgaj=new ImgAjaxSqlite($urlHome."/_ImgAjaxSqlite/TImgAjaxSqlite");
 * --// При необходимости создаем базу данных для изображений
 * --$imfilename=$Imgaj->imbasename.'.db3';
 * --if (!file_exists($imfilename)) $Imgaj->BaseFirstCreate();
 * --// Подключаемся к базе данных
 * --$impdo=$Imgaj->BaseConnect();
 * --// Подключаем шрифты и стили документа
 * --echo '<head>';
 * --$Imgaj->iniStyles(); 
 * --echo '</head>';
 * --// Готовим и отрабатываем форму по загрузке изображений
 * --echo '<body>'; 
 * --$Imgaj->SelImagesSendProcess();
 * --echo '</body>'; 
 * 
**/

// Свойства:
//
// --- $FltLead - команда управления передачей данных. По умолчанию fltNotTransmit,
// ---           то есть данные о загрузке не передаются для контроля ни в кукисы, 
// ---ни в консоль, а только записываются в LocalStorage. Если LocalStorage,
// ---браузером не поддерживается, то данные будут записываться в кукисы при 
// ---установке свойства $FltLead в значение fltSendCookies или fltAll 
// ---$Page - название страницы сайта;
// ---$Uagent - браузер пользователя;

// ---Подгружаем нужные модули библиотеки прикладных функций
// ---require_once pathPhpPrown."/CommonPrown.php";
// ---Подгружаем нужные модули библиотеки прикладных классов
// ---require_once pathPhpTools."/TArticlesMaker/ArticlesMakerClass.php";

class TinyGallery
{
   // ----------------------------------------------------- СВОЙСТВА КЛАССА ---
   protected $editdir;          // Каталог размещения файлов, связанных c материалом
   protected $classdir;         // Каталог файлов класса
   protected $WorkTinyHeight;   // Высота рабочей области Tiny
   protected $FooterTinyHeight; // Высота подвала области редактирования
   protected $KwinGalleryWidth; // Ширина галереи изображений
   protected $EdIzm;            // Единица измерения заданных параметров
   //protected $nym;      // Префикс имен файлов для фотографий галереи и материалов
   //protected $pid;      // Идентификатор группы текущего материала
   //protected $uid;      // Идентификатор текущего материала

   // ------------------------------------------------------- МЕТОДЫ КЛАССА ---
   public function __construct($SiteRoot,$urlHome,
      $WorkTinyHeight,$FooterTinyHeight,$KwinGalleryWidth,$EdIzm) 
   {
      // Инициализируем свойства класса
      $this->editdir=editdir; 
      // Принимаем путь к каталогу файлов класса
      $this->classdir=pathPhpTools.'/TTinyGallery';
      // Считываем предопределенные размеры частей рабочей области редактирования
      $this->WorkTinyHeight=$WorkTinyHeight;
      $this->FooterTinyHeight=$FooterTinyHeight;
      $this->KwinGalleryWidth=$KwinGalleryWidth;
      $this->EdIzm=$EdIzm;
      // Проверяем, установлен ли файл стилей в каталоге редактирования
      $fileStyle=$this->editdir.'/WorkTiny.css';
      $filename=$this->classdir.'/WorkTiny.css';
      //if (!file_exists($fileStyle)) 
      //{
         if (!copy($filename,$fileStyle))
         \prown\Alert('Не удалось скопировать файл стилей '.$filename); 
      //} 
      // Трассируем установленные свойства
      \prown\ConsoleLog('$this->editdir=' .$this->editdir); 
      \prown\ConsoleLog('$this->classdir='.$this->classdir); 
      \prown\ConsoleLog('$this->WorkTinyHeight='.$this->WorkTinyHeight); 
      \prown\ConsoleLog('$this->FooterTinyHeight='.$this->FooterTinyHeight); 
      \prown\ConsoleLog('$this->KwinGalleryWidth='.$this->KwinGalleryWidth); 
      \prown\ConsoleLog('$this->EdIzm='.$this->EdIzm); 
   }
   
   public function __destruct() 
   {
   }
   // *************************************************************************
   // *   Выполнить действия на странице до отправления заголовков страницы:  *
   // *                         (установить кукисы и т.д.)                    *
   // *************************************************************************
   public function ZeroEditSpace()
   {
   }
   // *************************************************************************
   // *        Установить стили пространства редактирования материала         *
   // *************************************************************************
   public function IniEditSpace()
   {
      // Настраиваемся на файл стилей
      $fileStyle=$this->editdir.'/WorkTiny.css';
      echo '<link rel="stylesheet" type="text/css" href="'.$fileStyle.'">';
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
      /*
      echo '
      <!-- theme: "modern", -->
      ';
      echo '<link rel="stylesheet" type="text/css" href="Styles/Gallery.css">';
      echo '<link rel="stylesheet" type="text/css" href="Styles/">';
      // Подключаем TinyMCE
      echo '
         <script src="/TinyMCE5-8-1/tinymce.min.js"></script>
         <script> tinymce.init
         ({
            selector: "#mytextarea",
            height: 180,
            width:  780,'.
            //'content_css: "/Styles/TinyMCE.css",'.
            '
            plugins:
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
      */ 
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

   // Включаем в разметку див галереи изображений 
   echo '<div id="KwinGallery">'; 
      echo 'KwinGallery<br>'; 
      /*
      // Выводим меню для выбора материала 
      if (prown\isComRequest(mmlVybratStatyuRedakti))
      {
         $Arti->GetPunktMenu($apdo); 
         //$Arti->MakeMenu();          
      }
      // Если указан выбор материала в запросе то в случае, 
      // если это режим редактирования, редактируем галерею 
      $getArti=prown\getComRequest('arti');
      if ($getArti<>NULL)
      {
         //echo '$_COOKIE["ModeArticle"]='.$_COOKIE["ModeArticle"].'<br>';
         if ($_COOKIE["ModeArticle"]==maGetPunktMenu)
         {
            //echo '$getArti='.$getArti.'<br>';
            // Выбираем $pid,$uid заказанного материала
            $pid=2; // 16
            $uid=3; // 17
            $Arti->SelUidPid($apdo,$getArti,$pid,$uid,$NameGru,$NameArt,$DateArt);
            //echo '$pid='.$pid.'<br>';
            //echo '$uid='.$uid.'<br>';
            //echo '$NameGru='.$NameGru.'<br>';
           //echo '$NameArt='.$NameArt.'<br>';
           //echo '$DateArt='.$DateArt.'<br>';
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
         }
      }
      //$basename=$_SERVER['DOCUMENT_ROOT'].'/ittve'; $username='tve'; $password='23ety17'; 
      //$Arti=new ArticlesMaker($basename,$username,$password);
      //$apdo=$Arti->BaseConnect();
      //$Galli->ViewGallery(gallidir,$apdo);
      */
   echo '</div>'; 
   // Включаем в разметку рабочую область редактирования
   echo '<div id="WorkTiny">';
      echo 'WorkTiny<br>'; 
      /*
      // Формируем заголовки статьи
      if ($getArti<>NULL)
      {
         echo '<div id="NameGru">'; 
         echo $NameGru.':';
         echo '</div>'; 
         echo '<div id="NameArt">'; 
         echo $NameArt.' ['.$DateArt.']';
         echo '</div>'; 
      }
      // Готовим форму для рабочей области Tiny
      echo '
         <form id="frmTinyText" method="get" action="/TinyItTve.php">
        <textarea id="mytextarea" name="dor">
      '; 
      echo htmlspecialchars($contents);
      echo '
         </textarea>
         </form>
      '; 
      // Подключаем загрузку  
      require_once $SiteRoot."/UploadImg.php";
      */
   echo '</div>';
   // Обустраиваем подвал области редактирования
   echo '<div id="FooterTiny">';
      echo 'FooterTiny<br>'; 
      /*
      echo '
         <ul class="uli">
         <li class="ili"><a class="ali" href="'.$cPref.mmlVernutsyaNaGlavnuyu.'">На-главную</a></li>
         <li class="ili"><a class="ali" href="'.$cPref.mmlVybratStatyuRedakti.'">Выбрать-статью</a></li>
         <li class="ili"><a class="ali" href="#">Пункт-меню-3</a></li>
         </ul>   
      ';
      */
   echo '</div>';
   }

   // --------------------------------------------------- ВНУТРЕННИЕ МЕТОДЫ ---
} 

// *************************************************** TinyGalleryClass.php ***
