// @codekit-prepend "vendor/jquery-2.2.2.js"
// @codekit-prepend "vendor/jquery.slides.min.js"

jQuery(document).ready(function () {


    $("body > header").on("click", "#burger", function (event) {
        event.preventDefault();
        $("body > header ul").toggleClass("show");
    });

    $("body #subnav").on("click", "#toggle", function (event) {
        event.preventDefault();
        $("body #subnav #toggle").toggleClass("open");
        $("body #subnav ul").toggleClass("show");
        $("body #subnav").toggleClass("blur");
    });



    $(document).scroll(function () {

        var pos = $(document).scrollTop();

        //console.log(pos);

        //var slideheader = $("section.slideheader").height();

        var start = 60;

        if (pos >= start) {
            $("#subnav").addClass("fixed");
        } else {
            $("#subnav").removeClass("fixed");
        }

        /*


         if (pos >= start && pos <= (start + 68)) {
         header((pos - start) / 0.68);
         } else if (pos >= (start + 68)) {
         header(100);
         } else if (pos <= start) {
         header(0);
         }

         if (pos >= 0 && pos <= 200) {
         $("div.logowhite").css("opacity", (-Math.pow((pos / 2), 2)) / 10000 + 1);
         } else if (pos >= 200) {
         $("div.logowhite").css("opacity", 0);
         } else if (pos <= start) {
         $("div.logowhite").css("opacity", 1);
         }

         */
    });


    $(".slidesjs").each(function () {

        var slider = $(this);

        var option = {
            width: 1920,
            height: slider.data("height"),
            navigation: {
                active: false
            },
            pagination: {
                active: false
            }
        };

        if (slider.data("size") != 1) {

            option.navigation = {
                active: true,
                effect: "fade"
            };

            option.pagination = {
                active: true,
                effect: "fade"
            };

            if (!slider.data("nav")) {
                option.navigation = {
                    active: false
                };
            }

            if (!slider.data("pag")) {
                option.pagination = {
                    active: false
                };
            }

            option.effect = {
                fade: {
                    speed: 200
                }
            };

            option.play = {
                effect: "fade",
                interval: 4000,
                auto: true,
                swap: true,
                restartDelay: 2500
            };
        }

        slider.slidesjs(option);
    });
});
