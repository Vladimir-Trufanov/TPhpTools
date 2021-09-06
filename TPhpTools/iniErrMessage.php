<?php namespace ttools;

// PHP7/HTML5, EDGE/CHROME                            *** iniErrMessage.php ***
// ****************************************************************************
// * TPhpTools                          Определить общие сообщения библиотеки *
// *                                                   PHP-прикладных классов *
// *                                                                          *
// * v1.0, 30.08.2021                              Автор:       Труфанов В.Е. *
// * Copyright © 2021 tve                          Дата создания:  15.01.2021 *
// ****************************************************************************

// Модуль собирает в одном файле константы, соответствующие пользовательским 
// ошибочным сообщениям и предупреждениям со всех классов библиотеки.

// BaseMakerClass:                   Обслуживатель баз данных SQLite3 через PDO 
define ("RequestExecutionError", "Ошибка исполнения запроса: ");
define ("WithParameters",        "с параметрами: ");

// UploadToServerClass:      Загрузчик файлов на сервер из временного хранилища
define ("DirDownloadMissing",    "Каталог для загрузки файла отсутствует");
define ("ExceedOnМAX_FILE_SIZE", "Размер файла превышает максимальный по МAX_FILE_SIZE");
define ("ExceedUploadMaxPHPINI", "Размер файла превышает upload_max_filesize из PHP.INI");
define ("FileCannotBeWritten",   "Файл невозможно записать на диск");
define ("FilePartiallyUploaded", "Файл загружен частично");
define ("FormLoadFileNotSpecif", "Данные формы загружены, но файл не был указано");
define ("LoadStoppedUndefPhp",   "Загрузка остановлена неопределенным PHP-расширением");
define ("NotWriteToDirectory",   "Не разрешена запись в каталог для загрузки файла");
define ("NotErrorOfCheckError",  "Не учтеная ошибка при проверке загрузки");
define ("TempoDirIsMissing",     "Временный каталог отсутствует");

// ****************************************************** iniErrMessage.php *** 

