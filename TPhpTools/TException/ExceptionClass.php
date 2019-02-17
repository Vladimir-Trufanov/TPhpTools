<?php 
                                          
// PHP7/HTML5, EDGE/CHROME                           *** ExceptionClass.php ***

// ****************************************************************************
// * TPhpTools              Обеспечить преобразования ошибок PHP в исключения *
// ****************************************************************************

//                                          Авторы: Котеров Д.В., Труфанов В.Е.
//                                          Дата создания:           03.02.2018
// Copyright © 2018 tve                     Посл.изменение:          05.02.2018

// ПОМНИТЬ(16.02.2019)! Если в коде сайта включается своя обработка исключений,
// то управление выводом ошибок display_errors на сайте NIC.RU отключается и
// работает только error_reporting (нужно разрешить обработку всех ошибок)

/**
 * Класс для преобразования перехватываемых (см. set_error_handler()) 
 * ошибок и предупреждений PHP в исключения (включая пользовательские 
 * сообщения, сформированные с помощью trigger_error).
 *
 * Следующие типы ошибок, хотя и поддерживаются формально, не могут
 * быть перехвачены:
 * E_ERROR, E_PARSE, E_CORE_ERROR, E_CORE_WARNING, E_COMPILE_ERROR,
 * E_COMPILE_WARNING
 */

class Exceptionizer
{
    // Создать новый объект-перехватчик и подключить его к стеку
    // обработчиков ошибок PHP (используется идеология "выделение 
    // ресурса есть инициализация").
    public function __construct($mask = E_ALL, $ignoreOther = false)
    {
        $catcher = new Exceptionizer_Catcher();
        $catcher->mask = $mask;
        $catcher->ignoreOther = $ignoreOther;
        $catcher->prevHdl = set_error_handler(array($catcher, "handler"));
    }
    // Уничтожить объект-перехватчик (например,
    // при выходе его из области видимости функции). 
    // Восстанавить предыдущий обработчик ошибок.
    public function __destruct()
    {
        restore_error_handler();
    }
    
    public function res($mes)
    {
      echo $mes;
    }
    

// ****************************************************************************
// *    Cгенерировать исключение или просто сформировать див с сообщением об  *
// *              ошибке в "жёсткой" систеие обработки ошибок                 *
// ****************************************************************************
function TriggerUserError($Message,$Mode=1)
{
   // "Жёсткая" система обработки ошибок представляется следующим образом:

   // - ошибка является контроллируемой в случае, когда известно в каком месте
   // сайта она может возникнуть. Иначе она является неконтроллируемой;

   // - файл index.php загружает (require_once) домашнюю страницу сайта,
   // окружённую try catch (E_EXCEPTION $e). Таким образом неконтроллируемая 
   // ошибка возникает не чистом экране до запуска html-кода с трассировкой 
   // всплывания исключения;
   
   // - в режиме $Mode<>1 вызывается исключение с пользовательской ошибкой, как
   // неконтроллируемая ошибка - trigger_error($Message,E_USER_ERROR);
   
   // - в режиме по умолчанию $Mode==1 предполагается, что ошибка произошла
   // в php-коде до разворачивания html-страницы и, в этом случае формируется 
   // дополнительный div сообщения с id="Ers"
   
   if ($Mode==1)
   {
      echo "<div id=\"Ers\"> ";
      echo ' '.$Message.' ';
      echo "</div>";
   } 
   else
   {
      trigger_error($Message,E_USER_ERROR);   
   } 
}
} // end Exceptionizer

/**
 * Обеспечить перехват ошибок (внутренний класс).
 * Нельзя использовать для этой же цели непосредственно $this 
 * (класса Exceptionizer): вызов set_error_handler() увеличивает 
 * счетчик ссылок на объект, а он должен остаться неизменным, чтобы в 
 * программе всегда оставалась ровно одна ссылка.
 */
