<?php

namespace Ganguera\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Ganguera\FrontendBundle\Entity\Oferta;
use Ganguera\FrontendBundle\Form\OfertaType;
use Ganguera\FrontendBundle\Form\BuscarOfertaType;
use Ganguera\FrontendBundle\Form\OfertaBusqueda;

/**
 * Description of OfertasRssController
 *
 * @author yandry
 */
class OfertasRssController extends Controller {

    public function recientesAction($prov) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();

        $prov_act = $em->getRepository('FrontendBundle:Provincia')->findOneByNombretoshow($prov);

        if (!$prov_act) {
            //$sesion->setFlash('info', 'Ha intentado entrar a una provincia que no existe. Guardaremos su error. (-_-)');
            return new RedirectResponse($this->generateUrl('frontend_iniciopage'));
        }
        $ofertas = $em->getRepository('FrontendBundle:Oferta')->findBy(['provincia' => $prov_act], ['datecreation' => 'DESC'], 10, 0);
        $param_render = [
            'prov' => $prov_act,
            'slug_act' => $prov,
            'ofertas' => $ofertas,
        ];
        return $this->render('FrontendBundle:OfertasRss:recientes.rss.twig', $param_render);
    }

}

?>
