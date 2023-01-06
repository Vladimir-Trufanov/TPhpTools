<?php namespace ttools; 
                                         
// PHP7/HTML5, EDGE/CHROME                       *** ArticlesMakerClass.php ***

// ****************************************************************************
// * TPhpTools                                   Построитель материалов сайта *
// *                                                                          *
// * v1.1, 26.12.2022                              Автор:       Труфанов В.Е. *
// * Copyright © 2022 tve                          Дата создания:  03.11.2022 *
// ****************************************************************************

/**
 * Класс ArticlesMaker организовывает базу данных материалов сайта (на примерах
 * материалов сайтов 'ittve.pw' и 'ittve.me', обеспечивает построение и ведение 
 * меню статей.
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

// --------------------- Константы для указания типа базы данных (по сайту) ---
define ("tbsIttveme", 'IttveMe'); 
define ("tbsIttvepw", 'IttvePw'); 
// -------------------------------------------------- Доступ к пунктам меню ---
define ("acsAll",   1);      // доступ разрешен всем
define ("acsClose", 2);      // закрыт, статья в разработке
define ("acsAutor", 4);      // только автору-хозяину сайта
// ----------------------------------- Режимы текущей работы объекта класса ---
// define ("maGetPunktMenu", 1);  // задана выборка материала для редактирования
// define ("maMakeMenu",     2);  // выбран материал для активной страницы

// Подгружаем общие функции класса
require_once("CommonArticlesMaker.php"); 
// Подгружаем модули функций класса, связанные с конкретным сайтом
if (articleSite==tbsIttveme) require_once("CommonIttveMe.php"); 
elseif (articleSite==tbsIttvepw) require_once("CommonIttvePw.php"); 

// Подгружаем нужные модули библиотеки прикладных функций
require_once(pathPhpPrown."/MakeCookie.php");
/*
require_once(pathPhpPrown."/iniRegExp.php");
require_once(pathPhpPrown."/MakeRID.php");
require_once(pathPhpPrown."/MakeUserError.php");
require_once(pathPhpPrown."/RecalcSizeInfo.php");
*/
// Подгружаем нужные модули библиотеки прикладных классов
//require_once(pathPhpTools."/iniToolsMessage.php");

class ArticlesMaker
{
   // ----------------------------------------------------- СВОЙСТВА КЛАССА ---
   protected $basename;             // база материалов: $_SERVER['DOCUMENT_ROOT'].'/itpw';
   protected $username;             // логин для доступа к базе данных
   protected $password;             // пароль
   public    $getArti;              // транслит выбранного материала
   // ------------------------------------------------------- МЕТОДЫ КЛАССА ---
   public function __construct($basename,$username,$password) 
   {
      // Инициализируем свойства класса
      $this->basename = $basename;
      $this->username = $username;
      $this->password = $password;
      if (isset($_COOKIE['PunktMenu'])) 
      $this->getArti=\prown\MakeCookie('PunktMenu');
      else $this->getArti=NULL; 
      // Трассируем установленные свойства
      //\prown\ConsoleLog('$this->basename='.$this->basename); 
      //\prown\ConsoleLog('$this->username='.$this->username); 
      //\prown\ConsoleLog('$this->password='.$this->password); 
      
      
      
   }
   public function __destruct() 
   {
   }
   // *************************************************************************
   // *                     Открыть соединение с базой данных                 *
   // *************************************************************************
   public function BaseConnect()
   {
      return _BaseConnect($this->basename,$this->username,$this->password);
   }
   // *************************************************************************
   // *     Построить html-код ТАБЛИЦЫ меню по базе данных материалов сайта   *
   // *                      с сортировкой по полям                           *
   // *************************************************************************
   public function MakeTblMenu($ListFields,$SignAsc,$SignDesc)
   {
      _MakeTblMenu($this->basename,$this->username,$this->password,
          $ListFields,$SignAsc,$SignDesc);
   } 
   // *************************************************************************
   // *        Построить html-код меню по базе данных материалов сайта        *
   // *************************************************************************
   public function MakeMenu()
   {
      _MakeMenu($this->basename,$this->username,$this->password);
   } 
   // 
   // *************************************************************************
   // *              Отметить в кукисе, что выбран указанный материал         *
   // *************************************************************************
   public function cookieGetPunktMenu($getArti) 
   {
      $this->getArti=\prown\MakeCookie('PunktMenu',$getArti,tStr);  
   }
   // *************************************************************************
   // *          Построить html-код меню и сделать выбор материала            *
   // *************************************************************************
   public function GetPunktMenu($pdo) 
   {
      $lvl=-1; $cLast='+++';
      $nLine=0; 
      $cli=""; // Начальная вставка конца пункта меню
      ShowCaseMe($pdo,1,1,$cLast,$nLine,$cli,$lvl);
   }
   // *************************************************************************
   // *    Создать резервную копию базы данных, построить новую базу данных   *
   // *************************************************************************
   public function BaseFirstCreate($aCharters='-') 
   {
      if (articleSite==tbsIttveme) 
      _BaseFirstCreate($this->basename,$this->username,$this->password,$aCharters);
      else
      _BaseFirstCreate($this->basename,$this->username,$this->password);
   }
   // *************************************************************************
   // *                Показать пример меню для конкретного сайта             *
   // *************************************************************************
   public function ShowSampleMenu() 
   {
      _ShowSampleMenu();
   }
   public function ShowProbaMenu() 
   {
      _ShowProbaMenu();
   }
   // ----------------------------------------------------- ЗАПРОСЫ ПО БАЗЕ ---
   
