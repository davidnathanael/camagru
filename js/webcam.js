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
    var upload_pic = null;

    function startup() {
        video = document.getElementById('video');
        canvas = document.getElementById('canvas');
        photo = document.getElementById('photo');
        startbutton = document.getElementById('startbutton');
        prev = document.getElementById('prev');
        next = document.getElementById('next');
        upload_pic = document.getElementById('fileToUpload');
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
                startbutton.style.display = "none";
                // document.getElementById("live-filters").remove();
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

            upload_pic.addEventListener('change', function (e) {
                if (!this.files[0])
                {
                    this.style.color = "transparent";
                    return;
                }
                this.style.color = "#FFF";
                var file = this.files[0];
                var fd = new FormData();

                fd.append("file", file);
                fd.append("filter", get_filter());
                fd.append("top", document.getElementById("top-move").value);
                fd.append("left", document.getElementById("left-move").value);
                fd.append("width", document.getElementById("added-width").value);

                var xhr = new XMLHttpRequest();
                xhr.open('POST', '../validations/UploadPicture.php', true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        var response = JSON.parse(xhr.response);
                        if (response.msg == "success") {
                            photo.setAttribute('src', response.data);
                            photo.style.display = "block";
                            page = 1;
                            load_pictures(page);
                        }
                    }
                };
                xhr.send(fd);
            });

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
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "../validations/Upload.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded;charset=UTF-8");
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var response = JSON.parse(xhr.response);
                    if (response.msg == "success") {
                        photo.setAttribute('src', response.data);
                        photo.style.display = "block";
                        page = 1;
                        load_pictures(page);
                    }
                }
            };

            var top_value = document.getElementById("top-move").value;
            var left_value = document.getElementById("left-move").value;
            var added_width = document.getElementById("added-width").value;

            xhr.send("filter="+ get_filter() + "&top=" + top_value + "&left=" + left_value + "&width=" + added_width + "&data=" + encodeURIComponent(data.replace("data:image/png;base64,", "")));
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
            var xhr = new XMLHttpRequest();
            if (page == 1)
            prev.style.display = "none";
            else
            prev.style.display = "inline-block";

            xhr.open("GET", "../validations/GetUserPictures.php?page=" + page, true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded;charset=UTF-8");
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var response = JSON.parse(xhr.response);
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
                    load_pictures(page - 1);
                    else if (response.error == "No records" && page == 1)
                    {
                        next.style.display = "none";
                    }

                }
            };
            xhr.send();
        }

        function create_element(pic, user_id) {
            var elem = document.createElement("img");
            var container = document.createElement('div');

            elem.src = '../img/photos/' + pic.img_path;
            elem.setAttribute("width", "160");
            elem.setAttribute("height", "120");
            elem.addEventListener('click', function (e) {
                photo.setAttribute('src', '../img/photos/' + pic.img_path);
                photo.style.display = "block";
            });

            if(user_id == pic.user_id)
            {
                var del_btn = document.createElement("button");
                del_btn.innerHTML = 'Delete';
                del_btn.setAttribute("class", "del-btn");
                del_btn.setAttribute("id", pic.id);
                del_btn.addEventListener('click', del_picture);
                container.appendChild(del_btn);
            }

            var like_btn = document.createElement("button");
            like_btn.innerHTML = (pic.liked) ? 'Unlike' : 'Like';
            like_btn.setAttribute("class", "like-btn");
            like_btn.setAttribute("id", pic.id);
            like_btn.addEventListener('click', like_picture);

            var comment_btn = document.createElement("button");
            comment_btn.innerHTML = 'Comment';
            comment_btn.setAttribute("class", "comment-btn");
            comment_btn.setAttribute("id", pic.id);
            comment_btn.addEventListener('click', comment_picture);

            var btn_container = document.createElement("div");
            btn_container.setAttribute("class", "btn-container");
            btn_container.appendChild(like_btn);
            btn_container.appendChild(comment_btn);

            var nb_likes = document.createElement("span");
            // nb_likes.innerHTML = (pic.likes) ? ((pic.likes == 1) ? "1 like" : pic.likes + " likes") : "No likes";
            nb_likes.innerHTML = (pic.likes);
            nb_likes.setAttribute("class", "likes");

            var nb_comments = document.createElement("span");
            // nb_comments.innerHTML = (pic.nb_comments) ? ((pic.nb_comments == 1) ? "1 comment" : pic.nb_comments + " comments") : "No comments";
            nb_comments.innerHTML = (pic.nb_comments);
            nb_comments.setAttribute("class", "nb-comments");


            // if (pic.nb_comments) {
            //     var comments_container = document.createElement("div");
            //     comments_container.setAttribute("class", "comments-container");
            //
            //     pic.comments.forEach(function (comment) {
            //         var comment_elem = document.createElement("span");
            //         comment_elem.innerHTML = "<strong>" + comment.user + "</strong> : " +comment.comment;
            //         comment_elem.setAttribute("class", "comment");
            //         comments_container.appendChild(comment_elem);
            //     });
            // }

            container.addEventListener('mouseover', function(e) {
                this.setAttribute("class", "picture hovered-picture");
            });

            container.addEventListener('mouseout', function(e) {
                this.setAttribute("class", "picture");
            });

            container.setAttribute("class", "picture");
            container.appendChild(nb_likes);
            container.appendChild(nb_comments);
            container.appendChild(elem);
            container.appendChild(btn_container);
            // if (pic.nb_comments)
            // container.appendChild(comments_container);

            document.getElementById("gallery").appendChild(container);
        }

        function del_picture() {
            if (confirm("Do you want to delete this picture?"))
            {
                var xhr = new XMLHttpRequest();
                var elem = this;
                xhr.open("GET", "../validations/DeletePicture.php?id=" + this.id, true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded;charset=UTF-8");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        var response = JSON.parse(xhr.response);
                        if (response.msg === "success")
                        {
                            elem.parentNode.remove();
                            load_pictures(page);
                        }
                    }
                };
                xhr.send();
            }
        }

        function like_picture()
        {
            var xhr = new XMLHttpRequest();
            var elem = this;
            xhr.open("GET", "../validations/LikePicture.php?id=" + this.id, true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded;charset=UTF-8");
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var response = JSON.parse(xhr.response);
                    if (response.msg == "success") {
                        load_pictures(page);
                    }
                }
            };
            xhr.send();
        }

        function comment_picture()
        {
            var comment = prompt("Comment picture");
            if (comment != null)
            {
                var xhr = new XMLHttpRequest();
                var elem = this;
                xhr.open("POST", "../validations/CommentPicture.php", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded;charset=UTF-8");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        var response = JSON.parse(xhr.response);
                        if (response.msg == "success") {
                            load_pictures(page);
                        }
                    }
                };
                xhr.send("id="+ elem.id +"&comment=" + encodeURIComponent(comment));
            }
        }

        window.addEventListener('load', startup, false);
    })();
