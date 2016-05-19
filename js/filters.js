var girls = document.getElementById('girls-radio');
var hair = document.getElementById('hair-radio');
var mustache = document.getElementById('mustache-radio');
var rainbow = document.getElementById('rainbow-radio');
var startbutton = document.getElementById('startbutton');
var uploadbutton = document.getElementById('fileToUpload');

document.getElementById('girls-filter').addEventListener('click', function (e) {
    select_filter(this);
    document.getElementById('live-filter-girls').style.display = "initial";
    console.log("test");
    girls.checked = true;
});

document.getElementById('hair-filter').addEventListener('click', function (e) {
    select_filter(this);
    document.getElementById('live-filter-hair').style.display = "initial";
    hair.checked = true;
});

document.getElementById('mustache-filter').addEventListener('click', function (e) {
    select_filter(this);
    document.getElementById('live-filter-mustache').style.display = "initial";
    mustache.checked = true;
});

document.getElementById('rainbow-filter').addEventListener('click', function (e) {
    select_filter(this);
    document.getElementById('live-filter-rainbow').style.display = "initial";
    rainbow.checked = true;
});

function select_filter(filter){
    reset_filters();
    filter.setAttribute('class', 'filter selected-filter');

    startbutton.removeAttribute('disabled');
    startbutton.textContent='Take Photo';
    startbutton.style.background= "#008080";
    startbutton.style.cursor = "pointer";
    uploadbutton.removeAttribute('disabled');

    uploadbutton.style.display = 'block';
}

function reset_filters() {
    document.getElementById('girls-filter').setAttribute('class', 'filter');
    document.getElementById('hair-filter').setAttribute('class', 'filter');
    document.getElementById('mustache-filter').setAttribute('class', 'filter');
    document.getElementById('rainbow-filter').setAttribute('class', 'filter');

    document.getElementById('live-filter-girls').style.display = "none";
    document.getElementById('live-filter-hair').style.display = "none";
    document.getElementById('live-filter-mustache').style.display = "none";
    document.getElementById('live-filter-rainbow').style.display = "none";

}
