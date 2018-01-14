<?php
/**
 * Created by PhpStorm.
 * User: mateusz
 * Date: 13.01.18
 * Time: 18:52
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Category;
use AppBundle\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class CategoryController extends Controller
{

    /**
     * @Route("/category/add", name="category_add")
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function addAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if($form->isSubmitted())
        {
            if($form->isValid())
            {
                $category
                    ->setOwner($this->getUser())
                    ->setColor('ffffff');

                $em = $this->getDoctrine()->getManager();

                $em->persist($category);
                $em->flush();

            }
        }

        return $this->redirectToRoute('my_task_index');
    }
}