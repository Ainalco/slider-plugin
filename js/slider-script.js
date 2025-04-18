jQuery(document).ready(function($) {
    $('.custom-slider').slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay: true,
        dots: true,
        arrows: true,
        responsive: [
            {
                breakpoint: 768,
                settings: { slidesToShow: 1 }
            },
            {
                breakpoint: 1024,
                settings: { slidesToShow: 2 }
            }
        ]
    });
});
