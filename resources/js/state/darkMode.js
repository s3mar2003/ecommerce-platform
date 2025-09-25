const darkModeState = {
    isDark: false,
    
    init() {
        const savedMode = localStorage.getItem('dark-mode');
        const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        
        this.isDark = savedMode ? savedMode === 'true' : systemPrefersDark;
        
        if (this.isDark) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            if (!localStorage.getItem('dark-mode')) {
                this.isDark = e.matches;
                if (this.isDark) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            }
        });
    },
    
    toggle() {
        this.isDark = !this.isDark;
        localStorage.setItem('dark-mode', this.isDark.toString());
        
        if (this.isDark) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    }
};

darkModeState.init();

export default darkModeState;