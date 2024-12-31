<?php

use Illuminate\Support\Facades\Storage;


function getFileS3Bucket($path){
    $path = Storage::disk('s3')->url($path);
    return $path;
}
function getPathS3Bucket(){
    return 'mp';
}

function uploadToSpaces($file, $path)
{
    $s3MediaUrl = Storage::disk('s3')->put($path, $file);
    return $s3MediaUrl;
}

    /**
     * Mask sensitive data.
     *
     * @param string $value The value to be masked.
     * @param int $startVisible Number of characters to show at the start.
     * @param int $endVisible Number of characters to show at the end.
     * @param string $maskChar The character to use for masking. Default is 'X'.
     * @return string The masked value.
     */
    function maskData($value, $startVisible = 0, $endVisible = 0, $maskChar = 'X')
    {
        // Return the value as-is if it's a special case like "-"
        if ($value === "-") {
            return $value;
        }

        $length = strlen($value);

        // If both `startVisible` and `endVisible` are 0, mask the entire value
        if ($startVisible === 0 && $endVisible === 0) {
            return str_repeat($maskChar, $length);
        }

        $maskLength = max(0, $length - ($startVisible + $endVisible));

        return substr($value, 0, $startVisible)
            . str_repeat($maskChar, $maskLength)
            . substr($value, -1 * $endVisible);
    }

        // Helper to Get Location from IP
    function getLocationFromIP($ip)
    {
        $response = file_get_contents("http://ipinfo.io/{$ip}/json");
        $data = json_decode($response, true);
        return $data['city'] . ', ' . $data['region'] . ', ' . $data['country'] ?? 'Unknown';
    }
