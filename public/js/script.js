document.addEventListener('DOMContentLoaded', function () {
    // Get all nav links
    const navLinks = document.querySelectorAll('.nav-link');

    // Get the current URL path (remove trailing slashes for consistency)
    const currentPath = window.location.pathname.replace(/\/+$/, '');

    // Debugging: Log the current path to verify
    console.log('Current Path:', currentPath);

    navLinks.forEach(link => {
        try {
            // Get the href attribute of the link and extract pathname
            const linkHref = link.getAttribute('href');
            if (!linkHref) {
                console.warn('Nav link has no href:', link);
                return;
            }

            // Handle relative and absolute URLs
            let linkPath;
            try {
                linkPath = new URL(linkHref, window.location.origin).pathname.replace(/\/+$/, '');
            } catch (e) {
                console.warn('Invalid URL for link:', linkHref, e);
                linkPath = linkHref.replace(/\/+$/, '');
            }

            // Debugging: Log each link's path
            console.log('Link Path:', linkPath, 'Comparing with:', currentPath);

            // Check if the link's path matches the current path
            if (currentPath === linkPath || (currentPath === '' && linkPath === '/')) {
                link.classList.add('active');
                console.log('Active link set:', linkPath);
            } else {
                link.classList.remove('active');
            }
        } catch (error) {
            console.error('Error processing nav link:', link, error);
        }
    });
});


  document.addEventListener('DOMContentLoaded', function() {
    // Ripple effect
    document.querySelectorAll('.nav-link').forEach(link => {
      link.addEventListener('click', function(e) {
        const ripple = document.createElement('span');
        ripple.classList.add('ripple');
        this.appendChild(ripple);
        
        const x = e.clientX - e.target.getBoundingClientRect().left;
        const y = e.clientY - e.target.getBoundingClientRect().top;
        
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';
        
        setTimeout(() => {
          ripple.remove();
        }, 800);
      });
    });
    
    // Scroll effect
    window.addEventListener('scroll', function() {
      const navbar = document.querySelector('.navbar');
      if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
      } else {
        navbar.classList.remove('scrolled');
      }
    });
    
    // Hover 3D effect
    document.querySelectorAll('.nav-link').forEach(link => {
      link.addEventListener('mousemove', function(e) {
        const rect = this.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        
        const centerX = rect.width / 2;
        const centerY = rect.height / 2;
        
        const angleX = (y - centerY) / 10;
        const angleY = (centerX - x) / 10;
        
        this.style.transform = `perspective(1000px) rotateX(${angleX}deg) rotateY(${angleY}deg) translateZ(20px)`;
      });
      
      link.addEventListener('mouseleave', function() {
        this.style.transform = '';
      });
    });
  });
  

