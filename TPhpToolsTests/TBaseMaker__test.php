<?php
                                         
// PHP7/HTML5, EDGE/CHROME                          *** TBaseMaker_test.php ***

// ****************************************************************************
// * TPhpTools                                       Тест класса TBaseMaker - *
// *                               обслуживателя баз данных SQLite3 через PDO *
// *                                                                          *
// * v1.0, 30.12.2020                              Автор:       Труфанов В.Е. *
// * Copyright © 2020 tve                          Дата создания:  18.12.2020 *
// ****************************************************************************

require_once $TPhpTools."/TPhpToolsTests/T".$classTT."_CreateBaseTest.php";


function echoi()
{
echo 'ttttt';
}
class test_TBaseMaker extends UnitTestCase 
{
   // Здесь все должно хорошо найтись в своих позициях
   function test_TBaseMaker_Simple()
   {
   
   /*                                                                 
      // **********************************************************************
      // *                    Создать тестовую базу данных                    *
      // **********************************************************************
      function CreateBaseTest()
      {
         $Result=true;
         echoi();
         // Проверяем, есть ли тестовая база данных, для того 
         // чтобы ее удалить и начать строить ее и заполнять заново
         $filename=$_SERVER['DOCUMENT_ROOT'].'/basemaker.db3';
         if (file_exists($filename)) 
         {
            if (!unlink($filename))
            {
               throw new Exception("Не удалось удалить тестовую базу данных $filename!");
            } 
            else echo "УThe file $filename exists<br>";
         } 
   //else 
   //{
   //   echo "The file $filename does not exist<br>";
   //}

   $pathBase='sqlite:'.$filename; 
   $username='tve';
   $password='23ety17';                                         
   //$pdo = new PDO($pathBase);
   $pdo = new PDO(
      $pathBase, 
      $username,
      $password,
      array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
   );
   try 
   {
      $pdo->beginTransaction();
      
      $sql='CREATE TABLE vids ([id-vid] INTEGER PRIMARY KEY AUTOINCREMENT, vid TEXT)';
      $st = $pdo->query($sql);
      $sql='CREATE TABLE colours (
         [id-colour] INTEGER PRIMARY KEY AUTOINCREMENT,
         colour      TEXT
      )';
      $st = $pdo->query($sql);
      $sql='CREATE TABLE produkts (
         name        TEXT PRIMARY KEY,
         [id-colour] INTEGER,
         calories    NUMERIC( 5, 1 ),
         [id-vid]    INTEGER
      )';
      $st = $pdo->query($sql);
      
      // https://art-life-spb.ru/kaiioraz_frukty
      // https://sostavproduktov.ru/produkty/yagody
      // https://sostavproduktov.ru/potrebitelyu/vidy-produktov/frukty

      $sql="INSERT INTO [vids] ([id-vid], [vid]) VALUES (1, 'фрукты');";
      $st = $pdo->query($sql);
      $sql="INSERT INTO [vids] ([id-vid], [vid]) VALUES (2, 'ягоды');";
      $st = $pdo->query($sql);

      $sql="INSERT INTO [colours] ([id-colour], [colour]) VALUES (1, 'красные');";
      $st = $pdo->query($sql);
      $sql="INSERT INTO [colours] ([id-colour], [colour]) VALUES (2, 'голубые');";
      $st = $pdo->query($sql);
      $sql="INSERT INTO [colours] ([id-colour], [colour]) VALUES (3, 'жёлтые');";
      $st = $pdo->query($sql);
      $sql="INSERT INTO [colours] ([id-colour], [colour]) VALUES (4, 'оранжевые');";
      $st = $pdo->query($sql);
      $sql="INSERT INTO [colours] ([id-colour], [colour]) VALUES (5, 'зелёные');";
      $st = $pdo->query($sql);


      $aProducts=[
         ['голубика', 2, 41, 2],
         ['брусника', 1, 41, 2],
         ['груши', 3, 42, 1],
         ['земляника', 1, 34, 2],
         ['рябина', 4, 81, 2],
         ['виноград', 5, 70, 1]
      ];
      $statement = $pdo->prepare("INSERT INTO [produkts] ".
         "([name], [id-colour], [calories], [id-vid]) VALUES ".
         "(:name,  :idcolour,   :calories,  :idvid);");
      $i=0;
      foreach ($aProducts as [$name,$idcolor,$calories,$idvid])
      $statement->execute(["name"=>$name, "idcolour"=>$idcolor, "calories"=>$calories, "idvid"=>$idvid]);
      
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
      //echo $e->getMessage();
   }

   
   echo 'есть наши контакты<br>';
   
}
*/
   
   
   
   
      echo '<div id="TestsDiv">';
      MakeTitle("TBaseMaker",'');
      
      CreateBaseTest() ;

      //$i=0;
      //$j=5/$i;
      //echo '$j';
      
      
      $filename=$_SERVER['DOCUMENT_ROOT'].'/basemaker.db3';
      $pathBase='sqlite:'.$filename; 
      $username='tve';
      $password='23ety17';                                         

      $pdo = new PDO($pathBase, $username, $password);
      $db = new BaseMaker($pdo);
      
      // Выборка одного значения
      $count = $db->queryValue('SELECT COUNT(*) FROM users');


      echo '</div>';

      
      /*
      // Проверяем, есть ли тестовая база данных, для того чтобы ее удалить
      // и начать тест
      $filename=$_SERVER['DOCUMENT_ROOT'].'/basemaker.db3';
      echo $filename.'<br>';
      if (file_exists($filename)) 
      {
         echo "The file $filename exists<br>";
      } 
      else 
      {
         echo "The file $filename does not exist<br>";
      }

      $string='Это строка для проверки функции Findes';
      $preg='/Это/u';
      $prefix='Findes("'.$preg.'","'.$string.'"); ';
      $Result=prown\Findes($preg,$string);
      $this->assertEqual('Это',$Result);
      
      //MakeTestMessage($prefix,'Подсtttтрока '.'"Это"'.' найдена в строке',80);
      echo $prefix.'***Подсtttтрока '.'"Это"'.' найдена в строке***<br>';
      
      $prefix='Findes("'.$preg.'","'.$string.'",$point); ';
      $Result=prown\Findes($preg,$string,$point);
      $this->assertEqual($point,0);
      MakeTestMessage($prefix,'Фрагмtttент '.'"Это"'.' найден с позиции 0',80);
      
      
      //$i=0;
      //$j=5/$i;
      //echo $j;
      
      $preg="/Findes/u";
      $prefix='Findes("'.$preg.'","'.$string.'",$point); ';
      $Result=prown\Findes($preg,$string,$point);
      $this->assertEqual($point,59);     // 59 позиция, а не 32, так как UTF8
      $this->assertNotEqual($point,32);  // если бы не UTF8
      MakeTestMessage($prefix,'Фрагмент '.'"Findes"'.' найден с байта 59 (не с 32, так как UTF8)',80);
      echo '</div>';
      */
      
      
      
      

      
  }

  
}


// **************************************************** TBaseMaker_test.php ***
