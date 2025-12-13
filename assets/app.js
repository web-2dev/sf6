import './bootstrap.js';
import * as Popper from "@popperjs/core"
// import zoomPlugin from 'chartjs-plugin-zoom';  //chart.js
import Chart from 'chart.js';
console.log("chart", Chart);

// import "./vendor/bootstrap/bootstrap.index.js";
/* après le from, on met l'arborescence vers le dossier du fichier js à partir du dossier ./vendor */
import * as bootstrap from 'bootstrap';
globalThis.bootstrap = bootstrap;

import $ from "jquery";
globalThis.$ = globalThis.jQuery = $;


// CSS
import "bootstrap/dist/css/bootstrap.min.css";
import "@fortawesome/fontawesome-free/css/all.css";
import './styles/app.scss';

console.log('%c assets/app.js🎉', "color: cyan; text-decoration: underline; background: #222;");

 