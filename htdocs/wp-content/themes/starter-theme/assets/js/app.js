/**
 * Theme JS building
 */
import '@babel/polyfill';
import './modernizr';

// Import Foundation JS.
import Foundation from 'foundation-sites';

// Require main style file here for concatenation.
import '../scss/app.scss';

// Import modules.
import './module-foundation.js';
