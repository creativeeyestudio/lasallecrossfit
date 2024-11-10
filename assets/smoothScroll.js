import Scrollbar from 'smooth-scrollbar';
import AOS from 'aos';

export class ScrollWeb {

    constructor(damping){
        this.damping = damping
    }

    get init(){
        const container = document.querySelector('#content');
        const scrollbar = Scrollbar.init(container, {
            damping: (this.damping / 100),
            renderByPixels: true,
            continuousScrolling: true,
            delegateTo: document,
            thumbMinSize: 15
        });

        scrollbar.track.xAxis.element.remove();

        AOS.init({
            duration: 1000,
            delay: 200,
            disable: window.innerWidth < 1200,
        });
      
        [].forEach.call(document.querySelectorAll('[data-aos]'), (el) => {
          scrollbar.addListener(() => {
            if (scrollbar.isVisible(el)) {
              el.classList.add('aos-animate');
            } else {
              el.classList.remove('aos-animate');
            }
          });
        });

        // Détection du Scroll
        const mainNavLinks = document.querySelectorAll('.main-nav li a[href^="#"]');
        scrollbar.addListener(function({ offset }){
            const scrollY = scrollbar.offset.y;

            if (scrollY > 50) {
                document.querySelector('html').classList.add('onScroll');
            } else {
                document.querySelector('html').classList.remove('onScroll');
            }

            // Mettez en évidence le lien de navigation correspondant à la section actuelle
            const currentSection = findCurrentSection(offset.y);
            mainNavLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href').slice(1) === currentSection) {
                    link.classList.add('active');
                }
            });
        })
          console.log('desktop');

        function findCurrentSection(scrollY) {
            let currentSection = '';
          
            // Parcourez les sections et déterminez la section actuelle
            for (const section of document.querySelectorAll('section')) {
              const sectionTop = section.offsetTop - 100;
              const sectionHeight = section.clientHeight;
          
              if (scrollY >= sectionTop && scrollY < sectionTop + sectionHeight) {
                currentSection = section.id;
              }
            }
          
            return currentSection;
          }

        // Scroll au click d'une ancre
        const navLinks = document.querySelectorAll('a[href^="#"]');
        navLinks.forEach(btn => {
            btn.addEventListener('click', function(){
                const margin = 0;
                const target = btn.getAttribute('href') || btn.getAttribute('data-link');
                const anchor = document.querySelector(target);
                const offset = container.getBoundingClientRect().top - anchor.getBoundingClientRect().top;
                scrollbar.scrollIntoView(anchor, { 
                    offset, 
                    offsetTop: margin
                });
                return false;
            })
        })

        return scrollbar;
    }

    scrollMobile() {
      const links = document.querySelectorAll('a[href*="#"]');

      links.forEach(link => {
        link.addEventListener('click', smoothScroll);
      });

      function smoothScroll(e) {
        e.preventDefault();
        const targetId = this.getAttribute('href').substring(1);
        const targetElement = document.getElementById(targetId);

        if (targetElement) {
          const offsetTop = targetElement.offsetTop;
          window.scrollTo({
            top: offsetTop,
            behavior: 'smooth'
          })
        } else {
          console.log("pas de target");
        }
      }
    }
}