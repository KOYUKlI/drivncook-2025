<!doctype html>
<html lang="fr"><meta charset="utf-8">
<body>
  <h1>Rapport d'utilisation de la flotte</h1>
  <div>Du: {{ request('from') ?? ($from ?? '') }} — Au: {{ request('to') ?? ($to ?? '') }}</div>
  @php $rows = $rows ?? ($report ?? []); @endphp
  <table border="1" cellpadding="6" cellspacing="0">
    <thead><tr>
      <th>Truck</th><th>KM</th><th>Heures</th><th>Jours actifs</th><th>Revenue</th>
    </tr></thead>
    <tbody>
      @forelse($rows as $r)
        <tr>
          <td>{{ $r['truck'] ?? ($r->truck ?? '') }}</td>
          <td>{{ $r['km'] ?? ($r->km ?? '') }}</td>
          <td>{{ $r['hours'] ?? ($r->hours ?? '') }}</td>
          <td>{{ $r['active_days'] ?? ($r->active_days ?? '') }}</td>
          <td>{{ $r['revenue'] ?? ($r->revenue ?? '') }}</td>
        </tr>
      @empty
        <tr><td colspan="5">Aucune donnée</td></tr>
      @endforelse
    </tbody>
  </table>
</body>
</html>
