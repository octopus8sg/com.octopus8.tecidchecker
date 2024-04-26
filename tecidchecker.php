<?php

require_once 'tecidchecker.civix.php';
// phpcs:disable
use CRM_Tecidchecker_ExtensionUtil as E;
// phpcs:enable
function tecidchecker_civicrm_config(&$config): void {
  _tecidchecker_civix_civicrm_config($config);
}

function tecidchecker_civicrm_install(): void {
  _tecidchecker_civix_civicrm_install();
}

function tecidchecker_civicrm_enable(): void {
  _tecidchecker_civix_civicrm_enable();
}

function tecidchecker_civicrm_validateForm($formName, &$fields, &$files, &$form, &$errors){
  //CHECKS IF FORM BEING SUBMITTED IS CRM_CONTRIBUTE_FORM_CONTRIBUTION
  if ($formName == 'CRM_Contribute_Form_Contribution'){
    //FINANCIAL TYPE CHECK
    $finTypeId = CRM_Utils_Array::value('financial_type_id', $fields);
    $financialType = civicrm_api4('FinancialType', 'get', ['where' => [['id', '=', $finTypeId],],], 0);
    if ($financialType['is_deductible'] == true){
      $contactID = CRM_Utils_Array::value('contact_id', $fields);
      $contactDetails = civicrm_api4('Contact', 'get', ['where' => [['id', '=', $contactID],],], 0);
      //CONTACT EXTERNAL ID CHECK
      if ($contactDetails['external_identifier'] === null || $contactDetails['external_identifier'] === ''){
        $errors['financial_type_id'] = ts( 'Donor is required to have NRIC/FIN/UEN inputted to create a TDR record');
      }
    }
    return;
  }
}