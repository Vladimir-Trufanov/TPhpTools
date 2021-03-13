<?php namespace ttools; 
                                         
// PHP7/HTML5, EDGE/CHROME                           *** BaseMakerClass.php ***

// ****************************************************************************
// * TPhpTools                     Обслуживатель баз данных SQLite3 через PDO *
// *                                                                          *
// * v1.0, 15.01.2021                              Автор:       Труфанов В.Е. *
// * Copyright © 2020 tve                          Дата создания:  18.12.2020 *
// ****************************************************************************

/**
 * Класс TBaseMaker обеспечивает ведение баз данных SQlite3 PDO: создание
 * таблиц, внесение данных, индексирование и выборку значений.
**/

// ****************************************************************************
// *                  Вывести сообщение об ошибке исполнения запроса          *
// ****************************************************************************
function sqlMessage($query,$params,$mode=rvsTriggerError)
{
   $mess='[TPhpTools] '.RequestExecutionError.$query; echo '<br>';
   trigger_error($mess);
   if (!$params==null)
   {
      //$mess=RequestExecutionError.$query.WithParameters; //.string($params);
      echo 
         "<span style=\"color:#993300; font-weight:bold; ".
         "font-family:'Anonymous Pro', monospace; font-size:0.9em\">";
      echo WithParameters;
      print_r($params);
      echo "</span><br>";
   }
}
// ****************************************************************************
// *      TBaseMaker: Обеспечить ведение баз данных SQlite3 PDO: создание     *
// *         таблиц, внесение данных, индексирование и выборку значений       *
// ****************************************************************************
class BaseMaker 
// https://oracleplsql.ru/system-tables-sqlite.html
// http://labaka.ru/tools/obyortka-dlya-raboty-s-pdo
// http://codeharmony.ru/materials/137
{
   private $db;
   public function __construct($pathBase,$username,$password) 
   {
      $this->db = new \PDO($pathBase,$username,$password);
      $this->db->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
      //\prown\MakeUserError('SendCookieFailed','TPhpTools',rvsTriggerError);
   }
   // ----------------------------------------------------------- методы класса
   // *************************************************************************
   // *                     Унаследовать родительские методы                  *
   // *************************************************************************
   public function beginTransaction() {return $this->db->beginTransaction();}
   public function commit() {return $this->db->commit();}
   public function inTransaction() {return $this->db->inTransaction();}
   public function rollback() {return $this->db->rollback();}
   // public function exec($query) {return $this->db->exec($query);} --> query($query);
   // public function prepare($query) {return $this->db->prepare($query);} // --> query($query);
   // public function execute($query) {return $this->db->execute($query);} // --> query($query);
   // *************************************************************************
   // *              Проверить, есть ли заданная таблица в базе               *
   // *************************************************************************
   public function isTable($tablename) 
   {
      $result=false;
      $sql='SELECT name FROM sqlite_master';      
      $prod=$this->queryRows($sql);
      foreach ($prod as $nomrow => $arr) 
      {
         if ($arr['name']==$tablename) 
         {
            $result=true;
            break;
         }  
      }
      return $result;
   }
   // *************************************************************************
   // *   Выполнить запрос для выборки значений в виде массива одной записи   *
   // *************************************************************************
   public function queryRow($query,$params = null,$fetchStyle=\PDO::FETCH_ASSOC,$classname=null) 
   {
      $rows=$this->queryRowOrRows(true,$query,$params,$fetchStyle,$classname);
      return $rows;
   }
   // *************************************************************************
   // * Выполнить запрос для выборки значений в виде массива нескольких записей
   // *************************************************************************
   public function queryRows($query,$params = null,$fetchStyle=\PDO::FETCH_ASSOC,$classname=null) 
   {
      $rows=$this->queryRowOrRows(false,$query,$params,$fetchStyle,$classname);
      return $rows;
   }
   // *************************************************************************
   // *    Выполнить запрос и вывести результат, как единственное значение    *
   // *************************************************************************
   public function queryValue($query,$params=null)
   /**
    * Примеры:
    * 
    * $query  - текст sql-запроса: 'SELECT COUNT(*) FROM vids'
    * $params - массив входных значений: null
    * 
    * $query  - текст sql-запроса: 'SELECT COUNT(*) FROM produkts WHERE calories<:calories'
    * $params - массив входных значений: array(':calories' => $calories)
    * 
    * $query  - текст sql-запроса: 'SELECT COUNT(*) FROM produkts WHERE calories>=? AND [id-vid] = ?'
    * $params - массив входных значений: array($calories, $idvid)
   **/
   {
      $result = null;
      $stmt = $this->db->prepare($query);
      if ($stmt->execute($params)) 
      {
         // Выбираем нулевой элемент выбранного столбца по запросу
         $result = $stmt->fetchColumn();
         // Разрешаем запустить новый запрос, если не все данные выбраны из набора
         $stmt->closeCursor();
      }
      else sqlMessage($query,$params);
      return $result;
   }
   // *************************************************************************
   // *        Выполнить запрос и вывести результат, как простой список       *
   // *              (не ассоциативный массив) выбранных значений             *
   // *                                                                       *
   // * Замечание:             метод выбирает в список нулевые элементы строк *
   // *                                из выбранного по запросу набора данных *
   // *************************************************************************
   public function queryValues($query,$params=null) 
   /**
    * $query  - текст sql-запроса: 'SELECT vid FROM vids'
    * $params - массив входных значений: null
    * 
    * $query  - текст sql-запроса: 'SELECT name FROM produkts WHERE calories>=? AND [id-vid] = ?'
    * $params - массив входных значений: [$calories, $idvid]
   **/
   { 
      $result = null;
      $stmt = $this->db->prepare($query);
      if ($stmt->execute($params)) 
      {
         $result = array();
         while ($row = $stmt->fetch(\PDO::FETCH_NUM)) 
         {
            $result[] = $row[0];
         }
      }
      else sqlMessage($query,$params);
      return $result;  
   }
   // *************************************************************************
   // *       Заключить строку в кавычки (если требуется) и экранировать      *
   // *   специальные символы внутри строки подходящим для драйвера способом  *
   // *************************************************************************
   public function quote($str) 
   {
      return $this->db->quote($str);
   }
   // *************************************************************************
   // *        Заключить все строки из списка в кавычки и экранировать        *
   // *   специальные символы внутри строк подходящим для драйвера способом   *
   // *************************************************************************
   public function quoteArray($arr) 
   {
      $result = array();
      foreach ($arr as $val) 
      {
         $result[] = $this->db->quote($val);
      }
      return $result;
   }
   // *************************************************************************
   // *  Заключить все строки из списка в кавычки и экранировать специлаьные  *
   // *     символы внутри строк подходящим для драйвера способом, а затем    *
   // *     объединить элементы массива в строку через заданный разделитель   *
   // *************************************************************************
   public function quoteImplodeArray($arr) 
   {
      return implode(',', $this->quoteArray($arr));
   }
   // *************************************************************************
   // *             Подготовить и выполнить запрос к базе данных              *
   // *************************************************************************
   public function query($query,$params=null) 
   {
      $result=null;
      $stmt=$this->db->prepare($query);
      $result=$stmt->execute($params);
      if (!$result) {sqlMessage($query,$params);}
      return $result;
   }
   // *************************************************************************
   // *             Вставить новую запись в таблицу базы данных               *
   // *************************************************************************
   public function insert($table,$fields) 
   {
      //filemtime('spoon');
      // Формируем текст запроса по типу
      // "INSERT INTO [vids] ([id-vid],[vid]) VALUES (:id-vid, :vid)",
      // но с фактическими значениями типа "строка" ???
      $names = '';
      $vals = '';
      foreach ($fields as $name => $val) 
      {
         if (isset($names[0])) 
         {
            $names.=',';
            $vals.= ',';
         }
         $names.='['.$name.']';
         $vals.="'".$val."'";
      }
      $sql="INSERT INTO ".'['.$table.']'.' ('.$names.') VALUES ('.$vals.')';
    
      //echo '---<br>';
      //echo $sql.'<br>';
      //echo '---<br>';
      //try 
      //{
         $result=$this->db->query($sql);
      //}
      //catch(PDOException $e) 
      //{
         //$this->report($e);
         //   echo 'Подключение не удалось: ';// . $e->getMessage();
      //}
      return $result;
   }

 
  // Returns true/false
  public function update($table, $fields, $where, $params = null) {
    try {
      $sql = 'UPDATE ' . $table . ' SET ';
      $first = true;
      foreach (array_keys($fields) as $name) {
        if (!$first) {
          $first = false;
          $sql .= ', ';
        }
        $first = false;
        $sql .= $name . ' = :_' . $name;
      }
      if (!is_array($params)) {
        $params = array();
      }
      $sql .= ' WHERE ' . $where;
      $rs = $this->db->prepare($sql);
      foreach ($fields as $name => $val) {
        $params[':_' . $name] = $val;
      }
      $result = $rs->execute($params);
      return $result;
    } catch(Exception $e) {
      $this->report($e);
    }
  }
   // ----------------------------------------------- приватные функции класса

   // *************************************************************************
   // *      Выполнить запрос и сформировать набор данных в виде массива      *
   // *     одной или нескольких записей в стиле PDO::FETCH_ASSOC, то есть    *
   // *        индексированный именами столбцов результирующего набора        *
   // *************************************************************************
   private function queryRowOrRows($singleRow,$query,$params,$fetchStyle,$classname)
   {
      $result = null;
      $stmt = $this->db->prepare($query);
      // 18.01.2021 До лучших времен исключаем метод выборки набора данных
      // по классу(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE), генерируя пользовательское сообщение !!!
      //if($classname) 
      //{
      //   $stmt->setFetchMode($fetchStyle, $classname);
      //} 
      //else 
      //{
         $stmt->setFetchMode($fetchStyle);
      //}
      if ($stmt->execute($params)) 
      {
         $result = $singleRow? $stmt->fetch(): $stmt->fetchAll();
         $stmt->closeCursor();
      }
      else sqlMessage($query,$params);
      return $result;
   }
 
 
  public function report($e) 
  {
    echo 'Привет!';
    //throw $e;
  }

}


/*
А вот и пример работы:

// Добавление записи (INSERT) и получение значения поля AUTO_INCREMENT
$newUserId = $db->insert('users', array('name' => 'NewUserName', 'password' => 'zzxxcc'));

// Изменение записи (UPDATE)
$db->update('users', array('name' => 'UpdatedName'), 'id=:id', array(':id' => $newUserId));
*/

// ***************************************************** BaseMakerClass.php ***
