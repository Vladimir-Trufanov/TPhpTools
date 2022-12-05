<?php namespace ttools; 
                                         
// PHP7/HTML5, EDGE/CHROME                         *** UnicodeUserClass.php ***

// ****************************************************************************
// * TPhpTools                             Помощник в выборке и использовании *
// *                                               избранных символов Юникода *
// *                                                                          *
// * v1.0, 02.12.2022                              Автор:       Труфанов В.Е. *
// * Copyright © 2022 tve                          Дата создания:  27.11.2022 *
// ****************************************************************************

/**
 * Класс --------- UnicodeUser строит интерфейс для выбора некоторых символа Юникода.
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
 * 
 * // Cоздаем объект для управления материалами сайта в базе данных
 * $Arti=new ttools\ArticlesMaker($basename,$username,$password);
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

// Ожидаемые цветности знаков юникода
define ("signBW",0);       // черно-белый знаки
define ("signColor",1);    // цветные знаки

// Подгружаем массив избранных знаков юникода
require_once("UnicodeArrays.php"); 

// Подгружаем нужные модули библиотеки прикладных функций
//require_once(pathPhpPrown."/CommonPrown.php");
/*
require_once(pathPhpPrown."/iniRegExp.php");
require_once(pathPhpPrown."/MakeRID.php");
require_once(pathPhpPrown."/MakeUserError.php");
require_once(pathPhpPrown."/RecalcSizeInfo.php");
*/
// Подгружаем нужные модули библиотеки прикладных классов
//require_once(pathPhpTools."/iniToolsMessage.php");

class UnicodeUser
{
   // ----------------------------------------------------- СВОЙСТВА КЛАССА ---
   protected $aUniCues;    // Массив избранных знаков юникода
   protected $FontFamily;  // Семейство фонтов для заголовков наборов юникодов

   // ------------------------------------------------------- МЕТОДЫ КЛАССА ---
   public function __construct($FontFamily='') 
   {
      // Инициализируем свойства класса
      $this->aUniCues=UnicodeMake();
      $this->FontFamily=UnicodeStyle($FontFamily);
      // Трассируем установленные свойства
      \prown\ConsoleLog('$this->FontFamily='.$this->FontFamily); 
   }
   public function __destruct() 
   {
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
   // *************************************************************************
   // *  Вывести набор юникодов через таблицу ($nCol-число столбцов таблицы)  *
   // *************************************************************************
   public function ViewCharsetAsTable($Charpos,$nCol)
   {
      $Charset=$this->aUniCues[$Charpos];
      $SetName=$Charset[1];
      $Charset=$Charset[3];
      // Формируем таблицу
      echo '<table id="setTable">';
      echo '<tr>
      <th class="setThead" colspan="'.$nCol.'">'.'*** '.$SetName.' ***'.'</th>
      </tr>';
      echo '<tbody class="setTbody">';
      $nPoint=0;
      $i = 1;
      while ($nPoint<count($Charset))
      {
         $aArray=$Charset[$nPoint];
         if ($i==1) echo '<tr class="setRow">';
         $hexsign=$aArray[0];
         if ($hexsign=='050E') $chex='<em>'.'&#x'.$hexsign.'</em>';
         else $chex='&#x'.$hexsign; 
         echo '<td title="'.$aArray[1].'">'; echo $chex; echo '</td>';
         if ($i==$nCol) {echo '</tr>'; $i=0;}
         $i++;
         $nPoint++;
      }
      if ($i<>$nCol) {echo '</tr>';}
      echo '</tbody> </table>';
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

// *************************************************** UnicodeUserClass.php ***
