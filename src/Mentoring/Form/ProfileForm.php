<?php

namespace Mentoring\Form;

use Mentoring\Form\DataTransformer\TextToTagsTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Mentoring\Validator\Constraints\TagConstraint;

class ProfileForm extends AbstractType
{
    protected $taxonomyService;

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => function (FormInterface $form) {
                $rules = ['Default'];
                $data = $form->getData();

                if ($data->isMentee()) {
                    $rules[] = 'menteeValidation';
                }

                if ($data->isMentor()) {
                    $rules[] = 'mentorValidation';
                }

                return $rules;
            },
        ));
    }

    public function __construct($taxonomyService)
    {
        $this->taxonomyService = $taxonomyService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $mentorTags = $builder
            ->create('mentorTags', 'text', [
                    'required' => false,
                    'constraints' => new TagConstraint(array('groups' => array('mentorValidation'))),
            ])
            ->addModelTransformer(new TextToTagsTransformer($this->taxonomyService, 'mentor'));

        $menteeTags = $builder
            ->create('apprenticeTags', 'text', [
                    'required' => false,
                    'constraints' => new TagConstraint(array('groups' => array('menteeValidation'))),
            ])
            ->addModelTransformer(new TextToTagsTransformer($this->taxonomyService, 'apprentice'));

        $builder
            ->add('name', 'text', ['constraints' => new NotBlank()])
            ->add('email', 'email', [
            'constraints' => [new Email()],
            ])
            ->add('isMentor', 'checkbox', [
            'required' => false,
            ])
            ->add($mentorTags)
            ->add('isMentee', 'checkbox', [
            'required' => false,
            ])
            ->add($menteeTags)
            ->add('profile', 'textarea', [
            'required' => false,
            ])
            ->add('save', 'submit')
        ;
    }

    public function getName()
    {
        return 'profile';
    }
}
