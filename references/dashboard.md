<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>FinalCut — Dashboard</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Indie+Flower&family=Inter:wght@400;500;600&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
<style>
  :root{
    --bg:#F7F7F7; --surface:#FFFFFF; --ink:#111111; --muted:#444444;
    --tag-blue-bg:#DCE9F5; --tag-blue-ink:#1F4E79;
    --tag-yellow-bg:#FBEBA0; --tag-yellow-ink:#6B5900;
  }
  *{box-sizing:border-box;margin:0;padding:0;}
  body{background:var(--bg);color:var(--ink);font-family:'Inter',system-ui,sans-serif;line-height:1.5;}
  a{color:inherit;text-decoration:none;}
  button{font-family:inherit;cursor:pointer;background:none;}
  h1,h2,h3{font-family:'Space Grotesk',sans-serif;}
  :focus-visible{outline:2px solid var(--ink);outline-offset:3px;}
  .hand{font-family:'Indie Flower',cursive;}
  .mono-label{font-family:'Space Grotesk',sans-serif;font-weight:600;letter-spacing:.14em;text-transform:uppercase;}
  .border-ink{border:1.5px solid var(--ink);}
  .shadow-hard{box-shadow:3px 3px 0 var(--ink);}
  .tag{display:inline-block;font-size:11px;font-weight:600;letter-spacing:.04em;padding:3px 10px;border-radius:999px;}
  .tag-blue{background:var(--tag-blue-bg);color:var(--tag-blue-ink);}
  .tag-yellow{background:var(--tag-yellow-bg);color:var(--tag-yellow-ink);}
  mark{background:var(--tag-yellow-bg);color:var(--ink);padding:0 3px;border-radius:3px;}

  /* ---------- SPLASH ---------- */
  #splash{
    position:fixed;inset:0;background:var(--bg);z-index:99999;
    display:flex;flex-direction:column;align-items:center;justify-content:center;gap:22px;
    transition:opacity .5s ease, visibility .5s ease;
  }
  #splash.hidden{opacity:0;visibility:hidden;pointer-events:none;}
  .splash-logo{width:96px;height:96px;border:2px solid var(--ink);border-radius:50%;
    display:flex;align-items:center;justify-content:center;font-family:'Space Grotesk';font-weight:700;font-size:13px;
    animation:spin 1.4s linear infinite;}
  .splash-bar-wrap{width:120px;height:2px;background:#e0e0e0;overflow:hidden;}
  .splash-bar{width:0%;height:100%;background:var(--ink);animation:fill 1.3s ease forwards;}
  .splash-text{font-size:.7rem;letter-spacing:.22em;color:var(--muted);text-transform:uppercase;}
  @keyframes spin{to{transform:rotate(360deg);}}
  @keyframes fill{to{width:100%;}}
  @media (prefers-reduced-motion:reduce){
    .splash-logo{animation:none;}
    .splash-bar{animation:none;width:100%;}
  }

  /* ---------- NAV ---------- */
  .nav{display:flex;align-items:center;justify-content:space-between;padding:20px 32px;border-bottom:1.5px solid var(--ink);}
  .logo{font-family:'Space Grotesk';font-weight:700;font-size:20px;letter-spacing:-.01em;}
  .nav-links{display:flex;gap:28px;font-size:13px;}
  .nav-links .mono-label{padding-bottom:3px;border-bottom:2px solid transparent;}
  .nav-links a[aria-current="page"] .mono-label{border-color:var(--ink);}
  .nav-links a:hover .mono-label{border-color:var(--muted);}
  .nav-avatar{width:34px;height:34px;border-radius:50%;border:1.5px solid var(--ink);display:flex;align-items:center;justify-content:center;font-weight:600;font-size:13px;}

  /* ---------- HERO (trailer penuh, tanpa maskot) ---------- */
  .hero{max-width:1120px;margin:0 auto;padding:48px 32px 8px;}
  .trailer-card{background:var(--surface);border-radius:8px;padding:24px;}
  .trailer-frame{aspect-ratio:16/9;border-radius:6px;position:relative;overflow:hidden;margin-bottom:20px;
    background:repeating-linear-gradient(135deg,#fff,#fff 10px,#f0f0f0 10px,#f0f0f0 20px);}
  .trailer-play{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);
    width:60px;height:60px;border-radius:50%;background:var(--ink);
    display:flex;align-items:center;justify-content:center;border:none;}
  .spotlight-eyebrow{font-size:11px;letter-spacing:.16em;color:var(--muted);margin-bottom:8px;}
  .spotlight-title{font-size:32px;font-weight:700;margin-bottom:8px;}
  .spotlight-meta{display:flex;align-items:center;gap:8px;font-size:12px;color:var(--muted);margin-bottom:20px;}
  .poster-square{aspect-ratio:1/1;border-radius:6px;position:relative;overflow:hidden;margin-bottom:18px;
    background:repeating-linear-gradient(135deg,#fff,#fff 8px,#f0f0f0 8px,#f0f0f0 16px);}
  .obi{position:absolute;right:0;top:0;bottom:0;width:34px;background:var(--ink);color:var(--bg);
    display:flex;align-items:center;justify-content:center;font-family:'Space Grotesk';font-weight:600;
    font-size:11px;letter-spacing:.12em;writing-mode:vertical-rl;text-transform:uppercase;}
  .scrubber-row{display:flex;align-items:center;gap:10px;margin-bottom:22px;}
  .scrubber{flex:1;height:2px;background:#e0e0e0;position:relative;}
  .scrubber-fill{position:absolute;left:0;top:0;height:100%;width:6%;background:var(--ink);transition:width .3s linear;}
  .time-label{font-size:11px;color:var(--muted);font-family:'Space Grotesk';min-width:34px;}
  .cta-row{display:flex;gap:12px;flex-wrap:wrap;}
  .pill{border-radius:999px;padding:11px 20px;font-size:12px;font-weight:600;letter-spacing:.04em;}
  .pill-outline{border:1.5px solid var(--ink);}
  .pill-solid{background:var(--ink);color:var(--bg);}

  /* ---------- ULASAN POPULER ---------- */
  .community{max-width:1120px;margin:0 auto;padding:24px 32px 72px;}
  .community h2{font-size:32px;margin-bottom:4px;}
  .community-sub{width:120px;height:2px;background:var(--ink);margin-bottom:36px;transform:rotate(-1deg);}
  .reviews-grid{display:grid;grid-template-columns:1fr 1fr;gap:32px 40px;}
  .review-card{display:flex;gap:16px;}
  .review-poster{width:76px;flex-shrink:0;aspect-ratio:2/3;border-radius:5px;
    background:repeating-linear-gradient(135deg,#fff,#fff 6px,#f0f0f0 6px,#f0f0f0 12px);}
  .review-user{display:flex;align-items:center;gap:8px;margin-bottom:6px;font-size:12px;color:var(--muted);}
  .review-avatar-sm{width:22px;height:22px;border-radius:50%;flex-shrink:0;}
  .review-film{font-family:'Space Grotesk';font-weight:700;font-size:16px;margin-bottom:6px;}
  .review-film .yr{font-weight:400;color:var(--muted);font-size:13px;}
  .review-text{font-size:13px;margin-bottom:8px;}
  .review-likes{font-size:11px;color:var(--muted);}

  /* ---------- SECTION HEADER (dipakai berulang) ---------- */
  .section-block{max-width:1120px;margin:0 auto;padding:8px 32px 56px;}
  .section-header{display:flex;align-items:baseline;justify-content:space-between;margin-bottom:22px;}
  .section-header h2{font-size:22px;}
  .see-all{font-size:12px;font-weight:600;border-bottom:1.5px solid var(--ink);}

  /* ---------- STATS ---------- */
  .stats-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;}
  .stat-card{background:var(--surface);padding:22px;}
  .stat-eyebrow-sm{font-size:11px;color:var(--muted);}
  .stat-num{font-size:34px;font-weight:700;display:block;margin:10px 0 4px;}
  .stat-sub{font-size:12px;color:var(--muted);}

  /* ---------- MOVIE GRID (sedang tayang) ---------- */
  .movie-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:18px;}
  .movie-card .poster-square{margin-bottom:10px;}
  .obi-sm{position:absolute;right:0;top:0;bottom:0;width:26px;background:var(--ink);color:var(--bg);
    display:flex;align-items:center;justify-content:center;font-family:'Space Grotesk';font-weight:600;
    font-size:9px;letter-spacing:.1em;writing-mode:vertical-rl;text-transform:uppercase;}
  .movie-title-sm{font-family:'Space Grotesk';font-weight:600;font-size:13px;margin-bottom:3px;}
  .movie-meta-sm{font-size:11px;color:var(--muted);}

  /* ---------- KOLEKSI SAYA ---------- */
  .koleksi-cols{display:grid;grid-template-columns:1fr 1fr;gap:28px;}
  .koleksi-col h3{margin-bottom:14px;}
  .mini-row{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;}
  .mini-poster{aspect-ratio:2/3;border-radius:5px;position:relative;
    background:repeating-linear-gradient(135deg,#fff,#fff 6px,#f0f0f0 6px,#f0f0f0 12px);}
  .mini-poster .rate-badge{position:absolute;bottom:4px;left:4px;background:var(--ink);color:var(--bg);
    font-size:9px;font-weight:600;padding:1px 5px;border-radius:3px;}

  /* ---------- RIWAYAT BOOKING ---------- */
  .riwayat-list{display:flex;flex-direction:column;gap:1px;background:var(--ink);}
  .riwayat-row{background:var(--surface);padding:16px 18px;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;}
  .riwayat-info{font-size:13px;font-weight:600;}
  .riwayat-sub{font-size:11px;color:var(--muted);font-weight:400;}
  .tag-outline{border:1.5px solid var(--ink);background:transparent;}

  /* ---------- FOOTER ---------- */
  footer{border-top:1.5px solid var(--ink);position:relative;padding:28px 32px;}
  .footer-inner{max-width:1120px;margin:0 auto;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;}
  .footer-links{display:flex;gap:22px;font-size:12px;}
  .footer-copy{font-size:11px;color:var(--muted);}
  .vert-tag{position:absolute;top:0;bottom:0;writing-mode:vertical-rl;display:flex;align-items:center;
    font-family:'Space Grotesk';font-weight:600;font-size:10px;letter-spacing:.14em;color:var(--muted);text-transform:uppercase;}
  .vert-tag.left{left:8px;}
  .vert-tag.right{right:8px;transform:rotate(180deg);}

  @media (max-width:860px){
    .nav-links{display:none;}
    .reviews-grid{grid-template-columns:1fr;}
    .vert-tag{display:none;}
    .stats-grid{grid-template-columns:1fr;}
    .movie-grid{grid-template-columns:repeat(2,1fr);}
    .koleksi-cols{grid-template-columns:1fr;}
  }
</style>
</head>
<body>

<div id="splash" role="status" aria-live="polite">
  <div class="splash-logo" aria-hidden="true">FC</div>
  <div class="splash-bar-wrap"><div class="splash-bar"></div></div>
  <p class="splash-text">Loading</p>
</div>

<header class="nav">
  <div class="logo">FINALCUT</div>
  <nav class="nav-links" aria-label="Navigasi utama">
    <a href="#" aria-current="page"><span class="mono-label">Beranda</span></a>
    <a href="#"><span class="mono-label">Film</span></a>
    <a href="#"><span class="mono-label">Koleksi</span></a>
    <a href="#"><span class="mono-label">Riwayat</span></a>
  </nav>
  <div style="display:flex;align-items:center;gap:16px;">
    <div class="nav-avatar" aria-label="Akun kamu">R</div>
    <a href="#" class="mono-label" style="font-size:11px;">Keluar</a>
  </div>
</header>

<main>
  <section class="hero">
    <div class="trailer-card border-ink shadow-hard">
      <div class="trailer-frame">
        <div class="obi" aria-hidden="true">Now showing</div>
        <button class="trailer-play" id="playBtn" aria-label="Putar trailer">
          <svg id="playIcon" width="18" height="18" viewBox="0 0 12 12"><path d="M2 1l9 5-9 5z" fill="#F7F7F7"/></svg>
        </button>
      </div>
      <p class="spotlight-eyebrow mono-label">Spotlight reel</p>
      <h1 class="spotlight-title">Senja di Kota Tua</h1>
      <div class="spotlight-meta">
        <span class="tag tag-blue">Drama</span>
        <span>Sedang tayang di 4 bioskop terdekat</span>
      </div>
      <div class="scrubber-row">
        <span class="time-label" id="tCurrent">0:07</span>
        <div class="scrubber"><div class="scrubber-fill" id="scrubFill"></div></div>
        <span class="time-label">2:15</span>
      </div>
      <div class="cta-row">
        <a href="#" class="pill pill-outline">Tonton trailer</a>
        <a href="#" class="pill pill-solid shadow-hard">Booking tiket</a>
      </div>
    </div>
  </section>

  <section class="section-block">
    <div class="stats-grid">
      <div class="stat-card border-ink shadow-hard">
        <span class="mono-label stat-eyebrow-sm">Film ditonton</span>
        <span class="stat-num">24</span>
        <span class="stat-sub">sepanjang bulan ini</span>
      </div>
      <div class="stat-card border-ink shadow-hard">
        <span class="mono-label stat-eyebrow-sm">Ulasan ditulis</span>
        <span class="stat-num">17</span>
        <span class="stat-sub">rata-rata rating 4.2</span>
      </div>
      <div class="stat-card border-ink shadow-hard">
        <span class="mono-label stat-eyebrow-sm">Booking mendatang</span>
        <span class="stat-num">02</span>
        <span class="stat-sub">jadwal minggu ini</span>
      </div>
    </div>
  </section>

  <section class="section-block">
    <div class="section-header">
      <h2>Sedang tayang</h2>
      <a href="#" class="see-all">Lihat semua film</a>
    </div>
    <div class="movie-grid">
      <div class="movie-card">
        <div class="poster-square"><div class="obi-sm" aria-hidden="true">Now</div></div>
        <p class="movie-title-sm">Rahasia Ombak</p>
        <p class="movie-meta-sm">Thriller · 2j 10m</p>
      </div>
      <div class="movie-card">
        <div class="poster-square"><div class="obi-sm" aria-hidden="true">Now</div></div>
        <p class="movie-title-sm">Malam Tanpa Bintang</p>
        <p class="movie-meta-sm">Horror · 1j 45m</p>
      </div>
      <div class="movie-card">
        <div class="poster-square"><div class="obi-sm" aria-hidden="true">Now</div></div>
        <p class="movie-title-sm">Hujan Bulan November</p>
        <p class="movie-meta-sm">Romance · 2j 02m</p>
      </div>
      <div class="movie-card">
        <div class="poster-square"><div class="obi-sm" aria-hidden="true">Now</div></div>
        <p class="movie-title-sm">Api di Ujung Jalan</p>
        <p class="movie-meta-sm">Action · 2j 20m</p>
      </div>
    </div>
  </section>

  <section class="section-block">
    <div class="section-header">
      <h2>Koleksi saya</h2>
      <a href="#" class="see-all">Lihat semua koleksi</a>
    </div>
    <div class="koleksi-cols">
      <div class="koleksi-col">
        <h3 class="mono-label stat-eyebrow-sm">Mau ditonton</h3>
        <div class="mini-row">
          <div><div class="mini-poster"></div><p class="movie-meta-sm" style="margin-top:6px">Api di Ujung Jalan</p></div>
          <div><div class="mini-poster"></div><p class="movie-meta-sm" style="margin-top:6px">Kota Tanpa Nama</p></div>
          <div><div class="mini-poster"></div><p class="movie-meta-sm" style="margin-top:6px">Duka yang Menari</p></div>
        </div>
      </div>
      <div class="koleksi-col">
        <h3 class="mono-label stat-eyebrow-sm">Sudah ditonton</h3>
        <div class="mini-row">
          <div><div class="mini-poster"><span class="rate-badge">4.5</span></div><p class="movie-meta-sm" style="margin-top:6px">Rahasia Ombak</p></div>
          <div><div class="mini-poster"><span class="rate-badge">3.8</span></div><p class="movie-meta-sm" style="margin-top:6px">Pelangi Terakhir</p></div>
          <div><div class="mini-poster"><span class="rate-badge">4.0</span></div><p class="movie-meta-sm" style="margin-top:6px">Malam Tanpa Bintang</p></div>
        </div>
      </div>
    </div>
  </section>

  <section class="section-block">
    <div class="section-header">
      <h2>Riwayat booking</h2>
      <a href="#" class="see-all">Lihat semua riwayat</a>
    </div>
    <div class="riwayat-list border-ink">
      <div class="riwayat-row">
        <p class="riwayat-info">Senja di Kota Tua <span class="riwayat-sub">· FinalCut Cineplex Paskal · 28 Jul, 19:30</span></p>
        <div style="display:flex;align-items:center;gap:14px">
          <span class="tag tag-blue">Lunas</span>
          <span class="mono-label" style="font-size:12px">Rp 90.000</span>
        </div>
      </div>
      <div class="riwayat-row">
        <p class="riwayat-info">Rahasia Ombak <span class="riwayat-sub">· FinalCut Cineplex Paskal · 30 Jul, 20:00</span></p>
        <div style="display:flex;align-items:center;gap:14px">
          <span class="tag tag-yellow">Menunggu bayar</span>
          <span class="mono-label" style="font-size:12px">Rp 45.000</span>
        </div>
      </div>
      <div class="riwayat-row">
        <p class="riwayat-info">Hujan Bulan November <span class="riwayat-sub">· FinalCut Cineplex Paskal · 20 Jul, 18:15</span></p>
        <div style="display:flex;align-items:center;gap:14px">
          <span class="tag tag-outline">Dibatalkan</span>
          <span class="mono-label" style="font-size:12px">Rp 45.000</span>
        </div>
      </div>
    </div>
  </section>

  <section class="community">
    <h2 class="hand">ulasan populer dari sesama penonton~</h2>
    <div class="community-sub" aria-hidden="true"></div>
    <div class="reviews-grid">
      <article class="review-card">
        <div class="review-poster"></div>
        <div>
          <div class="review-user">
            <svg class="review-avatar-sm border-ink" viewBox="0 0 100 100" role="img" aria-label="Avatar midnightreel"><circle cx="50" cy="40" r="18" fill="#fff" stroke="#111" stroke-width="3"/><path d="M32 24 Q50 10 68 24 Q60 20 50 24 Q40 20 32 24Z" fill="#111"/><path d="M20 92c4-24 18-32 30-32s26 8 30 32" fill="#fff" stroke="#111" stroke-width="3"/></svg>
            <span>midnightreel</span>
          </div>
          <p class="review-film">Rahasia Ombak <span class="yr">2026</span></p>
          <p class="review-text">Sinematografinya niat banget, scene di pelabuhan itu bikin merinding.</p>
          <p class="review-likes">♥ 12 suka</p>
        </div>
      </article>
      <article class="review-card">
        <div class="review-poster"></div>
        <div>
          <div class="review-user">
            <svg class="review-avatar-sm border-ink" viewBox="0 0 100 100" role="img" aria-label="Avatar kino.diary"><circle cx="50" cy="42" r="20" fill="#fff" stroke="#111" stroke-width="3"/><path d="M32 30 Q50 12 68 30" fill="none" stroke="#111" stroke-width="3"/></svg>
            <span>kino.diary</span>
          </div>
          <p class="review-film">Malam Tanpa Bintang <span class="yr">2026</span></p>
          <p class="review-text">Twist di 20 menit terakhir nggak nyangka, editingnya rapi banget.</p>
          <p class="review-likes">♥ 8 suka</p>
        </div>
      </article>
      <article class="review-card">
        <div class="review-poster"></div>
        <div>
          <div class="review-user">
            <svg class="review-avatar-sm border-ink" viewBox="0 0 100 100" role="img" aria-label="Avatar senja.nonton"><rect x="24" y="18" width="52" height="52" rx="10" fill="#fff" stroke="#111" stroke-width="3"/><circle cx="40" cy="40" r="3" fill="#111"/><circle cx="60" cy="40" r="3" fill="#111"/></svg>
            <span>senja.nonton</span>
          </div>
          <p class="review-film">Hujan Bulan November <span class="yr">2026</span></p>
          <p class="review-text">Endingnya related banget buat yang lagi patah hati.</p>
          <p class="review-likes">♥ 24 suka</p>
        </div>
      </article>
      <article class="review-card">
        <div class="review-poster"></div>
        <div>
          <div class="review-user">
            <svg class="review-avatar-sm border-ink" viewBox="0 0 100 100" role="img" aria-label="Avatar popcorn.kritis"><circle cx="50" cy="40" r="18" fill="#fff" stroke="#111" stroke-width="3"/><path d="M42 36 l6 6 -6 6M58 36l-6 6 6 6" stroke="#111" stroke-width="2.5" fill="none"/></svg>
            <span>popcorn.kritis</span>
          </div>
          <p class="review-film">Senja di Kota Tua <span class="yr">2026</span></p>
          <p class="review-text">Wajib nonton di bioskop, bukan cuma nunggu rilis streaming.</p>
          <p class="review-likes">♥ 31 suka</p>
        </div>
      </article>
    </div>
  </section>
</main>

<footer>
  <span class="vert-tag left" aria-hidden="true">FinalCut since 2026</span>
  <span class="vert-tag right" aria-hidden="true">Tonton · Ulas · Booking</span>
  <div class="footer-inner">
    <p class="footer-copy">© 2026 FinalCut — dibuat untuk keperluan akademik</p>
    <nav class="footer-links" aria-label="Tautan sosial">
      <a href="#">Instagram</a>
      <a href="#">TikTok</a>
      <a href="#">YouTube</a>
    </nav>
  </div>
</footer>

<script>
  // Splash: fade out once the page has finished loading
  window.addEventListener('load', () => {
    setTimeout(() => {
      document.getElementById('splash').classList.add('hidden');
    }, 900);
  });

  // Spotlight scrubber: purely cosmetic play/pause toggle
  const playBtn = document.getElementById('playBtn');
  const playIcon = document.getElementById('playIcon');
  const fill = document.getElementById('scrubFill');
  const timeLabel = document.getElementById('tCurrent');
  let playing = false, seconds = 7, timer = null;

  playBtn.addEventListener('click', () => {
    playing = !playing;
    playIcon.innerHTML = playing
      ? '<rect x="2" y="1" width="3" height="10" fill="#F7F7F7"/><rect x="7" y="1" width="3" height="10" fill="#F7F7F7"/>'
      : '<path d="M2 1l9 5-9 5z" fill="#F7F7F7"/>';
    playBtn.setAttribute('aria-label', playing ? 'Jeda trailer' : 'Putar trailer');
    if (playing) {
      timer = setInterval(() => {
        seconds = Math.min(seconds + 1, 135);
        const m = Math.floor(seconds / 60), s = String(seconds % 60).padStart(2, '0');
        timeLabel.textContent = `${m}:${s}`;
        fill.style.width = `${(seconds / 135) * 100}%`;
        if (seconds >= 135) { clearInterval(timer); playing = false; }
      }, 1000);
    } else {
      clearInterval(timer);
    }
  });
</script>

</body>
</html>
