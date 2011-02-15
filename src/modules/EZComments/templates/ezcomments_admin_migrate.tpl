{include file="ezcomments_admin_menu.tpl"}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='EZComments' src='admin.gif' alt='' }</div>
    <h2>{gt text="Import comments from other modules"}</h2>
    <form class="z-form" action="{modurl modname="EZComments" type="admin" func="migrate_go"}" method="post" enctype="application/x-www-form-urlencoded">
        <div>
            <input type="hidden" name="authid" value="{insert name='generateauthkey' module='EZComments'}" />
            <fieldset>
                <legend>{gt text="Migration"}</legend>
                <div class="z-formrow">
                    <label for="ezcomments_migrate">{gt text="Module"}</label>
                    <select id="ezcomments_migrate" name="migrate">{html_options options=$selectitems}</select>
                </div>
            </fieldset>
            <div class="z-buttons z-formbuttons">
                {button src='button_ok.png' set='icons/small' __alt='Start migration' __title='Start migration'}
                <a href="{modurl modname='EZComments' type='admin' func='main'}">{img modname='core' src='button_cancel.png' set='icons/small' __alt='Cancel' __title='Cancel'}</a>
            </div>
        </div>
    </form>
</div>
