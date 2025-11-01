<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Transit Guessr — Question</title>
  <style>
    :root{
      --line-color: #e30613;
      --line-name: "Ligne A";
      --operator: "Tisséo — Métro";

      --panel-color: #0f1220;
      --accent: #ff6b7a;
      --text: #ffffff;

      --card-w: min(1100px, 92vw);
      --card-h: clamp(420px, 70vh, 680px);
      --panel-w: min(46%, 520px);
      --radius: 18px;
      --shadow: 0 15px 40px rgba(0,0,0,.25);
    }
    *{box-sizing:border-box}
    html,body{height:100%}
    body{
      margin:0;background:#0a0c15;color:var(--text);
      font:16px/1.5 system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,Cantarell,"Helvetica Neue",Arial,"Noto Sans",sans-serif;
      display:grid;place-items:center;padding:24px;
    }

    /* Base labels */
    .stn-name{ fill:#fff; transition:opacity .12s ease, fill .12s ease; }

    /* Labels that are hidden by default (for stations with hideLabel:true) */
    .stn-name.hidden{ opacity:0; visibility:hidden; }

    /* When hovering any station, mute all labels */
    .map-wrap.hovering .stn-name{ fill:#9aa0a6 !important; opacity:.6; }

    /* ...except the hovered station: show its label at full white */
    .map-wrap.hovering g.station.is-hovered .stn-name{
    opacity:1; visibility:visible; fill:#fff !important;
    }

    /* Nice to have */
    #stations g.station{ cursor:pointer; }

    .quiz-card{position:relative;width:var(--card-w);height:var(--card-h);border-radius:var(--radius);overflow:hidden;box-shadow:var(--shadow);background:#02040a;isolation:isolate;}
    .quiz-card::after{content:"";position:absolute;inset:0;background:radial-gradient(120% 120% at 18% 40%, rgba(255,255,255,.06), transparent 55%) no-repeat;pointer-events:none;z-index:0;}
    .quiz-content{position:relative;z-index:1;height:100%;display:grid;grid-template-columns:1fr var(--panel-w);}
    .map-area{position:relative;display:grid;grid-template-rows:auto 1fr;}
    .map-header{display:flex;align-items:center;gap:10px;padding:14px 16px;}
    .badge{display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.15);border-radius:999px;padding:6px 10px;font-size:13px;backdrop-filter:blur(4px);}
    .dot{width:10px;height:10px;border-radius:50%;background:var(--line-color);box-shadow:0 0 0 3px color-mix(in oklab, var(--line-color), #000 75%);}
    .op{margin-left:auto;font-size:12px;opacity:.8;}
    .map-wrap{position:relative;overflow:hidden;padding:8px 12px 16px 16px;}
    .grid{position:absolute;inset:0;background:
        linear-gradient(to right, rgba(255,255,255,.05) 1px, transparent 1px) 0 0 / 40px 40px,
        linear-gradient(to bottom, rgba(255,255,255,.05) 1px, transparent 1px) 0 0 / 40px 40px;
      opacity:.35;pointer-events:none;}
    .right-panel{background:var(--panel-color);padding:28px;display:grid;grid-template-rows:auto 1fr auto;gap:18px;}
    .title{margin:0;font-size:clamp(20px,2.4vw,28px);line-height:1.15;font-weight:800;}
    .subtitle{margin:6px 0 0 0;font-size:14px;opacity:.8;}
    .choices{display:grid;gap:12px;align-content:start;margin-top:6px;}
    .choice{display:grid;grid-template-columns:36px 1fr auto;align-items:center;gap:14px;padding:14px 16px;border-radius:12px;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.08);color:var(--text);cursor:pointer;user-select:none;transition:transform .08s ease, background .2s ease, border-color .2s ease;}
    .choice:hover{transform:translateY(-1px);background:rgba(255,255,255,.10);border-color:rgba(255,255,255,.16);}
    .choice[aria-pressed="true"]{background:color-mix(in oklab, var(--line-color), #000 82%);border-color:color-mix(in oklab, var(--line-color), #fff 40%);}
    .bullet{width:28px;height:28px;border-radius:50%;display:grid;place-items:center;border:2px solid rgba(255,255,255,.55);font-size:12px;opacity:.9;background:rgba(0,0,0,.15);}
    .label{font-weight:700;}
    .meta{font-size:12px;opacity:.6;}
    .cta-row{display:flex;justify-content:space-between;align-items:center;gap:12px;}
    .skip,.confirm{appearance:none;border:none;cursor:pointer;border-radius:12px;padding:12px 16px;font-weight:700;font-size:14px;transition:transform .08s ease, opacity .2s ease, background .2s ease;}
    .skip{background:rgba(255,255,255,.08);color:#fff;border:1px solid rgba(255,255,255,.14);}
    .skip:hover{background:rgba(255,255,255,.12);}
    .confirm{background:var(--accent);color:#00101a;opacity:.7;}
    .confirm.enabled{opacity:1;}
    .confirm:disabled{cursor:not-allowed;opacity:.45;}
    @media (max-width:820px){:root{--panel-w:100%}.quiz-content{grid-template-columns:1fr}}
  </style>
</head>
<body>
  <article class="quiz-card" aria-labelledby="question-title">
    <div class="quiz-content">
      <!-- LEFT -->
      <section class="map-area" aria-label="Schéma de ligne de transport">
        <div class="map-header">
          <span class="badge"><span class="dot" aria-hidden="true"></span> <span class="line-name"></span></span>
          <span class="op"></span>
        </div>
        <div class="map-wrap">
          <div class="grid" aria-hidden="true"></div>
          <!-- Wider viewBox to fit long Paris trunk -->
          <svg id="map" width="100%" height="100%" viewBox="0 0 1100 460" role="img" aria-label="Plan stylisé de ligne"></svg>
        </div>
      </section>

      <!-- RIGHT -->
      <section class="right-panel" role="group" aria-describedby="question-subtitle">
        <header>
          <h1 id="question-title" class="title">À quelle ville appartient cette ligne&nbsp;?</h1>
          <p id="question-subtitle" class="subtitle">Choisissez la bonne ville.</p>
        </header>
        <div class="choices" id="choices"></div>
        <div class="cta-row">
          <button class="skip" type="button">Passer</button>
          <button class="confirm" type="button" disabled>Valider</button>
        </div>
      </section>
    </div>
  </article>

<!-- ===== QUESTIONS JSON ===== -->
<script type="application/json" id="questions-json">
{
  "questions": [
    {
      "title": "Ligne A",
      "operator": "Tisséo — Métro",
      "city": "Toulouse",
      "theme": { "lineColor": "#e30613" },
      "svg": {
        "mode": "simple",
        "y": 220, "xLeft": 60, "xRight": 1035, "lineWidth": 10,
        "dot": { "outer": 12, "core": 8 },
        "label": { "fontSize": 12, "margin": 14 }
      },
      "endCaps": { "emphasizeEnds": true },
      "labelPattern": "alternate",
      "stations": [
        { "name": "Basso-Cambo",                "labelPos": "n", "big": true },
        { "name": "Bellefontaine",              "labelPos": "n", "hideLabel": true },
        { "name": "Reynerie",                   "labelPos": "n", "hideLabel": true },
        { "name": "Mirail-Université",          "labelPos": "n", "hideLabel": true },
        { "name": "Bagatelle",                  "labelPos": "n", "hideLabel": true },
        { "name": "Mermoz",                     "labelPos": "n", "hideLabel": true },
        { "name": "Fontaine-Lestang",           "labelPos": "n", "hideLabel": true },
        { "name": "Arènes",                     "labelPos": "n", "hideLabel": true },
        { "name": "Patte-d’Oie",                "labelPos": "n", "hideLabel": true },
        { "name": "Saint-Cyprien — République", "labelPos": "n", "hideLabel": true },
        { "name": "Esquirol",                   "labelPos": "n", "hideLabel": true },
        { "name": "Capitole",                   "labelPos": "n", "hideLabel": true },
        { "name": "Jean-Jaurès",                "labelPos": "n", "hideLabel": true },
        { "name": "Marengo — SNCF",             "labelPos": "n", "hideLabel": true },
        { "name": "Jolimont",                   "labelPos": "n", "hideLabel": true },
        { "name": "Roseraie",                   "labelPos": "n", "hideLabel": true },
        { "name": "Argoulets",                  "labelPos": "n", "hideLabel": true },
        { "name": "Balma-Gramont",              "labelPos": "n", "big": true }
      ],
      "choices": ["Marseille","Toulouse","Lyon","Bordeaux"],
      "correctCity": "Toulouse"
    },

    {
      "title": "Ligne B",
      "operator": "Tisséo — Métro",
      "city": "Toulouse",
      "theme": { "lineColor": "#f6e016" },
      "svg": {
        "mode": "simple",
        "y": 220, "xLeft": 60, "xRight": 1035, "lineWidth": 10,
        "dot": { "outer": 10, "core": 6 },
        "label": { "fontSize": 12, "margin": 14 }
      },
      "endCaps": { "emphasizeEnds": true },
      "labelPattern": "alternate",
      "stations": [
        { "name": "Borderouge",                    "labelPos": "n", "big": true },
        { "name": "Trois-Cocus",                   "labelPos": "n", "hideLabel": true },
        { "name": "La Vache",                      "labelPos": "n", "hideLabel": true },
        { "name": "Barrière de Paris",             "labelPos": "n", "hideLabel": true },
        { "name": "Minimes — Claude Nougaro",      "labelPos": "n", "hideLabel": true },
        { "name": "Canal du Midi",                 "labelPos": "n", "hideLabel": true },
        { "name": "Compans-Caffarelli",            "labelPos": "n", "hideLabel": true },
        { "name": "Jeanne d’Arc",                  "labelPos": "n", "hideLabel": true },
        { "name": "Jean-Jaurès",                   "labelPos": "n", "hideLabel": true },
        { "name": "Carmes",                        "labelPos": "n", "hideLabel": true },
        { "name": "François Verdier",              "labelPos": "n", "hideLabel": true },
        { "name": "Saint-Michel — Marcel Langer",  "labelPos": "n", "hideLabel": true },
        { "name": "Empalot",                       "labelPos": "n", "hideLabel": true },
        { "name": "Sainte-Agne — SNCF",            "labelPos": "n", "hideLabel": true },
        { "name": "Saouzelong",                    "labelPos": "n", "hideLabel": true },
        { "name": "Rangueil",                      "labelPos": "n", "hideLabel": true },
        { "name": "Faculté de Pharmacie",          "labelPos": "n", "hideLabel": true },
        { "name": "Université Paul-Sabatier",      "labelPos": "n", "hideLabel": true },
        { "name": "Ramonville",                    "labelPos": "n",  "big": true }
      ],
      "choices": ["Lille","Toulouse","Rennes","Paris"],
      "correctCity": "Toulouse"
    },

    {
      "title": "Ligne 13",
      "operator": "RATP — Métro",
      "city": "Paris",
      "theme": { "lineColor": "#8ad3de" },
      "svg": { "mode": "poly", "lineWidth": 10, "dot": { "outer": 10, "core": 6 }, "label": { "fontSize": 15, "margin": 14 } },
      "paths": [
        { "id": "upper_parallel", "points": [[120,150],[170,150],[220,150],[260,150]] },
        { "id": "lower_parallel", "points": [[60,290],[90,290],[150,290],[260,290]] },
        { "id": "upper_curve",   "points": [[260,150],[275,160],[290,180],[305,200],[315,210],[325,215],[335,218],[345,219],[355,220]] },
        { "id": "lower_curve",   "points": [[260,290],[275,280],[290,265],[305,245],[315,230],[325,223],[335,221],[345,220],[355,220]] },
        { "id": "trunk",         "points": [[355,220],[1035,220]] }
      ],
      "stations": [
        { "name": "Les Courtilles",            "on": "upper_parallel", "t": 0.000, "labelPos": "n", "big": true },
        { "name": "Les Agnettes",              "on": "upper_parallel", "t": 0.200, "labelPos": "n", "hideLabel": true },
        { "name": "Gabriel Péri",              "on": "upper_parallel", "t": 0.400, "labelPos": "n", "hideLabel": true },
        { "name": "Mairie de Clichy",          "on": "upper_parallel", "t": 0.600, "labelPos": "n", "hideLabel": true },
        { "name": "Porte de Clichy",           "on": "upper_parallel", "t": 0.800, "labelPos": "n", "hideLabel": true },
        { "name": "Brochant",                  "on": "upper_parallel", "t": 1.000, "labelPos": "n", "hideLabel": true },

        { "name": "Saint-Denis—Université",    "on": "lower_parallel", "t": 0.000, "labelPos": "s", "big": true },
        { "name": "Basilique de Saint-Denis",  "on": "lower_parallel", "t": 0.143, "labelPos": "s", "hideLabel": true },
        { "name": "Saint-Denis—Porte de Paris","on": "lower_parallel", "t": 0.286, "labelPos": "s", "hideLabel": true },
        { "name": "Carrefour Pleyel",          "on": "lower_parallel", "t": 0.429, "labelPos": "s", "hideLabel": true },
        { "name": "Mairie de Saint-Ouen",      "on": "lower_parallel", "t": 0.572, "labelPos": "s", "hideLabel": true },
        { "name": "Garibaldi",                 "on": "lower_parallel", "t": 0.715, "labelPos": "s", "hideLabel": true },
        { "name": "Porte de Saint-Ouen",       "on": "lower_parallel", "t": 0.858, "labelPos": "s", "hideLabel": true },
        { "name": "Guy Môquet",                "on": "lower_parallel", "t": 1.000, "labelPos": "s", "hideLabel": true },

        { "name": "La Fourche",                "on": "trunk", "t": 0.0000, "labelPos": "n", "big": true },
        { "name": "Place de Clichy",           "on": "trunk", "t": 0.0588, "labelPos": "n", "hideLabel": true },
        { "name": "Liège",                     "on": "trunk", "t": 0.1176, "labelPos": "n", "hideLabel": true },
        { "name": "Saint-Lazare",              "on": "trunk", "t": 0.1764, "labelPos": "n", "hideLabel": true },
        { "name": "Miromesnil",                "on": "trunk", "t": 0.2352, "labelPos": "n", "hideLabel": true },
        { "name": "Champs-Élysées-Clemenceau", "on": "trunk", "t": 0.2940, "labelPos": "n", "hideLabel": true },
        { "name": "Montparnasse—Bienvenüe",    "on": "trunk", "t": 0.3528, "labelPos": "n", "hideLabel": true },
        { "name": "Varenne",                   "on": "trunk", "t": 0.4116, "labelPos": "n", "hideLabel": true },
        { "name": "Saint-François-Xavier",     "on": "trunk", "t": 0.4704, "labelPos": "n", "hideLabel": true },
        { "name": "Duroc",                     "on": "trunk", "t": 0.5292, "labelPos": "n", "hideLabel": true },
        { "name": "Montparnasse - Bienvenüe",  "on": "trunk", "t": 0.5880, "labelPos": "n", "hideLabel": true },
        { "name": "Gaîté",                     "on": "trunk", "t": 0.6468, "labelPos": "n", "hideLabel": true },
        { "name": "Pernety",                   "on": "trunk", "t": 0.7056, "labelPos": "n", "hideLabel": true },
        { "name": "Plaisance",                 "on": "trunk", "t": 0.7644, "labelPos": "n", "hideLabel": true },
        { "name": "Porte de Vanves",           "on": "trunk", "t": 0.8232, "labelPos": "n", "hideLabel": true },
        { "name": "Malakoff-Plateau de Vanves","on": "trunk", "t": 0.8820, "labelPos": "n", "hideLabel": true },
        { "name": "Malakoff-Rue Étienne Dolet","on": "trunk", "t": 0.9408, "labelPos": "n", "hideLabel": true },
        { "name": "Châtillon—Montrouge",       "on": "trunk", "t": 1.0000, "labelPos": "n", "big": true }
      ],
      "choices": ["Paris","Lille","Lyon","Toulouse"],
      "correctCity": "Paris"
    },

    {
      "title": "Ligne 1",
      "operator": "RATP — Métro",
      "city": "Paris",
      "theme": { "lineColor": "#ffd800" },
      "svg": {
        "mode": "simple",
        "y": 220, "xLeft": 60, "xRight": 1035, "lineWidth": 10,
        "dot": { "outer": 11, "core": 7 },
        "label": { "fontSize": 12, "margin": 12 }
      },
      "labelPattern": "alternate",
      "stations": [
        { "name": "La Défense", "big": true },
        { "name": "Esplanade de La Défense", "hideLabel": true },
        { "name": "Pont de Neuilly" },
        { "name": "Les Sablons", "hideLabel": true },
        { "name": "Charles de Gaulle — Étoile", "big": true },
        { "name": "George V", "hideLabel": true },
        { "name": "Champs-Élysées — Clemenceau" },
        { "name": "Concorde", "hideLabel": true },
        { "name": "Tuileries" },
        { "name": "Palais Royal — Musée du Louvre", "big": true },
        { "name": "Hôtel de Ville", "hideLabel": true },
        { "name": "Bastille" },
        { "name": "Nation", "big": true }
      ],
      "choices": ["Paris","Rennes","Lille","Marseille"],
      "correctCity": "Paris"
    },

    {
      "title": "Jubilee Line (demo)",
      "operator": "TfL — Underground",
      "city": "London",
      "theme": { "lineColor": "#868f98" },
      "svg": {
        "mode": "poly",
        "lineWidth": 10,
        "dot": { "outer": 10, "core": 6 },
        "label": { "fontSize": 12, "margin": 12 }
      },
      "paths": [
        { "id": "west", "points": [[80,300],[160,280],[240,260]] },
        { "id": "core", "points": [[240,260],[320,230],[400,220],[500,220],[600,220]] }
      ],
      "stations": [
        { "name": "Wembley Park", "on": "west", "t": 0.0, "labelPos": "s" },
        { "name": "Neasden", "on": "west", "t": 0.25, "hideLabel": true },
        { "name": "Dollis Hill", "on": "west", "t": 0.5, "hideLabel": true },
        { "name": "Willesden Green", "on": "west", "t": 0.75, "hideLabel": true },
        { "name": "Baker Street", "on": "core", "t": 0.1, "big": true, "labelPos": "n" },
        { "name": "Bond Street", "on": "core", "t": 0.2, "hideLabel": true },
        { "name": "Green Park", "on": "core", "t": 0.3, "hideLabel": true },
        { "name": "Westminster", "on": "core", "t": 0.4, "labelPos": "s" },
        { "name": "Waterloo", "on": "core", "t": 0.5, "big": true, "labelPos": "n" },
        { "name": "London Bridge", "on": "core", "t": 0.65, "hideLabel": true },
        { "name": "Canary Wharf", "on": "core", "t": 0.85, "big": true, "labelPos": "s" }
      ],
      "choices": ["New York","Berlin","London","Madrid"],
      "correctCity": "London"
    },

    {
      "title": "L5 (demo)",
      "operator": "TMB — Metro",
      "city": "Barcelona",
      "theme": { "lineColor": "#009fe3" },
      "svg": {
        "mode": "simple",
        "y": 220, "xLeft": 60, "xRight": 740, "lineWidth": 10,
        "dot": { "outer": 10, "core": 6 },
        "label": { "fontSize": 12, "margin": 12 }
      },
      "stations": [
        { "name": "Cornellà Centre", "big": true },
        { "name": "Gavarra", "hideLabel": true },
        { "name": "Sant Ildefons" },
        { "name": "Ernest Lluch", "hideLabel": true },
        { "name": "Collblanc", "big": true },
        { "name": "Badal", "hideLabel": true },
        { "name": "Plaça de Sants" },
        { "name": "Sants Estació", "big": true },
        { "name": "Entença", "hideLabel": true },
        { "name": "Hospital Clínic" },
        { "name": "Diagonal", "big": true },
        { "name": "Verdaguer", "hideLabel": true },
        { "name": "Sagrada Família" }
      ],
      "choices": ["Lisbon","Barcelona","Rome","Valencia"],
      "correctCity": "Barcelona"
    },

    {
      "title": "Powell–Hyde Cable Car",
      "operator": "SFMTA — Cable Car",
      "city": "San Francisco",
      "theme": { "lineColor": "#c03a2b" },
      "svg": {
        "mode": "poly",
        "lineWidth": 10,
        "dot": { "outer": 9, "core": 5 },
        "label": { "fontSize": 12, "margin": 10 }
      },
      "paths": [
        { "id": "hill", "points": [[80,320],[140,280],[200,240],[260,210],[320,190]] }
      ],
      "stations": [
        { "name": "Powell & Market", "on": "hill", "t": 0.0, "big": true, "labelPos": "s" },
        { "name": "Jackson St", "on": "hill", "t": 0.35, "hideLabel": true },
        { "name": "Lombard St", "on": "hill", "t": 0.55, "labelPos": "n" },
        { "name": "Hyde & Beach", "on": "hill", "t": 1.0, "big": true, "labelPos": "s" }
      ],
      "choices": ["San Francisco","Seattle","Los Angeles","San Diego"],
      "correctCity": "San Francisco"
    },

    {
      "title": "Route 96 (demo)",
      "operator": "Yarra Trams",
      "city": "Melbourne",
      "theme": { "lineColor": "#43a047" },
      "svg": {
        "mode": "simple",
        "y": 220, "xLeft": 60, "xRight": 740, "lineWidth": 10,
        "dot": { "outer": 9, "core": 5 },
        "label": { "fontSize": 12, "margin": 10 }
      },
      "stations": [
        { "name": "East Brunswick", "big": true },
        { "name": "Nicholson St", "hideLabel": true },
        { "name": "Lygon St" },
        { "name": "Melbourne Central", "big": true },
        { "name": "Bourke St Mall", "hideLabel": true },
        { "name": "Southern Cross" },
        { "name": "Port Junction", "hideLabel": true },
        { "name": "St Kilda Beach", "big": true }
      ],
      "choices": ["Sydney","Adelaide","Melbourne","Perth"],
      "correctCity": "Melbourne"
    }
  ]
}

</script>

<script>
/* ===== helpers ===== */
function svgEl(name, attrs = {}, children = []) {
  const ns = 'http://www.w3.org/2000/svg';
  const n = document.createElementNS(ns, name);
  for (const [k, v] of Object.entries(attrs)) n.setAttribute(k, v);
  (Array.isArray(children) ? children : [children]).forEach(c => {
    n.appendChild(typeof c === 'string' ? document.createTextNode(c) : c);
  });
  return n;
}

function setupHoverUI(){
  const mapWrap = document.querySelector('.map-wrap');
  if (!mapWrap) return;

  // Clear previous
  mapWrap.querySelectorAll('#stations g.station').forEach(g=>{
    g.onmouseover = g.onmouseout = null;
  });

  mapWrap.querySelectorAll('#stations g.station').forEach(g=>{
    g.addEventListener('mouseover', ()=>{
      mapWrap.classList.add('hovering');
      g.classList.add('is-hovered');
    });
    g.addEventListener('mouseout', ()=>{
      g.classList.remove('is-hovered');
      // If no station remains hovered, remove global “hovering”
      if (!mapWrap.querySelector('#stations g.station.is-hovered')) {
        mapWrap.classList.remove('hovering');
      }
    });
  });
}

function ensureDefs(svgRoot) {
  const defs = svgEl('defs', {}, [
    svgEl('filter', {
      id: 'glow',
      filterUnits: 'userSpaceOnUse',
      x: '-100', y: '-100', width: '1400', height: '800' // ← bigger than viewBox
    }, [
      svgEl('feGaussianBlur', { stdDeviation: '4', result: 'blur' }),
      svgEl('feMerge', {}, [
        svgEl('feMergeNode', { in: 'blur' }),
        svgEl('feMergeNode', { in: 'SourceGraphic' })
      ])
    ])
  ]);
  svgRoot.appendChild(defs);
  return defs;
}

/* Draw a station with optional icons */
function drawStationDot(g, outer, core, opts = {}) {
  const color = 'var(--line-color)';
  const o = outer, c = core;

  // base
  g.appendChild(svgEl('circle', { r: String(o), fill: color }));
  g.appendChild(svgEl('circle', { r: String(c), fill: '#fff' }));
  g.appendChild(svgEl('circle', {
    r: String((outer ?? 10) + 16),
    fill: 'transparent',
    'pointer-events': 'all'
  }));


  // interchange ring (white ring)
  if (opts.interchange) {
    g.appendChild(svgEl('circle', {
      r: String(o + 5),
      fill: 'none',
      stroke: '#fff',
      'stroke-width': 3
    }));
  }

  // terminal cap: subtle outer stroke ring in line color
  if (opts.terminal) {
    g.appendChild(svgEl('circle', {
      r: String(o + 3),
      fill: 'none',
      stroke: color,
      'stroke-width': 3
    }));
  }
}

/* Label with margin from dot edge */
function placeLabel(g, text, pos='n', fontSize=11, outerRadius=8, gap=10) {
  const d = (outerRadius || 0) + (gap || 0);
  let anchor = 'start', x = 0, y = 0;
  if (pos === 'n') { x = 10;  y = -d; anchor = 'start'; }
  else if (pos === 's'){ x = 0;  y =  d; anchor = 'end'; }
  else { x = 0; y = -d; anchor = 'start'; }

  const t = svgEl('text', {
    class: 'stn-name',
    'text-anchor': anchor,
    x: String(x),
    y: String(y),
    style: `font: 600 ${fontSize}px/1.1 system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif; fill:#fff;transform: rotate(-45deg);`
  });

  const parts = text.includes('—')
    ? text.split('—').map(s => s.trim()).map((s,k)=>k ? '— ' + s : s)
    : [text];

  parts.forEach((ln, i) => t.appendChild(svgEl('tspan', { x: String(x), dy: i ? String(12) : '0' }, ln)));
  g.appendChild(t);
}

/* ===== simple horizontal (all single lines) ===== */
function renderSimple(svgRoot, spec) {
  const { y, xLeft, xRight, lineWidth, dot, label } = spec.svg;
  while (svgRoot.firstChild) svgRoot.removeChild(svgRoot.firstChild);
  ensureDefs(svgRoot);

  const strokeColor = getComputedStyle(document.documentElement).getPropertyValue('--line-color').trim();
  svgRoot.appendChild(svgEl('path', {
    id: 'line', d: `M${xLeft},${y} L ${xRight},${y}`, fill: 'none',
    stroke: strokeColor, 'stroke-width': lineWidth, filter: 'url(#glow)'
  }));

  const list = spec.stations;
  const n = list.length;
  const step = (xRight - xLeft) / (n - 1);
  const gStations = svgEl('g', { id: 'stations' });
  svgRoot.appendChild(gStations);

  list.forEach((raw, i) => {
    const st = (typeof raw === 'string') ? { name: raw } : raw;
    const x = xLeft + step * i;
    const g = svgEl('g', { transform: `translate(${x},${y})` });
    g.classList.add('station');


    const isBig = !!st.big || !!st.terminal;
    const outer = st.outer ?? (isBig ? dot.outer + 4 : dot.outer);
    const core  = st.core  ?? (isBig ? dot.core  + 3 : dot.core);

    drawStationDot(g, outer, core, { interchange: !!st.interchange, terminal: !!st.terminal });

    // label side
    let pos;
    if (st.labelPos) pos = st.labelPos;
    else if (spec.labelPattern === 'above') pos = 'n';
    else if (spec.labelPattern === 'below') pos = 's';
    else pos = (i === 0 || i === n - 1) ? 'n' : (i % 2 === 0 ? 'n' : 's');

    // Always create the label; hide it if hideLabel is true
    if (st.name) {
        const gap = (spec.svg?.label?.margin ?? 12);
        placeLabel(g, st.name, pos, label.fontSize ?? 11, outer, st.labelGap ?? gap);
        if (st.hideLabel) {
            const t = g.querySelector('.stn-name');
            if (t) t.classList.add('hidden');
        }
    }

    gStations.appendChild(g);
  });
}

/* ===== fork (still horizontal segments only) =====
   Keeps your two parallels + straight merges + horizontal trunk */
function renderPoly(svgRoot, spec) {
  const { lineWidth, dot, label } = spec.svg;
  while (svgRoot.firstChild) svgRoot.removeChild(svgRoot.firstChild);
  ensureDefs(svgRoot);

  const strokeColor = getComputedStyle(document.documentElement).getPropertyValue('--line-color').trim();
  const gLines = svgEl('g', { id: 'lines', fill: 'none', stroke: strokeColor, 'stroke-width': lineWidth, 'stroke-linecap': 'round', 'stroke-linejoin': 'round', filter: 'url(#glow)' });
  svgRoot.appendChild(gLines);

  const pathMap = new Map();
  (spec.paths || []).forEach(p => {
    // force straight segments between points (already horizontal/diagonal in your json)
    const d = p.points.map((pt, i) => (i ? 'L ' : 'M') + pt[0] + ',' + pt[1]).join(' ');
    const pathEl = svgEl('path', { d });
    gLines.appendChild(pathEl);
    if (p.id) pathMap.set(p.id, pathEl);
  });

  const gStations = svgEl('g', { id: 'stations' });
  svgRoot.appendChild(gStations);

  (spec.stations || []).forEach(s => {
    let px = s.x, py = s.y;

    // Support { on: 'pathId', t } OR absolute { x, y }
    if (s.on && typeof s.t === 'number' && pathMap.has(s.on)) {
      const pathEl = pathMap.get(s.on);
      const total = pathEl.getTotalLength();
      const pt = pathEl.getPointAtLength(Math.max(0, Math.min(1, s.t)) * total);
      px = pt.x; py = pt.y;
    }

    if (px == null || py == null) return;

    const g = svgEl('g', { transform: `translate(${px},${py})` });
    g.classList.add('station');

    const isBig = !!s.big || !!s.terminal;
    const outer = s.outer ?? (isBig ? dot.outer + 4 : dot.outer);
    const core  = s.core  ?? (isBig ? dot.core  + 3 : dot.core);

    drawStationDot(g, outer, core, { interchange: !!s.interchange, terminal: !!s.terminal });

    if (s.name) {
        const gap = (spec.svg?.label?.margin ?? 12);
        placeLabel(g, s.name, s.labelPos || 'n', label.fontSize ?? 11, outer, s.labelGap ?? gap);
        if (s.hideLabel) {
            const t = g.querySelector('.stn-name');
            if (t) t.classList.add('hidden');
        }
    }

    gStations.appendChild(g);
  });
}

/* router unchanged */
function renderLine(svgRoot, spec) {
  const mode = (spec.svg && spec.svg.mode) || 'simple';
  if (mode === 'poly') return renderPoly(svgRoot, spec);
  return renderSimple(svgRoot, spec);
}

</script>
<script>
  // ===== minimal quiz driver =====
  const data = JSON.parse(document.getElementById('questions-json').textContent);
  let qIndex = 0;

  function applyTheme(q){
    document.documentElement.style.setProperty('--line-color', q.theme?.lineColor || '#999');
    document.querySelector('.line-name').textContent = q.title || '';
    document.querySelector('.op').textContent = q.operator || '';
  }

  function renderChoices(q){
    const wrap = document.getElementById('choices');
    wrap.innerHTML = q.choices.map((label, j) => `
      <button class="choice" data-choice="${j}" aria-pressed="false" type="button">
        <span class="bullet">${String.fromCharCode(65 + j)}</span>
        <span class="label">${label}</span>
        <span class="meta">Ville</span>
      </button>
    `).join('');

    const confirmBtn = document.querySelector('.confirm');
    confirmBtn.disabled = true;
    confirmBtn.classList.remove('enabled');

    wrap.onclick = (e) => {
      const btn = e.target.closest('.choice'); if (!btn) return;
      wrap.querySelectorAll('.choice[aria-pressed="true"]').forEach(b => b.setAttribute('aria-pressed','false'));
      btn.setAttribute('aria-pressed','true');
      confirmBtn.disabled = false;
      confirmBtn.classList.add('enabled');
    };

    confirmBtn.onclick = () => {
      const selectedIdx = Number(wrap.querySelector('.choice[aria-pressed="true"]')?.dataset.choice);
      const ok = q.choices[selectedIdx] === q.correctCity;
      alert(ok ? "✅ Bonne réponse !" : `❌ Mauvaise réponse. Réponse: ${q.correctCity}`);
    };

    document.querySelector('.skip').onclick = () => {
      qIndex = (qIndex + 1) % data.questions.length;
      loadQuestion(qIndex);
    };
  }

  function loadQuestion(i){
    const q = data.questions[i];
    applyTheme(q);
    renderLine(document.getElementById('map'), q);
    renderChoices(q);
    setupHoverUI();               // ← add this
  }

  // kick off
  loadQuestion(qIndex);
</script>

</body>
</html>
