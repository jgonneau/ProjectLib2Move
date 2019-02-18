<?php

namespace App\Command;

use App\Entity\Role;
use App\Entity\User;
use App\Entity\UserRole;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateAdminCommand extends Command
{
    protected static $defaultName = 'app:create-admin';
    private $entityManager;
    private $encoder;

    public function __construct(EntityManagerInterface $entityManager , UserPasswordEncoderInterface $encoder )
    {
        $this->entityManager = $entityManager ;
        $this->encoder = $encoder ;
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
        $role = new Role();

        $role->setNomRole('ROLE_ADMIN');
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

        $io->success( sprintf('Administrateur créé avec cette e-mail: %s', $email));
    }
}
