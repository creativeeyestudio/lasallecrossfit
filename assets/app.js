/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/web/app.scss';

// start the Stimulus application
import './bootstrap';

import { ScrollWeb } from './smoothScroll';
import { Parallax } from './parallax';
import { createApp } from 'vue';
import LastPosts from './vue/controllers/LastPosts';
import Contact from './vue/controllers/Contact';


// Variables
// -----------------------------------------------
const pageDatas = document.querySelector('body');
const values = {
    damping: pageDatas.dataset.damping,
    scrollImgSpeed: pageDatas.dataset.scrollimg
};


// Instantieur
// -----------------------------------------------
document.addEventListener('DOMContentLoaded', function () {
    createApp({
        components: { LastPosts, Contact }
    }).mount('#website');
    scrollWeb();
    parallax();
});


// Smooth Scrollbar
// -----------------------------------------------
function scrollWeb() {
    const scrollWeb = new ScrollWeb(values.damping);
    if (screen.width > 1200) {
        scrollWeb.init;
        console.log("Desktop");
    } else {
        scrollWeb.scrollMobile();
        console.log("Mobile");
    }

    return scrollWeb;
}


// Parallax
// -----------------------------------------------
function parallax() {
    const parallax = new Parallax(values.damping, values.scrollImgSpeed);
    parallax.initParallax();
    return parallax;
}


// Menu Nav
// -----------------------------------------------
var htmlContent = document.querySelector('html');

var navBtn = document.querySelectorAll('.toggle-nav');
navBtn.forEach(btn => {
    btn.addEventListener('click', function () {
        htmlContent.classList.toggle('nav-open');
    });
});

var navLink = document.querySelectorAll('a[href^="#"]');
navLink.forEach(link => {
    link.addEventListener('click', function () {
        htmlContent.classList.remove('nav-open');
    });
});


// Popup
// -----------------------------------------------
var closePopup = document.querySelector('.close-popup');
var popup = document.querySelector('.popup');

if (popup != undefined && closePopup != undefined) {
    closePopup.addEventListener('click', () => {
        popup.classList.add('removed');
    });
}


// Loader Site
// -----------------------------------------------
document.addEventListener('DOMContentLoaded', function () {
    function closeLoader() {
        document.querySelector('.loader').classList.add('open');
    }

    setTimeout(closeLoader, 4000);
})