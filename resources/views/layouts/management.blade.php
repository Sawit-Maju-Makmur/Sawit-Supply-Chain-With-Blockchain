<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - SIPS Sawit</title>
    <style>
        :root{--bg:#f3f6f4;--card:#fff;--tx:#12261c;--mut:#5d7066;--br:#dfe7e2;--pr:#1f7a4f;--prs:#e2f2e9;--wa:#a8670a;--was:#fcefd3;--er:#b83232;--ers:#fbe4e4;--side:#12382a}
        @media (prefers-color-scheme:dark){:root{--bg:#0e1612;--card:#16221b;--tx:#e5eee8;--mut:#92a69b;--br:#27372e;--pr:#44c88a;--prs:#16342a;--wa:#e8b04a;--was:#3a2f14;--er:#f08080;--ers:#3a1b1b;--side:#0a211a}}
        *{box-sizing:border-box}
        body{margin:0;background:var(--bg);color:var(--tx);font:14px/1.5 system-ui,-apple-system,"Segoe UI",sans-serif}
        .top{display:flex;align-items:center;gap:18px;background:var(--side);color:#cfe3d8;padding:12px 24px}
        .top .brand{color:#fff;font-size:16px;text-decoration:none}
        .top nav{flex:1;display:flex;gap:10px}
        .top a{color:#fff;text-decoration:none;padding:6px 12px;border-radius:8px;background:rgba(255,255,255,.14)}
        .top a.active{background:rgba(255,255,255,.3);font-weight:600}
        .top .link{background:none;border:0;color:#cfe3d8;cursor:pointer;font:inherit;text-decoration:underline}
        .wrap{max-width:1100px;margin:0 auto;padding:24px}
        .hd{display:flex;justify-content:space-between;gap:12px;align-items:flex-start;margin-bottom:16px}
        h1{margin:0 0 4px;font-size:22px} h3{margin:0 0 12px;font-size:15px}
        .mut{color:var(--mut)}
        .pbi{background:var(--prs);color:var(--pr);padding:3px 11px;border-radius:99px;font-size:12px;font-weight:600}
        .card{background:var(--card);border:1px solid var(--br);border-radius:12px;padding:18px;margin-bottom:16px}
        .bar{display:flex;gap:10px;flex-wrap:wrap;margin-bottom:16px}
        input,select,textarea{font:inherit;color:var(--tx);background:var(--card);border:1px solid var(--br);border-radius:8px;padding:8px 10px}
        textarea{width:100%;min-height:80px}
        .bar input[type=text]{min-width:220px}
        input:focus,select:focus,textarea:focus{outline:2px solid var(--pr);outline-offset:1px}
        .btn{display:inline-block;background:var(--pr);color:#fff;border:0;border-radius:8px;padding:8px 16px;font:inherit;font-weight:600;cursor:pointer;text-decoration:none}
        .btn.l{background:var(--prs);color:var(--pr)} .btn.d{background:var(--er)}
        .tw{overflow-x:auto}
        table{width:100%;border-collapse:collapse;min-width:640px}
        th{text-align:left;color:var(--mut);font-size:12.5px;padding:8px 10px;border-bottom:1px solid var(--br)}
        td{padding:10px;border-bottom:1px solid var(--br)}
        tr:last-child td{border-bottom:0}
        .chip{padding:2px 10px;border-radius:99px;font-size:12px;font-weight:600;white-space:nowrap}
        .ok{background:var(--prs);color:var(--pr)} .wa{background:var(--was);color:var(--wa)} .er{background:var(--ers);color:var(--er)}
        .alert{padding:10px 14px;border-radius:8px;margin-bottom:16px}
        .alert.ok{background:var(--prs);color:var(--pr)} .alert.er{background:var(--ers);color:var(--er)}
        .err{color:var(--er);font-size:13px;margin:4px 0 0}
        dl{display:grid;grid-template-columns:160px 1fr;gap:8px 16px;margin:0}
        dt{color:var(--mut)} dd{margin:0}
        .grid2{display:grid;grid-template-columns:1fr 1fr;gap:16px}
        .pg{display:flex;justify-content:space-between;align-items:center;margin-top:12px}
        @media(max-width:820px){.grid2{grid-template-columns:1fr}dl{grid-template-columns:1fr}.top{flex-wrap:wrap}}
    </style>
</head>
<body>
@php
    $u = auth()->user();
    $roleVal = \App\Http\Middleware\EnsureUserHasRole::roleOf($u) ?? 'User';
    $r = $roleVal instanceof \BackedEnum ? $roleVal->value : (string) $roleVal;
@endphp
<header class="top">
    <a href="{{ route('dashboard') }}" class="brand"><strong>SIPS Sawit</strong></a>
    <nav>
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <a href="{{ route('management.suppliers.index') }}" class="active">Data pemasok</a>
    </nav>
    <span>{{ $u->name }} <small>({{ str_replace('_', ' ', $r) }})</small></span>
    @if (Route::has('logout'))
        <form method="POST" action="{{ route('logout') }}">@csrf<button class="link" type="submit">Keluar</button></form>
    @endif
</header>
<main class="wrap">
    @if (session('success'))<div class="alert ok" dusk="flash-success">{{ session('success') }}</div>@endif
    @if (session('error'))<div class="alert er" dusk="flash-error">{{ session('error') }}</div>@endif
    @yield('content')
</main>
</body>
</html>

