<?php namespace ttools; 
                                         
// PHP7/HTML5, EDGE/CHROME                       *** ArticlesMakerClass.php ***

// ****************************************************************************
// * TPhpTools                                   Построитель материалов сайта *
// *                                                                          *
// * v1.1, 05.02.2023                              Автор:       Труфанов В.Е. *
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
 * stylesdir    - каталог стилей элементов разметки и фонтов
 * imgdir       - каталог файлов служебных для сайта изображений
 *    
 * Пример создания объекта класса:
 * 
 * // Указываем место размещения библиотеки прикладных функций TPhpPrown
 * define ("pathPhpPrown",$SiteHost.'/TPhpPrown/TPhpPrown');
 * // Указываем место размещения библиотеки прикладных классов TPhpTools
 * define ("pathPhpTools",$SiteHost.'/TPhpTools/TPhpTools');
 * // Указываем каталоги размещения файлов
 * define("editdir",'ittveEdit');  // файлы, связанные c материалом
 * define("stylesdir",'Styles');   // стили элементов разметки и фонты
 * define("imgdir",'Images');      // служебные для сайта файлы изображений
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
// ----------------------------------------- Ошибки обработки аякс-запросов ---
define ("gncNoCue", 'Статья не найдена в базе'); 

// Подгружаем общие функции класса
require_once("CommonArticlesMaker.php"); 
// Подгружаем модули функций класса, связанные с конкретным сайтом
if (articleSite==tbsIttveme) require_once("CommonIttveMe.php"); 
elseif (articleSite==tbsIttvepw) require_once("CommonIttvePw.php"); 

// Подгружаем нужные модули библиотеки прикладных функций
require_once(pathPhpPrown."/MakeCookie.php");
// Подгружаем нужные модули библиотеки прикладных классов
require_once(pathPhpTools."/CommonTools.php");

class ArticlesMaker
{
   // ----------------------------------------------------- СВОЙСТВА КЛАССА ---
   public $kindMessage;         // Объект вывода сообщений;  
   public $getArti;             // Транслит выбранного материала

   protected $classdir;         // Каталог файлов класса
   protected $editdir;          // Каталог размещения файлов, связанных c материалом
   protected $stylesdir;        // Каталог размещения файлов со стилями элементов разметки
   protected $imgdir;           // Каталог файлов служебных для сайта изображений

