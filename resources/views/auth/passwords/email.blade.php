<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password — EduPlay</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&family=Space+Grotesk:wght@700;800;900&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Inter', sans-serif;
      background: #07081a; color: #e2e8f0;
      min-height: 100vh; display: flex; flex-direction: column;
      overflow-x: hidden;
    }
    #sym-layer { position: fixed; inset: 0; pointer-events: none; z-index: 0; overflow: hidden; }
    .sym { position: absolute; font-family: 'Courier New', monospace; font-weight: 900; opacity: 0; animation: floatSym linear infinite; }
    @keyframes floatSym {
      0%   { transform: translateY(105vh) rotate(0deg); opacity: 0; }
      5%   { opacity: .13; } 95% { opacity: .13; }
      100% { transform: translateY(-10vh) rotate(360deg); opacity: 0; }
    }
    .top-nav {
      position: relative; z-index: 10; padding: 18px 28px;
      display: flex; align-items: center; justify-content: space-between;
    }
    .brand {
      font-family: 'Space Grotesk', sans-serif; font-size: 1.3rem; font-weight: 900;
      text-decoration: none;
      background: linear-gradient(135deg, #a78bfa, #60a5fa);
      -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
      display: flex; align-items: center; gap: 8px;
    }
    .brand img { height: 30px; width: auto; object-fit: contain; }
    .btn-back {
      font-size: .8rem; font-weight: 700; color: rgba(255,255,255,.4);
      text-decoration: none; display: flex; align-items: center; gap: 6px;
      background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.1);
      border-radius: 10px; padding: 7px 14px; transition: all .2s;
    }
    .btn-back:hover { color: #a5b4fc; background: rgba(99,102,241,.15); border-color: rgba(99,102,241,.4); }
    .auth-wrap {
      flex: 1; display: flex; align-items: center; justify-content: center;
      padding: 24px; position: relative; z-index: 1;
    }
    .auth-card {
      background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.09);
      border-radius: 24px; padding: 44px 40px; width: 100%; max-width: 420px;
      box-shadow: 0 24px 80px rgba(0,0,0,.5);
      animation: cardIn .55s cubic-bezier(.34,1.56,.64,1) both;
    }
    @keyframes cardIn { from { opacity:0; transform: translateY(32px) scale(.97); } to { opacity:1; transform: none; } }
    .auth-logo {
      width: 56px; height: 56px; border-radius: 16px; margin: 0 auto 20px;
      background: linear-gradient(135deg, #f59e0b, #d97706);
      display: flex; align-items: center; justify-content: center;
      font-size: 1.6rem; color: #fff;
      box-shadow: 0 8px 24px rgba(245,158,11,.4);
    }
    .auth-title {
      font-family: 'Space Grotesk', sans-serif; font-size: 1.65rem; font-weight: 900;
      color: #fff; text-align: center; margin-bottom: 6px;
    }
    .auth-sub { text-align: center; font-size: .83rem; color: rgba(255,255,255,.38); margin-bottom: 28px; line-height: 1.6; }
    .form-label {
      display: block; font-size: .75rem; font-weight: 700;
      color: rgba(255,255,255,.48); margin-bottom: 7px;
      text-transform: uppercase; letter-spacing: .6px;
    }
    .input-wrap { position: relative; margin-bottom: 20px; }
    .input-wrap .icon {
      position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
      color: rgba(255,255,255,.22); font-size: .9rem; pointer-events: none;
    }
    .form-control-dark {
      width: 100%; padding: 12px 14px 12px 40px;
      background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.1);
      border-radius: 12px; color: #fff; font-size: .9rem;
      transition: border-color .2s, box-shadow .2s; outline: none;
    }
    .form-control-dark::placeholder { color: rgba(255,255,255,.2); }
    .form-control-dark:focus { border-color: rgba(245,158,11,.6); box-shadow: 0 0 0 3px rgba(245,158,11,.14); }
    .btn-submit {
      width: 100%; padding: 13px; border: none; border-radius: 12px;
      background: linear-gradient(135deg, #f59e0b, #d97706);
      color: #fff; font-size: .95rem; font-weight: 800;
      cursor: pointer; transition: all .22s;
      box-shadow: 0 6px 20px rgba(245,158,11,.35);
      display: flex; align-items: center; justify-content: center; gap: 8px;
    }
    .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(245,158,11,.5); }
    .alert-success {
      background: rgba(16,185,129,.1); border: 1px solid rgba(16,185,129,.3);
      border-radius: 12px; padding: 12px 16px; margin-bottom: 20px;
      font-size: .82rem; color: #6ee7b7;
      display: flex; align-items: center; gap: 8px;
    }
    .back-login { text-align: center; margin-top: 20px; font-size: .82rem; color: rgba(255,255,255,.3); }
    .back-login a { color: #818cf8; text-decoration: none; font-weight: 700; }
    .back-login a:hover { color: #a5b4fc; }
  </style>
</head>
<body>
<div id="sym-layer" aria-hidden="true"></div>
<nav class="top-nav">
  <a href="/" class="brand">
    <img src="{{ asset('images/logo.png') }}" alt="EduPlay" style="height:50px;">
  </a>
  <a href="{{ route('login') }}" class="btn-back">
    <i class="bi bi-house-fill"></i> Home
  </a>
</nav>
<div class="auth-wrap">
  <div class="auth-card">
    <div class="auth-logo"><i class="bi bi-key-fill"></i></div>
    <h1 class="auth-title">Forgot password?</h1>
    <p class="auth-sub">Enter your email and we'll send you a reset link right away.</p>

    @if(session('status'))
    <div class="alert-success">
      <i class="bi bi-check-circle-fill"></i> {{ session('status') }}
    </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
      @csrf
      <label class="form-label">Email address</label>
      <div class="input-wrap">
        <i class="icon bi bi-envelope-fill"></i>
        <input type="email" name="email" value="{{ old('email') }}" required
               class="form-control-dark" placeholder="your@email.com" autofocus>
        @error('email')<div style="font-size:.73rem;color:#f87171;margin-top:5px;">{{ $message }}</div>@enderror
      </div>
      <button type="submit" class="btn-submit">
        <i class="bi bi-send-fill"></i> Send Reset Link
      </button>
    </form>

    <div class="back-login">
      Remembered it? <a href="{{ route('login') }}"><i class="bi bi-door-open me-1"></i>Back to login</a>
    </div>
  </div>
</div>
<script>
(function(){
  const syms=['π','∑','√','∞','÷','×','±','∆','∫','²','³'];
  const layer=document.getElementById('sym-layer');
  for(let i=0;i<40;i++){
    const s=document.createElement('span'); s.className='sym';
    s.textContent=syms[Math.floor(Math.random()*syms.length)];
    s.style.left=Math.random()*100+'%';
    s.style.fontSize=(.5+Math.random()*1.1)+'rem';
    s.style.animationDelay=Math.random()*12+'s';
    s.style.animationDuration=(8+Math.random()*10)+'s';
    s.style.color=['#818cf8','#60a5fa','#fbbf24','#a78bfa'][Math.floor(Math.random()*4)];
    layer.appendChild(s);
  }
})();
</script>
</body>
</html>
