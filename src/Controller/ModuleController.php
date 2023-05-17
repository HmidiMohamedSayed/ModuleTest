<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Module;
use App\Entity\Historique;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ModuleRepository;
use App\Repository\HistoriqueRepository;
use Doctrine\ORM\EntityManagerInterface;


class ModuleController extends AbstractController
{
    protected $moduleRepository;
    protected $entityManager;
    protected $historiqueRepository;
    public function __construct(EntityManagerInterface $entityManager,ModuleRepository $moduleRepository,HistoriqueRepository $historiqueRepository)
    {
        $this->moduleRepository = $moduleRepository;
        $this->historiqueRepository = $historiqueRepository;
        $this->entityManager= $entityManager;
    }

    #[Route('/modules', name: 'modules')]
    public function getAllModules(): Response
    {
        $modules = $this->moduleRepository->findAll();
        foreach($modules as $module){
            $module->setImage(base64_encode(stream_get_contents($module->getImage())));
        }
        return $this->render('module/modules.html.twig', [
            'modules' => $modules,
        ]);
    }

    #[Route('/module/{id}', name: 'module')]
    public function getModuleById($id): Response
    {

        $module = $this->moduleRepository->find($id);
        $module->setImage(base64_encode(stream_get_contents($module->getImage())));
        $criteria = [
            'moduleId' => $id
            ];
        $historiques=$this->historiqueRepository->findBy($criteria);
        $hist="";
        for($i=0;$i<count($historiques);$i++){
            $hist=$hist.strval(($i+1)*50).",".strval(200-$historiques[$i]->getMesure()).",";
        }
        $hist=substr($hist,1,-1);
        return $this->render('module/module.html.twig', [
            'module' => $module,
           'historique'=>$hist
        ]);
    }
    #[Route('/ajoutForm', name: 'ajoutForm')]
    public function afficherFormulaire(): Response
    {
        //affichage du formulaire d'ajout d'un module
        return $this->render('module/ajout.html.twig');
    }

    #[Route('/ajout', name: 'ajout')]
    public function ajouterModule(Request $request): Response
    {
        $nom=$request->request->get('nom');
        $mesure = $request->request->get('mesure');
        $duree =$request->request->get('duree');
        $nbdata =$request->request->get('nbdata');
        $description =$request->request->get('description');
        $upload = $request->files->get('file');
        if($mesure==""){$mesure=0;}
        if($duree==""){$duree=0;}
        if($nbdata==""){$nbdata=0;}
        $random=rand(1,3);
        if($random == 1 ){$etat="marche";}
        if($random == 2 ){$etat="reparation";}
        if($random == 3 ){$etat="panne";}
        $module=new Module($description,$nom,$mesure,$duree,$nbdata,$etat,file_get_contents($upload->getPathname()));
        $moduleId=$this->moduleRepository->save($module);

        return $this->redirectToRoute('modules');
    }

    #[Route('/modifierForm/{id}', name: 'modifierForm')]
    public function afficherFormulaireDeModification($id): Response
    {
        //affichage du formulaire de modification d'un module
        $module = $this->moduleRepository->find($id);
        $module->setImage(base64_encode(stream_get_contents($module->getImage())));
        return $this->render('module/modifier.html.twig', [
            'module' => $module,
        ]);
    }

    #[Route('/modifier/{id}', name: 'modifier')]
    public function modifierModule(Request $request,$id): Response
    {
        $ancienmodule = $this->moduleRepository->find($id);
        $mesure=$request->request->get('mesure');
        if($mesure != $ancienmodule->getMesure()){
            $historique = new Historique($ancienmodule->getId(),$mesure);
            $this->historiqueRepository->save($historique);
        }
        $ancienmodule->setNom($request->request->get('nom'));
        $ancienmodule->setMesure($mesure);
        $ancienmodule->setDuree($request->request->get('duree'));
        $ancienmodule->setNbdata($request->request->get('nbdata'));
        $ancienmodule->setDescription($request->request->get('description'));
        $ancienmodule->setEtat($request->request->get('etat'));
        $upload = $request->files->get('file');
        if($upload){
            $ancienmodule->setImage(file_get_contents($upload->getPathname()));
        }
        $this->entityManager->flush();
        return $this->redirectToRoute('modules');
    }
}
