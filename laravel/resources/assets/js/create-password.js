$(document).ready(function () {

    require('../plugins/ketchup/jquery.ketchup.all.min');

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
        })
        .validation('password', 'تعداد کارکتر حداقل باید ۶ عدد باشد', function (form, el, value) {
            return !(value.length < 6 && value.length !== 0);
        })
        .validation('confirm-password', 'لطفا در وارد کردن تکرار رمز عبور دقت نمایید', function (form, el, value) {
            return !(value !== $("input[name='password']").val() && value);
        });


    $(".form").ketchup({
        validateEvents: " blur change",
    });

    $(".form").submit(function () {
        if ($(".form").ketchup("isValid")) {
            $("html").prepend(`
                        <div class="before-send">
                            <img src="/images/loader.gif">
                        </div>
                    `);
        }
    });

});