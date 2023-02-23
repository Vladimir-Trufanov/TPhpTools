<?php namespace ttools; 
                                         
// PHP7/HTML5, EDGE/CHROME                              *** NoticeClass.php ***

// ****************************************************************************
// * TPhpTools               Вывести информационное сообщение, предупреждение *
// *                 или сообщение об ошибке единообразно из PHP и JavaScript *
// *                                                  через диалоги jQuery UI *
// *                                                                          *
// * v1.0, 24.01.2023                              Автор:       Труфанов В.Е. *
// * Copyright © 2022 tve                          Дата создания:  18.01.2023 *
// ****************************************************************************

/**
 * Класс --------- KwinGallery строит интерфейс для выбора некоторых символа Юникода.
 * Выборка символов осуществляется из одного из подмассивов общего массива 
 * массива $aUniCues. Подмассивы (наборы) созданы из авторских соображений и 
 * 
 * Для взаимодействия с объектами класса должны быть определены константы:
 *
 * pathPhpTools - путь к каталогу с файлами библиотеки прикладных классов;
 * pathPhpPrown - путь к каталогу с файлами библиотеки прикладных функции
 * stylesdir    - каталог стилей элементов разметки и фонтов
 * jsxdir       - каталог файлов на javascript
 *    
 * Пример создания объекта класса:
 * 
 * // Указываем место размещения библиотеки прикладных функций TPhpPrown
 * define ("pathPhpPrown",$SiteHost.'/TPhpPrown/TPhpPrown');
 * // Указываем место размещения библиотеки прикладных классов TPhpTools
 * define ("pathPhpTools",$SiteHost.'/TPhpTools/TPhpTools');
 * // Указываем каталог размещения файлов, связанных c материалом
 * define("stylesdir",'Styles');  
 * define("jsxdir",'Jsx');        
 * 
 * // Подключаем объект единообразного вывода сообщений
 * $note=new ttools\Notice();
 * 
 * echo '<head>';
 *    // Подключаем jquery и jquery-ui скрипты 
 *    echo ' 
 *       <script src="/jQuery/jquery-3.6.3.min.js"></script>
 *       <script src="/jQuery/jquery-ui.min.js"></script>
 *    ';
 *    // Инициируем объект и подключаем стили 
 *    $note->Init();
 * echo '</head>';
 * 
 * // Открываем и работаем с диалогом, например через JavaScript, jQueryUI:
 * // $('#DialogWind').dialog - прямое обращение к диалогу через его идентификатор;
 * // Notice_Info, js-функция вывода информационного сообщения через '#DialogWind'
 * 
 * echo '<body>';
 * echo '
 *    <script>
 *    // **********************************************************************
 *    // *     Добавить кнопку в диалог и задать вопрос по поводу удаления    *
 *    // **********************************************************************
 *    function UdalitMater(Uid)
 *    {
 *       $('#DialogWind').dialog
 *       ({
 *          buttons:[{text:"OK",click:function(){xUdalitMater(Uid)}}]
 *       });
 *       htmlText="Удалить выбранный материал по "+Uid+"?";
 *       Notice_Info(htmlText,"Удалить материал");
 *    }
 *    function xUdalitMater(Uid)
 *    {
 *       alert('Uid='+Uid);
 *       $("#DialogWind").dialog("close");
 *    }
 *    </script>
 * ';
 * echo '</body>';
 * 
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

// Подгружаем нужные модули библиотеки прикладных функций
require_once pathPhpPrown."/CommonPrown.php";

// Возможные типы сообщений
define ("Error",   1);
define ("Info",    2);
define ("Warning", 3);

class Notice
{
   // ----------------------------------------------------- СВОЙСТВА КЛАССА ---
   protected $stylesdir;      // каталог размещения файлов со стилями элементов разметки
   protected $jsxdir;         // каталог файлов на javascript
   protected $classdir;       // каталог файлов класса
   // ------------------------------------------------------- МЕТОДЫ КЛАССА ---
   public function __construct() 
   {
      // Инициализируем свойства класса
      $this->stylesdir = stylesdir; 
      $this->jsxdir    = jsxdir; 
      $this->classdir  = pathPhpTools.'/TNotice';
   }
   public function __destruct() 
   {
   }
   // *************************************************************************
   // *                 Проинициализировать стили объекта                     *
   // *        (выполнить действия в области <head></head> страницы)          *
   // *************************************************************************
   public function Init() 
   {
      // Проверяем, нужно ли заменить файлы стилей в каталогах и,
      // (при их отсутствии, при несовпадении размеров или старой дате) 
      // загружаем из класса 
      CompareCopyRoot('Lobster.ttf',$this->classdir,$this->stylesdir);
      CompareCopyRoot('Notice.js',$this->classdir,$this->jsxdir);
      // src: url(Styles/Lobster.ttf); 
      $urlttf='url('.$this->stylesdir.'/Lobster.ttf)';
      echo '
         <style>
         @font-face 
         {
            font-family: EmojNotice; 
            src: '.$urlttf.'; 
      }
      </style>
      ';
      // <script src="/Jsx/Notice.js"></script>
      echo '<script src="/'.$this->jsxdir.'/Notice.js"></script>';
      // Создаем окно для сообщений
      ?>
      <script>
      $(document).ready(function()
      {
         noticediv=document.createElement('div');
         noticediv.className="dialog_window";
         noticediv.innerHTML="<p>Привет, мир!</p>";
         noticediv.id="DialogWind";
         document.body.append(noticediv);
         CreateDialog();
      })
      </script>
      <?php
   }
   
   // *************************************************************************
   // *                     Вывести информационное сообщение                  *
   // *************************************************************************
   public function Info($messa,$title='')
   {
      ?> <script> $(document).ready(function()
      {
         messa="<?php echo $messa;?>";
         Info_Info(messa);
      })
      </script> <?php
   }
   // *************************************************************************
   // *                         Вывести сообщение об ошибке                   *
   // *************************************************************************
   private function Error($messa)
   {
   }
   // *************************************************************************
   // *                             Вывести предупреждение                    *
   // *************************************************************************
   private function Warning($messa)
   {
   }

   // --------------------------------------------------- ВНУТРЕННИЕ МЕТОДЫ ---

} 

// ******************************************************** NoticeClass.php ***
