<?php

namespace App\Listeners;

use Maatwebsite\Excel\Events\ImportFailed;
use App\Exceptions\ExcelImportException;

class ExcelErrorListener
{
    /**
     * Handle the event.
     */
    public function handle(ImportFailed $event)
    {
        $exception = $event->getException();

        if ($exception instanceof \Maatwebsite\Excel\Validators\ValidationException) {
            throw new ExcelImportException('Validation error', 422, $exception);
        } elseif ($exception instanceof \Maatwebsite\Excel\Exceptions\SheetNotFoundException) {
            throw new ExcelImportException('Sheet not found', 404, $exception);
        } else {
            throw new ExcelImportException('Error during Excel import', 500, $exception);
        }
    }
}
