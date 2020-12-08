jQuery(document).ready(function ($) {
    let comments = woocommerce_photo_reviews_params.hasOwnProperty('comments_container_id') ? woocommerce_photo_reviews_params.comments_container_id : 'comments';
    $('#' + comments).prepend($('.wcpr-filter-container')).prepend($('.wcpr-overall-rating-and-rating-count')).prepend($('.woocommerce-Reviews-title').eq(0));
    $('#commentform').attr('enctype','multipart/form-data');
    let max_files = woocommerce_photo_reviews_params.max_files;
    let selected_images = 0;
    $('#commentform').on('change', '.wcpr_image_upload', function (e) {
        selected_images = $(this.files).length;
    });
    $('#commentform').find('input[type="submit"]').on('click', function (e) {
        let $container = $(this).closest('form');
        let $content = $container.find('textarea[name="comment"]');
        let $name = $container.find('input[name="author"]');
        let $email = $container.find('input[name="email"]');
        let fileUpload = $container.find('.wcpr_image_upload');
        if ($content.length > 0 && !$content.val()) {
            alert(woocommerce_photo_reviews_params.i18n_required_comment_text);
            e.preventDefault();
            $content.focus();
            return false;
        }
        if ('on' == woocommerce_photo_reviews_params.required_image && selected_images == 0) {
            alert(woocommerce_photo_reviews_params.warning_required_image);
            e.preventDefault();
            return false;
        }
        if ($name.length > 0 && !$name.val()) {
            alert(woocommerce_photo_reviews_params.i18n_required_name_text);
            e.preventDefault();
            $name.focus();
            return false;
        }
        if ($email.length > 0 && !$email.val()) {
            alert(woocommerce_photo_reviews_params.i18n_required_email_text);
            e.preventDefault();
            $email.focus();
            return false;
        }

        if (fileUpload.length > 0) {
            if (fileUpload.prop('multiple')) {
                if (selected_images > max_files) {
                    alert(woocommerce_photo_reviews_params.warning_max_files);
                    e.preventDefault();
                    return false;
                }

            } else {
                if (fileUpload.length > max_files) {
                    alert(woocommerce_photo_reviews_params.warning_max_files);
                    e.preventDefault();
                    return false;
                }
            }
        }
        if ($container.find('input[name="wcpr_gdpr_checkbox"]').prop('checked') === false) {
            alert(woocommerce_photo_reviews_params.warning_gdpr);
            e.preventDefault();
            return false;
        }
    })

});
