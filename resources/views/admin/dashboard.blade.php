@extends('layouts.app')
@section('content')
<style>
:root {
  --pg-bg: #0b0d12;
  --pg-bg2: #0b0d12;
  --card-bg: #12151c;
  --card-bg-raised: #161a22;
  --card-border: rgba(255,255,255,.07);
  --card-border2: rgba(255,255,255,.06);
  --text: #e6e8ec;
  --text-muted: rgba(230,232,236,.52);
  --text-faint: rgba(230,232,236,.34);
  --input-bg: #161a22;
  --input-border: rgba(255,255,255,.10);
  --input-color: #e6e8ec;
  --head-bg: #0e1015;
  --head-border: rgba(255,255,255,.07);
  --shadow: 0 1px 2px rgba(0,0,0,.4);
  --shadow-md: 0 8px 24px rgba(0,0,0,.35);
  --accent: #4f7cff;
  --accent-soft: rgba(79,124,255,.12);
  --accent-border: rgba(79,124,255,.28);
  --success: #2fb673;
  --success-soft: rgba(47,182,115,.12);
  --warning: #d99a3d;
  --warning-soft: rgba(217,154,61,.12);
  --danger: #e2645f;
  --danger-soft: rgba(226,100,95,.12);
}
[data-bs-theme="light"] {
  --pg-bg: #f6f7f9;
  --pg-bg2: #f6f7f9;
  --card-bg: #ffffff;
  --card-bg-raised: #ffffff;
  --card-border: #e6e8ec;
  --card-border2: #edeef1;
  --text: #171a21;
  --text-muted: rgba(23,26,33,.58);
  --text-faint: rgba(23,26,33,.38);
  --input-bg: #ffffff;
  --input-border: #dcdfe4;
  --input-color: #171a21;
  --head-bg: #ffffff;
  --head-border: #e6e8ec;
  --shadow: 0 1px 2px rgba(16,24,40,.06);
  --shadow-md: 0 8px 24px rgba(16,24,40,.08);
  --accent: #3358e0;
  --accent-soft: rgba(51,88,224,.08);
  --accent-border: rgba(51,88,224,.22);
  --success: #1f9d63;
  --success-soft: rgba(31,157,99,.09);
  --warning: #b97417;
  --warning-soft: rgba(185,116,23,.1);
  --danger: #d0433d;
  --danger-soft: rgba(208,67,61,.09);
}

/* ---------- Page ---------- */
.t-page { background: var(--pg-bg); min-height: calc(100vh - 58px); padding: 0 0 60px; transition: background .2s; font-size: .95rem; }
.t-header { background: var(--head-bg); border-bottom: 1px solid var(--head-border); padding: 28px 0 0; margin-bottom: 28px; }
.t-header .container-lg { position: relative; }
.t-eyebrow { font-size: .72rem; font-weight: 700; color: var(--text-faint); letter-spacing: .3px; margin-bottom: 6px; }
.t-title-main { font-family: 'Space Grotesk', sans-serif; font-size: 1.65rem; font-weight: 700; margin: 0; letter-spacing: -.2px; color: var(--text); }
.t-admin-chip { background: var(--card-bg-raised); border: 1px solid var(--card-border); color: var(--text-muted); border-radius: 8px; padding: 6px 12px; font-size: .82rem; font-weight: 600; display: inline-flex; align-items: center; gap: 7px; }
.t-admin-chip i { color: var(--accent); }

/* ---------- Cards ---------- */
.t-card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 10px; overflow: hidden; transition: background .2s, border-color .2s; box-shadow: var(--shadow); }
.t-card-head { padding: 15px 20px; border-bottom: 1px solid var(--card-border2); display: flex; align-items: center; justify-content: space-between; gap: 12px; }
.t-card-body { padding: 20px; }
.t-title { display: flex; align-items: center; gap: 9px; font-size: .92rem; font-weight: 700; color: var(--text); }
.t-title i { font-size: .95rem; color: var(--text-muted); }
.t-text { color: var(--text); }
.t-muted { color: var(--text-muted); font-size: .92rem; }

/* ---------- Inputs ---------- */
.t-input { background: var(--input-bg); border: 1px solid var(--input-border); border-radius: 7px; color: var(--input-color); padding: 6px 12px; font-size: .85rem; width: 100%; outline: none; transition: border-color .15s; cursor: pointer; }
.t-input::placeholder { color: var(--text-faint); }
.t-input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-soft); }
.t-input option { background-color: var(--card-bg); color: var(--text); padding: 8px; }

