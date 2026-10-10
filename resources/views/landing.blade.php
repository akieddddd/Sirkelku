<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>Sirkelku - Cari Teman Se-Frekuensi</title>
<!-- Favicon -->
<link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
<link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
<link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@600;800&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<style>
:root{
  --ink:#1A1A1B;--muted:#576F76;--paper:#FFFFFF;--card:#FFFFFF;--line:#E2E8F0;
  --blue:#FF4500;--orange:#0079D3;--mint:#0DB39E;--pink:#FF585B;
  box-sizing:border-box;padding-top:env(safe-area-inset-top,0px);padding-bottom:env(safe-area-inset-bottom,0px);
}
html{scroll-padding-top:80px;scroll-behavior:smooth}
*{box-sizing:border-box}
body{margin:0;background:var(--paper);color:var(--ink);font-family:'Plus Jakarta Sans',system-ui,sans-serif;line-height:1.6;overflow-x:hidden}
h1,h2,h3{font-family:'Bricolage Grotesque','Plus Jakarta Sans',sans-serif;line-height:1.05;margin:0;letter-spacing:-.02em}
a{color:inherit}
.wrap{max-width:1160px;margin:0 auto;padding:0 24px}
:focus-visible{outline:3px solid var(--blue);outline-offset:3px;border-radius:12px}

header{position:sticky;top:0;z-index:10;background:color-mix(in srgb,var(--paper) 85%,transparent);backdrop-filter:blur(10px);border-bottom:1px solid var(--line)}
.brand{display:inline-flex !important;flex-direction:row !important;align-items:center !important;gap:10px !important;font-family:'Bricolage Grotesque','Plus Jakarta Sans',sans-serif;font-weight:800;font-size:1.45rem;text-decoration:none !important;flex-wrap:nowrap !important;white-space:nowrap !important;line-height:1 !important}
.brand svg, .brand img{width:36px !important;height:36px !important;object-fit:contain;flex-shrink:0 !important;display:inline-block !important;vertical-align:middle}
.brand div, .brand span{white-space:nowrap !important;display:inline-flex !important;flex-direction:row !important;align-items:center !important}
nav{display:flex;align-items:center;gap:24px;font-weight:500;font-size:.95rem}
nav a.link{text-decoration:none;color:var(--muted)}
nav a.link:hover{color:var(--ink)}
.btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:13px 24px;border-radius:999px;background:var(--blue);color:#fff;font-weight:600;text-decoration:none;border:0;cursor:pointer;font:inherit;box-shadow:0 8px 24px -8px var(--blue);transition:transform .15s}
.btn:hover{transform:translateY(-2px)}
.btn.ghost{background:transparent;color:var(--ink);box-shadow:none;border:1.5px solid var(--line)}
.btn.sm{padding:9px 20px;font-size:.92rem}
@media(max-width:700px){nav a.link{display:none}}

/* hero */
.hero{padding:64px 0 44px}
.hero .wrap{display:grid;grid-template-columns:1.05fr .95fr;gap:44px;align-items:center}
.hero-content{display:flex;flex-direction:column}
.hero h1{font-family:'Bricolage Grotesque','Plus Jakarta Sans',sans-serif;font-size:clamp(2.35rem,5.2vw,4.25rem);font-weight:800;line-height:1.12;letter-spacing:-.03em;text-wrap:balance;margin:0 0 20px;color:var(--ink)}
.hero p.lead{font-size:1.15rem;line-height:1.6;color:var(--muted);max-width:32em;margin:0 0 28px}
.cta{display:flex;gap:14px;flex-wrap:wrap;align-items:center;margin-bottom:20px}
.note{margin:0;font-size:.9rem;color:var(--muted)}
@media(max-width:900px){
  .hero .wrap{grid-template-columns:1fr;gap:36px;text-align:left}
  .hero{padding-top:36px;padding-bottom:28px}
  .hero h1{font-size:clamp(2.1rem,6vw,3.2rem);line-height:1.15;margin-bottom:16px}
  .hero p.lead{font-size:1.05rem;margin-bottom:22px}
  .stagewrap{order:2}
}

