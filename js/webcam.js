(function() {

    var width = 350;
    var height = 0;
    var streaming = false;
    var video = null;
    var canvas = null;
    var photo = null;
    var startbutton = null;

    function startup() {
        video = document.getElementById('video');
        canvas = document.getElementById('canvas');
        photo = document.getElementById('photo');
        startbutton = document.getElementById('startbutton');

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
                    var ret = xmlhttp.responseText
                    photo.setAttribute('src', ret);
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

        window.addEventListener('load', startup, false);
    })();
