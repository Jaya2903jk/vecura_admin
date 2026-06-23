
<?php $page = 'tickets'; ?>
@extends('layout.mainlayout')
@section('content')
<h2 class="sr-only">Spending Overview Dashboard — IOU, Petty Cash, Branch Wallet, Employee Balance</h2>

<style>
.sr-card{background:var(--color-background-primary);border:.5px solid var(--color-border-tertiary);border-radius:var(--border-radius-lg);padding:1rem 1.25rem}
.stat-label{font-size:11px;color:var(--color-text-secondary);text-transform:uppercase;letter-spacing:.06em;margin-bottom:2px}
.stat-val{font-size:22px;font-weight:500;line-height:1.1;color:var(--color-text-primary)}
.stat-sub{font-size:11px;color:var(--color-text-tertiary);margin-top:1px}
.badge{display:inline-block;font-size:11px;padding:2px 8px;border-radius:20px;font-weight:500}
.sec-title{font-size:14px;font-weight:500;color:var(--color-text-primary);margin-bottom:.75rem}
.sr-table{width:100%;border-collapse:collapse;font-size:12px}
.sr-table th{background:var(--color-background-secondary);padding:7px 10px;text-align:left;font-weight:500;color:var(--color-text-secondary);border-bottom:.5px solid var(--color-border-tertiary);white-space:nowrap}
.sr-table td{padding:7px 10px;border-bottom:.5px solid var(--color-border-tertiary);color:var(--color-text-primary)}
.sr-table tr:hover td{background:var(--color-background-secondary)}
.fsel{padding:5px 8px;border:.5px solid var(--color-border-secondary);border-radius:var(--border-radius-md);font-size:12px;color:var(--color-text-primary);background:var(--color-background-primary)}
.tab-btn{padding:5px 14px;border:.5px solid var(--color-border-secondary);border-radius:var(--border-radius-md);font-size:12px;cursor:pointer;background:var(--color-background-primary);color:var(--color-text-secondary);transition:.15s}
.tab-btn.active{background:#00467d;color:#fff;border-color:#00467d}
.pos{color:#059669}.neg{color:#dc2626}
.apply-btn{padding:5px 16px;border-radius:var(--border-radius-md);font-size:12px;cursor:pointer;background:#00467d;color:#fff;border:none;font-weight:500}
</style>

  <div class="page-wrapper">
        <div class="content">
  <!-- HEADER + FILTERS -->
  <div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:10px;margin-bottom:1rem">
    <div>
      <div style="font-size:16px;font-weight:500;color:var(--color-text-primary)">Spending Overview</div>
      <div style="font-size:12px;color:var(--color-text-secondary)">IOU · Petty Cash · Branch Wallet · Employee Balance</div>
    </div>
    <div style="display:flex;gap:6px;flex-wrap:wrap;align-items:center">
      <select id="fYear" class="fsel">
        <option>2024</option><option selected>2025</option><option>2026</option>
      </select>
      <select id="fMonth" class="fsel">
        <option value="">Full Year</option>
        <option>Jan</option><option>Feb</option><option>Mar</option><option>Apr</option>
        <option>May</option><option selected>Jun</option><option>Jul</option><option>Aug</option>
        <option>Sep</option><option>Oct</option><option>Nov</option><option>Dec</option>
      </select>
      <select id="fBranch" class="fsel">
        <option>All Branches</option>
        <option>Chennai Central</option><option>Coimbatore</option>
        <option>Madurai</option><option>Salem</option><option>Trichy</option>
      </select>
      <button class="apply-btn" onclick="randomize()">Apply</button>
    </div>
  </div>

  <!-- SUMMARY CARDS -->
  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:10px;margin-bottom:1.25rem">
    <div class="sr-card">
      <div class="stat-label">Total Given</div>
      <div class="stat-val" id="cGiven" style="color:#00467d">₹14.2L</div>
      <div class="stat-sub">IOU + Petty Cash</div>
    </div>
    <div class="sr-card">
      <div class="stat-label">Total Spent</div>
      <div class="stat-val" id="cSpent" style="color:#059669">₹11.8L</div>
      <div class="stat-sub">Paid + Bills</div>
    </div>
    <div class="sr-card">
      <div class="stat-label">IOU Pending</div>
      <div class="stat-val" id="cPending" style="color:#d97706">₹2.4L</div>
      <div class="stat-sub" id="cReq">38 requests</div>
    </div>
    <div class="sr-card">
      <div class="stat-label">Wallet Debited</div>
      <div class="stat-val" id="cWallet" style="color:#dc2626">₹8.6L</div>
      <div class="stat-sub">Branch cash out</div>
    </div>
    <div class="sr-card">
      <div class="stat-label">Emp. Pending</div>
      <div class="stat-val" id="cEmp" style="color:#7c3aed">₹1.1L</div>
      <div class="stat-sub">Balance to settle</div>
    </div>
    <div class="sr-card">
      <div class="stat-label">PC Bills</div>
      <div class="stat-val" id="cBill">₹3.2L</div>
      <div class="stat-sub" id="cSub">22 submissions</div>
    </div>
  </div>

  <!-- MONTHLY TREND + DOUGHNUT -->
  <div style="display:grid;grid-template-columns:2fr 1fr;gap:12px;margin-bottom:1.25rem">

    <div class="sr-card">
      <div class="sec-title">Monthly Trend — Given vs Spent</div>
      <div style="position:relative;width:100%;height:200px">
        <canvas id="chartMonthly" role="img" aria-label="Monthly given vs spent bar chart">Monthly IOU and petty cash given vs spent per month</canvas>
      </div>
    </div>

    <div class="sr-card">
      <div class="sec-title">Spend by Module</div>
      <div style="position:relative;width:100%;height:160px">
        <canvas id="chartDoughnut" role="img" aria-label="Module spending doughnut chart">Breakdown of spending by module type</canvas>
      </div>
      <div id="dLegend" style="margin-top:8px;font-size:11px;display:flex;flex-wrap:wrap;gap:6px"></div>
    </div>

  </div>

  <!-- DAILY CHART -->
  <div class="sr-card" style="margin-bottom:1.25rem">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.75rem">
      <div class="sec-title" style="margin:0">Daily Spending</div>
      <select id="fDayMonth" class="fsel" onchange="buildDaily()">
        <option>Jan</option><option>Feb</option><option>Mar</option><option>Apr</option>
        <option>May</option><option selected>Jun</option><option>Jul</option><option>Aug</option>
      </select>
    </div>
    <div style="position:relative;width:100%;height:180px">
      <canvas id="chartDaily" role="img" aria-label="Daily spending bar chart">Daily IOU and PC spending for selected month</canvas>
    </div>
  </div>

  <!-- TABLES TABS -->
  <div class="sr-card">
    <div style="display:flex;gap:6px;margin-bottom:1rem">
      <button class="tab-btn active" id="tb1" onclick="showTab('branch')">Branch Report</button>
      <button class="tab-btn"        id="tb2" onclick="showTab('emp')">Employee Report</button>
    </div>

    <div id="tabBranch" style="overflow-x:auto">
      <table class="sr-table">
        <thead><tr>
          <th>#</th><th>Branch</th><th>IOU Given</th><th>IOU Paid</th>
          <th>PC Given</th><th>Bill Spent</th><th>Total Given</th><th>Total Spent</th><th>Variance</th>
        </tr></thead>
        <tbody id="branchBody"></tbody>
      </table>
    </div>

    <div id="tabEmp" style="display:none;overflow-x:auto">
      <table class="sr-table">
        <thead><tr>
          <th>#</th><th>Employee</th><th>Requests</th><th>Total Given</th>
          <th>Total Paid</th><th>Settled</th><th>Pending</th>
        </tr></thead>
        <tbody id="empBody"></tbody>
      </table>
    </div>
  </div>
</div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
<script>
const MONTHS = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
const BRANCHES = ['Chennai Central','Coimbatore','Madurai','Salem','Trichy'];
const EMPS = ['Arjun K','Priya S','Ravi M','Deepa N','Karthik R','Meena T','Suresh P','Lavanya B','Raj V','Uma C'];

let cM, cD, cDo;

function rnd(min,max){ return Math.floor(Math.random()*(max-min+1))+min; }
function fmt(n){ return '₹'+(n>=100000?(n/100000).toFixed(1)+'L':n>=1000?(n/1000).toFixed(0)+'K':n); }
function fmtFull(n){ return '₹'+Number(n).toLocaleString('en-IN'); }

function buildMonthly(){
  const given = MONTHS.map(()=>rnd(80000,200000));
  const spent = given.map(v=>Math.floor(v*(.6+Math.random()*.35)));
  if(cM) cM.destroy();
  cM = new Chart(document.getElementById('chartMonthly'),{
    type:'bar',
    data:{
      labels:MONTHS,
      datasets:[
        {label:'Given',data:given,backgroundColor:'#93C5FD',borderRadius:3},
        {label:'Spent',data:spent,backgroundColor:'#00467d',borderRadius:3}
      ]
    },
    options:{
      responsive:true,maintainAspectRatio:false,
      plugins:{
        legend:{display:true,position:'top',labels:{font:{size:11},boxWidth:10,padding:10}},
        tooltip:{callbacks:{label:ctx=>' '+fmt(ctx.raw)}}
      },
      scales:{
        y:{ticks:{font:{size:10},callback:v=>fmt(v)}},
        x:{ticks:{font:{size:10}}}
      }
    }
  });
}

function buildDoughnut(){
  const vals = [rnd(300000,500000),rnd(100000,300000),rnd(80000,200000),rnd(50000,150000)];
  const cols = ['#378ADD','#1D9E75','#EF9F27','#D85A30'];
  const labs = ['IOU Payments','Petty Cash','Bill Settlements','Wallet Debits'];
  if(cDo) cDo.destroy();
  cDo = new Chart(document.getElementById('chartDoughnut'),{
    type:'doughnut',
    data:{labels:labs,datasets:[{data:vals,backgroundColor:cols,borderWidth:2}]},
    options:{
      responsive:true,maintainAspectRatio:false,cutout:'65%',
      plugins:{legend:{display:false},tooltip:{callbacks:{label:ctx=>' '+ctx.label+': '+fmt(ctx.raw)}}}
    }
  });
  document.getElementById('dLegend').innerHTML = labs.map((l,i)=>
    `<span style="display:flex;align-items:center;gap:3px"><span style="width:8px;height:8px;border-radius:2px;background:${cols[i]}"></span><span style="color:var(--color-text-secondary)">${l}</span></span>`
  ).join('');
}

function buildDaily(){
  const days = 30;
  const iou = Array.from({length:days},()=>rnd(0,25000));
  const pc  = Array.from({length:days},()=>rnd(0,15000));
  const labels = Array.from({length:days},(_,i)=>`${String(i+1).padStart(2,'0')}`);
  if(cD) cD.destroy();
  cD = new Chart(document.getElementById('chartDaily'),{
    type:'bar',
    data:{
      labels,
      datasets:[
        {label:'IOU',data:iou,backgroundColor:'#378ADD',borderRadius:2},
        {label:'PC', data:pc, backgroundColor:'#1D9E75',borderRadius:2}
      ]
    },
    options:{
      responsive:true,maintainAspectRatio:false,
      plugins:{
        legend:{display:true,position:'top',labels:{font:{size:11},boxWidth:10,padding:10}},
        tooltip:{callbacks:{label:ctx=>' '+fmt(ctx.raw)}}
      },
      scales:{
        y:{ticks:{font:{size:10},callback:v=>fmt(v)}},
        x:{ticks:{font:{size:9},autoSkip:false,maxRotation:0}}
      }
    }
  });
}

function renderBranch(){
  const rows = BRANCHES.map((b,i)=>{
    const ig=rnd(80000,300000),ip=rnd(50000,ig),pc=rnd(30000,150000),bs=rnd(20000,100000);
    const tg=ig+pc,ts=ip+bs,va=tg-ts;
    return `<tr>
      <td>${i+1}</td><td><strong>${b}</strong></td>
      <td>${fmtFull(ig)}</td><td>${fmtFull(ip)}</td>
      <td>${fmtFull(pc)}</td><td>${fmtFull(bs)}</td>
      <td><strong>${fmtFull(tg)}</strong></td>
      <td><strong>${fmtFull(ts)}</strong></td>
      <td class="${va>=0?'pos':'neg'}">${va>=0?'+':''}${fmtFull(va)}</td>
    </tr>`;
  }).join('');
  document.getElementById('branchBody').innerHTML = rows;
}

function renderEmp(){
  const rows = EMPS.map((e,i)=>{
    const tg=rnd(30000,200000),tp=rnd(20000,tg),ts=rnd(10000,tp),pb=tp-ts;
    return `<tr>
      <td>${i+1}</td><td><strong>${e}</strong></td>
      <td>${rnd(1,8)}</td>
      <td>${fmtFull(tg)}</td><td>${fmtFull(tp)}</td>
      <td>${fmtFull(ts)}</td>
      <td class="${pb>0?'neg':'pos'}">${fmtFull(pb)}</td>
    </tr>`;
  }).join('');
  document.getElementById('empBody').innerHTML = rows;
}

function showTab(t){
  document.getElementById('tabBranch').style.display = t==='branch'?'':'none';
  document.getElementById('tabEmp').style.display    = t==='emp'?'':'none';
  document.getElementById('tb1').className = 'tab-btn'+(t==='branch'?' active':'');
  document.getElementById('tb2').className = 'tab-btn'+(t==='emp'?' active':'');
}

function randomize(){
  const g=rnd(1000000,2000000),s=Math.floor(g*.75+rnd(0,100000));
  document.getElementById('cGiven').textContent  = fmt(g);
  document.getElementById('cSpent').textContent  = fmt(s);
  document.getElementById('cPending').textContent= fmt(g-s);
  document.getElementById('cWallet').textContent = fmt(rnd(500000,900000));
  document.getElementById('cEmp').textContent    = fmt(rnd(50000,200000));
  document.getElementById('cBill').textContent   = fmt(rnd(200000,500000));
  document.getElementById('cReq').textContent    = rnd(20,80)+' requests';
  document.getElementById('cSub').textContent    = rnd(10,40)+' submissions';
  buildMonthly(); buildDoughnut(); buildDaily(); renderBranch(); renderEmp();
}

document.addEventListener('DOMContentLoaded', randomize);
</script>
@endsection
