<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Manager\GameManager;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class TeamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('team', ChoiceType::class, [
                'choices' => [
                    'cross' => GameManager::CROSS_TEAM,
                    'circle' => GameManager::CIRCLE_TEAM,
                ],
                'expanded' => true,
                'multiple' => false,
            ]);
    }
}
