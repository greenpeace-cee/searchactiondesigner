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

interface CRM_Searchactiondesigner_Form_ConfigurationInterface {

  /**
   * Build the configuration form.
   *
   * Returns a list of elements to show on the form.
   *
   * @param \CRM_Core_Form $form
   * @param array $configuration
   *
   * @return array
   */
  public function buildConfigurationForm(CRM_Core_Form $form, array $configuration): array;

  /**
   * Process the submitted values.
   *
   * @param array $submittedValues
   *
   * @return array
   */
  public function processSubmittedValues(array $submittedValues): array;

}
