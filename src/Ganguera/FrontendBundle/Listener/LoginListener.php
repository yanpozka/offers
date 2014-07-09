<?php

namespace Ganguera\FrontendBundle\Listener;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;

/**
 * Description of LoginListener
 *
 * @author yandry
 */
class LoginListener {

    private $router;
    private $usuario = null;

    public function __construct(Router $router) {
        $this->router = $router;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event) {
        $this->usuario = $event->getAuthenticationToken()->getUser();
        $this->usuario->setDatelastvisit(new \DateTime());
    }

    public function onKernelResponse(FilterResponseEvent $event) {
        if (null != $this->usuario) {
            $this->usuario->setIplastvisit($event->getRequest()->getClientIp());
            
            $ofertas_prov = $this->router->generate('frontend_ver_ofertas_prov', array(
                'prov' => $this->usuario->getProvincia()->getNombretoshow()
            ));
            $event->setResponse(new RedirectResponse($ofertas_prov));
        }
    }
}