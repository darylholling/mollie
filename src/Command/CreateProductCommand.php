<?php

namespace App\Command;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class CreateProductCommand
 * @package App\Command
 */
class CreateProductCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * CreateProductCommand constructor.
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     */
    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    /**
     * @return void
     */
    public function configure(): void
    {
        $this->setName('product:create')
          ->setDescription('Create a new product');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \Exception
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $product = new Product();
        $helper = $this->getHelper('question');

        $name = new Question('Productnaam: ');
        $name = $helper->ask($input, $output, $name);
        $omschrijving = new Question('Omschrijving: ');
        $omschrijving = $helper->ask($input, $output, $omschrijving);
        $prijs = new Question('Prijs: ');
        $prijs = $helper->ask($input, $output, $prijs);

        $product->setName($name);
        $product->setOmschrijving($omschrijving);
        $product->setPrijs($prijs);
        $product->setImageName('');

        $errors = $this->validator->validate($product);

        if (count($errors) > 0) {
            $err = (string)$errors;
            $output->writeln($err);
        } else {
            $this->entityManager->persist($product);
            $this->entityManager->flush();
            $output->writeln('Product succesfully created!');
            $output->writeln('Do NOT forget to upload an image!!!');
        }
    }
}