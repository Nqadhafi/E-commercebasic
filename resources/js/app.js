import './bootstrap';
import 'bootstrap';

// Import jQuery
try {
    window.$ = window.jQuery = require('jquery');
} catch (e) {
    console.error('jQuery not found:', e);
}

// Import Bootstrap JavaScript
try {
    require('bootstrap/dist/js/bootstrap.bundle.min.js');
} catch (e) {
    console.error('Bootstrap JS not found:', e);
}