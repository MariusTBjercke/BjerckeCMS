import {DOMReady} from "@assets/js/shared/domready";

DOMReady(() => {
    if (document.body.classList.contains('preload')) {
        document.body.classList.remove('preload');
    }
});