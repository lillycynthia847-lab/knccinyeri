/* ===========================
   KNCCI NYERI — OPPORTUNITIES.JS
   =========================== */

// ── Tab Filter Logic
const tabs    = document.querySelectorAll('.opp-tab');
const sections = document.querySelectorAll('.opp-section');

tabs.forEach(tab => {
  tab.addEventListener('click', () => {
    // Update active tab
    tabs.forEach(t => t.classList.remove('active'));
    tab.classList.add('active');

    const filter = tab.dataset.filter;

    sections.forEach(section => {
      if (filter === 'all') {
        section.classList.remove('hidden');
      } else {
        // Match section id to filter keyword
        const matches = section.id === filter ||
          (filter === 'tender' && section.id === 'tenders') ||
          (filter === 'job'    && section.id === 'jobs')    ||
          (filter === 'grant'  && section.id === 'grants');
        section.classList.toggle('hidden', !matches);
      }
    });

    // Scroll to first visible section smoothly
    const firstVisible = document.querySelector('.opp-section:not(.hidden)');
    if (firstVisible) {
      const offset = firstVisible.getBoundingClientRect().top + window.scrollY - 160;
      window.scrollTo({ top: offset, behavior: 'smooth' });
    }
  });
});

// ── Deep-link: if URL has a hash matching a section, activate correct tab
window.addEventListener('DOMContentLoaded', () => {
  const hash = window.location.hash.replace('#', '');
  if (!hash) return;

  const map = { vendor: 'vendor', tenders: 'tender', jobs: 'job', grants: 'grant' };
  const filterKey = map[hash];
  if (!filterKey) return;

  const matchTab = document.querySelector(`.opp-tab[data-filter="${filterKey}"]`);
  if (matchTab) {
    matchTab.click();
    // Give page time to render before scrolling
    setTimeout(() => {
      const target = document.getElementById(hash);
      if (target) {
        const offset = target.getBoundingClientRect().top + window.scrollY - 160;
        window.scrollTo({ top: offset, behavior: 'smooth' });
      }
    }, 100);
  }
});