class Exceptionizer_Catcher
{
    // Битовые флаги предупреждений, которые будут перехватываться.
    public $mask = E_ALL;
    // Признак, нужно ли игнорировать остальные типы ошибок, или же
    // следует использовать стандартный механизм обработки PHP.
    public $ignoreOther = false;
    // Предыдущий обработчик ошибок.
    public $prevHdl = null;
    // Функция-обработчик ошибок PHP.
    public function handler($errno, $errstr, $errfile, $errline)
    {
        // Если error_reporting нулевой, значит, использован оператор @,
        // и все ошибки должны игнорироваться.
        if (!error_reporting()) return;
        // Перехватчик НЕ должен обрабатывать этот тип ошибки?
        if (!($errno & $this->mask)) 
        {
            // Если ошибку НЕ следует игнорировать...
            if (!$this->ignoreOther) 
            {
                if ($this->prevHdl) 
                {
                    // Если предыдущий обработчик существует, вызываем его.
                    $args = func_get_args();
                    call_user_func_array($this->prevHdl, $args);
                } 
                else 
                {
                    // Иначе возвращаем false, что вызывает запуск встроенного
                    // обработчика PHP.
                    return false;
                }
            }
            // Возвращаем true (все сделано).
            return true;
        }
        // Получаем текстовое представление типа ошибки.
        $types = array(
            "E_ERROR", "E_WARNING", "E_PARSE", "E_NOTICE", "E_CORE_ERROR",
            "E_CORE_WARNING", "E_COMPILE_ERROR", "E_COMPILE_WARNING",
            "E_USER_ERROR", "E_USER_WARNING", "E_USER_NOTICE", "E_STRICT",
        );
        // Формируем имя класса-исключения в зависимости от типа ошибки.
        $className = __CLASS__ . "_" . "Exception";
        foreach ($types as $t) 
        {
            $e = constant($t);
            if ($errno & $e) {$className = $t; break;}
        }
        // Генерируем исключение нужного типа.
        throw new $className($errno, $errstr, $errfile, $errline);
    }
}

/**
 * Базовый класс для всех исключений, полученных в результате ошибки PHP.
 */
abstract class Exceptionizer_Exception extends Exception
{
    public function __construct($no = 0, $str = null, $file = null, $line = 0) 
    {
        parent::__construct($str, $no);
        $this->file = $file;
        $this->line = $line;
    }
}

/**
 * Создаем иерархию "серьезности" ошибок, чтобы можно было
 * ловить не только исключения с указанием точного типа, но 
 * и сообщения, не менее "фатальные", чем указано.
 */
class E_EXCEPTION extends Exceptionizer_Exception {}
    class AboveE_STRICT extends E_EXCEPTION {} 
    class E_STRICT extends AboveE_STRICT {}
    class AboveE_NOTICE extends AboveE_STRICT {}
        class E_NOTICE extends AboveE_NOTICE {}
        class AboveE_WARNING extends AboveE_NOTICE {}
            class E_WARNING extends AboveE_WARNING {}
            class AboveE_PARSE extends AboveE_WARNING {}
                class E_PARSE extends AboveE_PARSE {}
                class AboveE_ERROR extends AboveE_PARSE {}
                    class E_ERROR extends AboveE_ERROR {} 
                    class E_CORE_ERROR extends AboveE_ERROR {}
                    class E_CORE_WARNING extends AboveE_ERROR {}
                    class E_COMPILE_ERROR extends AboveE_ERROR {}
                    class E_COMPILE_WARNING extends AboveE_ERROR {}
    class AboveE_USER_NOTICE extends E_EXCEPTION {}
        class E_USER_NOTICE extends AboveE_USER_NOTICE {}
        class AboveE_USER_WARNING extends AboveE_USER_NOTICE {}
            class E_USER_WARNING extends AboveE_USER_WARNING {}
            class AboveE_USER_ERROR extends AboveE_USER_WARNING {}
                class E_USER_ERROR extends AboveE_USER_ERROR {}

// Иерархии пользовательских и встроенных ошибок не сравнимы,
// т.к. они используются для разных целей, и оценить 
// "серьезность" нельзя.

// ***************************************************** ExceptionClass.php ***
