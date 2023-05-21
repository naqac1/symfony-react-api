<?php

namespace App\Controller;
  
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\;
  
/**
 * @Route("/api", name="api_")
 */
  
class WorkController extends AbstractController
{
    /**
    * @Route("/Work", name="Work_index", methods={"GET"})
    */
    public function index(ManagerRegistry $doctrine): Response
    {
        $products = $doctrine
            ->getRepository(Work::class)
            ->findAll();
   
        $data = [];
   
        foreach ($products as $product) {
           $data[] = [
               'id' => $product->getId(),
               'name' => $product->getName(),
               'description' => $product->getDescription(),
           ];
        }
   
   
        return $this->json($data);
    }
  
   
    /**
     * @Route("/Work", name="Work_new", methods={"POST"})
     */
    public function new(ManagerRegistry $doctrine, Request $request): Response
    {
        $entityManager = $doctrine->getManager();
   
        $Work = new Work();
        $Work->setName($request->request->get('name'));
        $Work->setDescription($request->request->get('description'));
   
        $entityManager->persist($Work);
        $entityManager->flush();
   
        return $this->json('Created new Work successfully with id ' . $Work->getId());
    }
   
    /**
     * @Route("/Work/{id}", name="Work_show", methods={"GET"})
     */
    public function show(ManagerRegistry $doctrine, int $id): Response
    {
        $Work = $doctrine->getRepository(Work::class)->find($id);
   
        if (!$Work) {
   
            return $this->json('No Work found for id' . $id, 404);
        }
   
        $data =  [
            'id' => $Work->getId(),
            'name' => $Work->getName(),
            'description' => $Work->getDescription(),
        ];
           
        return $this->json($data);
    }
   
    /**
     * @Route("/Work/{id}", name="Work_edit", methods={"PUT", "PATCH"})
     */
    public function edit(ManagerRegistry $doctrine, Request $request, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $Work = $entityManager->getRepository(Work::class)->find($id);
   
        if (!$Work) {
            return $this->json('No Work found for id' . $id, 404);
        }
         
        $content = json_decode($request->getContent());
        $Work->setName($content->name);
        $Work->setDescription($content->description);
        $entityManager->flush();
   
        $data =  [
            'id' => $Work->getId(),
            'name' => $Work->getName(),
            'description' => $Work->getDescription(),
        ];
           
        return $this->json($data);
    }
   
    /**
     * @Route("/Work/{id}", name="Work_delete", methods={"DELETE"})
     */
    public function delete(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $Work = $entityManager->getRepository(Work::class)->find($id);
   
        if (!$Work) {
            return $this->json('No Work found for id' . $id, 404);
        }
   
        $entityManager->remove($Work);
        $entityManager->flush();
   
        return $this->json('Deleted a Work successfully with id ' . $id);
    }
}