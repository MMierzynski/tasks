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
                    ->setColor('#3366ff');

                $em = $this->getDoctrine()->getManager();

                $em->persist($category);
                $em->flush();

            }
        }

        return $this->redirectToRoute('my_task_index');
    }

    /**
     * @Route("/category/add-first", name="category_add_first")
     *
     * @return RedirectResponse
     */
    public function addMainCategoryAction()
    {
        $category = new Category();
        $category
            ->setName('Główne')
            ->setColor('#3366ff')
            ->setOwner($this->getUser());

        $em = $this->getDoctrine()->getManager();
        $em->persist($category);
        $em->flush();

        return $this->redirectToRoute('my_task_index');
    }

    /**
     * Route("/category/edit/{id}", name="category_edit")
     *
     * @param Category $category
     * @param Request $request
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Category $category, Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $editForm = $this->createForm(CategoryType::class, $category);

        $editForm->handleRequest($request);

        if($editForm->isSubmitted())
        {
            if($editForm->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $em->flush();

                return $this->redirectToRoute('my_task_index');
            }
        }

        return $this->render(
            'category_edit');
    }
}