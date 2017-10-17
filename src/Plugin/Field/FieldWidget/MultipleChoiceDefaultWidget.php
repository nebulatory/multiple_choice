<?php

namespace Drupal\multiple_choice\Plugin\Field\FieldWidget;

use Drupal\Core\Field\Plugin\Field\FieldWidget\StringTextareaWidget;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\text\Plugin\Field\FieldWidget\TextareaWidget;;
use Drupal\multiple_choice\Plugin\Field\FieldType\MultipleChoiceItem;

/**
 * Plugin implementation of the 'multiple choice' widget.
 *
 * @FieldWidget(
 *   id = "multiple_choice_widget",
 *   module = "multiple_choice",
 *   label = @Translation("Multiple choice question widget"),
 *   field_types = {
 *     "multiple_choice"
 *   }
 * )
 */
class MultipleChoiceDefaultWidget extends StringTextareaWidget implements WidgetInterface {

  /**
   * {@inheritdoc}
   */
  public function formElement (FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {

    $answers = MultipleChoiceItem::emptyAnswers();
    if (!empty($items[$delta]->answers)) {
      if (is_string($items[$delta]->answers)) {
        $answers = unserialize($items[$delta]->answers);
      }
      elseif (is_array($items[$delta]->answers)) {
        $answers = $items[$delta]->answers;
      }
    }

    // container for all elements
    $element['mcdw'] = [
      '#type' => 'fieldset',
      '#attributes' => ['class' => ['mcdw-wrapper']],
    ] + $element;

    $element['mcdw']['question'] = [
      '#title' => t('Question text'),
      '#type' => 'textarea',
      '#default_value' => $items[$delta]->question,
      '#rows' => $this->getSetting('rows'),
      '#placeholder' => $this->getSetting('placeholder'),
      '#attributes' => ['class' => ['js-text-full', 'text-full']],
    ];

    $element['mcdw']['answers'] = [
      '#type' => 'table',
      '#caption' => t('Answers'),
      '#tree' => true,
      '#attached' => ['library' => ['multiple_choice/multiple_choice.widget']],
    ];

    foreach ($answers as $i => $answer) {
      $element['mcdw']['answers'][$i]['#attributes'] = ['class' => ['mcdw-answer-wrapper']];
      $element['mcdw']['answers'][$i]['correct'] = [
        '#type' => 'checkbox',
        '#title' => t('Correct answer'),
        '#attributes' => ['class' => ['mcdw-checkbox'], 'checked' => $answer['correct']],
        '#default_value' => $answer['correct'],
        '#return_value' => 'yes',
      ];
      $element['mcdw']['answers'][$i]['text'] = [
        '#type' => 'textfield',
        '#default_value' => $answer['text'],
      //'#size' => $this->getSetting('size'),
      //'#placeholder' => $this->getSetting('placeholder'),
      //'#maxlength' => $this->getFieldSetting('max_length'),
        '#attributes' => ['class' => ['mcdw-answer', 'js-text-full', 'text-full']],
      ];
      $element['mcdw']['answers'][$i]['removed'] = [
        '#type' => 'hidden',
        '#value' => 0,
        '#attributes' => ['class' => ['mcdw-removed']],
        '#wrapper_attributes' => ['class' => ['hidden']],
      ];
      $element['mcdw']['answers'][$i]['remove'] = [
        '#type' => 'button',
        '#value' => t('Remove'),
        '#executes_submit_callback' => false,
        '#attributes' => ['class' => ['mcdw-remove']],
        '#wrapper_attributes' => ['class' => ['mcdw-remove-wrapper']],
      ];
      $element['mcdw']['answers'][$i]['undo'] = [
        '#type' => 'button',
        '#value' => t('Undo'),
        '#executes_submit_callback' => false,
        '#attributes' => ['class' => ['mcdw-undo']],
        '#wrapper_attributes' => ['class' => ['mcdw-undo-wrapper hidden']],
      ];
    }

    $element['mcdw']['add'] = [
      '#type' => 'button',
      '#value' => t('Add another answer'),
      '#executes_submit_callback' => false,
      '#attributes' => ['class' => ['mcdw-add']],
      '#wrapper_attributes' => ['class' => ['mcdw-add-wrapper']],
    ];

    return $element;
  }

}
