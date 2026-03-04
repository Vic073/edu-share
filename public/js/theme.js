/**
 * EduShare Theme Toggle
 * Handles dark/light mode switching with localStorage persistence
 * and OS preference detection.
 */
(function() {
    'use strict';

    const STORAGE_KEY = 'edushare-theme';
    const DARK = 'dark';
    const LIGHT = 'light';

    function getPreferredTheme() {
        const stored = localStorage.getItem(STORAGE_KEY);
        if (stored) return stored;
        return window.matchMedia('(prefers-color-scheme: light)').matches ? LIGHT : DARK;
    }

    function setTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem(STORAGE_KEY, theme);
        updateToggleIcons(theme);
    }

    function updateToggleIcons(theme) {
        document.querySelectorAll('.theme-toggle').forEach(function(btn) {
            var icon = btn.querySelector('i');
            if (icon) {
                icon.className = theme === DARK ? 'fas fa-sun' : 'fas fa-moon';
            }
        });
    }

    function toggleTheme() {
        var current = document.documentElement.getAttribute('data-theme') || DARK;
        setTheme(current === DARK ? LIGHT : DARK);
    }

    // Apply theme immediately to prevent flash
    setTheme(getPreferredTheme());

    // Bind toggle buttons once DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        updateToggleIcons(document.documentElement.getAttribute('data-theme') || DARK);

        document.querySelectorAll('.theme-toggle').forEach(function(btn) {
            btn.addEventListener('click', toggleTheme);
        });
    });

    // Listen for OS theme changes
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
        if (!localStorage.getItem(STORAGE_KEY)) {
            setTheme(e.matches ? DARK : LIGHT);
        }
    });

    // Expose for external use
    window.EduShareTheme = { toggle: toggleTheme, set: setTheme };
})();
