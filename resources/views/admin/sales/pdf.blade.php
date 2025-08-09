<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sales Export</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h1 { font-size: 18px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; }
        th { background: #f2f2f2; text-align: left; }
    </style>
    </head>
<body>
    <h1>Sales (latest 200)</h1>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Ordered At</th>
                <th>Truck</th>
                <th>Franchise</th>
                <th>Total (€)</th>
            </tr>
        </thead>
        <tbody>
        @foreach($orders as $o)
            <tr>
                <td>{{ $o->id }}</td>
                <td>{{ \Carbon\Carbon::parse($o->ordered_at)->format('Y-m-d H:i') }}</td>
                <td>{{ optional($o->truck)->name }}</td>
                <td>{{ optional(optional($o->truck)->franchise)->name }}</td>
                <td>{{ number_format($o->total_price ?? 0, 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>
