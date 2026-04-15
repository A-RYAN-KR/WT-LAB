$(document).ready(function() {
    // Subtle fade-in animation for glass containers on load
    $(".glass-container").each(function(index) {
        $(this).css({
            'opacity': '0',
            'transform': 'translateY(20px)'
        }).delay(200 * index).animate({
            'opacity': '1',
            'transform': 'translateY(0)'
        }, {
            step: function(now, fx) {
                if (fx.prop === "transform") {
                    $(this).css('transform', 'translateY(' + (20 * (1 - now)) + 'px)');
                }
            },
            duration: 800,
            easing: 'swing'
        });
    });

    // Hover effect for skill badges
    $(".skill-badge").on("mouseenter", function() {
        $(this).animate({
            paddingLeft: '1.5rem',
            paddingRight: '1.5rem'
        }, 200);
    }).on("mouseleave", function() {
        $(this).animate({
            paddingLeft: '1.2rem',
            paddingRight: '1.2rem'
        }, 200);
    });

    // Smooth scroll for internal links (if any)
    $('a[href^="#"]').on('click', function(event) {
        var target = $(this.getAttribute('href'));
        if (target.length) {
            event.preventDefault();
            $('html, body').stop().animate({
                scrollTop: target.offset().top - 30
            }, 1000);
        }
    });

    // Parallax-ish effect for video background overlay
    $(window).on('scroll', function() {
        var scroll = $(window).scrollTop();
        $('.overlay').css({
            'opacity': 0.4 + (scroll / 2000)
        });
    });

    console.log("Interactive CV loaded successfully!");
});
