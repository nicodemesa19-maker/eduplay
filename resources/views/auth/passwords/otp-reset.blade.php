<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>New Password — EduPlay</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&family=Space+Grotesk:wght@700;800;900&display=swap" rel="stylesheet">
  <style>
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
    body{font-family:'Inter',sans-serif;background:#07081a;color:#e2e8f0;min-height:100vh;display:flex;flex-direction:column;overflow-x:hidden}
    #sym-layer{position:fixed;inset:0;pointer-events:none;z-index:0;overflow:hidden}
    .sym{position:absolute;font-family:'Courier New',monospace;font-weight:900;opacity:0;animation:floatSym linear infinite}
    @keyframes floatSym{0%{transform:translateY(105vh) rotate(0deg);opacity:0}5%{opacity:.13}95%{opacity:.13}100%{transform:translateY(-10vh) rotate(360deg);opacity:0}}
    .top-nav{position:relative;z-index:10;padding:18px 28px;display:flex;align-items:center;justify-content:space-between}
    .brand{font-family:'Space Grotesk',sans-serif;font-size:1.3rem;font-weight:900;text-decoration:none;background:linear-gradient(135deg,#a78bfa,#60a5fa);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;display:flex;align-items:center;gap:8px}
    .brand img{height:30px;width:auto;object-fit:contain}
    .btn-back{font-size:.8rem;font-weight:700;color:rgba(255,255,255,.4);text-decoration:none;display:flex;align-items:center;gap:6px;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:10px;padding:7px 14px;transition:all .2s}
    .btn-back:hover{color:#a5b4fc;background:rgba(99,102,241,.15);border-color:rgba(99,102,241,.4)}
    .auth-wrap{flex:1;display:flex;align-items:center;justify-content:center;padding:24px;position:relative;z-index:1}
    .auth-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.09);border-radius:24px;padding:44px 40px;width:100%;max-width:420px;box-shadow:0 24px 80px rgba(0,0,0,.5);animation:cardIn .55s cubic-bezier(.34,1.56,.64,1) both}
    @keyframes cardIn{from{opacity:0;transform:translateY(32px) scale(.97)}to{opacity:1;transform:none}}
    .auth-logo{width:56px;height:56px;border-radius:16px;margin:0 auto 20px;background:linear-gradient(135deg,#10b981,#059669);display:flex;align-items:center;justify-content:center;font-size:1.6rem;color:#fff;box-shadow:0 8px 24px rgba(16,185,129,.4)}
    .auth-title{font-family:'Space Grotesk',sans-serif;font-size:1.65rem;font-weight:900;color:#fff;text-align:center;margin-bottom:6px}
    .auth-sub{text-align:center;font-size:.83rem;color:rgba(255,255,255,.38);margin-bottom:28px}
    .form-group{margin-bottom:16px}
    .form-label{display:block;font-size:.75rem;font-weight:700;color:rgba(255,255,255,.48);margin-bottom:7px;text-transform:uppercase;letter-spacing:.6px}
    .input-wrap{position:relative}
    .input-wrap .icon{position:absolute;left:14px;top:50%;transform:translateY(-50%);color:rgba(255,255,255,.22);font-size:.9rem;pointer-events:none}
    .form-control-dark{width:100%;padding:12px 40px 12px 40px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:12px;color:#fff;font-size:.9rem;transition:border-color .2s,box-shadow .2s;outline:none}
    .form-control-dark::placeholder{color:rgba(255,255,255,.2)}
    .form-control-dark:focus{border-color:rgba(16,185,129,.6);box-shadow:0 0 0 3px rgba(16,185,129,.14)}
    .toggle-pw{position:absolute;right:14px;top:50%;transform:translateY(-50%);background:none;border:none;color:rgba(255,255,255,.3);cursor:pointer;font-size:.9rem;padding:0;transition:color .2s}
    .toggle-pw:hover{color:#a5b4fc}
    .field-error{font-size:.73rem;color:#f87171;margin-top:5px}
    .btn-submit{width:100%;padding:13px;border:none;border-radius:12px;margin-top:6px;background:linear-gradient(135deg,#10b981,#059669);color:#fff;font-size:.95rem;font-weight:800;cursor:pointer;transition:all .22s;box-shadow:0 6px 20px rgba(16,185,129,.35);display:flex;align-items:center;justify-content:center;gap:8px}
    .btn-submit:hover{transform:translateY(-2px);box-shadow:0 10px 28px rgba(16,185,129,.5)}
    .steps{display:flex;align-items:center;justify-content:center;gap:8px;margin-bottom:28px}
    .step{width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.72rem;font-weight:800}
    .step.done{background:rgba(16,185,129,.2);border:2px solid #10b981;color:#34d399}
    .step.active{background:rgba(16,185,129,.25);border:2px solid #10b981;color:#34d399}
    .step-line{flex:1;max-width:40px;height:1px;background:rgba(255,255,255,.1)}
  </style>
</head>
<body>
<div id="sym-layer" aria-hidden="true"></div>
<nav class="top-nav">
  <a href="/" class="brand"><img src="{{ asset('images/logo.png') }}" alt="EduPlay" style="height:50px;"></a>
  <a href="{{ route('login') }}" class="btn-back"><i class="bi bi-house-fill"></i> Home</a>
</nav>
<div class="auth-wrap">
  <div class="auth-card">
    <div class="auth-logo"><i class="bi bi-lock-fill"></i></div>
    <div class="steps">
      <div class="step done"><i class="bi bi-check" style="font-size:.8rem;"></i></div>
      <div class="step-line"></div>
      <div class="step done"><i class="bi bi-check" style="font-size:.8rem;"></i></div>
      <div class="step-line"></div>
      <div class="step active">3</div>
    </div>
    <h1 class="auth-title">New password</h1>
    <p class="auth-sub">Choose a strong new password.</p>

    <form method="POST" action="{{ route('password.otp.update') }}">
      @csrf
      <div class="form-group">
        <label class="form-label">New password</label>
        <div class="input-wrap">
          <i class="icon bi bi-lock-fill"></i>
          <input type="password" name="password" id="pw1" required
                 class="form-control-dark" placeholder="Min. 8 characters">
          <button type="button" class="toggle-pw" onclick="togglePw('pw1','i1')"><i class="bi bi-eye" id="i1"></i></button>
        </div>
        @error('password')<div class="field-error">{{ $message }}</div>@enderror
        <div id="pw-strength-bar" style="height:4px;border-radius:4px;margin-top:8px;background:rgba(255,255,255,.08);overflow:hidden;">
          <div id="pw-strength-fill" style="height:100%;width:0%;border-radius:4px;transition:width .3s,background .3s;"></div>
        </div>
        <div id="pw-strength-text" style="font-size:.7rem;margin-top:4px;color:rgba(255,255,255,.3);"></div>
      </div>
      <div class="form-group">
        <label class="form-label">Confirm password</label>
        <div class="input-wrap">
          <i class="icon bi bi-lock-fill"></i>
          <input type="password" name="password_confirmation" id="pw2" required
                 class="form-control-dark" placeholder="Repeat password">
          <button type="button" class="toggle-pw" onclick="togglePw('pw2','i2')"><i class="bi bi-eye" id="i2"></i></button>
        </div>
      </div>
      <button type="submit" class="btn-submit">
        <i class="bi bi-check-circle-fill"></i> Reset Password
      </button>
    </form>
  </div>
</div>
<script>
(function(){const s=['π','∑','√','∞','÷','×','²','³'],l=document.getElementById('sym-layer');
for(let i=0;i<40;i++){const e=document.createElement('span');e.className='sym';e.textContent=s[Math.floor(Math.random()*s.length)];
e.style.left=Math.random()*100+'%';e.style.fontSize=(.5+Math.random()*1.1)+'rem';
e.style.animationDelay=Math.random()*12+'s';e.style.animationDuration=(8+Math.random()*10)+'s';
e.style.color=['#818cf8','#60a5fa','#34d399','#a78bfa'][Math.floor(Math.random()*4)];l.appendChild(e);}})();
function togglePw(id,iconId){const i=document.getElementById(id),ic=document.getElementById(iconId),h=i.type==='password';i.type=h?'text':'password';ic.className=h?'bi bi-eye-slash':'bi bi-eye';}

document.getElementById('pw1').addEventListener('input', function() {
  const v = this.value;
  const score = [v.length>=8, /[A-Z]/.test(v), /[a-z]/.test(v), /\d/.test(v), /[\W_]/.test(v)].filter(Boolean).length;
  const levels = [
    {w:'0%',c:'transparent',l:''},
    {w:'20%',c:'#ef4444',l:'Very weak'},
    {w:'40%',c:'#f97316',l:'Weak'},
    {w:'60%',c:'#eab308',l:'Fair'},
    {w:'80%',c:'#84cc16',l:'Strong'},
    {w:'100%',c:'#10b981',l:'Very strong ✓'},
  ];
  const lv = levels[score];
  document.getElementById('pw-strength-fill').style.width = lv.w;
  document.getElementById('pw-strength-fill').style.background = lv.c;
  const t = document.getElementById('pw-strength-text');
  t.textContent = lv.l; t.style.color = lv.c;
});
</script>
</body>
</html>
