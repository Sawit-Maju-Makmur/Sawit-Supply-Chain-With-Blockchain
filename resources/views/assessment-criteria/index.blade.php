@extends('layouts.app')

@section('content')
<div class="shell">
    <aside class="sidebar">
        <div class="brand"><span class="brand-mark">S</span><span>SIPS Sawit</span></div>
        <div class="side-label">Sustainability Officer</div>
        <nav><a class="nav-link active" href="{{ route('assessment-criteria.index') }}"><span>⚙</span> Kriteria penilaian</a></nav>
    </aside>

    <main class="main">
        <header class="hd">
            <div>
                <div class="eyebrow">PENGATURAN EVALUASI</div>
                <h1>Kriteria dan bobot penilaian</h1>
                <p>Atur parameter evaluasi. Total bobot tiap kategori harus tepat 100%.</p>
            </div>
            <span class="pbi">PBI 10, Sprint 1</span>
        </header>

        @if ($errors->any())
            <div class="alert error">{{ $errors->first() }}</div>
        @endif
        @if (session('success'))
            <div class="alert success">{{ session('success') }}</div>
        @endif

        @if ($activeVersion)
            <section class="version-bar">
                <div><strong>Versi Aktif: v{{ $activeVersion->version_number }}</strong><span>Diterapkan untuk evaluasi baru</span></div>
                <label for="version-history">Riwayat versi</label>
                <select id="version-history">
                    @foreach ($versions as $version)
                        <option value="{{ $version->id }}" @selected($version->id === $displayVersion?->id)>v{{ $version->version_number }}{{ $version->is_active ? ' (aktif)' : ' (read-only)' }}</option>
                    @endforeach
                </select>
            </section>
        @endif

        <form method="POST" action="{{ route('assessment-criteria.store') }}" id="criteria-form">
            @csrf
            @foreach (['Sustainability', 'Traceability'] as $category)
                @php($items = $displayVersion?->criteria->where('category', $category) ?? collect())
                <section class="card category-card" data-category="{{ $category }}">
                    <div class="card-head">
                        <div><h2>{{ $category }}</h2><p>{{ $category === 'Sustainability' ? 'Prinsip dan praktik keberlanjutan pemasok.' : 'Pencatatan asal dan aliran TBS.' }}</p></div>
                        <span class="chip total-chip ok">Total <strong>100</strong>%</span>
                    </div>
                    <div class="wrap">
                        <table>
                            <thead><tr><th>Kriteria</th><th>Pertanyaan self-assessment</th><th class="weight-col">Bobot</th><th></th></tr></thead>
                            <tbody>
                                @foreach ($items as $index => $item)
                                    <tr>
                                        <td><input type="text" name="criteria[{{ $category }}_{{ $index }}][name]" value="{{ $item->name }}" required @readonly($isReadOnly)><input type="hidden" name="criteria[{{ $category }}_{{ $index }}][category]" value="{{ $category }}"></td>
                                        <td><input type="text" name="criteria[{{ $category }}_{{ $index }}][question_text]" value="{{ $item->question_text }}" required @readonly($isReadOnly)></td>
                                        <td class="weight-col"><div class="weight-input"><input type="number" name="criteria[{{ $category }}_{{ $index }}][weight_percentage]" value="{{ rtrim(rtrim($item->weight_percentage, '0'), '.') }}" min="0" max="100" step="0.01" required @readonly($isReadOnly)><span>%</span></div></td>
                                        <td>@unless($isReadOnly)<button type="button" class="remove" aria-label="Hapus kriteria">×</button>@endunless</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @unless($isReadOnly)<button type="button" class="btn btn-ghost add-criterion">+ Tambah Kriteria</button>@endunless
                </section>
            @endforeach

            @unless($isReadOnly)<div class="form-footer">
                <input class="notes-input" type="text" name="notes" placeholder="Catatan versi (opsional)">
                <button type="submit" class="btn btn-primary" id="save-button">Simpan kriteria baru (versi berikutnya)</button>
            </div>@endunless
        </form>
    </main>
</div>

<template id="criterion-row">
    <tr>
        <td><input type="text" data-field="name" required><input type="hidden" data-field="category"></td>
        <td><input type="text" data-field="question_text" required></td>
        <td class="weight-col"><div class="weight-input"><input type="number" data-field="weight_percentage" value="0" min="0" max="100" step="0.01" required><span>%</span></div></td>
        <td><button type="button" class="remove" aria-label="Hapus kriteria">×</button></td>
    </tr>
</template>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('criteria-form');
    const saveButton = document.getElementById('save-button');
    const template = document.getElementById('criterion-row');
    let rowId = Date.now();

    const updateCategory = (card) => {
        let total = 0;
        card.querySelectorAll('input[name*="[weight_percentage]"]').forEach((input) => total += Number(input.value) || 0);
        const chip = card.querySelector('.total-chip');
        chip.querySelector('strong').textContent = Number.isInteger(total) ? total : total.toFixed(2);
        chip.classList.toggle('ok', Math.abs(total - 100) < 0.001);
        chip.classList.toggle('er', Math.abs(total - 100) >= 0.001);
        return Math.abs(total - 100) < 0.001;
    };

    const updateAll = () => {
        const valid = [...document.querySelectorAll('.category-card')].map(updateCategory).every(Boolean);
        if (saveButton) saveButton.disabled = !valid;
    };

    document.getElementById('version-history')?.addEventListener('change', (event) => {
        window.location.href = `${window.location.pathname}?version=${event.target.value}`;
    });

    document.querySelectorAll('.category-card').forEach((card) => {
        card.addEventListener('input', updateAll);
        card.querySelector('.add-criterion')?.addEventListener('click', () => {
            const fragment = template.content.cloneNode(true);
            const category = card.dataset.category;
            fragment.querySelectorAll('[data-field]').forEach((input) => {
                const field = input.dataset.field;
                input.name = `criteria[${category}_${rowId}][${field}]`;
                if (field === 'category') input.value = category;
            });
            rowId++;
            card.querySelector('tbody').appendChild(fragment);
            updateAll();
        });
        card.addEventListener('click', (event) => {
            if (event.target.classList.contains('remove')) {
                event.target.closest('tr').remove();
                updateAll();
            }
        });
    });

    form.addEventListener('submit', (event) => {
        if (![...document.querySelectorAll('.category-card')].map(updateCategory).every(Boolean)) {
            event.preventDefault();
            window.alert('Total bobot setiap kategori harus tepat 100%.');
        }
    });
    updateAll();
});
</script>
@endsection
