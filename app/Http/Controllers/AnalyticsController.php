<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class AnalyticsController extends Controller
{
    public function dashboard()
    {
        $primaryProducts = 0;
        $secondaryProducts = 0;
        $totalBlogs = 0;
        $publishedBlogs = 0;
        $featuredBlogs = 0;

        try {
            $primaryProducts = DB::connection('mysql')->table('products')->count();
        } catch (Throwable $e) {
            $primaryProducts = 0;
        }

        try {
            $secondaryProducts = DB::connection('mysql_second')->table('products')->count();
        } catch (Throwable $e) {
            $secondaryProducts = 0;
        }

        try {
            $totalBlogs = Blog::count();
            $publishedBlogs = Blog::where('is_published', true)->count();
            $featuredBlogs = Blog::where('is_featured', true)->count();
        } catch (Throwable $e) {
            $totalBlogs = 0;
            $publishedBlogs = 0;
            $featuredBlogs = 0;
        }

        $categories = ProductCategory::count();
        $tags = ProductTag::count();

        $syncedCount = 0;
        $pendingSyncCount = 0;

        try {
            $primaryNames = DB::connection('mysql')->table('products')->pluck('name');

            if ($primaryNames->isNotEmpty()) {
                $syncedCount = DB::connection('mysql_second')
                    ->table('products')
                    ->whereIn('name', $primaryNames)
                    ->count();

                $pendingSyncCount = $primaryNames->count() - $syncedCount;

                if ($pendingSyncCount < 0) {
                    $pendingSyncCount = 0;
                }
            }
        } catch (Throwable $e) {
            $syncedCount = 0;
            $pendingSyncCount = 0;
        }

        $chartData = [
            'products_by_database' => [
                'labels' => ['Primary', 'Secondary'],
                'data' => [$primaryProducts, $secondaryProducts],
            ],
            'blogs_by_status' => [
                'labels' => ['Published', 'Drafts', 'Featured'],
                'data' => [$publishedBlogs, $totalBlogs - $publishedBlogs, $featuredBlogs],
            ],
            'sync_stats' => [
                'labels' => ['Synced', 'Pending'],
                'data' => [$syncedCount, $pendingSyncCount],
            ],
        ];

        return view('analytics.dashboard', compact(
            'primaryProducts',
            'secondaryProducts',
            'totalBlogs',
            'publishedBlogs',
            'featuredBlogs',
            'categories',
            'tags',
            'syncedCount',
            'pendingSyncCount',
            'chartData'
        ));
    }

    public function productStats()
    {
        $stats = [];

        try {
            $stats['by_database'] = [
                'Primary' => DB::connection('mysql')->table('products')->count(),
                'Secondary' => DB::connection('mysql_second')->table('products')->count(),
            ];
        } catch (Throwable $e) {
            $stats['by_database'] = ['Primary' => 0, 'Secondary' => 0];
        }

        try {
            $stats['by_category'] = ProductCategory::withCount('products')->get()->map(function ($category) {
                return [
                    'name' => $category->name,
                    'count' => $category->products_count,
                ];
            })->toArray();
        } catch (Throwable $e) {
            $stats['by_category'] = [];
        }

        try {
            $stats['by_tag'] = ProductTag::withCount('products')->get()->map(function ($tag) {
                return [
                    'name' => $tag->name,
                    'count' => $tag->products_count,
                ];
            })->toArray();
        } catch (Throwable $e) {
            $stats['by_tag'] = [];
        }

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }

    public function syncStats()
    {
        $stats = [
            'synced' => 0,
            'pending' => 0,
            'history' => [],
        ];

        try {
            $primaryNames = DB::connection('mysql')->table('products')->pluck('name');

            if ($primaryNames->isNotEmpty()) {
                $syncedCount = DB::connection('mysql_second')
                    ->table('products')
                    ->whereIn('name', $primaryNames)
                    ->count();

                $stats['synced'] = $syncedCount;
                $stats['pending'] = $primaryNames->count() - $syncedCount;

                if ($stats['pending'] < 0) {
                    $stats['pending'] = 0;
                }
            }
        } catch (Throwable $e) {
            $stats['synced'] = 0;
            $stats['pending'] = 0;
        }

        try {
            $stats['history'] = \Spatie\Activitylog\Models\Activity::query()
                ->where('log_name', 'like', '%sync%')
                ->orWhere('description', 'like', '%sync%')
                ->latest()
                ->take(50)
                ->get()
                ->map(function ($activity) {
                    return [
                        'description' => $activity->description,
                        'causer' => $activity->causer?->name,
                        'created_at' => $activity->created_at->toDateTimeString(),
                    ];
                })
                ->toArray();
        } catch (Throwable $e) {
            $stats['history'] = [];
        }

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }

    public function exportPdf()
    {
        try {
            $primaryProducts = 0;
            $secondaryProducts = 0;

            try {
                $primaryProducts = DB::connection('mysql')->table('products')->count();
            } catch (Throwable $e) {
                $primaryProducts = 0;
            }

            try {
                $secondaryProducts = DB::connection('mysql_second')->table('products')->count();
            } catch (Throwable $e) {
                $secondaryProducts = 0;
            }

            $totalBlogs = Blog::count();
            $publishedBlogs = Blog::where('is_published', true)->count();
            $categories = ProductCategory::count();
            $tags = ProductTag::count();

            $data = [
                'primaryProducts' => $primaryProducts,
                'secondaryProducts' => $secondaryProducts,
                'totalProducts' => $primaryProducts + $secondaryProducts,
                'totalBlogs' => $totalBlogs,
                'publishedBlogs' => $publishedBlogs,
                'categories' => $categories,
                'tags' => $tags,
                'generatedAt' => now()->format('Y-m-d H:i:s'),
            ];

            $pdf = Pdf::loadView('analytics.pdf', $data);

            $filename = 'analytics_report_' . now()->format('Y_m_d_H_i_s') . '.pdf';

            return $pdf->download($filename);
        } catch (Throwable $e) {
            Log::error('PDF export failed: ' . $e->getMessage());

            return back()->with('error', 'PDF export failed: ' . $e->getMessage());
        }
    }
}
