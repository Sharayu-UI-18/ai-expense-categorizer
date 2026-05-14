/*
  script.js - Frontend starter JavaScript
  Contains DOMContentLoaded scaffold and stub functions for interaction.
*/

/**
 * script.js - Frontend interactions (UI only)
 * - Smooth scrolling
 * - Mobile navbar toggle
 * - Fake loading spinner demo and result rendering (frontend-only)
 */

document.addEventListener('DOMContentLoaded', () => {
  document.body.classList.add('page-ready');

  // Element refs
  const expenseForm = document.getElementById('expense-form');
  const loadingSpinner = document.getElementById('loading-spinner');
  const resultSection = document.getElementById('ai-result');
  const historyList = document.getElementById('history-list');
  const ctaAnalyze = document.getElementById('cta-analyze');
  const categorySearch = document.getElementById('category-search');
  const categoryRows = document.querySelectorAll('.category-history-row');
  const categoryCards = document.querySelectorAll('.category-card');
  const categoryFilterEmpty = document.getElementById('category-filter-empty');

  // Smooth scroll for all local anchor links
  document.querySelectorAll('a[href^="#"]').forEach((link) => {
    link.addEventListener('click', (event) => {
      const targetId = link.getAttribute('href');
      const target = targetId && targetId !== '#' ? document.querySelector(targetId) : null;
      if (!target) {
        return;
      }

      event.preventDefault();
      target.scrollIntoView({ behavior: 'smooth', block: 'start' });

      if (navLinks && navLinks.classList.contains('open')) {
        navLinks.classList.remove('open');
      }
    });
  });

  // Mobile nav toggle
  const navToggle = document.getElementById('nav-toggle');
  const navLinks = document.getElementById('nav-links');
  if(navToggle && navLinks){
    navToggle.addEventListener('click', () => {
      navLinks.classList.toggle('open');
      navToggle.classList.toggle('active');
    });
  }

  // Highlight the current page navigation target for a clearer dashboard state
  const path = window.location.pathname.split('/').pop() || 'index.php';
  document.querySelectorAll('.nav-link').forEach((link) => {
    const href = link.getAttribute('href');
    if ((path === 'index.php' || path === '') && href === '#') {
      link.classList.add('active');
    }
    if (path === 'category.php' && href === '#history') {
      link.classList.add('active');
    }
  });

  // Smooth scroll for CTA button
  if(ctaAnalyze){
    ctaAnalyze.addEventListener('click', (e) => {
      e.preventDefault();
      const expenseField = document.getElementById('expense_text');
      if (!expenseField) {
        return;
      }

      expenseField.scrollIntoView({behavior: 'smooth', block: 'center'});
      // subtle pulse animation on the textarea
      if(expenseField){
        expenseField.animate([{boxShadow: '0 0 0 rgba(0,0,0,0)'},{boxShadow: '0 0 0 4px rgba(6,182,212,0.14)'}],{duration:450,iterations:1});
      }
    });
  }

  // Fake analyze flow (frontend-only demo)
  if(expenseForm){
    expenseForm.addEventListener('submit', function(e){
      e.preventDefault();
      // Show spinner and simulate loading
      showSpinner(true);
      const submitButton = document.getElementById('analyze-btn');
      if (submitButton) {
        submitButton.disabled = true;
      }

      // Small delay so the user sees the loading state before the POST request.
      setTimeout(() => {
        expenseForm.submit();
      }, 450);
    });
  }

  // Category page search filter (frontend-only view filtering)
  if (categorySearch && categoryRows.length) {
    categorySearch.addEventListener('input', () => {
      const query = categorySearch.value.trim().toLowerCase();
      let visibleCount = 0;
      categoryRows.forEach((row) => {
        const haystack = (row.getAttribute('data-search') || '').toLowerCase();
        const isVisible = haystack.includes(query);
        row.style.display = isVisible ? 'grid' : 'none';
        if (isVisible) {
          visibleCount += 1;
        }
      });

      if (categoryFilterEmpty) {
        categoryFilterEmpty.style.display = visibleCount === 0 ? 'grid' : 'none';
      }
    });
  }

  // Give category cards a slightly stronger hover lift on click before navigation.
  categoryCards.forEach((card) => {
    card.addEventListener('click', () => {
      card.classList.add('active');
      setTimeout(() => card.classList.remove('active'), 220);
    });
  });

  // Display spinner utility
  function showSpinner(show){
    if(!loadingSpinner) return;
    loadingSpinner.style.display = show ? 'inline-block' : 'none';
  }

  // Demo: animate history items on load
  if(historyList){
    const items = historyList.querySelectorAll('.history-item');
    items.forEach((it,i) => {
      it.style.opacity = 0;it.style.transform = 'translateY(6px)';
      setTimeout(()=>{
        it.style.transition = 'opacity .4s ease, transform .4s ease';
        it.style.opacity = 1;it.style.transform = 'translateY(0)';
      }, 120 * i);
    });
  }

  // Progressive reveal for major sections
  const revealTargets = document.querySelectorAll('.hero-card, .expense-card, .result-item, .history');
  if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.style.transition = 'opacity 600ms ease, transform 600ms ease';
          entry.target.style.opacity = '1';
          entry.target.style.transform = 'translateY(0)';
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12 });

    revealTargets.forEach((target) => {
      target.style.opacity = '0';
      target.style.transform = 'translateY(18px)';
      observer.observe(target);
    });
  }

  // Small global fade-in for a smooth page transition feel.
  requestAnimationFrame(() => {
    document.body.classList.add('page-entered');
  });

  window.addEventListener('pageshow', () => {
    showSpinner(false);
    const submitButton = document.getElementById('analyze-btn');
    if (submitButton) {
      submitButton.disabled = false;
    }
  });
});

