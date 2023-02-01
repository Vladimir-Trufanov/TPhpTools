<?php namespace ttools;

// PHP7/HTML5, EDGE/CHROME                              *** CommonTools.php ***
// ****************************************************************************
// * TPhpTools                                             Блок общих функций *
// *                                                                          *
// * v1.0, 01.02.2023                               Автор:      Труфанов В.Е. *
// * Copyright © 2023 tve                           Дата создания: 09.01.2023 *
// ****************************************************************************

/**
 *    Программный модуль CommonTools содержит в себе небольшие функции, которые
 * используются в объектах классов библиотеки TPhpTools, но могут быть 
 * использованы при написании сайта на PHP или в backend-разработке проекта.
 *
 *    Функции CompareCopyRoot выбирает файл из каталога класса. Проверяет его 
 * существование, размер и время создания или модификации в корневом каталоге 
 * сайта (или в заданном подкаталоге следующего уровня). При различиях между
 * ними перебрасываетосит файл из каталога класса в целевой каталог.
 *
**/

// ****************************************************************************
// *    Выбрать файл из каталога класса. Проверить его существование, размер  *
// *      и время создания или модификации в корневом каталоге сайта (или в   *
// *      заданном подкаталоге следующего уровня). При различиях между ними   *
// *         перебрасываетосит файл из каталога класса в целевой каталог      *
// ****************************************************************************
function CompareCopyRoot($Namef,$classDir,$toDir='')
{
   // Если каталог, в который нужно перебросить файл - корневой
   if ($toDir=='') $thisdir=$toDir;
   // Если каталог указан относительно корневого (без обратных слэшей !!!)
   else $thisdir=$toDir.'/';
   // Проверяем существование, параметры и перебрасываем файл
   $fileStyle=$thisdir.$Namef;
   clearstatcache($fileStyle);
   $filename=$classDir.'/'.$Namef;
   clearstatcache($filename);
   if ((!file_exists($fileStyle))||
   (filesize($filename)<>filesize($fileStyle))||
   (filemtime($filename)>filemtime($fileStyle))) 
   {
      if (!copy($filename,$fileStyle))
      \prown\Alert('Не удалось скопировать файл класса: '.$filename); 
   }
}
// ****************************************************************************
// *                Вывести в указанный лог-файл заданную строку              *
// ****************************************************************************
function PutString($String,$filenameIn=NULL)
{
   if ($filenameIn==NULL) $filename="LogName.txt";
   else $filename=$filenameIn;
   $fp = fopen($filename,"a+");
   if (flock($fp,LOCK_EX)) 
   { 
      fputs($fp,$String."\n");
      flock($fp, LOCK_UN); 
      fclose($fp);
   } 
   else \prown\Alert('Не удалось запереть файл: '.$filename); 
}
// ****************************************************************************
// *        Выбрать указанную страницу сайта и сопутствующую информацию       *
// *                при ошибке чтения страницы c помощью CURL                 *
// ****************************************************************************
/**
 * "Как получить контент сайта на PHP": 
 *            http://my-skills.ru/public/kak_poluchit_kontent_sayta_na_PHP.html
 *            
 * Неоспоримыми преимуществами в функционале пользуется библиотека или можно 
 * сказать модуль PHP - CURL. Для полноценного контролируемого получения 
 * контента здесь есть множество разных дополнений. Это и практически 
 * полноценный эмулятор браузерного обращения к сайту, работа скрипта через 
 * proxy с приватной идентификацией и многое другое. Ниже показана функция 
 * получения контента с помощью CURL.
 * 
 * Для использования функций cURL в PHP необходимо установить пакет libcurl. 
 * Необходимо использовать версию libcurl 7.10.5 или новее. Начиная с PHP 7.3.0, 
 * требуется версия 7.15.5 или новее. Начиная с PHP 8.0.0, требуется версия 
 * 7.29.0 или новее.
 *
**/
function curl($url,&$varerr)
{
   $uagent = "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_7; en-US) AppleWebKit/534.16 (KHTML, like Gecko) Chrome/10.0.648.205 Safari/534.16";
   // Инициируем работу cUrl   
   $ch = curl_init($url);
   // Устанавливаем необходимые параметры
   curl_setopt($ch,CURLOPT_URL,$url); 
   curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);   // возвращаем веб-страницу вместо вывода напрямую
   curl_setopt($ch,CURLOPT_HEADER,false);          // не включаем заголовки в вывод
   @curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);  // следуем любому заголовку Location, отправленному сервером в своём ответе
   curl_setopt($ch,CURLOPT_ENCODING, "");          // декодировать запрос любой кодировки: "identity","deflate","gzip" 
   curl_setopt($ch,CURLOPT_USERAGENT,$uagent);  
   curl_setopt($ch,CURLOPT_TIMEOUT,10);            // разрешить количество секунд для выполнения cURL-функций
   curl_setopt($ch,CURLOPT_MAXREDIRS,10);          // останавливаться после 10-ого редиректа
   // Выполняем заданную операцию
   $content=curl_exec($ch);
   // Выбираем данные о возможной ошибке
   $err=curl_errno($ch);
   if ($err<>0) 
   {
      // Выбираем массив с информацией об ошибке
      $header=curl_getinfo($ch);
      // Дополняем массив
      $errmsg=curl_error($ch);
      $header['errno']   = $err;
      $header['errmsg']  = $errmsg;
      $header['content'] = $content; 
      $content='';
   }
   else $header=array();
   // Завершаем сеанс работы и возвращаем результат
   curl_close($ch);
   $varerr=var_export($header,true);
   return $content;
}
// ****************************************************************************
// *        Выбрать указанную страницу сайта и сопутствующую информацию       *
// *               при ошибке чтения страницы c помощью СОКЕТОВ               *
// ****************************************************************************
/**
 * Получение контента средствами PHP. Данная функция использует встроенные в 
 * PHP функции для работы с сокетами, которые предназначены для соединения 
 * клиента с сервером: 
 *                         https://i-leon.ru/получение-контента-средствами-php/
 *            
 * Входящими параметрами являются: $url — строка, содержащая URL http-протокола,
 * $user_agent — строка с любым юзер-агентом (если пропустить параметр или 
 * установить его в null — user_agent будет как в IE). Константа 
 * MAX_REDIRECTS_NUM устанавливает количество разрешенных редиректов 
 * (поддерживаются 301 и 302 редиректы).
 * 
 * Функция может быть вызвана для просмотра страницы с.о:
 * // Выбираем текущую страницу
 * $url=$this->urlHome;
 * // Готовим параметры и выбираем страницу
 * $user_agent='MySuperBot 1.02';
 * $URL_OBJ = abi_get_url_object($url, $user_agent);
 * if( $URL_OBJ )
 * {
 *    $CONTENT = $URL_OBJ['content'];
 *    $HEADER = $URL_OBJ['header'];
 *    $TITLE = $URL_OBJ['title'];
 *    $DESCRIPTION = $URL_OBJ['description'];
 *    $KEYWORDS = $URL_OBJ['keywords'];
 *    $TIME_REQUEST = $URL_OBJ['time'];
 *    \prown\ConsoleLog('$TITLE ='.$TITLE);
 *    PutString($CONTENT,'proba.txt');
 * }
 * else print 'Запрашиваемая страница недоступна.';
 *
 * можно получить значения любого мета-тега. Для этого можно воспользоваться 
 * следующим кодом:
 *    preg_match ("/charset=(.*?)\"/is", $CONTENT, $char);
 *    $charset=$char[1];
 * 
**/
function abi_get_url_object($url,$user_agent=null)
{
   define('ABI_URL_STATUS_UNSUPPORTED', 100);
   define('ABI_URL_STATUS_OK', 200);
   define('ABI_URL_STATUS_REDIRECT_301', 301);
   define('ABI_URL_STATUS_REDIRECT_302', 302);
   define('ABI_URL_STATUS_NOT_FOUND', 404);
   
   define('MAX_REDIRECTS_NUM',4);  // количество попыток открыть сокет
   
   // Получаем массив текущего времени из двух элементов
   // (microtime() returns a string in the form "msec sec")
   $TIME_START=explode(' ',microtime());
   //var_dump($TIME_START);
   // Инициируем начальные значения
   $TRY_ID = 0;
   $URL_RESULT = false;
   // Выполняем выборку контента заданной страницы (делаем 4 попытки)
   do
   {
      // Разбираем URL и возвращаем его компоненты по следующему образцу
      // следующей url-строки:
      //            $url_str="https://webfanat.com:8083/article_id?id=136#136";
      // В parse_url мы передаем нашу url строку и на выходе получаем 
      // ассоциативный массив содержащий следующие пары свойств и значений.
      //    scheme => https,       host  => webfanat.com, port     => 8083,
      //    path   => /article_id, query => id=136,       fragment => 136
      $URL_PARTS = @parse_url($url);
      if( !is_array($URL_PARTS))
      {
         // Если не удалось распарсить, завершаем работу
         break;
      };
      // Выбираем/назначаем элементы для обращения через сокеты
      $URL_SCHEME=(isset($URL_PARTS['scheme']))?$URL_PARTS['scheme']:'http';
      $URL_HOST=(isset($URL_PARTS['host']))?$URL_PARTS['host']:'';
      $URL_PATH=(isset($URL_PARTS['path']))?$URL_PARTS['path']:'/';
      $URL_PORT=(isset($URL_PARTS['port']))?intval($URL_PARTS['port']):80;

      if(isset($URL_PARTS['query'])&&$URL_PARTS['query']!='')
      {
         $URL_PATH.='?'.$URL_PARTS['query'];
      };
      $URL_PORT_REQUEST=( $URL_PORT == 80 )?'':":$URL_PORT";
      /*
      \prown\ConsoleLog('$URL_PORT_REQUEST ='.$URL_PORT_REQUEST); 
      */
      // Формируем get-запрос для сокета
      $USER_AGENT=($user_agent== null)?'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)':strval($user_agent);
      $GET_REQUEST="GET $URL_PATH HTTP/1.0\r\n"
      ."Host: $URL_HOST$URL_PORT_REQUEST\r\n"
      ."Accept: text/plain\r\n"
      ."Accept-Encoding: identity\r\n"
      ."User-Agent: $USER_AGENT\r\n\r\n";
      // Открываем сокет
      $SOCKET_TIME_OUT = 30;
      $SOCKET = @fsockopen($URL_HOST, $URL_PORT, $ERROR_NO, $ERROR_STR, $SOCKET_TIME_OUT);
      if ($SOCKET)
      {
         if (fputs($SOCKET,$GET_REQUEST))
         {
            socket_set_timeout($SOCKET, $SOCKET_TIME_OUT);
            // Выбираем заголовок
            $header = '';
            $SOCKET_STATUS = socket_get_status($SOCKET);
            while (!feof($SOCKET) && !$SOCKET_STATUS['timed_out'])
            {
               $temp=fgets($SOCKET, 128);
               if(trim($temp) == '') break;
               $header .= $temp;
               $SOCKET_STATUS = socket_get_status($SOCKET);
            };
            //--- get server code ---
            if (preg_match('~HTTP\/(\d+\.\d+)\s+(\d+)\s+(.*)\s*\\r\\n~si', $header, $res))
            $SERVER_CODE = $res[2];
            else break;
            if ($SERVER_CODE == ABI_URL_STATUS_OK)
            {
               //--- read content ---
               $content = '';
               $SOCKET_STATUS = socket_get_status($SOCKET);
               while (!feof($SOCKET) && !$SOCKET_STATUS['timed_out'])
               {
                  $content .= fgets($SOCKET, 1024*8);
                  $SOCKET_STATUS = socket_get_status($SOCKET);
               };
               //--- time results ---
               $TIME_END = explode(' ', microtime());
               $TIME_TOTAL = ($TIME_END[0]+$TIME_END[1])-($TIME_START[0]+$TIME_START[1]);
               //--- output ---
               $URL_RESULT['header'] = $header;
               $URL_RESULT['content'] = $content;
               $URL_RESULT['time'] = $TIME_TOTAL;
               $URL_RESULT['description'] = '';
               $URL_RESULT['keywords'] = '';
               //--- title ---
               $URL_RESULT['title']=( preg_match('~<title>(.*)<\/title>~U', $content, $res))?strval($res[1]):'';
               //--- meta tags ---
               if (preg_match_all('~<meta\s+name\s*=\s*["\']?([^"\']+)["\']?\s+content\s*=["\']?([^"\']+)["\']?[^>]+>~', $content, $res, PREG_SET_ORDER)>0)
               {
                  foreach ($res as $meta) 
                  $URL_RESULT[strtolower($meta[1])]=$meta[2];
               };
            }
            elseif ($SERVER_CODE == ABI_URL_STATUS_REDIRECT_301 || $SERVER_CODE == ABI_URL_STATUS_REDIRECT_302)
            {
               if( preg_match('~location\:\s*(.*?)\\r\\n~si', $header, $res))
               {
                  $REDIRECT_URL = rtrim($res[1]);
                  $URL_PARTS = @parse_url($REDIRECT_URL);
                  if (isset($URL_PARTS['scheme']) && isset($URL_PARTS['host']))
                  $url = $REDIRECT_URL;
                  else
                  $url = $URL_SCHEME.'://'.$URL_HOST.'/'.ltrim($REDIRECT_URL, '/');
               }
               else
               {
                  break;
               };
            };
         }; // GET request is OK
         fclose($SOCKET);
      } // socket open is OK
      else
      {
         break;
      };
      $TRY_ID++;
   }
   while($TRY_ID<=MAX_REDIRECTS_NUM && $URL_RESULT===false);
   return $URL_RESULT;
};
// ******************************************************** CommonTools.php *** 
