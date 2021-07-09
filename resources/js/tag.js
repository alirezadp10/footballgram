window._ = require("lodash");

$(document).ready(function () {

    $(".instagram-nav").children().first().addClass("active");

    $(".instagram-nav").children().first().attr("aria-selected", true);

    let tabCount = $(".instagram-nav").children().length;

    $(".instagram-nav").children().css({
        width: `${100 / tabCount}%`,
    });

    $(".instagram-tab-content").children().first().addClass("show active");

    let firstTab = $(".instagram-nav .active").attr("aria-controls");

    let news_received = 0;
    if (firstTab === "#nav-news") {
        $.ajax({
            url       : "/posts/tags/get/news",
            type      : "GET",
            data      : {
                name         : window.LARAVEL_TAG_NAME,
                news_received: news_received,
            },
            beforeSend: function () {
                $("html").prepend(`
                    <div class="before-send">
                        <img src="/images/loader.gif">
                    </div>
                `);
            },
            success   : function (data) {
                if (data.news.length) {
                    $("#news-wrapper").empty();
                    news_received += data.news.length;
                    data.news.forEach(function (item) {
                        let like = item.isLiked ? 'post-liked' : '';
                        let dislike = item.isDisliked ? 'post-disliked' : '';
                        $("#news-wrapper").append(
                            `
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="post-instagram-wrapper"
                                         data-post-id="${item.id}"
                                         data-post-type="${item.type}">
                                        <a href="${item.url}">
                                            <span class="post-layout"></span>
                                        </a>
                                        <img src="${item.mainPhoto}"
                                             class="post-instagram-img lazyload" />
                                        <a href="${item.authorUrl}" class="post-instagram-author">
                                            <i class="fas fa-pencil-alt"></i> ${item.authorName}
                                        </a>
                                        <div class="post-instagram-like-wrapper">
                                            <div class="post-instagram-like ${like}">
                                                <span class="count-like">${item.countLike}</span>
                                                <i class="far fa-thumbs-up"></i>
                                            </div>
                                            <div class="post-instagram-dislike ${dislike}">
                                                <span class="count-dislike">${item.countDislike}</span>
                                                <i class="far fa-thumbs-down"></i>
                                            </div>
                                        </div>
                                        <a href="${item.url}">
                                            <div class="post-instagram-caption">
                                                ${item.title}
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            `,
                        );
                    });
                }
            },
            error     : function (data) {
                if (data.status === 429) {
                    window.alertify.set("notifier", "position", "bottom-left");
                    window.alertify.notify(
                        "تعداد درخواست های شما از سرور بیش از حد مجاز بوده است. <br> یک دقیقه ی دیگر دوباره تلاش کنید.",
                        "error",
                        5,
                    );
                }
            },
            complete  : function () {
                _.delay(function () {
                    $(".before-send").remove();
                }, 1000);
            },
        });
    }

    let user_contents_received = 0;
    if (firstTab === "#nav-user-contents") {
        $.ajax({
            url       : "/posts/tags/get/user-contents",
            type      : "GET",
            data      : {
                name                  : window.LARAVEL_TAG_NAME,
                user_contents_received: user_contents_received,
            },
            beforeSend: function () {
                $("html").prepend(`
                    <div class="before-send">
                        <img src="/images/loader.gif">
                    </div>
                `);
            },
            success   : function (data) {
                if (data.userContents.length) {
                    $("#user-contents-wrapper").empty();
                    user_contents_received += data.userContents.length;
                    data.userContents.forEach(function (item) {
                        let like = item.isLiked ? 'post-liked' : '';
                        let dislike = item.isDisliked ? 'post-disliked' : '';
                        $("#user-contents-wrapper").append(
                            `
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="post-instagram-wrapper"
                                         data-post-id="${item.id}"
                                         data-post-type="${item.type}">
                                        <a href="${item.url}">
                                            <span class="post-layout"></span>
                                        </a>
                                        <img src="${item.mainPhoto}"
                                             class="post-instagram-img lazyload" />
                                        <a href="${item.authorUrl}" class="post-instagram-author">
                                            <i class="fas fa-pencil-alt"></i> ${item.authorName}
                                        </a>
                                        <div class="post-instagram-like-wrapper">
                                            <div class="post-instagram-like ${like}">
                                                <span class="count-like">${item.countLike}</span>
                                                <i class="far fa-thumbs-up"></i>
                                            </div>
                                            <div class="post-instagram-dislike ${dislike}">
                                                <span class="count-dislike">${item.countDislike}</span>
                                                <i class="far fa-thumbs-down"></i>
                                            </div>
                                        </div>
                                        <a href="${item.url}">
                                            <div class="post-instagram-caption">
                                                ${item.title}
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            `,
                        );
                    });
                }
            },
            error     : function (data) {
                if (data.status === 429) {
                    window.alertify.set("notifier", "position", "bottom-left");
                    window.alertify.notify(
                        "تعداد درخواست های شما از سرور بیش از حد مجاز بوده است. <br> یک دقیقه ی دیگر دوباره تلاش کنید.",
                        "error",
                        5,
                    );
                }
            },
            complete  : function () {
                _.delay(function () {
                    $(".before-send").remove();
                }, 1000);
            },
        });
    }

    let user_contents_active  = $("div[aria-controls = '#nav-user-contents']").hasClass("active");
    let user_contents_clicked = false;
    $(".user-contents").click(function () {
        if (!user_contents_clicked && !user_contents_active) {
            user_contents_clicked = true;
            $.ajax({
                url       : "/posts/tags/get/user-contents",
                type      : "GET",
                data      : {
                    name                  : window.LARAVEL_TAG_NAME,
                    user_contents_received: user_contents_received,
                },
                beforeSend: function () {
                    $("html").prepend(`
                    <div class="before-send">
                        <img src="/images/loader.gif">
                    </div>
                `);
                },
                success   : function (data) {
                    if (data.userContents.length) {
                        $("#user-contents-wrapper").empty();
                        user_contents_received += data.userContents.length;
                        data.userContents.forEach(function (item) {
                            let like = item.isLiked ? 'post-liked' : '';
                            let dislike = item.isDisliked ? 'post-disliked' : '';
                            $("#user-contents-wrapper").append(
                                `
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="post-instagram-wrapper"
                                             data-post-id="${item.id}"
                                             data-post-type="${item.type}">
                                            <a href="${item.url}">
                                                <span class="post-layout"></span>
                                            </a>
                                            <img src="${item.mainPhoto}"
                                                 class="post-instagram-img lazyload" />
                                            <a href="${item.authorUrl}" class="post-instagram-author">
                                                <i class="fas fa-pencil-alt"></i> ${item.authorName}
                                            </a>
                                            <div class="post-instagram-like-wrapper">
                                                <div class="post-instagram-like ${like}">
                                                    <span class="count-like">${item.countLike}</span>
                                                    <i class="far fa-thumbs-up"></i>
                                                </div>
                                                <div class="post-instagram-dislike ${dislike}">
                                                    <span class="count-dislike">${item.countDislike}</span>
                                                    <i class="far fa-thumbs-down"></i>
                                                </div>
                                            </div>
                                            <a href="${item.url}">
                                                <div class="post-instagram-caption">
                                                    ${item.title}
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                `,
                            );
                        });
                    }
                },
                error     : function (data) {
                    if (data.status === 429) {
                        window.alertify.set("notifier", "position", "bottom-left");
                        window.alertify.notify(
                            "تعداد درخواست های شما از سرور بیش از حد مجاز بوده است. <br> یک دقیقه ی دیگر دوباره تلاش کنید.",
                            "error",
                            5,
                        );
                    }
                },
                complete  : function () {
                    _.delay(function () {
                        $(".before-send").remove();
                    }, 1000);
                },
            });
        }
    });

    let comments_received = 0;
    let comments_clicked  = false;
    $(".comments").click(function () {
        if (!comments_clicked) {
            comments_clicked = true;
            $.ajax({
                url       : "/posts/tags/get/comments",
                type      : "GET",
                data      : {
                    name             : window.LARAVEL_TAG_NAME,
                    comments_received: comments_received,
                },
                beforeSend: function () {
                    $("html").prepend(`
                    <div class="before-send">
                        <img src="/images/loader.gif">
                    </div>
                `);
                },
                success   : function (data) {
                    if (data.comments.length) {
                        $("#comments-wrapper").empty();
                        comments_received += data.comments.length;
                        data.comments.forEach(function (item) {
                            let post_comment_liked    = item.isLiked === true ? "post-comment-liked" : "";
                            let post_comment_disliked = item.isDisliked === true ? "post-comment-disliked" : "";
                            $("#comments-wrapper").append(
                                `
                                <div class="col-12">
                                    <div class="post-comment"
                                         data-id="${item.id}"
                                         data-level="${item.level}">
                                        <div class="post-comment-body">
                                            <div class="post-comment-avatar">
                                                <img src="${item.image}">
                                            </div>
                                            <div class="post-comment-author">
                                                <a href="${item.authorUrl}">${item.author}</a> نوشته:
                                            </div>
                                            <div class="post-comment-context">
                                                ${item.context}
                                            </div>
                                        </div>
                                        <div class="post-comment-like-parent">
                                            <span class="post-comment-like-wrapper">
                                                <button class="post-comment-like-button ${post_comment_liked}">
                                                    <i class="far fa-thumbs-up"></i>
                                                </button>
                                                <span class="post-comment-like-count ${post_comment_liked}">
                                                    ${item.like}
                                                </span>
                                            </span>
                                            <span class="post-comment-dislike-wrapper">
                                                <button class="post-comment-dislike-button ${post_comment_disliked}">
                                                    <i class="far fa-thumbs-down"></i>
                                                </button>
                                                <span class="post-comment-dislike-count ${post_comment_disliked}">
                                                    ${item.dislike}
                                                </span>
                                            </span>
                                            <a href="${item.url}">
                                                <div class="post-comment-view">
                                                        <i class="fas fa-eye"></i>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>                                    
                            `,
                            );
                        });
                    }
                },
                error     : function (data) {
                    if (data.status === 429) {
                        window.alertify.set("notifier", "position", "bottom-left");
                        window.alertify.notify(
                            "تعداد درخواست های شما از سرور بیش از حد مجاز بوده است. <br> یک دقیقه ی دیگر دوباره تلاش کنید.",
                            "error",
                            5,
                        );
                    }
                },
                complete  : function () {
                    _.delay(function () {
                        $(".before-send").remove();
                    }, 1000);
                },
            });
        }
    });

    $(document).on("click", ".post-comment-like-wrapper", function () {
        var $this = $(this);
        $.ajax({
            url    : "/posts/comments/like",
            type   : "POST",
            data   : {
                _token: $("meta[name='csrf-token']").attr("content"),
                id    : $(this).parent().parent().attr("data-id"),
                type  : "comment",
            },
            success: function (data) {
                if (data["status"] === "Done") {
                    $this.parent().parent().find(".post-comment-like-count").addClass("post-comment-liked");
                    $this.parent().parent().find(".post-comment-like-button").addClass("post-comment-liked");
                    $this.parent().parent().find(".post-comment-like-count").html(data["like"]);

                    $this.parent().parent().find(".post-comment-dislike-count").removeClass("post-comment-disliked");
                    $this.parent().parent().find(".post-comment-dislike-button").removeClass("post-comment-disliked");
                    $this.parent().parent().find(".post-comment-dislike-count").html(data["dislike"]);
                }
            },
            error  : function (data) {
                if (data.status === 429) {
                    window.alertify.set("notifier", "position", "bottom-left");
                    window.alertify.notify(
                        "تعداد درخواست های شما از سرور بیش از حد مجاز بوده است. <br> یک دقیقه ی دیگر دوباره تلاش کنید.",
                        "error",
                        5,
                    );
                }
            },
        });
    });

    $(document).on("click", ".post-comment-dislike-wrapper", function () {
        var $this = $(this);
        $.ajax({
            url    : "/posts/comments/dislike",
            type   : "POST",
            data   : {
                _token: $("meta[name='csrf-token']").attr("content"),
                id    : $(this).parent().parent().attr("data-id"),
                type  : "comment",
            },
            success: function (data) {
                if (data["status"] === "Done") {
                    $this.parent().parent().find(".post-comment-like-count").removeClass("post-comment-liked");
                    $this.parent().parent().find(".post-comment-like-button").removeClass("post-comment-liked");
                    $this.parent().parent().find(".post-comment-like-count").html(data["like"]);

                    $this.parent().parent().find(".post-comment-dislike-count").addClass("post-comment-disliked");
                    $this.parent().parent().find(".post-comment-dislike-button").addClass("post-comment-disliked");
                    $this.parent().parent().find(".post-comment-dislike-count").html(data["dislike"]);
                }
            },
            error  : function (data) {
                if (data.status === 429) {
                    window.alertify.set("notifier", "position", "bottom-left");
                    window.alertify.notify(
                        "تعداد درخواست های شما از سرور بیش از حد مجاز بوده است. <br> یک دقیقه ی دیگر دوباره تلاش کنید.",
                        "error",
                        5,
                    );
                }
            },
        });
    });

    $(".more-news").click(function () {
        $.ajax({
            url       : "/posts/tags/get/news",
            type      : "GET",
            data      : {
                name         : window.LARAVEL_TAG_NAME,
                news_received: news_received,
            },
            beforeSend: function () {
                $("html").prepend(`
                    <div class="before-send">
                        <img src="/images/loader.gif">
                    </div>
                `);
            },
            success   : function (data) {
                if (data.news.length) {
                    news_received += data.news.length;
                    data.news.forEach(function (item) {
                        let like = item.isLiked ? 'post-liked' : '';
                        let dislike = item.isDisliked ? 'post-disliked' : '';
                        $("#news-wrapper").append(
                            `
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="post-instagram-wrapper"
                                         data-post-id="${item.id}"
                                         data-post-type="${item.type}">
                                        <a href="${item.url}">
                                            <span class="post-layout"></span>
                                        </a>
                                        <img src="${item.mainPhoto}"
                                             class="post-instagram-img lazyload" />
                                        <a href="${item.authorUrl}" class="post-instagram-author">
                                            <i class="fas fa-pencil-alt"></i> ${item.authorName}
                                        </a>
                                        <div class="post-instagram-like-wrapper">
                                            <div class="post-instagram-like ${like}">
                                                <span class="count-like">${item.countLike}</span>
                                                <i class="far fa-thumbs-up"></i>
                                            </div>
                                            <div class="post-instagram-dislike ${dislike}">
                                                <span class="count-dislike">${item.countDislike}</span>
                                                <i class="far fa-thumbs-down"></i>
                                            </div>
                                        </div>
                                        <a href="${item.url}">
                                            <div class="post-instagram-caption">
                                                ${item.title}
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            `,
                        );
                    });
                }
                else {
                    $(".more-news").parent().remove();
                }
            },
            error     : function (data) {
                if (data.status === 429) {
                    window.alertify.set("notifier", "position", "bottom-left");
                    window.alertify.notify(
                        "تعداد درخواست های شما از سرور بیش از حد مجاز بوده است. <br> یک دقیقه ی دیگر دوباره تلاش کنید.",
                        "error",
                        5,
                    );
                }
            },
            complete  : function () {
                _.delay(function () {
                    $(".before-send").remove();
                }, 1000);
            },
        });
    });

    $(".more-user-contents").click(function () {
        $.ajax({
            url       : "/posts/tags/get/user-contents",
            type      : "GET",
            data      : {
                name                  : window.LARAVEL_TAG_NAME,
                user_contents_received: user_contents_received,
            },
            beforeSend: function () {
                $("html").prepend(`
                    <div class="before-send">
                        <img src="/images/loader.gif">
                    </div>
                `);
            },
            success   : function (data) {
                if (data.userContents.length) {
                    user_contents_received += data.userContents.length;
                    data.userContents.forEach(function (item) {
                        let like = item.isLiked ? 'post-liked' : '';
                        let dislike = item.isDisliked ? 'post-disliked' : '';
                        $("#user-contents-wrapper").append(
                            `
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="post-instagram-wrapper"
                                         data-post-id="${item.id}"
                                         data-post-type="${item.type}">
                                        <a href="${item.url}">
                                            <span class="post-layout"></span>
                                        </a>
                                        <img src="${item.mainPhoto}"
                                             class="post-instagram-img lazyload" />
                                        <a href="${item.authorUrl}" class="post-instagram-author">
                                            <i class="fas fa-pencil-alt"></i> ${item.authorName}
                                        </a>
                                        <div class="post-instagram-like-wrapper">
                                            <div class="post-instagram-like ${like}">
                                                <span class="count-like">${item.countLike}</span>
                                                <i class="far fa-thumbs-up"></i>
                                            </div>
                                            <div class="post-instagram-dislike ${dislike}">
                                                <span class="count-dislike">${item.countDislike}</span>
                                                <i class="far fa-thumbs-down"></i>
                                            </div>
                                        </div>
                                        <a href="${item.url}">
                                            <div class="post-instagram-caption">
                                                ${item.title}
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            `,
                        );
                    });
                }
                else {
                    $(".more-user-contents").parent().remove();
                }
            },
            error     : function (data) {
                if (data.status === 429) {
                    window.alertify.set("notifier", "position", "bottom-left");
                    window.alertify.notify(
                        "تعداد درخواست های شما از سرور بیش از حد مجاز بوده است. <br> یک دقیقه ی دیگر دوباره تلاش کنید.",
                        "error",
                        5,
                    );
                }
            },
            complete  : function () {
                _.delay(function () {
                    $(".before-send").remove();
                }, 1000);
            },
        });
    });

    $(".more-comments").click(function () {
        $.ajax({
            url       : "/posts/tags/get/comments",
            type      : "GET",
            data      : {
                name             : window.LARAVEL_TAG_NAME,
                comments_received: comments_received,
            },
            beforeSend: function () {
                $("html").prepend(`
                    <div class="before-send">
                        <img src="/images/loader.gif">
                    </div>
                `);
            },
            success   : function (data) {
                if (data.comments.length) {
                    $("#comments-wrapper").empty();
                    comments_received += data.comments.length;
                    data.comments.forEach(function (item) {
                        let post_comment_liked    = item.isLiked === true ? "post-comment-liked" : "";
                        let post_comment_disliked = item.isDisliked === true ? "post-comment-disliked" : "";
                        $("#comments-wrapper").append(
                            `
                                <div class="col-12">
                                    <div class="post-comment"
                                         data-id="${item.id}"
                                         data-level="${item.level}">
                                        <div class="post-comment-body">
                                            <div class="post-comment-avatar">
                                                <img src="${item.image}">
                                            </div>
                                            <div class="post-comment-author">
                                                <a href="${item.authorUrl}">${item.author}</a> نوشته:
                                            </div>
                                            <div class="post-comment-context">
                                                ${item.context}
                                            </div>
                                        </div>
                                        <div class="post-comment-like-parent">
                                            <span class="post-comment-like-wrapper">
                                                <button class="post-comment-like-button ${post_comment_liked}">
                                                    <i class="far fa-thumbs-up"></i>
                                                </button>
                                                <span class="post-comment-like-count ${post_comment_liked}">
                                                    ${item.like}
                                                </span>
                                            </span>
                                            <span class="post-comment-dislike-wrapper">
                                                <button class="post-comment-dislike-button ${post_comment_disliked}">
                                                    <i class="far fa-thumbs-down"></i>
                                                </button>
                                                <span class="post-comment-dislike-count ${post_comment_disliked}">
                                                    ${item.dislike}
                                                </span>
                                            </span>
                                            <a href="${item.url}">
                                                <div class="post-comment-view">
                                                        <i class="fas fa-eye"></i>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>                                    
                            `,
                        );
                    });
                }
                else {
                    $(".more-comments").parent().remove();
                }
            },
            error     : function (data) {
                if (data.status === 429) {
                    window.alertify.set("notifier", "position", "bottom-left");
                    window.alertify.notify(
                        "تعداد درخواست های شما از سرور بیش از حد مجاز بوده است. <br> یک دقیقه ی دیگر دوباره تلاش کنید.",
                        "error",
                        5,
                    );
                }
            },
            complete  : function () {
                _.delay(function () {
                    $(".before-send").remove();
                }, 1000);
            },
        });
    });

    $(document).on("click", ".post-instagram-like", function () {
        var $this = $(this);
        $.ajax({
            url    : $this.parent().parent().attr("data-post-type") == "news" ? "/posts/news/like" : "/posts/user-contents/like",
            type   : "POST",
            data   : {
                _token: $("meta[name='csrf-token']").attr("content"),
                id    : $this.parent().parent().attr("data-post-id"),
                type  : $this.parent().parent().attr("data-post-type"),
            },
            success: function (data) {
                if (data["status"] === "Done") {
                    $this.addClass("post-liked");
                    $this.find(".count-like").text(data["like"]);
                    $this.parent().find(".post-instagram-dislike").removeClass("post-disliked");
                    $this.parent().find(".count-dislike").text(data["dislike"]);
                }
            },
            error  : function (data) {
                if (data.status === 401) {
                    window.alertify.notify('', "error", 0.00000000001).dismissOthers();
                    window.alertify.set("notifier", "position", "bottom-left");
                    window.alertify.notify(
                        "برای انجام این کار ابتدا ثبت نام کنید",
                        "error",
                        10,
                    );
                }
                if (data.status === 429) {
                    window.alertify.set("notifier", "position", "bottom-left");
                    window.alertify.notify(
                        "تعداد درخواست های شما از سرور بیش از حد مجاز بوده است. <br> یک دقیقه ی دیگر دوباره تلاش کنید.",
                        "error",
                        5,
                    ).dismissOthers();
                }
            },
        });
    });

    $(document).on("click", ".post-instagram-dislike", function () {
        var $this = $(this);
        $.ajax({
            url    : $this.parent().parent().attr("data-post-type") == "news" ? "/posts/news/dislike" : "/posts/user-contents/dislike",
            type   : "POST",
            data   : {
                _token: $("meta[name='csrf-token']").attr("content"),
                id    : $this.parent().parent().attr("data-post-id"),
                type  : $this.parent().parent().attr("data-post-type"),
            },
            success: function (data) {
                if (data["status"] === "Done") {
                    $this.addClass("post-disliked");
                    $this.find(".count-dislike").text(data["dislike"]);
                    $this.parent().find(".post-instagram-like").removeClass("post-liked");
                    $this.parent().find(".count-like").text(data["like"]);
                }
            },
            error  : function (data) {
                if (data.status === 401) {
                    window.alertify.notify('', "error", 0.00000000001).dismissOthers();
                    window.alertify.set("notifier", "position", "bottom-left");
                    window.alertify.notify(
                        "برای انجام این کار ابتدا ثبت نام کنید",
                        "error",
                        10,
                    );
                }
                if (data.status === 429) {
                    window.alertify.set("notifier", "position", "bottom-left");
                    window.alertify.notify(
                        "تعداد درخواست های شما از سرور بیش از حد مجاز بوده است. <br> یک دقیقه ی دیگر دوباره تلاش کنید.",
                        "error",
                        5,
                    ).dismissOthers();
                }
            },
        });
    });

});