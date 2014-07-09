<?php

namespace Ganguera\FrontendBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Ganguera\FrontendBundle\Form\OfertaBusqueda;
use Ganguera\FrontendBundle\Entity\Oferta;

//use Doctrine\Common\Collections\ArrayCollection;
//use Doctrine\ORM\Query\Parameter;

class OfertaRepository extends EntityRepository {

    public function insertar(Oferta &$oferta, $usuario) {
        $oferta->setDatecreation(new \DateTime());
        $oferta->setUsuario($usuario);
    }

    public function findByUserSearch(OfertaBusqueda $oferBus, $slug_prov, $max_results = 10, $off_set = 0) {
        $em = $this->getEntityManager();
        $query = $this->createQueryBuilder('o')
                ->select('o', 'p')
                ->join('o.provincia', 'p')
                ->where('p.nombretoshow = :slugprov')
                ->setParameter('slugprov', $slug_prov);

        if (!empty($oferBus->titulo)) {
            $query->andWhere("o.titulo LIKE :titulo")
                    ->setParameter('titulo', "%" . $oferBus->titulo . "%");
        }
        if (!empty($oferBus->description)) {
            $query->andWhere('o.titulo LIKE :description')
                    ->setParameter('description', "%" . $oferBus->description . "%");
        }
        if (!empty($oferBus->costo_min)) {
            $query->andWhere('o.cost >= :costo_min')
                    ->setParameter('costo_min', $oferBus->costo_min);
        }
        if (!empty($oferBus->costo_max)) {
            $query->andWhere('o.cost <= :costo_max')
                    ->setParameter('costo_max', $oferBus->costo_max);
        }
        if (!empty($oferBus->fecha)) {
            $query->andWhere(':fecha BETWEEN o.datestart AND o.datefinish')
                    ->setParameter('fecha', $oferBus->fecha);
        }
        //$query->setMaxResults($max_results); // la cantidad de resultados
        //$query->setFirstResult($off_set);
        $query->orderBy('o.datecreation', 'DESC');

        $consulta = $query->getQuery();
        $list = $consulta->getResult();

        $n = count($list);
        if ($max_results <= 0) // !! Avoid a future division by ZERO el estupido error en el que caen muchos que van rapido y no se fijan y eso que me fije jajja porque si esto no sirve ahora a esperar unos dos o tres meses hasta enero no trabajo,all the far-away
            $max_results = 10;
        $cant_paginas = floor($n / $max_results);
        if ($n % $max_results > 0)
            $cant_paginas++;
        if ($off_set >= $cant_paginas)
            $off_set = 0;
        $ofertas_list = array();

        for ($i = $off_set * $max_results, $Size = $i + $max_results; $i < $Size && $i < $n; $i++) {
            $ofertas_list[] = $list[$i];
        }
        return array(
            'ofertas_list' => $ofertas_list,
            'cant_paginas' => $cant_paginas,
            'page' => $off_set
        );
    }

}

?>
