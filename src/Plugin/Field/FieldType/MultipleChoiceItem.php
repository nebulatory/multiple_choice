<?php

namespace Drupal\multiple_choice\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Provides a field type for multiple choice questions.
 *
 * @FieldType(
 *   id = "multiple_choice",
 *   label = @Translation("Multiple choice"),
 *   default_formatter = "multiple_choice_formatter",
 *   default_widget = "multiple_choice_widget",
 * )
 */

class MultipleChoiceItem extends FieldItemBase implements FieldItemInterface {

  /**
   * {@inheritdoc}
   */
  public static function schema (FieldStorageDefinitionInterface $field_definition) {
    return [
      'columns' => [
        'question' => [
          'description' => 'The question text.',
          'type' => 'text',
          'not null' => true,
          'size' => 'big',
        ],
        'answers' => [
          'description' => 'The serialized data for the answers.',
          'type' => 'blob',
          'not null' => true,
        ],
      ],
      'indexes' => [
        'question' => ['question'],
      ],
    ];
  }

  /**
   * Generate an empty array of answers.
   */
  public static function emptyAnswers () {
    return [
      ['text' => '', 'correct' => false],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions (FieldStorageDefinitionInterface $field_definition) {
    $properties['question'] = DataDefinition::create('string')
      ->setLabel(t('Question'))
      ->setRequired(true);

    $properties['answers'] = DataDefinition::create('string')
      ->setLabel(t('Answers'))
      ->setRequired(true);

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty () {
    $question = $this->get('question')->getValue();
    return !strlen(trim($question));
  /*
    $answers = $this->get('answers')->getValue();
    if (
      !strlen(trim($question)) or
      empty($answers) or
      (is_string($answers) and empty(unserialize($answers)))
     ) {
      return true;
    }
    return false;
  */
  }

  /**
   * {@inheritdoc}
   */
  public function setValue ($values, $notify = true) {
    // if it's a submission
    if (isset($values['mcdw'])) {
      $values = $values['mcdw'];
      $answers = [];

      foreach ($values['answers'] as $answer) {
        if (!empty($answer['removed']) or empty($answer['text'])) {
          continue;
        }
        $isCorrect = empty($answer['correct']) ? false : ($answer['correct'] === 'yes');
        $answers[] = [
          'text' => $answer['text'],
          'correct' => $isCorrect,
        ];
      }

      $values['answers'] = serialize($answers);
    }
    else {
      $answers = self::emptyAnswers();
      if (!empty($values['answers'])) {
        if (is_string($values['answers'])) {
          $answers = unserialize($values['answers']);
        }
        elseif (is_array($values['answers'])) {
          $answers = $values['answers'];
        }
      }
      $values['answers'] = $answers;
    }

    return parent::setValue($values, $notify);
  }

}
