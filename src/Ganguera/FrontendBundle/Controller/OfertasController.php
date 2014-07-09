<?php

namespace Ganguera\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Ganguera\FrontendBundle\Entity\Oferta;
use Ganguera\FrontendBundle\Form\OfertaType;
use Ganguera\FrontendBundle\Form\BuscarOfertaType;
use Ganguera\FrontendBundle\Form\OfertaBusqueda;

class OfertasController extends Controller {

    public function inicioAction($page = 1) {
        //$request = $this->getRequest();
        $em = $this->getDoctrine()->getManager(); // Find por ID: $provn = $em->find('FrontendBundle:Provincia', 1);
        $provs_list_tmp = $em->getRepository('FrontendBundle:Provincia')->findAll();

        $provs_list = $this->get('ganguera.utilidades')->getParts($provs_list_tmp, 
                $this->container->getParameter('ganguera.cant_prov_x_fila'));
        $cant = $this->container->getParameter('ganguera.cant_ultimas_ofertas_inicio');
        //$page = $request->query->get('p', 1);
        if ($page < 1) {
            $page = 1;
        }
        $ult_ofertas_list = $this->listUltOfertasToShow($cant, ($page - 1) * $cant);
        if ($page != 1 && count($ult_ofertas_list) == 0) {
            return new RedirectResponse($this->generateUrl('frontend_iniciopage'));
        }
        $prev_url = '#';
        if ($page > 1) {
            $prev_url = $this->generateUrl('frontend_iniciopage') . '/' . ($page - 1); //'?p=' . ($page-1);
        }
        $param_render = array(
            'title' => 'Inicio',
            'section_act' => 'inicio',
            'provs_list' => $provs_list,
            'tamano_span' => $this->container->getParameter('ganguera.tamano_span'),
            'ult_ofertas_list' => $ult_ofertas_list,
            'page' => $page,
            'next_url' => $this->generateUrl('frontend_iniciopage') . '/' . ($page + 1), //'?p=' . ($page+1),
            'prev_url' => $prev_url
        );

        return $this->render('FrontendBundle:Inicio:inicio.html.twig', $param_render);
    }

    public function insertarAction() {
        $request = $this->getRequest();
        $oferta = new Oferta();
        $formulario = $this->createForm(new OfertaType(), $oferta);

        if ($request->getMethod() == 'POST') {
            $formulario->bind($request);
            if ($formulario->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->getRepository('FrontendBundle:Oferta')->insertar($oferta, $this->get('security.context')->getToken()->getUser());
                $em->persist($oferta);
                $em->flush();
                $session = $request->getSession();
                $session->getFlashBag()->set('bueno', 'La oferta ha sido publicada correctamente!');

                return $this->redirect($this->generateUrl('frontend_ver_oferta', array('id_oferta' => $oferta->getId())));
            }
        }
        $param_render = array(
            'title' => 'Publicar Oferta',
            'section_act' => 'insertar_oferta',
            'formulario' => $formulario->createView()
        );
        return $this->render('FrontendBundle:Ofertas:insertar_oferta.html.twig', $param_render);
    }

    public function provinciaOfertasAction($prov) {
        //$request = $this->getRequest();
        //$sesion = $request->getSession();
        $em = $this->getDoctrine()->getManager();
        $prov_act = $em->getRepository('FrontendBundle:Provincia')->findOneByNombretoshow($prov);

        if (!$prov_act) {
            //$sesion->setFlash('info', 'Ha intentado entrar a una provincia que no existe. Guardaremos su error. (-_-)');
            return new RedirectResponse($this->generateUrl('frontend_iniciopage'));
        }

        $formulario = $this->createForm(new BuscarOfertaType(), new OfertaBusqueda());
        $param_render = [
            'title' => $prov_act,
            'slug_act' => $prov,
            'url_get_op' => $this->generateUrl('frontend_get_ofertas_prov'),
            'formulario' => $formulario->createView()
        ];
        return $this->render('FrontendBundle:Ofertas:listado_ofertas_x_provincia.html.twig', $param_render);
    }

