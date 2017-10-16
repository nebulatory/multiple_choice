<?php

namespace Drupal\multiple_choice\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\Plugin\Field\FieldFormatter\BasicStringFormatter;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'multiple_choice' formatter.
 *
 * @FieldFormatter(
 *   id = "multiple_choice_formatter",
 *   label = @Translation("Multiple choice"),
 *   field_types = {
 *     "multiple_choice"
 *   }
 * )
 */
class MultipleChoiceDefaultFormatter extends BasicStringFormatter {

  /**
   * {@inheritdoc}
   */
  public function viewElements (FieldItemListInterface $items, $langcode) {
    $element = parent::viewElements($items, $langcode);

    return $element;
  }

}
