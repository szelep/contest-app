$(document).ready(function(){
    var flashCount = $('.flash-container').children();

})

$(window).on('click', '[data-send="ajax"]', function (e){
    e.preventDefault();
    console.log('e');
})