<?php
// Display all errors for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Log file
$logFile = 'webhook.log';

/**
 * Log messages to the log file.
 *
 * @param string $message
 */
function logMessage($message)
{
    global $logFile;
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - " . $message . "\n", FILE_APPEND);
}

// Log the webhook payload
$payload = file_get_contents('php://input');
logMessage("Payload received: $payload");

try {
    // Trigger the shell script via system call
    $scriptPath = '/home/u314481296/domains/admin.mangosdemexico.com/public_html/git-pull.sh';
    $result = file_put_contents('/tmp/trigger_git_pull', 'run'); // Temporary file as trigger
    if ($result === false) {
        throw new Exception('Failed to write to trigger file.');
    }

    logMessage("Git pull triggered via shell script.");
    echo 'Webhook executed successfully.';
} catch (Exception $e) {
    logMessage("Error: " . $e->getMessage());
    http_response_code(500);
    echo 'Internal Server Error: ' . $e->getMessage();
}
