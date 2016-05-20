var girls = document.getElementById('girls-radio');
var hair = document.getElementById('hair-radio');
var mustache = document.getElementById('mustache-radio');
var rainbow = document.getElementById('rainbow-radio');
var startbutton = document.getElementById('startbutton');
var uploadbutton = document.getElementById('fileToUpload');
var live_girls = document.getElementById('live-filter-girls');
var live_hair = document.getElementById('live-filter-hair');
var live_mustache = document.getElementById('live-filter-mustache');
var live_rainbow = document.getElementById('live-filter-rainbow');
var top_input =  document.getElementById('top-move');
var left_input =  document.getElementById('left-move');
var width_input =  document.getElementById('added-width');

top_move = 0;
left_move = 0;
added_width = 0;

document.onkeydown = function(e) {
    if (e.keyCode == 82)
        reset_moving();
    if (e.keyCode == 37) {

        e.preventDefault();

        left_move--;
        left_input.value = left_move;

        live_girls.style.left = (parseInt(window.getComputedStyle(live_girls).left) - 1 + "px");
        live_hair.style.left = (parseInt(window.getComputedStyle(live_hair).left) - 1 + "px");
        live_mustache.style.left = (parseInt(window.getComputedStyle(live_mustache).left) - 1 + "px");
        live_rainbow.style.left = (parseInt(window.getComputedStyle(live_rainbow).left) - 1 + "px");
    }
    if (e.keyCode == 38) {
        e.preventDefault();

        top_move--;
        top_input.value = top_move;

        live_girls.style.top= (parseInt(window.getComputedStyle(live_girls).top) - 1 + "px");
        live_hair.style.top= (parseInt(window.getComputedStyle(live_hair).top) - 1 + "px");
        live_mustache.style.top = (parseInt(window.getComputedStyle(live_mustache).top) - 1 + "px");
        live_rainbow.style.top = (parseInt(window.getComputedStyle(live_rainbow).top) - 1 + "px");
    }
    if (e.keyCode == 39) {
        e.preventDefault();

        left_move++;
        left_input.value = left_move;

        live_girls.style.left = (parseInt(window.getComputedStyle(live_girls).left) + 1 + "px");
        live_hair.style.left = (parseInt(window.getComputedStyle(live_hair).left) + 1 + "px");
        live_mustache.style.left = (parseInt(window.getComputedStyle(live_mustache).left) + 1 + "px");
        live_rainbow.style.left = (parseInt(window.getComputedStyle(live_rainbow).left) + 1 + "px");
    }
    if (e.keyCode == 40) {
        e.preventDefault();

        top_move++;
        top_input.value = top_move;
        live_girls.style.top= (parseInt(window.getComputedStyle(live_girls).top) + 1 + "px");
        live_hair.style.top = (parseInt(window.getComputedStyle(live_hair).top) + 1 + "px");
        live_mustache.style.top = (parseInt(window.getComputedStyle(live_mustache).top) + 1 + "px");
        live_rainbow.style.top= (parseInt(window.getComputedStyle(live_rainbow).top) + 1 + "px");
    }
    if (e.keyCode == 107) {
        added_width++;

        width_input.value  = added_width;
        live_girls.style.width = (parseInt(window.getComputedStyle(live_girls).width) + 1 + "px");
        live_hair.style.width = (parseInt(window.getComputedStyle(live_hair).width) + 1 + "px");
        live_mustache.style.width = (parseInt(window.getComputedStyle(live_mustache).width) + 1 + "px");
        live_rainbow.style.width = (parseInt(window.getComputedStyle(live_rainbow).width) + 1 + "px");
    }
    if (e.keyCode == 109) {
        added_width--;

        width_input.value  = added_width;
        live_girls.style.width = (parseInt(window.getComputedStyle(live_girls).width) - 1 + "px");
        live_hair.style.width = (parseInt(window.getComputedStyle(live_hair).width) - 1 + "px");
        live_mustache.style.width = (parseInt(window.getComputedStyle(live_mustache).width) - 1 + "px");
        live_rainbow.style.width = (parseInt(window.getComputedStyle(live_rainbow).width) - 1 + "px");
    }
};

document.getElementById('girls-filter').addEventListener('click', function (e) {
    reset_girls();
    select_filter(this);
    live_girls.style.display = "initial";
    live_girls.style.width = "initial";
    girls.checked = true;
});

document.getElementById('hair-filter').addEventListener('click', function (e) {
    reset_hair();
    select_filter(this);
    live_hair.style.display = "initial";
    live_hair.style.width = "initial";
    hair.checked = true;
});

document.getElementById('mustache-filter').addEventListener('click', function (e) {
    reset_mustache();
    select_filter(this);
    live_mustache.style.display = "initial";
    live_mustache.style.width = "initial";
    mustache.checked = true;
});

document.getElementById('rainbow-filter').addEventListener('click', function (e) {
    reset_rainbow();
    select_filter(this);
    live_rainbow.style.display = "initial";
    live_rainbow.style.width = "initial";
    rainbow.checked = true;
});

function select_filter(filter){
    reset_moving();
    reset_filters();

    filter.setAttribute('class', 'selected-filter');

    startbutton.removeAttribute('disabled');
    startbutton.textContent='Take Photo';
    startbutton.style.background= "#008080";
    startbutton.style.cursor = "pointer";

    uploadbutton.removeAttribute('disabled');
    uploadbutton.style.display = 'block';
}

function reset_girls() {
    live_girls.style.left = "0px";
    live_girls.style.top = "0px";
    live_girls.style.width = "initial";
}

function reset_hair() {
    live_hair.style.left = "100px";
    live_hair.style.top = "0px";
    live_hair.style.width = "initial";
}

function reset_mustache() {
    live_mustache.style.left = "140px";
    live_mustache.style.top = "140px";
    live_mustache.style.width = "initial";
}

function reset_rainbow() {
    live_rainbow.style.left = "0px";
    live_rainbow.style.top = "0px";
    live_rainbow.style.width = "initial";
}

function reset_moving(){
    top_move = 0;
    left_move = 0;
    added_width = 0;

    top_input.value = 0;
    left_input.value = 0;
    width_input.value = 0;
}

function reset_filters() {
    document.getElementById('girls-filter').setAttribute('class', '');
    document.getElementById('hair-filter').setAttribute('class', '');
    document.getElementById('mustache-filter').setAttribute('class', '');
    document.getElementById('rainbow-filter').setAttribute('class', '');

    document.getElementById('live-filter-girls').style.display = "none";
    document.getElementById('live-filter-hair').style.display = "none";
    document.getElementById('live-filter-mustache').style.display = "none";
    document.getElementById('live-filter-rainbow').style.display = "none";
}
