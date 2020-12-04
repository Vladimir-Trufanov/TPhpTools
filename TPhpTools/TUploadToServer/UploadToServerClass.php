<?php 
// namespace ttools; 
                                         
// PHP7/HTML5, EDGE/CHROME                      *** UploadToServerClass.php ***

// ****************************************************************************
// * TPhpTools                                     Загрузчик файлов на сервер *
// *                                                                          *
// * v1.0, 04.12.2020                              Автор:       Труфанов В.Е. *
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

class UploadToServer
{

   protected $_uploaded = array();
   protected $_destination;         // каталог для размещения изображения
   protected $_max = 57200;         // максимальный размер файла
   protected $_messages = array();  // массив сообщений по загрузке файла
   protected $_renamed = false;
   protected $_permitted = array(   // MIME-типы изображений
                                 'image/gif',    
	          							'image/jpeg',
								         'image/pjpeg',
								         'image/png');

   public function __construct($path) 
   {
	   if (!is_dir($path) || !is_writable($path)) 
      {
         throw new Exception("$path must be a valid, writable directory.");
      }
	   $this->_destination = $path;
	   $this->_uploaded = $_FILES;
   }
   
   // Переместить временный файл в заданный каталог
   public function move() 
   {
      // Перекидываем запись об одном загруженном файле из $_FILES в одномерный
      // массив для простого доступа к параметрам этого файла
	   $field = current($this->_uploaded);
	   $success = move_uploaded_file($field['tmp_name'], $this->_destination . $field['name']);
      if ($success) 
      {
         $this->_messages[] = $field['name'] . ' uploaded successfully';
      } 
      else 
      {
         $this->_messages[] = 'Could not upload ' . $field['name'];
      }
   }
   
   // Вывести массив сообщений о загрузке файла
   public function getMessages() 
   {
      return $this->_messages;
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
