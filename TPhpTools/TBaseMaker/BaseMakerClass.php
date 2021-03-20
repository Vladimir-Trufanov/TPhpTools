<?php namespace ttools; 
                                         
// PHP7/HTML5, EDGE/CHROME                           *** BaseMakerClass.php ***

// ****************************************************************************
// * TPhpTools                     Обслуживатель баз данных SQLite3 через PDO *
// *                                                                          *
// * v1.0, 15.03.2021                              Автор:       Труфанов В.Е. *
// * Copyright © 2020 tve                          Дата создания:  18.12.2020 *
// ****************************************************************************

/**
 * Класс TBaseMaker обеспечивает ведение баз данных SQlite3 PDO: создание
 * таблиц, внесение данных, индексирование и выборку значений.
**/

// ****************************************************************************
// *                  Вывести сообщение об ошибке исполнения запроса          *
// ****************************************************************************
function sqlMessage($query,$params)
{
   $mess='[TPhpTools] '.RequestExecutionError.$query;
   if ($params!==null)
   {
      echo WithParameters;
      $parm='<br>          '.WithParameters.'[';
      foreach ($params as $name => $value) 
      {
         $parm=$parm.' '.$name.'=>'.$value;
      }
      $parm=$parm.' ]';
      $mess=$mess.$parm;
   }
   trigger_error($mess);
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
   public function queryRow($query,$params = null,$fetchStyle=\PDO::FETCH_ASSOC) 
   {
      $rows=$this->queryRowOrRows(true,$query,$params,$fetchStyle);
      return $rows;
   }
   // *************************************************************************
   // * Выполнить запрос для выборки значений в виде массива нескольких записей
   // *************************************************************************
   public function queryRows($query,$params = null,$fetchStyle=\PDO::FETCH_ASSOC) 
   {
      $rows=$this->queryRowOrRows(false,$query,$params,$fetchStyle);
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
   // Выполняется обычный PDO-запрос, но набор данных всегда возвращается в
   // виде массива требуемых записей в стиле PDO::FETCH_ASSOC
   /*
   $sign=array( 
      0=>array('name'=>'голубика','colour'=>'голубые',  'calories'=>41,'vid'=>'ягоды'), 
      1=>array('name'=>'брусника','colour'=>'красные',  'calories'=>41,'vid'=>'ягоды'), 
      2=>array('name'=>'груши',   'colour'=>'жёлтые',   'calories'=>42,'vid'=>'фрукты'),
      3=>array('name'=>'рябина',  'colour'=>'оранжевые','calories'=>81,'vid'=>'ягоды'),
      4=>array('name'=>'виноград','colour'=>'зелёные',  'calories'=>70,'vid'=>'фрукты')
   );
   */
   {
      $fetchStyle=\PDO::FETCH_ASSOC;
      $result=$this->queryRowOrRows(false,$query,$params,$fetchStyle);
      return $result;
   }
   // *************************************************************************
   // *  Вставить новую запись в таблицу базы данных:  $newUserId=$db->insert *
   // *      ('users',array('name' =>'NewUserName','password'=>'zzxxcc'));    *
   // *************************************************************************
   public function insert($table,$fields)
   {
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
      $result=$this->db->query($sql);
      return $result;
   }
   // ----------------------------------------------- приватные функции класса
   // *************************************************************************
   // *      Выполнить запрос и сформировать набор данных в виде массива      *
   // *     одной или нескольких записей в стиле PDO::FETCH_ASSOC, то есть    *
   // *        индексированный именами столбцов результирующего набора        *
   // *************************************************************************
   private function queryRowOrRows($singleRow,$query,$params,$fetchStyle)
   {
      $result = null;
      $stmt = $this->db->prepare($query);
      $stmt->setFetchMode($fetchStyle);
      if ($stmt->execute($params)) 
      {
         // Возвращаем либо только одну строку результирующего набора,
         // либо весь набор
         $result = $singleRow? $stmt->fetch(): $stmt->fetchAll();
         // Освобождаем соединение с сервером, давая возможность запускать 
         // другие SQL-запросы, но текущий запрос оставляем в состоянии 
         // готовности к повторному запуску
         $stmt->closeCursor();
      }
      else sqlMessage($query,$params);
      return $result;
   }
}
// ***************************************************** BaseMakerClass.php ***
