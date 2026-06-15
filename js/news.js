/* ===========================
   KNCCI NYERI — NEWS.JS
   =========================== */

const newsTabs    = document.querySelectorAll('.news-tab');
const swCatLinks  = document.querySelectorAll('.sw-cat-link');
const newsCards   = document.querySelectorAll('.n-card');
const nEmpty      = document.getElementById('nEmpty');
const loadMoreBtn = document.getElementById('loadMoreBtn');
const loadMoreWrap= document.getElementById('loadMore');
const searchInput = document.getElementById('newsSearch');
const searchBtn   = document.getElementById('searchBtn');

const INITIAL_VISIBLE = 6;
let currentFilter = 'all';
let allVisible = [];

// ── Filter articles
function filterNews(filter) {
  currentFilter = filter;
  allVisible = [];

  newsCards.forEach(card => {
    const cat = card.dataset.category;
    const matches = filter === 'all' || cat === filter;
    card.classList.toggle('hidden', !matches);
    if (matches) allVisible.push(card);
  });

  // Show only first N initially
  allVisible.forEach((card, i) => {
    card.classList.toggle('hidden', i >= INITIAL_VISIBLE);
  });

  nEmpty.style.display = allVisible.length === 0 ? 'block' : 'none';
  loadMoreWrap.style.display = allVisible.length > INITIAL_VISIBLE ? 'block' : 'none';
}

// ── Sync tabs + sidebar
function setActiveFilter(filter) {
  newsTabs.forEach(t => t.classList.toggle('active', t.dataset.filter === filter));
  swCatLinks.forEach(l => l.classList.toggle('active', l.dataset.cat === filter));
  filterNews(filter);
}

// ── Tab clicks
newsTabs.forEach(tab => {
  tab.addEventListener('click', () => setActiveFilter(tab.dataset.filter));
});

// ── Sidebar category clicks
swCatLinks.forEach(link => {
  link.addEventListener('click', e => {
    e.preventDefault();
    setActiveFilter(link.dataset.cat);
  });
});

// ── Load more
loadMoreBtn.addEventListener('click', () => {
  // Show all cards in current filter
  allVisible.forEach(card => card.classList.remove('hidden'));
  loadMoreWrap.style.display = 'none';
});

// ── Search
function doSearch() {
  const q = searchInput.value.trim().toLowerCase();
  if (!q) { setActiveFilter('all'); return; }

  // Reset tabs
  newsTabs.forEach(t => t.classList.remove('active'));
  swCatLinks.forEach(l => l.classList.remove('active'));

  let found = 0;
  newsCards.forEach(card => {
    const text = card.textContent.toLowerCase();
    const matches = text.includes(q);
    card.classList.toggle('hidden', !matches);
    if (matches) found++;
  });

  nEmpty.style.display = found === 0 ? 'block' : 'none';
  loadMoreWrap.style.display = 'none';
}

searchBtn.addEventListener('click', doSearch);
searchInput.addEventListener('keydown', e => { if (e.key === 'Enter') doSearch(); });

// ── Newsletter form
const newsletterForm = document.getElementById('newsletterForm');
if (newsletterForm) {
  newsletterForm.addEventListener('submit', e => {
    e.preventDefault();
    const btn = newsletterForm.querySelector('button');
    btn.textContent = 'Subscribed!';
    btn.style.background = 'var(--green)';
    btn.disabled = true;
    newsletterForm.reset();
  });
}

// ── Reset (used by empty state)
function resetNewsFilter() {
  searchInput.value = '';
  setActiveFilter('all');
}

// ── Init
setActiveFilter('all');
