/*
 *   Beetle
 *   Written by Pasquale Vitiello (pasqualevitiello@gmail.com),
 *   mokaine.com
 */

jQuery(document).ready(function ($) {

       /* Define some vars */

    var win = $(window),
        testimonial = $('.testimonial-slider'),
        cCarousel = $('.custom-carousel'),
        moreBtnIcon = '<div class="more"><a href="#main"><i class="icon icon-arrow-down"></i></a></div>';

    /* Milestone */

    var countItem = $('.count-item');

    function milestone() {

        countItem.each(function () {

            var $this = $(this);

            $this.onScreen({
                doIn: function () {
                    var countNumber = $this.find('.count-number'),
                        countTitle = $this.find('.count-subject');
                    countNumber.countTo({
                        onComplete: function () {
                            countTitle.delay(100).addClass('subject-on');
                            countNumber.removeClass('count-number').addClass('count-number-done');
                        }
                    });
                },
            });

        });
    }

    if (countItem.length) {

        milestone();

    }

    /* Testimonial Carousel */

    function initTestimonial() {

        testimonial.each(function () {

            var $this = $(this),
                autoplay = $this.data('autoplay'),
                pagination = $this.data('pagination'),
                transition = $this.data('transition'),
                autoheight = $this.data('autoheight');

            $this.owlCarousel({
                singleItem: true,
                autoPlay: autoplay || false,
                transitionStyle: transition || false,
                autoHeight: autoheight || false,
                stopOnHover: true,
                responsiveBaseWidth: ".slider",
                responsiveRefreshRate: 0,
                addClassActive: true,
                pagination: pagination || false,
                rewindSpeed: 2000,
            });

        });

    }

    if (testimonial.length) {

        initTestimonial();

    }

    /* Custom Carousel */

     function initCCarousel() {

         cCarousel.each(function () {

             var $this = $(this),
                 autoplay = $this.data('autoplay'),
                 pagination = $this.data('pagination'),
                 transition = $this.data('transition'),
                 autoheight = $this.data('autoheight');

             $this.owlCarousel({
                 singleItem: true,
                 autoPlay: autoplay || false,
                 transitionStyle: transition || false,
                 autoHeight: autoheight || false,
                 stopOnHover: true,
                 responsiveBaseWidth: ".slider",
                 responsiveRefreshRate: 0,
                 addClassActive: true,
                 pagination: pagination || false,
                 rewindSpeed: 2000,
           });
        });

    }

    if (cCarousel.length) {

         initCCarousel();

   }

});