<?php
include 'vendor/autoload.php';
$app = include 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$failedJob = Illuminate\Support\Facades\DB::table('failed_jobs')->orderBy('id', 'desc')->first();
if ($failedJob) {
    echo "ID: " . $failedJob->id . "\n";
    echo "Failed At: " . $failedJob->failed_at . "\n";
    echo "Exception Class: " . get_class($failedJob) . "\n";
    
    // Parse the exception to find the message
    $exceptionText = $failedJob->exception;
    $firstLine = strtok($exceptionText, "\n");
    echo "Message: " . $firstLine . "\n";
    
    // Print next few lines
    for ($i = 0; $i < 5; $i++) {
        echo strtok("\n") . "\n";
    }
} else {
    echo "No failed jobs found.\n";
}
