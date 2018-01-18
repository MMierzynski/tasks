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
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
        $cid = $request->query->get('cid');


        $tasks = $em->getRepository(Task::class)->findBy(['owner' => $this->getUser(),'status'=>Task::TASK_ACTIVE],['expiresAt'=>'ASC']);

        if(isset($cid))
        {
            $tasks = $em->getRepository(Task::class)->findBy(['owner'=>$this->getUser(), 'category'=>$cid, 'status'=>Task::TASK_ACTIVE],['expiresAt'=>'ASC']);
        }


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
     * @Route("/tasks/show/{id}", name="my_task_show")
     *
     * @param Task $task
     * @return Response
     */
    public function showAction(Task $task)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $doneForm = $this->createFormBuilder()
            ->setAction($this->generateUrl('my_task_done',['id'=>$task->getId()]))
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label'=>'Done'
                ])
            ->getForm();

        $deleteForm = $this->createFormBuilder()
            ->setAction($this->generateUrl('my_task_delete',['id'=>$task->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label'=>'Delete',
                    'attr'=>[
                        'class'=>'btn btn-danger'
                    ]
                ]
            )
            ->getForm();

        return $this->render('task/details.html.twig',
            [
                'task'=>$task,
                'doneForm'=>$doneForm->createView(),
                'deleteForm'=>$deleteForm->createView()
            ]);
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
        $this->denyAccessUnlessGranted('ROLE_USER');

        if($this->getUser() !== $task->getOwner())
        {
            throw new AccessDeniedException('This is not your task');
        }
        $task->setStatus(Task::TASK_COMPLETE);

        $em = $this->getDoctrine()->getManager();
        $em->persist($task);
        $em->flush();

        return $this->redirectToRoute('my_task_index');
    }

    /**
     * @Route("/tasks/edit/{id}", name="my_task_edit")
     *
     * @param Task $task
     * @return RedirectResponse
     */
    public function editAction(Task $task, Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $editForm = $this->createForm(TaskType::class, $task);

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
            'task/edit.html.twig',
            [
                'editForm'=>$editForm->createView()
            ]);
    }

    /**
     * @Route("/tasks/delete/{id}", name="my_task_delete", methods={"DELETE"})
     *
     * @param Task $task
     * @return RedirectResponse
     */
    public function deleteAction(Task $task)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        if($this->getUser() !== $task->getOwner())
        {
            throw new \Symfony\Component\Finder\Exception\AccessDeniedException('You are not owner of this task');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($task);
        $em->flush();

        return $this->redirectToRoute('my_task_index');
    }
}