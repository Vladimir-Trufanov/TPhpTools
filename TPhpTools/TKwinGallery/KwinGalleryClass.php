<?php namespace ttools; 
                                         
// PHP7/HTML5, EDGE/CHROME                         *** KwinGalleryClass.php ***

// ****************************************************************************
// * TPhpTools                 Фрэйм галереи изображений, связанных с текущим *
// *                   материалом (uid) из выбранной (указанной) группы (pid) *
// *                                                                          *
// * v2.0, 19.12.2022                              Автор:       Труфанов В.Е. *
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

// Подгружаем нужные модули библиотеки прикладных функций
require_once pathPhpPrown."/CommonPrown.php";
// Подгружаем нужные модули библиотеки прикладных классов
require_once pathPhpTools."/TArticlesMaker/ArticlesMakerClass.php";

class KwinGallery
{
   // ----------------------------------------------------- СВОЙСТВА КЛАССА ---
   protected $gallidir;  // Каталог для размещения файлов галереи и связанных материалов
   protected $nym;       // Префикс имен файлов для фотографий галереи и материалов
   protected $pid;       // Семейство фонтов для заголовков наборов юникодов
   protected $uid;       // Семейство фонтов для заголовков наборов юникодов

   protected $editdir='ittveEdit/';

   // ------------------------------------------------------- МЕТОДЫ КЛАССА ---
   public function __construct($gallidir,$nym,$pid,$uid) 
   {
      // Инициализируем свойства класса
      $this->gallidir=$gallidir; 
      $this->nym=$nym; $this->pid=$pid; $this->uid=$uid;
      // Трассируем установленные свойства
      //\prown\ConsoleLog('$this->gallidir='.$this->gallidir); 
      //\prown\ConsoleLog('$this->nym='.$this->nym); 
      //\prown\ConsoleLog('$this->pid='.$this->pid); 
      //\prown\ConsoleLog('$this->uid='.$this->uid); 
   }
   
   public function __destruct() 
   {
   }
   // *************************************************************************
   // *       Развернуть изображения галереи и обеспечить их ведение:         *
   // *                $Dir - каталог для размещения изображений              *
   // *************************************************************************
   public function ViewGallery($Dir)
   {
      // Выбираем режим работы с изображениями, как режим редактирования материала
      if ($Dir==$this->editdir)
      {
         // Проверяем существование и создаем базу данных редактируемого материала
         $basename=$_SERVER['DOCUMENT_ROOT'].'/itEdit'; // имя базы без расширения 'db3'
         $username='tve';
         $password='23ety17'; 
         $Arti=new ArticlesMaker($basename,$username,$password);
         // Создаем (или открываем) базу данных для редактируемого материала
         $aCharters=[                                                          
            [ 1, 0,-1, 'ittve.me',            '/',                 acsAll,'20',''],
            [16, 1,-1, 'Прогулки',            'progulki',          acsAll,'20',''],
            [17,16, 0,    'Охота на медведя', 'ohota-na-medvedya', acsAll,'2011.05.06',''],
            [21, 0,-1, 'ittve.end',           '/',                 acsAll,'20','']
         ];       

         $Arti->BaseFirstCreate($aCharters);
      }
      else
      {
         \prown\ConsoleLog('НЕ ='.$this->editdir); 
      }
   }
   // *************************************************************************
   // *                 Вывести набор юникодов одним столбцом                 *
   // *************************************************************************
   public function ViewCharsetAsColomn($Charpos)
   {
      $Charset=$this->aUniCues[$Charpos];
      $SetName=$Charset[1];
      echo $SetName.'<br>'; 
      $Charset=$Charset[3];
      //echo '<pre>';
      //print_r($Charset);
      //echo '</pre>';
      foreach ($Charset as $aArray) 
      {
         $hexbeg=$aArray[0];
         $nbeg=hexdec($hexbeg);
         $hexsign=dechex($nbeg);
         if ($hexsign=='50e') $chex='<em>'.'&#x'.$hexbeg.'</em>'; else $chex='&#x'.$hexbeg; 
         echo $chex.' '.$hexbeg.'-->'.$nbeg.' '.$chex.' '.$aArray[1]."<br>";
      }
      echo '<br>'; 
   }
   // --------------------------------------------------- ВНУТРЕННИЕ МЕТОДЫ ---
   /*
   // *************************************************************************
   // *                           Проверить размер файла                      *
   // *************************************************************************
   protected function checkSize($filename,$size) 
   {
      $Result=imok;
      // Проверяем формат указания размера файла в php.ini, где он должен быть
      // указан в мбайтах целым числом и символом "M" в конце
      $point=-1;
      $subs=Findes(regIntMbyte,$this->_maxphp,$point);
      if ($subs=='')
      {
         // "Неверно определен размер файла загрузки в Мбайт" для php.ini
         $Result=\prown\MakeUserError(InvalidUploadSize,$this->_prefix,rvsReturn);
      }
      // Продолжаем анализ размера файла
      else
      {
         // Переводим мбайты в байты
         $numb=substr($subs,0,strlen($subs)-1);
         $Unit="MB"; $point=\prown\RecalcToBytes($Unit,(int)$numb,0,rvsReturn);
         // Если пересчет с ошибкой, то возвращаем сообщение с ошибкой пересчета
         if (gettype($point)=="string") $Result=$point;
         // Переопределяем максимальный размер файла
         else
         {
            if ($point<$this->_max) $this->_max=$point; 
            // Отмечаем ошибочным сообщением то, что файл слишком большой или не выбран
            if ($size == 0)
            {
               $Result=\prown\MakeUserError(ZeroFileSize,$this->_prefix,rvsReturn);
            } 
            // Отмечаем обход скрытого задания максимального размера файла
            elseif ($size>$this->_max) 
            {
               $Result=\prown\MakeUserError
               (ExceedOnМaxSize.': '.$size.'>'.$this->_max,$this->_prefix,rvsReturn);
            } 
         }
      }
      return $Result;
   }
   */
} 

// *************************************************** KwinGalleryClass.php ***
