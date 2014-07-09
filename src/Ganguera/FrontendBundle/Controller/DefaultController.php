<?php

namespace Ganguera\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller {

    public function servicioAction() {
        $server = new \SoapServer(NULL, ['uri' => 'Ofertassyf2/web/app_dev.php/websryan']);
        $server->setObject($this->get('ganguera.servicio_web'));
        $response = new Response();
        $response->headers->set('Content-Type', 'text/xml; charset=ISO-8859-1');
        ob_start();
        $server->handle();
        $response->setContent(ob_get_clean());

        return $response;
    }

    public function clientServicioAction() {
        $client = new \SoapClient(NULL, [
            "location" => "http://127.0.0.1/Ofertassyf2/web/app_dev.php/websryan",
            "uri" => "urn:getData"]);

        $result = $client->__soapCall('hello', [/*Parámetros aqui*/]);
        $robj = json_decode($result);
        //print_r($robj);
        return new Response($robj->yan);
    }

    public function listProvinciasAction() {
        $request = $this->getRequest();
        if (!$request->isXmlHttpRequest()) {
            throw new NotFoundHttpException(
            'Pero que tratas de hacer, prohibido,seras baneado de por vida.');
        }
        $em = $this->getDoctrine()->getManager();
        $provs_list_tmp = $em->getRepository('FrontendBundle:Provincia')->findAll();
        $data_list = array();
        foreach ($provs_list_tmp as $prov) :
            $objtmp = array(
                'url' => $this->generateUrl('frontend_ver_ofertas_prov', array('prov' => $prov->getNombretoshow())),
                'slug' => $prov->getNombretoshow(),
                'label' => $prov . ''
            );
            $data_list[] = $objtmp;
        endforeach;

        return new Response(json_encode($data_list));
    }

    public function ayudaAction() {
        $param_render = array('section_act' => 'ayuda');
        $respuesta = $this->render('FrontendBundle:Inicio:ayuda.html.twig', $param_render);
        //$respuesta->setMaxAge(500);
        return $respuesta;
    }

    public function mapasitioAction() {
        $param_render = array('section_act' => 'mapasitio');
        $respuesta = $this->render('FrontendBundle:Inicio:mapasitio.html.twig', $param_render);
        //$respuesta->setMaxAge(600);
        return $respuesta;
    }

}