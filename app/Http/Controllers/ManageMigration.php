<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ManageMigration extends Controller
{
    public function index(Request $request){
       // Run all pending migrations
   $allowed = ['migrate', 'cache:clear', 'config:cache', 'db:seed'];

        $command = $request->query('command'); // e.g., ?command=migrate

        if (!in_array($command, $allowed)) {
            return response()->json(['status' => 'error', 'message' => 'Command not allowed']);
        }

        try {
            // Use --force in production to avoid confirmation
            Artisan::call($command, ['--force' => true]);

            return response()->json([
                'status' => 'success',
                'output' => Artisan::output()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
}
}