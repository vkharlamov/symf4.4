<?php

// src/Form/TagType.php
namespace App\Form;

use App\Entity\Tag;
use App\Form\DataTransformer\TagsToTagStringTransformer;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class TagType extends AbstractType
{
    /**
     * @var PostRepository
     */
    private $postRepository;
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var TagRepository
     */
    private $tagRepository;

    public function __construct(
        PostRepository $postRepository,
        TagRepository $tagRepository,
        RouterInterface $router)
    {
        $this->postRepository = $postRepository;
        $this->router = $router;
        $this->tagRepository = $tagRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new TagsToTagStringTransformer(
            $this->postRepository,
            $this->tagRepository,
            $options['finder_callback']
        ));
    }

    public function getParent()
    {
//        return ChoiceType::class;

        return TextType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
//        $resolver->setDefaults([
//            'data_class' => Tag::class,
//            'multiple' => true,
//        ]);

        $resolver->setDefaults([
            'invalid_message' => 'TAGS not found.',
//            'finder_callback' => function(tagRepository $postRepository, int $id) {
//                return $postRepository->findOneBy(['id' => $id]);
//            },
            'multiple' => true
        ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $attr = $view->vars['attr'];
        $class = isset($attr['class']) ? $attr['class'] . ' ' : '';
        // by default
        $class .= 'js-tags-select2';

        $attr['class'] = $class;
        // url to handle search request
        $attr['data-autocomplete-url'] = $this->router->generate('tags_search_utility');
//        $view->vars['attr'] = $attr;

        $view->vars['attr'] = array_merge($attr, ['data-toggle' => 'tags']);
//        dd(__METHOD__,$options);
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'tags';
    }
}