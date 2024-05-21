/**
 * Do something when specific fluent form submit inside elementor popup
 *
 * this code will hide .no-card-needed after specific id form submit,
 * inside elementor popup
 */

(function ($) {
  $(document).on("elementor/popup/show", (event, id, instance) => {
    const fluentForms = $(".frm-fluent-form");
    fluentForms.each(function () {
      const $form = $(this);
      const formId = $form.attr("data-form_id");

      $form.on("fluentform_submission_success", function () {
        if (formId == 3) {
          $(".no-card-needed").css("display", "none");
        }
      });
    });
  });
})(jQuery);
