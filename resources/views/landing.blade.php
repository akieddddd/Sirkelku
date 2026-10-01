<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>Sirkelku - Cari Teman Se-Frekuensi</title>
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
header .wrap{display:flex;align-items:center;justify-content:space-between;height:68px}
.brand{display:flex;align-items:center;gap:10px;font-family:'Bricolage Grotesque';font-weight:800;font-size:1.45rem;text-decoration:none}
.brand svg, .brand img{width:34px;height:34px;object-fit:contain}
nav{display:flex;align-items:center;gap:26px;font-weight:500;font-size:.95rem}
nav a.link{text-decoration:none;color:var(--muted)}
nav a.link:hover{color:var(--ink)}
.btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:13px 24px;border-radius:999px;background:var(--blue);color:#fff;font-weight:600;text-decoration:none;border:0;cursor:pointer;font:inherit;font-weight:600;box-shadow:0 8px 24px -8px var(--blue);transition:transform .15s}
.btn:hover{transform:translateY(-2px)}
.btn.ghost{background:transparent;color:var(--ink);box-shadow:none;border:1.5px solid var(--line)}
.btn.sm{padding:9px 20px;font-size:.92rem}
@media(max-width:700px){nav a.link{display:none}}

/* hero */
.hero{padding:64px 0 40px}
.hero .wrap{display:grid;grid-template-columns:1.05fr .95fr;gap:32px;align-items:center}
.hero h1{font-size:clamp(2.6rem,6vw,4.6rem);font-weight:800}
.hero p.lead{font-size:1.15rem;color:var(--muted);max-width:30em;margin:22px 0 30px}
.cta{display:flex;gap:12px;flex-wrap:wrap}
.note{margin-top:18px;font-size:.9rem;color:var(--muted)}
@media(max-width:900px){.hero .wrap{grid-template-columns:1fr}.hero{padding-top:36px}}

/* orbit */
.stagewrap{display:flex;flex-direction:column;align-items:center;gap:22px}
.stage{--s:min(440px,86vw);--r:calc(var(--s)*.37);position:relative;width:var(--s);height:var(--s)}
.rg{position:absolute;inset:0;border-radius:50%;border:1.5px dashed var(--line)}
.rg.b{inset:15%;border-style:solid;opacity:.7}
.ring{position:absolute;inset:0;animation:spin 48s linear infinite}
.av{position:absolute;top:50%;left:50%;width:0;height:0;transform:rotate(var(--a)) translateX(var(--r))}
.un{position:absolute;left:-29px;top:-29px;width:58px;height:58px;animation:spin 48s linear infinite reverse}
.face{width:100%;height:100%;transform:rotate(calc(-1*var(--a)));}
.face span{display:grid;place-items:center;width:100%;height:100%;border-radius:50%;background:var(--c);font-size:1.6rem;border:3px solid var(--paper);box-shadow:0 10px 20px -8px rgba(18,27,58,.4);animation:pop .45s both}
.core{position:absolute;inset:27%;border-radius:50%;background:var(--ink);color:var(--paper);display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;padding:12px;box-shadow:0 0 0 12px color-mix(in srgb,var(--blue) 14%,transparent),0 0 0 28px color-mix(in srgb,var(--blue) 7%,transparent)}
.core b{font-family:'Bricolage Grotesque';font-size:1.5rem;line-height:1.1}
.core small{opacity:.75;font-size:.8rem;margin-top:4px}
.core .big{font-size:2.2rem;line-height:1;margin-bottom:6px}
@keyframes spin{to{transform:rotate(360deg)}}
@keyframes pop{from{transform:scale(.3);opacity:0}to{transform:scale(1);opacity:1}}
.chips{display:flex;flex-wrap:wrap;gap:8px;justify-content:center;max-width:460px}
.chip{border:1.5px solid var(--line);background:var(--card);color:var(--ink);padding:8px 16px;border-radius:999px;font:inherit;font-weight:500;font-size:.92rem;cursor:pointer;transition:all .15s}
.chip:hover{border-color:var(--blue)}
.chip[aria-pressed="true"]{background:var(--ink);color:var(--paper);border-color:var(--ink)}

/* marquee */
.strip{border-block:1px solid var(--line);overflow:hidden;padding:16px 0;margin-top:48px;background:var(--card)}
.strip div{display:flex;gap:40px;width:max-content;animation:slide 40s linear infinite;font-family:'Bricolage Grotesque';font-weight:600;font-size:1.25rem;color:var(--muted)}
@keyframes slide{to{transform:translateX(-50%)}}