/* orbit */
.stagewrap{display:flex;flex-direction:column;align-items:center;gap:22px;max-width:100%}
.stage{--s:min(440px,86vw);--r:calc(var(--s)*.37);position:relative;width:var(--s);height:var(--s)}
.rg{position:absolute;inset:0;border-radius:50%;border:1.5px dashed var(--line);pointer-events:none}
.rg.b{inset:15%;border-style:solid;opacity:.7}
.ring{position:absolute;inset:0;animation:orbitSpin 32s linear infinite;transform-origin:50% 50%;will-change:transform}
.av{position:absolute;top:50%;left:50%;width:0;height:0;transform:rotate(var(--a)) translateX(var(--r))}
.un{position:absolute;left:-29px;top:-29px;width:58px;height:58px;animation:orbitCounter 32s linear infinite;transform-origin:50% 50%;will-change:transform}
.face{width:100%;height:100%;transform:rotate(calc(-1*var(--a)))}
.face span{display:grid;place-items:center;width:100%;height:100%;border-radius:50%;background:var(--c);font-size:1.6rem;border:3px solid var(--paper);box-shadow:0 10px 20px -8px rgba(18,27,58,.4);transition:background-color .35s ease}
.face span img{width:100%;height:100%;object-fit:cover;border-radius:50%;pointer-events:none}
.core{position:absolute;inset:27%;border-radius:50%;background:var(--ink);color:var(--paper);display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;padding:12px;box-shadow:0 0 0 12px color-mix(in srgb,var(--blue) 14%,transparent),0 0 0 28px color-mix(in srgb,var(--blue) 7%,transparent)}
.core b{font-family:'Bricolage Grotesque';font-size:1.5rem;line-height:1.1}
.core small{opacity:.75;font-size:.8rem;margin-top:4px}
.core .big{font-size:2.2rem;line-height:1;margin-bottom:6px}
@keyframes orbitSpin{from{transform:rotate(0deg)}to{transform:rotate(360deg)}}
@keyframes orbitCounter{from{transform:rotate(0deg)}to{transform:rotate(-360deg)}}
.chips{display:flex;flex-wrap:wrap;gap:8px;justify-content:center;max-width:460px}
.chip{border:1.5px solid var(--line);background:var(--card);color:var(--ink);padding:8px 16px;border-radius:999px;font:inherit;font-weight:500;font-size:.92rem;cursor:pointer;transition:all .15s}
.chip:hover{border-color:var(--blue)}
.chip[aria-pressed="true"]{background:var(--ink);color:var(--paper);border-color:var(--ink)}

/* marquee */
.strip{position:relative;border-block:1px solid var(--line);overflow:hidden;padding:16px 0;margin-top:48px;background:var(--card);mask-image:linear-gradient(to right,transparent,black 4%,black 96%,transparent);-webkit-mask-image:linear-gradient(to right,transparent,black 4%,black 96%,transparent)}
.strip::before,.strip::after{content:"";position:absolute;top:0;bottom:0;width:60px;z-index:2;pointer-events:none}
.strip::before{left:0;background:linear-gradient(to right,var(--paper),transparent)}
.strip::after{right:0;background:linear-gradient(to left,var(--paper),transparent)}
.marq-track{display:flex;width:max-content;animation:marqSlide 25s linear infinite;will-change:transform}
.strip:hover .marq-track{animation-play-state:paused}
.marq-group{display:flex;align-items:center;gap:36px;padding-right:36px;font-family:'Bricolage Grotesque',sans-serif;font-weight:600;font-size:1.25rem;color:var(--muted);white-space:nowrap}
.strip-dot{display:inline-block;width:6px;height:6px;border-radius:999px;background:var(--blue);margin-left:20px;vertical-align:middle}
@keyframes marqSlide{0%{transform:translateX(0)}100%{transform:translateX(-50%)}}

