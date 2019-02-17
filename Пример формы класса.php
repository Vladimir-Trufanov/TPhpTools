<?php
// PHP7/HTML5, EDGE/CHROME                                     *** Main.php ***

// ****************************************************************************
// * Exception                         Обо мне, путешествиях и ... Черногории *
// ****************************************************************************

//                                                   Автор:       Труфанов В.Е.
//                                                   Дата создания:  13.01.2019
// Copyright © 2019 tve                              Посл.изменение: 30.01.2019

//require_once $_SERVER['DOCUMENT_ROOT']."/TException/ExceptionClass.php";
//require_once $_SERVER['DOCUMENT_ROOT']."/TException/UserMessages.php";


class User
{
   protected $_user_id;
   protected $_user_email;
   protected $_user_password;
	  
   public function __construct($user_id)
   {
      $user_record = self::_getUserRecord($user_id);
      $this->_user_id = $user_record['id'];
      $this->_user_email = $user_record['email'];
      $this->_user_password = $user_record['password'];
   }
	  
   //Данные функции сформируем позже
   public function __get($value) {}
   public function __set($name, $value) {}
  
   private static function _getUserRecord($user_id)
   {
      $user_id = self::_validateUserId($user_id);
      //Для урока будем использовать модель записи для представления передачи данных в базу
      $user_record = array();
      switch($user_id) 
      {
      case 1:
         $user_record['id'] = 1;
         $user_record['email'] = 'nikko@example.com';
         $user_record['password'] = 'i like croissants';
         break;
  
      case 2:
         $user_record['id'] = 2;
         $user_record['email'] = 'john@example.com';
         $user_record['password'] = 'me too!';
         break;
  
      case 0:
          break;
  
      case 'error':
          //имитируем неизвестное исключение от какой-нибудь используемой библиотеки SQL:
          throw new Exception('Ошибка библиотеки SQL!');
          break;
      }
	  
      return $user_record;
   }
   
   private static function _validateUserId($user_id)
	{
	   if(!is_numeric($user_id) && $user_id != 'error') 
      {
	     //throw new Exception('Ой! Здесь что-то не так с идентификатором пользователя');
        //trigger_error('Ой! Здесь что-то не так с идентификатором пользователя',E_USER_ERROR); //E_USER_NOTICE);
        TriggerUserMessage('Оjй! Здесь что-то не так с идентификатором пользователя');
        $user_id=0;
	   }
	   return $user_id;
	}
}

//$w2e = new Exceptionizer(E_ALL);
//----
//try 
//{
   //$f=0;
   //$g=1/$f;

   // Сначала создаем пользователя с номером один
   //$user = new User(1);
   // Затем намеренно делаем ошибку
   //$user2 = new User('not numeric');
   $w2e->res('ВооАП?');

   TriggerUserMessage('Здесь возникла пользовательская ошибка!');
 
//} 
//----
//catch(Exception $e) 
///{
//   echo "Исключение: {$e->getMessage()}";
//   echo '<pre>';
//   echo $e->getTraceAsString();
//   echo '</pre>';
//}

//} 
// Перехватываем пользовательскую ошибку/сообщение
//catch (E_USER_ERROR $e) 
//{
//   echo "Пользовательское исключение: {$e->getMessage()}";
//   echo '<pre>';
//   echo $e->getTraceAsString();
//   echo '</pre>';
//}



//----


// Перехватываем ошибку сайта

//----



?>

<!DOCTYPE HTML>
<html>
 <head>
  <title>Исключения в PHP</title>
  <meta charset="utf-8">
 </head>
 <body>

  <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diem 
  nonummy nibh euismod tincidunt ut lacreet dolore magna aliguam erat volutpat.</p>
  <p>Ut wisis enim ad minim veniam, quis nostrud exerci tution ullamcorper 
  suscipit lobortis nisl ut aliquip ex ea commodo consequat.</p>

 </body>
</html>