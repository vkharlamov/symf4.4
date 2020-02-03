<?php

namespace App\Form;

use App\Dictionary\Constants;
use App\DTO\Comment as CommentDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class CommentFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'comment',
                TextareaType::class,
                [
                    //'mapped' => false,
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length([
                            'max' => Constants::COMMENT_MAX_LENGTH,
                            'min' => Constants::COMMENT_MIN_LENGTH,
                        ]),
                    ],
                    'attr' => ['class' => 'form-group form-control']
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CommentDto::class,
//            'compound' => true,
//            'inherit_data' => true,
        ]);
    }
}
