@php
  $locale = app()->getLocale();
  $isFr = str_starts_with($locale, 'fr');
  $formatMoney = function (int $cents) use ($isFr) {
    $amount = $cents / 100;
    if (class_exists('NumberFormatter')) {
      $locale = $isFr ? 'fr_FR' : 'en_US';
      $fmt = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
      // Force EUR and preserve spacing similar to examples
      $out = $fmt->formatCurrency($amount, 'EUR');
      // Normalize thin/nb spaces to regular space to avoid '?' glyphs in PDFs
      $out = str_replace(["\u{00A0}", "\u{202F}"], ' ', $out);
      return $out;
    }
    return $isFr ? number_format($amount, 2, ',', ' ').' €' : '€'.number_format($amount, 2, '.', ',');
  };
  $formatDate = function (string $ymd) use ($isFr) {
    $dt = \Carbon\Carbon::parse($ymd);
    return $isFr ? $dt->format('d/m/Y') : $dt->format('Y-m-d');
  };
  $periodLabel = \Carbon\Carbon::createFromDate($viewModel['year'], $viewModel['month'], 1)
    ->locale($isFr ? 'fr' : 'en')->isoFormat('MMMM YYYY');
@endphp

@extends('layouts.pdf')

@section('title'){{ __('pdf.monthly.title') }} — {{ $periodLabel }}@endsection
@section('period'){{ $periodLabel }}@endsection

@section('content')
  @include('pdfs.reports.partials._cover', ['viewModel' => $viewModel, 'periodLabel' => $periodLabel])
  @include('pdfs.reports.partials._kpis', ['kpis' => $viewModel['kpis'], 'formatMoney' => $formatMoney])
  @include('pdfs.reports.partials._daily', ['daily' => $viewModel['daily'], 'formatMoney' => $formatMoney, 'formatDate' => $formatDate])
  @includeWhen(!empty($viewModel['per_truck'] ?? []), 'pdfs.reports.partials._by_truck', ['perTruck' => $viewModel['per_truck'], 'formatMoney' => $formatMoney])
  @includeWhen(!empty($viewModel['top_products'] ?? []), 'pdfs.reports.partials._top_products', ['topProducts' => $viewModel['top_products'], 'formatMoney' => $formatMoney, 'isFr' => $isFr])
  @include('pdfs.reports.partials._notes', ['observations' => $viewModel['observations'] ?? [], 'formatMoney' => $formatMoney, 'formatDate' => $formatDate, 'isFr' => $isFr])
@endsection
