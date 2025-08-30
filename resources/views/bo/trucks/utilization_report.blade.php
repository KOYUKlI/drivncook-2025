<!doctype html>
<html lang="fr">
<meta charset="utf-8">
<body style="font-family: system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif; color:#111;">
  <h1 style="font-size:20px; margin:0 0 8px;">Rapport d'utilisation de la flotte</h1>
  <div style="color:#555; margin-bottom:12px;">Du: {{ request('from') ?? ($from ?? '') }} — Au: {{ request('to') ?? ($to ?? '') }}</div>
  @php $rows = $rows ?? ($report ?? []); @endphp
  <table border="0" cellpadding="6" cellspacing="0" style="border-collapse: collapse; width:100%;">
    <thead>
      <tr style="background:#f3f4f6; border-bottom:1px solid #e5e7eb;">
        <th align="left" style="font-size:12px; text-transform:uppercase; color:#6b7280;">Truck</th>
        <th align="right" style="font-size:12px; text-transform:uppercase; color:#6b7280;">KM</th>
        <th align="right" style="font-size:12px; text-transform:uppercase; color:#6b7280;">Heures</th>
        <th align="right" style="font-size:12px; text-transform:uppercase; color:#6b7280;">Jours actifs</th>
        <th align="right" style="font-size:12px; text-transform:uppercase; color:#6b7280;">Revenu</th>
      </tr>
    </thead>
    <tbody>
      @forelse($rows as $idx => $r)
        <tr style="border-bottom:1px solid #e5e7eb; {{ $idx % 2 === 1 ? 'background:#fafafa;' : '' }}">
          <td style="padding:6px 8px;">{{ $r['truck'] ?? ($r->truck ?? '') }}</td>
          <td align="right" style="padding:6px 8px;">{{ $r['km'] ?? ($r->km ?? '') }}</td>
          <td align="right" style="padding:6px 8px;">{{ $r['hours'] ?? ($r->hours ?? '') }}</td>
          <td align="right" style="padding:6px 8px;">{{ $r['active_days'] ?? ($r->active_days ?? '') }}</td>
          <td align="right" style="padding:6px 8px;">{{ $r['revenue'] ?? ($r->revenue ?? '') }}</td>
        </tr>
      @empty
        <tr><td colspan="5" style="padding:10px; color:#6b7280;">Aucune donnée</td></tr>
      @endforelse
    </tbody>
  </table>
</body>
</html>
