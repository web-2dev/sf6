/** 
 * 💬 COURS                                                             
 * Il faut éviter d'appeler 2 fois la fonction Twig importmap().
 * Donc pour 
 * */
import "./app.js";

/* 
💬 COURS                                                                
    Cette instruction va permettre la création du fichier gestion.css */
import './styles/gestion.scss';


/** 
 * Fonction pour  l'affichage de l'image juste après le téléchargement depuis un input file 
 * Déclencher cette fonction dans l'écouteur d'évènement "change" des inputs
 * @param inputElement  :   Element jQuery (input type file)
 * @param imgElement    :   Element jQuery (img tag)
*/
let dataSrcUploadedImage = (inputElement, imgElement) => {  // version narrow function
    if (inputElement.files && inputElement.files[0]) {
        var reader = new FileReader();
        var data;
        reader.onload = function (e) {
            $(imgElement).prop("src", e.target.result);
            // ❗ fix Bootstrap 4 File type input doesn't display name of uploaded file
            $(inputElement).next('.custom-file-label').html(inputElement.files[0].name);
        };
        reader.readAsDataURL(inputElement.files[0]);
    }
}

/* Comme jQuery a été intégré, on utilise l'évènement ready de jQuery pour ajouter le code JS */
$(function(){
    console.log('%c gestion.js ', 'background: #222; color: #bada55');

    // $('[data-toggle="popover"]').popover();
    // tables
    $("table").addClass("table");
    $("table.table").addClass("table-secondary table-bordered table-hover");
    
    // forms
    $("[type='file']").on("change", function(){
        var id = $(this).prop("id");            // je récu)ère l'identifiant de l'input
        var label = $("[for='" + id + "']");    // le label lié à l'input à l'attribut 'for' qui vaut l'id de l'input
        label.append("<img class='mini ml-3'id='" + id + "img' >");  // j'ajoute une balise 'img'à ce label
        dataSrcUploadedImage(this, $('#' + id + 'img'));
    });

    // let main = document.querySelector("main");
    // let hauteurAvant = main.clientHeight;
    // main.style.height = "calc(100vh - 60px)";
    // if(hauteurAvant > main.clientHeight) {
    //     main.style.height = hauteurAvant + "px";
    // }
});