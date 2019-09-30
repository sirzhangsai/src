<?php

namespace yuns\str;

class StrTool
{

    protected $code;
    protected $message;
    protected $data;

    public function __construct($data = null, string $message, int $code)
    {
        $this->code = $code;
        $this->message = $message;
        $this->data = $data;
    }

    public static function success( $data = null, string $message= 'success', int $code = 0)
    {
        $result = new self($data, $message, $code);
        $result->exportResult();
    }
    public static function error( $data = null, string $message = 'error', int $code = 1)
    {
        $result = new self($data, $message, $code);
        $result->exportResult();
    }
    
    public  function JsonEncode()
    {
        $arr = [
            'code' => $this->code,
            'message' => $this->message,
            'data' => $this->data
        ];
        return json_encode($arr, JSON_UNESCAPED_UNICODE);
    }

    public function __toString()
    {
        header('Content-Type:application/json');
        return  $this->JsonEncode();
    }

    public function exportResult()
    {
        echo $this;
        exit();
    }

}