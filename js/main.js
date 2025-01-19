
const techinfo = document.querySelector('.teacher');
const role = document.querySelector('.role');


role.addEventListener('change',()=> {

    if(role.value == 2){
        techinfo.innerHTML = `<div class="form-group">
                                        <select name="specialty" class="custom-select border-0 px-4" id="specialty" style="height: 47px;">
                                            <option selected>Your Specialty</option>
                                            <option value="1">Programming</option>
                                            <option value="2">Data Science</option>
                                            <option value="3">Digital Marketing</option>
                                            <option value="4">Graphic Design</option>
                                            <option value="5">Music Production</option>
                                            <option value="6">Fitness & Nutrition</option>
                                            <option value="7">Photography</option>
                                            <option value="8">Business Management</option>
                                            <option value="9">Language Learning</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <textarea class="form-control border-0 p-4" id="description" placeholder="Leave a brief description about your self" name='description'></textarea>
                                    </div>`;
    }else {
        techinfo.classList.add('d-none');
        document.querySelector('.teacher').innerHTML = " "
    }
    return;
})







(function ($) {
    "use strict";
    
    // Dropdown on mouse hover
    $(document).ready(function () {
        function toggleNavbarMethod() {
            if ($(window).width() > 992) {
                $('.navbar .dropdown').on('mouseover', function () {
                    $('.dropdown-toggle', this).trigger('click');
                }).on('mouseout', function () {
                    $('.dropdown-toggle', this).trigger('click').blur();
                });
            } else {
                $('.navbar .dropdown').off('mouseover').off('mouseout');
            }
        }
        toggleNavbarMethod();
        $(window).resize(toggleNavbarMethod);
    });
    
    
    // Back to top button
    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            $('.back-to-top').fadeIn('slow');
        } else {
            $('.back-to-top').fadeOut('slow');
        }
    });
    $('.back-to-top').click(function () {
        $('html, body').animate({scrollTop: 0}, 1500, 'easeInOutExpo');
        return false;
    });


    // Testimonials carousel
    $(".testimonial-carousel").owlCarousel({
        autoplay: true,
        smartSpeed: 1500,
        dots: true,
        loop: true,
        items: 1
    });
    
})(jQuery);

