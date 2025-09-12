// Gestion des toasts Bootstrap
document.addEventListener('DOMContentLoaded', function () {
  // Initialisation des toasts
  const toastElList = [].slice.call(document.querySelectorAll('.toast'));
  toastElList.map(function (toastEl) { 
    return new bootstrap.Toast(toastEl).show(); 
  });


  (function () {
    const applyTheme = () => {
      const isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
      document.documentElement.setAttribute('data-bs-theme', isDark ? 'dark' : 'light');
      document.documentElement.setAttribute('class', isDark ? 'dark' : 'light');
    };
    // Première application
    applyTheme();
    // Réagir si l'utilisateur change le thème système
    const mq = window.matchMedia('(prefers-color-scheme: dark)');
    if (mq.addEventListener) {
      mq.addEventListener('change', applyTheme);
    } else if (mq.addListener) {
      // Safari anciens
      mq.addListener(applyTheme);
    }
  })();

  // Filtrage des tâches avec debounce
  const filterInput = document.getElementById('slot-filter');
  if (filterInput) {
    const lists = [
      document.getElementById('slots-list'),
    ];

    const getItems = () => lists.flatMap(list => Array.from(list.querySelectorAll('li.slots')));

    function debounce(fn, delay) {
      let t; 
      return (...args) => { 
        clearTimeout(t); 
        t = setTimeout(() => fn.apply(null, args), delay); 
      };
    }

    const applyFilter = () => {
      const filter = (filterInput.value || '').toLowerCase();
      getItems().forEach(item => {
        const name = item.querySelector('.task-name').textContent.toLowerCase();
        item.style.display = name.includes(filter) ? '' : 'none';
      });
    };

    filterInput.addEventListener('input', debounce(applyFilter, 300));
  }


  // Amélioration de l'accessibilité mobile
  if (window.innerWidth <= 576) {
    // Augmenter la taille des zones cliquables sur mobile
    document.querySelectorAll('.btn').forEach(btn => {
      btn.style.minHeight = '44px';
      btn.style.padding = '12px 16px';
    });
    
    // Améliorer la navigation tactile
    document.querySelectorAll('.nav-link').forEach(link => {
      link.style.padding = '12px 16px';
      link.style.minHeight = '44px';
    });
  }
});


// Fonction utilitaire pour afficher des messages
function showMessage(message, type = 'info') {
  const toastContainer = document.querySelector('.toast-container');
  if (toastContainer) {
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-bg-${type} border-0`;
    toast.setAttribute('role', 'status');
    toast.setAttribute('aria-live', 'polite');
    toast.setAttribute('aria-atomic', 'true');
    toast.setAttribute('data-bs-delay', '4000');
    
    toast.innerHTML = `
      <div class="d-flex">
        <div class="toast-body">${message}</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    `;
    
    toastContainer.appendChild(toast);
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
  }
}
