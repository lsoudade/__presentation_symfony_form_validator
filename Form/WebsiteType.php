<?php

namespace project\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class WebsiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('title', 'text', array(
            'required'    => true,
            'label'       => 'form.name',
            'constraints' => array(
                new Assert\NotBlank(),
            )
        ))
        ->add('url', 'url', array(
            'required'    => true,
            'label'       => 'form.url',
            'constraints' => array(
                new Assert\NotBlank(),
            )
        ));
    }
}
