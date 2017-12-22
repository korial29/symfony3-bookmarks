<?php

namespace AppBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\BookmarkType;

class AddBookmarkType extends BookmarkType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('title', TextType::class, array('label' => 'bookmarks_local.main.form.title'))
            ->add('url', TextType::class, array('label' => 'bookmarks_local.main.form.url'))
            ->remove('authorName', TextType::class, array('label' => 'bookmarks_local.main.form.authorName'))
            ->remove('width', TextType::class, array('label' => 'bookmarks_local.main.form.width'))
            ->remove('height', TextType::class, array('label' => 'bookmarks_local.main.form.height'))
            ->add('save', SubmitType::class, array('label' => 'bookmarks_local.main.new_title'))
            ->remove('tags', TextType::class, array('label' => 'tag_local.main.list_title'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => array('urlOnly'),
        ));
    }
}
