// script.js - Complete Interactive Functionality for Mohamed Boudiaf High School Website

document.addEventListener('DOMContentLoaded', function() {
    const loadingScreen = document.querySelector('.loading-screen');
    setTimeout(() => {
        loadingScreen.classList.add('hidden');
    }, 1000);
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mainNav = document.getElementById('mainNav');
    
    mobileMenuBtn.addEventListener('click', function() {
        mainNav.classList.toggle('active');
        const icon = this.querySelector('i');
        icon.classList.toggle('fa-bars');
        icon.classList.toggle('fa-times');
    });
    document.querySelectorAll('nav ul li a').forEach(link => {
        link.addEventListener('click', () => {
            mainNav.classList.remove('active');
            mobileMenuBtn.querySelector('i').classList.add('fa-bars');
            mobileMenuBtn.querySelector('i').classList.remove('fa-times');
        });
    });
    const darkModeToggle = document.getElementById('darkModeToggle');
    const currentTheme = localStorage.getItem('theme') || 'light';
    
    if (currentTheme === 'dark') {
        document.documentElement.setAttribute('data-theme', 'dark');
    }
    darkModeToggle.addEventListener('click', function() {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        
        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
    });

    // ============= Back to Top Button =============
    const backToTopBtn = document.getElementById('backToTop');
    
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            backToTopBtn.classList.add('visible');
        } else {
            backToTopBtn.classList.remove('visible');
        }
    });
    window.addEventListener('load', function() {
        if (window.location.hash) {
            history.replaceState(null, null, ' ');
            window.scrollTo(0, 0);
        }
    });

    backToTopBtn.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
    const statNumbers = document.querySelectorAll('.stat-number');
    
    function animateStats() {
        statNumbers.forEach(stat => {
            const target = parseInt(stat.getAttribute('data-count'));
            const duration = 2000; 
            const step = target / (duration / 16); 
            
            let current = 0;
            const increment = () => {
                current += step;
                if (current < target) {
                    stat.textContent = Math.floor(current);
                    requestAnimationFrame(increment);
                } else {
                    stat.textContent = target;
                }
            };
            
            increment();
        });
    }
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateStats();
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    document.querySelectorAll('.stats-grid').forEach(grid => {
        observer.observe(grid);
    });
    /*const subjects = ['شعبة علوم تجريبية', 'شعبة آداب وفلسفة', 'شعبة تقني رياضي', 'شعبة تسير واقتصاد'];
    const grades = [13.2, 15.8, 12.7, 16.3];
    const colors = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e'];
    const gradesChart = document.getElementById('gradesChart');

    function createChart() {
        gradesChart.innerHTML = '';
        subjects.forEach((subject, index) => {
            const barContainer = document.createElement('div');
            barContainer.className = 'chart-bar-container';
            barContainer.style.width = `${100 / subjects.length}%`;
            
            const bar = document.createElement('div');
            bar.className = 'chart-bar';
            bar.style.backgroundColor = colors[index];
            
            // Calculate bar height (percentage of max grade)
            const heightPercentage = (grades[index] / 20) * 100;
            bar.style.height = '0%'; // Start at 0 for animation
            
            // Add grade label
            const gradeLabel = document.createElement('div');
            gradeLabel.className = 'chart-grade-label';
            gradeLabel.textContent = grades[index];
            
            // Add subject label
            const subjectLabel = document.createElement('div');
            subjectLabel.className = 'chart-subject-label';
            subjectLabel.textContent = subject;
            
            barContainer.appendChild(bar);
            barContainer.appendChild(gradeLabel);
            barContainer.appendChild(subjectLabel);
            gradesChart.appendChild(barContainer);
            
            // Animate bar growth
            setTimeout(() => {
                bar.style.height = `${heightPercentage}%`;
            }, index * 200);
            
            // Add hover effect
            barContainer.addEventListener('mouseenter', () => {
                bar.style.transform = 'scale(1.05)';
                gradeLabel.style.opacity = '1';
            });
            
            barContainer.addEventListener('mouseleave', () => {
                bar.style.transform = 'scale(1)';
                gradeLabel.style.opacity = '0.8';
            });
        });
    }*/

    // Initialize chart when in viewport
    const chartObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                createChart();
                chartObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    chartObserver.observe(gradesChart);
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 90, // Adjust for header height
                    behavior: 'smooth'
                });
            }
        });
    });
    document.querySelectorAll('.news-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.querySelector('.news-date').style.transform = 'scale(1.1)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.querySelector('.news-date').style.transform = 'scale(1)';
        });
    });

    document.querySelectorAll('.department-card').forEach((card, index) => {
        card.style.transitionDelay = `${index * 0.1}s`;
        
        // Pulse animation on hover
        card.addEventListener('mouseenter', function() {
            this.querySelector('.card-icon').style.transform = 'scale(1.1)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.querySelector('.card-icon').style.transform = 'scale(1)';
        });
    });
    const aboutImage = document.querySelector('.about-image');
    if (aboutImage) {
        const zoomBtn = aboutImage.querySelector('.zoom-btn');
        const modal = document.createElement('div');
        modal.className = 'image-modal';
        modal.innerHTML = `
            <div class="modal-content">
                <span class="close-modal">&times;</span>
                <img src="${aboutImage.querySelector('img').src}" alt="Enlarged school image">
            </div>
        `;
        document.body.appendChild(modal);
        
        zoomBtn.addEventListener('click', function() {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        });
        
        modal.querySelector('.close-modal').addEventListener('click', function() {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        });
        
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        });
    }

    // ============= Current Year in Footer =============
    document.querySelector('.footer-bottom p').innerHTML = 
        document.querySelector('.footer-bottom p').innerHTML.replace('2025', new Date().getFullYear());

    // ============= Add CSS for dynamically created elements =============
    const dynamicStyles = document.createElement('style');
    dynamicStyles.textContent = `
        .chart-bar-container {
            position: relative;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            align-items: center;
            padding: 0 10px;
        }
        
        .chart-bar {
            width: 60%;
            border-radius: 5px 5px 0 0;
            transition: all 0.3s ease;
        }
        
        .chart-grade-label {
            position: absolute;
            top: -25px;
            font-weight: bold;
            opacity: 0.8;
            transition: opacity 0.3s ease;
        }
        
        .chart-subject-label {
            margin-top: 10px;
            font-size: 0.8rem;
            text-align: center;
            color: var(--text-light);
        }
        
        .image-modal {
            display: none;
            position: fixed;
            top: 0;
            right: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            z-index: 1001;
            justify-content: center;
            align-items: center;
        }
        
        .modal-content {
            position: relative;
            max-width: 90%;
            max-height: 90%;
        }
        
        .modal-content img {
            max-width: 100%;
            max-height: 80vh;
            border-radius: 10px;
        }
        
        .close-modal {
            position: absolute;
            top: -40px;
            left: 0;
            color: white;
            font-size: 2rem;
            cursor: pointer;
        }
    `;
    document.head.appendChild(dynamicStyles);
});