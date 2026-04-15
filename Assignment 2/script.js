$(document).ready(function() {
    console.log("VIT Project Relevance Hub Initialized");

    // Function to handle counter animation
    const animateCounters = () => {
        $('.counter').each(function() {
            const $this = $(this);
            const target = +$this.data('target');
            const count = +$this.text();
            
            // If already at target, skip
            if (count === target) return;

            const increment = target / 50; // Speed control

            const updateCount = () => {
                const current = +$this.text();
                if (current < target) {
                    $this.text(Math.ceil(current + increment));
                    setTimeout(updateCount, 20);
                } else {
                    $this.text(target + '%');
                }
            };
            updateCount();
        });
    };

    // Trigger counter on load since SDP is active by default
    animateCounters();

    // jQuery Tab Switch Event
    $('button[data-bs-toggle="pill"]').on('shown.bs.tab', function (e) {
        const targetId = $(e.target).attr('data-bs-target');
        console.log("Switched to: " + targetId);

        // Re-trigger counter if SDP tab is selected
        if(targetId === '#pills-sdp') {
            $('.counter').text('0');
            animateCounters();
        }

        // Simple scale effect on content reveal using jQuery
        $(targetId).hide().fadeIn(600);
    });

    // Dynamic Title Hover Effect
    $('#main-title').hover(
        function() {
            $(this).css('letter-spacing', '2px');
            $(this).addClass('accent-text');
        }, 
        function() {
            $(this).css('letter-spacing', 'normal');
        }
    );

    // Interactive Course Items
    $('.grid-item').on('click', function() {
        $(this).toggleClass('bg-accent text-white');
        alert("Exploring " + $(this).text().trim() + " relevance!");
    });
});
