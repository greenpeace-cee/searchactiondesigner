<?php
/**
 * Copyright (C) 2023  Jaap Jansma (jaap.jansma@civicoop.org)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

use CRM_Searchactiondesigner_ExtensionUtil as E;

class CRM_Searchactiondesigner_Form_Configuration_Case implements CRM_Searchactiondesigner_Form_ConfigurationInterface {

  /**
   * Build the configuration form
   *
   * @param \CRM_Core_Form $form
   * @param array $configuration
   *
   * @return array
   */
  public function buildConfigurationForm(CRM_Core_Form $form, array $configuration): array {
    $summaryOptions = [
      'manage_case' => E::ts('Show on manage case screen'),
    ];
    $form->add('select', 'summary', E::ts('Summary'), $summaryOptions, FALSE, array(
      'style' => 'min-width:250px',
      'class' => 'crm-select2 huge',
      'placeholder' => E::ts('- Do not show on Manage Case Screen -'),
    ));
    $form->add('text', 'weight', E::ts('Weight (place of the action in the menu)'), false);

    $defaults = [];
    if (isset($configuration['summary'])) {
      $defaults['summary'] = $configuration['summary'];
    }
    $defaults['weight'] = 0;
    if (!empty($configuration['weight'])) {
      $defaults['weight'] = $configuration['weight'];
    }
    $form->setDefaults($defaults);

    return ['summary', 'weight'];
  }

  /**
   * Process the submitted values.
   *
   * @param array $submittedValues
   *
   * @return array
   */
  public function processSubmittedValues(array $submittedValues): array {
    $configuration = [];
    if (isset($submittedValues['summary'])) {
      $configuration['summary'] = $submittedValues['summary'];
    }
    if (isset($submittedValues['weight'])) {
      $configuration['weight'] = $submittedValues['weight'];
    }
    return $configuration;
  }

}
