@extends('layouts.app')

@section('title', 'Hasil Self-Assessment')

@section('content')
    @php $info = $assessment->level_info; @endphp

    <h1 class="page-title">Hasil Evaluasi Kepatuhan</h1>
    <p class="page-sub">{{ $assessment->nama_kebun }} · {{ $assessment->nama_petani }}</p>

    <div class="card">
        <div class="result">
            <div class="score-circle" style="--p: {{ (float) $assessment->percentage }}">
                <b>{{ rtrim(rtrim(number_format($assessment->percentage, 2), '0'), '.') }}%</b>
                <small>{{ $assessment->total_score }}/{{ $assessment->max_score }} poin</small>
            </div>
            <div>
                <div>Tingkat Kepatuhan</div>
                <span class="badge {{ $info['class'] }}" style="font-size:1rem;padding:6px 18px;">
                    {{ $assessment->level }}
                </span>
                <p style="color:var(--muted);margin:10px 0 0;">{{ $info['note'] }}</p>
            </div>
        </div>
    </div>

    <div class="card">
        <h2>Data Kebun</h2>
        <ul class="detail-list">
            <li><span>Nama Petani</span><b>{{ $assessment->nama_petani }}</b></li>
            <li><span>Nama Kebun</span><b>{{ $assessment->nama_kebun }}</b></li>
            <li><span>Lokasi</span><b>{{ $assessment->lokasi }}</b></li>
            <li><span>Luas Lahan</span><b>{{ $assessment->luas_lahan }} Ha</b></li>
            <li><span>Tahun Tanam</span><b>{{ $assessment->tahun_tanam ?? '-' }}</b></li>
            <li><span>Tanggal Pengisian</span><b>{{ $assessment->created_at->translatedFormat('d F Y, H:i') }}</b></li>
        </ul>
    </div>

    @foreach ($categories as $category)
        <div class="card">
            <h2>{{ $category['title'] }}</h2>
            <ul class="detail-list">
                @foreach ($category['questions'] as $key => $text)
                    @php
                        $ans = $assessment->answers[$key] ?? 'tidak';
                        $cls = ['ya' => 'badge-high', 'sebagian' => 'badge-medium', 'tidak' => 'badge-low'][$ans];
                    @endphp
                    <li>
                        <span>{{ $text }}</span>
                        <span class="badge {{ $cls }}">{{ $options[$ans]['label'] }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    @endforeach

    @if ($assessment->catatan)
        <div class="card">
            <h2>Catatan</h2>
            <p style="margin:0;">{{ $assessment->catatan }}</p>
        </div>
    @endif

    <div class="actions">
        <a href="{{ route('assessments.index') }}" class="btn btn-outline">Kembali</a>
        <a href="{{ route('assessments.create') }}" class="btn btn-primary">Isi Formulir Baru</a>
    </div>
@endsection
