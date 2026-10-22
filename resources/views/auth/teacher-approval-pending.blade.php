<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Waiting for Approval — EduPlay</title>
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
    .auth-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.09);border-radius:24px;padding:44px 40px;width:100%;max-width:520px;box-shadow:0 24px 80px rgba(0,0,0,.5);animation:cardIn .55s cubic-bezier(.34,1.56,.64,1) both}
    @keyframes cardIn{from{opacity:0;transform:translateY(32px) scale(.97)}to{opacity:1;transform:none}}
    .auth-logo{width:80px;height:80px;border-radius:20px;margin:0 auto 28px;background:linear-gradient(135deg,#10b981,#34d399);display:flex;align-items:center;justify-content:center;font-size:2.2rem;color:#fff;box-shadow:0 8px 32px rgba(16,185,129,.4)}
    .auth-title{font-family:'Space Grotesk',sans-serif;font-size:1.8rem;font-weight:900;color:#fff;text-align:center;margin-bottom:12px}
    .auth-sub{text-align:center;font-size:.9rem;color:rgba(255,255,255,.5);margin-bottom:32px;line-height:1.8}
    .info-box{background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.3);border-radius:14px;padding:20px;margin-bottom:28px;font-size:.88rem;color:rgba(255,255,255,.8);line-height:1.7}
    .info-box strong{color:#34d399;display:block;margin-bottom:8px;font-weight:700}
    .email-highlight{background:rgba(99,102,241,.15);border:1px solid rgba(99,102,241,.3);border-radius:8px;padding:12px;text-align:center;font-weight:700;color:#a78bfa;font-family:monospace;font-size:.85rem;margin:16px 0}
    .next-steps{background:rgba(99,102,241,.05);border-left:3px solid rgba(99,102,241,.5);border-radius:8px;padding:16px;margin:24px 0;font-size:.85rem}
    .next-steps ol{margin:8px 0 0 18px;padding:0}
    .next-steps li{margin:6px 0;color:rgba(255,255,255,.7)}
    .btn-home{width:100%;padding:13px;border:none;border-radius:12px;background:linear-gradient(135deg,#6366f1,#7c3aed);color:#fff;font-size:.95rem;font-weight:800;cursor:pointer;transition:all .22s;box-shadow:0 6px 20px rgba(99,102,241,.35);display:flex;align-items:center;justify-content:center;gap:8px;text-decoration:none;margin-top:16px}
    .btn-home:hover{transform:translateY(-2px);box-shadow:0 10px 28px rgba(99,102,241,.5);color:#fff;text-decoration:none}
    .status-badge{display:inline-block;background:rgba(16,185,129,.2);border:1px solid rgba(16,185,129,.5);color:#34d399;padding:6px 14px;border-radius:20px;font-size:.78rem;font-weight:700;margin-top:12px}
  </style>
</head>
<body>
<div id="sym-layer" aria-hidden="true"></div>
<nav class="top-nav">
  <a href="/" class="brand"><img src="{{ asset('images/logo.png') }}" alt="EduPlay" style="height:50px;"></a>
  <a href="{{ route('login') }}" class="btn-back"><i class="bi bi-arrow-left-circle-fill"></i> Back to login</a>
</nav>
<div class="auth-wrap">
  <div class="auth-card">
    <div class="auth-logo"><i class="bi bi-hourglass-split"></i></div>
    <h1 class="auth-title">Verification Complete!</h1>
    <p class="auth-sub">Your email has been verified successfully. Now we're waiting for admin approval to activate your teacher account.</p>

    <div class="info-box">
      <strong><i class="bi bi-info-circle"></i> What happens next?</strong>
      The EduPlay admin team will review your teacher registration and verify your faculty credentials. You'll receive an email notification once your account is approved.
    </div>

    <div class="email-highlight">
      <i class="bi bi-envelope"></i><br>{{ $email }}
    </div>

    <div class="next-steps">
      <strong>Timeline:</strong>
      <ol>
        <li>Admin reviews your faculty ID and information</li>
        <li>You'll receive an approval email (usually within 24-48 hours)</li>
        <li>Click the approval link or log in to access your account</li>
        <li>Start creating classrooms and quizzes!</li>
      </ol>
    </div>

    <p style="text-align:center;font-size:.82rem;color:rgba(255,255,255,.4);margin-top:20px;">
      <i class="bi bi-shield-check" style="color:#34d399;"></i>
      <span class="status-badge">Pending Admin Review</span>
    </p>

    <a href="{{ route('login') }}" class="btn-home">
      <i class="bi bi-box-arrow-in-right"></i> Go to Login
    </a>
  </div>
</div>

<script>
(function(){const s=['π','∑','√','∞','÷','×','²','³'],l=document.getElementById('sym-layer');
for(let i=0;i<40;i++){const e=document.createElement('span');e.className='sym';e.textContent=s[Math.floor(Math.random()*s.length)];
e.style.left=Math.random()*100+'%';e.style.fontSize=(.5+Math.random()*1.1)+'rem';
e.style.animationDelay=Math.random()*12+'s';e.style.animationDuration=(8+Math.random()*10)+'s';
e.style.color=['#818cf8','#60a5fa','#fbbf24','#a78bfa'][Math.floor(Math.random()*4)];l.appendChild(e);}})();
</script>
</body>
</html>
