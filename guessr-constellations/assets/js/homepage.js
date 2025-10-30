
let currentId = 1;
let lastQuestionId = null;
const optionsWrap = () => document.querySelector('#options');
const figure = () => document.querySelector('#figure');
const rightPanel = () => document.querySelector('#right-panel');
const nextBtn = () => document.querySelector('#nextBtn');
const question = () => document.querySelector('#question');
const endbutton = () => document.querySelector('#endbutton');

    // Récupération de la question
async function loadQuestion(id){
  const res = await fetch(`api/get-question.php?id=${id}`); // récupérer les données du json et la question
  const data = await res.json();
  if(!data || !data.success){ alert('Impossible de charger la question'); return; }
  lastQuestionId = data.next_id;
  currentId = data.id;
  renderQuestion(data);
}

    // Affichage des questions
function renderQuestion(q){
  // image
  figure().innerHTML = `<img src="assets/img/constellations/${q.image}" alt="Constellation #${q.id}">`;
  // options
  const html = q.options.map((opt,i)=>{
    return `<label class="option" data-value="${opt.value}">
      <input type="radio" name="answer" value="${opt.value}">
      <span>${opt.label}</span>
    </label>`;
  }).join('');
  optionsWrap().innerHTML = html;
  // clear right panel
  rightPanel().innerHTML = `<div class="sidebar-title"></div><div class="meta">Sélectionnez une réponse puis validez.</div>`;
  nextBtn().disabled = true;
  endbutton().hide = true;
  // listen
  optionsWrap().querySelectorAll('.option input').forEach(inp=>{
    inp.addEventListener('change', ()=>{
      checkAnswer(inp.value);
    });
  });
  question().innerHTML=q.name;
}

    // Vérifier si réponse juste ou fausse
async function checkAnswer(value){
  const body = new FormData();
  body.append('id', String(currentId));
  body.append('answer', value);
  const res = await fetch('api/check-answer.php', {method:'POST', body});
  const data = await res.json();
  // mark UI
  document.querySelectorAll('.option').forEach(el=>{
    el.classList.add('disabled');
    const v = el.getAttribute('data-value');
    if(v === data.correct_value){ el.classList.add('correct'); }
    if(v === value && !data.is_correct){ el.classList.add('incorrect'); }
  });

  // right panel, affichage texte
  const ok = data.is_correct;
  rightPanel().innerHTML = `
    <h3>${ok?'<span class="result-ok">Bonne réponse !</span>':'<span class="result-ko">Mauvaise réponse.</span>'}
      <span class="badge">#${data.id}</span>
    </h3>
    <p>${data.description}</p>
    <p>Pour en savoir plus : <a href="${data.wiki}" target="_blank" rel="noopener">voir le site</a></p>
  `;

  // next button
  if(data.next_id){
    endbutton().hidden = true;
    nextBtn().disabled = false;
    nextBtn().onclick = () => loadQuestion(data.next_id);
  }else{
    nextBtn().hidden = true;
    nextBtn().onclick = null;
    endbutton().hidden = false;
  }
}

window.addEventListener('DOMContentLoaded', ()=>{
  loadQuestion(currentId);
});
