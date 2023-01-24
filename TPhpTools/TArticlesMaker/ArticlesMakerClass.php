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
// $kindMessage - объект вывода сообщений. По умолчанию = NULL, что означает,
//    что сообщение выводится через alert. Методом setKindMessage может быть
//    подключен объект класса TNotice, который и будет заниматься выводом всех
//    сообщений

// --------------------- Константы для указания типа базы данных (по сайту) ---
define ("tbsIttveme", 'IttveMe'); 
define ("tbsIttvepw", 'IttvePw'); 
// -------------------------------------------------- Доступ к пунктам меню ---
define ("acsAll",   1);      // доступ разрешен всем
define ("acsClose", 2);      // закрыт, статья в разработке
define ("acsAutor", 4);      // только автору-хозяину сайта
/*
// --------------- Ошибки обработки аякс-запросе названия группы материалов ---
define ("gncPrown", '1');    // нет пути к библиотекам прикладных функций
define ("gncTools", '2');    // нет пути к библиотекам прикладных классов
define ("gncIdCue", '3');    // не передан идентификатор группы материалов
*/
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
   public $kindMessage;         // Объект вывода сообщений;  
   public $getArti;             // Транслит выбранного материала

   protected $editdir;          // Каталог размещения файлов, связанных c материалом
   protected $classdir;         // Каталог файлов класса
   protected $basename;         // База материалов: $_SERVER['DOCUMENT_ROOT'].'/itpw';
   protected $username;         // Логин для доступа к базе данных
   protected $password;         // Пароль
   protected $fileStyle;        // Файл стилей
   // ------------------------------------------------------- МЕТОДЫ КЛАССА ---
   public function __construct($basename,$username,$password) 
   {
      // Инициализируем свойства класса
      $this->editdir  = editdir; 
      $this->classdir=pathPhpTools.'/TArticlesMaker';
      $this->basename = $basename;
      $this->username = $username;
      $this->password = $password;
      $this->kindMessage = NULL;
      
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
   // *           Спрятать в __destruct обработку клика выбора раздела        *
   // *                    (при назначении новой статьи)                      *
   // *************************************************************************
   public function __destruct() 
   {
      ?> 
      <style>
      @font-face 
      {
         font-family: Emojitveme; 
         src: url(Styles/Lobster.ttf); 
      }
      /*
      p 
      {
         font-family: Emojitveme;
      }
      */
      </style>

      <script>
      pathPhpTools="<?php echo pathPhpTools;?>";
      pathPhpPrown="<?php echo pathPhpPrown;?>";
      // **********************************************************************
      // *        Задать обработчик аякс-запроса по удалению материала        *
      // **********************************************************************
      function UdalitMater(Uid)
      {
         htmlText="Удалить выбранный материал по "+Uid+"?";
         $('#DialogWind').html(htmlText);
         $('#DialogWind').dialog
         ({
            bgiframe:true,      // совместимость с IE6
            closeOnEscape:true, // закрывать при нажатии Esc
            modal:true,         // модальное окно
            resizable:true,     // разрешено изменение размера
            height:"auto",      // высота окна автоматически
            draggable:true, 
            show:{effect:"fade",delay:250,duration:1000},
            hide:{effect:"explode",delay:250,duration:1000,easing:'swing'},
            title: "Удалить материал",
            buttons:[{text:"OK",click:function(){xUdalitMater(Uid)}}]
         });
         // Устанавливаем шрифты диалогового окна
         // 'font-family':'"Verdana", sans-serif'
         $('#DialogWind').parent().find(".ui-dialog").css({
         });
         $('#DialogWind').parent().find(".ui-dialog-title").css({
            'font-size': '1.2rem',
            'font-weight':800,
            'color':'red',
            'font-family':'"Emojitveme"'
         });
         $('#DialogWind').parent().find(".ui-dialog-content").css(
            'color','blue'
         );
         // При необходимости скрываем заголовок диалога
         // $('#DialogWind').parent().find(".ui-dialog-titlebar").hide();
         // Прячем крестик
         // $('#DialogWind').parent().find(".ui-dialog-titlebar-close").hide();
      }
      function xUdalitMater(Uid)
      {
         alert('Uid='+Uid);
         $("#DialogWind" ).dialog("close");
      }
      // **********************************************************************
      // *  Задать обработчик аякс-запроса по клику выбора раздела материалов *
      // *  при назначении новой статьи:                                      *
      // *         !!! 16.01.2023 - не удалось запускать обработчик из других *
      // *       мест, кроме корневого каталога. Это особенность скорее из-за *
      // *                     того, что работа выполняется в объекте класса. *
      // **********************************************************************
      function SelMatiSection(Uid)
      {
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
      }
      // **********************************************************************
      // *             Выделить метку (наборы символов до и после) в принятом *
      // *                                     сообщения и извлечь сообщение. *
      // * так как в АЯКС-запросах на jQuery, когда от сервера                *
      // * передается сообщение в js, то (фактически - 19.01.2023)            *
      // * перед сообщением подвешивается сам js-скрипт запроса.              *   
      // **********************************************************************
      function FreshLabel(messa)
      {
         result='{"NameGru":"nodef", "Piati":0, "iif":"nodef"}';
         str=messa;
         target='ghjun5'; // цель поиска
         pos=0; nBeg=0; nEnd=0;
         while (true) 
         {
            foundPos=str.indexOf(target,pos);
            if (foundPos<0) break;
            // Меняем начальную и конечную позиции подстроки
            nBeg=nEnd+6; nEnd=foundPos;
            result=str.substring(nBeg,nEnd); 
            // Продолжаем со следующей позиции
            pos=foundPos+1; 
         };
         return result;
      }
      // **********************************************************************
      // *             Обработать ошибку выполнения аякс-запроса              *
      // **********************************************************************
      function SmarttodoError(jqXHR,exception) 
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
      </script>
      <?php
   }
   // *************************************************************************
   // *       Подключить объект класса TNotice, который будет заниматься      *
   // *                        выводом всех сообщений                         *
   // *************************************************************************
   public function setKindMessage($note)
   {
      $this->kindMessage = $note;
   }
   // *************************************************************************
   // *           Сформировать строки меню по базе данных материалов          *
   // *  (общий механизм: $clickGru-вызов процедуры обработки клика по группе *
   // *   материалов, $clickGru-вызов процедуры обработки клика по материалу) *
   // *************************************************************************
   public function MakeUniMenu($pdo,$clickGru='',$clickOne='')
   {
      $lvl=-1;      // инициировали текущий уровень меню
      $cLast='+++'; // инициировали признак типа сформированной строки меню
      $nLine=0;     // инициировали счетчик сформированных строк меню
      $cli="";      // сбрасили начальную вставку конца пункта меню
      $this->_MakeUniMenu($clickGru,$clickOne,$pdo,1,1,$cLast,$nLine,$cli,$lvl);
   }
   private function _MakeUniMenu($clickGru,$clickOne,
   $pdo,$ParentID,$PidIn,&$cLast,&$nLine,&$cli,&$lvl,$FirstUl=' class="accordion"')
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
         foreach ($table as $row)
         {
            // Инкрементируем счетчик строк
            $nLine++; 
            // Выбираем параметры записи
            $Uid=$row["uid"]; $Pid=$row["pid"]; 
            $NameArt=$row['NameArt']; $Translit=$row["Translit"];
            $IdCue=$row["IdCue"]; $DateArt=$row["DateArt"]; 
            // Закрываем предыдущий 'LI'
            if ($cLast<>'+ul') 
            {
                $cli="</li>\n";
                echo($cli); $cLast='-li';
            }
            // Выводим 'LI' группы материалов или собственно материала
            $grClick=$this->HandleСlick($clickGru,$Uid);
            $maClick=$this->HandleСlick($clickOne,$Uid);
            echo('<li>'); 
            if ($IdCue==-1)
            {
               echo('<i'.$grClick.'>'.$NameArt.
                    '<span id="spa'.$Uid.'">'.$Uid.'.</span>'.
                    '</i>'."\n");
            } 
            else
            {
               echo('<i'.$maClick.'><em>'.$Uid.'.</em>'.$NameArt.'</i>'."\n"); 
            }
            $cLast='+li';
            // Заходим на следующую строку
            $this->_MakeUniMenu($clickGru,$clickOne,
               $pdo,$Uid,$Pid,$cLast,$nLine,$cli,$lvl,' class="sub-menu"'); 
            $lvl--; 
         }
         $cli="</li>\n";
         echo($cli); $cLast='-li'; 
         echo("</ul>\n");  $cLast='-ul';
      }
   }
   private function HandleСlick($clickIs,$Uid)
   {
      if ($clickIs=='') $Result='';
      else $Result=' onclick="'.$clickIs.'('.$Uid.')"';
      return $Result;
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
      $stmt=$pdo->query($cSQL);
      $table=$stmt->fetchAll();
      $count=count($table);
      // Если найдена одна запись, то выбираем данные
      if ($count>0)
      {
         $pid=$table[0]['pid']; $uid=$table[0]['uid']; 
         $NameArt=$table[0]['NameArt']; $DateArt=$table[0]['DateArt'];
         $contents=$table[0]['Art'];
         // Добираем $NameGru
         $table=$this->SelRecord($pdo,$pid); $NameGru=$table[0]['NameArt'];
      }
      // Если больше одной записи, то диагностируем ошибку
      if ($count>1)
      {
         \prown\Alert("В группе '".$NameGru."' статья '".$NameArt."' c дублированным транслитом: ".$getArti); 
         //\prown\Alert('Найдено несколько ['.$count.'] записей по транслиту: '.$getArti); 
      }
      // Если не найдено записей, то диагностируем ошибку
      else if ($count<1)
      \prown\Alert('Не найдено записей по транслиту: '.$getArti);
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
   public function InsertByTranslit($pdo,$Translit,$pid,$NameArt,$DateArt,$contents)
   {
    try 
    {
      $pdo->beginTransaction();
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
      $pdo->commit();
    } 
    catch (Exception $e) 
    {
      // Если в транзакции, то делаем откат изменений
      if ($pdo->inTransaction()) 
      {
         $pdo->rollback();
      }
      // Продолжаем исключение
      throw $e;
    }
   }
   // *************************************************************************
   // *                       Обновить материал по транслиту                  *
   // *************************************************************************
   public function UpdateByTranslit($pdo,$Translit,$contents)
   {
    try 
    {
      $pdo->beginTransaction();
      //\prown\ConsoleLog('1 update='.$Translit); 
      $statement = $pdo->prepare("UPDATE [stockpw] SET [Art] = :Art WHERE [Translit] = :Translit;");
      $statement->execute(["Art"=>$contents,"Translit"=>$Translit]);
      //\prown\ConsoleLog('2 update='.$Translit); 
      $pdo->commit();
    } 
    catch (Exception $e) 
    {
      // Если в транзакции, то делаем откат изменений
      if ($pdo->inTransaction()) 
      {
         $pdo->rollback();
      }
      // Продолжаем исключение
      throw $e;
    }
   }
}
// ************************************************* ArticlesMakerClass.php ***
