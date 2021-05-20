//////////////////////////////////////////////////////////////////
////////////////////////     tinymce     /////////////////////////
//////////////////////////////////////////////////////////////////

const tinymce = require("tinymce");
require("tinymce-i18n/langs/fa_IR");
require("tinymce/themes/modern/theme.min.js");
require("tinymce/plugins/code");
require("tinymce/plugins/directionality");
require("tinymce/plugins/image");
require("tinymce/plugins/link");
require("tinymce/plugins/media");
require("tinymce/plugins/template");
require("tinymce/plugins/codesample");
require("tinymce/plugins/table");
require("tinymce/plugins/hr");
require("tinymce/plugins/anchor");
require("tinymce/plugins/lists");
require("tinymce/plugins/textcolor");
require("tinymce/plugins/colorpicker");
require("tinymce/plugins/visualblocks");
require("tinymce/plugins/help");

tinymce.init({
    path_absolute            : "/",
    branding                 : false,
    selector                 : "#context",
    skin                     : false,
    height                   : 370,
    theme                    : "modern",
    plugins                  : "visualblocks code directionality image link media template table hr anchor lists textcolor colorpicker help",
    toolbar1                 : "bold italic forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | ltr rtl | numlist bullist outdent indent  | image | removeformat | code | formatselect | styleselect",
    image_advtab             : true,
    theme_advanced_font_sizes: "10px,12px,13px,14px,16px,18px,20px",
    font_size_style_values   : "10px,12px,13px,14px,16px,18px,20px",
    templates                : [
        {title: "Test template 1", content: "Test 1"},
        {title: "Test template 2", content: "Test 2"},
    ],
    style_formats            : [
        {
            title  : "مقدمه",
            inline : "span",
            classes: "preface",
        },
        {
            title  : "متن گفت و گو",
            inline : "span",
            classes: "conversation",
        },
    ],
    content_css              : "/css/tinymce.css",
    relative_urls            : false,
    file_browser_callback    : (field_name, url, type, win) => {
        var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName("body")[0].clientWidth;
        var y = window.innerHeight || document.documentElement.clientHeight || document.getElementsByTagName("body")[0].clientHeight;

        var cmsURL = "/laravel-filemanager?field_name=" + field_name;
        if (type == "image") {
            cmsURL = cmsURL + "&type=Images";
        } else {
            cmsURL = cmsURL + "&type=Files";
        }

        tinyMCE.activeEditor.windowManager.open({
            file          : cmsURL,
            title         : "Filemanager",
            width         : x * 0.8,
            height        : y * 0.8,
            resizable     : "yes",
            close_previous: "no",
        });
    },
});

//////////////////////////////////////////////////////////////////
////////////////////////     wizard    ///////////////////////////
//////////////////////////////////////////////////////////////////

require("../plugins/Bootstrap-Wizard/dist/js/jquery.smartWizard.min.js");

$(window).on("load", function () { // makes sure the whole site is loaded
    $("#smartwizard").fadeIn();
    $("#smartwizard").smartWizard({
        selected              : 0,
        theme                 : "default",
        transitionEffect      : "none",
        keyNavigation         : false,
        showStepURLhash       : false,
        enableAnchorOnDoneStep: true,
        toolbarSettings       : {
            toolbarButtonPosition: "top",
        },
        anchorSettings        : {
            enableAllAnchors: true,
        },
        lang                  : {
            next    : "مرحله ی بعد",
            previous: "مرحله ی قبل",
        },
    });
});


//////////////////////////////////////////////////////////////////
////////////////////////     ketchup     /////////////////////////
//////////////////////////////////////////////////////////////////

require("../plugins/ketchup/jquery.ketchup.all.min");

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
    })
    .validation("select-tools", "انتخاب کردن این فیلد ضروری است", function (form, el, value) {
        return value.length !== 0;
    })
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

$(".form").ketchup({
    validateEvents: " blur change",
});

$(".form").submit(function () {
    if (!$(".form").ketchup("isValid")) {
        window.alertify.notify(`
            اطلاعات لازم در فرم را به درستی پر کنید و سپس دوباره اقدام کنید
        `, "error", 20).dismissOthers();
    }
});


//////////////////////////////////////////////////////////////////
////////////////////////     select2    //////////////////////////
//////////////////////////////////////////////////////////////////
// require("select2");
// $(".category").select2();


//////////////////////////////////////////////////////////////////
////////////////////////     cropper    //////////////////////////
//////////////////////////////////////////////////////////////////
require("cropper");
let $image = $("#preview-photo");
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
            // aspectRatio: 45 / 32,
            movable    : true,
            zoomable   : true,
            rotatable  : true,
            scalable   : true
        });
    };
});
$(".form").submit(function () {
    $("#main_photo").val($image.cropper('getCroppedCanvas').toDataURL());
});