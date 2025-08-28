<!doctype html>
<html lang="fr"><meta charset="utf-8">
<body>
  <h1>Rapport de conformité (80/20)</h1>
  <div>Du: {{ request('from') ?? ($from ?? '') }} — Au: {{ request('to') ?? ($to ?? '') }}</div>
  @php $orders = $orders ?? []; @endphp
  <table border="1" cellpadding="6" cellspacing="0">
    <thead><tr>
      <th>ID</th><th>Ratio central</th><th>Statut</th><th>Override</th><th>Créé le</th>
    </tr></thead>
    <tbody>
      @forelse($orders as $po)
        <tr>
          <td>{{ $po->id ?? '' }}</td>
          <td>{{ $po->central_ratio ?? '' }}</td>
          <td>{{ $po->status ?? '' }}</td>
          <td>{{ $po->override_reason ?? '' }}</td>
          <td>{{ $po->created_at ?? '' }}</td>
        </tr>
      @empty
        <tr><td colspan="5">Aucune donnée</td></tr>
      @endforelse
    </tbody>
  </table>
</body>
</html>
