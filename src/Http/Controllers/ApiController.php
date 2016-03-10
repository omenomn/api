<?php

namespace Dandaj\Api\Http\Controllers;

use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    protected $statusCode = 200;

    public function getStatusCode()
    {
    	return $this->statusCode;
    }

    public function setStatusCode($statusCode)
    {
    	$this->statusCode = $statusCode;

    	return $this;
    } 

    public function respondNotFound($message = 'Nie znaleziono')
    {
    	return $this->setStatusCode(404)->respondWithError($message);
    }

    public function respond($data, $headers = [])
    {
    	return response()->json(array_merge($data, [
    			'status_code' =>  $this->getStatusCode(),
    		]), $this->getStatusCode(), $headers);
    }

    public function respondWithError($message)
    {
    	return $this->respond([
    		'status' => 'error',
    		'message' => [
                $message
            ],
    	]);
    }
}
