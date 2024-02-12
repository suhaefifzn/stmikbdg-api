<?php

namespace App\Exceptions;

use Exception;

class ExcelImportException extends Exception
{
    public function render($request)
    {
        return response()->json(['error' => 'Error during Excel import'], 500);
    }
}