   protected $basename;         // База материалов: $_SERVER['DOCUMENT_ROOT'].'/itpw';
   protected $username;         // Логин для доступа к базе данных
   protected $password;         // Пароль
   protected $fileStyle;        // Файл стилей
   // ------------------------------------------------------- МЕТОДЫ КЛАССА ---
   public function __construct($basename,$username,$password,$note) 
   {
      // Инициализируем свойства класса
      $this->editdir     = editdir; 
      $this->stylesdir   = stylesdir; 
      $this->imgdir      = imgdir; 
      $this->classdir    = pathPhpTools.'/TArticlesMaker';
      
      $this->basename    = $basename;
      $this->username    = $username;
      $this->password    = $password;
      $this->kindMessage = NULL;
      
      if (isset($_COOKIE['PunktMenu'])) 
      $this->getArti=\prown\MakeCookie('PunktMenu');
      else $this->getArti=NULL; 
      // Выполняем действия на странице до отправления заголовков страницы: 
      // (устанавливаем кукисы и т.д.)                  
      $this->ZeroEditSpace();
      // Трассируем установленные свойства
      //\prown\ConsoleLog('$this->basename='.$this->basename); 
   }
   // *************************************************************************
   // *           Спрятать в __destruct обработку клика выбора раздела        *
   // *                    (при назначении новой статьи)                      *
   // *************************************************************************
   public function __destruct() 
   {
      require_once(pathPhpTools."/JsTools.php");
      ?> 
      <script>
      pathPhpTools="<?php echo pathPhpTools;?>";
      pathPhpPrown="<?php echo pathPhpPrown;?>";
      gncNoCue="<?php echo gncNoCue;?>"; 

      // **********************************************************************
      // *       Проверить целостность базы данных по 16 очередным записям    *
      // **********************************************************************
      function GetPunktTestBase()
      {
         // Выбираем последний проверенный uid
         TestPoint=Number(localStorage.getItem('TestPoint'));
         if (Number.isNaN(TestPoint)) TestPoint=0;
         //console.log('в наче '+TestPoint);
         // Делаем запрос на определение наименования раздела материалов
         pathphp="TestBase.php";
         $.ajax({
            url: pathphp,
            type: 'POST',
            data: {TestPoint:TestPoint, pathTools:pathPhpTools, pathPrown:pathPhpPrown},
            // Выводим ошибки при выполнении запроса в PHP-сценарии
            error: function (jqXHR,exception) {SmarttodoError(jqXHR,exception)},
            // Обрабатываем ответное сообщение
            success: function(message)
            {
               // Вырезаем из запроса чистое сообщение
               messa=FreshLabel(message);
               // Получаем параметры ответа
               parm=JSON.parse(messa);
               // Если ошибка, то выводим сообщение
               if (parm.error==true) Error_Info(parm.messa);
               // Иначе меняем значение проверенного uid-а
               else 
               {
                  //console.log('в коне '+parm.TestPoint);
                  // Отмечаем последний проверенный uid
                  localStorage.setItem('TestPoint',parm.TestPoint);
                  // Выводим сообщение, что все хорошо
                  // Info_Info(parm.messa); 
               }
            }
         });
      }
      // **********************************************************************
      // *        Задать обработчик аякс-запроса по удалению материала        *
      // **********************************************************************
      function UdalitMater(Uid)
      {
         $('#DialogWind').dialog
         ({
            buttons:[{text:"OK",click:function(){xUdalitMater(Uid)}}]
         });
         htmlText="Удалить выбранный материал по "+Uid+"?";
         Notice_Info(htmlText,"Удалить материал");
      }
      function xUdalitMater(Uid)
      {
         // Выводим в диалог предварительный результат выполнения запроса
         htmlText="Удалить статью по "+Uid+" не удалось!";
         $('#DialogWind').html(htmlText);
         // Выполняем запрос на удаление
         pathphp="deleteArt.php";
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
               // Выводим результат выполнения
               if (parm.NameArt==gncNoCue) htmlText=parm.NameArt+' Uid='+Uid;
               else htmlText=parm.NameArt;
               $('#DialogWind').html(htmlText);
            }
         });
         // Удаляем кнопку из диалога и увеличиваем задержку до закрытия
         delayClose=1500;
         $('#DialogWind').dialog
         ({
            buttons:[],
            hide:{effect:"explode",delay:delayClose,duration:1000,easing:'swing'},
            title: "Удаление материала",
         });
         // Закрываем окно
         $("#DialogWind").dialog("close");
         // Перезагружаем страницу через 4 секунды
         setTimeout(function() {location.reload();}, 4000);
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
      </script>
      <?php
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
      CompareCopyRoot('ArticlesMaker.css',$this->classdir,$this->stylesdir);
      CompareCopyRoot('bgnoise_lg.jpg',$this->classdir,$this->imgdir);
      CompareCopyRoot('icons.png',$this->classdir,$this->imgdir);
      CompareCopyRoot('getNameCue.php',$this->classdir);
      CompareCopyRoot('deleteArt.php',$this->classdir);
      CompareCopyRoot('TestBase.php',$this->classdir);
   }
   // *************************************************************************
   // *       Подключить объект класса TNotice, который будет заниматься      *
   // *                        выводом всех сообщений                         *
   // *************************************************************************
   public function setKindMessage($note)
   {
      $this->kindMessage = $note;
   }
   private function Alert($messa)
   {
      if ($this->kindMessage==NULL) \prown\Alert($messa);
      else $this->kindMessage->Info($messa); 
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
   // *        Установить стили пространства редактирования материала         *
   // *************************************************************************
   public function IniEditSpace()
   {
      // Настраиваемся на файл стилей
      // <link rel="stylesheet" type="text/css" href="/Styles/ArticlesMaker.css">
      $this->fileStyle='/'.$this->stylesdir.'/ArticlesMaker.css';
      echo '<link rel="stylesheet" type="text/css" href="'.$this->fileStyle.'">';

      // Настраиваем фоны графическими файлами
      $bgnoise_lg=$this->imgdir.'/bgnoise_lg.jpg';
      $icons=$this->imgdir.'/icons.png';
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
   public function getPunktMenu($pdo) 
   {
      // Проверяем целостность базы данных (по 10 очередным записям) 
      // ВРЕМЕННО ЗДЕСЬ
      ?> <script> 
         $(document).ready(function() {GetPunktTestBase();})
      </script> <?php
      // Готовим выбор материала
      $lvl=-1; $cLast='+++'; $nLine=0; $cli=""; 
      ShowCaseMe($pdo,1,1,$cLast,$nLine,$cli,$lvl);
   }
   // *************************************************************************
   // *    Создать резервную копию базы данных, построить новую базу данных   *
   // * ($aCharters='-',подключить массив со структурой основной базы данных) *
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
      // Так как функция запускается на фазе построения сайта ZERO, то по
      // ошибке возвращается сообщение об этом, иначе возвращается "Все хорошо у меня"
      $ErrMessage=imok;
      // Инициируем возвращаемые данные
      $pid=0; $uid=0; 
      $NameGru='Материал для редактирования не выбран!'; 
      $contents='Новый материал'; 
      $NameArt=''; $DateArt='';
      if ($getArti==NULL) $ErrMessage='Транслит материала не определен';
      else
      {
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
            $table=$this->SelRecord($pdo,$pid); 
            if (count($table)>0) $NameGru=$table[0]['NameArt'];
            else $ErrMessage='Для статьи с Uid='.$uid.' неверный идентификатор группы: Pid='.$pid; 
         }
         // Если больше одной записи, то диагностируем ошибку
         if ($count>1) $ErrMessage="В группе '".$NameGru."' статья '".$NameArt."' c дублированным транслитом: ".$getArti;
         // Если не найдено записей, то диагностируем ошибку.
         // На странице ситуация очевидна (на 27.01.2023)
         else if ($count<1) $ErrMessage='Не найдено записей по транслиту: '.$getArti;
      }
      return $ErrMessage;
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
   // * Удалить запись по идентификатору: в случае успешного удаления функция *
   // *     возвращает сообщение, что все хорошо, иначе сообщение об ошибке   *
   // *************************************************************************
   public function DelRecord($pdo,$UnID)
   {
     try
     {
       $pdo->beginTransaction();
       $cSQL='DELETE FROM stockpw WHERE uid='.$UnID;
       $stmt = $pdo->query($cSQL);
       $pdo->commit();
       $messa=imok;
     } 
     catch (\Exception $e) 
     {
       $messa=$e->getMessage();
       if ($pdo->inTransaction()) $pdo->rollback();
     }
     return $messa;
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
