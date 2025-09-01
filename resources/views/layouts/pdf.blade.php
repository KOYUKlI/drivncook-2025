<!DOCTYPE html>
<html lang="{{ str_starts_with(app()->getLocale(), 'fr') ? 'fr' : 'en' }}">
<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    <style>
        :root {
            --brand: #FF6B00;
            --dark: #2B2B2B;
            --muted: #555555;
            --line: #E5E7EB;
            --bg: #F3F4F6;
        }
        @page { margin: 14mm; }
        * { box-sizing: border-box; }
        html, body { padding: 0; margin: 0; }
    body { color: var(--dark); font: 12px 'DejaVu Sans', DejaVuSans, sans-serif; }
        h1, h2, h3 { margin: 0 0 8px 0; }
        .brand { color: var(--brand); }
        .muted { color: var(--muted); }
        .small { font-size: 10px; }
        .card { border: 1px solid var(--line); border-radius: 8px; padding: 12px; page-break-inside: avoid; }
        .grid { display: table; width: 100%; border-spacing: 12px 0; }
        .grid .col { display: table-cell; vertical-align: top; }
        .kpi { background: var(--bg); border-radius: 8px; padding: 12px; text-align: center; page-break-inside: avoid; }
        .kpi .value { font-size: 18px; font-weight: 700; color: var(--brand); }
        .kpi .label { font-size: 11px; color: var(--muted); }
    table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        thead { background: var(--bg); display: table-header-group; }
        tfoot { display: table-footer-group; }
        th, td { text-align: left; padding: 8px; border-bottom: 1px solid var(--line); }
        th.num, td.num { text-align: right; white-space: nowrap; }
    tbody tr:nth-child(even) { background: #FAFAFA; }
    /* Stable widths for typical 4-col numeric tables */
    .table-4col th:nth-child(1), .table-4col td:nth-child(1) { width: 38%; }
    .table-4col th:nth-child(2), .table-4col td:nth-child(2) { width: 18%; }
    .table-4col th:nth-child(3), .table-4col td:nth-child(3) { width: 22%; }
    .table-4col th:nth-child(4), .table-4col td:nth-child(4) { width: 22%; }
        .section { margin: 12px 0 18px; page-break-inside: avoid; }
        .section-title { font-size: 14px; font-weight: 700; margin-bottom: 6px; }
        .cover { page-break-after: always; }
        .break-before { page-break-before: always; }
        .summary { list-style: disc; padding-left: 18px; margin: 8px 0 0; }
        .badge { display: inline-block; background: var(--brand); color: #fff; border-radius: 999px; font-size: 10px; padding: 2px 8px; }

        /* Fixed footer with CSS counters */
        .footer { position: fixed; left: 0; right: 0; bottom: 6mm; height: 12px; color: var(--muted); font-size: 10px; border-top: 1px solid #eee; padding-top: 6px; }
        .footer .left { float: left; }
        .footer .right { float: right; }
        /* Page numbers via Dompdf PHP script below; no CSS counters to avoid 0 pages issue */
    </style>
</head>
<body>
<div class="footer">
    <div class="left">&copy; {{ date('Y') }} Driv’n Cook — @yield('period')</div>
    <div class="right"></div>
    <div style="clear: both"></div>
</div>

<script type="text/php">
if (isset($pdf)) {
    $font = $fontMetrics->get_font('DejaVu Sans', 'normal');
    $size = 8;
    $color = [0.33,0.33,0.33];
    // Right side page counter
    $text = "{{ __('pdf.footer.page') }} ".$PAGE_NUM." {{ __('pdf.footer.of') }} ".$PAGE_COUNT;
    $width = $fontMetrics->get_text_width($text, $font, $size);
    $x = $pdf->get_width() - $width - 28; // 28px right padding approx
    $y = $pdf->get_height() - 18; // above bottom
    $pdf->page_text($x, $y, $text, $font, $size, $color);
    // Left side period/copyright
    $leftText = "© ".date('Y')." Driv’n Cook — {{ trim($__env->yieldContent('period')) }}";
    $pdf->page_text(20, $y, $leftText, $font, $size, $color);
}
</script>

<main>
@yield('content')
</main>

</body>
</html>
