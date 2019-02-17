<?php
// PHP7/HTML5, EDGE/CHROME                                     *** Main.php ***

// ****************************************************************************
// * Exception                         Обо мне, путешествиях и ... Черногории *
// ****************************************************************************

//                                                   Автор:       Труфанов В.Е.
//                                                   Дата создания:  13.01.2019
// Copyright © 2019 tve                              Посл.изменение: 30.01.2019

// == 1 == Не контроллируемая ошибка
//$f=0;
//$g=1/$f;

// == 2 == Контроллируемая ошибка
$w2e->TriggerUserError('Здесь возникла пользовательская ошибка!');

// == 3 == Не контроллируемая ошибка
//$f=0;
//$g=1/$f;
 
// == 4 == Контроллируемая ошибка
//$w2e->TriggerUserError('Здесь возникла пользовательская ошибка!',2);

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

<?php
try 
{
   echo 'F';
   //$f=0;
   //$g=1/$f;
   $w2e->TriggerUserError('Здесь ошибка!',2);
   echo 'E';

} 
// Можно перехватить и пользовательскую ошибку/сообщение
// catch (E_USER_ERROR $e) {MakeUserMessage($e);}
// Перехватываем ошибку сайта
catch (E_EXCEPTION $e) 
{
   
   echo "Системное исключение2: {$e->getMessage()}";
   echo '<pre>';
   echo $e->getTraceAsString();
   echo '</pre>';
   
   // При необходимости выводим дополнительную информацию
   // Header("Content-type: text/plain");
   // $headers = getallheaders();
   // print_r($headers);
   // print_r($_SERVER);
}
?>
  
  <p>Ut wisis enim ad minim veniam, quis nostrud exerci tution ullamcorper 
  suscipit lobortis nisl ut aliquip ex ea commodo consequat.</p>

 </body>
</html>