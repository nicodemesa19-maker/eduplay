@extends('layouts.app')
@section('content')
<style>
/* ============================================================
   Teacher analytics — design tokens
   Neutral canvas, hairline structure, one accent, two data hues.
   ============================================================ */
:root {
  --nav-h: 58px;

  --bg:        #0B0D13;
  --surface:   #11141C;
  --surface-2: #161A24;
  --surface-3: #1C2130;
  --line:      #232938;
  --line-soft: #1A1F2B;

  --ink:       #E7EAF0;
  --ink-2:     #99A2B4;
  --ink-3:     #626C80;

  --accent:    #5B7CFA;
  --accent-in: #ffffff;
  --accent-wash: rgba(91,124,250,.12);

  --d1: #5B7CFA;   /* completion */
  --d2: #2BB99B;   /* mastery */
  --good: #2BB99B;
  --mid:  #D8A33C;
  --low:  #E0616E;
  --off:  #566074;

  --tab-num: "tnum" 1, "lnum" 1;
  --r: 10px;
}
[data-bs-theme="light"] {
  --bg:        #F6F7F9;
  --surface:   #FFFFFF;
  --surface-2: #FFFFFF;
  --surface-3: #F2F4F7;
  --line:      #E3E6EC;
  --line-soft: #EDEFF3;

  --ink:       #12151D;
  --ink-2:     #5A6577;
  --ink-3:     #8A93A4;

  --accent:    #3D5BDB;
  --accent-wash: rgba(61,91,219,.08);

  --d1: #3D5BDB;
  --d2: #12917A;
  --good: #12917A;
  --mid:  #B07D12;
  --low:  #C93F4E;
  --off:  #98A1B0;
}

.t-page {
  background: var(--bg);
  min-height: calc(100vh - var(--nav-h));
  padding-bottom: 72px;
  color: var(--ink);
  font-size: 14px;
  line-height: 1.5;
  -webkit-font-smoothing: antialiased;
}
.t-page :where(h1,h2,h3) { letter-spacing: -.015em; }
.num { font-variant-numeric: var(--tab-num); }

/* ---------- masthead ---------- */
.mast { border-bottom: 1px solid var(--line); background: var(--surface); }
.mast-in { display: flex; align-items: flex-end; justify-content: space-between; gap: 20px; flex-wrap: wrap; padding: 20px 0 0; }
.mast h1 { font-size: 20px; font-weight: 650; margin: 0; }
.mast p { color: var(--ink-2); margin: 4px 0 0; font-size: 13px; max-width: 64ch; }
.who { display: inline-flex; align-items: center; gap: 7px; font-size: 12.5px; font-weight: 600; color: var(--ink-2); border: 1px solid var(--line); border-radius: 99px; padding: 5px 12px; }
.who .dot { width: 6px; height: 6px; border-radius: 50%; background: var(--good); }

.tabs { display: flex; gap: 2px; margin-top: 16px; overflow-x: auto; scrollbar-width: none; }
.tabs::-webkit-scrollbar { display: none; }
.tab { position: relative; padding: 10px 14px; font-size: 13px; font-weight: 550; color: var(--ink-2); text-decoration: none; white-space: nowrap; display: inline-flex; align-items: center; gap: 7px; border-bottom: 2px solid transparent; }
.tab:hover { color: var(--ink); }
.tab.active { color: var(--ink); border-bottom-color: var(--accent); }
.tab .bi { font-size: 13px; opacity: .8; }

/* ---------- control bar ---------- */
.ctl { position: sticky; top: var(--nav-h); z-index: 30; background: var(--bg); border-bottom: 1px solid var(--line); }
.ctl-in { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; padding: 10px 0; }
.ctl-group { display: flex; align-items: center; gap: 7px; }
.ctl-sep { width: 1px; height: 22px; background: var(--line); }
.lab { font-size: 12px; color: var(--ink-3); font-weight: 550; }

.inp {
  appearance: none; background: var(--surface); border: 1px solid var(--line);
  color: var(--ink); border-radius: 8px; padding: 6px 10px; font-size: 13px; font-weight: 500;
  outline: none; transition: border-color .12s, box-shadow .12s; min-height: 32px;
}
select.inp { padding-right: 26px; background-image: linear-gradient(45deg, transparent 50%, var(--ink-3) 50%), linear-gradient(135deg, var(--ink-3) 50%, transparent 50%); background-position: right 12px center, right 7px center; background-size: 5px 5px, 5px 5px; background-repeat: no-repeat; }
.inp:hover { border-color: var(--ink-3); }
.inp:focus-visible { border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-wash); }
.inp::placeholder { color: var(--ink-3); }
.inp option { background: var(--surface); color: var(--ink); }
.srch { position: relative; }
.srch .bi { position: absolute; left: 9px; top: 50%; transform: translateY(-50%); color: var(--ink-3); font-size: 12px; pointer-events: none; }
.srch .inp { padding-left: 28px; width: 190px; }

.btn { display: inline-flex; align-items: center; gap: 6px; border-radius: 8px; font-size: 12.5px; font-weight: 600; padding: 6px 11px; min-height: 32px; cursor: pointer; text-decoration: none; border: 1px solid var(--line); background: var(--surface); color: var(--ink-2); }
.btn:hover { color: var(--ink); border-color: var(--ink-3); }
.btn:focus-visible { outline: 2px solid var(--accent); outline-offset: 1px; }
.btn-acc { background: var(--accent); border-color: var(--accent); color: var(--accent-in); }
.btn-acc:hover { color: var(--accent-in); filter: brightness(1.08); border-color: var(--accent); }
.btn-xs { padding: 4px 9px; min-height: 28px; font-size: 12px; }

.scope { margin-left: auto; display: flex; align-items: center; gap: 10px; font-size: 12.5px; color: var(--ink-3); }
.scope b { color: var(--ink); font-weight: 600; font-variant-numeric: var(--tab-num); }

/* ---------- export menu ---------- */
.xmenu { position: relative; }
.xmenu > summary { list-style: none; cursor: pointer; }
.xmenu > summary::-webkit-details-marker { display: none; }
.xmenu-list { position: absolute; right: 0; top: calc(100% + 5px); background: var(--surface-2); border: 1px solid var(--line); border-radius: 10px; box-shadow: 0 16px 40px rgba(0,0,0,.36), 0 2px 6px rgba(0,0,0,.2); padding: 5px; min-width: 244px; z-index: 60; }
[data-bs-theme="light"] .xmenu-list { box-shadow: 0 16px 36px rgba(16,24,40,.12), 0 2px 5px rgba(16,24,40,.06); }
.xmenu-list button, .xmenu-list .xlink { display: flex; align-items: center; gap: 9px; width: 100%; background: transparent; border: 0; color: var(--ink); text-align: left; padding: 8px 10px; border-radius: 7px; font-size: 12.5px; font-weight: 500; cursor: pointer; text-decoration: none; }
.xmenu-list button:hover, .xmenu-list button:focus-visible, .xmenu-list .xlink:hover { background: var(--surface-3); outline: none; color: var(--ink); }
.xmenu-list .bi { color: var(--ink-3); width: 14px; font-size: 12px; }
.xmenu-list .sep { height: 1px; background: var(--line); margin: 4px 2px; }
.xmenu-list .hint { font-size: 11px; color: var(--ink-3); padding: 5px 10px 4px; line-height: 1.45; }

/* ---------- metric strip ---------- */
.strip { border: 1px solid var(--line); border-radius: var(--r); background: var(--surface); display: grid; grid-template-columns: repeat(4, 1fr); overflow: hidden; margin: 20px 0; }
.metric { padding: 16px 18px; border-right: 1px solid var(--line-soft); position: relative; }
.metric:last-child { border-right: 0; }
.metric-l { font-size: 12px; color: var(--ink-2); font-weight: 550; display: flex; align-items: center; gap: 6px; }
.metric-v { font-size: 28px; font-weight: 600; margin-top: 8px; line-height: 1; font-variant-numeric: var(--tab-num); letter-spacing: -.02em; }
.metric-v small { font-size: 15px; color: var(--ink-3); font-weight: 500; margin-left: 2px; }
.metric-s { font-size: 12px; color: var(--ink-3); margin-top: 6px; }
.metric-bar { height: 3px; background: var(--line); border-radius: 2px; margin-top: 12px; overflow: hidden; }
.metric-bar i { display: block; height: 100%; border-radius: 2px; }
@media (max-width: 900px) { .strip { grid-template-columns: repeat(2, 1fr); } .metric:nth-child(2) { border-right: 0; } .metric:nth-child(-n+2) { border-bottom: 1px solid var(--line-soft); } }
@media (max-width: 480px) { .strip { grid-template-columns: 1fr; } .metric { border-right: 0; border-bottom: 1px solid var(--line-soft); } }

/* ---------- panels ---------- */
.panel { background: var(--surface); border: 1px solid var(--line); border-radius: var(--r); overflow: hidden; }
.panel-h { padding: 14px 18px; border-bottom: 1px solid var(--line-soft); display: flex; align-items: flex-start; justify-content: space-between; gap: 14px; flex-wrap: wrap; }
.panel-h h2 { font-size: 14px; font-weight: 600; margin: 0; }
.panel-h .sub { font-size: 12px; color: var(--ink-3); margin-top: 4px; max-width: 70ch; }
.panel-b { padding: 16px 18px; }
.panel-acts { display: flex; align-items: center; gap: 7px; }

.chart-box { position: relative; }

/* ---------- status marks ---------- */
.smark { display: inline-flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 550; color: var(--ink-2); white-space: nowrap; }
.smark i.sq { width: 6px; height: 6px; border-radius: 2px; flex-shrink: 0; }
.filters-row { display: flex; gap: 6px; flex-wrap: wrap; margin-top: 12px; }
.fchip { display: inline-flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 550; color: var(--ink-2); background: transparent; border: 1px solid var(--line); border-radius: 7px; padding: 4px 9px; cursor: pointer; font-variant-numeric: var(--tab-num); }
.fchip:hover { border-color: var(--ink-3); color: var(--ink); }
.fchip[aria-pressed="true"] { border-color: var(--accent); background: var(--accent-wash); color: var(--ink); }
.fchip:focus-visible { outline: 2px solid var(--accent); outline-offset: 1px; }
.fchip i.sq { width: 6px; height: 6px; border-radius: 2px; }
.fchip b { font-weight: 600; color: var(--ink); }

/* ---------- attention rows ---------- */
.alist { max-height: 372px; overflow-y: auto; overflow-x: hidden; }
.arow { display: grid; grid-template-columns: 26px minmax(0, 1fr) auto auto; align-items: center; gap: 10px; width: 100%; text-align: left; background: transparent; border: 0; border-bottom: 1px solid var(--line-soft); padding: 10px 14px; color: var(--ink); cursor: pointer; box-sizing: border-box; }
.arow:last-child { border-bottom: 0; }
.arow:hover { background: var(--surface-3); }
.arow:focus-visible { outline: none; background: var(--surface-3); box-shadow: inset 2px 0 0 var(--accent); }
.arow .chev { color: var(--ink-3); font-size: 11px; flex-shrink: 0; }
.aname { font-size: 13px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block; line-height: 1.3; }
.areason { font-size: 11.5px; color: var(--ink-3); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block; margin-top: 2px; line-height: 1.3; }
.ini { width: 26px; height: 26px; border-radius: 6px; background: var(--surface-3); border: 1px solid var(--line); color: var(--ink-2); display: flex; align-items: center; justify-content: center; font-size: 10.5px; font-weight: 650; letter-spacing: .02em; flex-shrink: 0; }