    /**
     * Obtiene por Ajax las ofertas de una provincia dada
     * 
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function getOfertasProvAction() {
        $request = $this->getRequest();
        $slug_prov = $request->request->get('slug', '');
        if (!$request->isXmlHttpRequest() || $slug_prov == '') {
            return new RedirectResponse($this->generateUrl('frontend_iniciopage'));
        }
        $em = $this->getDoctrine()->getEntityManager();

        $oferBus = new OfertaBusqueda();
        $formulario = $this->createForm(new BuscarOfertaType(), $oferBus);
        $formulario->bind($request);
        $data_json = array('list' => array());
        $errors = array();
        if ($formulario->isValid()) {
            $page = $request->request->get('page', 1) - 1;
            if ($page < 0)
                $page = 0;
            $ofertas_obj = $em->getRepository('FrontendBundle:Oferta')
                    ->findByUserSearch($oferBus, $slug_prov, $this->container->getParameter('ganguera.cant_ofertas_prov'), $page);

            $data_json['list'] = $this->listOfertasToShow($ofertas_obj['ofertas_list']);

            $data_json['page'] = $ofertas_obj['page'] + 1;
            $inicio = $data_json['page'] - 4;
            if ($inicio < 0)
                $inicio = 1;
            $fin = $data_json['page'] + 4;
            if ($fin > $ofertas_obj['cant_paginas'])
                $fin = $ofertas_obj['cant_paginas'];
            $data_json['start_index'] = $inicio;
            $data_json['end_index'] = $fin;
        }
        else {
            // *** Sacando los errores a pepe y cojincillos
            //$errors[] = 'Anie hay errores y no sabemos por el momento como atraparlos';
            $vista = $formulario->createView();

            foreach ($vista->children as $children) {
                foreach ($children->vars['errors'] as $err) :
                    $errors[] = $err->getMessage();
                endforeach;
            }
            
            foreach ($vista->vars['errors'] as $err)
                $errors[] = $err->getMessage();
            // $vista['children']['costo_min']['vars']['errors']['message']
        }
        $data_json['errors'] = $errors;
        return new Response(json_encode($data_json));
    }

    public function detallesAction($id_oferta) {
        $em = $this->getDoctrine()->getManager();
        $oferta = $em->getRepository('FrontendBundle:Oferta')->find($id_oferta);
        if (!$oferta) {
            $this->getRequest()->getSession()->getFlashBag()->set('error', 'Ha intentado entrar a una OFERTA que no existe');
            return new RedirectResponse($this->generateUrl('frontend_iniciopage'));
        }
        $param_render = array(
            'title' => 'Detalles de oferta ' . $oferta->getId(),
            'oferta' => $oferta
        );
        return $this->render('FrontendBundle:Ofertas:ver_oferta.html.twig', $param_render);
    }

    /**
     * Listado de las ultimas ofertas publicadas
     */
    private function listUltOfertasToShow($cant_ofertas_toshow, $off_set = 0) {
        $em = $this->getDoctrine()->getManager();  // getEntityManager esta en desuso desde la 2.1

        $ofertas_list = $em->getRepository('FrontendBundle:Oferta')->findBy(
                array(), array('datecreation' => 'DESC'), $cant_ofertas_toshow, $off_set);
        return $this->listOfertasToShow($ofertas_list);
    }

    /**
     * Crea nuevo listado de objetos solo con los elementos necesarios para mostrar(url y label)
     */
    private function listOfertasToShow($ofertas_list) {
        $data_list = array();
        foreach ($ofertas_list as $oferta):
            $objtmp = array(
                'url' => $this->generateUrl('frontend_ver_oferta', array('id_oferta' => $oferta->getId())),
                'label' => $oferta . ''
            );
            $data_list[] = $objtmp;
        endforeach;
        return $data_list;
    }

    /**
     * Ultimas ofertas por Ajax
     */
    public function ultimasOfertasAction() {
        $request = $this->getRequest();
        if (!$request->isXmlHttpRequest()) {
            $request->getSession()->getFlashBag()->set('error', 
                    'Esta intentando acceder a un servicio sin autorizo. Lo estamos vijilando.');
            return new RedirectResponse($this->generateUrl('frontend_iniciopage'));
        }

        $cant = $this->container->getParameter('ganguera.cant_ultimas_ofertas');
        return new Response(json_encode($this->listUltOfertasToShow($cant)));
    }

    /**
     * Ultimas ofertas de la provincia por Ajax del usuario autenticado
     */
    public function ultimasOfertasProvAction() {
        $request = $this->getRequest();
        if (!$request->isXmlHttpRequest()) {
            $request->getSession()->getFlashBag()->set('error', 
                    'Esta intentando acceder a un servicio sin autorizo. Lo estamos vijilando.');
            return new RedirectResponse($this->generateUrl('frontend_iniciopage'));
        }

        $em = $this->getDoctrine()->getEntityManager();
        $slug_prov = $request->request->get('slug', '');
        if ($slug_prov == '') {
            $request->getSession()->getFlashBag()->set('error', 'Esta intentando acceder a una provincia inexistente.');
            return new RedirectResponse($this->generateUrl('frontend_iniciopage'));
        }

        $prov = $em->getRepository('FrontendBundle:Provincia')->findByNombretoshow($slug_prov);
        $cant_ofertas_toshow = $this->container->getParameter('ganguera.cant_ultimas_ofertas');
        $ofertas_list = $em->getRepository('FrontendBundle:Oferta')->findBy(
                array('provincia' => $prov), array('datecreation' => 'DESC'), $cant_ofertas_toshow, 0);

        return new Response(json_encode($this->listOfertasToShow($ofertas_list)));
    }
}