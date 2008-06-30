<?php
/**
* Return an array of items to show in the your account panel
*
* @return   array
*/
function EZComments_accountapi_getall($args)
{
  // Create an array of links to return
  pnModLangLoad('EZComments');
  $items = array(
  array(	'url'     => pnModURL('EZComments', 'admin', 'main'),
  'title'   => _EZCOMMENTS_MANAGEMYCOMMENTS,
  'icon'    => 'mycommentsbutton.gif',
  'set'     => null)
  );
  // Return the items
  return $items;
}
