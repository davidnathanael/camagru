var girls = document.getElementById('girls-radio');
var hair = document.getElementById('hair-radio');
var mustache = document.getElementById('mustache-radio');
var rainbow = document.getElementById('rainbow-radio');
var startbutton = document.getElementById('startbutton');

document.getElementById('girls-filter').addEventListener('click', function (e) {
    select_filter(this);
    girls.checked = true;
});

document.getElementById('hair-filter').addEventListener('click', function (e) {
    select_filter(this);
    hair.checked = true;
});

document.getElementById('mustache-filter').addEventListener('click', function (e) {
    select_filter(this);
    mustache.checked = true;
});

document.getElementById('rainbow-filter').addEventListener('click', function (e) {
    select_filter(this);
    rainbow.checked = true;
});

function select_filter(filter){
    document.getElementById('girls-filter').setAttribute('class', 'filter');
    document.getElementById('hair-filter').setAttribute('class', 'filter');
    document.getElementById('mustache-filter').setAttribute('class', 'filter');
    document.getElementById('rainbow-filter').setAttribute('class', 'filter');
    filter.setAttribute('class', 'filter selected-filter');
    startbutton.removeAttribute('disabled');
    startbutton.textContent='Take Photo';
}
