<?php

namespace Src\Helper\Http;

use Src\Helper\Trimmer\impl\RequestTrimmer;
use Src\Helper\Trimmer\ITrimmer;

class HttpRequest
{
    protected string $method = 'GET';
    protected array $data = [];
    protected ITrimmer $trimmer;

    public function __construct(ITrimmer $trimmer = null, $method = null)
    {
        $this->method = $method ?? $_SERVER['REQUEST_METHOD'];
        $this->data = match($this->method) {
                        'POST' => $_POST,
                        'GET' => $_GET,
                        'PUT' => $this->_getPutData(),
                        'DELETE' => $this->_getDeleteData(),
                        default => []
                    };
        $this->trimmer = $trimmer ?? new RequestTrimmer();
    }

    public static function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getData(): array {
        return $this->data;
    }

    public function get(string $itemKey, bool $toTrim = true)
    {
        /**
         * If such key is set in the data array
         */
        if(isset($this->data[$itemKey])) {
            /**
             * Get item value
             */
            $value = $this->data[$itemKey];
            if($toTrim) {
                /**
                 * If desired, trim and clear it
                 */
                return $this->trimmer->in($value);
            }
            return $value;
        }
        return null;
    }

    private function _getPutData()
    {
        $putData = [];
        parse_str(file_get_contents('php://input'), $putData);

        return $putData;
    }

    private function _getDeleteData()
    {
        $deleteData = [];
        parse_str($_SERVER['QUERY_STRING'], $deleteData);

        return $deleteData;
    }
}