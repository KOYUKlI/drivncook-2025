<?php

namespace App\Http\Controllers\BO;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class); // admin-only via role middleware; additional gate if desired

        // Handle predefined periods
        $period = $request->input('period');
        $from = $request->date('from_date');
        $to = $request->date('to_date');
        
        if ($period && !$from && !$to) {
            $to = Carbon::today();
            
            switch ($period) {
                case '24h':
                    $from = Carbon::now()->subDay();
                    break;
                case '7d':
                    $from = Carbon::now()->subDays(7);
                    break;
                case '30d':
                    $from = Carbon::now()->subDays(30);
                    break;
            }
        }

        $userId = $request->string('user_id')->trim()->toString();
        $route = $request->string('route')->trim()->toString();

        $q = AuditLog::query()
            ->when($from, fn($qq) => $qq->whereDate('created_at', '>=', $from->format('Y-m-d')))
            ->when($to, fn($qq) => $qq->whereDate('created_at', '<=', $to->format('Y-m-d')))
            ->when($userId, fn($qq) => $qq->where('user_id', $userId))
            ->when($route, fn($qq) => $qq->where('route', 'like', "%$route%"))
            ->with('user')  // Eager load user relationship
            ->latest('created_at');

        // Export functionality
        if ($request->has('export')) {
            return $this->export($q, $request->input('export'));
        }

        $logs = $q->paginate(25)->withQueryString();
        $users = User::orderBy('name')->get(['id','name']);

        return view('bo.audit.index', [
            'logs' => $logs,
            'users' => $users,
            'filters' => [
                'from_date' => $from?->toDateString(),
                'to_date' => $to?->toDateString(),
                'user_id' => $userId,
                'route' => $route,
                'period' => $period,
            ],
        ]);
    }

    /**
     * Export audit logs to CSV or XLSX
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $format
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function export($query, $format = 'csv')
    {
        $this->authorize('viewAny', User::class);
        
        $logs = $query->get();
        $filename = 'audit_logs_' . date('Y-m-d_His') . '.' . $format;
        
        if ($format === 'csv') {
            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $delimiter = app()->getLocale() === 'fr' ? ';' : ',';

            $callback = function() use ($logs, $delimiter) {
                $handle = fopen('php://output', 'w');

                // UTF-8 BOM for Excel
                fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

                $sanitize = function ($value) {
                    $str = (string) ($value ?? '');
                    if ($str !== '' && preg_match('/^[=+\-@]/', $str)) {
                        return "'".$str;
                    }
                    return $str;
                };

                // CSV header
                fputcsv($handle, [
                    __('audit.timestamp'),
                    __('audit.user'),
                    __('audit.method'),
                    __('audit.route'),
                    __('audit.resource'),
                    __('audit.ip'),
                    __('audit.user_agent'),
                ], $delimiter);

                // CSV rows
                foreach ($logs as $log) {
                    fputcsv($handle, [
                        optional($log->created_at)->format('Y-m-d H:i:s'),
                        $sanitize(optional($log->user)->name),
                        $log->method,
                        $sanitize($log->route),
                        $sanitize($log->subject_type ? Str::afterLast($log->subject_type, '\\') . ' #' . $log->subject_id : ''),
                        $log->ip,
                        $sanitize($log->user_agent),
                    ], $delimiter);
                }

                fclose($handle);
            };

            return response()->stream($callback, Response::HTTP_OK, $headers);
        }
        
        // XLSX export would require a package like Laravel Excel or Spout
        // For this implementation, we'll return a simple notification if XLSX is requested
        return back()->with('notice', __('audit.xlsx_not_implemented'));
    }
}
