<?php

namespace project\Form;

use Symfony\Component\Validator\ExecutionContextInterface;
use Project\Form\DataTransformer\BooleanTransformer;
use Project\Form\DataTransformer\PasswordTransformer;

class UserForm
{
    public function build(array $data = null)
    {
        $builder = $this->app['form.factory']->createBuilder('form', $data);
        
        $builder
        ->add('username', 'text', array(
            'required'    => true,
            'label'       => 'form.username',
            'attr'        => array('class' => 'form-control'),
            'constraints' => array(
                new Assert\NotBlank(),
                new Assert\Length(array(
                    'min'        => 3,
                    'minMessage' => 'form.error.username.min'
                )),
                new Assert\Callback(array(array($this, 'uniqueUsername')))
            )
        ))
        ->add('email', 'email', array(
            'required'    => true,
            'label'       => 'form.email',
            'attr'        => array('class' => 'form-control'),
            'constraints' => array(
                new Assert\NotBlank(),
                new Assert\Email(),
                new Assert\Callback(array(array($this, 'uniqueEmail')))
            )
        ))
        ->add('website', new WebsiteType());
        
        $builder->add($builder->create('password', 'repeated', array(
            'type'            => 'password',
            'required'        => true,
            'first_options'   => array('label' => 'form.password', 'attr' => array('class' => 'form-control')),
            'second_options'  => array('label' => 'form.password_confirmation', 'attr' => array('class' => 'form-control')),
            'invalid_message' => 'form.error.passwords_not_match',
            'constraints'     => array(
                new Assert\NotBlank(),
                new Assert\Length(array(
                    'min'        => 3,
                    'minMessage' => 'form.error.passwords_length'
                ))
            )
        ))->addModelTransformer(new PasswordTransformer()) );
        
        $builder->add($builder->create('enabled', 'checkbox', array(
            'required'    => false,
            'label'       => 'form.enabled'
        ))->addModelTransformer(new BooleanTransformer()) );
    }
    
    /**
     * Callback function testing email existence in member table
     * To use as a form constraint
     * 
     * Must be public to avoid an exception
     * 
     * @param string $email Email address to test in database
     * @param \Symfony\Component\Validator\ExecutionContextInterface $context
     */
    public function uniqueEmail($email, ExecutionContextInterface $context)
    {
        if ( $this->app['manager.user']->emailExists($email) ) {
            $context->addViolation('form.error.email.exists');
        }
    }
    
    /**
     * Callback function testing username existence in member table
     * To use as a form constraint
     * 
     * Must be public to avoid an exception
     * 
     * @param string $email Email address to test in database
     * @param \Symfony\Component\Validator\ExecutionContextInterface $context
     */
    public function uniqueUsername($username, ExecutionContextInterface $context)
    {
        if ( $this->app['manager.user']->usernameExists($username) ) {
            $context->addViolation('form.error.username.exists');
        }
    }
}
