require("../plugins/ketchup/jquery.ketchup.all.min");

$(document).ready(function () {

    //////////////////////////////////////////////////////////////////
    ////////////////////////     cropper    //////////////////////////
    //////////////////////////////////////////////////////////////////
    require("cropper");
    let $image = $("#preview-photo");

    function getRoundedCanvas(sourceCanvas) {
        var canvas  = document.createElement("canvas");
        var context = canvas.getContext("2d");
        var width   = sourceCanvas.width;
        var height  = sourceCanvas.height;

        canvas.width                  = width;
        canvas.height                 = height;
        context.imageSmoothingEnabled = true;
        context.drawImage(sourceCanvas, 0, 0, width, height);
        context.globalCompositeOperation = "destination-in";
        context.beginPath();
        context.arc(width / 2, height / 2, Math.min(width, height) / 2, 0, 2 * Math.PI, true);
        context.fill();
        return canvas;
    }

    $("input:file").change(function () {

        let oFReader = new FileReader();

        oFReader.readAsDataURL(this.files[0]);

        oFReader.onload = function () {
            // Destroy the old cropper instance
            $image.cropper("destroy");
            // Replace url
            $image.attr("src", this.result);
            // Start cropper

            $image.cropper({
                aspectRatio     : 1,
                dragMode        : "move",
                movable         : true,
                zoomable        : true,
                viewMode        : 3,
                restore         : false,
                guides          : false,
                center          : false,
                highlight       : false,
                // cropBoxMovable  : false,
                // cropBoxResizable: false,
                // rotatable       : true,
                scalable        : true,
                ready           : function () {
                    croppable = true;
                },
            });
        };
    });

    $.ketchup
        .createErrorContainer(function (form, el) {
            if ($(el).attr("id") === "username") {
                return $("<ul/>", {
                    "class": "ketchup-custom",
                }).insertAfter(el.parent());
            }
            return $("<ul/>", {
                "class": "ketchup-custom",
            }).insertAfter(el);
        })
        .addErrorMessages(function (form, el, container, messages) {
            container.html("");
            for (i = 0; i < messages.length; i++) {
                $("<li/>", {
                    text: messages[i],
                }).appendTo(container);
            }
        })
        .showErrorContainer(function (form, el, container) {
            container.slideDown("fast");
        })
        .hideErrorContainer(function (form, el, container) {
            container.slideUp("fast");
        })
        .messages({
            required : "پر کردن این فیلد ضروری است",
            minlength: "تعداد کارکتر حداقل باید {arg1} عدد باشد",
            maxlength: "تعداد کارکتر حداکثر باید {arg1} عدد باشد",
            min      : "حداقل باید {arg1} رقم وارد کنید",
            max      : "حداکثر باید {arg1} رقم وارد کنید",
            number   : "تنها عدد مجاز است",
            email    : "آدرس ایمیل معتبر نمی باشد",
        });

    $(".config-submit-profile-button").click(function (e) {

        $.ketchup
            .validation(
                "usernameLength",
                "تعداد کارکتر حداکثر باید ۴ عدد باشد",
                function (form, el, value) {
                    return !(value.length !== 0 && value.length < 4);
                },
            )
            .validation(
                "username",
                "نام کاربری تنها باید شامل عدد و حروف انگلیسی باشد",
                function (form, el, value) {
                    if (value.length !== 0) {
                        let letters = /^[A-Za-z-_0-9]+$/;
                        return !!value.match(letters);
                    }
                    return true;
                },
            )
            .validation(
                "mail-custom",
                "آدرس ایمیل معتبر نمی باشد",
                function (form, el, value) {
                    if (value.length !== 0) {
                        let re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                        return re.test(String(value).toLowerCase());
                    }
                    return true;
                },
            )
            .validation(
                "imageValidateExtension",
                "فرمت تصویر اشتباه است",
                function (form, el, value) {
                    let fileName = value.split("\\");
                    let ext      = fileName[fileName.length - 1].split(".");
                    return !(value !== ""
                        && ext[ext.length - 1] !== "png"
                        && ext[ext.length - 1] !== "PNG"
                        && ext[ext.length - 1] !== "jpg"
                        && ext[ext.length - 1] !== "JPG"
                        && ext[ext.length - 1] !== "jpeg"
                        && ext[ext.length - 1] !== "JPEG"
                        && ext[ext.length - 1] !== "bmp"
                        && ext[ext.length - 1] !== "BMP"
                    );
                });


        $(".config-profile-form").ketchup({
            validateEvents: " blur change",
        });

        if (!$(".config-profile-form").ketchup("isValid")) {
            return;
        }

        e.preventDefault();

        $this = $(this);

        $("#avatar").val(getRoundedCanvas($image.cropper("getCroppedCanvas")).toDataURL());

        $.ajax({
            url        : $(".config-profile-form").attr("action"),
            type       : "POST",
            data       : new FormData($(".config-profile-form")[0]),
            contentType: false,
            processData: false,
            success    : function (response) {

                window.alertify.set("notifier", "position", "bottom-left");

                if (response.status === "done") {
                    window.alertify.notify(
                        "عملیات به روز رسانی با موفقیت انجام شد!",
                        "success",
                        15,
                    );
                }

                if (response.status === "failed") {
                    window.alertify.notify(
                        response.message,
                        "error",
                        10,
                    );
                }

            },
            error      : function (response) {
                if (response.status === 422) {
                    window.alertify.notify("", "error", 0.00000000001).dismissOthers();
                    Object.values(response.responseJSON.errors).forEach(function (item) {
                        window.alertify.set("notifier", "position", "bottom-left");
                        window.alertify.notify(
                            item[0],
                            "error",
                            10,
                        );
                    });
                }
            },
        });
    });

    $(".config-submit-password-button").click(function (e) {

        $.ketchup
            .validation(
                "passwordValidation",
                "تعداد کارکتر حداقل باید ۴ عدد باشد",
                function (form, el, value) {
                    return !(value.length < 4 && value.length !== 0);
                },
            )
            .validation(
                "confirmPasswordValidation",
                "لطفا در وارد کردن تکرار رمز عبور دقت فرمایید",
                function (form, el, value) {
                    return (!(value !== $("#newPassword").val() && value));
                },
            );

        $(".config-password-form").ketchup({
            validateEvents: " blur change",
        });

        if (!$(".config-password-form").ketchup("isValid")) {
            return;
        }
        e.preventDefault();

        $this = $(this);
        $.ajax({
            url    : $(".config-password-form").attr("action"),
            type   : "POST",
            data   : {
                _token                     : $("meta[name='csrf-token']").attr("content"),
                "current_password"         : $("#currentPassword").val(),
                "new_password"             : $("#newPassword").val(),
                "new_password_confirmation": $("#newPasswordConfirmation").val(),
            },
            success: function (response) {

                window.alertify.set("notifier", "position", "bottom-left");

                if (response.status === "done") {
                    window.alertify.notify(
                        "عملیات به روز رسانی با موفقیت انجام شد!",
                        "success",
                        15,
                    );
                }

                if (response.status === "failed") {
                    window.alertify.notify(
                        response.message,
                        "error",
                        15,
                    );
                }

            },
            error  : function (response) {
                if (response.status === 422) {
                    window.alertify.notify("", "error", 0.00000000001).dismissOthers();
                    Object.values(response.responseJSON.errors).forEach(function (item) {
                        window.alertify.set("notifier", "position", "bottom-left");
                        window.alertify.notify(
                            item[0],
                            "error",
                            10,
                        );
                    });
                }
            },
        });

    });
});