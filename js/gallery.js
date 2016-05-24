
(function() {
    var prev = null;
    var next = null;
    var del_btn = null;

    function startup() {
        prev = document.getElementById('prev');
        next = document.getElementById('next');
        page = 1;

            load_pictures(1);

            prev.addEventListener('click', function(e){
                load_pictures((page > 1) ? page - 1 : 1);
                if (page > 1)
                page = page - 1;
            });

            next.addEventListener('click', function(e){
                load_pictures(page + 1);
                page = page + 1;
            });

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

            xhr.open("GET", "../validations/GetPictures.php?page=" + page, true);
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
            elem.setAttribute("width", "350");
            elem.setAttribute("height", "262");

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
            nb_comments.innerHTML = (pic.nb_comments) ? ((pic.nb_comments == 1) ? "1 comment" : pic.nb_comments + " comments") : "No comments";
            nb_comments.setAttribute("class", "nb-comments");


            if (pic.nb_comments) {
                var comments_container = document.createElement("div");
                comments_container.setAttribute("class", "comments-container");

                pic.comments.forEach(function (comment) {
                    var comment_elem = document.createElement("span");
                    comment_elem.innerHTML = "<strong>" + comment.user + "</strong> : " +comment.comment;
                    comment_elem.setAttribute("class", "comment");
                    comments_container.appendChild(comment_elem);
                });
            }

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
            if (pic.nb_comments)
                container.appendChild(comments_container);

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
