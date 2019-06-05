<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class BoardType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $movables = $options['data'];
        foreach ($movables as $movable) {
            $coords = $movable->getCoords();
            $builder->add($coords->__toString(), SubmitType::class, [
                'label' => false,
                'attr' => [ 'class' => 'movable-cube']
            ]);
        }
    }
}
