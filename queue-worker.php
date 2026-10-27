#!/usr/bin/env php
<?php
/**
 * Queue Worker CLI Script
 * Run this via cron or supervisor to process background jobs
 * 
 * Usage: php queue-worker.php [options]
 *   --limit=N     Process max N jobs (default: 10)
 *   --type=TYPE   Process only specific job type
 *   --daemon      Run continuously (add sleep between batches)
 *   --retry       Retry failed jobs from last 24 hours
 *   --clean       Clean old completed jobs
 */

require_once __DIR__ . '/config.php';

// Parse arguments
$options = getopt('', ['limit::', 'type::', 'daemon', 'retry', 'clean']);
$limit = (int)($options['limit'] ?? 10);
$type = $options['type'] ?? null;
$daemon = isset($options['daemon']);
$retry = isset($options['retry']);
$clean = isset($options['clean']);

echo "RajasthanReads Queue Worker\n";
echo "===========================\n\n";

// Retry failed jobs if requested
if ($retry) {
    echo "Retrying failed jobs...\n";
    $retried = Queue::retryFailed(24);
    echo "Retried {$retried} jobs\n\n";
}

// Clean old jobs if requested
if ($clean) {
    echo "Cleaning old jobs...\n";
    $deleted = Queue::cleanOld(7);
    echo "Deleted {$deleted} old jobs\n\n";
}

// Main processing loop
$iteration = 0;
$totalProcessed = 0;
$totalFailed = 0;

do {
    $iteration++;
    echo "[Batch {$iteration}] Processing up to {$limit} jobs...\n";
    
    $stats = Queue::work($limit);
    
    $totalProcessed += $stats['processed'];
    $totalFailed += $stats['failed'];
    
    echo "  Processed: {$stats['processed']}\n";
    echo "  Failed: {$stats['failed']}\n";
    
    if (!empty($stats['errors'])) {
        foreach ($stats['errors'] as $error) {
            echo "  Error: {$error}\n";
        }
    }
    
    echo "\n";
    
    // In daemon mode, sleep if no jobs processed
    if ($daemon && $stats['processed'] === 0) {
        echo "[Daemon] No jobs available, sleeping 5 seconds...\n";
        sleep(5);
    }
    
} while ($daemon);

echo "===========================\n";
echo "Total processed: {$totalProcessed}\n";
echo "Total failed: {$totalFailed}\n";
echo "Done.\n";