/* features */
.features{padding:96px 0 40px}
.fhead{display:flex;justify-content:space-between;align-items:end;gap:24px;flex-wrap:wrap;margin-bottom:32px}
.fhead h2{font-size:clamp(2rem,4.4vw,3.2rem);font-weight:800}
.fhead p{color:var(--muted);margin:10px 0 0}
.arrows{display:flex;gap:10px}
.arrows button{width:50px;height:50px;border-radius:50%;border:1.5px solid var(--line);background:var(--card);color:var(--ink);font-size:1.3rem;cursor:pointer;transition:all .15s}
.arrows button:hover{background:var(--ink);color:var(--paper)}
.track{display:flex;gap:20px;overflow-x:auto;padding:6px 24px 28px;margin:0 -24px;scrollbar-width:none;scroll-behavior:auto}
.track::-webkit-scrollbar{display:none}
.fc{flex:0 0 min(340px,82vw);border-radius:28px;padding:30px;min-height:360px;display:flex;flex-direction:column;justify-content:space-between;color:#fff;background:var(--bg);user-select:none}
.fc:nth-child(even){border-radius:28px 28px 100px 28px}
.fc .ic{font-size:2.6rem;width:72px;height:72px;border-radius:22px;background:rgba(255,255,255,.2);display:grid;place-items:center}
.fc h3{font-size:1.75rem;margin-bottom:10px}
.fc p{margin:0;opacity:.92;font-size:.98rem}

/* steps */
.steps{padding:72px 0}
.steps h2{font-size:clamp(2rem,4.4vw,3.2rem);font-weight:800;margin-bottom:36px;max-width:12em}
.sgrid{display:grid;grid-template-columns:repeat(3,1fr);gap:0;border:1.5px solid var(--line);border-radius:28px;background:var(--card);overflow:hidden}
.st{padding:34px;border-right:1.5px solid var(--line)}
.st:last-child{border:0}
.st h3{font-size:1.5rem;margin:14px 0 8px}
.st p{margin:0;color:var(--muted)}
.st .n{display:grid;place-items:center;width:44px;height:44px;border-radius:50%;background:var(--c);color:#fff;font-weight:800;font-family:'Bricolage Grotesque'}
@media(max-width:800px){.sgrid{grid-template-columns:1fr}.st{border-right:0;border-bottom:1.5px solid var(--line)}}

/* final cta */
.final{padding:24px 0 96px}
.box{position:relative;overflow:hidden;background:#1A1A1B;color:#fff;border:1px solid var(--line);border-radius:36px;padding:72px 32px;text-align:center}
.box h2{font-size:clamp(2rem,5vw,3.6rem);font-weight:800;max-width:14em;margin:0 auto 18px}
.box p{opacity:.8;max-width:34em;margin:0 auto 30px}
.box .btn{background:#FF4500;color:#fff;box-shadow:0 10px 30px -10px #FF4500}
.box i{position:absolute;border-radius:50%;border:2px solid rgba(255,255,255,.14);pointer-events:none}
.box i:nth-of-type(1){width:420px;height:420px;left:-140px;top:-160px}
.box i:nth-of-type(2){width:300px;height:300px;right:-90px;bottom:-130px;border-color:rgba(255,69,0,.5)}
.box i:nth-of-type(3){width:180px;height:180px;right:14%;top:-70px}
.box>*:not(i){position:relative}

footer{background:var(--ink);padding:28px 0;color:rgba(255,255,255,.7);font-size:.92rem}
footer .wrap{display:flex;justify-content:space-between;align-items:center;gap:16px;flex-wrap:wrap}
footer .brand{font-size:1.15rem;color:#fff;display:inline-flex !important;flex-direction:row !important;align-items:center !important;gap:10px !important;text-decoration:none !important;flex-wrap:nowrap !important;white-space:nowrap !important}
footer .brand svg{width:30px !important;height:30px !important;flex-shrink:0 !important;display:inline-block !important}
footer .soc{display:flex;gap:18px}
footer .soc a{text-decoration:none}footer .soc a:hover{color:#fff}
</style>
</head>
<body>
<header>
  <div class="wrap" style="display: flex; align-items: center; justify-content: space-between; height: 68px;">
    <x-logo :href="url('/')" icon-class="w-9 h-9" text-size="text-2xl" class="brand" />
    <nav>
      <a class="link" href="#fitur">Fitur</a>
      <a class="link" href="#cara">Cara main</a>
      @auth
        <a class="btn sm" href="{{ route('feed.index') }}">Masuk ke Aplikasi</a>
      @else
        <a class="btn sm" href="{{ route('login') }}">Masuk</a>
      @endauth
    </nav>
  </div>
</header>

<main>
<section class="hero">
  <div class="wrap">
    <div class="hero-content">
      <h1>Cari teman se-frekuensi. Bikin sirkel sendiri!</h1>
      <p class="lead">Tempat nongkrong online buat kamu yang mau berbagi hobi, ngobrol seru, atau sekadar mabar. Gabung ke sirkel yang cocok, atau buka sirkelmu sendiri.</p>
      <div class="cta">
        @auth
          <a class="btn" href="{{ route('feed.index') }}">Mulai Nongkrong</a>
        @else
          <a class="btn" href="{{ route('login') }}">Masuk</a>
          <a class="btn ghost" href="#fitur">Lihat fiturnya</a>
        @endauth
      </div>
      <p class="note">Pilih minat di samping, lihat sirkel seperti apa yang bisa kamu temukan.</p>
    </div>
    <div class="stagewrap">
      <div class="stage" id="stage" aria-live="polite">
        <div class="rg"></div><div class="rg b"></div>
        <div class="ring" id="ring"></div>
        <div class="core">
          <span class="big" id="cEmoji">
            <svg style="width: 1.2em; height: 1.2em;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
          </span>
          <b id="cName">Nongkrong Yuk</b>
          <small id="cSub">Bagikan cerita & keseharianmu</small>
        </div>
      </div>
      <div class="chips" id="chips" role="group" aria-label="Pilih minat"></div>
    </div>
  </div>
</section>

@php
  $words = ['Mabar', 'Diskusi', 'Musik', 'Anime', 'Baca buku', 'Fotografi', 'Ngoding', 'Nongkrong'];
@endphp
<div class="strip" aria-hidden="true">
  <div class="marq-track" id="marqTrack">
    <div class="marq-group" id="marq1">
      @foreach($words as $w)
        <span>{{ $w }}<i class="strip-dot"></i></span>
      @endforeach
    </div>
    <div class="marq-group" id="marq2">
      @foreach($words as $w)
        <span>{{ $w }}<i class="strip-dot"></i></span>
      @endforeach
    </div>
  </div>
</div>

<section class="features" id="fitur">
  <div class="wrap">
    <div class="fhead">
      <div>
        <h2>Fitur andalan Sirkelku</h2>
        <p>Semua yang kamu butuhkan untuk berinteraksi dan berkomunitas.</p>
      </div>
      <div class="arrows">
        <button id="prev" aria-label="Fitur sebelumnya" style="display: inline-flex; align-items: center; justify-content: center;">
          <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
        </button>
        <button id="next" aria-label="Fitur berikutnya" style="display: inline-flex; align-items: center; justify-content: center;">
          <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        </button>
      </div>
    </div>
    <div class="track" id="track">
      <article class="fc" style="--bg:#FF4500"><div class="ic"><svg style="width: 40px; height: 40px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div><div><h3>Sirkel sesuai minat</h3><p>Gabung ke sirkel yang sehobi, dari musik sampai coding. Tidak ada yang cocok? Buat sendiri.</p></div></article>
      <article class="fc" style="--bg:#0079D3"><div class="ic"><svg style="width: 40px; height: 40px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg></div><div><h3>Ruang diskusi</h3><p>Ngobrol santai atau bahas topik menarik secara terstruktur dan mudah diakses.</p></div></article>
      <article class="fc" style="--bg:#0B9A89"><div class="ic"><svg style="width: 40px; height: 40px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5"></path></svg></div><div><h3>Mabar terorganisir</h3><p>Cari rekan bermain, bentuk squad, dan tentukan jadwal bermain bersama.</p></div></article>
      <article class="fc" style="--bg:#E5484D"><div class="ic"><svg style="width: 40px; height: 40px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div><div><h3>Agenda sirkel</h3><p>Jadwalkan pertemuan daring maupun luring untuk anggota sirkel.</p></div></article>
      <article class="fc" style="--bg:#1A1A1B;border:1.5px solid var(--line)"><div class="ic"><svg style="width: 40px; height: 40px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg></div><div><h3>Moderasi terpercaya</h3><p>Pengelola komunitas dapat menjaga diskusi tetap kondusif dan nyaman.</p></div></article>
    </div>
  </div>
</section>

<section class="steps" id="cara">
  <div class="wrap">
    <h2>Tiga langkah bergabung</h2>
    <div class="sgrid">
      <div class="st" style="--c:var(--blue)"><span class="n">1</span><h3>Daftar akun</h3><p>Buat akun dan tentukan minat yang sesuai.</p></div>
      <div class="st" style="--c:var(--orange)"><span class="n">2</span><h3>Pilih sirkel</h3><p>Telusuri komunitas yang diminati dan bergabung.</p></div>
      <div class="st" style="--c:var(--mint)"><span class="n">3</span><h3>Berinteraksi</h3><p>Ikuti diskusi, temukan teman baru, atau bangun sirkel sendiri.</p></div>
    </div>
  </div>
</section>

<section class="final" id="daftar">
  <div class="wrap">
    <div class="box">
      <i></i><i></i><i></i>
      <h2>Mulai terhubung hari ini</h2>
      <p>Daftar akun, pilih minatmu, dan temukan teman sefrekuensi.</p>
      @auth
        <a class="btn" href="{{ route('feed.index') }}">Mulai Nongkrong</a>
      @else
        <a class="btn" href="{{ route('register') }}">Daftar sekarang</a>
      @endauth
    </div>
  </div>
</section>
</main>

<footer>
  <div class="wrap" style="display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap;">
    <x-logo :href="url('/')" icon-class="w-8 h-8" text-size="text-xl" dark="#FFFFFF" text-color="text-white" class="brand" />
    <span>© {{ date('Y') }} Sirkelku. Hak cipta dilindungi.</span>
    <div class="soc"><a href="#">Twitter</a><a href="#">Instagram</a></div>
  </div>
</footer>

<script>
const D=[
 {k:'Nongkrong Yuk',e:'<svg style="width: 1.2em; height: 1.2em;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>',n:'Nongkrong Yuk',s:'Bagikan cerita & keseharianmu',f:['Mia','Leo','Sam','Zoe','Max','Ava']},
 {k:'Satu Sirkel',e:'<svg style="width: 1.2em; height: 1.2em;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>',n:'Satu Sirkel',s:'Gabung komunitas sehobi',f:['Jay','Kai','Rae','Ivy','Eli','Fay']},
 {k:'Tongkrongan.id',e:'<svg style="width: 1.2em; height: 1.2em;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>',n:'Tongkrongan.id',s:'Forum diskusi tanpa batas',f:['Luz','Rex','Sky','Ash','Pax','Nyx']},
 {k:'Teman Main',e:'<svg style="width: 1.2em; height: 1.2em;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5"></path></svg>',n:'Teman Main',s:'Cari teman mabar instan',f:['Kip','Sia','Roy','Mae','Val','Jon']},
 {k:'Kirim Pesan',e:'<svg style="width: 1.2em; height: 1.2em;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>',n:'Kirim Pesan',s:'Ngobrol personal real-time',f:['Noa','Ali','Eve','Ken','Ben','Lia']}
];
const C=['#FF4500','#0079D3','#0DB39E','#FF585B','#7193FF','#FFB000'];

// 1. Orbit Hero Widget
const ring=document.getElementById('ring'),chips=document.getElementById('chips');
const cEmoji=document.getElementById('cEmoji'),cName=document.getElementById('cName'),cSub=document.getElementById('cSub');

for(let j=0;j<6;j++){
  const angle=j*60-90;
  const av=document.createElement('div');
  av.className='av';
  av.style.setProperty('--a',`${angle}deg`);
  av.innerHTML=`<div class="un"><div class="face" style="--a:${angle}deg"><span id="avSpan${j}" style="padding:0;overflow:hidden;"><img id="avImg${j}" src="" alt="Avatar" style="width:100%;height:100%;object-fit:cover;"></span></div></div>`;
  ring.appendChild(av);
}

function show(i){
  const d=D[i];
  cEmoji.innerHTML=d.e;cName.textContent=d.n;cSub.textContent=d.s;
  d.f.forEach((f,j)=>{
    const span=document.getElementById(`avSpan${j}`);
    const img=document.getElementById(`avImg${j}`);
    if(span) span.style.setProperty('--c',C[(j+i)%C.length]);
    if(img) img.src=`https://api.dicebear.com/7.x/avataaars/svg?seed=${f}&backgroundColor=transparent`;
  });
  [...chips.children].forEach((b,j)=>b.setAttribute('aria-pressed',j===i));
}

D.forEach((d,i)=>{
  const b=document.createElement('button');
  b.className='chip';
  b.textContent=d.k;
  b.onclick=()=>{
    show(i);
    clearInterval(chipAutoTimer);
  };
  chips.appendChild(b);
});
show(0);

let chipAutoTimer=setInterval(()=>{
  const cur=[...chips.children].findIndex(b=>b.getAttribute('aria-pressed')==='true');
  show((cur+1)%D.length);
},4500);

chips.addEventListener('click',()=>clearInterval(chipAutoTimer));

// 2. Slider Fitur Andalan: Seamless Infinite Auto-Scroll Loop
(function() {
  const tr = document.getElementById('track');
  const prevBtn = document.getElementById('prev');
  const nextBtn = document.getElementById('next');
  if (!tr) return;

  // Clone original cards to achieve a seamless, continuous infinite loop
  const originalCards = Array.from(tr.children);
  if (originalCards.length === 0) return;

  originalCards.forEach(card => {
    const clone = card.cloneNode(true);
    clone.setAttribute('aria-hidden', 'true');
    tr.appendChild(clone);
  });

  const speed = 0.85; // Speed in pixels per frame (~50px/sec at 60fps)
  let isPaused = false;
  let pauseResumeTimer = null;
  let animId = null;

  function getLoopThreshold() {
    return tr.scrollWidth / 2;
  }

  function loop() {
    if (!isPaused) {
      tr.scrollLeft += speed;
      const threshold = getLoopThreshold();
      if (tr.scrollLeft >= threshold) {
        tr.scrollLeft -= threshold;
      }
    }
    animId = requestAnimationFrame(loop);
  }

  // Start continuous smooth loop
  animId = requestAnimationFrame(loop);

  // Pause on hover
  tr.addEventListener('mouseenter', () => { isPaused = true; });
  tr.addEventListener('mouseleave', () => {
    clearTimeout(pauseResumeTimer);
    isPaused = false;
  });

  // Touch pause for mobile devices
  tr.addEventListener('touchstart', () => { isPaused = true; }, { passive: true });
  tr.addEventListener('touchend', () => {
    clearTimeout(pauseResumeTimer);
    pauseResumeTimer = setTimeout(() => { isPaused = false; }, 1800);
  }, { passive: true });

  // Manual navigation buttons (prev / next)
  function manualScroll(direction) {
    isPaused = true;
    clearTimeout(pauseResumeTimer);

    const firstCard = tr.firstElementChild;
    const cardStep = firstCard ? (firstCard.offsetWidth + 20) : 360;
    const threshold = getLoopThreshold();

    if (direction === 'prev' && tr.scrollLeft <= 10) {
      tr.scrollLeft += threshold;
    }

    tr.scrollBy({
      left: direction === 'next' ? cardStep : -cardStep,
      behavior: 'smooth'
    });

    // Temporarily pause so user can comfortably inspect card, then smoothly resume loop
    pauseResumeTimer = setTimeout(() => {
      if (tr.scrollLeft >= threshold) {
        tr.scrollLeft -= threshold;
      }
      isPaused = false;
    }, 2500);
  }

  if (prevBtn) {
    prevBtn.addEventListener('click', (e) => {
      e.preventDefault();
      manualScroll('prev');
    });
  }

  if (nextBtn) {
    nextBtn.addEventListener('click', (e) => {
      e.preventDefault();
      manualScroll('next');
    });
  }
})();
</script>
</body>
</html>
