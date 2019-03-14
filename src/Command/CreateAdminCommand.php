<?php

namespace App\Command;

use App\Entity\Role;
use App\Entity\User;
use App\Entity\UserRole;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Routing\RouterInterface;

class CreateAdminCommand extends Command
{
    protected static $defaultName = 'app:create-admin';
    private $entityManager;
    private $encoder;
    private $router;

    public function __construct(EntityManagerInterface $entityManager , UserPasswordEncoderInterface $encoder, RouterInterface $router)
    {
        $this->entityManager = $entityManager ;
        $this->encoder = $encoder ;
        $this->router = $router;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Creation d\'un compte d\'administrateur.')
            ->addArgument('email', InputArgument::REQUIRED, 'Email pour le compte administrateur.')
            ->addArgument('password', InputArgument::REQUIRED, 'Mot de passe pour le compte administrateur.')
            ->addArgument('nom', InputArgument::OPTIONAL, 'Nom de l\'administrateur.')
            ->addArgument('prenom', InputArgument::OPTIONAL, 'Prénom de l\'administrateur.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //Recuperation des paramètres
        $io = new SymfonyStyle( $input, $output);
        $firstname = $input->getArgument('prenom');
        $lastname = $input->getArgument('nom');
        $email = $input->getArgument( 'email');
        $password = $input->getArgument( 'password');
        $io->note(sprintf('Creation d\'un admin avec cette email: %s', $email));

        //Initialisation administrateur
        $user = new User();
        $userRole = new UserRole();
        $roleName = 'ADMIN';
        $role = $this->entityManager->getRepository(Role::class)->findOneBy(['nomRole' => $roleName]);

        if(!$role){

            $role = new Role();
            $role->setNomRole('ADMIN');
            $role->setLevelRole(3000);
            $role->setDefaultPathRedirection('/admin');

        }

        //$role->setNomRole('ROLE_ADMIN');
        $userRole->setRole($role);
        $userRole->setUser($user);

        $hash_password =  $this->encoder->encodePassword($user, $password);

        if ($firstname == null)
        {
            $firstname = "";
        }
        if ($lastname == null)
        {
            $lastname = "";
        }
        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        $user->setEmail($email);
        $user->setPassword($hash_password);
        $user->setBirthday(new \DateTime());
        $user->setPhoneNumber("");
        $user->setLicenceNumber("");
        $user->setRoles(['ROLE_ADMIN']);

        //Creation de l'administrateur        
        $this->entityManager->persist($user);
        $this->entityManager->persist($userRole);
        $this->entityManager->persist($role);
        $this->entityManager->flush();


        //Creation des accès administrateurs
        //Recupere l'entity manager
        //$em = $this->getDoctrine()->getManager();
        /** var Router $router */
        /*
        //Recupere l'instance router
        $router = $this->get('router');

        //Recupere les routes
        $routes = $router->getRouteCollection()->all();
        
        //Tableau des routes, chemins
        $all_route_path = [];
        

        foreach ($routes as $route) {

            $access = new AccessRole();
            $routePath = $route->getPath();

            //Verification de l'existence de la route
            $existingRoutePath = $this->entityManager->getRepository(AccessRole::class)->findBy([
                'authorizationEspace' => $routePath
            ]);

            if (!preg_match('/_./', $routePath) && $existingRoutePath == null) {
                    $access->setAuthorizationEspace($routePath);
                    $access->setCreatedAt(new \DateTime());
                    $access->setCreatedBy("_generated");
                    $this->entityManager->persist($access);
                    $this->entityManager->flush();
                    
                    //array_push($all_route_path, $routePath);
            }

            if (preg_match('/.admin./', $routePath)) {

                $role = $this->entityManager->getRepository(Role::class)->findOneBy(['nomRole' => 'ADMIN']);
                $auth_path = $this->entityManager->getRepository(AccessRole::class)->findOneBy(['authorization_espace' => $routePath]);

                //Verification d'accès url
                $sql = 'INSERT INTO access_role_role 
                (access_role_id, role_id) 
                VALUES (:auth_path, :role_id);';

                $conn = $this->entityManager->getConnection(); //$this-> //->getEntityManager()->getConnection();

                $req_prep = $conn->prepare($sql);
                $req_prep->execute(['auth_path' => $auth_path->getId(), 'role_id' => $role->getId() ]);
            }


        }*/

        $io->success( sprintf('Administrateur créé avec cette e-mail: %s', $email));
    }
}
