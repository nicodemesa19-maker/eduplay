<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verify OTP — EduPlay</title>
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
    .auth-logo{width:56px;height:56px;border-radius:16px;margin:0 auto 20px;background:linear-gradient(135deg,#6366f1,#7c3aed);display:flex;align-items:center;justify-content:center;font-size:1.6rem;color:#fff;box-shadow:0 8px 24px rgba(99,102,241,.4)}
    .auth-title{font-family:'Space Grotesk',sans-serif;font-size:1.65rem;font-weight:900;color:#fff;text-align:center;margin-bottom:6px}
    .auth-sub{text-align:center;font-size:.83rem;color:rgba(255,255,255,.38);margin-bottom:28px;line-height:1.6}
    .otp-inputs{display:flex;gap:10px;justify-content:center;margin-bottom:24px}
    .otp-box{width:52px;height:60px;background:rgba(255,255,255,.06);border:2px solid rgba(255,255,255,.12);border-radius:12px;color:#fff;font-size:1.6rem;font-weight:900;text-align:center;outline:none;transition:border-color .2s,box-shadow .2s;font-family:'Space Grotesk',sans-serif}
    .otp-box:focus{border-color:rgba(99,102,241,.7);box-shadow:0 0 0 3px rgba(99,102,241,.2)}
    .otp-box.filled{border-color:rgba(99,102,241,.5);background:rgba(99,102,241,.1)}
    .field-error{font-size:.73rem;color:#f87171;margin-top:5px;text-align:center}
    .btn-submit{width:100%;padding:13px;border:none;border-radius:12px;background:linear-gradient(135deg,#6366f1,#7c3aed);color:#fff;font-size:.95rem;font-weight:800;cursor:pointer;transition:all .22s;box-shadow:0 6px 20px rgba(99,102,241,.35);display:flex;align-items:center;justify-content:center;gap:8px}
    .btn-submit:hover{transform:translateY(-2px);box-shadow:0 10px 28px rgba(99,102,241,.5)}
    .alert-error{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.3);border-radius:12px;padding:12px 16px;margin-bottom:20px;font-size:.82rem;color:#fca5a5;display:flex;align-items:center;gap:8px}
    .alert-success{background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.3);border-radius:12px;padding:12px 16px;margin-bottom:20px;font-size:.82rem;color:#6ee7b7;display:flex;align-items:center;gap:8px}
    .back-login{text-align:center;margin-top:20px;font-size:.82rem;color:rgba(255,255,255,.3)}
    .back-login a{color:#818cf8;text-decoration:none;font-weight:700}
    .steps{display:flex;align-items:center;justify-content:center;gap:8px;margin-bottom:28px}
    .step{width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.72rem;font-weight:800}
    .step.done{background:rgba(16,185,129,.2);border:2px solid #10b981;color:#34d399}
    .step.active{background:rgba(99,102,241,.25);border:2px solid #6366f1;color:#a5b4fc}
    .step.inactive{background:rgba(255,255,255,.05);border:2px solid rgba(255,255,255,.1);color:rgba(255,255,255,.3)}
    .step-line{flex:1;max-width:40px;height:1px;background:rgba(255,255,255,.1)}
    .resend-row{text-align:center;margin-top:14px;font-size:.8rem;color:rgba(255,255,255,.3)}
    .resend-row a{color:#818cf8;text-decoration:none;font-weight:700}
  </style>
</head>
<body>
<div id="sym-layer" aria-hidden="true"></div>
<nav class="top-nav">
  <a href="/" class="brand"><img src="{{ asset('images/logo.png') }}" alt="EduPlay" style="height:50px;"></a>
  <a href="{{ session('otp_purpose') === 'teacher_verification' ? route('register.form', 'teacher') : route('password.request') }}" class="btn-back"><i class="bi bi-arrow-left-circle-fill"></i> {{ session('otp_purpose') === 'teacher_verification' ? 'Back to registration' : 'Back' }}</a>
</nav>
<div class="auth-wrap">
  <div class="auth-card">
    <div class="auth-logo"><i class="bi bi-shield-check"></i></div>
    <div class="steps">
      <div class="step done"><i class="bi bi-check" style="font-size:.8rem;"></i></div>
      <div class="step-line"></div>
      <div class="step active">2</div>
      <div class="step-line"></div>
      <div class="step inactive">3</div>
    </div>
    <h1 class="auth-title">{{ session('otp_purpose') === 'teacher_verification' ? 'Verify your email' : 'Enter OTP' }}</h1>
    <p class="auth-sub">{{ session('otp_purpose') === 'teacher_verification' ? 'We sent a 6-digit verification code to' : 'We sent a 6-digit code to' }}<br><strong style="color:#a78bfa;">{{ session('otp_email') }}</strong>
      @if(session('otp_purpose') === 'teacher_verification')
      <br><br>Enter it to confirm your teacher email. After verification, please wait for admin approval.
      @endif
    </p>

    @if($errors->has('otp'))
    <div class="alert-error"><i class="bi bi-exclamation-circle-fill"></i> {{ $errors->first('otp') }}</div>
    @endif
    @if(session('success'))
    <div class="alert-success"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ session('otp_purpose') === 'teacher_verification' ? route('teacher.verify-email.post') : route('password.otp.verify.post') }}" id="otpForm">
      @csrf
      <input type="hidden" name="otp" id="otpHidden">
      <div class="otp-inputs">
        <input type="text" class="otp-box" maxlength="1" inputmode="numeric" pattern="[0-9]">
        <input type="text" class="otp-box" maxlength="1" inputmode="numeric" pattern="[0-9]">
        <input type="text" class="otp-box" maxlength="1" inputmode="numeric" pattern="[0-9]">
        <input type="text" class="otp-box" maxlength="1" inputmode="numeric" pattern="[0-9]">
        <input type="text" class="otp-box" maxlength="1" inputmode="numeric" pattern="[0-9]">
        <input type="text" class="otp-box" maxlength="1" inputmode="numeric" pattern="[0-9]">
      </div>
      <button type="submit" class="btn-submit" id="verifyBtn" disabled>
        <i class="bi bi-shield-check"></i> {{ session('otp_purpose') === 'teacher_verification' ? 'Verify Email' : 'Verify OTP' }}
      </button>
    </form>
    <div class="resend-row">{{ session('otp_purpose') === 'teacher_verification' ? 'Need to start over?' : 'Didn\'t get it?' }} <a href="{{ session('otp_purpose') === 'teacher_verification' ? route('register.form', 'teacher') : route('password.request') }}">{{ session('otp_purpose') === 'teacher_verification' ? 'Go back and register again' : 'Resend OTP' }}</a></div>
    <div class="back-login"><a href="{{ route('login') }}">Back to login</a></div>
  </div>
</div>
<script>
(function(){const s=['π','∑','√','∞','÷','×','²','³'],l=document.getElementById('sym-layer');
for(let i=0;i<40;i++){const e=document.createElement('span');e.className='sym';e.textContent=s[Math.floor(Math.random()*s.length)];
e.style.left=Math.random()*100+'%';e.style.fontSize=(.5+Math.random()*1.1)+'rem';
e.style.animationDelay=Math.random()*12+'s';e.style.animationDuration=(8+Math.random()*10)+'s';
e.style.color=['#818cf8','#60a5fa','#fbbf24','#a78bfa'][Math.floor(Math.random()*4)];l.appendChild(e);}})();

const boxes = document.querySelectorAll('.otp-box');
const hidden = document.getElementById('otpHidden');
const btn    = document.getElementById('verifyBtn');

boxes.forEach((box, i) => {
  box.addEventListener('input', () => {
    box.value = box.value.replace(/[^0-9]/g,'');
    if (box.value && i < boxes.length - 1) boxes[i+1].focus();
    box.classList.toggle('filled', !!box.value);
    const val = [...boxes].map(b => b.value).join('');
    hidden.value = val;
    btn.disabled = val.length < 6;
  });
  box.addEventListener('keydown', e => {
    if (e.key === 'Backspace' && !box.value && i > 0) boxes[i-1].focus();
  });
  box.addEventListener('paste', e => {
    e.preventDefault();
    const paste = (e.clipboardData.getData('text') || '').replace(/\D/g,'').slice(0,6);
    paste.split('').forEach((c,j) => { if(boxes[j]) { boxes[j].value=c; boxes[j].classList.add('filled'); } });
    hidden.value = paste;
    btn.disabled = paste.length < 6;
    if(boxes[paste.length]) boxes[paste.length].focus();
  });
});
</script>
</body>
</html>
