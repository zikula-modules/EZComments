<?php

declare(strict_types=1);

namespace Zikula\EZCommentsModule\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Zikula\Bundle\CoreBundle\Controller\AbstractController;
use Zikula\EZCommentsModule\Form\Config;
use Zikula\PermissionsModule\Annotation\PermissionCheck;
use Zikula\ThemeModule\Engine\Annotation\Theme;

/**
 * @Route("/config")
 */
class ConfigController extends AbstractController
{
    /**
     * @Route("/config")
     * @Theme("admin")
     * @PermissionCheck("admin")
     * @Template("@ZikulaEZCommentsModule/Config/config.html.twig")
     * @return array|RedirectResponse
     */
    public function configAction(Request $request)
    {
        $form = $this->createForm(Config::class, $this->getVars());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('save')->isClicked()) {
                $this->setVars($form->getData());
                $this->addFlash('status', 'Done! Module configuration updated.');
            }
            if ($form->get('cancel')->isClicked()) {
                $this->addFlash('status', 'Operation cancelled.');
            }

            return $this->redirectToRoute('zikulaezcommentsmodule_admin_index');
        }

        return [
            'form' => $form->createView()
        ];
    }
}
