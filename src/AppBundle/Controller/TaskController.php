<?php
/**
 * Created by PhpStorm.
 * User: mateusz
 * Date: 13.01.18
 * Time: 09:59
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Category;
use AppBundle\Entity\Task;
use AppBundle\Form\CategoryType;
use AppBundle\Form\TaskType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends Controller
{
    /**
     * @Route("/tasks", name="my_task_index")
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository(Category::class)->findBy(['owner' => $this->getUser()]);
        $tasks = $em->getRepository(Task::class)->findBy(['owner' => $this->getUser()]);

        $addTaskForm = $this->createForm(
            TaskType::class,
            null,
            [
               'action'=>$this->generateUrl('my_task_add')
            ]);

        $addCategoryForm = $this->createForm(
            CategoryType::class,
            null,
            [
                'action'=>$this->generateUrl('category_add')
            ]);


        return $this->render(
            'task/index.html.twig',
            [
                'tasks' => $tasks,
                'categories' => $categories,
                'addCategoryForm' => $addCategoryForm->createView(),
                'addTaskForm'=>$addTaskForm->createView()
            ]
        );
    }

    /**
     * @Route("/tasks/add", name="my_task_add")
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function addAction(Request $request)
    {
        $task = new Task();

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if($form->isSubmitted())
        {
            if($form->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $category = $em->getRepository(Category::class)->findOneBy(['id'=>1]);

                $task
                    ->setOwner($this->getUser())
                    ->setStatus(Task::TASK_ACTIVE)
                    ->setCategory($category);


                $em->persist($task);
                $em->flush();
            }
        }



        return $this->redirectToRoute('my_task_index');
    }


    /**
     * @Route("/tasks/done/{id}", name="my_task_done", methods={"POST"})
     *
     * @param Task $task
     * @return RedirectResponse
     */
    public function doneAction(Task $task)
    {

        return $this->redirectToRoute('my_task_index');
    }

    /**
     * @Route("/tasks/edit/{id}", name="my_task_edit", methods={"POST"})
     *
     * @param Task $task
     * @return RedirectResponse
     */
    public function editAction(Task $task)
    {

        return $this->redirectToRoute('my_task_index');
    }

}