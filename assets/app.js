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

 
document.addEventListener("DOMContentLoaded", function(evt) {
    document.querySelector(".adapt").addEventListener("click", (e) => {
        // const img = e.target;
        const img =  document.querySelector(".adapt");
        if( img.classList.length == 1 ) {
            img.classList.add("tranche-livre");
            console.log("tranche-livre");
        } else if( img.classList.contains("tranche-livre") ) {
            img.classList.remove("tranche-livre");
            img.classList.add("couverture-ancienne");
            console.log("couverture-ancienne");
        } else if( img.classList.contains("couverture-ancienne") ) {
            img.classList.remove("couverture-ancienne");
            img.classList.add("livre-relie");
            console.log("livre-relie");
        } else if( img.classList.contains("livre-relie") ) {
            img.classList.remove("livre-relie");
            img.classList.add("ancien");
            console.log("ancien");
        } else if( img.classList.contains("ancien") ) {
            img.classList.remove("ancien");
            img.classList.add("effet-papier");
            console.log("effet-papier");
        } else if( img.classList.contains("effet-papier") ) {
            img.classList.remove("effet-papier");
        }
    })
})