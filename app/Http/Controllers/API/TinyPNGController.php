<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\TinyPNG;

\Tinify\setKey(env("TINY_PNG_API_KEY"));

class TinyPNGController extends Controller
{
    public function compressionCount()
    {
        try {
            // Use the Tinify API client.
            $compressionsThisMonth = \Tinify\compressionCount();
        } catch(\Tinify\AccountException $e) {
            // Verify your API key and account limit.
            return response()->json("The error message is: " . $e->getMessage(), 422);
        } catch(\Tinify\ClientException $e) {
            // Check your source image and request options.
            return response()->json("The error message is: " . $e->getMessage(), 422);
        } catch(\Tinify\ServerException $e) {
            // Temporary issue with the Tinify API.
            return response()->json("The error message is: " . $e->getMessage(), 422);
        } catch(\Tinify\ConnectionException $e) {
            // A network connection error occurred.
            return response()->json("The error message is: " . $e->getMessage(), 422);
        } catch(Exception $e) {
            // Something else went wrong, unrelated to the Tinify API.
            return response()->json("The error message is: " . $e->getMessage(), 422);
        }

        if($compressionsThisMonth > 0) {
            return count($compressionsThisMonth);
        }
        else {
            return 0;
        }
    }

}
