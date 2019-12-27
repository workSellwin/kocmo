<?php


namespace Kocmo\Exchange\Import;
use Monolog\Logger,
    Monolog\Handler\StreamHandler;

class Base
{
    protected $loggerInfo = null;
    protected $loggerError = null;
    protected $errors = [];
    protected $values = [];

    function __construct()
    {
        $this->loggerInfo = new Logger('exchange info');
        $this->loggerInfo->pushHandler( new StreamHandler($_SERVER['DOCUMENT_ROOT'] . '/api/info.log', Logger::INFO) );
        $this->loggerError = new Logger('exchange error');
        $this->loggerError->pushHandler( new StreamHandler($_SERVER['DOCUMENT_ROOT'] . '/api/error.log', Logger::ERROR) );
    }

    /**
     * @return array
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * @param string $value
     */
    public function setValues(string $value)
    {
        $this->values[] = $value;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param $error
     */
    public function setError($error)
    {
        $this->errors[] = $error;
    }

    public function addLogInfo(string $message){
        $this->loggerInfo->info($message);
    }

    public function addLogInfoArray(array $messages){

        if(count($messages)){
            foreach ($messages as $mess){
                if(gettype($mess) == 'string'){
                    $this->addLogInfo($mess);
                }
            }
        }
    }

    public function addLogError(string $message){
        $this->loggerError->info($message);
    }

    public function addLogErrorsArray(array $messages){

        if(count($messages)){
            foreach ($messages as $mess){
                if(gettype($mess) == 'string'){
                    $this->addLogError($mess);
                }
            }
        }
    }

    public function isSuccess(){

        if(count($this->errors)){
            return false;
        }
        return true;
    }
}