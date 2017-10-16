+function ($) {
  'use strict';

  // .mcdw = multiple choice default widget

  // remove an answer field
  $('.mcdw-wrapper').on('click', '.mcdw-remove', function () {
    var $wrapper = $(this).closest('.mcdw-answer-wrapper');
    $wrapper
      .find('.mcdw-checkbox, .mcdw-answer')
      .attr('disabled', true)
      .end()
      .find('.mcdw-removed')
      .val(1)
      .end()
      .find('.mcdw-undo')
      .closest('.mcdw-undo-wrapper')
      .removeClass('hidden')
      .end()
      .end()
      .find('.mcdw-remove-wrapper')
      .addClass('hidden');

    return false;
  });

  // undo remove handler
  $('.mcdw-wrapper').on('click', '.mcdw-undo', function () {
    var $wrapper = $(this).closest('.mcdw-answer-wrapper');
    $wrapper
      .find('.mcdw-checkbox, .mcdw-answer')
      .attr('disabled', false)
      .end()
      .find('.mcdw-removed')
      .val(0)
      .end()
      .find('.mcdw-undo')
      .closest('.mcdw-undo-wrapper')
      .addClass('hidden')
      .end()
      .end()
      .find('.mcdw-remove-wrapper')
      .removeClass('hidden');

    return false;
  });

  // add answer handler
  $('.mcdw-wrapper').on('click', '.mcdw-add', function () {
    var $el = $('.mcdw-answer-wrapper'),
        $parent = $el.parent(),
        $clone = $el.eq(0).clone(),
        index = $el.length;

    var html = $('<div>')
      .html($clone)
      .html()
      .replace(/(mcdw.{1,2}answers.{1,2})[0-9]+?/g, '\$1' + index);
    $parent.append(html);

    $parent
      .children()
      .last()
      .find(':checkbox')
      .prop('checked', false)
      .end()
      .find('.mcdw-undo')
      .click()
      .end()
      .find('.mcdw-answer')
      .val('');

    return false;
  });

}(jQuery);
