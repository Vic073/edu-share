/**
 * EduShare – Production-ready JavaScript
 * Replaces Vue 2 dependency with clean ES6+
 */

// ============================================
// Theme Management (FOUC prevention + toggle)
// ============================================
class ThemeManager {
    constructor() {
        this.theme = localStorage.getItem('edushare-theme') ||
            (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        this.apply(this.theme);
        this.bindToggle();

        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
            if (!localStorage.getItem('edushare-theme')) {
                this.apply(e.matches ? 'dark' : 'light');
            }
        });
    }

    apply(theme) {
        this.theme = theme;
        document.documentElement.classList.toggle('dark', theme === 'dark');
        this.updateToggleUI();
    }

    toggle() {
        const next = this.theme === 'dark' ? 'light' : 'dark';
        localStorage.setItem('edushare-theme', next);
        this.apply(next);
    }

    updateToggleUI() {
        const btn = document.getElementById('theme-toggle');
        if (!btn) return;
        const icon = btn.querySelector('i');
        if (!icon) return;
        icon.className = `fas text-xs ${this.theme === 'dark' ? 'fa-moon' : 'fa-sun'}`;
    }

    bindToggle() {
        document.addEventListener('DOMContentLoaded', () => {
            const btn = document.getElementById('theme-toggle');
            if (btn) btn.addEventListener('click', () => this.toggle());
            this.updateToggleUI();
        });
    }
}

// Expose globally and run immediately
window.themeManager = new ThemeManager();

// ============================================
// File Upload with Drag & Drop
// ============================================
class FileUploader {
    constructor(opts = {}) {
        this.dropzone = document.getElementById(opts.dropzoneId || 'dropzone');
        this.fileInput = document.getElementById(opts.fileInputId || 'fileInput');
        this.previewArea = document.getElementById(opts.previewAreaId || 'filePreviewArea');
        this.fileList = document.getElementById(opts.fileListId || 'fileList');
        this.maxSize = opts.maxSize || 50 * 1024 * 1024;

        if (this.dropzone && this.fileInput) this.init();
    }

    init() {
        this.dropzone.addEventListener('click', () => this.fileInput.click());

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(ev => {
            this.dropzone.addEventListener(ev, e => { e.preventDefault(); e.stopPropagation(); });
        });
        ['dragenter', 'dragover'].forEach(ev =>
            this.dropzone.addEventListener(ev, () => this.dropzone.classList.add('dropzone-active'))
        );
        ['dragleave', 'drop'].forEach(ev =>
            this.dropzone.addEventListener(ev, () => this.dropzone.classList.remove('dropzone-active'))
        );

        this.dropzone.addEventListener('drop', e => this.handleFiles(e.dataTransfer.files));
        this.fileInput.addEventListener('change', e => this.handleFiles(e.target.files));
    }

    handleFiles(files) {
        if (!files.length) return;
        const file = files[0];
        const ext = file.name.split('.').pop().toLowerCase();
        const allowed = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'txt', 'zip'];

