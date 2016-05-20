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

function select_filter(former_filter, filter) {
    reset_moving(former_filter, true);

    filter.setAttribute('class', 'selected-filter');

    startbutton.removeAttribute('disabled');
    startbutton.textContent='Take Photo';
    startbutton.style.background= "#008080";
    startbutton.style.cursor = "pointer";

    uploadbutton.removeAttribute('disabled');
    uploadbutton.style.display = 'block';
}

function move(direction) {
    var selected = document.getElementsByClassName('selected-filter')[0];
    if (!selected)
        return;
    var elem = document.getElementById("live-" + selected.id);
    if (direction == "left") {
        left_move--;
        left_input.value = left_move;
        elem.style.left = (parseInt(window.getComputedStyle(elem).left) - 1 + "px");
    }
    if (direction == "up") {
        top_move--;
        top_input.value = top_move;
        elem.style.top= (parseInt(window.getComputedStyle(elem).top) - 1 + "px");
    }
    if (direction == "right") {
        left_move++;
        left_input.value = left_move;
        elem.style.left = (parseInt(window.getComputedStyle(elem).left) + 1 + "px");
    }
    if (direction == "down") {
        top_move++;
        top_input.value = top_move;
        elem.style.top= (parseInt(window.getComputedStyle(elem).top) + 1 + "px");
    }
    if (direction == "wider") {
        added_width++;
        width_input.value  = added_width;
        elem.style.width = (parseInt(window.getComputedStyle(elem).width) + 1 + "px");
    }
    if (direction == "tighter") {
        added_width--;
        width_input.value  = added_width;
        elem.style.width = (parseInt(window.getComputedStyle(elem).width) - 1 + "px");
    }
}

document.onkeydown = function(e) {
    if (e.keyCode == 82)
        reset_moving(document.getElementsByClassName('selected-filter')[0], false);
    if (e.keyCode == 37) {
        e.preventDefault();
        move('left');
    }
    if (e.keyCode == 38) {
        e.preventDefault();
        move('up');
    }
    if (e.keyCode == 39) {
        e.preventDefault();
        move('right');
    }
    if (e.keyCode == 40) {
        e.preventDefault();
        move('down');
    }
    if (e.keyCode == 187) {
        move('wider');
    }
    if (e.keyCode == 189) {
        move('tighter');
    }
};


document.getElementById('filter-girls').addEventListener('click', function (e) {
    select_filter(document.getElementsByClassName('selected-filter')[0], this);
    live_girls.style.display = "initial";
    live_girls.style.width = "initial";
    girls.checked = true;
});

document.getElementById('filter-hair').addEventListener('click', function (e) {
    select_filter(document.getElementsByClassName('selected-filter')[0], this);
    live_hair.style.display = "initial";
    live_hair.style.width = "initial";
    hair.checked = true;
});

document.getElementById('filter-mustache').addEventListener('click', function (e) {
    select_filter(document.getElementsByClassName('selected-filter')[0], this);
    live_mustache.style.display = "initial";
    live_mustache.style.width = "initial";
    mustache.checked = true;
});

document.getElementById('filter-rainbow').addEventListener('click', function (e) {
    select_filter(document.getElementsByClassName('selected-filter')[0], this);
    live_rainbow.style.display = "initial";
    live_rainbow.style.width = "initial";
    rainbow.checked = true;
});

function get_initial_position(filter, attribute) {
    if (filter == "filter-hair")
        return (attribute == "left") ? "100px" : "0px";
    else if (filter == "filter-mustache")
        return "140px";
    return "0px";
}

function reset_moving(filter, reset_button_pressed) {
    if (!filter)
        return;


    var live_filter = document.getElementById("live-" + filter.id);
    var initial_top = get_initial_position(filter.id, "top");
    var initial_left = get_initial_position(filter.id, "left");

    live_filter.style.top = initial_top;
    live_filter.style.left = initial_left;
    live_filter.style.width = "initial";

    if (reset_button_pressed) {
        live_filter.style.display = "none";
        filter.setAttribute('class', '');
    }


    top_move = 0;
    left_move = 0;
    added_width = 0;

    top_input.value = 0;
    left_input.value = 0;
    width_input.value = 0;
}
