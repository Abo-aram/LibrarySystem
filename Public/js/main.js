(function () {
  const STORAGE_KEY = 'preferred-theme';
  const root = document.documentElement;

  const prefersDarkScheme = () => window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;

  const getStoredTheme = () => localStorage.getItem(STORAGE_KEY);

  const applyTheme = (theme) => {
    const resolvedTheme = theme === 'dark' ? 'dark' : 'light';
    root.setAttribute('data-theme', resolvedTheme);
    localStorage.setItem(STORAGE_KEY, resolvedTheme);
    updateToggleButtons(resolvedTheme);
  };

  const updateToggleButtons = (theme) => {
    document.querySelectorAll('.theme-toggle').forEach((button) => {
      button.setAttribute('aria-pressed', theme === 'dark');
      button.textContent = theme === 'dark' ? '☀️' : '🌙';
      button.title = theme === 'dark' ? 'Switch to light mode' : 'Switch to dark mode';
    });
  };

  const setupToggleButton = (button) => {
    if (button.dataset.themeInitialized) return;

    button.addEventListener('click', () => {
      const currentTheme = root.getAttribute('data-theme');
      applyTheme(currentTheme === 'dark' ? 'light' : 'dark');
    });

    button.dataset.themeInitialized = 'true';
  };

  const initTheme = () => {
    const storedTheme = getStoredTheme();
    if (storedTheme) {
      root.setAttribute('data-theme', storedTheme);
    } else if (prefersDarkScheme()) {
      root.setAttribute('data-theme', 'dark');
    } else {
      root.setAttribute('data-theme', 'light');
    }

    updateToggleButtons(root.getAttribute('data-theme'));
  };

  const initToggles = () => {
    document.querySelectorAll('.theme-toggle').forEach(setupToggleButton);
    updateToggleButtons(root.getAttribute('data-theme'));
  };

  document.addEventListener('DOMContentLoaded', () => {
    initTheme();
    initToggles();
  });

  window.LibraryTheme = {
    refreshToggles() {
      initToggles();
    },
    setTheme(theme) {
      applyTheme(theme);
    },
  };

  // Apply theme as early as possible
  initTheme();
})();
