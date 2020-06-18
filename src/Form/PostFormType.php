<?php

namespace App\Form;

use App\Dictionary\Constants;
use App\Entity\Post;
use App\Entity\Tag;
use App\Entity\User;
use phpDocumentor\Reflection\Types\Collection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints as Assert;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class PostFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                TextType::class,
                [
                    'empty_data' => '',
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length([
                            'max' => Constants::POST_MAX_TITLE,
                            'min' => Constants::POST_MIN_TITLE,
                        ]),
                    ],
                    'attr' => ['class' => 'form-group form-control']
                ]
            )
            ->add('tags', Select2EntityType::class, [
                'multiple' => true, // Using transformer for several tags @see Tetranz\Select2EntityBundle\Form\DataTransformer\EntitiesToPropertyTransformer
                'remote_route' => 'tags_search_autocomplete',
//                'remote_route' => 'ajax_autocomplete', @TODO  try to use bundle service
//                'remote_params' => ['var1'=>'value1'], // static route parameters for request->query
                'class' => '\App\Entity\Tag', // class to get tags from
                'primary_key' => 'id',
                'text_property' => 'name',
                'minimum_input_length' => 2,
                'page_limit' => 10,
                'allow_clear' => true,
                'delay' => 250,
                'cache' => true,
                'cache_timeout' => 60000, // if 'cache' is true
                'language' => 'en',
                'placeholder' => 'Type a tags',
                'allow_add' => [
                    'enabled' => false, // disable add new tags. only from Tags entity
                    'new_tag_text' => ' (NEW)',
                    'new_tag_prefix' => '__',
                    'tag_separators' => '[",", " "]'
                ],
                'attr' => [
                    'class' => 'form-control select2entity'
                ]
            ])
            ->add(
                'content',
                TextareaType::class,
                [
                    'empty_data' => '',
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length([
                            'max' => Constants::POST_MAX_CONTENT,
                            'min' => Constants::POST_MIN_CONTENT,
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
            'data_class' => Post::class,
//            'compound' => true,
//            'inherit_data' => true,
        ]);
    }
}
