<?php

namespace Mentoring\Controller;

use Mentoring\User\User;
use Silex\Application;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Email;

class AccountController
{
    public function profileAction(Application $app, Request $request)
    {
        $user = $app['session']->get('user');

        $form = $this->createProfileForm($app, $user);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $app['user.manager']->saveUser($user);
            $app['session']->getFlashBag()->add('success', 'Your profile has been saved.');

            return $app->redirect($app['url_generator']->generate('account.profile'));
        }

        return $app['twig']->render('account/profile.twig', array(
            'profile_form' => $form->createView()
        ));
    }

    /**
     * @param Application $app
     * @param User $user
     * @param array $options
     * @return Form
     */
    protected function createProfileForm(Application $app, User $user, array $options = array())
    {
        /** @var \Symfony\Component\Form\FormFactory $form_factory */
        $form_factory = $app['form.factory'];

        return $form_factory
            ->createBuilder('form', $user, array_merge($options, array(
                'data_class' => 'Mentoring\User\User'
            )))
            ->add('name', 'text')
            ->add('email', 'email', [
                'constraints' => [new Email()]
            ])
            ->add('isMentor', 'checkbox', [
                'required' => false,
            ])
            ->add('isMentee', 'checkbox', [
                'required' => false,
            ])
            ->add('save', 'submit')
            ->getForm();
    }
}