<div class="crm-block crm-form-block crm-mysettings-form-block">
  <div class="crm-submit-buttons">{include file="CRM/common/formButtons.tpl" location="top"}</div>
 
<fieldset>
    <table class="form-layout">
        <tr class="crm-mysettings-form-block-specialty">
          <td class="label">{$form.oactivity_relationship_types.label}</td>
          <td>
            {$form.oactivity_relationship_types.html}
          </td>
        </tr>
         <tr class="crm-mysettings-form-block-recipient">
          <td class="label">{$form.oactivity_activity_types.label}</td>
          <td>
            {$form.oactivity_activity_types.html}
          </td>
        </tr>
   </table>
 
    <div class="crm-submit-buttons">{include file="CRM/common/formButtons.tpl" location="bottom"}</div>
</fieldset>
 
</div>
