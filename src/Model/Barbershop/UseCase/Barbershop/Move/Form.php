<?php

declare(strict_types=1);

namespace App\Model\Barbershop\UseCase\Barbershop\Move;

use App\ReadModel\Barbershop\CompanyFetcher;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Form extends AbstractType
{
    private $companies;

    public function __construct(CompanyFetcher $companies)
    {
        $this->companies = $companies;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('company', Type\ChoiceType::class, ['choices' => array_flip($this->companies->assoc())]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Command::class,
        ]);
    }
}
