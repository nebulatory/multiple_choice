+function ($, Drupal) {
  'use strict';
  Drupal.behaviors.multipleChoiceBehavior = {
    attach: function (context, settings) {

      // .mcdw = multiple choice default widget

      // remove an answer field
      $('.mcdw-wrapper', context).once('mcdwRemoveBehavior').each(function () {
        $(this).on('click', '.mcdw-remove', function () {
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
      });

      // undo remove handler
      $('.mcdw-wrapper', context).once('mcdwUndoBehavior').each(function () {
        $(this).on('click', '.mcdw-undo', function () {
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
      });

      // add answer handler
      $('.mcdw-wrapper', context).once('mcdwAddBehaviour').each(function () {
        $(this).on('click', '.mcdw-add', function () {
          var $el = $(this).closest('.mcdw-wrapper').find('.mcdw-answer-wrapper').eq(0),
              $parent = $el.parent(),
              $clone = $el.clone(),
              index = $el.siblings().length + 1;

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
      });

    },
  };

}(jQuery, Drupal);
