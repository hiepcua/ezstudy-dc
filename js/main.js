function toggleFullScreen() {
    var a = $(window).height() - 10;

    if (!document.fullscreenElement && // alternative standard method
        !document.mozFullScreenElement && !document.webkitFullscreenElement) { // current working methods
        if (document.documentElement.requestFullscreen) {
            document.documentElement.requestFullscreen();
        } else if (document.documentElement.mozRequestFullScreen) {
            document.documentElement.mozRequestFullScreen();
        } else if (document.documentElement.webkitRequestFullscreen) {
            document.documentElement.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
        }
    } else {
        if (document.cancelFullScreen) {
            document.cancelFullScreen();
        } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        } else if (document.webkitCancelFullScreen) {
            document.webkitCancelFullScreen();
        }
    }
}
function refreshParent() {
  window.opener.location.href = window.opener.location.href;

  if (window.opener.progressWindow){
    window.opener.progressWindow.close()
  }
  window.close();
}
function printPage() {
	window.print();
}
function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}

function slider_prohot(margin){
    var owl = $("#slider_prohot");
    owl.owlCarousel({
        margin:20,
        responsive:{
            0:{ items:2},
            600:{ items:3},
            1000:{ items:4},
            1200:{ items:6}
        },
        pagination: false,
        nav: true,
        dots: false

    });
}
