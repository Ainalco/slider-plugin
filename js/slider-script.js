document.addEventListener('DOMContentLoaded', function () {
    const sliderWrappers = document.querySelectorAll('.custom-slider-wrapper');
  
    sliderWrappers.forEach(function (sliderWrapper) {
      const slider = sliderWrapper.querySelector('.custom-slider');
      const slides = slider.querySelectorAll('.slide');
      const prevBtn = sliderWrapper.querySelector('.prev');
      const nextBtn = sliderWrapper.querySelector('.next');
      const pagination = sliderWrapper.querySelector('.custom-slider-pagination');
  
      let currentIndex = 0;
      const totalSlides = slides.length;
      let autoSlideInterval;
      let startX = 0;
  
      // Create pagination dots
      slides.forEach((_, index) => {
        const dot = document.createElement('span');
        dot.classList.add('dot');
        if (index === 0) dot.classList.add('active');
        dot.addEventListener('click', () => {
          currentIndex = index;
          updateSlider();
          resetInterval();
        });
        pagination.appendChild(dot);
      });
  
      const dots = pagination.querySelectorAll('.dot');
  
      function updateSlider() {
        slides.forEach((slide, i) => {
          slide.classList.toggle('active', i === currentIndex);
          dots[i].classList.toggle('active', i === currentIndex);
        });
      }
  
      function showNextSlide() {
        currentIndex = (currentIndex + 1) % totalSlides;
        updateSlider();
      }
  
      function showPrevSlide() {
        currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
        updateSlider();
      }
  
      function resetInterval() {
        clearInterval(autoSlideInterval);
        autoSlideInterval = setInterval(showNextSlide, 5000);
      }
  
      // Touch support
      sliderWrapper.addEventListener('touchstart', (e) => {
        startX = e.touches[0].clientX;
      });
  
      sliderWrapper.addEventListener('touchend', (e) => {
        let endX = e.changedTouches[0].clientX;
        if (endX < startX - 50) showNextSlide();
        else if (endX > startX + 50) showPrevSlide();
        resetInterval();
      });
  
      // Controls
      prevBtn.addEventListener('click', () => {
        showPrevSlide();
        resetInterval();
      });
  
      nextBtn.addEventListener('click', () => {
        showNextSlide();
        resetInterval();
      });
  
      // Init
      updateSlider();
      resetInterval();
    });
  });
  