/* ---------- Stat cards ---------- */
.stat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 20px; }
@media (max-width: 1100px) { .stat-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 560px)  { .stat-grid { grid-template-columns: 1fr; } }
.stat-card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 10px; padding: 18px 20px; box-shadow: var(--shadow); }
.stat-num { font-size: 1.85rem; font-weight: 700; color: var(--text); margin-bottom: 2px; line-height: 1.1; letter-spacing: -.4px; }
.stat-label { font-size: .74rem; font-weight: 600; color: var(--text-muted); letter-spacing: .2px; }
.stat-badge { display: inline-flex; align-items: center; gap: 5px; padding: 3px 8px; border-radius: 6px; font-size: .72rem; font-weight: 600; margin-top: 12px; }
.stat-icon { width: 38px; height: 38px; border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: 1.05rem; flex-shrink: 0; background: var(--card-bg-raised); border: 1px solid var(--card-border); color: var(--text-muted); }

/* ---------- Progress ---------- */
.progress-bar-custom { height: 6px; background: rgba(255,255,255,.06); border-radius: 99px; overflow: hidden; margin-top: 9px; }
[data-bs-theme="light"] .progress-bar-custom { background: rgba(16,24,40,.07); }
.progress-fill { height: 100%; border-radius: 99px; transition: width .6s ease; }

