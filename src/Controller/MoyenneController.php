<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EtudiantRepository;


class MoyenneController extends AbstractController
{
    /**
     * @Route("/moyenne", name="list_moyenne")
     */
    public function index(EtudiantRepository $etudiantRepository)
    {
        $etudiants = $etudiantRepository->findAll();
        $notesEtudianteJson = [];

        foreach( $etudiants as  $etudiant){
            $somme = 0;
            $sommeCofficient = 0;
            $moyenne = 0;
            $idEtudiant = $etudiant->getId();
            $notes = $etudiant->getNotes();


          
            foreach($notes as $note){
                $noter = $note->getNote();
                $cofficient = $note->getCofficient();
                $somme = $somme + ($noter * $cofficient);
                $sommeCofficient = $sommeCofficient +  $cofficient;
                $moyenne = $somme / $sommeCofficient;

                $entityManager = $this->getDoctrine()->getManager();
                $persisteMoyenne = $etudiantRepository->find( $idEtudiant );
                $persisteMoyenne->setMoyenne( $moyenne);
                $entityManager->persist($persisteMoyenne);
                $entityManager->flush();
            }
        }

        
        foreach( $etudiants as  $etudiant){

            $objetFinale = [
                "id" => $etudiant->getId(),
                "nom" => $etudiant->getNom(),
                "prenom" => $etudiant->getPrenom(),
                "moyenne" => $etudiant->getMoyenne(),
            ];
            
            $notesEtudianteJson[] =  $objetFinale;
        }



        return $this->render('moyenne/index.html.twig',[
            'etudiantes' => $notesEtudianteJson
        ]);
    }
}
