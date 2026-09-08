<?php

namespace App\Http\Controllers;

use App\Models\Backup;
use App\Models\SyncLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class BackupController extends Controller
{
    public function index()
    {
        $backups = Backup::orderByDesc('created_at')->paginate(20);
        $syncLogs = SyncLog::orderByDesc('created_at')->limit(20)->get();

        return view('backups.index', compact('backups', 'syncLogs'));
    }

    public function create(Request $request)
    {
        $database = $request->input('database', 'mysql');

        if (!in_array($database, ['mysql', 'mysql_second'])) {
            $database = 'mysql';
        }

        $databaseName = config("database.connections.{$database}.database");
        $filename = "backup_{$databaseName}_" . now()->format('Ymd_His') . '.sql';
        $path = storage_path("app/backups/{$filename}");

        try {
            if (!file_exists(dirname($path))) {
                mkdir(dirname($path), 0755, true);
            }

            $startedAt = now();

            $host = config("database.connections.{$database}.host");
            $port = config("database.connections.{$database}.port");
            $username = config("database.connections.{$database}.username");
            $password = config("database.connections.{$database}.password");

            $command = "mysqldump -h {$host} -P {$port} -u {$username} -p{$password} {$databaseName} > {$path}";

            exec($command, $output, $returnVar);

            $completedAt = now();
            $status = $returnVar === 0 ? 'completed' : 'failed';
            $size = file_exists($path) ? filesize($path) : null;

            Backup::create([
                'filename' => $filename,
                'file_path' => $path,
                'size' => $size,
                'database_name' => $databaseName,
                'status' => $status,
            ]);

            SyncLog::create([
                'source_database' => $database,
                'target_database' => 'backup',
                'records_synced' => 0,
                'status' => $status,
                'error_message' => $returnVar !== 0 ? "mysqldump exited with code {$returnVar}" : null,
                'started_at' => $startedAt,
                'completed_at' => $completedAt,
            ]);

            if ($status === 'completed') {
                return back()->with('success', "Backup created successfully: {$filename}");
            }

            return back()->with('error', "Backup failed with exit code {$returnVar}.");
        } catch (Throwable $e) {
            Log::error('Backup creation failed: ' . $e->getMessage());

            return back()->with('error', 'Unable to create backup: ' . $e->getMessage());
        }
    }

    public function download(Backup $backup)
    {
        if (!file_exists($backup->file_path)) {
            return back()->with('error', 'Backup file not found.');
        }

        return response()->download($backup->file_path, $backup->filename);
    }

    public function destroy(Backup $backup)
    {
        try {
            if (file_exists($backup->file_path)) {
                unlink($backup->file_path);
            }

            $backup->delete();

            return back()->with('success', 'Backup deleted successfully.');
        } catch (Throwable $e) {
            Log::error('Backup deletion failed: ' . $e->getMessage());

            return back()->with('error', 'Unable to delete backup: ' . $e->getMessage());
        }
    }
}