/* ---------- matrix ---------- */
.mx-wrap { overflow: auto; max-height: 560px; }
.mx { width: 100%; border-collapse: collapse; }
.mx th, .mx td { border-right: 1px solid var(--line-soft); border-bottom: 1px solid var(--line-soft); }
.mx thead th { position: sticky; top: 0; z-index: 2; background: var(--surface); font-size: 11.5px; font-weight: 600; color: var(--ink-2); padding: 9px 10px; text-align: center; white-space: nowrap; border-bottom: 1px solid var(--line); }
.mx th.c-name, .mx td.c-name { position: sticky; left: 0; z-index: 3; background: var(--surface); text-align: left; min-width: 210px; }
.mx thead th.c-name { z-index: 4; }
.mx tbody tr:hover td { background: var(--surface-3); }
.mx tbody tr:hover td.c-name { background: var(--surface-3); }
.mx td { padding: 0; text-align: center; height: 44px; }
.cellv { display: block; font-size: 13px; font-weight: 600; font-variant-numeric: var(--tab-num); line-height: 1.15; }
.cells { display: block; font-size: 10.5px; color: var(--ink-3); font-variant-numeric: var(--tab-num); margin-top: 2px; }
.cell { display: flex; flex-direction: column; justify-content: center; min-height: 44px; padding: 6px 8px; position: relative; }
.cell::after { content: ''; position: absolute; left: 0; right: 0; bottom: 0; height: 2px; background: currentColor; opacity: .55; }
.cell.empty { color: var(--ink-3); }
.cell.empty::after { display: none; }
.stu { display: flex; align-items: center; gap: 9px; width: 100%; background: transparent; border: 0; color: var(--ink); padding: 7px 12px; text-align: left; cursor: pointer; }
.stu .nm { font-size: 13px; font-weight: 600; }
.stu .meta { font-size: 11px; color: var(--ink-3); margin-top: 1px; }
.stu:hover .nm { color: var(--accent); }
.stu:focus-visible { outline: 2px solid var(--accent); outline-offset: -2px; }
.scale { display: flex; gap: 16px; flex-wrap: wrap; align-items: center; padding: 11px 18px; border-top: 1px solid var(--line-soft); font-size: 12px; color: var(--ink-3); }
.scale span { display: inline-flex; align-items: center; gap: 7px; }
.scale i { width: 18px; height: 6px; border-radius: 2px; }

/* ---------- segmented ---------- */
.seg { display: inline-flex; background: var(--surface-3); border: 1px solid var(--line); border-radius: 8px; padding: 2px; }
.seg button { border: 0; background: transparent; color: var(--ink-2); font-weight: 600; font-size: 12px; padding: 4px 11px; border-radius: 6px; cursor: pointer; }
.seg button:hover { color: var(--ink); }
.seg button.active { background: var(--surface); color: var(--ink); box-shadow: 0 1px 2px rgba(0,0,0,.25); }
[data-bs-theme="light"] .seg button.active { box-shadow: 0 1px 2px rgba(16,24,40,.08); }
.seg button:focus-visible { outline: 2px solid var(--accent); outline-offset: 1px; }

/* ---------- empty / loading ---------- */
.nodata { padding: 44px 20px; text-align: center; color: var(--ink-3); font-size: 13px; }
.nodata .bi { display: block; font-size: 18px; margin-bottom: 8px; opacity: .6; }
.sk { background: var(--line-soft); border-radius: 5px; position: relative; overflow: hidden; }
.sk::after { content: ''; position: absolute; inset: 0; transform: translateX(-100%); background: linear-gradient(90deg, transparent, rgba(255,255,255,.06), transparent); animation: shim 1.4s infinite; }
@keyframes shim { 100% { transform: translateX(100%); } }
@media (prefers-reduced-motion: reduce) { .sk::after { animation: none; } }

/* ---------- data table ---------- */
.dt-wrap { overflow: auto; max-height: 460px; }
.dt { width: 100%; border-collapse: collapse; }
.dt th { position: sticky; top: 0; z-index: 1; background: var(--surface); font-size: 11.5px; font-weight: 600; color: var(--ink-2); text-align: left; padding: 9px 18px; border-bottom: 1px solid var(--line); white-space: nowrap; }
.dt td { padding: 9px 18px; border-bottom: 1px solid var(--line-soft); font-size: 13px; vertical-align: middle; }
.dt tbody tr:hover td { background: var(--surface-3); }
.dt tbody tr:last-child td { border-bottom: 0; }
.dt .n { font-variant-numeric: var(--tab-num); }
.bar { background: var(--line); border-radius: 2px; height: 4px; overflow: hidden; flex: 1; min-width: 60px; }
.bar i { display: block; height: 100%; background: var(--d1); border-radius: 2px; }
.gtag { display: inline-flex; align-items: center; gap: 7px; }
.gtag .gi { width: 22px; height: 22px; border-radius: 5px; display: flex; align-items: center; justify-content: center; font-size: 11px; background: var(--surface-3); border: 1px solid var(--line); }
.lvl { font-size: 11.5px; font-weight: 600; color: var(--ink-2); font-variant-numeric: var(--tab-num); white-space: nowrap; min-width: 64px; }
.live { display: inline-flex; align-items: center; gap: 5px; font-size: 11px; font-weight: 600; color: var(--ink-3); }
.live .pulse { width: 5px; height: 5px; border-radius: 50%; background: var(--good); animation: pulse 2s ease-in-out infinite; }
@keyframes pulse { 0%,100% { opacity: 1; } 50% { opacity: .25; } }
@media (prefers-reduced-motion: reduce) { .live .pulse { animation: none; } }
.rowcount { padding: 9px 18px; border-top: 1px solid var(--line-soft); font-size: 11.5px; color: var(--ink-3); font-variant-numeric: var(--tab-num); }

/* ---------- drawer ---------- */
.dback { position: fixed; inset: 0; background: rgba(6,8,14,.55); z-index: 1040; }
.drw { position: fixed; top: 0; right: 0; height: 100%; width: min(560px, 100%); background: var(--surface); border-left: 1px solid var(--line); z-index: 1050; overflow-y: auto; }
.drw[hidden], .dback[hidden] { display: none; }
.drw-h { position: sticky; top: 0; background: var(--surface); border-bottom: 1px solid var(--line); padding: 16px 20px; display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; z-index: 2; }
.drw-b { padding: 16px 20px 44px; }
.drw h2 { font-size: 16px; font-weight: 650; margin: 0; }
.drw .meta { font-size: 12px; color: var(--ink-3); margin-top: 3px; }
.xclose { background: transparent; border: 1px solid var(--line); color: var(--ink-2); border-radius: 7px; width: 28px; height: 28px; cursor: pointer; font-size: 12px; flex-shrink: 0; }
.xclose:hover { color: var(--ink); border-color: var(--ink-3); }
.status-explain { margin: 12px 0 4px; padding: 12px 14px; border-radius: 8px; border: 1px solid var(--line); background: rgba(255,255,255,.03); font-size: 12px; line-height: 1.65; }
.status-explain .se-title { font-weight: 650; font-size: 12.5px; margin-bottom: 6px; display:flex; align-items:center; gap:6px; }
.status-explain .se-title i { width:7px; height:7px; border-radius:2px; flex-shrink:0; }
.status-explain .se-rule { color: var(--ink-3); margin: 2px 0; }
.status-explain .se-rule b { color: var(--ink); font-weight: 600; }
.status-explain .se-crit { display:flex; align-items:baseline; gap:6px; margin: 3px 0; padding: 3px 0; }
.status-explain .se-crit .chk { width:14px; height:14px; border-radius:3px; display:inline-flex; align-items:center; justify-content:center; font-size:9px; flex-shrink:0; font-weight:700; }
.status-explain .se-crit .chk.pass { background: rgba(43,185,155,.18); color: var(--good); }
.status-explain .se-crit .chk.fail { background: rgba(224,97,110,.18); color: var(--low); }
.status-explain .se-note { margin-top: 8px; padding-top: 8px; border-top: 1px solid var(--line-soft); color: var(--ink-3); font-style: italic; font-size: 11.5px; }
.kv { display: grid; grid-template-columns: repeat(2, 1fr); border: 1px solid var(--line); border-radius: 8px; overflow: hidden; margin: 14px 0; }
.kv div { padding: 11px 13px; border-right: 1px solid var(--line-soft); border-bottom: 1px solid var(--line-soft); }
.kv div:nth-child(2n) { border-right: 0; }
.kv div:nth-last-child(-n+2) { border-bottom: 0; }
.kv b { display: block; font-size: 18px; font-weight: 600; font-variant-numeric: var(--tab-num); }
.kv span { font-size: 11.5px; color: var(--ink-3); }
.gblock { border-top: 1px solid var(--line-soft); padding: 13px 0; }
.gblock .hd { display: flex; align-items: baseline; justify-content: space-between; gap: 10px; }
.gblock .hd strong { font-size: 13px; font-weight: 600; }
.gblock .hd span { font-size: 11.5px; color: var(--ink-3); font-variant-numeric: var(--tab-num); }
.lvls { display: flex; flex-wrap: wrap; gap: 3px; margin-top: 9px; }
.lvls i { width: 16px; height: 16px; border-radius: 3px; display: inline-block; }
.lvls i.weak { box-shadow: inset 0 0 0 1.5px var(--low); }

/* ---------- toasts ---------- */
.toasts { position: fixed; right: 16px; bottom: 16px; z-index: 1100; display: flex; flex-direction: column; gap: 7px; }
.toast-i { background: var(--surface-2); border: 1px solid var(--line); color: var(--ink); border-radius: 9px; padding: 10px 13px; font-size: 12.5px; font-weight: 500; box-shadow: 0 12px 30px rgba(0,0,0,.35); display: flex; align-items: center; gap: 8px; max-width: 330px; }
.toast-i .bi { font-size: 13px; }
.toast-i.ok .bi { color: var(--good); }
.toast-i.err .bi { color: var(--low); }

.notice { display: flex; align-items: center; gap: 8px; border: 1px solid var(--line); border-radius: 8px; padding: 10px 14px; font-size: 13px; margin-top: 18px; }

