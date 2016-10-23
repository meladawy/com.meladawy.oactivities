<?php

require_once 'CRM/Core/Page.php';

class CRM_Oactivities_Page_OActivities extends CRM_Core_Page {
  public function run() {
    // Example: Set the page-title dynamically; alternatively, declare a static title in xml/Menu/*.xml
    CRM_Utils_System::setTitle(ts('OActivities'));
    // Get current client id
    $cid = CRM_Utils_Request::retrieve('cid', 'Positive', $this, FALSE);
    $contacts_in_relation = array() ;
    // Admin Settings Variables
    $activity_types = $this->_getActivityTypes() ;
    $relationship_types = $this->_getRelationshipTypes() ;
    // Activities List
    $matched_activities_counter = 0;
    $matched_activities = array();

    try{
    $relation_result = civicrm_api3('Relationship', 'get', array(
      'sequential' => 1,
      'relationship_type_id' => array("IN" => $relationship_types ),
      'contact_id_b' => $cid,
    ));
    }
    catch (CiviCRM_API3_Exception $e) {
      // Handle error here.
      $errorMessage = $e->getMessage();
      $errorCode = $e->getErrorCode();
      $errorData = $e->getExtraParams();
      return array(
        'error' => $errorMessage,
        'error_code' => $errorCode,
        'error_data' => $errorData,
      );
    }

    if(!empty($relation_result['values'])) {
      foreach($relation_result['values'] as $relation_result_item) {
        $contacts_in_relation[] = $relation_result_item['contact_id_a'];
        $contact_in_relation_activities = civicrm_api3('ActivityContact', 'get', array(
          'sequential' => 1,
          'contact_id' => $relation_result_item['contact_id_a'],
          'api.Contact.get' => array(),
          'api.Activity.get' => array('activity_type_id' => array("IN" => $activity_types)),
        ));
        if(!empty($contact_in_relation_activities['values'])) {
          foreach($contact_in_relation_activities['values'] as $contact_in_relation_activity_item) {
              if(!empty($contact_in_relation_activity_item['api.Activity.get']['values'])) {
                $matched_activities[$matched_activities_counter]['activity'] = $contact_in_relation_activity_item['api.Activity.get']['values'][0];
                $matched_activities[$matched_activities_counter]['contact'] = $contact_in_relation_activity_item['api.Contact.get']['values'][0];
                $matched_activities_counter++ ;
              }
           }
        }
      }
    }
    $this->assign('activities', $matched_activities);

    parent::run();
  }
  /**
  * Protected function to retrieve the list of activity types based on settings form
  * admin/setting/oactivity
  */
  protected function _getActivityTypes() {
    $settings_activity_types =  CRM_Core_BAO_Setting::getItem('Organization Activity Settings', 'oactivity_activity_types') ;
    if(!empty($settings_activity_types)) {
      return $settings_activity_types ;
    }
    $activity_types_options = array() ;
    // If settings attributes are empty then retrieve all activities
    $activity_types = civicrm_api3('OptionValue', 'get', array(
      'sequential' => 1,
      'return' => array("id", "name"),
      'option_group_id' => "activity_type",
    ));
    if(!empty($activity_types['values'])) {
      foreach($activity_types['values'] as $activity_type) {
        $activity_types_options[] = $activity_type['id'] ;
      }
      return $activity_types_options ;
    }
  }
  /**
  * Protected function to retrieve the list of relationship types based on settings form
  * admin/setting/oactivity
  */
  protected function _getRelationshipTypes() {
    $settings_relationship_types =  CRM_Core_BAO_Setting::getItem('Organization Activity Settings', 'oactivity_relationship_types') ;
    if(!empty($settings_relationship_types)) {
      return $settings_relationship_types;
    }
    $relationship_types_options = array() ;
    // If settings attributes are empty then retrieve all types
    $relationship_types = civicrm_api3('RelationshipType', 'get', array(
      'sequential' => 1,
      'is_active' => 1,
      'contact_type_a' => "Individual",
      'contact_type_b' => "Organization",
    ));
    if(!empty($relationship_types['values'])) {
      foreach($relationship_types['values'] as $relationship_type) {
        $relationship_types_options[] = $relationship_type['id'] ;
      }
      return $relationship_types_options ;
    }
  }
}
