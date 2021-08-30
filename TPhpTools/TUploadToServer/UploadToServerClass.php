<?php namespace ttools; 
                                         
// PHP7/HTML5, EDGE/CHROME                      *** UploadToServerClass.php ***

// ****************************************************************************
// * TPhpTools             Загрузчик файлов на сервер из временного хранилища *
// *                                                                          *
// * v2.0, 25.08.2021                              Автор:       Труфанов В.Е. *
// * Copyright © 2020 tve                          Дата создания:  03.12.2020 *
// ****************************************************************************

/**
 * Класс FixLoadTimer обеспечивает расчет и регистрацию текущего, среднего,
 * наибольшего и наименьшего времени загрузки страницы сайта. По умолчанию 
 * определенные данные записываются в памяти браузера LocalStorage. 
 * Если браузером LocalStorage не поддерживается, то расчитанные значения 
 * записываются в кукисы. 
**/

// Свойства:
//
// $FltLead - команда управления передачей данных. По умолчанию fltNotTransmit,
//            то есть данные о загрузке не передаются для контроля ни в кукисы, 
// ни в консоль, а только записываются в LocalStorage. Если LocalStorage,
// браузером не поддерживается, то данные будут записываться в кукисы при 
// установке свойства $FltLead в значение fltSendCookies или fltAll 
// $Page - название страницы сайта;
// $Uagent - браузер пользователя;

// ----------------------- Константы управления передачей данных о загрузке ---
define ("fltNotTransmit",  0); // данные не передаются  
define ("fltWriteConsole", 1); // записываются в консоль
define ("fltSendCookies",  2); // отправляются в кукисы
define ("fltAll",          3); // записываются в консоль, отправляются в кукисы  

// Подключаем модули библиотек прикладных функций и классов
require_once $TPhpTools."/iniErrMessage.php";
require_once $TPhpPrown."/iniConstMem.php";
require_once $TPhpPrown."/MakeUserError.php";

class UploadToServer
{
   // ----------------------------------------------------- СВОЙСТВА КЛАССА ---
   protected $_destination;         // каталог для размещения изображения
   protected $_max=57200;           // максимальный размер файла
   protected $_message=Ok;          // сообщение по загрузке файла
   protected $_modemess=rvsReturn;  // массив сообщений по загрузке файла
   protected $_permitted=array(     // разрешенные MIME-типы (здесь для изображений)
      'image/gif','image/jpeg','image/jpg','image/png');
   protected $_renamed=false;       // "имя загруженного файла изменилось"
   protected $_uploaded=array();    // $_FILES - данные о загруженном файле
   // ------------------------------------------------------- МЕТОДЫ КЛАССА ---
   public function __construct($path) 
   {
      $this->_destination = $path;
	   $this->_uploaded = $_FILES;
   }
   // Переместить временный файл в заданный каталог
   public function move($overwrite = false) 
   {
      $this->message=Ok;
      // "Каталог для загрузки файла отсутствует"
 	   if (!is_dir($this->_destination ))
      {
         $this->message=\prown\MakeUserError(DirDownloadMissing,'TPhpTools',rvsReturn);
      }
      // "Не разрешена запись в каталог для загрузки файла"
      else if (!is_writable($this->_destination)) 
      {
         $this->message=\prown\MakeUserError(NotWriteToDirectory,'TPhpTools',rvsReturn);
      }
      else
      {
         //echo 'Запись разрешена!';
      }
      
      
      
      //echo '<br>***<br>'; 
      /*
      // Перекидываем запись об одном загруженном файле из $_FILES в одномерный
      // массив для простого доступа к параметрам этого файла
	   $field = current($this->_uploaded);
      $OK = $this->checkError($field['name'], $field['error']);
      if ($OK) 
      {
         $sizeOK = $this->checkSize($field['name'], $field['size']);
         $typeOK = $this->checkType($field['name'], $field['type']);
         if ($sizeOK && $typeOK) 
         {
         	$name = $this->checkName($field['name'], $overwrite);
            $success = move_uploaded_file($field['tmp_name'],$this->_destination.$name);
            if ($success)
            {
               $message=$field['name'].' uploaded successfully';
               if ($this->_renamed) 
               {
                  $message.=" and renamed $name";
               }
               $this->_messages[]=$message;
            } 
            else 
            {
               $this->_messages[] = 'Could not upload ' . $field['name'];
            }
         }
      }
      */
      return $this->message;
   }
   
