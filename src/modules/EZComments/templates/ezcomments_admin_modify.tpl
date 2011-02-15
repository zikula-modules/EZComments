{include file="ezcomments_admin_menu.tpl"}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='EZComments' src='admin.gif' alt='' }</div>
    <h2>{gt text="Edit comment"}</h2>
    {form cssClass="z-form"}
    {formvalidationsummary}
    <fieldset>
        <legend>{gt text="Edit"}</legend>

        {if $anonname neq ''}
        <div class="z-formrow">
            {formlabel for="ezcomments_anonname" __text='Name'}
            {formtextinput id="ezcomments_anonname" text=$anonname size="32" maxLength="255"}
        </div>
        <div class="z-formrow">
            {formlabel for="ezcomments_anonmail" __text='E-mail address (will not be published)'}
            {formtextinput id="ezcomments_anonmail" text=$anonmail size="32" maxLength="255"}
        </div>
        <div class="z-formrow">
            {formlabel for="ezcomments_anonwebsite" __text='Website'}
            {formtextinput id="ezcomments_anonwebsite" text=$anonwebsite size="32" maxLength="255"}
        </div>
        {else}
        <div class="z-formrow">
            {formlabel for="ezcomments_name" __text='Commentator'}
            <span id="ezcomments_name">{usergetvar name=uname uid=$uid}</span>
        </div>
        {/if}

        <div class="z-formrow">
            {formlabel for="ezcomments_ipaddr" __text='IP address'}
            {gt text="not logged" assign=notloggedstring}
            <span id="ezcomments_ipaddr">{$ipaddr|default:$notloggedstring|safetext}</span>
        </div>
        <div class="z-formrow">
            {formlabel for="ezcomments_subject" __text='Subject'}
            {formtextinput id="ezcomments_subject" text=$subject size="32" maxLength="255"}
        </div>
        <div class="z-formrow">
            {formlabel for="ezcomments_comment" __text='Comment'}
            {formtextinput id="ezcomments_comment" textMode="multiline" rows="10" cols="50" text=$comment size="32"}
        </div>
        <div class="z-formrow">
            {formlabel for="ezcomments_status" __text='Status'}
            {formdropdownlist id="ezcomments_status" items=$statuslevels selectedValue=$status}
        </div>
        <div class="z-formrow">
            {formlabel for="ezcomments_sendmeback" __text='Send me back to the commented content after finishing'}
            {formcheckbox id="ezcomments_sendmeback" checked=$redirect}
        </div>

    </fieldset>

    <div class="z-buttons z-formbuttons">
        {formbutton id="submit" commandName="submit" __text="Update"}
        {formbutton id="delete" commandName="delete" __text="Delete"}
        {formbutton id="cancel" commandName="cancel" __text="Cancel"}
    </div>

    {/form}
</div>

