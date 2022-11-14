<?php namespace ttools; 
                                         
// PHP7/HTML5, EDGE/CHROME                       *** ArticlesMakerClass.php ***

// ****************************************************************************
// * TPhpTools                                   Построитель материалов сайта *
// *                                                                          *
// * v1.1, 12.11.2022                              Автор:       Труфанов В.Е. *
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

// Подгружаем общие функции класса
require_once("CommonArticlesMaker.php"); 
// Подгружаем модули функций класса, связанные с конкретным сайтом
if (articleSite==tbsIttveme) require_once("CommonIttveMe.php"); 
elseif (articleSite==tbsIttvepw) require_once("CommonIttvePw.php"); 

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

class ArticlesMaker
{
   // ----------------------------------------------------- СВОЙСТВА КЛАССА ---
   protected $basename;             // база материалов: $_SERVER['DOCUMENT_ROOT'].'/itpw';
   protected $username;             // логин для доступа к базе данных
   protected $password;             // пароль
   //protected $_message=Ok;        // сообщение по загрузке файла
   //protected $_uploaded=array();  // $_FILES - данные о загруженном файле
   // ------------------------------------------------------- МЕТОДЫ КЛАССА ---
   public function __construct($basename,$username,$password) 
   {
      // Инициализируем свойства класса
      $this->basename = $basename;
      $this->username = $username;
      $this->password = $password;
      // Трассируем установленные свойства
      //\prown\ConsoleLog('$this->basename='.$this->basename); 
      //\prown\ConsoleLog('$this->username='.$this->username); 
      //\prown\ConsoleLog('$this->password='.$this->password); 
   }
   public function __destruct() 
   {
   }
   // *************************************************************************
   // *                    Открыть соединение с базой данных                  *
   // *************************************************************************
   public function BaseConnect($basename,$username,$password,&$pdo)
   {
      // Получаем спецификацию файла базы данных материалов
      $filename=$basename.'.db3';
      // Создается объект PDO и файл базы данных
      $pathBase='sqlite:'.$filename; 
      // Подключаем PDO к базе
      $pdo = new \PDO(
         $pathBase, 
         $username,
         $password,
         array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION)
      );
   }
   // *************************************************************************
   // *    Создать резервную копию базы данных, построить новую базу данных   *
   // *************************************************************************
   public function BaseFirstCreate() 
   {
      _BaseFirstCreate($this->basename,$this->username,$this->password);
   }
   // *************************************************************************
   // *       Показать пример меню (с использованием smartmenu или без)       *
   // *************************************************************************
   public function ShowSampleMenu() 
   {
      _ShowSampleMenu();
   }
   /*
   // *************************************************************************
   // *              Переместить временный файл в заданный каталог            *
   // *************************************************************************
   public function move($overwrite = false) 
   {
      // По умолчанию "Неопределенная ошибка загрузки файла на сервер"
      $this->message=UnspecErrorUpload;
      // "Каталог для загрузки файла отсутствует"
 	  if (!is_dir($this->_destination ))
      {
         $this->message=\prown\MakeUserError(DirDownloadMissing,$this->_prefix,rvsReturn);
      }
      // "Не разрешена запись в каталог для загрузки файла"
      else if (!is_writable($this->_destination)) 
      {
         $this->message=\prown\MakeUserError(NotWriteToDirectory,$this->_prefix,rvsReturn);
      }
      // "Файл не выбран и не загружен во временный каталог"
      else if (count($this->_uploaded)==0) 
      {
         $this->message=\prown\MakeUserError(FileNotSelectLoad,$this->_prefix,rvsReturn);
      }
      // Выполняем контроль прочих ошибок
      else
      {
	     $field=current($this->_uploaded);
         // Выполняем предварительный контроль параметров временного файла 
         $this->message=$this->checkError($field['name'],$field['error']);
         // Если контроль успешен, то проверяем размер
         if ($this->message==imok)
         {
            $this->message=$this->checkSize($field['name'],$field['size']);
            // Размер подтвержден, анализируем тип файла
            if ($this->message==imok)
            {
               $this->message=$this->checkType($field['name'],$field['type']);
               // Тип подтвержден, выполняем перемещение файла на сервер
               if ($this->message==imok)
               {
                  // Назначаем имя загруженного файла и его тип
                  if ($this->_name=='') $name=$field['name'];
                  else
                  {
                     $this->_type=substr($field['type'],strpos($field['type'],'/')+1);
                     $name=$this->_name.'.'.$this->_type;
                  }
                  // Перемещаем файл и присваиваем назначенное имя
                  $success=move_uploaded_file($field['tmp_name'],$this->_destination.'/'.$name);
                  if ($success)
                  {
                     //\prown\ConsoleLog($name.' uploaded successfully'); 
                  } 
                  else 
                  {
                     // "Не удалось загрузить файл на сервер"
                     $this->message=\prown\MakeUserError(CouldNotUploadTo.': '.$field['name'],$this->_prefix,rvsReturn);
                  }
              }
            }
         }
      }
      return $this->message;
   }
   // *************************************************************************
   // *                   Указать расширение загруженного файла               *
   // *************************************************************************
   public function getExt() 
   {
      return $this->_type;
   }
   */
   // --------------------------------------------------- ВНУТРЕННИЕ МЕТОДЫ ---
   
   // *************************************************************************
   // *     Проверить код ошибки по принятому файлу во временное хранилище    *
   // *************************************************************************
   //protected function checkError($filename,$error) 
   /* 
   * https://www.php.net/manual/ru/features.file-upload.errors.php
   * PHP возвращает код ошибки наряду с другими атрибутами принятого файла. 
   * Он расположен в массиве, создаваемом PHP при загрузке файла, и может быть
   * получен при обращении по ключу error. Другими словами, код ошибки можно 
   * найти в $_FILES['userfile']['error'].
   * 
   * UPLOAD_ERR_OK
   * Значение: 0; Ошибок не возникло, файл был успешно загружен на сервер.
   * UPLOAD_ERR_INI_SIZE
   * Значение: 1; Размер принятого файла превысил максимально допустимый размер,
   * который задан директивой upload_max_filesize конфигурационного файла 
   * php.ini.
   * UPLOAD_ERR_FORM_SIZE
   * Значение: 2; Размер загружаемого файла превысил значение MAX_FILE_SIZE,
   * указанное в HTML-форме.
   * UPLOAD_ERR_PARTIAL
   * Значение: 3; Загружаемый файл был получен только частично.
   * UPLOAD_ERR_NO_FILE
   * Значение: 4; Файл не был загружен.
   * UPLOAD_ERR_NO_TMP_DIR
   * Значение: 6; Отсутствует временная папка.
   * UPLOAD_ERR_CANT_WRITE
   * Значение: 7; Не удалось записать файл на диск.
   * UPLOAD_ERR_EXTENSION
   * Значение: 8; Модуль PHP остановил загрузку файла. PHP не предоставляет 
   * способа определить, какой модуль остановил загрузку файла; в этом может 
   * помочь просмотр списка загруженных модулей спомощью phpinfo().
   */
   
   /*
   {
      switch ($error) 
      {
      case 0:
         // Загрузка файла успешна, просто возвращаем Ok
         return imok;
      case 1:
         // Размер файла превышает максимальный, указанный upload_max_filesize в php.ini
         return \prown\MakeUserError(ExceedUploadMaxPHPINI.': '.
            ini_get('upload_max_filesize'),$this->_prefix,rvsReturn);
      case 2:
         // Размер файла превышает максимальный, указанный в скрытом поле MAX_FILE_SIZE
         return \prown\MakeUserError(ExceedOnМAX_FILE_SIZE.': '.
            \prown\getComRequest($Com='MAX_FILE_SIZE'),$this->_prefix,rvsReturn);
      case 3:
         // Файл загружен частично
         return \prown\MakeUserError(FilePartiallyUploaded.': '.$filename,
            $this->_prefix,rvsReturn);
      case 4:
         // Данные формы загружены, но файл не был указан
         return \prown\MakeUserError(FormLoadFileNotSpecif,$this->_prefix,rvsReturn);
      case 6:
         // Временный каталог отсутствует
         return \prown\MakeUserError(TempoDirIsMissing.': '.
            ini_get('upload_tmp_dir'),$this->_prefix,rvsReturn);
      case 7:
         // Файл невозможно записать на диск
         return \prown\MakeUserError(FileCannotBeWritten,$this->_prefix,rvsReturn);
      case 8:
         // Загрузка остановлена неопределенным PHP-расширением
         return \prown\MakeUserError(LoadStoppedUndefPhp,$this->_prefix,rvsReturn);
      default:
         // Не учтеная ошибка при проверке загрузки
         return \prown\MakeUserError(NotErrorOfCheckError.': '.$error,$this->_prefix,rvsReturn);
      }
      */
   //}
   
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
   // *************************************************************************
   // *                          Проверить MIME-тип файла                     *
   // *************************************************************************
   protected function checkType($filename,$type) 
   {
      // Сюда приходим, когда срабатывает проверка в HTML на MAX_FILE_SIZE
      // и загрузки не происходит, говорим про это
      if (empty($type)) 
      {
         $Result=\prown\MakeUserError(ZeroFileSize,$this->_prefix,rvsReturn);
      }
      // "Недопустимый тип файла" 
      elseif (!in_array($type,$this->_permitted)) 
      {
         $Result=\prown\MakeUserError(IsNotPermitTypeFile.': '.$filename,$this->_prefix,rvsReturn);
      }
      // Отмечаем, что тип файла верный
      else $Result=imok;
      // Возвращаем результат
      return $Result;
   }
   
   // Добавить новые разрешенные типы файлов
   public function addPermittedTypes($types) 
   {
      // Оператором приведения типа реализуем возможность передачи новых
      // MIME-типов через список, например:
      // $upload->addPermittedTypes(array('application/pdf','text/plain'));
      $types = (array) $types;
      $this->isValidMime($types);
      $this->_permitted = array_merge($this->_permitted, $types);
   }
   // Заменить список разрешенных типов файлов
   public function setPermittedTypes($types) 
   {
      $types = (array) $types;
      $this->isValidMime($types);
      $this->_permitted = $types;
   }
   // Проверить переданные значения (исключение, если неверный тип)
   protected function isValidMime($types) 
   {
      // 07.12.2020 Здесь сделать в дальнейшем так, чтобы содержался полный список MIME-типов
      // без добавления на лету (заложить возможность работы с группами типов файлов - изображения,
      // документы ... и пользовательская группа, собираемая через setPermittedTypes)
      $alsoValid = array(
         'image/tiff',
			'application/pdf',
			'text/plain',
			'text/rtf');
      $valid = array_merge($this->_permitted, $alsoValid);
      foreach ($types as $type) 
      {
         if (!in_array($type, $valid)) 
         {
            throw new Exception("$type is not a permitted MIME type");
         }
      }
   }
   // Изменить значение максимально допустимого размера файла
   public function setMaxSize($num) 
   {
      if (!is_numeric($num)) 
      {
         throw new Exception("Maximum size must be a number.");
      }
      $this->_max = (int) $num;
   }
   // Избежать перезаписи существующего файла с таким же именем,
   // для этого: вставить очередное число перед именем загружаемого файла,
   // все пробелы в имени файла заменить на символы подчеркивания
   protected function checkName($name, $overwrite) 
   {
      // Заменяем пробелы символами подчеркивания
      $nospaces = str_replace(' ', '_', $name);
      // В случае отмечаем, что имя файла изменилось
      if ($nospaces != $name) 
      {
         $this->_renamed = true;
      }
      // Если нельзя перезаписывать одноименные файлы,
      // то готовим измененное имя файла
      if (!$overwrite) 
      {
         // Вытаскиваем массив с именами всех файлов и папок в целевом каталоге
         $existing = scandir($this->_destination);
         // Определяем, есть ли имя загруженного файла 
         // в массиве имен файлов и каталогов
         if (in_array($nospaces, $existing)) 
         {
            // Выделяем имя файла и расширение
            $dot = strrpos($nospaces, '.');
            if ($dot) 
            {
               $base = substr($nospaces, 0, $dot);
               $extension = substr($nospaces, $dot);
            } 
            else 
            {
               $base = $nospaces;
               $extension = '';
            }
            // Генерируем имена файлов с очередным номером и проверяем их присутствие в списке.
            // В случае отсутствия в списке запоминаем имя и выходим из цикла
            $i = 1;
            do 
            {
               $nospaces = $base . '_' . $i++ . $extension;
            } 
            while (in_array($nospaces, $existing));
            $this->_renamed = true;
         }
      }
      return $nospaces;
   }
   */
} 

// ************************************************* ArticlesMakerClass.php ***
