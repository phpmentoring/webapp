<?php

namespace Mentoring\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ConversationStartForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subject', TextType::class, [
                'required' => true,
                'constraints' => new NotBlank()
            ])
            ->add('body', TextareaType::class, [
                'label' => 'Message',
                'required' => true,
                'constraints' => new NotBlank()
            ])
            ->add('to', HiddenType::class, [
                'required' => false
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function getName()
    {
        return 'start_conversation';
    }
}
