document.addEventListener('DOMContentLoaded', function() {
    // --- Load saved settings on page load ---
    const savedTheme = localStorage.getItem('theme') || 'light';
    const savedColor = localStorage.getItem('color') || '#4e73df';
    
    document.documentElement.setAttribute('data-theme', savedTheme);
    document.documentElement.style.setProperty('--primary-color', savedColor);

    // --- Theme Toggle Logic ---
    const themeToggle = document.getElementById('theme-toggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', function() {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
        });
    }

    // --- Color Palette Logic ---
    const colorOptions = document.querySelectorAll('.color-option');
    colorOptions.forEach(option => {
        // Set active class for the currently saved color
        if (option.getAttribute('data-color') === savedColor) {
            option.classList.add('active');
        }

        option.addEventListener('click', function() {
            const newColor = this.getAttribute('data-color');
            
            document.documentElement.style.setProperty('--primary-color', newColor);
            localStorage.setItem('color', newColor);
            
            // Update active class
            colorOptions.forEach(opt => opt.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // --- Profile Popup Logic ---
    const profileIcon = document.getElementById('profileIcon');
    const profilePopup = document.getElementById('profilePopup');

    if (profileIcon && profilePopup) {
        profileIcon.addEventListener('click', function(e) {
            e.stopPropagation(); // Prevent event from bubbling up
            profilePopup.classList.toggle('show');
        });

        // Close popup if clicked outside
        document.addEventListener('click', function(e) {
            if (!profilePopup.contains(e.target)) {
                profilePopup.classList.remove('show');
            }
        });
    }

    // --- Auto-hide alert messages ---
    setTimeout(() => {
        const alert = document.querySelector('.alert');
        if (alert) {
            alert.style.display = 'none';
        }
    }, 5000);
});