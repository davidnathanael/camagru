var girls = document.getElementById('girls-radio');
var hair = document.getElementById('hair-radio');
var mustache = document.getElementById('mustache-radio');
var rainbow = document.getElementById('rainbow-radio');
var startbutton = document.getElementById('startbutton');

girls.addEventListener('click', function(e){
    startbutton.removeAttribute('disabled');
    startbutton.textContent='Take Photo';
});

hair.addEventListener('click', function(e){
    startbutton.removeAttribute('disabled');
    startbutton.textContent='Take Photo';
});

mustache.addEventListener('click', function(e){
    startbutton.removeAttribute('disabled');
    startbutton.textContent='Take Photo';
});

rainbow.addEventListener('click', function(e){
    startbutton.removeAttribute('disabled');
    startbutton.textContent='Take Photo';
});
