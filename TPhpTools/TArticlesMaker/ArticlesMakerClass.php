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
 * editdir      - каталог размещения файлов, относительно корневого
 *    
 * Пример создания объекта класса:
 * 
 * // Указываем место размещения библиотеки прикладных функций TPhpPrown
 * define ("pathPhpPrown",$SiteHost.'/TPhpPrown/TPhpPrown');
 * // Указываем место размещения библиотеки прикладных классов TPhpTools
 * define ("pathPhpTools",$SiteHost.'/TPhpTools/TPhpTools');
 * // Указываем каталог размещения файлов, связанных c материалом
 * define("editdir",'ittveEdit');
 * 
 * // Cоздаем объект для управления материалами сайта в базе данных
 * $Arti=new ttools\ArticlesMaker($basename,$username,$password,$SiteRoot);
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
   protected $editdir;          // Каталог размещения файлов, связанных c материалом
   protected $classdir;         // Каталог файлов класса
   protected $basename;         // База материалов: $_SERVER['DOCUMENT_ROOT'].'/itpw';
   protected $username;         // Логин для доступа к базе данных
   protected $password;         // Пароль
   protected $fileStyle;        // Файл стилей
   public    $getArti;          // Транслит выбранного материала
   // ------------------------------------------------------- МЕТОДЫ КЛАССА ---
   public function __construct($basename,$username,$password) 
   {
      // Инициализируем свойства класса
      $this->editdir  = editdir; 
      $this->classdir=pathPhpTools.'/TArticlesMaker';
      $this->basename = $basename;
      $this->username = $username;
      $this->password = $password;
      
      if (isset($_COOKIE['PunktMenu'])) 
      $this->getArti=\prown\MakeCookie('PunktMenu');
      else $this->getArti=NULL; 
      // Выполняем действия на странице до отправления заголовков страницы: 
      // (устанавливаем кукисы и т.д.)                  
      $this->ZeroEditSpace();
      // Трассируем установленные свойства
      //\prown\ConsoleLog('$this->basename='.$this->basename); 
      //\prown\ConsoleLog('$this->username='.$this->username); 
      //\prown\ConsoleLog('$this->password='.$this->password); 
   }
   // *************************************************************************
   // *             Прячем в __destruct обработку клика выбора раздела        *
   // *                    (при назначении новой статьи)                      *
   // *************************************************************************
   public function __destruct() 
   {
      ?> 
      <script>
      pathPhpTools="<?php echo pathPhpTools;?>";
      pathPhpPrown="<?php echo pathPhpPrown;?>";
      
      function isi(Uid)
      {
         // Задаем обработчик аякс-запроса
         // !!! 16.01.2023 - не удалось запускать обработчик из других мест,
         // кроме корневого каталога. Это особенность скорее из-за того,
         // что работа выполняется в объекте класса
         pathphp="getNameCue.php";
         // Делаем запрос на определение наименования раздела материалов
         $.ajax({
            url: pathphp,
            type: 'POST',
            data: {idCue:Uid, pathTools:pathPhpTools, pathPrown:pathPhpPrown},
            async: false,
            error: function()
            {
               alert('Ошибка!');
            },
            success: function(message)
            {
               arr=JSON.parse(message); 
               //alert(message);
               alert('success!');
               //$('#Message').html(message+'. Указать название и дату для новой статьи');
               $('#Message').html(arr[0]+'. Указать название и дату для новой статьи');
               //$('#nsCue').attr('value',Uid);
            }
         });
      }
      </script>
      <?php
     
   }
   // *************************************************************************
   // *     Сформировать строки меню для добавления заголовка новой статьи    *
   // *                    (при назначении новой статьи)                      *
   // *************************************************************************
   
               /*
            
                    
            $.ajax({
            type:'POST',                        // тип запроса
            url: 'getNameCue.php',           // скрипт обработчика
            dataType: "json",
            data:  {idCue:Uid, pathTools:pathPhpTools, pathPrown:pathPhpPrown},
            //cache: true,  // запрошенные страницы кэшировать браузером (задаем явно для IE)
            //processData: false,                 // отключаем, так как передаем файл
            // Отмечаем результат выполнения скрипта по аякс-запросу (успешный или нет)
            success:function(data)
            {
               //alert(data[0].text);
            },
            // Отмечаем безуспешное удаление старых файлов
            error:function(data)
            {
               alert('Ошибка!');
            }
            });

         
         
         
 
            
      function isi(Uid)
      {
         // Задаем обработчик аякс-запроса
         // !!! 16.01.2023 - не удалось запускать обработчик из других мест,
         // кроме корневого каталога. Это особенность скорее из-за того,
         // что работа выполняется в объекте класса
         pathphp="getNameCue.php";
         // Делаем запрос на определение наименования раздела материалов
         $.ajax({
            url: pathphp,
            type: 'POST',
            data: {idCue:Uid, pathTools:pathPhpTools, pathPrown:pathPhpPrown},
            async: false,
            error: function()
            {
               alert('Ошибка!');
            },
            success: function(message)
            {
               $('#Message').html(message+'. Указать название и дату для новой статьи');
               $('#nsCue').attr('value',Uid);
            }
         });
      }
      */

         /*
         // Делаем запрос на определение наименования раздела материалов
         $.ajax({
            url: pathphp,
            data: {idCue:Uid, pathTools:pathPhpTools, pathPrown:pathPhpPrown},
            //method: 'get',
            dataType: 'json',
            
            
            type:'POST',                        // тип запроса
            cache: true,  // запрошенные страницы кэшировать браузером (задаем явно для IE)
            processData: false,                 // отключаем, так как передаем файл

            
            
            
            
            success: function(data)
            {
               //NameGru=data[0].NameGru;
	           //console.dir(data);
               //$('#Message').html(NameGru+'. Указать название и дату для новой статьи');
               //$('#nsCue').attr('value',Uid);
            },
            error: function (jqXHR, exception) 
            {
	           if (jqXHR.status === 0) 
               {
		          alert('Ошибка/нет соединения.');
	           } 
               else if (jqXHR.status == 404) 
               {
		          alert('Требуемая страница не найдена (404).');
	           } 
               else if (jqXHR.status == 500) 
               {
		          alert('Внутренняя ошибка сервера (500).');
	           } 
               else if (exception === 'parsererror') 
               {
		          alert('Cинтаксический анализ JSON не выполнен.');
               } 
               else if (exception === 'timeout') 
               {
		          alert('Ошибка (time out) времени ожидания ответа.');
	           } 
               else if (exception === 'abort') 
               {
		          alert('Ajax-запрос прерван.');
	           } 
               else 
               {
	    	      alert('Неперехваченная ошибка: '+jqXHR.responseText);
	           }
            }
         });
         */

      

   
   
   public function MakeTitlesArt($pdo)
   {
      $lvl=-1; $cLast='+++';
      $nLine=0; 
      $cli=""; // Начальная вставка конца пункта меню
      $this->ShowTitlesArt($pdo,1,1,$cLast,$nLine,$cli,$lvl);
   }
   private function ShowTitlesArt($pdo,$ParentID,$PidIn,&$cLast,&$nLine,&$cli,&$lvl,$FirstUl=' class="accordion"')
   {
      // Определяем текущий уровень меню
      $lvl++; 
      // Выбираем все записи одного родителя
      $cSQL="SELECT uid,NameArt,Translit,pid,IdCue,DateArt FROM stockpw WHERE pid=".$ParentID." ORDER BY uid";
      $stmt = $pdo->query($cSQL);
      $table = $stmt->fetchAll();
      if (count($table)>0) 
      {
         echo('<ul'.$FirstUl.'>'."\n"); $cLast='+ul';
         // Перебираем все записи родителя, подсчитываем количество, формируем пункты меню
         $nPoint=0;
         foreach ($table as $row)
         {
            $nLine++; $cLine=''; 
            $Uid=$row["uid"]; $Pid=$row["pid"]; $Translit=$row["Translit"];
            $IdCue=$row["IdCue"]; $DateArt=$row["DateArt"]; 
            if ($cLast<>'+ul') 
            {
                $cli="</li>\n";
                echo($cli); $cLast='-li';
            }
            if ($IdCue==-1)
            {
               echo('<li id="'.$Translit.'" class="'.$Translit.'">'); 
               //echo('<i>'.$row['NameArt'].'<a href="?titl='.$Uid.'">'.'<span>'.$Uid.'</span></a></i>'."\n"); 
               //echo('<i class="ispan" onclick="isi('.$Uid.')">'.$row['NameArt'].'<div><span class="inpspan" id="spa'.$Uid.'">'.$Uid.'.</span></div></i>'."\n"); 
               //echo('<i onclick="isi('.$Uid.')">'.$row['NameArt'].'<div><span class="inpspan" id="spa'.$Uid.'">'.$Uid.'.</span></div></i>'."\n"); 
               echo('<i onclick="isi('.$Uid.')">'.$row['NameArt'].
                    '<span id="spa'.$Uid.'">'.$Uid.'.</span>'.
                    '</i>'."\n");
               /*
               echo('<i onclick="isi('.$Uid.')">'.$row['NameArt'].
                    '<span id="spa'.$Uid.'">'.'<input name="reset" value="'.$Uid.'">'.'</span>'.
                    '</i>'."\n");
               */
               /*
               echo('<i>'.$row['NameArt'].
                    '<span id="spa'.$Uid.'">'.'<input name="reset" value="'.$Uid.'">'.'</span>'.
                    '</i>'."\n");
              */
            } 
            else
            {
               $nPoint++;
               echo('<li><i><em>'.$Uid.'.</em>'.$row['NameArt'].'</i>'."\n"); 
               //<li><i><em>13</em>Таёжный зоопарк на озере Сямозеро<span>04.07.2010</span></i></li>			
            }
            
            /*
            <li id="progulki" class="progulki"><i>Прогулки<a href="?titl=16"><span>16</span></a>
            <ul class="sub-menu">
            <li><i><em>17</em>Охота на медведя<span>04.07.2010</span></i>
            </li>
            </ul>
            
            <li id="progulki" class="progulki"><i>Прогулки<a href="#201"><span>201</span></a></i>
            <ul class="sub-menu">
            <li><i><em>21</em>Охота на медведя<span>24.07.2010</span></i></li>			
            </ul>
            </li>
            */
            
            
            $cLast='+li';
            $this->ShowTitlesArt($pdo,$Uid,$Pid,$cLast,$nLine,$cli,$lvl,' class="sub-menu"'); 
            $lvl--; 
         }
         $cli="</li>\n";
         echo($cli); $cLast='-li'; 
         echo("</ul>\n");  $cLast='-ul';
      }
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
      $this->CompareCopyRoot('ArticlesMaker.css',$this->editdir);
      $this->CompareCopyRoot('bgnoise_lg.jpg',$this->editdir);
      $this->CompareCopyRoot('icons.png',$this->editdir);
      $this->CompareCopyRoot('getNameCue.php');
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
   // *        Установить стили пространства редактирования материала         *
   // *************************************************************************
   public function IniEditSpace()
   {
      // Настраиваемся на файл стилей
      $this->fileStyle=$this->editdir.'/ArticlesMaker.css';
      echo '<link rel="stylesheet" type="text/css" href="'.$this->fileStyle.'">';
      // Настраиваем фоны графическими файлами
      $bgnoise_lg=$this->editdir.'/bgnoise_lg.jpg';
      $icons=$this->editdir.'/icons.png';
      echo '
      <style>
      .accordion li > a span,
      .accordion li > i span 
      {
         background:#e0e3ec url('.$bgnoise_lg.') repeat top left;
      }
      .accordion > li > a:before 
      {
         background-image:url('.$icons.');
      }
      </style>
      ';
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
   // *  Выбрать $pid,$uid,$NameGru,$NameArt,$DateArt,$contents по транслиту  *
   // *************************************************************************
   public function SelUidPid($pdo,$getArti,&$pid,&$uid,&$NameGru,&$NameArt,&$DateArt,&$contents)
   {
      // Инициируем возвращаемые данные
      $pid=0; $uid=0; 
      $NameGru='Материал для редактирования не выбран!'; $NameArt=''; $DateArt='';
      // Выбираем по транслиту $pid,$uid,$NameArt
      $cSQL='SELECT * FROM stockpw WHERE Translit="'.$getArti.'"';
      //\prown\ConsoleLog('$getArti='.$getArti);
      $stmt=$pdo->query($cSQL);
      $table=$stmt->fetchAll();
      if (count($table)==1)
      {
         $pid=$table[0]['pid']; $uid=$table[0]['uid']; 
         $NameArt=$table[0]['NameArt']; $DateArt=$table[0]['DateArt'];
         $contents=$table[0]['Art'];
         // Добираем $NameGru
         $table=$this->SelRecord($pdo,$pid); $NameGru=$table[0]['NameArt'];
      }
   }
   // *************************************************************************
   // * Выбрать запись по идентификатору                                      *
   // *              (например, узнать наименование группы по идентификатору: *
   // *          $table=SelRecord($pdo,$pid); $NameGru=$table[0]['NameArt'];) *
   // *************************************************************************
   public function SelRecord($pdo,$UnID)
   {
      $cSQL='SELECT * FROM stockpw WHERE uid='.$UnID;
      $stmt = $pdo->query($cSQL);
      $table = $stmt->fetchAll();
      return $table; 
   }

   // *************************************************************************
   // *                      Вставить материал по транслиту                   *
   // *************************************************************************
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
}
// ************************************************* ArticlesMakerClass.php ***
