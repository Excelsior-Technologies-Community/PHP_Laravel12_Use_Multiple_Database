<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExecuteQueryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;
use Throwable;

class DatabaseToolController extends Controller
{
    public function backupForm()
    {
        return view('database-tools.backup');
    }

    public function backup()
    {
        try {
            $backups = [];

            foreach (['mysql', 'mysql_second'] as $connection) {
                $config = config("database.connections.{$connection}");
                $database = $config['database'];

                $filename = "backup_{$connection}_" . now()->format('Y_m_d_H_i_s') . '.sql';

                $backupDir = storage_path('app/backups');

                if (!file_exists($backupDir)) {
                    mkdir($backupDir, 0755, true);
                }

                $command = sprintf(
                    'mysqldump -h%s -P%s -u%s %s --single-transaction > %s',
                    escapeshellarg($config['host']),
                    escapeshellarg($config['port']),
                    escapeshellarg($config['username']),
                    escapeshellarg($database),
                    escapeshellarg($backupDir . '/' . $filename)
                );

                if (!empty($config['password'])) {
                    $command = sprintf(
                        'mysqldump -h%s -P%s -u%s -p%s %s --single-transaction > %s',
                        escapeshellarg($config['host']),
                        escapeshellarg($config['port']),
                        escapeshellarg($config['username']),
                        escapeshellarg($config['password']),
                        escapeshellarg($database),
                        escapeshellarg($backupDir . '/' . $filename)
                    );
                }

                exec($command, $output, $returnCode);

                if ($returnCode === 0 && file_exists($backupDir . '/' . $filename)) {
                    $backups[] = [
                        'connection' => $connection,
                        'database' => $database,
                        'filename' => $filename,
                        'path' => $backupDir . '/' . $filename,
                        'size' => filesize($backupDir . '/' . $filename),
                    ];
                }
            }

            activity()
                ->causedBy(auth()->user())
                ->log('Database backup created');

            return redirect()
                ->route('database-tools.backup')
                ->with('success', count($backups) . ' backup(s) created successfully.');
        } catch (Throwable $e) {
            Log::error('Database backup failed: ' . $e->getMessage());

            return redirect()
                ->route('database-tools.backup')
                ->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }

    public function restoreForm()
    {
        return view('database-tools.restore');
    }

    public function restore(Request $request)
    {
        $request->validate([
            'connection' => ['required', 'in:mysql,mysql_second'],
            'file' => ['required', 'file', 'mimes:sql', 'max:102400'],
        ]);

        try {
            $file = $request->file('file');
            $connection = $request->input('connection');

            $config = config("database.connections.{$connection}");
            $database = $config['database'];

            $command = sprintf(
                'mysql -h%s -P%s -u%s %s < %s',
                escapeshellarg($config['host']),
                escapeshellarg($config['port']),
                escapeshellarg($config['username']),
                escapeshellarg($database),
                escapeshellarg($file->getRealPath())
            );

            if (!empty($config['password'])) {
                $command = sprintf(
                    'mysql -h%s -P%s -u%s -p%s %s < %s',
                    escapeshellarg($config['host']),
                    escapeshellarg($config['port']),
                    escapeshellarg($config['username']),
                    escapeshellarg($config['password']),
                    escapeshellarg($database),
                    escapeshellarg($file->getRealPath())
                );
            }

            exec($command, $output, $returnCode);

            activity()
                ->causedBy(auth()->user())
                ->log("Database restored on {$connection}");

            if ($returnCode === 0) {
                return redirect()
                    ->route('database-tools.restore')
                    ->with('success', "Database restored successfully on {$connection}.");
            }

            return redirect()
                ->route('database-tools.restore')
                ->with('error', 'Restore command failed with code: ' . $returnCode);
        } catch (Throwable $e) {
            Log::error('Database restore failed: ' . $e->getMessage());

            return redirect()
                ->route('database-tools.restore')
                ->with('error', 'Restore failed: ' . $e->getMessage());
        }
    }

    public function migrateForm()
    {
        return view('database-tools.migrate');
    }

    public function runMigration(Request $request)
    {
        $request->validate([
            'connection' => ['nullable', 'in:mysql,mysql_second'],
            'path' => ['nullable', 'string'],
            'force' => ['nullable', 'boolean'],
        ]);

        try {
            $connection = $request->input('connection');
            $path = $request->input('path');
            $force = $request->boolean('force', false);

            if ($force && !auth()->user()?->hasRole('admin')) {
                return redirect()
                    ->route('database-tools.migrate')
                    ->with('error', 'Unauthorized. Admin role required for force migration.');
            }

            $options = ['--force' => $force];

            if ($connection) {
                $options['--database'] = $connection;
            }

            if ($path) {
                $options['--path'] = $path;
            }

            Artisan::call('migrate', $options);
            $output = Artisan::output();

            activity()
                ->causedBy(auth()->user())
                ->log("Migrations run on " . ($connection ?: 'default'));

            return redirect()
                ->route('database-tools.migrate')
                ->with('success', 'Migrations completed successfully.')
                ->with('output', $output);
        } catch (Throwable $e) {
            Log::error('Migration failed: ' . $e->getMessage());

            return redirect()
                ->route('database-tools.migrate')
                ->with('error', 'Migration failed: ' . $e->getMessage());
        }
    }

    public function seedForm()
    {
        return view('database-tools.seed');
    }

    public function runSeeder(Request $request)
    {
        $request->validate([
            'class' => ['nullable', 'string'],
            'force' => ['nullable', 'boolean'],
        ]);

        try {
            $class = $request->input('class');
            $force = $request->boolean('force', false);

            if ($force && !auth()->user()?->hasRole('admin')) {
                return redirect()
                    ->route('database-tools.seed')
                    ->with('error', 'Unauthorized. Admin role required for force seeding.');
            }

            $options = ['--force' => $force];

            if ($class) {
                $options['--class'] = $class;
            }

            Artisan::call('db:seed', $options);
            $output = Artisan::output();

            activity()
                ->causedBy(auth()->user())
                ->log('Seeders run');

            return redirect()
                ->route('database-tools.seed')
                ->with('success', 'Seeding completed successfully.')
                ->with('output', $output);
        } catch (Throwable $e) {
            Log::error('Seeding failed: ' . $e->getMessage());

            return redirect()
                ->route('database-tools.seed')
                ->with('error', 'Seeding failed: ' . $e->getMessage());
        }
    }

    public function queryForm()
    {
        return view('database-tools.query');
    }

    public function executeQuery(ExecuteQueryRequest $request)
    {
        $validated = $request->validated();
        $query = trim($validated['query']);
        $connection = $validated['connection'];

        $isAdmin = auth()->user()?->hasRole('admin');

        $writeCommands = ['INSERT', 'UPDATE', 'DELETE', 'DROP', 'CREATE', 'ALTER', 'TRUNCATE'];
        $firstWord = strtoupper(explode(' ', $query)[0]);

        if (in_array($firstWord, $writeCommands) && !$isAdmin) {
            return redirect()
                ->route('database-tools.query')
                ->with('error', 'Unauthorized. Admin role required for write operations.');
        }

        try {
            $results = DB::connection($connection)
                ->select($query);

            activity()
                ->causedBy(auth()->user())
                ->log("SQL query executed on {$connection}: {$query}");

            return redirect()
                ->route('database-tools.query')
                ->with('success', 'Query executed successfully.')
                ->with('results', $results);
        } catch (Throwable $e) {
            Log::error('Query execution failed: ' . $e->getMessage());

            return redirect()
                ->route('database-tools.query')
                ->with('error', 'Query failed: ' . $e->getMessage());
        }
    }

    public function compareDatabases(Request $request)
    {
        Log::info('compareDatabases called', [$request->all()]);

        try {
            $primaryTables = DB::connection('mysql')
                ->getSchemaBuilder()
                ->getTables();

            $secondaryTables = DB::connection('mysql_second')
                ->getSchemaBuilder()
                ->getTables();

            $primaryTableNames = collect($primaryTables)->pluck('name')->sort()->toArray();
            $secondaryTableNames = collect($secondaryTables)->pluck('name')->sort()->toArray();

            $missingInSecondary = array_diff($primaryTableNames, $secondaryTableNames);
            $missingInPrimary = array_diff($secondaryTableNames, $primaryTableNames);
            $commonTables = array_intersect($primaryTableNames, $secondaryTableNames);

            Log::info('compareDatabases tables loaded', [
                'primary' => count($primaryTableNames),
                'secondary' => count($secondaryTableNames),
                'common' => count($commonTables),
            ]);

            $tableDifferences = [];

            if ($request->filled('table') && in_array($request->input('table'), $commonTables)) {
                $table = $request->input('table');

                try {
                    $primaryColumns = DB::connection('mysql')
                        ->getSchemaBuilder()
                        ->getColumnListing($table);

                    $secondaryColumns = DB::connection('mysql_second')
                        ->getSchemaBuilder()
                        ->getColumnListing($table);

                    $missingColumnsInSecondary = array_diff($primaryColumns, $secondaryColumns);
                    $missingColumnsInPrimary = array_diff($secondaryColumns, $primaryColumns);

                    if (!empty($missingColumnsInSecondary) || !empty($missingColumnsInPrimary)) {
                        $tableDifferences[] = [
                            'table' => $table,
                            'missing_in_secondary' => $missingColumnsInSecondary,
                            'missing_in_primary' => $missingColumnsInPrimary,
                        ];
                    }
                } catch (Throwable $e) {
                    $tableDifferences[] = [
                        'table' => $table,
                        'error' => $e->getMessage(),
                    ];
                }
            }

            activity()
                ->causedBy(auth()->user())
                ->log('Database comparison performed');

            Log::info('compareDatabases returning view');

            return view('database-tools.compare', [
                'primaryTables' => $primaryTableNames,
                'secondaryTables' => $secondaryTableNames,
                'missingInSecondary' => $missingInSecondary,
                'missingInPrimary' => $missingInPrimary,
                'commonTables' => $commonTables,
                'tableDifferences' => $tableDifferences,
                'selectedTable' => $request->input('table'),
            ]);
        } catch (Throwable $e) {
            Log::error('Database comparison failed: ' . $e->getMessage());

            return redirect()
                ->route('database-tools.compare')
                ->with('error', 'Comparison failed: ' . $e->getMessage());
        }
    }
}
