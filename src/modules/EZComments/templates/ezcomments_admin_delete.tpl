{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="delete" size="small"}
    <h3>{gt text='Delete'}</h3>
</div>

<form class="z-form" action="{modurl modname="EZComments" type="admin" func="delete"}" method="post" enctype="application/x-www-form-urlencoded">
    <div>
        <input type="hidden" name="authid" value="{insert name='generateauthkey' module='EZComments'}" />
        <input type="hidden" name="confirmation" value="1" />
        <input type="hidden" name="id" value="{$id|safetext}" />
        <input type="hidden" name="redirect" value="{$redirect|safetext}" />
        <fieldset>
            <legend>{gt text="Confirmation prompt"}</legend>
            <div class="z-buttons z-formbuttons">
                {button src='button_ok.png' set='icons/small' __alt='Delete' __title='Delete'}
                {if $redirect neq ''}
                <a href="{$redirect|safetext}">{img modname='core' src='button_cancel.png' set='icons/small' __alt='Cancel' __title='Cancel'}</a>
                {else}
                <a href="{modurl modname='EZComments' type='admin' func='main'}">{img modname='core' src='button_cancel.png' set='icons/small' __alt='Cancel' __title='Cancel'}</a>
                {/if}
            </div>
        </fieldset>
    </div>
</form>
{adminfooter}