jQuery(document).ready(function($) {
    $('.sfb-faq-question').on('click', function() {
        const $item = $(this).parent();
        const $answer = $(this).next('.sfb-faq-answer');
        
        // Close other items
        $('.sfb-faq-item').not($item).removeClass('active');
        
        // Toggle current item
        $item.toggleClass('active');
        
        // Smooth scroll to the clicked question
        if ($item.hasClass('active')) {
            $('html, body').animate({
                scrollTop: $item.offset().top - 100
            }, 300);
        }
    });
}); 