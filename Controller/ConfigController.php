<?php

namespace Zikula\EZCommentsModule\Controller;


use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Zikula\Bundle\CoreBundle\Controller\AbstractController;
use Zikula\EZCommentsModule\Form\Config;

/**
 * Class ConfigController
 * @Route("/config")
 */
class ConfigController extends AbstractController
{
    /**
     * @Route("/config")
     *
     * This is a standard function to modify the configuration parameters of the module.
     *
     * @param Request $request
     * @throws AccessDeniedException Thrown if the user doesn't have admin access to the module
     * @Template("@ZikulaEZCommentsModule/Config/config.html.twig")
     * @return array|RedirectResponse
     */
    public function configAction(Request $request)
    {
       if (!$this->hasPermission($this->name . '::', '::', ACCESS_ADMIN)) {
            throw new AccessDeniedException($this->trans('You do not have pemission to access the EZComments admin interface.'));
        }

        $form = $this->createForm(Config::class, $this->getVars());

        if ($form->handleRequest($request)->isValid()) {
            if ($form->get('save')->isClicked()) {
                $this->setVars($form->getData());
                $this->addFlash('status', $this->trans('Done! Module configuration updated.'));
            }
            if ($form->get('cancel')->isClicked()) {
                $this->addFlash('status', $this->trans('Operation cancelled.'));
            }

            return $this->redirectToRoute('zikulaezcommentsmodule_admin_index');
        }

        return [
            'form' => $form->createView()
        ];
    }
}
