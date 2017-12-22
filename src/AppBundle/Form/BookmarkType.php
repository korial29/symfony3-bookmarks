<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\CallbackTransformer;

class BookmarkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array('label' => 'bookmarks_local.main.form.title'))
            ->add('url', TextType::class, array('label' => 'bookmarks_local.main.form.url'))
            ->add('authorName', TextType::class, array('label' => 'bookmarks_local.main.form.authorName'))
            ->add('width', TextType::class, array('label' => 'bookmarks_local.main.form.width'))
            ->add('height', TextType::class, array('label' => 'bookmarks_local.main.form.height'))
            ->add('save', SubmitType::class, array('label' => 'bookmarks_local.main.form.save'))
            ->add('tags', TextType::class, array('label' => 'tag_local.main.list_title'));

        $builder->get('tags')
            ->addModelTransformer(new CallbackTransformer(
                function ($tagsAsArray) {
                    // transform the array to a string
                    if(!empty($tagsAsArray)){
                        $array = [];
                        foreach ($tagsAsArray as $tag) {
                            $array[] = $tag;
                        }
                        return implode(', ', $array);
                    }
                    return null;
                },
                function ($tagsAsString) {
                    // transform the string back to an array
                    return explode(', ', $tagsAsString);
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'translation_domain' => 'messages',
            ]
        );
    }
}
