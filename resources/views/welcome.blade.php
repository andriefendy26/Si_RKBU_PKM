<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RKBU — Rencana Kebutuhan Barang Unit</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --navy:   #0D1B2A;
            --navy2:  #132033;
            --teal:   #1A6B5C;
            --teal2:  #23907A;
            --gold:   #C9A84C;
            --gold2:  #E8C96A;
            --cream:  #F7F3EC;
            --muted:  #8A9BB0;
            --white:  #FFFFFF;
            --border: rgba(201,168,76,.18);
        }

        html { scroll-behavior: smooth; }

        body {
            background: var(--navy);
            color: var(--cream);
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ── Background texture ── */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 60% at 70% 20%, rgba(26,107,92,.22) 0%, transparent 60%),
                radial-gradient(ellipse 60% 50% at 10% 80%, rgba(201,168,76,.08) 0%, transparent 55%);
            pointer-events: none;
            z-index: 0;
        }

        /* ── Grid overlay ── */
        body::after {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(201,168,76,.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(201,168,76,.04) 1px, transparent 1px);
            background-size: 60px 60px;
            pointer-events: none;
            z-index: 0;
        }

        /* ── NAV ── */
        nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem 3rem;
            background: rgba(13,27,42,.75);
            backdrop-filter: blur(14px);
            border-bottom: 1px solid var(--border);
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: .75rem;
        }

        .nav-logo {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--teal), var(--teal2));
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-family: 'DM Serif Display', serif;
            font-size: 1rem;
            color: var(--gold2);
            letter-spacing: -.5px;
        }

        .nav-title {
            font-size: .8rem;
            font-weight: 600;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--cream);
        }

        .nav-sub {
            font-size: .68rem;
            color: var(--muted);
            letter-spacing: .06em;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .btn-ghost {
            padding: .5rem 1.25rem;
            border: 1px solid var(--border);
            border-radius: 6px;
            color: var(--cream);
            font-size: .82rem;
            font-weight: 500;
            text-decoration: none;
            transition: border-color .2s, background .2s;
        }

        .btn-ghost:hover {
            border-color: var(--gold);
            background: rgba(201,168,76,.07);
        }

        .btn-primary {
            padding: .5rem 1.4rem;
            background: linear-gradient(135deg, var(--teal), var(--teal2));
            border: 1px solid rgba(35,144,122,.5);
            border-radius: 6px;
            color: var(--white);
            font-size: .82rem;
            font-weight: 600;
            text-decoration: none;
            transition: opacity .2s, transform .15s;
        }

        .btn-primary:hover {
            opacity: .88;
            transform: translateY(-1px);
        }

        /* ── HERO ── */
        .hero {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 8rem 3rem 5rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .hero-content {
            max-width: 640px;
        }

        .hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .35rem .9rem;
            border: 1px solid var(--border);
            border-radius: 100px;
            font-size: .72rem;
            font-weight: 600;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 1.75rem;
            background: rgba(201,168,76,.06);
            animation: fadeUp .6s ease both;
        }

        .hero-eyebrow span { width: 6px; height: 6px; border-radius: 50%; background: var(--gold2); }

        .hero-title {
            font-family: 'DM Serif Display', serif;
            font-size: clamp(2.8rem, 6vw, 4.5rem);
            line-height: 1.08;
            color: var(--white);
            margin-bottom: 1.5rem;
            animation: fadeUp .6s .1s ease both;
        }

        .hero-title em {
            font-style: italic;
            color: var(--gold2);
        }

        .hero-desc {
            font-size: 1.05rem;
            line-height: 1.75;
            color: var(--muted);
            max-width: 520px;
            margin-bottom: 2.5rem;
            font-weight: 300;
            animation: fadeUp .6s .2s ease both;
        }

        .hero-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            animation: fadeUp .6s .3s ease both;
        }

        .btn-lg {
            padding: .85rem 2.2rem;
            font-size: .92rem;
        }

        /* ── HERO visual side ── */
        .hero-visual {
            position: absolute;
            right: -2rem;
            top: 50%;
            transform: translateY(-50%);
            width: 480px;
            animation: fadeIn .8s .2s ease both;
        }

        .doc-stack {
            position: relative;
            width: 380px;
        }

        .doc-card {
            background: rgba(19,32,51,.85);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 1.5rem;
            backdrop-filter: blur(10px);
        }

        .doc-card-back {
            position: absolute;
            top: -16px; left: 20px; right: -20px;
            background: rgba(26,107,92,.18);
            border: 1px solid rgba(26,107,92,.25);
            border-radius: 14px;
            height: calc(100% + 16px);
            z-index: -1;
        }

        .doc-card-back2 {
            position: absolute;
            top: -30px; left: 38px; right: -38px;
            background: rgba(201,168,76,.07);
            border: 1px solid rgba(201,168,76,.12);
            border-radius: 14px;
            height: calc(100% + 30px);
            z-index: -2;
        }

        .doc-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.25rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border);
        }

        .doc-title-wrap { }
        .doc-label { font-size: .65rem; letter-spacing: .12em; text-transform: uppercase; color: var(--muted); margin-bottom: .2rem; }
        .doc-name { font-size: .95rem; font-weight: 600; color: var(--cream); }

        .doc-badge {
            padding: .25rem .65rem;
            border-radius: 100px;
            font-size: .65rem;
            font-weight: 600;
            letter-spacing: .08em;
            text-transform: uppercase;
        }
        .badge-good { background: rgba(26,107,92,.25); color: #5DDDB8; border: 1px solid rgba(93,221,184,.2); }
        .badge-warn { background: rgba(201,168,76,.15); color: var(--gold2); border: 1px solid rgba(201,168,76,.25); }
        .badge-bad  { background: rgba(180,40,40,.18); color: #F87171; border: 1px solid rgba(248,113,113,.2); }

        .doc-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: .55rem 0;
            border-bottom: 1px solid rgba(201,168,76,.07);
        }

        .doc-row:last-child { border-bottom: none; }

        .doc-row-label { font-size: .78rem; color: var(--muted); }
        .doc-row-val { font-size: .82rem; font-weight: 500; color: var(--cream); }

        .doc-footer {
            margin-top: 1.25rem;
            padding-top: 1rem;
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .doc-total-label { font-size: .72rem; color: var(--muted); }
        .doc-total-val { font-size: 1.05rem; font-weight: 700; color: var(--gold2); font-family: 'DM Serif Display', serif; }

        /* ── STATS STRIP ── */
        .stats {
            position: relative;
            z-index: 1;
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
            background: rgba(13,27,42,.5);
        }

        .stats-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 3rem;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1px;
        }

        .stat {
            padding: 0 2rem;
            border-right: 1px solid var(--border);
        }
        .stat:first-child { padding-left: 0; }
        .stat:last-child { border-right: none; }

        .stat-num {
            font-family: 'DM Serif Display', serif;
            font-size: 2.4rem;
            color: var(--gold2);
            line-height: 1;
            margin-bottom: .3rem;
        }

        .stat-label { font-size: .75rem; color: var(--muted); font-weight: 400; letter-spacing: .04em; }

        /* ── FEATURES ── */
        .features {
            position: relative;
            z-index: 1;
            max-width: 1200px;
            margin: 0 auto;
            padding: 6rem 3rem;
        }

        .section-label {
            font-size: .72rem;
            letter-spacing: .16em;
            text-transform: uppercase;
            color: var(--gold);
            font-weight: 600;
            margin-bottom: .75rem;
        }

        .section-title {
            font-family: 'DM Serif Display', serif;
            font-size: clamp(1.8rem, 3.5vw, 2.6rem);
            color: var(--white);
            margin-bottom: 1rem;
        }

        .section-desc {
            font-size: .95rem;
            color: var(--muted);
            max-width: 480px;
            line-height: 1.7;
            margin-bottom: 3.5rem;
            font-weight: 300;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
        }

        .feature-card {
            background: rgba(19,32,51,.6);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 2rem;
            transition: border-color .25s, transform .25s;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--teal2), transparent);
            opacity: 0;
            transition: opacity .3s;
        }

        .feature-card:hover {
            border-color: rgba(26,107,92,.4);
            transform: translateY(-4px);
        }

        .feature-card:hover::before { opacity: 1; }

        .feature-icon {
            width: 44px; height: 44px;
            background: rgba(26,107,92,.15);
            border: 1px solid rgba(26,107,92,.25);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 1.25rem;
            font-size: 1.2rem;
        }

        .feature-title {
            font-size: .95rem;
            font-weight: 600;
            color: var(--cream);
            margin-bottom: .6rem;
        }

        .feature-desc {
            font-size: .82rem;
            color: var(--muted);
            line-height: 1.65;
        }

        /* ── WORKFLOW ── */
        .workflow {
            position: relative;
            z-index: 1;
            background: rgba(19,32,51,.4);
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }

        .workflow-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 6rem 3rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5rem;
            align-items: center;
        }

        .steps {
            display: flex;
            flex-direction: column;
            gap: .25rem;
        }

        .step {
            display: flex;
            gap: 1.25rem;
            padding: 1.25rem;
            border-radius: 10px;
            cursor: default;
            transition: background .2s;
        }

        .step:hover { background: rgba(201,168,76,.05); }

        .step-num {
            width: 32px; height: 32px;
            border-radius: 50%;
            background: rgba(26,107,92,.15);
            border: 1px solid rgba(26,107,92,.3);
            display: flex; align-items: center; justify-content: center;
            font-size: .75rem;
            font-weight: 700;
            color: var(--teal2);
            flex-shrink: 0;
            margin-top: .1rem;
        }

        .step-body {}
        .step-title { font-size: .9rem; font-weight: 600; color: var(--cream); margin-bottom: .35rem; }
        .step-desc  { font-size: .8rem; color: var(--muted); line-height: 1.6; }

        /* ── CTA ── */
        .cta-section {
            position: relative;
            z-index: 1;
            max-width: 1200px;
            margin: 0 auto;
            padding: 6rem 3rem;
            text-align: center;
        }

        .cta-box {
            background: linear-gradient(135deg, rgba(26,107,92,.15) 0%, rgba(13,27,42,.5) 100%);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 4rem 3rem;
            position: relative;
            overflow: hidden;
        }

        .cta-box::before {
            content: '';
            position: absolute;
            top: -60px; right: -60px;
            width: 240px; height: 240px;
            background: radial-gradient(circle, rgba(201,168,76,.12) 0%, transparent 70%);
            pointer-events: none;
        }

        .cta-box::after {
            content: '';
            position: absolute;
            bottom: -40px; left: -40px;
            width: 180px; height: 180px;
            background: radial-gradient(circle, rgba(26,107,92,.1) 0%, transparent 70%);
            pointer-events: none;
        }

        .cta-title {
            font-family: 'DM Serif Display', serif;
            font-size: clamp(1.8rem, 3vw, 2.5rem);
            color: var(--white);
            margin-bottom: 1rem;
        }

        .cta-desc {
            font-size: .95rem;
            color: var(--muted);
            max-width: 440px;
            margin: 0 auto 2.5rem;
            line-height: 1.7;
            font-weight: 300;
        }

        .cta-actions { display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap; }

        /* ── FOOTER ── */
        footer {
            position: relative;
            z-index: 1;
            border-top: 1px solid var(--border);
            padding: 2rem 3rem;
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .footer-brand { font-size: .75rem; color: var(--muted); }
        .footer-brand strong { color: var(--cream); }

        .footer-copy { font-size: .72rem; color: var(--muted); }

        /* ── ANIMATIONS ── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 1024px) {
            .hero-visual { display: none; }
            .features-grid { grid-template-columns: 1fr 1fr; }
            .workflow-inner { grid-template-columns: 1fr; gap: 3rem; }
            .stats-inner { grid-template-columns: repeat(2, 1fr); gap: 2rem; }
            .stat { border-right: none; border-bottom: 1px solid var(--border); padding: 0 0 1.5rem; }
            .stat:nth-child(2n) { border-bottom: none; }
        }

        @media (max-width: 640px) {
            nav { padding: 1rem 1.5rem; }
            .hero { padding: 7rem 1.5rem 4rem; }
            .features, .cta-section { padding: 4rem 1.5rem; }
            .features-grid { grid-template-columns: 1fr; }
            .stats-inner { padding: 2rem 1.5rem; }
            footer { flex-direction: column; gap: .75rem; text-align: center; padding: 2rem 1.5rem; }
        }
    </style>
</head>
<body>

    {{-- NAV --}}
    <nav>
        <div class="nav-brand">
            <img src="https://pkmpantaiamal.com/logo-puskesmas.png" alt="Logo" style="width: 28px; height: 28px; margin-top: 2px;">
            {{-- <div class="nav-logo">RK</div> --}}
            <div>
                <div class="nav-title">RKBU</div>
                <div class="nav-sub">Puskesmas Pantai Amal</div>
            </div>
        </div>
    </nav>

    {{-- HERO --}}
    <section class="hero">
        <div class="hero-content">
            <div class="hero-eyebrow">
                <span></span>
                Sistem Manajemen Pengadaan
            </div>
            <h1 class="hero-title">
                Rencana Kebutuhan<br>
                <em>Barang Unit</em><br>
                yang Terstruktur
            </h1>
            <p class="hero-desc">
                Platform digital untuk menyusun, mengelola, dan mengekspor data kebutuhan barang setiap unit di Puskesmas Pantai Amal secara efisien dan transparan.
            </p>
            <div class="hero-actions">
                    <a href="{{ url('/admin') }}" class="btn-primary btn-lg">Buka Dashboard →</a>
            </div>
        </div>

        {{-- Floating doc card --}}
        <div class="hero-visual">
            <div class="doc-stack">
                <div class="doc-card-back2"></div>
                <div class="doc-card-back"></div>
                <div class="doc-card">
                    <div class="doc-header">
                        <div class="doc-title-wrap">
                            <div class="doc-label">Unit / Ruangan</div>
                            <div class="doc-name">UKS — Tahun 2026</div>
                        </div>
                        <span class="doc-badge badge-good">Aktif</span>
                    </div>

                    <div class="doc-row">
                        <span class="doc-row-label">Kursi Pemeriksaan</span>
                        <span class="doc-badge badge-warn">RR</span>
                    </div>
                    <div class="doc-row">
                        <span class="doc-row-label">Tensimeter Digital</span>
                        <span class="doc-badge badge-good">B</span>
                    </div>
                    <div class="doc-row">
                        <span class="doc-row-label">Timbangan Badan</span>
                        <span class="doc-badge badge-bad">RB</span>
                    </div>
                    <div class="doc-row">
                        <span class="doc-row-label">Stetoskop</span>
                        <span class="doc-badge badge-good">B</span>
                    </div>
                    <div class="doc-row">
                        <span class="doc-row-label">Meja Periksa</span>
                        <span class="doc-badge badge-warn">RR</span>
                    </div>

                    <div class="doc-footer">
                        <div>
                            <div class="doc-total-label">Total Estimasi Biaya</div>
                        </div>
                        <div class="doc-total-val">Rp 14.750.000</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- STATS --}}
    <div class="stats">
        <div class="stats-inner">
            <div class="stat">
                <div class="stat-num">15+</div>
                <div class="stat-label">Unit / Ruangan Aktif</div>
            </div>
            <div class="stat">
                <div class="stat-num">3</div>
                <div class="stat-label">Status Kondisi Barang</div>
            </div>
            <div class="stat">
                <div class="stat-num">xlsx</div>
                <div class="stat-label">Format Ekspor Otomatis</div>
            </div>
            <div class="stat">
                <div class="stat-num">∞</div>
                <div class="stat-label">Data Per Tahun Anggaran</div>
            </div>
        </div>
    </div>

    {{-- FEATURES --}}
    <section class="features" id="fitur">
        <div class="section-label">Fitur Utama</div>
        <h2 class="section-title">Semua yang dibutuhkan<br>untuk pengelolaan RKBU</h2>
        <p class="section-desc">Dirancang khusus untuk kebutuhan administrasi puskesmas, dari pencatatan hingga pelaporan.</p>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">📋</div>
                <div class="feature-title">Input Data Terstruktur</div>
                <div class="feature-desc">Formulir terstandar untuk mencatat nama barang, kondisi (B/RR/RB), jumlah tersedia, kebutuhan, kekurangan, dan estimasi biaya per unit.</div>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🏥</div>
                <div class="feature-title">Manajemen Per Unit</div>
                <div class="feature-desc">Data dikelompokkan per ruangan — UKS, Laboratorium, KIA, Apotek, dan lainnya. Setiap unit memiliki data RKBU-nya sendiri.</div>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📊</div>
                <div class="feature-title">Ekspor Excel Otomatis</div>
                <div class="feature-desc">Generate laporan RKBU dalam format Excel (.xlsx) sesuai format resmi Dinas Kesehatan, lengkap dengan multi-sheet per ruangan.</div>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🔍</div>
                <div class="feature-title">Filter & Pencarian</div>
                <div class="feature-desc">Cari dan filter data berdasarkan tahun anggaran, kondisi barang, atau nama ruangan dengan cepat dan mudah.</div>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📅</div>
                <div class="feature-title">Multi Tahun Anggaran</div>
                <div class="feature-desc">Simpan dan kelola data RKBU untuk berbagai tahun anggaran. Bandingkan kebutuhan antar tahun untuk perencanaan lebih baik.</div>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🔐</div>
                <div class="feature-title">Akses Berbasis Peran</div>
                <div class="feature-desc">Setiap petugas hanya melihat dan mengelola data unit miliknya. Admin dapat memantau seluruh data lintas unit.</div>
            </div>
        </div>
    </section>

    {{-- WORKFLOW --}}
    <div class="workflow">
        <div class="workflow-inner">
            <div>
                <div class="section-label">Cara Kerja</div>
                <h2 class="section-title">Dari pencatatan<br>ke laporan resmi</h2>
                <p class="section-desc" style="margin-bottom: 0;">Proses yang sederhana namun menghasilkan dokumen yang profesional dan sesuai standar.</p>
            </div>

            <div class="steps">
                <div class="step">
                    <div class="step-num">1</div>
                    <div class="step-body">
                        <div class="step-title">Login & Pilih Tahun Anggaran</div>
                        <div class="step-desc">Masuk ke sistem dan pilih tahun anggaran yang akan dikelola untuk memulai pencatatan RKBU.</div>
                    </div>
                </div>
                <div class="step">
                    <div class="step-num">2</div>
                    <div class="step-body">
                        <div class="step-title">Input Data Barang Per Unit</div>
                        <div class="step-desc">Isi formulir untuk setiap barang: nama, kondisi, jumlah tersedia, kebutuhan, kekurangan, harga satuan, dan analisa kebutuhan.</div>
                    </div>
                </div>
                <div class="step">
                    <div class="step-num">3</div>
                    <div class="step-body">
                        <div class="step-title">Review & Verifikasi Data</div>
                        <div class="step-desc">Pantau semua data yang masuk dari seluruh unit melalui tabel interaktif dengan filter dan sorting.</div>
                    </div>
                </div>
                <div class="step">
                    <div class="step-num">4</div>
                    <div class="step-body">
                        <div class="step-title">Ekspor Laporan Excel</div>
                        <div class="step-desc">Generate file Excel otomatis dengan format resmi, satu sheet per ruangan, siap untuk diserahkan ke Dinas Kesehatan.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- CTA --}}
    <section class="cta-section">
        <div class="cta-box">
            <h2 class="cta-title">Siap memulai pengisian RKBU?</h2>
            <p class="cta-desc">Masuk ke sistem dan mulai catat kebutuhan barang unit Anda sekarang. Cepat, terstruktur, dan mudah diekspor.</p>
            <div class="cta-actions">
                <a href="{{ url('/admin') }}" class="btn-primary btn-lg">Buka Dashboard →</a>                
            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer>
        <div class="footer-brand">
            <strong>RKBU</strong> — Rencana Kebutuhan Barang Unit<br>
            Puskesmas Pantai Amal
        </div>
        <div class="footer-copy">© {{ date('Y') }} Puskesmas Pantai Amal. Hak cipta dilindungi.</div>
    </footer>

</body>
</html>