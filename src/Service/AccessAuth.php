<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;


class AccessAuth extends AbstractController
{
    public function verif(Request $request, EntityManagerInterface $entityManager)
    {
        if (!$this->getUser())
        {
            return '';
        }

        //Requete pour verification d'accès url
        $sql = 'SELECT authorization_espace, nom_role
        FROM user_role ur
        LEFT JOIN user u ON ur.user_id = u.id
        LEFT JOIN role r ON r.id = ur.role_id
        LEFT JOIN access_role_role arr ON arr.role_id = r.id
        LEFT JOIN access_role ar ON ar.id = arr.access_role_id
        WHERE u.id = :user_id
        AND ar.authorization_espace = :espace
        ORDER BY r.level_role DESC
        LIMIT 1';


        //Creation connexion database
        $conn = $entityManager->getConnection(); 

        $req_prep = $conn->prepare($sql);
        $req_prep->execute(['user_id' => $this->getUser()->getId(), 'espace' => $request->server->get('REQUEST_URI') ]);
        
        //Recuperation d'autorisation
        $is_authorized = $req_prep->fetchAll();

        if ($is_authorized)
        {
            if ($request->server->get('REQUEST_URI') != "/")
            {
                return '';
            }
        }


        ////
        //Debut redirection  

        //Requete pour verification d'accès url
        $sql = 'SELECT default_path_redirection, nom_role
        FROM user_role ur
        LEFT JOIN user u ON ur.user_id = u.id
        LEFT JOIN role r ON r.id = ur.role_id
        ORDER BY r.level_role DESC
        LIMIT 1';

        //Creation connexion database
        $conn = $entityManager->getConnection();

        $req_prep = $conn->prepare($sql);
        $req_prep->execute([ 'user_id' => $this->getUser()->getId() ]);

        //Recuperation d'autorisation
        $redirection = $req_prep->fetchAll();

        if ($redirection && $redirection[0]['nom_role'] != "ADMIN")
        {
           return $redirection[0]['default_path_redirection'];
           //Non Autorisé!
        }
        return '';
        //Fin redirection
        ////
        




        /*if ($test == "141")
        {
            return $this->redirectToRoute('home');
        }
        else {
            return $this->redirectToRoute('account');
        }*/

        //return $this->getUser()->getId();

        /*$entityManager = $this->getDoctrine()->getManager();entityManager*/
        /*$ret = $this->em->getRepository(UserRole::class)->findBy(['user' => 141]);
        
        $test = '';

        foreach ($ret as $value) {
            $test .= $value->getRole()->getNomRole();
        }

        dd($ret);
        dd($test);

        return $test;*/

        //$entityManager = $this->getDoctrine()->getManager();
        //$entityManager = $this->getEntityManager();
        /*$ret = $entityManager->getRepository(UserRole::class)->findBy(['user' => $test]);

        return $ret;
        
        $a = '';

        foreach ($ret as $value) {
            $a .= $value->getRole()->getId() . '-';
        }*/

        /*$query = $entityManager->createQuery(
            'SELECT * 
            FROM App\Entity\AccessRole ar
            LEFT JOIN access_role_role arr ON arr.access_role_id = ar.id
            LEFT JOIN App\Entity\Role r ON arr.role_id = r.id
            LEFT JOIN App\Entity\UserRole ur ON ur.role_id = r.id
            LEFT JOIN App\Entity\User u ON ur.user_id = u.id
            WHERE u.id = :user_id'
        )->setParameter('user_id', $test);
        $query = $entityManager->createQuery(
            'SELECT * 
            FROM App\Entity\AccessRole'
        );*/

        ////////////////////////////////////////////////

        /*$sql = 'SELECT * 
            FROM access_role ar
            LEFT JOIN access_role_role arr ON arr.access_role_id = ar.id
            LEFT JOIN role r ON arr.role_id = r.id
            LEFT JOIN user_role ur ON ur.role_id = r.id
            LEFT JOIN user u ON ur.user_id = u.id
            WHERE u.id = :user_id';

        $conn = $entityManager->getConnection(); //$this-> //->getEntityManager()->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->execute(['user_id' => $test]);


    
        // returns an array of Product objects
        //return $query->execute();

       //l $retour_accessrolerole = $entityManager->getRepository(AccessRole::class)

        return $stmt->fetchAll();*/
        //return $this->redirect('login');
        ////////////////////////////////


       

    }
}