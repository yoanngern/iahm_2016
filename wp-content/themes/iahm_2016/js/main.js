// @codekit-prepend "vendor/jquery-2.2.2.js"

jQuery(document).ready(function () {

    $(document).scroll(function () {

        var pos = $(document).scrollTop();

        //console.log(pos);

        //var slideheader = $("section.slideheader").height();

        var start = 60;

        if(pos >= start) {
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
});
