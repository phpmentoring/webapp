<?php

namespace Mentoring\Form;

use Mentoring\Form\DataTransformer\TextToTagsTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;

class ProfileForm extends AbstractType
{
    protected $taxonomyService;

    public function __construct($taxonomyService)
    {
        $this->taxonomyService = $taxonomyService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $mentorTags = $builder
            ->create('mentorTags', 'text')
            ->addModelTransformer(new TextToTagsTransformer($this->taxonomyService, 'mentor'))
        ;
        $builder
            ->add('name', 'text')
            ->add('email', 'email', [
                'constraints' => [new Email()]
            ])
            ->add('isMentor', 'checkbox', [
                'required' => false,
            ])
            ->add($mentorTags)
            ->add('isMentee', 'checkbox', [
                'required' => false,
            ])
            ->add(
                $builder
                    ->create('apprenticeTags', 'text', ['required' => false])
                    ->addModelTransformer(new TextToTagsTransformer($this->taxonomyService, 'apprentice'))
            )
            ->add('profile', 'textarea', [
                'required' => false
            ])
            ->add('save', 'submit')
        ;
    }

    public function getName()
    {
        return 'profile';
    }
}
