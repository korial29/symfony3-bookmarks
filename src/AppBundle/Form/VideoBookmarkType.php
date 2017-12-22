<?php

namespace AppBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use AppBundle\Form\BookmarkType;

class VideoBookmarkType extends BookmarkType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('duration', TextType::class, array('label' => 'bookmarks_local.main.form.duration'));
    }
}
