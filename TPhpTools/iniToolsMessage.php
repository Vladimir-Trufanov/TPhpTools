<?php namespace ttools;

// PHP7/HTML5, EDGE/CHROME                          *** iniToolsMessage.php ***
// ****************************************************************************
// * TPhpTools                          Определить общие сообщения библиотеки *
// *                                                   PHP-прикладных классов *
// *                                                                          *
// * v2.0, 03.01.2022                              Автор:       Труфанов В.Е. *
// * Copyright © 2021 tve                          Дата создания:  15.01.2021 *
// ****************************************************************************

// Модуль собирает в одном файле константы, соответствующие пользовательским 
// ошибочным сообщениям и предупреждениям со всех классов библиотеки.

// BaseMakerClass:                   Обслуживатель баз данных SQLite3 через PDO 
define ("RequestExecutionError", "Ошибка исполнения запроса: ");
define ("WithParameters",        "с параметрами: ");

// UploadToServerClass:      Загрузчик файлов на сервер из временного хранилища
define ("CouldNotUploadTo",      "Не удалось загрузить файл на сервер");
define ("DirDownloadMissing",    "Каталог для загрузки файла отсутствует");
define ("ExceedOnМAX_FILE_SIZE", "Размер файла превышает максимальный по МAX_FILE_SIZE");
define ("ExceedOnМaxSize",       "Размер файла превышает максимальный");
define ("ExceedUploadMaxPHPINI", "Размер файла превышает upload_max_filesize из PHP.INI");
define ("FileCannotBeWritten",   "Файл невозможно записать на диск");
define ("FileNotSelectLoad",     "Файл не выбран и не загружен во временный каталог");
define ("FilePartiallyUploaded", "Файл загружен частично");
define ("FormLoadFileNotSpecif", "Данные формы загружены, но файл не был указан");
define ("InvalidUploadSize",     "Неверно определен размер файла загрузки в Мбайт");
define ("IsNotPermitTypeFile",   "Недопустимый тип файла");
define ("LoadStoppedUndefPhp",   "Загрузка остановлена неопределенным PHP-расширением");
define ("NotErrorOfCheckError",  "Не учтеная ошибка при проверке загрузки");
define ("NotLoadSizeExceedMax",  "Не произошло загрузки файла из-за превышения размера по MAX_FILE_SIZE");
define ("NotWriteToDirectory",   "Не разрешена запись в каталог для загрузки файла");
define ("TempoDirIsMissing",     "Временный каталог отсутствует");
define ("UnspecErrorUpload",     "Неопределенная ошибка загрузки файла на сервер");
define ("ZeroFileSize",          "Нулевой размер файла - либо большой, либо не выбран");

// **************************************************** iniToolsMessage.php *** 

