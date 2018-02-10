<?php
/**
 * Created by PhpStorm.
 * User: mateusz
 * Date: 05.02.18
 * Time: 16:21
 */

namespace AppBundle\Twig;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\TwigBundle\DependencyInjection\TwigExtension;
use Twig_SimpleFunction;

class CategoryExtension extends TwigExtension
{
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('findSelectedCategory',[$this, 'findSelectedCategory'])
        ];
    }

    public function findSelectedCategory(ArrayCollection $categories, $categoryID)
    {
        foreach($categories as $category)
        {
            if($category.getId() == $categoryID)
                return $category;
        }

        return false;
    }
}