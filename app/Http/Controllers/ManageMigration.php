<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ManageMigration extends Controller
{
    public function index(Request $request){
       // Run all pending migrations
      // Run all pending migrations
    Artisan::call('migrate');

    // Get the output
    $output = Artisan::output();

    return "Migration executed successfully: <pre>$output</pre>";
}
}