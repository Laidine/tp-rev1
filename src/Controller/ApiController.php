<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{
       /**
     * @Route("/listeRegions", name="listeRegions")
     */
    public function listeRegion(SerializerInterface $serializer)
    {
        $mesRegions= file_get_contents('https://geo.api.gouv.fr/regions');
        $mesRegionsTab=$serializer->decode($mesRegions,'json');
        $mesRegionsObjet=$serializer->denormalize($mesRegionsTab, 'App\Entity\Region[]','json');
        //dump($mesRegionsObjet);
        //die();
        return $this->render('api/index.html.twig', [
            'mesRegions'=>$mesRegionsTab
        ]);
        
    }

     /**
     * @Route("/listeDepsParRegion", name="listeDepsParRegion")
     */
    public function listeDepsParRegion(Request $request, SerializerInterface $serializer)
    {
        $codeRegion=$request->query>get('region');
        $mesRegions= file_get_contents('https://geo.api.gouv.fr/regions');
     
        $mesRegionsObjet=$serializer->denormalize($mesRegionsTab, 'App\Entity\Region[]','json');

        if($codeRegion == null || $codeRegion == "Toutes"){
            $mesDeps= file_get_contents('https://geo.api.gouv.fr/departements');
        }else{
            $mesDeps= file_get_contents('https://geo.api.gouv.fr/regions/'.$codeRegion.' /departements');
            
        }
        $mesDeps=$serializer->decode($mesDeps,'json');
        
        
        return $this->render('api/listDepsParRegion.html.twig', [
            'mesRegions'=>$mesRegionsTab,
            'mesDeps'=> $mesDeps
        ]);
        
    }
}