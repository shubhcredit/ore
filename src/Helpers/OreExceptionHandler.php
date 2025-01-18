<?php

use Illuminate\Support\Facades\Log;

function oreExceptionLog($e){
      // Log the exception for debugging
      Log::error('Error while creating record: ' . $e->getMessage());

      // Optionally rethrow the exception for Laravel's handler
      report($e);
  
      // Return a user-friendly error response
      return response()->json([
          'message' => 'An error occurred while processing your request. Please try again later.',
          'error' => $e->getMessage() // Optionally include this in debug mode only
      ], 500);
}