@media (max-width: 767px) {
  .ctl { position: static; }
  .scope { margin-left: 0; width: 100%; }
  .srch .inp { width: 100%; }
}
@media print {
  .ctl, .tabs, .panel-acts, .drw, .dback, .toasts { display: none !important; }
  .t-page { background: #fff; }
  .panel, .strip { break-inside: avoid; border-color: #ddd; }
}
</style>

<div class="t-page">

  {{-- masthead --}}
  <div class="mast">
    <div class="container-lg px-4">
      <div class="mast-in">
        <div style="display: flex; align-items: center; gap: 20px;">
          @if(Auth::user()->avatar_url)
            <div style="width: 96px; height: 96px; border-radius: 50%; overflow: hidden; border: 3px solid var(--line); flex-shrink: 0;">
              @if(str_starts_with(Auth::user()->avatar_url, 'http'))
                <img src="{{ Auth::user()->avatar_url }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
              @else
                <img src="{{ Storage::url(Auth::user()->avatar_url) }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
              @endif
            </div>
          @endif
          <div>
            <h1>{{ Auth::user()->display_name ?: Auth::user()->name }}'s Dashboard</h1>
          </div>
        </div>
        <span class="who"><span class="dot"></span>{{ Auth::user()->name }}</span>
      </div>
      <nav class="tabs">
        <a href="{{ route('teacher.dashboard') }}" class="tab active"><i class="bi bi-graph-up"></i> Overview</a>
        <a href="{{ route('teacher.classrooms') }}" class="tab"><i class="bi bi-building"></i> Classrooms</a>
        <a href="{{ route('teacher.students') }}" class="tab"><i class="bi bi-people"></i> Students</a>
        <a href="{{ route('teacher.export-progress') }}" class="tab"><i class="bi bi-download"></i> Export data</a>
        <a href="{{ route('teacher.profile.show') }}" class="tab"><i class="bi bi-person-circle"></i> Profile</a>
      </nav>
    </div>
  </div>

  {{-- control bar --}}
  <div class="ctl">
    <div class="container-lg px-4">
      <div class="ctl-in">
        <div class="ctl-group">
          <label class="lab" for="gradeSelect">Grade</label>
          <select id="gradeSelect" class="inp">
            <option value="">All</option>
            <option value="4">Grade 4</option>
            <option value="5">Grade 5</option>
            <option value="6">Grade 6</option>
          </select>
        </div>
        <div class="ctl-group">
          <label class="lab" for="sectionSelect">Section</label>
          <select id="sectionSelect" class="inp">
            <option value="">All</option>
            @foreach($classrooms as $c)
              <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->class_code }})</option>
            @endforeach
          </select>
        </div>
        <div class="ctl-sep"></div>
        <div class="srch">
          <i class="bi bi-search"></i>
          <label for="studentSearch" class="visually-hidden">Find a student</label>
          <input type="search" id="studentSearch" class="inp" placeholder="Find a student">
        </div>
        <button type="button" class="btn" id="resetFilters"><i class="bi bi-arrow-counterclockwise"></i> Reset</button>

        <div class="scope">
          <span id="scopeLabel" aria-live="polite"></span>
          <details class="xmenu" id="mainExport">
            <summary class="btn btn-acc"><i class="bi bi-box-arrow-down"></i> Export</summary>
            <div class="xmenu-list">
              <button type="button" data-x="workbook"><i class="bi bi-table"></i> Excel workbook — all tables</button>
              <button type="button" data-x="charts"><i class="bi bi-bar-chart"></i> Excel workbook — charts</button>
              <button type="button" data-x="both"><i class="bi bi-collection"></i> Excel workbook — tables and charts</button>
              <div class="sep"></div>
              <button type="button" data-x="csv-students"><i class="bi bi-filetype-csv"></i> CSV — student summary</button>
              <a class="xlink" href="{{ route('teacher.export-progress') }}"><i class="bi bi-cloud-download"></i> CSV — full progress (server)</a>
              <div class="hint">Exports use the filters set above.</div>
            </div>
          </details>
        </div>
      </div>
    </div>
  </div>

  <div class="container-lg px-4">

    @if(session('success'))
    <div class="notice" style="border-color:rgba(43,185,155,.4);color:var(--good);"><i class="bi bi-check2"></i> {{ session('success') }}</div>
    @endif
    @if(session('message'))
    <div class="notice" style="border-color:var(--accent);color:var(--accent);"><i class="bi bi-info-circle"></i> {{ session('message') }}</div>
    @endif

    {{-- metric strip --}}
    <div class="strip" id="kpiRow">
      <div class="metric"><div class="sk" style="height:11px;width:56%"></div><div class="sk" style="height:24px;width:40%;margin-top:12px"></div></div>
      <div class="metric"><div class="sk" style="height:11px;width:56%"></div><div class="sk" style="height:24px;width:40%;margin-top:12px"></div></div>
      <div class="metric"><div class="sk" style="height:11px;width:56%"></div><div class="sk" style="height:24px;width:40%;margin-top:12px"></div></div>
      <div class="metric"><div class="sk" style="height:11px;width:56%"></div><div class="sk" style="height:24px;width:40%;margin-top:12px"></div></div>
    </div>

    {{-- attention + topics --}}
    <div class="row g-3 mb-3">
      <div class="col-lg-5">
        <div class="panel h-100">
          <div class="panel-h">
            <div style="flex:1;min-width:0;">
              <h2>Needs attention</h2>
              <div class="sub">Select a status to filter, or a student to open their record.</div>
              <div class="filters-row" id="statusChips"></div>
            </div>
            <div class="panel-acts">
              <details class="xmenu"><summary class="btn btn-xs"><i class="bi bi-box-arrow-down"></i></summary>
                <div class="xmenu-list">
                  <button type="button" data-x="sheet" data-key="attention"><i class="bi bi-table"></i> Excel — flagged students</button>
                  <button type="button" data-x="csv" data-key="attention"><i class="bi bi-filetype-csv"></i> CSV — flagged students</button>
                </div>
              </details>
            </div>
          </div>
          <div class="alist" id="attentionList"></div>
        </div>
      </div>

      <div class="col-lg-7">
        <div class="panel h-100">
          <div class="panel-h">
            <div>
              <h2>Progress by topic</h2>
              <div class="sub">Completion is how far students have gone. Mastery is their average score on levels played.</div>
            </div>
            <div class="panel-acts">
              <details class="xmenu"><summary class="btn btn-xs"><i class="bi bi-box-arrow-down"></i></summary>
                <div class="xmenu-list">
                  <button type="button" data-x="sheet" data-key="topics"><i class="bi bi-table"></i> Excel — table</button>
                  <button type="button" data-x="chart" data-key="topics"><i class="bi bi-bar-chart"></i> Excel — chart image</button>
                  <button type="button" data-x="png" data-key="topics"><i class="bi bi-image"></i> PNG image</button>
                </div>
              </details>
            </div>
          </div>
          <div class="panel-b">
            <div class="chart-box" style="height:336px;"><canvas id="topicChart" role="img" aria-label="Average completion and mastery for each game"></canvas></div>
          </div>
        </div>
      </div>
    </div>

    {{-- matrix --}}
    <div class="panel mb-3">
      <div class="panel-h">
        <div>
          <h2>Mastery by student and topic</h2>
          <div class="sub">Average score per game, with the level each student has reached. Select a student for the level detail.</div>
        </div>
        <div class="panel-acts">
          <details class="xmenu"><summary class="btn btn-xs"><i class="bi bi-box-arrow-down"></i> Export</summary>
            <div class="xmenu-list">
              <button type="button" data-x="sheet" data-key="heatmap"><i class="bi bi-table"></i> Excel — mastery grid</button>
              <button type="button" data-x="sheet" data-key="levels"><i class="bi bi-list-ol"></i> Excel — every level score</button>
              <button type="button" data-x="csv" data-key="heatmap"><i class="bi bi-filetype-csv"></i> CSV — mastery grid</button>
            </div>
          </details>
        </div>
      </div>
      <div class="mx-wrap" id="heatmap"></div>
      <div class="scale">
        <span><i style="background:var(--good)"></i>Mastered, 80% and above</span>
        <span><i style="background:var(--mid)"></i>Developing, 60–79%</span>
        <span><i style="background:var(--low)"></i>Needs encouragement, below 60%</span>
        <span><i style="background:var(--off);opacity:.5"></i>Not played</span>
      </div>
    </div>

    {{-- trend + compare --}}
    <div class="row g-3 mb-3">
      <div class="col-lg-6">
        <div class="panel h-100">
          <div class="panel-h">
            <div>
              <h2>Weekly activity</h2>
              <div class="sub">Levels completed and students who played, last 6 weeks.</div>
            </div>
            <div class="panel-acts">
              <details class="xmenu"><summary class="btn btn-xs"><i class="bi bi-box-arrow-down"></i></summary>
                <div class="xmenu-list">
                  <button type="button" data-x="sheet" data-key="trend"><i class="bi bi-table"></i> Excel — table</button>
                  <button type="button" data-x="chart" data-key="trend"><i class="bi bi-bar-chart"></i> Excel — chart image</button>
                  <button type="button" data-x="png" data-key="trend"><i class="bi bi-image"></i> PNG image</button>
                </div>
              </details>
            </div>
          </div>
          <div class="panel-b">
            <div class="chart-box" style="height:300px;"><canvas id="trendChart" role="img" aria-label="Levels completed and active students per week"></canvas></div>
            <div class="nodata" id="trendEmpty" hidden><i class="bi bi-calendar3"></i>No daily activity recorded for these students yet.</div>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="panel h-100">
          <div class="panel-h">
            <div>
              <h2>Compare groups</h2>
              <div class="sub">Average completion and mastery side by side.</div>
            </div>
            <div class="panel-acts">
              <div class="seg" id="compareToggle" role="group" aria-label="Compare by">
                <button type="button" data-by="section" class="active">Sections</button>
                <button type="button" data-by="grade">Grades</button>
              </div>
              <details class="xmenu"><summary class="btn btn-xs"><i class="bi bi-box-arrow-down"></i></summary>
                <div class="xmenu-list">
                  <button type="button" data-x="sheet" data-key="compare"><i class="bi bi-table"></i> Excel — table</button>
                  <button type="button" data-x="chart" data-key="compare"><i class="bi bi-bar-chart"></i> Excel — chart image</button>
                  <button type="button" data-x="png" data-key="compare"><i class="bi bi-image"></i> PNG image</button>
                </div>
              </details>
            </div>
          </div>
          <div class="panel-b">
            <div class="chart-box" style="height:300px;"><canvas id="compareChart" role="img" aria-label="Average completion and mastery by group"></canvas></div>
          </div>
        </div>
      </div>
    </div>

    {{-- live log --}}
    <div class="panel">
      <div class="panel-h">
        <div>
          <h2>Progress log <span class="live"><span class="pulse"></span>live</span></h2>
          <div class="sub">Latest level and best score for every student and game. <span id="lastUpdated"></span></div>
        </div>
        <div class="panel-acts">
          <div class="srch">
            <i class="bi bi-search"></i>
            <label for="progressSearch" class="visually-hidden">Search student or game</label>
            <input type="text" id="progressSearch" class="inp" placeholder="Search student or game">
          </div>
          <details class="xmenu"><summary class="btn btn-xs"><i class="bi bi-box-arrow-down"></i> Export</summary>
            <div class="xmenu-list">
              <button type="button" data-x="sheet" data-key="live"><i class="bi bi-table"></i> Excel — rows shown</button>
              <button type="button" data-x="csv" data-key="live"><i class="bi bi-filetype-csv"></i> CSV — rows shown</button>
              <div class="sep"></div>
              <a class="xlink" href="{{ route('teacher.export-progress') }}"><i class="bi bi-cloud-download"></i> CSV — full log from server</a>
            </div>
          </details>
        </div>
      </div>
      <div id="progressBody">
        <div class="nodata"><div class="sk" style="height:11px;width:40%;margin:0 auto 8px"></div><div class="sk" style="height:11px;width:26%;margin:0 auto"></div></div>
      </div>
    </div>
  </div>
</div>

