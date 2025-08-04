<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\Storage;


class RiskPredictorController extends Controller
{
    public function index()
    {
        return view('risk_predictor.index');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'student_data' => 'required|file|mimes:xlsx,xls'
        ]);

        // Ensure directory exists
        Storage::makeDirectory('temp');

        // Give the file a unique name to prevent caching issues
        $filename = 'students_' . time() . '.xlsx';

        // Get the uploaded file instance
        $uploadedFile = $request->file('student_data');

        // Move the file manually to storage/app/temp (guaranteed to exist)
        $fullPath = storage_path("app/temp/{$filename}");
        $uploadedFile->move(storage_path('app/temp'), $filename);

        // Add a short delay to ensure file write completion
        usleep(100000); // 100 ms

        // Check that the file exists before proceeding
        if (!file_exists($fullPath)) {
            return response()->json([
                'error' => 'Excel file not found after move',
                'path' => $fullPath
            ], 500);
        }

        $pythonPath = 'C:\\Users\\Aksh\\anaconda3\\python.exe';
        $scriptPath = base_path('ml_model/predict.py');

        $process = new Process([$pythonPath, $scriptPath, $fullPath]);
        $process->run();

        if (!$process->isSuccessful()) {
            return response()->json([
                'error' => 'Python process failed',
                'stdout' => $process->getOutput(),
                'stderr' => $process->getErrorOutput()
            ], 500);
        }

        $output = json_decode($process->getOutput(), true);

        return back()->with('predictions', $output);
    }


}
