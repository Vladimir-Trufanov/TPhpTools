<?php namespace ttools; 
                                         
// PHP7/HTML5, EDGE/CHROME                          *** SQLiteBLOBClass.php ***

// ****************************************************************************
// * TPhpTools                    Загрузить файл (BLOB-данное) в базу данных, *
// *                          обновить данные в базе данных, извлечь обратно. *
// *                                                                          *
// *                          https://www.sqlitetutorial.net/sqlite-php/blob/ *
// *                          https://prowebmastering.ru/sqlite-pdo-blob.html *
// *                                                                          *
// * Объекты класса позволяют управлять BLOB-данными в базе данных SQLite     *
// * с помощью PHP PDO. BLOB обозначает большой двоичный объект, который      * 
// * представляет собой набор двоичных данных, хранящихся в виде значения     *
// * в базе данных. Используя BLOB, вы можете хранить документы, изображения  *
// * и другие мультимедийные файлы в базе данных.                             *
// *                                                                          *
// * v1.0, 14.02.2023                              Автор:       Труфанов В.Е. *
// * Copyright © 2022 tve                          Дата создания:  14.02.2023 *
// ****************************************************************************

class SQLiteBLOB
{
   // ----------------------------------------------------- СВОЙСТВА КЛАССА ---
   private $pdo;
   // ------------------------------------------------------- МЕТОДЫ КЛАССА ---
   public function __construct($pdo)
   {
      $this->pdo = $pdo;
   }
   // *************************************************************************
   // *                  Вставить файл (BLOB-данное) в базу данных            *
   // *************************************************************************
   /**
   * Insert blob data into the documents table
   * @param type $pathToFile
   * @return type
   * 
   * Для вставки BLOB-данных в таблицу, используются следующие шаги:
   * 
   * Выполняется подключение к базе данных SQLite и создается экземпляр класса 
   * PDO. В классе используется функция fopen(), чтобы прочитать файл. Функция 
   * fopen() возвращает указатель на файл. Далее готовится оператор INSERT к 
   * выполнению, вызовом метода prepare() объекта PDO. Метод prepare() возвращает 
   * экземпляр класса PDOStatement. Затем используется метод bindParam() объекта 
   * PDOStatement, чтобы связать параметр с именем переменной. Для этого данные
   * BLOB привязываются к указателю файла. И, наконец, вызывается метод execute() 
   * объекта PDO.
   */
   public function insertDoc($mimeType,$pathToFile) 
   {
      if (!file_exists($pathToFile)) throw new \Exception("File %s not found.");
      $sql="INSERT INTO documents(mime_type,doc) VALUES(:mime_type,:doc)";
      // read data from the file
      $fhi=fopen($pathToFile,'rb');
      $stmt=$this->pdo->prepare($sql);
      $stmt->bindParam(':mime_type',$mimeType);
      $stmt->bindParam(':doc',$fhi,\PDO::PARAM_LOB);
      $stmt->execute();
      //fclose($fhi);
      unset($fhi); 
      return $this->pdo->lastInsertId();
   }
   // *************************************************************************
   // *                    Прочитать BLOB-документ из базы данных             *
   // *************************************************************************
   /**
   * Read document from the documents table
   * @param type $documentId
   * @return type
   */
   public function readDoc($documentId) 
   {
      $sql = "SELECT [mime_type],[doc]". 
         "FROM [documents] "."WHERE document_id = :document_id";
      // initialize the params
      $mimeType = null;
      $doc = null;
      $stmt=$this->pdo->prepare($sql);
      if ($stmt->execute([":document_id"=>$documentId]))
      {
         $stmt->bindColumn(1, $mimeType);
         $stmt->bindColumn(2, $doc, \PDO::PARAM_LOB);
         return $stmt->fetch(\PDO::FETCH_BOUND)?
         [
            "document_id" => $documentId,
            "mime_type"   => $mimeType,
            "doc"         => $doc
         ]:null;
      } 
      else 
      {
         return null;
      }
   }
   // *************************************************************************
   // *                     Обновить данные BLOB в базе данных                *
   // *************************************************************************
   /**
   * Update document
   * @param type $documentId
   * @param type $mimeType
   * @param type $pathToFile
   * @return type
   * @throws \Exception
   */
   public function updateDoc($documentId, $mimeType, $pathToFile) 
   {
      if (!file_exists($pathToFile))
      throw new \Exception("File %s not found.");
      $fh = fopen($pathToFile, 'rb');
      $sql = "UPDATE documents
             SET mime_type = :mime_type,
             doc = :doc
             WHERE document_id = :document_id";
      $stmt = $this->conn->prepare($sql);
      $stmt->bindParam(':mime_type', $mimeType);
      $stmt->bindParam(':data', $fh, \PDO::PARAM_LOB);
      $stmt->bindParam(':document_id', $documentId);
      fclose($fh);
      return $stmt->execute();
   }
   // --------------------------------------------------- ВНУТРЕННИЕ МЕТОДЫ ---
} 

// **************************************************** SQLiteBLOBClass.php ***
