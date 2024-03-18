
$(document).ready(function(){

  $(".fecha").inputmask({"mask":"99/99/9999",positionCaretOnTab : false});
  $(".año").inputmask({"mask":"9999",positionCaretOnTab : false});
});

$('body').keydown(function(){
  if (event.keyCode == 88) {
    $(".bhoechie-tab-content:visible").find(".excel").click();
  }
});