        if (!allowed.includes(ext)) {
            this.toast('Invalid file type. Allowed: PDF, DOC, DOCX, PPT, PPTX, TXT, ZIP', 'error');
            return;
        }
        if (file.size > this.maxSize) {
            this.toast(`File too large. Max ${this.fmtSize(this.maxSize)}`, 'error');
            return;
        }
        this.showPreview(file);
    }

    showPreview(file) {
        if (!this.previewArea || !this.fileList) return;
        this.previewArea.classList.remove('hidden');
        const icon = this.getIcon(file.name);
        const size = this.fmtSize(file.size);
        this.fileList.innerHTML = `
            <div class="flex items-center justify-between p-3 bg-dark-50 dark:bg-dark-700 rounded-lg border border-dark-200 dark:border-dark-600">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-primary-100 dark:bg-primary-900/40 flex items-center justify-center">
                        <i class="fas fa-${icon} text-primary-600 dark:text-primary-400"></i>
                    </div>
                    <div>
                        <p class="font-medium text-dark-900 dark:text-dark-100 text-sm truncate max-w-[200px]">${file.name}</p>
                        <p class="text-xs text-dark-500 dark:text-dark-400">${size}</p>
                    </div>
                </div>
                <button type="button" class="text-dark-400 hover:text-red-500 transition-colors" onclick="window.fileUploader.removeFile()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
    }

    removeFile() {
        if (this.fileInput) this.fileInput.value = '';
        if (this.previewArea) this.previewArea.classList.add('hidden');
    }

    getIcon(name) {
        const e = name.split('.').pop().toLowerCase();
        return {
            pdf: 'file-pdf', doc: 'file-word', docx: 'file-word',
            ppt: 'file-powerpoint', pptx: 'file-powerpoint',
            txt: 'file-alt', zip: 'file-archive'
        }[e] || 'file';
    }

    fmtSize(b) {
        if (!b) return '0 B';
        const k = 1024, s = ['B', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(b) / Math.log(k));
        return (b / Math.pow(k, i)).toFixed(1) + ' ' + s[i];
    }

    toast(msg, type = 'info') { window.EduShare.showToast(msg, type); }
}

// ============================================
// AI Chat – connected to backend /ai/chat
// ============================================
class AIChat {
    constructor() {
        this.panel = document.getElementById('ai-chat-panel');
        this.messages = document.getElementById('chat-messages');
        this.input = document.getElementById('chat-input');
        this.sendBtn = document.getElementById('chat-send');
        this.isOpen = false;
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

        if (this.panel) this.init();
    }

    init() {
        document.getElementById('ai-chat-toggle')?.addEventListener('click', () => this.toggle());
        document.getElementById('chat-close')?.addEventListener('click', () => this.close());

        this.sendBtn?.addEventListener('click', () => this.send());
        this.input?.addEventListener('keypress', e => {
            if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); this.send(); }
        });
    }

    toggle() { this.isOpen ? this.close() : this.open(); }

    open() {
        this.panel.classList.remove('hidden');
        this.panel.classList.add('flex');
        this.isOpen = true;
        if (this.messages && !this.messages.children.length) {
            this.addMsg('Hello! I\'m your AI study assistant. Ask me anything about your materials or studies!', 'ai');
        }
        setTimeout(() => this.input?.focus(), 100);
    }

    close() {
        this.panel.classList.add('hidden');
        this.panel.classList.remove('flex');
        this.isOpen = false;
    }

    async send() {
        const text = this.input?.value.trim();
        if (!text) return;

        this.addMsg(text, 'user');
        this.input.value = '';
        this.showLoading();

        try {
            const res = await fetch('/ai/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ message: text }),
            });

            const data = await res.json();
            this.removeLoading();

            if (data.error) {
                this.addMsg(data.error, 'ai');
            } else {
                this.addMsg(data.response || 'I didn\'t get a response, please try again.', 'ai');
            }
        } catch (err) {
            this.removeLoading();
            this.addMsg('Network error. Please check your connection and try again.', 'ai');
        }
    }

    addMsg(text, sender) {
        if (!this.messages) return;
        const div = document.createElement('div');
        div.className = `chat-message chat-message-${sender}`;
        const icon = sender === 'ai'
            ? '<i class="fas fa-robot mr-2 text-primary-400"></i>'
            : '<i class="fas fa-user mr-2 opacity-70"></i>';
        div.innerHTML = icon + this.escapeHtml(text);
        this.messages.appendChild(div);
        this.messages.scrollTop = this.messages.scrollHeight;
    }

    escapeHtml(str) {
        return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    }

    showLoading() {
        const d = document.createElement('div');
        d.id = 'chat-loading';
        d.className = 'chat-message chat-message-ai flex items-center gap-2';
        d.innerHTML = '<span class="spinner"></span><span class="text-sm text-dark-500">Thinking…</span>';
        this.messages?.appendChild(d);
        if (this.messages) this.messages.scrollTop = this.messages.scrollHeight;
    }

    removeLoading() { document.getElementById('chat-loading')?.remove(); }
}

// ============================================
// Navbar scroll shadow
// ============================================
class NavbarManager {
    constructor() {
        this.el = document.querySelector('.navbar');
        if (this.el) {
            window.addEventListener('scroll', () => {
                this.el.classList.toggle('navbar-scrolled', window.scrollY > 20);
            }, { passive: true });
        }
    }
}

// ============================================
// Global utilities
// ============================================
window.EduShare = {
    showToast(message, type = 'info') {
        const colors = { success: 'bg-green-600', error: 'bg-red-600', warning: 'bg-amber-500', info: 'bg-blue-600' };
        const toast = document.createElement('div');
        toast.className = `fixed bottom-6 left-6 px-4 py-3 rounded-lg text-white text-sm font-medium shadow-lg z-[9999] animate-slide-up ${colors[type] || colors.info}`;
        toast.textContent = message;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3500);
    },

    confirm(message) { return confirm(message); },

    formatBytes(bytes) {
        if (!bytes) return '0 B';
        const k = 1024, s = ['B', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return (bytes / Math.pow(k, i)).toFixed(1) + ' ' + s[i];
    }
};

// ============================================
// DOM Ready – initialise everything
// ============================================
document.addEventListener('DOMContentLoaded', () => {
    // File uploader
    window.fileUploader = new FileUploader();

    // AI chat
    window.aiChat = new AIChat();

    // Navbar
    new NavbarManager();

    // Mobile menu
    const mobileBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    mobileBtn?.addEventListener('click', () => mobileMenu?.classList.toggle('hidden'));

    // User dropdown
    const userBtn = document.getElementById('user-menu-btn');
    const userDrop = document.getElementById('user-dropdown');
    userBtn?.addEventListener('click', e => { e.stopPropagation(); userDrop?.classList.toggle('hidden'); });
    document.addEventListener('click', () => userDrop?.classList.add('hidden'));

    // Flash message auto-dismiss
    setTimeout(() => {
        document.querySelectorAll('[data-auto-dismiss]').forEach(el => el.remove());
    }, 5000);
});
