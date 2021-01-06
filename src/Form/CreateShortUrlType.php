<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateShortUrlType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'url',
            UrlType::class,
            [
                'label' => 'Enter url',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => 'form-control mb-2', 'placeholder' => 'example.com'],
            ]
        )
            ->add(
                'expireAt',
                DateTimeType::class,
                [
                    'date_label' => 'Expire at',
                    'label_attr' => ['class' => 'form-label'],
                    'attr'       => ['class' => 'form-select'],
                ]
            )
            ->add(
                'save',
                SubmitType::class,
                ['label' => 'Save', 'attr' => ['class' => 'btn btn-primary mt-4']]
            );
    }
}