<div class="dback" id="drawerBackdrop" hidden></div>
<aside class="drw" id="drawer" role="dialog" aria-modal="true" aria-labelledby="drawerTitle" hidden></aside>
<div class="toasts" id="toastStack" aria-live="polite"></div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/exceljs@4.4.0/dist/exceljs.min.js"></script>
<script>
window.addEventListener('load', function () {
  if (typeof Chart === 'undefined') {
    document.getElementById('kpiRow').innerHTML = '<div class="metric" style="grid-column:1/-1;"><div class="nodata"><i class="bi bi-wifi-off"></i>Charts could not load. Check the connection and refresh.</div></div>';
    return;
  }

  // ------------------------------------------------------------------
  // Data from the server
  // ------------------------------------------------------------------
  const DATA = {
    progress:   @json($progressData),          // { student_id: [ { game, current_level, level_scores, last_played } ] }
    students:   @json($students),              // [ { id, name, grade_level, classrooms: [{id,name}] } ]
    classrooms: @json($classrooms),
    activity:   @json($activityData ?? []),    // { student_id: [ { date, levels_completed, minutes_played, games_played } ] }
    games:      @json($games ?? [])            // [ { id, slug, name, display_name, total_levels } ]
  };

  const T = { support: 60, mastered: 80, inactiveDays: 7, weakLevelsForSupport: 3, slowdownRatio: 0.5 };

  const css = v => getComputedStyle(document.documentElement).getPropertyValue(v).trim();
  const BAND = {
    mastered:   { label: 'Mastered',            v: '--good' },
    developing: { label: 'Developing',          v: '--mid'  },
    support:    { label: 'Needs encouragement', v: '--low'  },
    none:       { label: 'Not played',          v: '--off'  }
  };
  const STATUS = {
    needs_support: { label: 'Needs encouragement', v: '--low',  order: 0 },
    inactive:      { label: 'Inactive',            v: '--mid',  order: 1 },
    slowing:       { label: 'Slowing down',        v: '--mid',  order: 2 },
    not_started:   { label: 'Not started',         v: '--off',  order: 3 },
    on_track:      { label: 'On track',            v: '--good', order: 4 }
  };
  const bandColor = k => css(BAND[k].v);
  const statusColor = k => css(STATUS[k].v);

  // ------------------------------------------------------------------
  // Helpers
  // ------------------------------------------------------------------
  const DAY = 86400000;
  const esc = s => String(s == null ? '' : s).replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
  const num = v => { const n = Number(v); return isNaN(n) ? 0 : n; };
  const clamp = (v, lo, hi) => Math.min(hi, Math.max(lo, v));
  const avg = arr => arr.length ? Math.round(arr.reduce((a, b) => a + b, 0) / arr.length) : null;
  const initials = name => String(name || '?').trim().split(/\s+/).slice(0, 2).map(w => w[0]).join('').toUpperCase();
  const bandOf = v => v == null ? 'none' : v >= T.mastered ? 'mastered' : v >= T.support ? 'developing' : 'support';
  const tint = (hex, a) => {
    const h = String(hex).replace('#', '');
    if (h.length !== 6) return hex;
    return 'rgba(' + parseInt(h.slice(0, 2), 16) + ',' + parseInt(h.slice(2, 4), 16) + ',' + parseInt(h.slice(4, 6), 16) + ',' + a + ')';
  };

  function parseDate(v) {
    if (!v) return null;
    let s = String(v);
    if (/^\d{4}-\d{2}-\d{2}$/.test(s)) s += 'T00:00:00';
    else s = s.replace(' ', 'T');
    const d = new Date(s);
    return isNaN(d.getTime()) ? null : d;
  }
  function weekStart(d) {
    const x = new Date(d);
    x.setHours(0, 0, 0, 0);
    x.setDate(x.getDate() - ((x.getDay() + 6) % 7));
    return x;
  }

  function gameKey(g)  { return String(g.id != null ? g.id : (g.slug != null ? g.slug : g.name)); }
  function gameName(g) { return g.display_name || g.name || 'Game'; }

  function levelScores(p) {
    let raw = p.level_scores != null ? p.level_scores : (p.best_scores != null ? p.best_scores : (p.scores != null ? p.scores : null));
    if (typeof raw === 'string') { try { raw = JSON.parse(raw); } catch (e) { raw = null; } }
    if (!raw) return [];
    if (Array.isArray(raw)) return raw.map((v, i) => ({ level: i + 1, score: num(v) }));
    return Object.keys(raw).map(k => ({ level: parseInt(k, 10), score: num(raw[k]) }))
      .filter(x => !isNaN(x.level)).sort((a, b) => a.level - b.level);
  }

  const catalog = (function () {
    const map = {};
    (DATA.games || []).forEach(g => { map[gameKey(g)] = { key: gameKey(g), name: gameName(g), total: num(g.total_levels) }; });
    Object.keys(DATA.progress).forEach(sid => (DATA.progress[sid] || []).forEach(p => {
      if (!p.game) return;
      const k = gameKey(p.game);
      if (!map[k]) map[k] = { key: k, name: gameName(p.game), total: num(p.game.total_levels) };
    }));
    return Object.keys(map).map(k => map[k]);
  })();

  // ------------------------------------------------------------------
  // Per-student metrics (logic unchanged)
  // ------------------------------------------------------------------
  function computeStudent(s) {
    const prog = (DATA.progress[s.id] || []).filter(p => p.game);
    const games = {};
    let lastPlayedFromProgress = null;

    prog.forEach(p => {
      const total = num(p.game.total_levels);
      const level = num(p.current_level);
      const pct = total > 0 ? clamp(Math.round(((level - 1) / total) * 100), 0, 100) : 0;
      const scores = levelScores(p);
      const played = scores.filter(x => x.score > 0);
      const mastery = played.length ? Math.round(played.reduce((a, x) => a + x.score, 0) / played.length) : null;
      const weak = played.filter(x => x.score < T.support).map(x => x.level);
      games[gameKey(p.game)] = { name: gameName(p.game), total, level, pct, mastery, weak, scores };
      const lp = parseDate(p.last_played || p.updated_at);
      if (lp && (!lastPlayedFromProgress || lp > lastPlayedFromProgress)) lastPlayedFromProgress = lp;
    });

    const playedKeys = Object.keys(games);
    const denom = playedKeys.length || 1;
    const completion = Math.round(playedKeys.reduce((a, k) => a + games[k].pct, 0) / denom);
    const masteries = Object.keys(games).map(k => games[k].mastery).filter(v => v != null);
    const mastery = avg(masteries);
    const weakCount = Object.keys(games).reduce((a, k) => a + games[k].weak.length, 0);

    const acts = (DATA.activity[s.id] || []).map(a => ({
      date: parseDate(a.date),
      levels: num(a.levels_completed),
      minutes: num(a.minutes_played),
      played: num(a.games_played)
    })).filter(a => a.date);
    const now = new Date();
    const didPlay = a => a.levels > 0 || a.minutes > 0 || a.played > 0;
    const within = (a, fromDays, toDays) => { const age = (now - a.date) / DAY; return age >= fromDays && age < toDays; };
    let lastActive = lastPlayedFromProgress;
    acts.filter(didPlay).forEach(a => { if (!lastActive || a.date > lastActive) lastActive = a.date; });
    const daysSince = lastActive ? Math.floor((now - lastActive) / DAY) : null;
    const week = acts.filter(a => within(a, 0, 7));
    const prevWeek = acts.filter(a => within(a, 7, 14));
    const activeDays7 = week.filter(didPlay).length;
    const minutes7 = week.reduce((a, x) => a + x.minutes, 0);
    const levelsThis = week.reduce((a, x) => a + x.levels, 0);
    const levelsPrev = prevWeek.reduce((a, x) => a + x.levels, 0);
    const hasActivity = acts.length > 0;
    const activeThisWeek = hasActivity ? activeDays7 > 0 : (daysSince !== null && daysSince < 7);

    let weakest = null;
    Object.keys(games).forEach(k => {
      const g = games[k];
      if (g.mastery != null && (!weakest || g.mastery < weakest.mastery)) weakest = g;
    });

    const avgBand = bandOf(mastery);
    let overallRemark = BAND[avgBand].label;
    if (prog.length === 0) overallRemark = 'Not started';
    else if (daysSince !== null && daysSince >= T.inactiveDays) overallRemark = 'Inactive';

    const lowGames = Object.keys(games).filter(k => games[k].mastery !== null && games[k].mastery < T.support).map(k => games[k]);
    const lowGamesCount = lowGames.length;

    let status = 'on_track', reason = overallRemark + (mastery != null ? ' · ' + mastery + '% avg' : '');
    if (prog.length === 0) {
      status = 'not_started'; reason = 'Has not played any game yet';
    } else if (daysSince !== null && daysSince >= T.inactiveDays) {
      status = 'inactive'; reason = 'No play for ' + daysSince + ' days';
    } else if (mastery !== null && mastery < T.support) {
      status = 'needs_support';
      reason = 'Low average: ' + mastery + '% (needs encouragement)';
    } else if (hasActivity && levelsPrev >= 2 && levelsThis < levelsPrev * T.slowdownRatio) {
      status = 'slowing'; reason = levelsThis + ' levels this week, ' + levelsPrev + ' last week';
    } else {
      status = 'on_track';
      if (lowGamesCount > 0) {
        reason = mastery + '% avg · ' + lowGamesCount + ' game' + (lowGamesCount > 1 ? 's' : '') + ' need encouragement';
      } else {
        reason = overallRemark + ' · ' + mastery + '% average';
      }
    }

    return { games, completion, mastery, weakCount, weakest, lowGames, lowGamesCount, overallRemark, avgBand, daysSince, activeDays7, minutes7, levelsThis, levelsPrev,
             hasActivity, activeThisWeek, acts, status, reason, lastActive };
  }

  const model = DATA.students.map(s => ({ s, m: computeStudent(s) }));

  // ------------------------------------------------------------------
  // Scope
  // ------------------------------------------------------------------
  const state = { grade: '', section: '', q: '', status: '', compareBy: 'section' };
  const gradeSelect = document.getElementById('gradeSelect');
  const sectionSelect = document.getElementById('sectionSelect');
  const studentSearch = document.getElementById('studentSearch');

  function matches(x, useGrade, useSection) {
    if (useGrade && state.grade && String(x.s.grade_level) !== state.grade) return false;
    if (useSection && state.section && !(x.s.classrooms || []).some(c => String(c.id) === state.section)) return false;
    if (state.q && String(x.s.name || '').toLowerCase().indexOf(state.q) === -1) return false;
    return true;
  }
  const scoped = () => model.filter(x => matches(x, true, true));

  // ------------------------------------------------------------------
  // Chart defaults — quiet grid, thin bars, small type
  // ------------------------------------------------------------------
  Chart.defaults.font.family = getComputedStyle(document.body).fontFamily;
  Chart.defaults.font.size = 11;
  Chart.defaults.animation.duration = 260;

  const charts = {};
  function drawChart(id, config) {
    if (charts[id]) charts[id].destroy();
    charts[id] = new Chart(document.getElementById(id).getContext('2d'), config);
  }
  const grid = () => ({ color: css('--line-soft'), drawTicks: false, drawBorder: false });
  const ticks = extra => Object.assign({ color: css('--ink-3'), padding: 8, font: { size: 11 } }, extra || {});
  function legendOpts() {
    return { position: 'top', align: 'end', labels: { color: css('--ink-2'), usePointStyle: true, pointStyle: 'rectRounded', boxWidth: 8, boxHeight: 8, padding: 16, font: { size: 11.5 } } };
  }
  function tooltipOpts(suffix) {
    return {
      backgroundColor: css('--surface-2'), borderColor: css('--line'), borderWidth: 1,
      titleColor: css('--ink'), bodyColor: css('--ink-2'), padding: 10, cornerRadius: 7,
      displayColors: true, boxWidth: 8, boxHeight: 8, usePointStyle: true,
      callbacks: suffix ? { label: c => ' ' + c.dataset.label + '  ' + c.formattedValue + suffix } : undefined
    };
  }

  // ------------------------------------------------------------------
  // Renderers
  // ------------------------------------------------------------------
  function renderScope(list) {
    const bits = [];
    if (state.grade) bits.push('Grade ' + state.grade);
    if (state.section) { const c = DATA.classrooms.find(c => String(c.id) === state.section); if (c) bits.push(c.name); }
    if (state.q) bits.push('"' + state.q + '"');
    if (state.status) bits.push(STATUS[state.status].label);
    document.getElementById('scopeLabel').innerHTML =
      '<b>' + list.length + '</b> student' + (list.length === 1 ? '' : 's') + (bits.length ? ' · ' + esc(bits.join(' · ')) : '');
  }

  function renderKPIs(list) {
    const n = list.length;
    const completion = avg(list.map(x => x.m.completion));
    const mastery = avg(list.map(x => x.m.mastery).filter(v => v != null));
    const active = list.filter(x => x.m.activeThisWeek).length;
    const flagged = list.filter(x => x.m.status !== 'on_track').length;
    const mBand = BAND[bandOf(mastery)];

    const cell = (label, value, unit, sub, color, fill) =>
      '<div class="metric">' +
        '<div class="metric-l">' + label + '</div>' +
        '<div class="metric-v">' + value + (unit ? '<small>' + unit + '</small>' : '') + '</div>' +
        '<div class="metric-s">' + sub + '</div>' +
        '<div class="metric-bar"><i style="width:' + clamp(fill, 0, 100) + '%;background:' + color + ';"></i></div>' +
      '</div>';

    document.getElementById('kpiRow').innerHTML =
      cell('Game progress', completion == null ? '—' : completion, completion == null ? '' : '%',
           'Average across ' + (list.length ? avg(list.map(x => Object.keys(x.m.games).length)) : 0) + ' games played', css('--d1'), completion || 0) +
      cell('Average mastery', mastery == null ? '—' : mastery, mastery == null ? '' : '%',
           mastery == null ? 'No scores recorded' : mBand.label, bandColor(bandOf(mastery)), mastery || 0) +
      cell('Played this week', active, ' / ' + n, 'Active in the last 7 days', css('--d2'), n ? (active / n) * 100 : 0) +
      cell('Needs attention', flagged, n ? ' / ' + n : '',
           flagged === 0 ? 'Everyone on track' : 'Flagged by status', flagged ? css('--low') : css('--good'), n ? (flagged / n) * 100 : 0);
  }

  function smark(status) {
    const st = STATUS[status];
    return '<span class="smark"><i class="sq" style="background:' + statusColor(status) + ';"></i>' + st.label + '</span>';
  }

  function renderAttention(list) {
    const counts = {};
    list.forEach(x => { counts[x.m.status] = (counts[x.m.status] || 0) + 1; });
    document.getElementById('statusChips').innerHTML = Object.keys(STATUS).filter(k => counts[k]).map(k =>
      '<button type="button" class="fchip" data-status="' + k + '" aria-pressed="' + (state.status === k) + '">' +
      '<i class="sq" style="background:' + statusColor(k) + ';"></i>' + STATUS[k].label + ' <b>' + counts[k] + '</b></button>').join('');

    const flagged = list.filter(x => state.status ? x.m.status === state.status : x.m.status !== 'on_track')
      .sort((a, b) => STATUS[a.m.status].order - STATUS[b.m.status].order || a.s.name.localeCompare(b.s.name));
    const el = document.getElementById('attentionList');
    if (list.length === 0) { el.innerHTML = '<div class="nodata"><i class="bi bi-funnel"></i>No students match these filters.</div>'; return; }
    if (flagged.length === 0) {
      el.innerHTML = '<div class="nodata"><i class="bi bi-check2-circle"></i>' +
        (state.status ? 'No students hold this status right now.' : 'Every student in this view is on track.') + '</div>';
      return;
    }
    el.innerHTML = flagged.map(x =>
      '<button type="button" class="arow" data-sid="' + esc(x.s.id) + '">' +
      '<span class="ini">' + esc(initials(x.s.name)) + '</span>' +
      '<span style="min-width:0;overflow:hidden;"><span class="aname">' + esc(x.s.name) + '</span>' +
      '<span class="areason" title="' + esc(x.m.reason) + '">' + esc(x.m.reason) + '</span></span>' +
      smark(x.m.status) + '<i class="bi bi-chevron-right chev"></i></button>').join('');
  }

  function renderTopics(list) {
    const labels = catalog.map(g => g.name);
    const completion = catalog.map(g => avg(list.map(x => x.m.games[g.key] ? x.m.games[g.key].pct : 0)) || 0);
    const mastery = catalog.map(g => avg(list.map(x => x.m.games[g.key] ? x.m.games[g.key].mastery : null).filter(v => v != null)) || 0);
    drawChart('topicChart', {
      type: 'bar',
      data: { labels, datasets: [
        { label: 'Completion', data: completion, backgroundColor: css('--d1'), borderRadius: 2, barPercentage: .74, categoryPercentage: .66 },
        { label: 'Mastery', data: mastery, backgroundColor: css('--d2'), borderRadius: 2, barPercentage: .74, categoryPercentage: .66 }
      ] },
      options: {
        indexAxis: 'y', responsive: true, maintainAspectRatio: false,
        layout: { padding: { top: 4 } },
        plugins: { legend: legendOpts(), tooltip: tooltipOpts('%') },
        scales: {
          x: { beginAtZero: true, max: 100, grid: grid(), border: { display: false }, ticks: ticks({ callback: v => v + '%', stepSize: 25 }) },
          y: { grid: { display: false }, border: { display: false }, ticks: ticks({ color: css('--ink-2'), crossAlign: 'far' }) }
        }
      }
    });
  }

  function renderHeatmap(list) {
    const el = document.getElementById('heatmap');
    if (list.length === 0) { el.innerHTML = '<div class="nodata"><i class="bi bi-funnel"></i>No students match these filters.</div>'; return; }
    const rows = list.slice().sort((a, b) => STATUS[a.m.status].order - STATUS[b.m.status].order || a.s.name.localeCompare(b.s.name));

    const cell = (val, sub, title, isGame) => {
      if (val == null) return '<td><span class="cell empty" title="' + esc(title) + '">—' + (sub ? '<span class="cells">' + sub + '</span>' : '') + '</span></td>';
      const k = bandOf(val), c = bandColor(k);
      const isLow = val < T.support;
      let badge = '';
      if (isGame && isLow) {
        badge = '<span class="cells" style="color:var(--low);font-weight:600;font-size:9.5px;letter-spacing:-.2px;margin-top:2px;">Needs encouragement</span>';
      }
      return '<td><span class="cell" style="color:' + c + ';background:' + tint(c, .10) + ';" title="' + esc(title) + '">' +
             '<span class="cellv" style="color:' + c + ';">' + val + '%</span>' +
             (sub ? '<span class="cells">' + sub + '</span>' : '') +
             badge +
             '</span></td>';
    };

    let html = '<table class="mx"><thead><tr><th class="c-name">Student</th><th>Overall</th>' +
      catalog.map(g => '<th>' + esc(g.name) + '</th>').join('') + '</tr></thead><tbody>';
    rows.forEach(x => {
      html += '<tr><td class="c-name"><button type="button" class="stu" data-sid="' + esc(x.s.id) + '">' +
        '<span class="ini">' + esc(initials(x.s.name)) + '</span>' +
        '<span><span class="nm">' + esc(x.s.name) + '</span>' +
        '<span class="meta">Grade ' + esc(x.s.grade_level || '—') + ' · ' + esc(x.m.overallRemark) + '</span></span></button></td>';
      html += cell(x.m.mastery, x.m.completion + '% done', 'Overall mastery: ' + (x.m.mastery != null ? x.m.mastery + '% (' + x.m.overallRemark + ')' : 'No scores'), false);
      catalog.forEach(g => {
        const gm = x.m.games[g.key];
        const sub = gm ? 'Lvl ' + gm.level + '/' + gm.total : '';
        const isLow = gm && gm.mastery != null && gm.mastery < T.support;
        const gRemark = isLow ? 'Needs encouragement' : (gm && gm.mastery != null ? BAND[bandOf(gm.mastery)].label : 'not played');
        html += cell(gm && gm.mastery != null ? gm.mastery : null, sub,
          g.name + (gm && gm.mastery != null ? ': ' + gm.mastery + '% — ' + gRemark : ': not played'),
          true);
      });
      html += '</tr>';
    });
    el.innerHTML = html + '</tbody></table>';
  }

  let trendBuckets = [];
  function renderTrend(list) {
    const now = new Date();
    const first = weekStart(new Date(now.getTime() - 5 * 7 * DAY));
    const buckets = [];
    for (let i = 0; i < 6; i++) {
      const d = new Date(first.getTime() + i * 7 * DAY);
      buckets.push({ label: d.toLocaleDateString(undefined, { month: 'short', day: 'numeric' }), levels: 0, students: new Set() });
    }
    let any = false;
    list.forEach(x => x.m.acts.forEach(a => {
      const idx = Math.round((weekStart(a.date) - first) / (7 * DAY));
      if (idx < 0 || idx > 5) return;
      any = true;
      buckets[idx].levels += a.levels;
      if (a.levels > 0 || a.minutes > 0 || a.played > 0) buckets[idx].students.add(x.s.id);
    }));
    trendBuckets = buckets;
    document.getElementById('trendChart').parentElement.hidden = !any;
    document.getElementById('trendEmpty').hidden = any;
    if (!any) { if (charts.trendChart) { charts.trendChart.destroy(); delete charts.trendChart; } return; }
    drawChart('trendChart', {
      type: 'bar',
      data: { labels: buckets.map(b => b.label), datasets: [
        { type: 'bar', label: 'Levels completed', data: buckets.map(b => b.levels), backgroundColor: tint(css('--d1'), .85), borderRadius: 2, barPercentage: .5, categoryPercentage: .8, yAxisID: 'y', order: 2 },
        { type: 'line', label: 'Students who played', data: buckets.map(b => b.students.size), borderColor: css('--d2'), backgroundColor: css('--d2'), borderWidth: 1.75, tension: .32, pointRadius: 2.5, pointHoverRadius: 4, yAxisID: 'y2', order: 1 }
      ] },
      options: {
        responsive: true, maintainAspectRatio: false,
        interaction: { mode: 'index', intersect: false },
        plugins: { legend: legendOpts(), tooltip: tooltipOpts('') },
        scales: {
          x: { grid: { display: false }, border: { color: css('--line') }, ticks: ticks() },
          y: { beginAtZero: true, grid: grid(), border: { display: false }, ticks: ticks({ precision: 0 }), title: { display: true, text: 'Levels', color: css('--ink-3'), font: { size: 11 } } },
          y2: { beginAtZero: true, position: 'right', grid: { display: false }, border: { display: false }, ticks: ticks({ precision: 0 }), title: { display: true, text: 'Students', color: css('--ink-3'), font: { size: 11 } } }
        }
      }
    });
  }

  let compareGroups = [];
  function renderCompare() {
    let groups = [];
    if (state.compareBy === 'section') {
      groups = DATA.classrooms.map(c => ({ name: c.name, list: model.filter(x => matches(x, true, false) && (x.s.classrooms || []).some(k => String(k.id) === String(c.id))) }));
    } else {
      groups = ['4', '5', '6'].map(g => ({ name: 'Grade ' + g, list: model.filter(x => matches(x, false, true) && String(x.s.grade_level) === g) }));
    }
    groups = groups.filter(g => g.list.length > 0);
    compareGroups = groups;
    drawChart('compareChart', {
      type: 'bar',
      data: { labels: groups.map(g => g.name), datasets: [
        { label: 'Completion', data: groups.map(g => avg(g.list.map(x => x.m.completion)) || 0), backgroundColor: css('--d1'), borderRadius: 2, barPercentage: .7, categoryPercentage: .62 },
        { label: 'Mastery', data: groups.map(g => avg(g.list.map(x => x.m.mastery).filter(v => v != null)) || 0), backgroundColor: css('--d2'), borderRadius: 2, barPercentage: .7, categoryPercentage: .62 }
      ] },
      options: {
        responsive: true, maintainAspectRatio: false,
        plugins: {
          legend: legendOpts(),
          tooltip: Object.assign(tooltipOpts('%'), {
            callbacks: {
              title: items => { const g = groups[items[0].dataIndex]; return g.name + ' · ' + g.list.length + ' student' + (g.list.length === 1 ? '' : 's'); },
              label: c => ' ' + c.dataset.label + '  ' + c.formattedValue + '%'
            }
          })
        },
        scales: {
          x: { grid: { display: false }, border: { color: css('--line') }, ticks: ticks({ color: css('--ink-2') }) },
          y: { beginAtZero: true, max: 100, grid: grid(), border: { display: false }, ticks: ticks({ callback: v => v + '%', stepSize: 25 }) }
        }
      }
    });
  }

  function renderAll() {
    const list = scoped();
    renderScope(list);
    renderKPIs(list);
    renderAttention(list);
    renderTopics(list);
    renderHeatmap(list);
    renderTrend(list);
    renderCompare();
  }

  // ------------------------------------------------------------------
  // Drawer
  // ------------------------------------------------------------------
  const drawer = document.getElementById('drawer');
  const backdrop = document.getElementById('drawerBackdrop');
  let lastTrigger = null;

  // Builds detailed explanation HTML for the student status
  function buildStatusExplanation(m) {
    const sc = bandColor(m.avgBand);
    let h = '<div class="status-explain">';
    h += '<div class="se-title"><i style="background:' + sc + ';"></i> Overall status: ' + m.overallRemark + (m.mastery != null ? ' (' + m.mastery + '% average)' : '') + '</div>';

    if (m.status === 'needs_support') {
      h += '<div class="se-rule">This student is marked as <b>Needs encouragement</b> because their overall average score is <b>' + (m.mastery != null ? m.mastery + '%' : 'below 60%') + '</b> (below the ' + T.support + '% threshold).</div>';
    } else if (m.status === 'inactive') {
      h += '<div class="se-rule">This student is flagged because:</div>';
      h += '<div class="se-crit"><span class="chk fail">✗</span>';
      h += '<span>No play activity for <b>' + m.daysSince + ' day' + (m.daysSince !== 1 ? 's' : '') + '</b> ';
      h += '(threshold: ' + T.inactiveDays + ' days without playing)</span></div>';
      h += '<div class="se-note">💡 The student may need a reminder or encouragement to resume playing.</div>';
    } else if (m.status === 'slowing') {
      h += '<div class="se-rule">This student is flagged because:</div>';
      h += '<div class="se-crit"><span class="chk fail">✗</span>';
      h += '<span>Completed only <b>' + m.levelsThis + ' level' + (m.levelsThis !== 1 ? 's' : '') + '</b> this week vs <b>' + m.levelsPrev + '</b> last week ';
      h += '(dropped by more than ' + Math.round((1 - T.slowdownRatio) * 100) + '%)</span></div>';
    } else if (m.status === 'not_started') {
      h += '<div class="se-rule">This student has <b>not played any game</b> yet.</div>';
      h += '<div class="se-note">💡 The student may need guidance on how to access and start the games.</div>';
    } else {
      h += '<div class="se-rule">This student\'s overall progress is based on their average mastery: <b>' + (m.mastery != null ? m.mastery + '%' : 'N/A') + ' (' + m.overallRemark + ')</b>.</div>';
      h += '<div class="se-crit"><span class="chk pass">✓</span><span>Overall average meets or exceeds expectations.</span></div>';
    }

    // Game-level remarks for low score games
    if (m.lowGames && m.lowGames.length > 0) {
      h += '<div class="se-note" style="border-top:1px solid var(--line-soft);margin-top:10px;padding-top:8px;">' +
           '<b>⚠️ Game-level remarks:</b> The following ' + m.lowGames.length + ' game' + (m.lowGames.length > 1 ? 's have' : ' has') + ' low scores and need encouragement:' +
           '<ul style="margin:6px 0 0;padding-left:18px;">';
      m.lowGames.forEach(function(g) {
        h += '<li><b>' + esc(g.name) + '</b>: ' + g.mastery + '% avg (Needs encouragement' + (g.weak && g.weak.length ? ' · low score on level' + (g.weak.length > 1 ? 's ' : ' ') + g.weak.join(', ') : '') + ')</li>';
      });
      h += '</ul></div>';
    }

    h += '</div>';
    return h;
  }

  function openDrawer(sid, trigger) {
    const x = model.find(r => String(r.s.id) === String(sid));
    if (!x) return;
    lastTrigger = trigger || null;
    const m = x.m;
    const rooms = (x.s.classrooms || []).map(c => c.name).join(', ') || 'No section';

    // Build the detailed status explanation
    var explainHtml = buildStatusExplanation(m);

    let html = '<div class="drw-h">' +
      '<div><h2 id="drawerTitle">' + esc(x.s.name) + '</h2>' +
      '<div class="meta">Grade ' + esc(x.s.grade_level || '—') + ' · ' + esc(rooms) + '</div>' +
      '<div style="margin-top:8px;"><span class="smark"><i class="sq" style="background:' + bandColor(m.avgBand) + ';"></i>' + esc(m.overallRemark) + (m.mastery != null ? ' (' + m.mastery + '% avg)' : '') + '</span></div></div>' +
      '<button type="button" class="xclose" id="drawerClose" aria-label="Close">✕</button></div>' +
      '<div class="drw-b">' +
      explainHtml +
      '<div class="kv">' +
        '<div><b>' + m.completion + '%</b><span>Game progress</span></div>' +
        '<div><b>' + (m.mastery == null ? '—' : m.mastery + '%') + '</b><span>Average mastery</span></div>' +
        '<div><b>' + (m.hasActivity ? m.activeDays7 : '—') + '</b><span>Active days, last 7</span></div>' +
        '<div><b>' + (m.hasActivity ? m.levelsThis + ' / ' + m.levelsPrev : '—') + '</b><span>Levels this week / last</span></div>' +
      '</div>';
    if (m.hasActivity) html += '<div class="meta">' + m.minutes7 + ' minutes played in the last 7 days.</div>';

    html += '<div style="display:flex;align-items:center;justify-content:space-between;gap:10px;margin:20px 0 4px;">' +
            '<h3 style="font-size:13px;font-weight:600;margin:0;">Scores by level</h3>' +
            '<button type="button" class="btn btn-xs" data-x="student" data-sid="' + esc(x.s.id) + '"><i class="bi bi-box-arrow-down"></i> Export student</button></div>' +
            '<div class="meta">One square per level. Outlined squares scored below ' + T.support + '%.</div>';

    catalog.forEach(g => {
      const gm = m.games[g.key];
      html += '<div class="gblock"><div class="hd" style="display:flex;align-items:center;justify-content:space-between;gap:8px;">' +
              '<strong>' + esc(g.name) + '</strong>';
      if (!gm) {
        html += '<span class="meta" style="color:var(--off);">Not played</span></div></div>';
        return;
      }

      const gBand = gm.mastery != null ? bandOf(gm.mastery) : 'none';
      const gColor = bandColor(gBand);
      const isLow = gm.mastery !== null && gm.mastery < T.support;
      const gRemark = isLow ? 'Needs encouragement' : BAND[gBand].label;

      html += '<span style="display:inline-flex;align-items:center;gap:6px;font-size:12px;font-weight:600;color:' + gColor + ';">' +
              '<i style="width:6px;height:6px;border-radius:2px;background:' + gColor + ';display:inline-block;"></i>' +
              gRemark + (gm.mastery != null ? ' (' + gm.mastery + '%)' : '') + ' · Lvl ' + gm.level + '/' + gm.total + '</span></div>';

      const byLevel = {};
      gm.scores.forEach(sc => { byLevel[sc.level] = sc.score; });
      const total = gm.total || gm.scores.length;
      let strip = '';
      for (let l = 1; l <= total; l++) {
        const sc = byLevel[l];
        const c = sc > 0 ? bandColor(bandOf(sc)) : css('--off');
        strip += '<i class="' + (sc > 0 && sc < T.support ? 'weak' : '') + '" style="background:' + (sc > 0 ? c : tint(c, .28)) + ';" title="Level ' + l + ': ' + (sc > 0 ? sc + '%' : 'not played') + '"></i>';
      }
      html += '<div class="lvls">' + strip + '</div>';

      if (isLow) {
        html += '<div class="meta" style="color:var(--low);margin-top:7px;font-weight:600;">⚠️ Low score (' + gm.mastery + '%) — this game needs encouragement & targeted practice.</div>';
      }
      if (gm.weak.length) {
        html += '<div class="meta" style="color:var(--low);margin-top:4px;">Revisit levels: ' + gm.weak.join(', ') + '</div>';
      }
      html += '</div>';
    });
    html += '</div>';

    drawer.innerHTML = html;
    drawer.hidden = false;
    backdrop.hidden = false;
    document.body.style.overflow = 'hidden';
    document.getElementById('drawerClose').focus();
  }
  function closeDrawer() {
    drawer.hidden = true;
    backdrop.hidden = true;
    document.body.style.overflow = '';
    if (lastTrigger && document.contains(lastTrigger)) lastTrigger.focus();
  }

  document.addEventListener('click', e => {
    const btn = e.target.closest('[data-sid]');
    if (btn && (btn.classList.contains('arow') || btn.classList.contains('stu'))) { openDrawer(btn.dataset.sid, btn); return; }
    if (e.target.closest('#drawerClose') || e.target === backdrop) closeDrawer();
  });
  document.addEventListener('keydown', e => { if (e.key === 'Escape' && !drawer.hidden) closeDrawer(); });

  // ------------------------------------------------------------------
  // Controls
  // ------------------------------------------------------------------
  let searchTimer = null;
  gradeSelect.addEventListener('change', () => { state.grade = gradeSelect.value; renderAll(); });
  sectionSelect.addEventListener('change', () => { state.section = sectionSelect.value; renderAll(); });
  studentSearch.addEventListener('input', () => {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => { state.q = studentSearch.value.trim().toLowerCase(); renderAll(); }, 180);
  });
  document.getElementById('resetFilters').addEventListener('click', () => {
    state.grade = ''; state.section = ''; state.q = ''; state.status = '';
    gradeSelect.value = ''; sectionSelect.value = ''; studentSearch.value = '';
    renderAll();
  });
  document.getElementById('statusChips').addEventListener('click', e => {
    const b = e.target.closest('[data-status]');
    if (!b) return;
    state.status = state.status === b.dataset.status ? '' : b.dataset.status;
    renderAll();
  });
  document.getElementById('compareToggle').addEventListener('click', e => {
    const b = e.target.closest('button[data-by]');
    if (!b) return;
    state.compareBy = b.dataset.by;
    document.querySelectorAll('#compareToggle button').forEach(x => x.classList.toggle('active', x === b));
    renderCompare();
  });
  document.addEventListener('click', e => {
    document.querySelectorAll('details.xmenu[open]').forEach(d => { if (!d.contains(e.target)) d.open = false; });
  });

  new MutationObserver(renderAll).observe(document.documentElement, { attributes: true, attributeFilter: ['data-bs-theme'] });

  renderAll();

  // ==================================================================
  // EXPORTS
  // ==================================================================
  function toast(msg, kind) {
    const el = document.createElement('div');
    el.className = 'toast-i ' + (kind || 'ok');
    el.innerHTML = '<i class="bi ' + (kind === 'err' ? 'bi-exclamation-triangle' : 'bi-check2') + '"></i><span>' + esc(msg) + '</span>';
    document.getElementById('toastStack').appendChild(el);
    setTimeout(() => el.remove(), 3600);
  }
  const stamp = () => new Date().toISOString().slice(0, 10);
  function scopeName() {
    const bits = [];
    if (state.grade) bits.push('grade' + state.grade);
    if (state.section) { const c = DATA.classrooms.find(c => String(c.id) === state.section); if (c) bits.push(String(c.name).replace(/\s+/g, '-')); }
    return bits.length ? '_' + bits.join('_') : '';
  }
  const fileName = base => base + scopeName() + '_' + stamp();

  function saveBlob(blob, name) {
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url; a.download = name;
    document.body.appendChild(a); a.click(); a.remove();
    setTimeout(() => URL.revokeObjectURL(url), 1500);
  }
  const fmtDate = d => d ? d.toISOString().slice(0, 10) : '';

  const SHEETS = {
    summary() {
      const list = scoped();
      const completion = avg(list.map(x => x.m.completion));
      const mastery = avg(list.map(x => x.m.mastery).filter(v => v != null));
      const active = list.filter(x => x.m.activeThisWeek).length;
      const counts = {};
      list.forEach(x => { counts[x.m.status] = (counts[x.m.status] || 0) + 1; });
      const rows = [
        ['Teacher dashboard summary'],
        ['Generated', new Date().toLocaleString()],
        ['Grade filter', state.grade ? 'Grade ' + state.grade : 'All grades'],
        ['Section filter', state.section ? (DATA.classrooms.find(c => String(c.id) === state.section) || {}).name || '' : 'All sections'],
        ['Name search', state.q || '(none)'],
        [],
        ['Measure', 'Value'],
        ['Students in view', list.length],
        ['Average curriculum completed (%)', completion == null ? '' : completion],
        ['Average mastery (%)', mastery == null ? '' : mastery],
        ['Played in the last 7 days', active],
        ['Games in the curriculum', catalog.length],
        [],
        ['Status', 'Students']
      ];
      Object.keys(STATUS).forEach(k => rows.push([STATUS[k].label, counts[k] || 0]));
      return { name: 'Summary', rows, title: 'Summary' };
    },
    students() {
      const rows = [['Student', 'Grade', 'Sections', 'Status', 'Reason', 'Curriculum completed (%)', 'Average mastery (%)',
                     'Weak levels', 'Days since last play', 'Active days (last 7)', 'Minutes (last 7)', 'Levels this week', 'Levels last week', 'Last active']];
      scoped().slice().sort((a, b) => a.s.name.localeCompare(b.s.name)).forEach(x => rows.push([
        x.s.name, x.s.grade_level || '', (x.s.classrooms || []).map(c => c.name).join(', '),
        STATUS[x.m.status].label, x.m.reason, x.m.completion, x.m.mastery == null ? '' : x.m.mastery,
        x.m.weakCount, x.m.daysSince == null ? '' : x.m.daysSince, x.m.hasActivity ? x.m.activeDays7 : '',
        x.m.hasActivity ? x.m.minutes7 : '', x.m.hasActivity ? x.m.levelsThis : '', x.m.hasActivity ? x.m.levelsPrev : '',
        fmtDate(x.m.lastActive)
      ]));
      return { name: 'Students', rows, title: 'Student summary' };
    },
    attention() {
      const rows = [['Student', 'Grade', 'Sections', 'Status', 'Reason', 'Average mastery (%)', 'Weak levels', 'Days since last play']];
      scoped().filter(x => state.status ? x.m.status === state.status : x.m.status !== 'on_track')
        .sort((a, b) => STATUS[a.m.status].order - STATUS[b.m.status].order || a.s.name.localeCompare(b.s.name))
        .forEach(x => rows.push([x.s.name, x.s.grade_level || '', (x.s.classrooms || []).map(c => c.name).join(', '),
          STATUS[x.m.status].label, x.m.reason, x.m.mastery == null ? '' : x.m.mastery, x.m.weakCount,
          x.m.daysSince == null ? '' : x.m.daysSince]));
      return { name: 'Needs attention', rows, title: 'Students who need attention' };
    },
    topics() {
      const list = scoped();
      const rows = [['Game', 'Average completion (%)', 'Average mastery (%)', 'Students who played', 'Students in view', 'Total levels']];
      catalog.forEach(g => {
        const played = list.filter(x => x.m.games[g.key] && x.m.games[g.key].mastery != null).length;
        rows.push([g.name,
          avg(list.map(x => x.m.games[g.key] ? x.m.games[g.key].pct : 0)) || 0,
          avg(list.map(x => x.m.games[g.key] ? x.m.games[g.key].mastery : null).filter(v => v != null)) || 0,
          played, list.length, g.total]);
      });
      return { name: 'Progress by topic', rows, title: 'Progress by topic', chart: 'topicChart' };
    },
    heatmap() {
      const head = ['Student', 'Grade', 'Sections', 'Status', 'Overall mastery (%)', 'Overall completion (%)'];
      catalog.forEach(g => { head.push(g.name + ' mastery (%)'); head.push(g.name + ' level'); });
      const rows = [head];
      scoped().slice().sort((a, b) => STATUS[a.m.status].order - STATUS[b.m.status].order || a.s.name.localeCompare(b.s.name)).forEach(x => {
        const r = [x.s.name, x.s.grade_level || '', (x.s.classrooms || []).map(c => c.name).join(', '), STATUS[x.m.status].label,
                   x.m.mastery == null ? '' : x.m.mastery, x.m.completion];
        catalog.forEach(g => {
          const gm = x.m.games[g.key];
          r.push(!gm || gm.mastery == null ? '' : gm.mastery);
          r.push(gm ? gm.level + ' / ' + gm.total : '');
        });
        rows.push(r);
      });
      return { name: 'Mastery grid', rows, title: 'Mastery by student and topic' };
    },
    levels() {
      const rows = [['Student', 'Grade', 'Game', 'Level', 'Best score (%)', 'Band']];
      scoped().slice().sort((a, b) => a.s.name.localeCompare(b.s.name)).forEach(x => {
        catalog.forEach(g => {
          const gm = x.m.games[g.key];
          if (!gm) return;
          const byLevel = {};
          gm.scores.forEach(sc => { byLevel[sc.level] = sc.score; });
          const total = gm.total || gm.scores.length;
          for (let l = 1; l <= total; l++) {
            const sc = byLevel[l];
            rows.push([x.s.name, x.s.grade_level || '', g.name, l, sc > 0 ? sc : '', sc > 0 ? BAND[bandOf(sc)].label : 'Not played']);
          }
        });
      });
      return { name: 'Level scores', rows, title: 'Score for every level' };
    },
    trend() {
      const rows = [['Week starting', 'Levels completed', 'Students who played']];
      trendBuckets.forEach(b => rows.push([b.label, b.levels, b.students.size]));
      return { name: 'Weekly activity', rows, title: 'Weekly activity', chart: 'trendChart' };
    },
    compare() {
      const rows = [[state.compareBy === 'section' ? 'Section' : 'Grade', 'Students', 'Average completion (%)', 'Average mastery (%)']];
      compareGroups.forEach(g => rows.push([g.name, g.list.length,
        avg(g.list.map(x => x.m.completion)) || 0,
        avg(g.list.map(x => x.m.mastery).filter(v => v != null)) || 0]));
      return { name: 'Group comparison', rows, title: 'Compare groups', chart: 'compareChart' };
    },
    live() {
      const rows = [['Student', 'Classroom', 'Game', 'Level', 'Total levels', 'Progress (%)', 'Average score', 'Last played']];
      (window.TD_LIVE_ROWS || []).forEach(r => {
        const pct = r.total_levels > 0 ? clamp(Math.round(((r.level - 1) / r.total_levels) * 100), 0, 100) : 0;
        rows.push([r.student_name, r.classroom, r.game || 'Not started', r.game ? r.level : '', r.game ? r.total_levels : '',
                   r.game ? pct : '', r.game ? Number(r.avg_score || 0) : '', r.last_played || '']);
      });
      return { name: 'Progress log', rows, title: 'Progress log' };
    },
    student(sid) {
      const x = model.find(r => String(r.s.id) === String(sid));
      if (!x) return { name: 'Student', rows: [['No data']], title: 'Student' };
      const rows = [
        [x.s.name], ['Grade', x.s.grade_level || ''], ['Sections', (x.s.classrooms || []).map(c => c.name).join(', ')],
        ['Status', STATUS[x.m.status].label], ['Reason', x.m.reason],
        ['Curriculum completed (%)', x.m.completion], ['Average mastery (%)', x.m.mastery == null ? '' : x.m.mastery],
        ['Active days (last 7)', x.m.hasActivity ? x.m.activeDays7 : ''], ['Minutes (last 7)', x.m.hasActivity ? x.m.minutes7 : ''],
        ['Levels this week', x.m.hasActivity ? x.m.levelsThis : ''], ['Levels last week', x.m.hasActivity ? x.m.levelsPrev : ''],
        [], ['Game', 'Level', 'Total levels', 'Average mastery (%)', 'Weak levels']
      ];
      catalog.forEach(g => {
        const gm = x.m.games[g.key];
        rows.push([g.name, gm ? gm.level : '', gm ? gm.total : g.total, gm && gm.mastery != null ? gm.mastery : '', gm ? gm.weak.join(', ') : '']);
      });
      rows.push([], ['Game', 'Level', 'Best score (%)']);
      catalog.forEach(g => {
        const gm = x.m.games[g.key];
        if (!gm) return;
        const byLevel = {};
        gm.scores.forEach(sc => { byLevel[sc.level] = sc.score; });
        const total = gm.total || gm.scores.length;
        for (let l = 1; l <= total; l++) rows.push([g.name, l, byLevel[l] > 0 ? byLevel[l] : '']);
      });
      return { name: String(x.s.name).slice(0, 28), rows, title: x.s.name };
    }
  };

  function colWidths(rows) {
    const w = [];
    rows.forEach(r => (r || []).forEach((c, i) => { w[i] = Math.max(w[i] || 10, Math.min(42, String(c == null ? '' : c).length + 3)); }));
    return w.map(x => ({ wch: x }));
  }

  function writeWorkbook(sheets, name) {
    if (typeof XLSX === 'undefined') { toast('The Excel library could not load. Check the connection.', 'err'); return false; }
    const wb = XLSX.utils.book_new();
    const used = {};
    sheets.forEach(s => {
      let sheetName = String(s.name).replace(/[\\\/\?\*\[\]:]/g, ' ').slice(0, 31) || 'Sheet';
      if (used[sheetName]) { sheetName = sheetName.slice(0, 28) + ' ' + (++used[sheetName]); } else { used[sheetName] = 1; }
      const ws = XLSX.utils.aoa_to_sheet(s.rows);
      ws['!cols'] = colWidths(s.rows);
      ws['!freeze'] = { xSplit: 0, ySplit: 1 };
      XLSX.utils.book_append_sheet(wb, ws, sheetName);
    });
    XLSX.writeFile(wb, name + '.xlsx');
    return true;
  }

  function writeCsv(sheet, name) {
    const csv = sheet.rows.map(r => (r || []).map(c => {
      const v = c == null ? '' : String(c);
      return /[",\n]/.test(v) ? '"' + v.replace(/"/g, '""') + '"' : v;
    }).join(',')).join('\n');
    saveBlob(new Blob(['\ufeff' + csv], { type: 'text/csv;charset=utf-8;' }), name + '.csv');
  }

  function chartPng(id) {
    const ch = charts[id];
    if (!ch || !ch.canvas) return null;
    const src = ch.canvas;
    const c = document.createElement('canvas');
    c.width = src.width; c.height = src.height;
    const ctx = c.getContext('2d');
    ctx.fillStyle = css('--surface') || '#ffffff';
    ctx.fillRect(0, 0, c.width, c.height);
    ctx.drawImage(src, 0, 0);
    return { dataUrl: c.toDataURL('image/png'), w: src.width, h: src.height };
  }

  const CHART_KEYS = ['topics', 'trend', 'compare'];

  async function writeChartWorkbook(keys, name, withTables) {
    if (typeof ExcelJS === 'undefined') { toast('The chart export library could not load. Use the PNG export instead.', 'err'); return; }
    const wb = new ExcelJS.Workbook();
    wb.creator = 'Teacher dashboard';
    wb.created = new Date();

    if (withTables) {
      ['summary', 'students', 'heatmap', 'levels', 'live'].forEach(k => {
        const s = SHEETS[k]();
        const ws = wb.addWorksheet(String(s.name).slice(0, 31));
        s.rows.forEach(r => ws.addRow(r || []));
        ws.getRow(1).font = { bold: true };
        ws.columns.forEach((col, i) => {
          let max = 10;
          s.rows.forEach(r => { const v = r && r[i]; if (v != null) max = Math.max(max, Math.min(42, String(v).length + 3)); });
          col.width = max;
        });
      });
    }

    let added = 0;
    keys.forEach(k => {
      const s = SHEETS[k]();
      const img = s.chart ? chartPng(s.chart) : null;
      const ws = wb.addWorksheet(String(s.name).slice(0, 31));
      ws.getCell('A1').value = s.title;
      ws.getCell('A1').font = { bold: true, size: 13 };
      ws.getCell('A2').value = 'Filters: ' + (state.grade ? 'Grade ' + state.grade : 'All grades') + ' · ' +
        (state.section ? ((DATA.classrooms.find(c => String(c.id) === state.section) || {}).name || '') : 'All sections');
      ws.getCell('A2').font = { italic: true, size: 10 };

      let firstDataRow = 4;
      if (img) {
        const scale = Math.min(1, 720 / img.w);
        const id = wb.addImage({ base64: img.dataUrl, extension: 'png' });
        ws.addImage(id, { tl: { col: 0.2, row: 3.2 }, ext: { width: img.w * scale, height: img.h * scale } });
        firstDataRow = 4 + Math.ceil((img.h * scale) / 19) + 2;
        added++;
      }
      s.rows.forEach((r, i) => {
        const row = ws.getRow(firstDataRow + i);
        row.values = r || [];
        if (i === 0) row.font = { bold: true };
        row.commit && row.commit();
      });
      ws.columns.forEach((col, i) => {
        let max = 12;
        s.rows.forEach(r => { const v = r && r[i]; if (v != null) max = Math.max(max, Math.min(42, String(v).length + 3)); });
        col.width = max;
      });
    });

    const buf = await wb.xlsx.writeBuffer();
    saveBlob(new Blob([buf], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' }), name + '.xlsx');
    toast(added ? 'Excel file with ' + added + ' chart' + (added === 1 ? '' : 's') + ' downloaded.' : 'Excel file downloaded.');
  }

  function exportPng(key) {
    const s = SHEETS[key]();
    const img = s.chart ? chartPng(s.chart) : null;
    if (!img) { toast('No chart to save for this panel yet.', 'err'); return; }
    fetch(img.dataUrl).then(r => r.blob()).then(b => { saveBlob(b, fileName(key) + '.png'); toast('Chart image downloaded.'); });
  }

  document.addEventListener('click', async e => {
    const btn = e.target.closest('[data-x]');
    if (!btn) return;
    const kind = btn.dataset.x, key = btn.dataset.key;
    const menu = btn.closest('details.xmenu');
    if (menu) menu.open = false;
    try {
      if (kind === 'sheet') {
        if (writeWorkbook([SHEETS[key]()], fileName(key))) toast('Excel file downloaded.');
      } else if (kind === 'csv') {
        writeCsv(SHEETS[key](), fileName(key)); toast('CSV file downloaded.');
      } else if (kind === 'png') {
        exportPng(key);
      } else if (kind === 'chart') {
        await writeChartWorkbook([key], fileName(key + '_chart'), false);
      } else if (kind === 'workbook') {
        const sheets = ['summary', 'students', 'attention', 'topics', 'heatmap', 'levels', 'trend', 'compare', 'live'].map(k => SHEETS[k]());
        if (writeWorkbook(sheets, fileName('teacher_dashboard'))) toast('Workbook with ' + sheets.length + ' sheets downloaded.');
      } else if (kind === 'charts') {
        await writeChartWorkbook(CHART_KEYS, fileName('teacher_dashboard_charts'), false);
      } else if (kind === 'both') {
        await writeChartWorkbook(CHART_KEYS, fileName('teacher_dashboard_full'), true);
      } else if (kind === 'csv-students') {
        writeCsv(SHEETS.students(), fileName('students')); toast('CSV file downloaded.');
      } else if (kind === 'student') {
        if (writeWorkbook([SHEETS.student(btn.dataset.sid)], fileName('student_report'))) toast('Student report downloaded.');
      }
    } catch (err) {
      console.error(err);
      toast('That export could not be created. Try again.', 'err');
    }
  });
});
</script>

{{-- Progress log (polls the existing API) --}}
<script>
(function () {
  const apiUrl = @json(route('teacher.progress-api'));
  const body = document.getElementById('progressBody');
  const searchInput = document.getElementById('progressSearch');
  const lastUpdatedEl = document.getElementById('lastUpdated');
  let currentData = [];

  const esc = s => String(s == null ? '' : s).replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
  const ini = name => String(name || '?').trim().split(/\s+/).slice(0, 2).map(w => w[0]).join('').toUpperCase();

  const gameIcons = {
    math_card_match: '🃏', number_wars: '⚔️', rocket_math: '🚀', factor_pond: '🐸',
    time_quest: '🌍', mdas_maze: '🎲', metric_quest: '📏', geometry_grid: '📐'
  };

  function renderTable(rows) {
    const filter = (searchInput.value || '').toLowerCase();
    const filtered = filter
      ? rows.filter(r => String(r.student_name).toLowerCase().includes(filter) || String(r.game || '').toLowerCase().includes(filter))
      : rows;

    window.TD_LIVE_ROWS = filtered;

    if (filtered.length === 0) {
      body.innerHTML = '<div class="nodata"><i class="bi bi-inbox"></i>' +
        (filter ? 'No student or game matches that search.' : 'No progress yet. Students appear once they start playing.') + '</div>';
      return;
    }

    let html = '<div class="dt-wrap"><table class="dt"><thead><tr>' +
      '<th>Student</th><th>Classroom</th><th>Game</th><th style="min-width:180px;">Progress</th><th>Avg score</th><th>Last played</th>' +
      '</tr></thead><tbody>';

    filtered.forEach(r => {
      const who = '<td><div style="display:flex;align-items:center;gap:9px;"><span class="ini">' + esc(r.student_init || ini(r.student_name)) + '</span>' +
                  '<span style="font-weight:600;">' + esc(r.student_name) + '</span></div></td>' +
                  '<td style="color:var(--ink-2);">' + esc(r.classroom) + '</td>';
      if (!r.game) {
        html += '<tr>' + who + '<td colspan="4" style="color:var(--ink-3);">Has not started any games</td></tr>';
        return;
      }
      const icon = gameIcons[r.game_slug] || '🎮';
      const pct = r.total_levels > 0 ? Math.min(100, Math.max(0, Math.round(((r.level - 1) / r.total_levels) * 100))) : 0;
      html += '<tr>' + who +
        '<td><span class="gtag"><span class="gi">' + icon + '</span>' + esc(r.game) + '</span></td>' +
        '<td><div style="display:flex;align-items:center;gap:9px;"><span class="lvl">Lvl ' + esc(r.level) + ' / ' + esc(r.total_levels) + '</span>' +
        '<span class="bar"><i style="width:' + pct + '%;"></i></span>' +
        '<span class="n" style="color:var(--ink-3);min-width:34px;text-align:right;">' + pct + '%</span></div></td>' +
        '<td class="n" style="font-weight:600;">' + Number(r.avg_score || 0).toFixed(1) + '</td>' +
        '<td class="n" style="color:var(--ink-3);">' + (r.last_played ? esc(r.last_played) : '—') + '</td></tr>';
    });

    body.innerHTML = html + '</tbody></table></div>' +
      '<div class="rowcount">' + filtered.length + ' row' + (filtered.length === 1 ? '' : 's') + '</div>';
  }

  function fetchProgress() {
    if (document.hidden) return;
    fetch(apiUrl, { credentials: 'same-origin' })
      .then(r => r.json())
      .then(data => {
        currentData = data.rows || [];
        lastUpdatedEl.textContent = data.updated_at ? 'Updated ' + data.updated_at : '';
        renderTable(currentData);
      })
      .catch(() => {});
  }

  searchInput.addEventListener('input', () => renderTable(currentData));
  fetchProgress();
  setInterval(fetchProgress, 10000);
})();
</script>
@endsection