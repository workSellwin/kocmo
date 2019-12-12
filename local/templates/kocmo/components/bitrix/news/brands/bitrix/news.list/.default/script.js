"use strict";

document.addEventListener('DOMContentLoaded', pageStart);

function pageStart() {
    let disabledLetterNodes = document.querySelectorAll('.letter-disabled');

    if (disabledLetterNodes.length) {

        for (let i = 0; i < disabledLetterNodes.length; i++) {
            disabledLetterNodes[i].addEventListener('click', function (event) {
                event.preventDefault();
            })
        }
    }
}