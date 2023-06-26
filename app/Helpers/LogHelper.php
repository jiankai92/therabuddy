<?php

use Illuminate\Support\Facades\Log;

if (!function_exists('writeToLog')) {

    /**
     * Write Entry To Logfile
     *
     * @param string $file
     * @param string $line
     * @param string $message
     * @param string $level
     * @param string $channel
     * @return void
     */
    function writeToLog(string $file, string $line, string $message, string $level = 'notice', string $channel = 'stack'): void
    {
        $msg = "[$file, $line]\n$message";
        Log::channel($channel)->$level($msg);
    }
}
