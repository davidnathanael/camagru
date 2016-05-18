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
    var del_btn = null;

    function startup() {
        video = document.getElementById('video');
        canvas = document.getElementById('canvas');
        photo = document.getElementById('photo');
        startbutton = document.getElementById('startbutton');
        prev = document.getElementById('prev');
        next = document.getElementById('next');
        page = 1;

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
                    page = 1;
                    load_pictures(page);
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
            console.log(page);
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
                    var response = JSON.parse(xmlhttp.response);
                    console.log(response);
                    if (response.msg == 'success')
                    {
                        var pictures = response.pictures;

                        if (pictures.length < 10 || page == response.last_page)
                            next.style.display = "none";
                        else
                            next.style.display = "inline-block";
                        pictures.forEach(function (pic) {
                            create_element(pic, response.user_id);
                        });
                    }
                    else if (response.error == "No records" && page > 1)
                    {
                        load_pictures(page - 1);
                    }
                }
            };
            xmlhttp.send();
        }

        function create_element(pic, user_id) {
            var elem = document.createElement("img");
            var container = document.createElement('div');

            elem.src = '../img/photos/' + pic.img_path;
            elem.setAttribute("height", "100");
            elem.setAttribute("width", "130");
            elem.addEventListener('click', function (e) {
                photo.setAttribute('src', '../img/photos/' + pic.img_path);
                photo.style.display = "block";
            });

            if(user_id == pic.user_id)
            {
                var del_btn = document.createElement("span");
                del_btn.innerHTML = 'x';
                del_btn.setAttribute("class", "del-btn");
                del_btn.setAttribute("id", pic.id);
                del_btn.addEventListener('click', del_picture);
                container.appendChild(del_btn);
            }

            var like_btn = document.createElement("span");
            like_btn.innerHTML = (pic.liked) ? 'Unlike' : 'Like';
            like_btn.setAttribute("class", "like-btn");
            like_btn.setAttribute("id", pic.id);
            like_btn.addEventListener('click', like_picture);

            var nb_likes = document.createElement("span");
            nb_likes.innerHTML = (pic.likes) ? ((pic.likes == 1) ? "1 like" : pic.likes + "likes") : "No likes";
            nb_likes.setAttribute("class", "likes");

            var comment_btn = document.createElement("span");
            comment_btn.innerHTML = 'Comment ' + ((pic.nb_comments) ? pic.nb_comments : "");
            comment_btn.setAttribute("class", "comment-btn");
            comment_btn.setAttribute("id", pic.id);
            comment_btn.addEventListener('click', comment_picture);


            var comments_container = document.createElement("div");
            comments_container.setAttribute("class", "comments-container");

            if (pic.comments.length) {
                pic.comments.forEach(function (comment) {
                    var comment_elem = document.createElement("span");
                    comment_elem.innerHTML = "<strong>" + comment.user + "</strong> : " +comment.comment;
                    comment_elem.setAttribute("class", "comment");
                    comments_container.appendChild(comment_elem);
                });
            }

            container.addEventListener('mouseover', function(e) {
                this.setAttribute("class", "picture show-comments");
            });

            container.addEventListener('mouseout', function(e) {
                this.setAttribute("class", "picture");
            });

            container.setAttribute("class", "picture");
            container.appendChild(elem);
            container.appendChild(nb_likes);
            container.appendChild(like_btn);
            container.appendChild(comment_btn);
            container.appendChild(comments_container);

            document.getElementById("gallery").appendChild(container);
        }

        function del_picture() {
            if (confirm("Do you want to delete this picture?"))
            {
                var xmlhttp = new XMLHttpRequest();
                var elem = this;
                xmlhttp.open("GET", "../validations/DeletePicture.php?id=" + this.id, true);
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded;charset=UTF-8");
                xmlhttp.onreadystatechange = function() {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        var response = JSON.parse(xmlhttp.response);
                        if (response.msg === "success")
                        {
                            elem.parentNode.remove();
                            load_pictures(page);
                        }
                        else
                            console.log(response.error);
                    }
                };
                xmlhttp.send();
            }
        }

        function like_picture()
        {
            var xmlhttp = new XMLHttpRequest();
            var elem = this;
            xmlhttp.open("GET", "../validations/LikePicture.php?id=" + this.id, true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded;charset=UTF-8");
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    var response = JSON.parse(xmlhttp.response);
                    if (response.msg == "success") {
                        load_pictures(page);
                    }
                }
            };
            xmlhttp.send();
        }

        function comment_picture()
        {
            var comment = prompt("Comment picture");
            if (comment != null)
            {
                var xmlhttp = new XMLHttpRequest();
                var elem = this;
                xmlhttp.open("POST", "../validations/CommentPicture.php", true);
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded;charset=UTF-8");
                xmlhttp.onreadystatechange = function() {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        var response = JSON.parse(xmlhttp.response);
                        if (response.msg == "success") {
                            load_pictures(page);
                        }
                    }
                };
                xmlhttp.send("id="+ elem.id +"&comment=" + encodeURIComponent(comment));
            }
        }

        window.addEventListener('load', startup, false);
    })();
