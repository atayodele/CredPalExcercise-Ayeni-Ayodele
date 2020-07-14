<?php

namespace App\Traits;

use Illuminate\Http\Response;
use DB;

trait ApiResponser
{
    public function successResponse($data, $code = Response::HTTP_OK)
    {
        return response()->json(['data' => $data], $code);
    }

    public function errorResponse($message, $code)
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

    public function createResponse($data, $code = Response::HTTP_CREATED)
    {
        return response()->json(['data' => $data], $code);
    }
    public function noResponse($message, $code = Response::HTTP_NO_CONTENT)
    {
        return response()->json(['message' => $message], $code);
    }
    public function notFound($message, $code = Response::HTTP_NOT_FOUND)
    {
        return response()->json(['message' => $message], $code);
    }
    public function validationError($message, $code = Response::HTTP_UNPROCESSABLE_ENTITY)
    {
        return response()->json(['message' => $message], $code);
    }
    public function UNAUTHORIZED($message, $code = Response::HTTP_UNAUTHORIZED)
    {
        return response()->json(['message' => $message], $code);
    }
    public function CheckDuplicateIsbn($pin)
    {
        $check = DB::table("books")
                        ->where('isbn', $pin)
                        ->first();
        return $check;
    }
}