/* features */
.features{padding:96px 0 40px}
.fhead{display:flex;justify-content:space-between;align-items:end;gap:24px;flex-wrap:wrap;margin-bottom:32px}
.fhead h2{font-size:clamp(2rem,4.4vw,3.2rem);font-weight:800}
.fhead p{color:var(--muted);margin:10px 0 0}
.arrows{display:flex;gap:10px}
.arrows button{width:50px;height:50px;border-radius:50%;border:1.5px solid var(--line);background:var(--card);color:var(--ink);font-size:1.3rem;cursor:pointer;transition:all .15s}
.arrows button:hover{background:var(--ink);color:var(--paper)}
.track{display:flex;gap:20px;overflow-x:auto;scroll-snap-type:x mandatory;padding:6px 24px 28px;margin:0 -24px;scrollbar-width:none}
.track::-webkit-scrollbar{display:none}
.fc{flex:0 0 min(340px,82vw);scroll-snap-align:start;border-radius:28px;padding:30px;min-height:360px;display:flex;flex-direction:column;justify-content:space-between;color:#fff;background:var(--bg)}
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
footer .brand{font-size:1.15rem;color:#fff}footer .brand svg{width:26px;height:26px}
footer .soc{display:flex;gap:18px}
footer .soc a{text-decoration:none}footer .soc a:hover{color:#fff}

@media(prefers-reduced-motion:reduce){*{animation:none!important;scroll-behavior:auto!important}}
</style>
</head>
<body>
<header>
  <div class="wrap">
    <a class="brand" href="{{ url('/') }}">
      <img src="{{ asset('images/logo.png') }}" alt="Sirkelku Logo">
      Sirkelku.
    </a>
    <nav>
      <a class="link" href="#fitur">Fitur</a>
      <a class="link" href="#cara">Cara main</a>
      @auth
        <a class="btn sm" href="{{ route('feed.index') }}">Masuk ke Aplikasi</a>
      @else
        <a class="link" href="{{ route('login') }}">Masuk</a>
        <a class="btn sm" href="{{ route('register') }}">Daftar sekarang</a>
      @endauth
    </nav>
  </div>
</header>

<main>
<section class="hero">
  <div class="wrap">
    <div>
      <h1>Cari teman se-frekuensi. Bikin sirkel sendiri!</h1>
      <p class="lead">Tempat nongkrong online buat kamu yang mau berbagi hobi, ngobrol seru, atau sekadar mabar. Gabung ke sirkel yang cocok, atau buka sirkelmu sendiri.</p>
      <div class="cta">
        @auth
          <a class="btn" href="{{ route('feed.index') }}">Mulai Nongkrong</a>
        @else
          <a class="btn" href="{{ route('register') }}">Daftar sekarang</a>
          <a class="btn ghost" href="#fitur">Lihat fiturnya</a>
        @endauth
      </div>
      <p class="note">Pilih minat di samping, lihat sirkel seperti apa yang bisa kamu temukan.</p>
    </div>
    <div class="stagewrap">
      <div class="stage" id="stage" aria-live="polite">
        <div class="rg"></div><div class="rg b"></div>
        <div class="ring" id="ring"></div>
        <div class="core"><span class="big" id="cEmoji">🎮</span><b id="cName">Sirkel Mabar</b><small id="cSub">Cari squad tiap malam</small></div>
      </div>
      <div class="chips" id="chips" role="group" aria-label="Pilih minat"></div>
    </div>
  </div>
</section>

<div class="strip" aria-hidden="true"><div id="marq"></div></div>

<section class="features" id="fitur">
  <div class="wrap">
    <div class="fhead">
      <div>
        <h2>Fitur andalan Sirkelku</h2>
        <p>Semua yang kamu butuhkan buat nongkrong digital.</p>
      </div>
      <div class="arrows">
        <button id="prev" aria-label="Fitur sebelumnya">←</button>
        <button id="next" aria-label="Fitur berikutnya">→</button>
      </div>
    </div>
    <div class="track" id="track">
      <article class="fc" style="--bg:#FF4500"><div class="ic"><svg style="width: 40px; height: 40px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div><div><h3>Sirkel sesuai minat</h3><p>Gabung ke sirkel yang sehobi, dari musik sampai coding. Tidak ada yang cocok? Buat sendiri.</p></div></article>
      <article class="fc" style="--bg:#0079D3"><div class="ic"><svg style="width: 40px; height: 40px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg></div><div><h3>Ruang diskusi</h3><p>Ngobrol santai atau bahas serius, rapi per topik supaya obrolan gampang dicari lagi.</p></div></article>
      <article class="fc" style="--bg:#0B9A89"><div class="ic"><svg style="width: 40px; height: 40px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5"></path></svg></div><div><h3>Mabar tanpa ribet</h3><p>Cari teman main, bentuk squad, dan atur jadwal mabar dalam satu tempat.</p></div></article>
      <article class="fc" style="--bg:#E5484D"><div class="ic"><svg style="width: 40px; height: 40px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div><div><h3>Acara sirkel</h3><p>Jadwalkan kumpul online atau offline, anggota tinggal klik untuk ikut.</p></div></article>
      <article class="fc" style="--bg:#1A1A1B;border:1.5px solid var(--line)"><div class="ic"><svg style="width: 40px; height: 40px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg></div><div><h3>Nyaman dan aman</h3><p>Pembuat sirkel bisa atur aturan dan moderasi sendiri, jadi suasana tetap enak.</p></div></article>
    </div>
  </div>
</section>

<section class="steps" id="cara">
  <div class="wrap">
    <h2>Tiga langkah buat punya sirkel</h2>
    <div class="sgrid">
      <div class="st" style="--c:var(--blue)"><span class="n">1</span><h3>Daftar</h3><p>Bikin akun dan pilih minat yang kamu suka.</p></div>
      <div class="st" style="--c:var(--orange)"><span class="n">2</span><h3>Temukan sirkel</h3><p>Telusuri sirkel yang cocok lalu gabung.</p></div>
      <div class="st" style="--c:var(--mint)"><span class="n">3</span><h3>Mulai ngobrol</h3><p>Sapa anggota, ikut acara, atau buka sirkelmu sendiri.</p></div>
    </div>
  </div>
</section>

<section class="final" id="daftar">
  <div class="wrap">
    <div class="box">
      <i></i><i></i><i></i>
      <h2>Sirkelmu lagi nunggu kamu</h2>
      <p>Gabung gratis, pilih minatmu, dan temukan teman yang satu frekuensi.</p>
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
  <div class="wrap">
    <a class="brand" href="{{ url('/') }}">
      <img src="{{ asset('images/logo.png') }}" alt="Sirkelku Logo">
      Sirkelku.
    </a>
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
const ring=document.getElementById('ring'),chips=document.getElementById('chips');
function show(i){
  const d=D[i];
  cEmoji.innerHTML=d.e;cName.textContent=d.n;cSub.textContent=d.s;
  ring.innerHTML=d.f.map((f,j)=>`<div class="av" style="--a:${j*60-90}deg"><div class="un"><div class="face" style="--a:${j*60-90}deg"><span style="--c:${C[(j+i)%6]};animation-delay:${j*60}ms;padding:0;overflow:hidden;"><img src="https://api.dicebear.com/7.x/avataaars/svg?seed=${f}&backgroundColor=transparent" style="width:100%;height:100%;object-fit:cover;"></span></div></div></div>`).join('');
  [...chips.children].forEach((b,j)=>b.setAttribute('aria-pressed',j===i));
}
D.forEach((d,i)=>{const b=document.createElement('button');b.className='chip';b.textContent=d.k;b.onclick=()=>show(i);chips.appendChild(b)});
show(0);
let auto=setInterval(()=>{const cur=[...chips.children].findIndex(b=>b.getAttribute('aria-pressed')==='true');show((cur+1)%D.length)},4500);
chips.addEventListener('click',()=>clearInterval(auto));
if(matchMedia('(prefers-reduced-motion:reduce)').matches)clearInterval(auto);

const words=['Mabar','Diskusi','Musik','Anime','Baca buku','Fotografi','Ngoding','Nongkrong'];
marq.innerHTML=[...words,...words].map(w=>`<span>${w} ✦</span>`).join('');

const tr=document.getElementById('track');
const step=()=>tr.querySelector('.fc').offsetWidth+20;
prev.onclick=()=>tr.scrollBy({left:-step(),behavior:'smooth'});
next.onclick=()=>tr.scrollBy({left:step(),behavior:'smooth'});
</script>
</body>
</html>