   // Вывести массив сообщений о загрузке файла
   public function getMessages() 
   {
      return $this->_messages;
   }
   // Перевести размер файла в байтах в КБайты
   public function getMaxSize() 
   {
      return number_format($this->_max/1024, 1).'kB';
   }
   // Сформировать сообщение по коду ошибки
   protected function checkError($filename, $error) 
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
   {
      switch ($error) 
      {
      case 0:
         // Загрузка файла успешна, просто возвращаем true
         return true;
      case 1:
         // Размер файла превышает максимальный, указанный в php.ini
         $this->_messages[] = "$filename exceeds maximum size on PHP.INI: " . $this->getMaxSize();
      case 2:
         // Размер файла превышает максимальный, указанный в скрытом поле MAX_FILE_SIZE
         $this->_messages[] = "$filename exceeds maximum size on MAX_FILE_SIZE: " . $this->getMaxSize();
         return true;
      case 3:
         // Файл загружен частично
         $this->_messages[] = "Party uploading $filename. Please try again.";
         return false;
      case 4:
         // Данные формы загружены, но файл не был указан
         $this->_messages[] = 'No file selected.';
         return false;
      case 6:
         // Временная папка отсутствует
         $this->_messages[] = 'Временная папка отсутствует.';
         return false;
      case 7:
         // Файл невозможно записать на диск
         $this->_messages[] = 'Файл невозможно записать на диск.';
         return false;
      case 8:
         // Загрузка остановлена неопределенным PHP-расширением
         $this->_messages[] = 'Загрузка остановлена неопределенным PHP-расширением.';
         return false;
      default:
         $this->_messages[] = 'System error['.$error.'] uploading $filename. Contact webmaster.';
		return false;
      }
   }
   // Проверить размер файла
   protected function checkSize($filename, $size) 
   {
      if ($size == 0)
      {
         // Отмечаем ошибочным сообщением то, что файл слишком большой или не выбран
         $this->_messages[] = "$filename ".'слишком большой или не выбран.';
         return false;
      } 
      elseif ($size > $this->_max) 
      {
         // Проверяем возможный обход скрытого задания максимального размера 
         // файла через MAX_FILE_SIZE. 
         $this->_messages[] = "$filename exceeds maximum size: " . $this->getMaxSize();
         return false;
      } 
      else 
      {
         return true;
      }
   }
   // Проверить MIME-тип
   protected function checkType($filename, $type) 
   {
      if (empty($type)) 
      {
         // Некрасиво! Сюда приходим, когда срабатывает проверка в HTML на MAX_FILE_SIZE
         // и загрузки не происходит, говорим про это
         $this->_messages[] = "Не произошло загрузки файла из-за превышения размера по MAX_FILE_SIZE.";
         return false;
      } 
      elseif (!in_array($type, $this->_permitted)) 
      {
         $this->_messages[] = "$filename is not a permitted type of file.";
         return false;
      } 
      else 
      {
         return true;
      }
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
   // *************************************************************************
   // *  Проинициализировать параметры php.ini для управления выводом ошибок  *
   // *************************************************************************
   private function InisetErrors()
   {
   // Определяем режим вывода ошибок:
   //   если display_errors = on, то в случае ошибки браузер получит html 
   // c текстом ошибки и кодом 200
   //   если же display_errors = off, то для фатальных ошибок код ответа будет 500
   // и результат не будет возвращён пользователю, для остальных ошибок – 
   // код будет работать неправильно, но никому об этом не расскажет
   ini_set('display_errors','Off');
   // Определяем режим вывода ошибок при запуске PHP:
   //   если = on, то даже при включённом display_errors возникающие ошибки во 
   // время запуска PHP, не будут отображаться. 
   ini_set('display_startup_errors','Off');
   // Определяем ведение журнала, в котором будут сохраняться сообщения об ошибках.
   // Это может быть журнал сервера или error_log. Применимость этой настройки 
   // зависит от конкретного сервера.
   //   При работе на готовых работающих web сайтах следует протоколировать 
   // ошибки там, где они отображаются. Настойчиво рекомендуем включать директиву 
   // display_startup_errors только для отладки.
   ini_set('log_errors','On');
   ini_set('error_log','log.txt');
   // Определяем типы выводимых ошибок
   // (здесь указываем все, кроме устаревающих)
   error_reporting(E_ALL & ~E_DEPRECATED);
   }
   
   // .HTACCESS
   // ## Устанавливаем кодировку сайта по умолчанию
   // AddDefaultCharset utf-8
   // ## Определяем, что будет использоваться кукис для хранения идентификатора 
   // ## сессии на стороне клиента. on[boolean] = "включено".
   // php_flag session.use_cookies on
   // php_flag session.use_cookies on
   // php_value session.cookie_lifetime 10800
   // 
   // .USER.INI
   // ; Определяем, что будет использоваться кукис для хранения идентификатора 
   // ; сессии на стороне клиента. 1[boolean] = "включено".
   // session.use_cookies = 1
   // session.cookie_lifetime = 10803
   // ; Определяем, что сессию автоматически при старте не запускать.
   // session.auto_start = 0



   
   
   
   
} 

// ************************************************ UploadToServerClass.php ***
