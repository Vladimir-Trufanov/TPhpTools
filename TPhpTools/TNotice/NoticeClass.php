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
 * editdir      - каталог размещения файлов, относительно корневого
 * stylesdir    - каталог стилей элементов разметки и фонтов
 *    
 * Пример создания объекта класса:
 * 
 * // Указываем место размещения библиотеки прикладных функций TPhpPrown
 * define ("pathPhpPrown",$SiteHost.'/TPhpPrown/TPhpPrown');
 * // Указываем место размещения библиотеки прикладных классов TPhpTools
 * define ("pathPhpTools",$SiteHost.'/TPhpTools/TPhpTools');
 * // Указываем каталог размещения файлов, связанных c материалом
 * define("editdir",'ittveEdit');
 * define("stylesdir",'Styles');   // стили элементов разметки и фонты
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
   protected $editdir;        // Каталог размещения файлов, необходимыъх классу
   protected $stylesdir;      // Каталог размещения файлов со стилями элементов разметки
   protected $classdir;       // Каталог файлов класса
   // ------------------------------------------------------- МЕТОДЫ КЛАССА ---
   public function __construct() 
   {
      // Инициализируем свойства класса
      $this->editdir   = editdir; 
      $this->stylesdir = stylesdir; 
      $this->classdir  = pathPhpTools.'/TNotice';
   }
   public function __destruct() 
   {
      ?>
      <script>
      // **********************************************************************
      // *                 Создать и настроить виджет "Диалог"                *
      // **********************************************************************
      function CreateDialog()
      {
         $('#DialogWind').dialog
         ({
            bgiframe:true,      // совместимость с IE6
            closeOnEscape:true, // закрывать при нажатии Esc
            modal:true,         // модальное окно
            resizable:true,     // разрешено изменение размера
            height:"auto",      // высота окна автоматически
            autoOpen:false,     // сразу диалог не открывать
            width:600,
            draggable:true, 
            show:{effect:"fade",delay:100,duration:1500},
            hide:{effect:"explode",delay:250,duration:1000,easing:'swing'},
            title: "Это окно",
         });
         // Устанавливаем шрифты диалогового окна
         // 'font-family':'"Verdana", sans-serif'
         $('#DialogWind').parent().find(".ui-dialog").css({
         });
         $('#DialogWind').parent().find(".ui-dialog-title").css({
            'font-size': '1.1rem',
            'font-weight':800,
            'color':'red',
            'font-family':'"EmojNotice"'
         });
         $('#DialogWind').parent().find(".ui-dialog-content").css(
            'color','blue'
         );
         // При необходимости скрываем заголовок диалога
         // $('#DialogWind').parent().find(".ui-dialog-titlebar").hide();
         // Прячем крестик
         // $('#DialogWind').parent().find(".ui-dialog-titlebar-close").hide();
         //}
      }
      // **********************************************************************
      // *                 Создать и настроить виджет "Диалог"                *
      // **********************************************************************
      function Notice_Info(messa,ititle,delayClose=250)
      {
         $('#DialogWind').html(messa);
         $('#DialogWind').dialog
         ({
            title: ititle,
            // Восстанавливаем параметры закрытия, 
            // так как они, возможно, были изменены
            hide:{effect:"explode",delay:delayClose,duration:1000,easing:'swing'},
         });
         $('#DialogWind').dialog("open")
      }
      // **********************************************************************
      // *                                 Ошибка!                            *
      // **********************************************************************
      function Error_Info(messa)
      {
         $('#DialogWind').parent().find(".ui-dialog-content").css('color','red');
         Notice_Info(messa,'Ошибка',250);
      }
      // **********************************************************************
      // *                               Информация!                          *
      // **********************************************************************
      function Info_Info(messa)
      {
         $('#DialogWind').parent().find(".ui-dialog-content").css('color','blue');
         Notice_Info(messa,'Оk',250);
      }
      </script>
      <?php
   }
   // *************************************************************************
   // *                 Проинициализировать стили объекта                     *
   // *        (выполнить действия в области <head></head> страницы)          *
   // *************************************************************************
   public function Init() 
   {
      // Проверяем, нужно ли заменить файлы стилей в каталоге редактирования и,
      // (при его отсутствии, при несовпадении размеров или старой дате) 
      // загружаем из класса 
      $this->CompareCopyRoot('Lobster.ttf',$this->stylesdir);
      // Подключаем шрифт
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
      ?> 
      <script>
      $(document).ready(function()
      {
         // Создаем окно для сообщений
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
   // *    Проверить существование, параметры и перебросит файл из каталога   *
   // *      класса в любой каталог относительно корневого каталога сайта     *
   // *************************************************************************
   private function CompareCopyRoot($Namef,$toDir='')
   {
      // Если каталог, в который нужно перебросить файл - корневой
      if ($toDir=='') $thisdir=$toDir;
      // Если каталог указан относительно корневого (без обратных слэшей !!!)
      else $thisdir=$toDir.'/';
      // Проверяем существование, параметры и перебрасываем файл
      $fileStyle=$thisdir.$Namef;
      clearstatcache($fileStyle);
      $filename=$this->classdir.'/'.$Namef;
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
   // *                     Вывести информационное сообщение                  *
   // *************************************************************************
   public function Info($messa,$title)
   {
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
