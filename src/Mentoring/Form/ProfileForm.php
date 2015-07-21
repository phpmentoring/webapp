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
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Mentoring\User\CountryService;

class ProfileForm extends AbstractType
{
    protected $taxonomyService;
    protected $countryService;

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

    public function __construct($taxonomyService, CountryService $countryService)
    {
        $this->taxonomyService = $taxonomyService;
        $this->countryService = $countryService;
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
            ->add('country', 'country', ['required' => false, 'placeholder' => 'Please choose a country'])
            ->add('city', 'text', ['required' => false])
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

        $formModifier = function (FormInterface $form, $country = null) {
            $states = null === $country ? array() : $this->countryService->fetchStatesNameByCountry($country);
            $form->add('state', 'choice', array(
                'choices'     => $states,
                'required' => false,
                'placeholder' => $country ? null : 'Please choose a state'
            ));
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                $data = $event->getData();
                $formModifier($event->getForm(), $data->getCountry());
            }
        );

        $builder->get('country')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                $country = $event->getForm()->getData();
                $formModifier($event->getForm()->getParent(), $country);
            }
        );
    }

    public function getName()
    {
        return 'profile';
    }
}
