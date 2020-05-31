<?php

declare(strict_types=1);

namespace App\ReadModel\Barbershop\Filter;

use App\Model\Barbershop\Entity\Barbershop\Barbershop;
use App\ReadModel\Barbershop\CompanyFetcher;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Form extends AbstractType
{
    private const PER_PAGE = [
        '5' => 5,
        '10' => 10,
        '25' => 25,
        '50' => 50,
    ];

    private $companies;

    public function __construct(CompanyFetcher $companies)
    {
        $this->companies = $companies;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', Type\TextType::class, ['required' => false, 'attr' => [
                'placeholder' => 'Name',
                'onchange' => 'this.form.submit()',
            ]])
            ->add('adress', Type\TextType::class, ['required' => false, 'attr' => [
                'placeholder' => 'Adress',
                'onchange' => 'this.form.submit()',
            ]])
            ->add('companyName', Type\ChoiceType::class, [
                'choices' => array_flip($this->companies->assoc()),
                'required' => false,
                'placeholder' => 'All companies',
                'attr' => ['onchange' => 'this.form.submit()'],
            ])
            ->add('status', Type\ChoiceType::class, ['choices' => [
                'Active' => Barbershop::ACTIVE,
                'Archived' => Barbershop::ARCHIVED,
            ], 'required' => false, 'placeholder' => 'All statuses',
                'attr' => ['onchange' => 'this.form.submit()'],
            ])
            ->add('perPage', Type\ChoiceType::class, [
                'choices' => self::PER_PAGE,
                'required' => true, 'placeholder' => 'Members per page',
                'attr' => ['onchange' => 'this.form.submit()'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Filter::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }
}
