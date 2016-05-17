(function() {

    var width = 350;
    var height = 0;
    var streaming = false;
    var video = null;
    var canvas = null;
    var photo = null;
    var prev = null;
    var next = null;
    var startbutton = null;

    function startup() {
        video = document.getElementById('video');
        canvas = document.getElementById('canvas');
        photo = document.getElementById('photo');
        startbutton = document.getElementById('startbutton');
        prev = document.getElementById('prev');
        next = document.getElementById('next');
        var page = 1;

        navigator.getMedia = ( navigator.getUserMedia ||
            navigator.webkitGetUserMedia ||
            navigator.mozGetUserMedia ||
            navigator.msGetUserMedia);

            navigator.getMedia({
                video: true,
                audio: false
            }, function(stream) {
                if (navigator.mozGetUserMedia)
                video.mozSrcObject = stream;
                else {
                    var vendorURL = window.URL || window.webkitURL;
                    video.src = vendorURL.createObjectURL(stream);
                }
                video.play();
            }, function(err) {
                console.log("An error occured! " + err);
            });

            load_pictures(1);

            video.addEventListener('canplay', function(ev){
                if (!streaming) {
                    height = video.videoHeight / (video.videoWidth/width);
                    if (isNaN(height)) {
                        height = width / (4/3);
                    }

                    video.setAttribute('width', width);
                    video.setAttribute('height', height);
                    canvas.setAttribute('width', width);
                    canvas.setAttribute('height', height);
                    streaming = true;
                }
            }, false);

            startbutton.addEventListener('click', function(ev){
                takepicture();
                ev.preventDefault();
            }, false);

            prev.addEventListener('click', function(e){
                load_pictures((page > 1) ? page - 1 : 1);
                if (page > 1)
                    page = page - 1;
            });

            next.addEventListener('click', function(e){
                load_pictures(page + 1);
                page = page + 1;
            });

            clearphoto();
        }

        function clearphoto() {
            var context = canvas.getContext('2d');
            context.fillStyle = "#AAA";
            context.fillRect(0, 0, canvas.width, canvas.height);

            var data = canvas.toDataURL('image/png');
        }

        function takepicture() {
            var context = canvas.getContext('2d');
            if (width && height) {
                canvas.width = width;
                canvas.height = height;
                context.drawImage(video, 0, 0, width, height);

                var data = canvas.toDataURL('image/png');
                upload(data);
            }
            else
            clearphoto();
        }

        function  upload(data) {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.open("POST", "../validations/Upload.php", true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded;charset=UTF-8");
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    var ret = xmlhttp.responseText;
                    photo.setAttribute('src', ret);
                    photo.style.display = "block";
                    load_pictures(1);
                    page = 1;
                }
            };
            xmlhttp.send("filter="+ get_filter() +"&data=" + encodeURIComponent(data.replace("data:image/png;base64,", "")));
        }

        function    get_filter() {
            if (document.getElementById('girls-radio').checked)
                return ('girls');
            else if (document.getElementById('hair-radio').checked)
                return ('hair');
            else if (document.getElementById('mustache-radio').checked)
                return ('mustache');
            else if (document.getElementById('rainbow-radio').checked)
                return ('rainbow');
        }

        function load_pictures(page) {
            var node = document.getElementById("gallery");
            while (node.firstChild) {
                node.removeChild(node.firstChild);
            }
            var xmlhttp = new XMLHttpRequest();
            if (page == 1)
                prev.style.display = "none";
            else
                prev.style.display = "inline-block";

            xmlhttp.open("GET", "../validations/GetPictures.php?page=" + page, true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded;charset=UTF-8");
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    var pictures = JSON.parse(xmlhttp.response);
                    if (pictures.constructor === Array)
                    {
                        if (pictures.length < 10)
                            next.style.display = "none";
                        else
                            next.style.display = "inline-block";
                        pictures.forEach( function (pic)
                        {
                            var elem = document.createElement("img");

                            elem.src = '../img/photos/' + pic.img_path;
                            elem.setAttribute("height", "80");
                            elem.setAttribute("width", "130");

                            var container = document.createElement('div');
                            container.setAttribute("class", "picture");
                            container.appendChild(elem);
                            document.getElementById("gallery").appendChild(container);
                        });
                    }
                    else {
                        page = page - 1;
                        load_pictures(page - 1);
                    }
                }
            };
            xmlhttp.send();
        }


        window.addEventListener('load', startup, false);
    })();