   // *************************************************************************
   // *                      Выбрать $pid,$uid по транслиту                   *
   // *************************************************************************
   public function SelUidPid($pdo,$getArti,&$pid,&$uid,&$NameGru,&$NameArt,&$DateArt,&$contents)
   {
      _SelUidPid($pdo,$getArti,$pid,$uid,$NameGru,$NameArt,$DateArt,$contents);
   }
   
   // ****************************************************************************
   // *                         Вставить материал по транслиту                   *
   // ****************************************************************************
   public function InsertByTranslit($pdo,$Translit,$pid,$uid,$NameGru,$NameArt,$DateArt,$contents)
   {
      \prown\ConsoleLog('1 insert='.$Translit); 
      //$icontents = htmlspecialchars($contents,ENT_QUOTES);	
      $icontents = htmlspecialchars($contents);	
      $statement = $pdo->prepare("INSERT INTO [stockpw] ".
         "([pid], [IdCue], [NameArt], [Translit], [access], [DateArt], [Art]) VALUES ".
         "(:pid,  :IdCue,  :NameArt,  :Translit,  :access,  :DateArt,  :Art);");
      $statement->execute([
         "pid"      => $pid, 
         "IdCue"    => 0, 
         "NameArt"  => $NameArt, 
         "Translit" => $Translit, 
         "access"   => acsAll, 
         "DateArt"  => $DateArt, 
         "Art"      => $icontents
      ]);
      \prown\ConsoleLog('2 insert='.$Translit); 
   }
   // ****************************************************************************
   // *                         Обновить материал по транслиту                   *
   // ****************************************************************************
   public function UpdateByTranslit($pdo,$Translit,$contents)
   {
      //\prown\ConsoleLog('1 update='.$Translit); 
      $statement = $pdo->prepare("UPDATE [stockpw] SET [Art] = :Art WHERE [Translit] = :Translit;");
      $statement->execute(["Art"=>$contents,"Translit"=>$Translit]);
      //\prown\ConsoleLog('2 update='.$Translit); 
   }
   // *************************************************************************
   // *     Сформировать строки меню для добавления заголовка новой статьи    *
   // ****************************************************************************
   public function MakeTitlesArt($pdo)
   {
      $lvl=-1; $cLast='+++';
      $nLine=0; 
      $cli=""; // Начальная вставка конца пункта меню
      ShowTitlesArt($pdo,1,1,$cLast,$nLine,$cli,$lvl);
   }
   // --------------------------------------------------- ВНУТРЕННИЕ МЕТОДЫ ---
}
// ************************************************* ArticlesMakerClass.php ***
