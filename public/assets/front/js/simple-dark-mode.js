// Simple Dark Mode Toggle
function toggleDarkMode() {
    const body = document.body;
    const icon = document.getElementById('dark-mode-icon');
    
    // Toggle dark mode class
    body.classList.toggle('dark-mode');
    
    // Update icon
    if (body.classList.contains('dark-mode')) {
        icon.className = 'fa fa-sun-o';
        localStorage.setItem('darkMode', 'enabled');
    } else {
        icon.className = 'fa fa-moon-o';
        localStorage.setItem('darkMode', 'disabled');
    }
}

// Check saved preference on page load
document.addEventListener('DOMContentLoaded', function() {
    const darkMode = localStorage.getItem('darkMode');
    const icon = document.getElementById('dark-mode-icon');
    
    // Default to dark mode if no preference is saved
    if (darkMode === 'disabled') {
        // User explicitly chose light mode
        icon.className = 'fa fa-moon-o';
    } else {
        // Default dark mode (first visit or user chose dark)
        document.body.classList.add('dark-mode');
        icon.className = 'fa fa-sun-o';
        // Set localStorage if it's the first visit
        if (!darkMode) {
            localStorage.setItem('darkMode', 'enabled');
        }
    }
});