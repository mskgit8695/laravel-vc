var jQ = jQuery.noConflict();

jQ(document).ready(function () {
  jQ(".mob-menu-btn").on("click", function () {
    jQ(".left-section").addClass("moveLeft");
    jQ(this).hide();
    jQ(".close-btn").fadeIn();
  });
  jQ(".close-btn").on("click", function () {
    jQ(".left-section").removeClass("moveLeft");
    jQ(this).hide();
    jQ(".mob-menu-btn").fadeIn();
  });


  //This function For header-sticky
  // jQ(window).scroll(function () {
  //   if (jQ(this).scrollTop() >= 50) {
  //     jQ(".header").addClass("header-sticky");
  //   } else {
  //     jQ(".header").removeClass("header-sticky");
  //   }
  // });
});
