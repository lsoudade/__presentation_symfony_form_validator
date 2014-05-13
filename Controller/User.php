<?php

namespace Project\Controller;

use Project\Form\UserForm;

class User extends Controller
{
    public function create()
    {
        $form = (new UserForm)->build();
        
        $form->handleRequest($this->request);

        if ($form->isValid()) {
                                
            $user = $this->app['manager.user']->create($form->getData());
            
            return $this->app->redirect($this->app['url_generator']->generate('homepage'));
        }
        
        // Display the form
        return $this->render('form', array('form' => $form->createView()));
    }
    
    public function update()
    {
        $user = $this->app['manager.user']->find(1);
        
        $form = (new UserForm)->build($user);
        
        $form->handleRequest($this->request);

        if ($form->isValid()) {
                                
            $user = $this->app['manager.user']->update($form->getData());
            
            return $this->app->redirect($this->app['url_generator']->generate('homepage'));
        }
        
        // Display the form
        return $this->render('form', array('form' => $form->createView()));
    }
}