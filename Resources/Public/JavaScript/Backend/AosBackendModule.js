define(['jquery'], function($) {
  let $showMark = $('.t3js-formengine-label:contains("AosShowPalette")');
  $showMark.closest('.form-group').addClass('hide');
  $showMark.closest('fieldset.form-section.hide').removeClass('hide');
});
