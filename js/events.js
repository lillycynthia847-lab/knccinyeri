/* ===========================
   KNCCI NYERI — EVENTS.JS
   =========================== */

const tabs      = document.querySelectorAll('.ev-tab');
const cards     = document.querySelectorAll('.ev-card');
const evGrid    = document.getElementById('evGrid');
const evEmpty   = document.getElementById('evEmpty');
const featWrap  = document.getElementById('featuredWrap');
const gridBtn   = document.getElementById('gridView');
const listBtn   = document.getElementById('listView');
const secLabel  = document.getElementById('sectionLabel');
const secTitle  = document.getElementById('sectionTitle');
const secSub    = document.getElementById('sectionSub');

// ── Filter logic
function filterEvents(filter) {
  let visible = 0;

  cards.forEach(card => {
    const cat    = card.dataset.category;
    const status = card.dataset.status;

    let show = false;
    if (filter === 'all')  show = status === 'upcoming';
    else if (filter === 'past') show = status === 'past';
    else show = cat === filter && status === 'upcoming';

    card.classList.toggle('hidden', !show);
    if (show) visible++;
  });

  // Show/hide empty state
  evEmpty.style.display = visible === 0 ? 'block' : 'none';

  // Show/hide featured block (only on "all" and matching category)
  const hideFeatured = filter === 'past';
  featWrap.style.display = hideFeatured ? 'none' : 'block';

  // Update section header text
  const labels = {
    all:         ['Upcoming Events',        'Mark Your Calendar',               'Register early — spaces are limited for most events'],
    agm:         ['Annual General Meetings','Chapter AGM Schedule',              'The most important governance event for all members'],
    workshop:    ['Workshops',              'Skills & Knowledge Sessions',       'Practical workshops designed to grow your business'],
    networking:  ['Networking Events',      'Build Your Business Network',       'Connect with Nyeri\'s leading business professionals'],
    training:    ['Training Programmes',    'Invest in Your Team',               'Professional training tailored for SMEs and entrepreneurs'],
    conference:  ['Conferences',            'Strategic Business Conversations',  'High-level forums on trade, investment, and policy'],
    past:        ['Past Events',            'Event Archive',                     'Reports and highlights from previous KNCCI Nyeri events'],
  };

  const [label, title, sub] = labels[filter] || labels.all;
  secLabel.textContent = label;
  secTitle.textContent = title;
  secSub.textContent   = sub;
}

// ── Tab click
tabs.forEach(tab => {
  tab.addEventListener('click', () => {
    tabs.forEach(t => t.classList.remove('active'));
    tab.classList.add('active');
    filterEvents(tab.dataset.filter);
  });
});

// ── Grid / List toggle
gridBtn.addEventListener('click', () => {
  gridBtn.classList.add('active');
  listBtn.classList.remove('active');
  evGrid.classList.remove('list-view');
});
listBtn.addEventListener('click', () => {
  listBtn.classList.add('active');
  gridBtn.classList.remove('active');
  evGrid.classList.add('list-view');
});

// ── Reset filter (used by empty state button)
function resetFilter() {
  tabs.forEach(t => t.classList.remove('active'));
  document.querySelector('[data-filter="all"]').classList.add('active');
  filterEvents('all');
}

// ── Init: show upcoming only
filterEvents('all');
