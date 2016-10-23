<?php

require_once 'CRM/Admin/Form/Setting.php';
require_once 'CRM/Core/BAO/CustomField.php';

/**
 * Form controller class
 *
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC43/QuickForm+Reference
 */
class CRM_Oactivities_Form_OActivitySettings extends CRM_Admin_Form_Setting {

  /**
   * Build the form object.
   *
   * @return void
   */
  public function buildQuickForm() {
    // Get relationship types between organizations and Individuals
    $relationship_types_options = array() ;
    $activity_types_options = array() ;


    $relationship_types = civicrm_api3('RelationshipType', 'get', array(
      'sequential' => 1,
      'is_active' => 1,
      'contact_type_a' => "Individual",
      'contact_type_b' => "Organization",
    ));
    if(!empty($relationship_types['values'])) {
      foreach($relationship_types['values'] as $relationship_type) {
        $relationship_types_options[$relationship_type['id']] = $relationship_type['label_a_b'] ;
      }
      $this->add('advmultiselect','oactivity_relationship_types', ts('Relationship Types'), $relationship_types_options, true);
    }

    $activity_types = civicrm_api3('OptionValue', 'get', array(
      'sequential' => 1,
      'return' => array("id", "name"),
      'option_group_id' => "activity_type",
    ));
    if(!empty($activity_types['values'])) {
      foreach($activity_types['values'] as $activity_type) {
        $activity_types_options[$activity_type['id']] = $activity_type['name'] ;
      }
      $this->add('advmultiselect','oactivity_activity_types', ts('Activity Types'), $activity_types_options, true);
    }

    parent::buildQuickForm();
  }


  function postProcess() {
    // process all form values and save valid settings
    $values = $this->exportValues();
    // save generic settings
    CRM_Core_BAO_Setting::setItem($values['oactivity_relationship_types'],'Organization Activity Settings', 'oactivity_relationship_types');
    CRM_Core_BAO_Setting::setItem($values['oactivity_activity_types'],'Organization Activity Settings', 'oactivity_activity_types');
  }

  function setDefaultValues() {
    $defaults = array() ; 
    $settings_relationship_types =  CRM_Core_BAO_Setting::getItem('Organization Activity Settings', 'oactivity_relationship_types') ;
    $settings_activity_types =  CRM_Core_BAO_Setting::getItem('Organization Activity Settings', 'oactivity_activity_types') ;

    if(!empty($settings_relationship_types)) {
      $defaults['oactivity_relationship_types'] =  $settings_relationship_types ;
    }

    if(!empty($settings_activity_types)) {
      $defaults['oactivity_activity_types'] =  $settings_activity_types ;
    }
    return $defaults;
  }


}
