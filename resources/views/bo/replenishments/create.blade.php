@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.sidebar')
@endsection

@section('content')
    <x-ui.breadcrumbs :items="[
        ['title' => __('ui.dashboard'), 'url' => route('bo.dashboard')],
        ['title' => __('ui.replenishments.title'), 'url' => route('bo.replenishments.index')],
        ['title' => __('ui.replenishments.create')],
    ]" />
    <div class="p-6 max-w-4xl mx-auto">
        <h1 class="text-xl font-semibold mb-4">{{ __('ui.replenishments.create') }}</h1>

        <form method="POST" action="{{ route('bo.replenishments.store') }}" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <label class="block">
                    <span class="block text-sm text-gray-700 mb-1">{{ __('ui.common.warehouse') }}</span>
                    <select name="warehouse_id" id="warehouse_id" class="w-full border rounded p-2" required>
                        <option value="">-- {{ __('ui.common.select_option') }} --</option>
                        @foreach ($warehouses as $w)
                            <option value="{{ $w->id }}">{{ $w->name }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="block">
                    <span class="block text-sm text-gray-700 mb-1">{{ __('ui.common.franchisee') }}</span>
                    <select name="franchisee_id" class="w-full border rounded p-2" required>
                        <option value="">-- {{ __('ui.common.select_option') }} --</option>
                        @foreach ($franchisees as $f)
                            <option value="{{ $f->id }}">{{ $f->name }}</option>
                        @endforeach
                    </select>
                </label>
            </div>

            <div>
                <h2 class="font-medium mb-2">{{ __('ui.common.lines') }}</h2>
                <div id="lines" class="space-y-2"></div>
                <button type="button" id="add-line"
                    class="btn btn-secondary mt-2">{{ __('ui.common.add_line') }}</button>
            </div>

            <div class="pt-4">
                <button type="submit" class="btn btn-primary">{{ __('ui.common.save') }}</button>
                <a href="{{ route('bo.replenishments.index') }}" class="btn">{{ __('ui.common.cancel') }}</a>
            </div>
        </form>
    </div>

    @push('scripts')
                <script>
                    const warehouseInventories = @json($warehouseInventories ?? collect());
                    const selectPlaceholder = @json(__('ui.common.select_option'));
                    const removeLabel = @json(__('ui.common.remove_line'));
            let lineIdx = 0;
            const lines = document.getElementById('lines');
            document.getElementById('add-line').addEventListener('click', addLine);
                        document.getElementById('warehouse_id').addEventListener('change', refreshAllSelects);

                    // Base options HTML (all items with data attributes), like stock-movements/create
                    const baseOptions = `
                        @foreach ($stockItems as $s)
                            <option value="{{ $s->id }}" data-name="{{ $s->name }}" data-unit="{{ $s->unit ?? '' }}">{{ $s->name }}@if($s->unit) ({{ $s->unit }})@endif</option>
                        @endforeach
                    `;

            function addLine() {
                const idx = lineIdx++;
                const row = document.createElement('div');
                row.className = 'grid grid-cols-12 gap-2 items-center';
                            row.innerHTML = `
            <div class="col-span-6">
                <select data-line-idx="${idx}" name="lines[${idx}][stock_item_id]" class="w-full border rounded p-2 item-select" required>
                        <option value="">-- ${selectPlaceholder} --</option>
                        ${baseOptions}
                </select>
            </div>
            <div class="col-span-3">
                <input type="number" min="1" class="w-full border rounded p-2" name="lines[${idx}][qty]" placeholder="{{ __('ui.common.qty') }}" required />
            </div>
            <div class="col-span-2">
                <input type="number" min="0" step="1" class="w-full border rounded p-2" name="lines[${idx}][unit_price_cents]" placeholder="{{ __('ui.misc.cents') }}" required />
            </div>
            <div class="col-span-1 text-right">
                <button type="button" class="btn btn-danger" aria-label="${removeLabel}">&times;</button>
            </div>`;
                lines.appendChild(row);
                                // Wire remove
                                row.querySelector('button.btn-danger').addEventListener('click', () => row.remove());
                                // Ensure options reflect current warehouse
                                filterSelectByWarehouse(row.querySelector('select.item-select'));
            }

                        function currentWarehouseId() {
                                const sel = document.getElementById('warehouse_id');
                                return sel && sel.value ? sel.value : null;
                        }

                            function formatOption(optionEl, qty) {
                                const name = optionEl.getAttribute('data-name') || optionEl.textContent.trim();
                                const unit = optionEl.getAttribute('data-unit') || '';
                                const baseLabel = unit ? `${name} (${unit})` : name;
                                optionEl.textContent = (qty !== null && qty !== undefined) ? `${baseLabel} — ${qty}` : baseLabel;
                            }

                            function filterSelectByWarehouse(selectEl) {
                                if (!selectEl) return;
                                const wid = currentWarehouseId();
                                const inv = warehouseInventories[wid] || [];
                                const qtyMap = Object.fromEntries(inv.map(i => [i.id, i.qty_on_hand]));

                                Array.from(selectEl.options).forEach(opt => {
                                    if (!opt.value) return; // skip placeholder
                                    if (!wid) {
                                        opt.hidden = false;
                                        formatOption(opt);
                                        return;
                                    }
                                    const qty = qtyMap[opt.value];
                                    if (qty === undefined || qty <= 0) {
                                        opt.hidden = true;
                                        formatOption(opt); // reset to base label
                                    } else {
                                        opt.hidden = false;
                                        formatOption(opt, qty);
                                    }
                                });

                                // If selected is hidden, reset to placeholder
                                const current = selectEl.options[selectEl.selectedIndex];
                                if (current && current.hidden) {
                                    selectEl.selectedIndex = 0;
                                    const event = new Event('change');
                                    selectEl.dispatchEvent(event);
                                }
                            }

                        function refreshAllSelects() {
                                document.querySelectorAll('select.item-select').forEach(filterSelectByWarehouse);
                        }

                        // Start with one line; options will be empty until a warehouse is selected
                        addLine();
                            // Apply initial filtering/formatting for the first line (if any)
                            refreshAllSelects();
        </script>
    @endpush
@endsection
