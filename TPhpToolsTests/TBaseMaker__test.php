<?php
                                         
// PHP7/HTML5, EDGE/CHROME                         *** TBaseMaker__test.php ***

// ****************************************************************************
// * TPhpTools                Главный модуль тестирования класса TBaseMaker - *
// *                               обслуживателя баз данных SQLite3 через PDO *
// *                                                                          *
// * v1.0, 17.03.2021                              Автор:       Труфанов В.Е. *
// * Copyright © 2020 tve                          Дата создания:  18.12.2020 *
// ****************************************************************************

MakeTitle("TBaseMaker",'');
//filemtime('генерируем отладочное исключение'); 

// Начинаем протоколировать тесты
SimpleMessage();
      
// Проверяем существование и удаляем файл базы данных 
$filename=$_SERVER['DOCUMENT_ROOT'].'/basemaker.db3';
PointMessage('Проверяется существование и удаляется старый файл базы данных:');  
SimpleMessage(); PointMessage('--- '.$filename);  
UnlinkFile($filename);
OkMessage();

PointMessage('Создается объект PDO и файл тестовой базы данных');  // "Калорийность некоторых продуктов"
$pathBase='sqlite:'.$filename; 
$username='tve';
$password='23ety17';     
// Подключаем PDO к базе
$pdo = new PDO(
   $pathBase, 
   $username,
   $password,
   array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
);
OkMessage();
      
PointMessage('Проверяются настройки целостности по внешним ключам:'); 
// (по запросу PRAGMA:foreign_keys)                                    
PragmaBaseTest($pdo,$shellTest);
OkMessage();
   
PointMessage('Через PDO строятся таблицы и объект PDO уничтожается');                                    
CreateBaseTest($pdo);
unset($pdo); // удалили объект класса
OkMessage();

// Заново создаем базу данных и подключаем к ней TBaseMaker
$db = new ttools\BaseMaker($pathBase,$username,$password);
// Тестируем Values, Rows методы
test_ValueRow($db,$shellTest);

// Тестируем метод query
unset($db); // удалили объект класса
UnlinkFile($filename);
$db = new ttools\BaseMaker($pathBase,$username,$password);
test_Query($db,$shellTest);

// Тестируем Update, Insert методы
test_UpdateInsert($db,$shellTest);

// Удаляем объект класса
unset($db); 
// *************************************************** TBaseMaker__test.php ***
