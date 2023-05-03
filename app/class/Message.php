<?php namespace App\class;

class Message{

    public $success;
    public $message;
    public $errors;
    public $status;
    public $user;
    public $token;

    function __construct($success, $message, $errors, $status, $user, $token)
    {
        $this->success = $success;
        $this->message = $message;
        $this->errors  = $errors;
        $this->status  = $status;
        $this->user    = $user;
        $this->token   = $token;
    }

    public function message(){

        return response()->json([
            'success' => $this->success,
            'message' => $this->message,
            'errors'  => $this->errors,
            'user'    => $this->user,
            'token'   => $this->token
        ], $this->status);
    }
}
