<?php

namespace Ganguera\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\HttpFoundation\RedirectResponse;
use Ganguera\FrontendBundle\Entity\Usuario;
use Ganguera\BackendBundle\Form\UsuarioType;
use Ganguera\FrontendBundle\Form\UsuarioEditType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends Controller {

    public function registroAction() {
        $request = $this->getRequest();
        if ($this->get('security.context')->isGranted('ROLE_USUARIO')) {
            $request->getSession()->getFlashBag()->set('error', 'No tienes los permisos para estar aqui.');
            throw new NotFoundHttpException('Que intentas hacer perra.');
        }
        $usuario = new Usuario();
        $form = $this->createForm(new UsuarioType(), $usuario);
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->getRepository('FrontendBundle:Usuario')->insertar(
                        $request->getClientIp(), $usuario, $this->get('security.encoder_factory'));
//                $file = $form['imgavatar']->getData();
//                $dir = $this->container->getParameter('ganguera.upload_avatar_user');
//                $name = $file->getClientOriginalName();
//                $file->move($dir, $name);
//                $usuario->setImgavatar($name);
                $em->persist($usuario);
                $em->flush();
                $session = $request->getSession();
                $session->getFlashBag()->set('bueno', 'Congratulaciones ha sido registrado con exito en nuestro sitio!' .
                        ' Bienvenido!'
                );
                return $this->redirect($this->generateUrl(
                                        'frontend_usuario_detalles', array('id_user' => $usuario->getId())));
            }
        }
        return $this->render('FrontendBundle:Usuario:registro.html.twig', array(
                    'title' => 'Registrarse',
                    'form' => $form->createView()
        ));
    }

    function detallesUserAction($id_user) {
        $em = $this->getDoctrine()->getManager();
        $usuario = $em->find('FrontendBundle:Usuario', $id_user);
        if (!$usuario) {
            $this->getRequest()->getSession()->getFlashBag()->set('error', 'El usuario no existe.');
            /* throw new NotFoundHttpException(
              'Que intentas hacer perra el usuario no existe.'); */
            return $this->redirect($this->generateUrl('frontend_iniciopage'));
            //return new RedirectResponse($this->generateUrl('frontend_iniciopage'));
        }

        $is_update = false;
        $form = null;
        if ($this->get('security.context')->isGranted('ROLE_USUARIO')) { // si no es un anonymous IS_AUTHENTICATED_ANONYMOUSLY
            $user_token = $this->get('security.context')->getToken()->getUser();

            if ($user_token->getId() == $usuario->getId()) {
                $is_update = true;
                $request = $this->getRequest();
                $formulario = $this->createForm(new UsuarioEditType(), $usuario);
                if ($request->getMethod() == 'POST') {
                    $formulario->bind($request);
                    if ($formulario->isValid()) {
                        if ($usuario->getDeclaracion() == "") {
                            $usuario->setDeclaracion("ninguna");
                        }
                        if ($usuario->getDireccion() == "") {
                            $usuario->setDireccion("");
                        }
                        die("solo tu y yo");
                        $em->persist($usuario);
                        $em->flush();
                    }
                }
                $form = $formulario->createView();
            }
        }
        return $this->render('FrontendBundle:Usuario:detalles.html.twig', array(
                    'usuario' => $usuario,
                    'title' => 'Detalles del usuario ' . $usuario,
                    'update' => $is_update,
                    'formulario' => $form
        ));
    }
}