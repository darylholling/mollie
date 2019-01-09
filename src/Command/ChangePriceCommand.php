<?php

namespace App\Command;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ChangePriceCommand
 * @package App\Command
 */
class ChangePriceCommand extends Command
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
     * ChangePriceCommand constructor.
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
        $this->setName('product:price-change')
            ->setDescription('Create a new product')
            ->addArgument('id', InputArgument::OPTIONAL, 'Id of the product')
            ->addArgument('price', InputArgument::OPTIONAL, 'Price of the product');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $helper = $this->getHelper('question');

        if ($input->getArgument('id') == '') {
            $product = new Question('ProductID: ');
            $product = $helper->ask($input, $output, $product);
            $productPrice = new Question('New price: ');
            $productPrice = $helper->ask($input, $output, $productPrice);
        } else {
            $product = $input->getArgument('id');
            $productPrice = $input->getArgument('price');
        }

        $product = $this->entityManager->getRepository(Product::class)->findOneBy(['id' => $product]);
        $product->setPrijs($productPrice);
        $errors = $this->validator->validate($product);

        if (count($errors) > 0) {
            $err = (string)$errors;
            $output->writeln($err);
        } else {
            $this->entityManager->flush();
            $output->writeln('Product ' . strtolower($product->getName()) . '(id: ' . $product->getId() . ')' . ' heeft een nieuwe prijs van €' . $productPrice);
        }
    }
}