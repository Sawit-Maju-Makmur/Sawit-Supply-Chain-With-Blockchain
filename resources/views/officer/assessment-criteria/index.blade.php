@extends('layouts.management')

@section('title', 'Kriteria Asesmen')

@section('content')
    <div class="hd">
        <div>
            <h1>Kriteria dan Bobot Asesmen</h1>
            <p class="mut">Atur kriteria Sustainability dan Traceability. Total bobot kriteria aktif wajib tepat 100% pada setiap kategori.</p>
        </div>
        <span class="pbi">Sustainability Officer</span>
    </div>

    @if ($errors->any())
        <div class="alert er" role="alert">
            <ul style="margin:0; padding-left:1.25rem;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @foreach ($categories as $category => $label)
        @php
            $storedCriteria = $criteriaByCategory->get($category, collect());
            $rows = old('category') === $category
                ? old('criteria', [])
                : $storedCriteria->all();
            if (count($rows) === 0) {
                $rows = [['name' => '', 'description' => '', 'regulatory_reference' => '', 'weight' => '100', 'active' => '1']];
            }
            $activeWeight = collect($rows)->sum(function ($row) {
                $active = is_array($row) ? ($row['active'] ?? $row['is_active'] ?? false) : $row->is_active;
                $deleted = is_array($row) ? ($row['delete'] ?? false) : false;
                $weight = is_array($row) ? ($row['weight'] ?? 0) : $row->weight;

                return filter_var($active, FILTER_VALIDATE_BOOLEAN) && ! filter_var($deleted, FILTER_VALIDATE_BOOLEAN)
                    ? (float) $weight
                    : 0;
            });
        @endphp

        <section class="card">
            <div class="hd">
                <div>
                    <h2 style="margin:0 0 4px;">{{ $label }}</h2>
                    <p class="mut" style="margin:0;">Bobot aktif saat ini: <strong>{{ number_format($activeWeight, 2, ',', '.') }}%</strong> / 100%</p>
                </div>
                <span class="chip {{ round($activeWeight, 2) === 100.0 ? 'ok' : 'wa' }}">
                    {{ round($activeWeight, 2) === 100.0 ? 'Siap digunakan' : 'Belum 100%' }}
                </span>
            </div>

            <form method="POST" action="{{ route('officer.criteria.update', $category) }}" data-criteria-form>
                @csrf
                @method('PUT')
                <input type="hidden" name="category" value="{{ $category }}">
                <div class="tw">
                    <table style="min-width:1000px;">
                        <thead>
                            <tr>
                                <th>Kriteria</th>
                                <th>Deskripsi</th>
                                <th>Referensi regulasi</th>
                                <th>Bobot (%)</th>
                                <th>Aktif</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody data-criteria-list>
                            @foreach ($rows as $index => $criterion)
                                @php
                                    $value = fn ($key, $fallback = '') => is_array($criterion)
                                        ? ($criterion[$key] ?? $fallback)
                                        : ($criterion->$key ?? $fallback);
                                    $criterionId = $value('id');
                                    $isActive = filter_var($value('active', $value('is_active', true)), FILTER_VALIDATE_BOOLEAN);
                                    $isDeleted = filter_var($value('delete', false), FILTER_VALIDATE_BOOLEAN);
                                @endphp
                                <tr>
                                    <td>
                                        <input type="hidden" name="criteria[{{ $index }}][id]" value="{{ $criterionId }}">
                                        <input type="text" name="criteria[{{ $index }}][name]" value="{{ $value('name') }}" maxlength="160" required placeholder="Nama kriteria" style="width:190px;">
                                    </td>
                                    <td><textarea name="criteria[{{ $index }}][description]" maxlength="2000" placeholder="Jelaskan indikator yang dinilai" style="width:220px; min-height:64px;">{{ $value('description') }}</textarea></td>
                                    <td><input type="text" name="criteria[{{ $index }}][regulatory_reference]" value="{{ $value('regulatory_reference') }}" maxlength="255" placeholder="Contoh: nomor regulasi/pasal" style="width:190px;"></td>
                                    <td><input type="number" name="criteria[{{ $index }}][weight]" value="{{ $value('weight', '0') }}" min="0" max="100" step="0.01" required style="width:100px;"></td>
                                    <td>
                                        <input type="hidden" name="criteria[{{ $index }}][active]" value="0">
                                        <label><input type="checkbox" name="criteria[{{ $index }}][active]" value="1" @checked($isActive)> Aktif</label>
                                    </td>
                                    <td>
                                        @if ($criterionId)
                                            <label><input type="checkbox" name="criteria[{{ $index }}][delete]" value="1" @checked($isDeleted)> Hapus</label>
                                        @else
                                            <button type="button" class="btn d" data-remove-row>Hapus baris</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <template data-row-template>
                    <tr>
                        <td>
                            <input type="hidden" name="criteria[__INDEX__][id]" value="">
                            <input type="text" name="criteria[__INDEX__][name]" value="" maxlength="160" required placeholder="Nama kriteria" style="width:190px;">
                        </td>
                        <td><textarea name="criteria[__INDEX__][description]" maxlength="2000" placeholder="Jelaskan indikator yang dinilai" style="width:220px; min-height:64px;"></textarea></td>
                        <td><input type="text" name="criteria[__INDEX__][regulatory_reference]" value="" maxlength="255" placeholder="Contoh: nomor regulasi/pasal" style="width:190px;"></td>
                        <td><input type="number" name="criteria[__INDEX__][weight]" value="0" min="0" max="100" step="0.01" required style="width:100px;"></td>
                        <td>
                            <input type="hidden" name="criteria[__INDEX__][active]" value="0">
                            <label><input type="checkbox" name="criteria[__INDEX__][active]" value="1" checked> Aktif</label>
                        </td>
                        <td><button type="button" class="btn d" data-remove-row>Hapus baris</button></td>
                    </tr>
                </template>
                <div class="bar" style="margin-top:12px; margin-bottom:0;">
                    <button type="button" class="btn l" data-add-row>Tambah kriteria</button>
                    <button type="submit" class="btn">Simpan {{ $label }}</button>
                </div>
            </form>

            @if ($storedCriteria->isNotEmpty())
                <p class="mut" style="margin:12px 0 0;">
                    Terakhir diubah {{ optional($storedCriteria->max('updated_at'))->format('d/m/Y H:i') }}
                    oleh {{ $storedCriteria->sortByDesc('updated_at')->first()->updatedBy?->name ?? 'akun yang sudah dihapus' }}.
                </p>
            @endif
        </section>
    @endforeach

    <script>
        document.querySelectorAll('[data-criteria-form]').forEach(form => {
            let nextIndex = form.querySelectorAll('[data-criteria-list] tr').length;
            const list = form.querySelector('[data-criteria-list]');
            const template = form.querySelector('[data-row-template]');

            form.querySelector('[data-add-row]').addEventListener('click', () => {
                const row = template.content.cloneNode(true);
                row.querySelectorAll('[name]').forEach(input => {
                    input.name = input.name.replaceAll('__INDEX__', String(nextIndex));
                });
                list.appendChild(row);
                nextIndex += 1;
            });

            list.addEventListener('click', event => {
                if (event.target.matches('[data-remove-row]')) {
                    event.target.closest('tr').remove();
                }
            });
        });
    </script>
@endsection