/* ---------- List items ---------- */
.dash-user-item { padding: 12px 4px; border-bottom: 1px solid var(--card-border2); display: flex; align-items: center; gap: 13px; }
.dash-user-item:last-child { border-bottom: none; padding-bottom: 4px; }
.dash-user-item:first-child { padding-top: 2px; }
.dash-user-rank { width: 26px; height: 26px; border-radius: 6px; background: var(--card-bg-raised); border: 1px solid var(--card-border); display: flex; align-items: center; justify-content: center; color: var(--text-muted); font-weight: 700; font-size: .78rem; flex-shrink: 0; }
.dash-user-rank.r1 { background: var(--accent-soft); border-color: var(--accent-border); color: var(--accent); }
.dash-user-info { flex: 1; min-width: 0; }
.dash-user-name { font-weight: 600; font-size: .88rem; color: var(--text); margin-bottom: 1px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.dash-user-email { font-size: .76rem; color: var(--text-faint); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.dash-stat-num { font-size: 1.05rem; font-weight: 700; color: var(--text); line-height: 1.1; }
.dash-stat-label { font-size: .66rem; text-transform: uppercase; letter-spacing: .5px; color: var(--text-faint); font-weight: 600; }
.dash-avatar { width: 34px; height: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: .95rem; flex-shrink: 0; background: var(--card-bg-raised); border: 1px solid var(--card-border); }
.empty-state { text-align: center; padding: 26px 0; color: var(--text-faint); }
.empty-state i { font-size: 1.6rem; opacity: .6; }

/* ---------- Activity ---------- */
.activity-row { display: flex; justify-content: space-between; align-items: center; }
.activity-row .lbl { font-weight: 600; color: var(--text); font-size: .87rem; }
.activity-row .val { font-size: 1.1rem; font-weight: 700; }
.rate-box { padding: 12px 14px; border-radius: 8px; background: var(--card-bg-raised); border: 1px solid var(--card-border); display: flex; justify-content: space-between; align-items: center; }

/* ---------- Mini stat cards ---------- */
.mini-stat { height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 22px; }
.mini-stat .stat-icon { width: 44px; height: 44px; font-size: 1.2rem; margin-bottom: 12px; border-radius: 10px; }

/* ---------- Nav tabs ---------- */
.nav-tabs-wrapper { display: flex; align-items: center; gap: 4px; margin-top: 20px; overflow-x: auto; padding-bottom: 0; border-bottom: 1px solid var(--head-border); }
.nav-tabs-wrapper::-webkit-scrollbar { height: 0; }
.nav-tab { display: inline-flex; align-items: center; gap: 7px; padding: 10px 14px; font-size: .86rem; font-weight: 600; color: var(--text-muted); text-decoration: none; border-bottom: 2px solid transparent; white-space: nowrap; transition: color .15s, border-color .15s; }
.nav-tab:hover { color: var(--text); }
.nav-tab.active { color: var(--text); border-bottom-color: var(--accent); }
.nav-tab .tab-badge { background: var(--warning-soft); color: var(--warning); border-radius: 99px; padding: 1px 7px; font-size: .7rem; font-weight: 700; }

/* ---------- Chart ---------- */
.chart-wrap { position: relative; height: 300px; padding: 18px 20px; }
.chart-legend { display: flex; justify-content: flex-start; gap: 20px; padding: 12px 20px; border-top: 1px solid var(--card-border2); }
.chart-legend span { font-size: .78rem; color: var(--text-muted); font-weight: 500; display: inline-flex; align-items: center; }
.chart-legend i { display: inline-block; width: 8px; height: 8px; border-radius: 2px; margin-right: 7px; }
.chart-select { width: auto; padding-right: 30px; appearance: none; background-image: url('data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%2710%27 height=%2710%27 viewBox=%270 0 10 10%27%3E%3Cpath fill=%27%23888%27 d=%27M5 7.5L1 3h8z%27/%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 10px center; }
</style>

<div class="t-page">
  <div class="t-header">
    <div class="container-lg px-4">
      <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
          <div class="t-eyebrow">ADMIN PANEL</div>
          <h1 class="t-title-main">Analytics Dashboard</h1>
          <p class="t-muted mt-1 mb-0">Platform insights and performance metrics</p>
        </div>
        <span class="t-admin-chip">
          <i class="bi bi-person-fill-gear"></i> {{ Auth::user()->name }}
        </span>
      </div>

      <div class="nav-tabs-wrapper">
        <a href="{{ url('/admin/dashboard') }}" class="nav-tab active">
          <i class="bi bi-house-fill"></i> Dashboard
        </a>
        <a href="{{ route('admin.pending-teachers') }}" class="nav-tab">
          <i class="bi bi-person-check-fill"></i> Pending Teachers
          @if($pendingTeachers > 0)
          <span class="tab-badge">{{ $pendingTeachers }}</span>
          @endif
        </a>
        <a href="{{ route('admin.manage-users') }}" class="nav-tab">
          <i class="bi bi-people-fill"></i> Manage Users
        </a>
      </div>
    </div>
  </div>

  <div class="container-lg px-4">

    {{-- Overview Stats --}}
    <div class="stat-grid">
      <div class="stat-card">
        <div class="d-flex justify-content-between align-items-start">
          <div style="flex:1">
            <div class="stat-num">{{ $totalTeachers + $totalStudents }}</div>
            <div class="stat-label">Total Users</div>
            <div class="stat-badge" style="background:var(--success-soft);color:var(--success);">
              <i class="bi bi-arrow-up"></i>{{ $recentTeachers + $recentStudents }} this week
            </div>
          </div>
          <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
        </div>
      </div>

      <div class="stat-card">
        <div class="d-flex justify-content-between align-items-start">
          <div style="flex:1">
            <div class="stat-num">{{ $totalTeachers }}</div>
            <div class="stat-label">Teachers</div>
            @if($pendingTeachers > 0)
            <div class="stat-badge" style="background:var(--warning-soft);color:var(--warning);">
              <i class="bi bi-hourglass-split"></i>{{ $pendingTeachers }} pending
            </div>
            @else
            <div class="stat-badge" style="background:var(--success-soft);color:var(--success);">
              <i class="bi bi-check-circle"></i>All approved
            </div>
            @endif
          </div>
          <div class="stat-icon"><i class="bi bi-mortarboard-fill"></i></div>
        </div>
      </div>

      <div class="stat-card">
        <div class="d-flex justify-content-between align-items-start">
          <div style="flex:1">
            <div class="stat-num">{{ $totalStudents }}</div>
            <div class="stat-label">Students</div>
            <div class="stat-badge" style="background:var(--success-soft);color:var(--success);">
              <i class="bi bi-arrow-up"></i>{{ $recentStudents }} this week
            </div>
          </div>
          <div class="stat-icon"><i class="bi bi-backpack-fill"></i></div>
        </div>
      </div>

      <div class="stat-card">
        <div class="d-flex justify-content-between align-items-start">
          <div style="flex:1">
            <div class="stat-num">{{ $totalClassrooms }}</div>
            <div class="stat-label">Classrooms</div>
            <div class="stat-badge" style="background:var(--accent-soft);color:var(--accent);">
              <i class="bi bi-building"></i>Active classes
            </div>
          </div>
          <div class="stat-icon"><i class="bi bi-building"></i></div>
        </div>
      </div>
    </div>

    {{-- User Growth Chart (Full Width) --}}
    <div class="t-card mb-4">
      <div class="t-card-head">
        <div class="t-title">
          <i class="bi bi-graph-up"></i>User Growth (Last 6 Months)
        </div>
        <select class="t-input chart-select" id="chartTypeSelector" onchange="updateChart()">
          <option value="bar">Bar chart</option>
          <option value="line">Line chart</option>
          <option value="doughnut">Donut chart</option>
        </select>
      </div>
      <div class="chart-wrap">
        <canvas id="userGrowthChart"></canvas>
      </div>
      <div class="chart-legend">
        <span><i style="background:var(--accent);"></i>Teachers</span>
        <span><i style="background:var(--success);"></i>Students</span>
      </div>
    </div>

    {{-- User Activity & Content Stats Row --}}
    <div class="row g-4 mb-4">
      <div class="col-lg-4">
        <div class="t-card" style="height:100%">
          <div class="t-card-head">
            <div class="t-title">
              <i class="bi bi-activity"></i>User Activity
            </div>
          </div>
          <div class="t-card-body">
            <div class="mb-4">
              <div class="activity-row">
                <span class="lbl">Active Users</span>
                <span class="val" style="color:var(--success);">{{ $activeUsers }}</span>
              </div>
              <div class="progress-bar-custom">
                <div class="progress-fill" style="width:{{ ($activeUsers / max($activeUsers + $inactiveUsers, 1)) * 100 }}%;background:var(--success);"></div>
              </div>
            </div>

            <div class="mb-4">
              <div class="activity-row">
                <span class="lbl">Inactive Users</span>
                <span class="val" style="color:var(--danger);">{{ $inactiveUsers }}</span>
              </div>
              <div class="progress-bar-custom">
                <div class="progress-fill" style="width:{{ ($inactiveUsers / max($activeUsers + $inactiveUsers, 1)) * 100 }}%;background:var(--danger);"></div>
              </div>
            </div>

            <div class="rate-box">
              <span style="color:var(--text-muted);font-size:.85rem;font-weight:500;">Activity Rate</span>
              <span style="font-weight:700;font-size:1rem;color:var(--text);">{{ round(($activeUsers / max($activeUsers + $inactiveUsers, 1)) * 100) }}%</span>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-8">
        <div class="row g-4" style="height:100%">
          <div class="col-md-6">
            <div class="t-card" style="height:100%">
              <div class="mini-stat">
                <div class="stat-icon"><i class="bi bi-joystick"></i></div>
                <div class="stat-num">{{ $totalGames }}</div>
                <div class="stat-label">Available Games</div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="t-card" style="height:100%">
              <div class="mini-stat">
                <div class="stat-icon"><i class="bi bi-building"></i></div>
                <div class="stat-num">{{ $totalClassrooms }}</div>
                <div class="stat-label">Active Classrooms</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Top Teachers & Recent Users --}}
    <div class="row g-4 mb-4">
      <div class="col-lg-6">
        <div class="t-card" style="height:100%">
          <div class="t-card-head">
            <div class="t-title">
              <i class="bi bi-trophy-fill"></i>Top Teachers
            </div>
          </div>
          <div class="t-card-body">
            @forelse($topTeachers as $index => $teacher)
            <div class="dash-user-item">
              <div class="dash-user-rank {{ $index === 0 ? 'r1' : '' }}">{{ $index + 1 }}</div>
              <div class="dash-user-info">
                <div class="dash-user-name">{{ $teacher->name }}</div>
                <div class="dash-user-email">{{ $teacher->email }}</div>
              </div>
              <div class="text-end">
                <div class="dash-stat-num">{{ $teacher->taught_classrooms_count }}</div>
                <div class="dash-stat-label">Classrooms</div>
              </div>
            </div>
            @empty
            <div class="empty-state">
              <i class="bi bi-inbox"></i>
              <p class="mt-2 mb-0">No teachers yet</p>
            </div>
            @endforelse
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="t-card" style="height:100%">
          <div class="t-card-head">
            <div class="t-title">
              <i class="bi bi-clock-history"></i>Recent Registrations
            </div>
          </div>
          <div class="t-card-body">
            @forelse($recentUsers as $user)
            <div class="dash-user-item">
              <div class="dash-avatar">
                <i class="bi {{ $user->role === 'teacher' ? 'bi-mortarboard-fill' : 'bi-backpack-fill' }}" style="color:{{ $user->role === 'teacher' ? 'var(--accent)' : 'var(--success)' }};"></i>
              </div>
              <div class="dash-user-info">
                <div class="dash-user-name">{{ $user->name }}</div>
                <div class="dash-user-email">{{ $user->email }}</div>
              </div>
              <div class="text-end">
                <span class="action-badge" style="background:{{ $user->role === 'teacher' ? 'var(--accent-soft)' : 'var(--success-soft)' }};color:{{ $user->role === 'teacher' ? 'var(--accent)' : 'var(--success)' }};padding:3px 9px;border-radius:6px;font-size:.72rem;font-weight:600;">
                  {{ ucfirst($user->role) }}
                </span>
                <div style="font-size:.72rem;color:var(--text-faint);margin-top:4px;">
                  {{ $user->created_at->diffForHumans() }}
                </div>
              </div>
            </div>
            @empty
            <div class="empty-state">
              <i class="bi bi-inbox"></i>
              <p class="mt-2 mb-0">No recent users</p>
            </div>
            @endforelse
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const chartData={labels:{!!json_encode(array_column($userGrowth,'month'))!!},teachers:{!!json_encode(array_column($userGrowth,'teachers'))!!},students:{!!json_encode(array_column($userGrowth,'students'))!!}};
let userGrowthChart=null;
document.addEventListener('DOMContentLoaded',()=>createChart('bar'));
function createChart(type){
const ctx=document.getElementById('userGrowthChart');
if(!ctx)return;
if(userGrowthChart)userGrowthChart.destroy();
const isDark=document.documentElement.getAttribute('data-bs-theme')!=='light';
const textColor=isDark?'#e6e8ec':'#171a21';
const mutedColor=isDark?'rgba(230,232,236,.52)':'rgba(23,26,33,.58)';
const gridColor=isDark?'rgba(255,255,255,0.06)':'rgba(16,24,40,0.06)';
const accent=isDark?'#4f7cff':'#3358e0';
const success=isDark?'#2fb673':'#1f9d63';
let config={
type:type,
data:{
labels:chartData.labels,
datasets:type==='doughnut'?[{
label:'Total Users',
data:chartData.teachers.map((t,i)=>t+chartData.students[i]),
backgroundColor:[accent,success,'#d99a3d','#8a8f9c','#5a8bd6','#4fae9b'],
borderColor:isDark?'#12151c':'#ffffff',
borderWidth:2
}]:[
{label:'Teachers',data:chartData.teachers,backgroundColor:type==='line'?'transparent':accent,borderColor:accent,borderWidth:2,borderRadius:4,fill:false,tension:.35,pointRadius:type==='line'?4:0,pointHoverRadius:type==='line'?6:0,pointBackgroundColor:accent,pointBorderColor:isDark?'#12151c':'#fff',pointBorderWidth:2},
{label:'Students',data:chartData.students,backgroundColor:type==='line'?'transparent':success,borderColor:success,borderWidth:2,borderRadius:4,fill:false,tension:.35,pointRadius:type==='line'?4:0,pointHoverRadius:type==='line'?6:0,pointBackgroundColor:success,pointBorderColor:isDark?'#12151c':'#fff',pointBorderWidth:2}
]
},
options:{
responsive:true,
maintainAspectRatio:false,
plugins:{
legend:{display:type==='doughnut',position:'bottom',labels:{color:mutedColor,padding:14,font:{size:12,weight:'600'},usePointStyle:true,pointStyle:'circle',boxWidth:8}},
tooltip:{backgroundColor:isDark?'#1a1e27':'#ffffff',titleColor:textColor,bodyColor:mutedColor,borderColor:isDark?'rgba(255,255,255,.1)':'rgba(16,24,40,.08)',borderWidth:1,padding:12,displayColors:true,callbacks:{label:ctx=>{let label=ctx.dataset.label||'';if(label)label+=': ';label+=ctx.parsed.y!==undefined?ctx.parsed.y:ctx.parsed;if(type==='doughnut'){const total=ctx.dataset.data.reduce((a,b)=>a+b,0);const perc=((ctx.parsed/total)*100).toFixed(1);label+=` (${perc}%)`}return label}}}
},
scales:type==='doughnut'?{}:{
x:{grid:{display:false},ticks:{color:mutedColor,font:{size:11,weight:'500'}}},
y:{beginAtZero:true,grid:{color:gridColor,drawBorder:false},ticks:{color:mutedColor,font:{size:11,weight:'500'},stepSize:1,callback:value=>Number.isInteger(value)?value:''}}
},
animation:{duration:500,easing:'easeOutQuart'}
}
};
userGrowthChart=new Chart(ctx,config);
}
function updateChart(){const type=document.getElementById('chartTypeSelector').value;createChart(type)}
const observer=new MutationObserver(mutations=>{mutations.forEach(m=>{if(m.attributeName==='data-bs-theme'){const type=document.getElementById('chartTypeSelector').value;createChart(type)}})});
observer.observe(document.documentElement,{attributes:true,attributeFilter:['data-bs-theme']});
</script>
@endsection