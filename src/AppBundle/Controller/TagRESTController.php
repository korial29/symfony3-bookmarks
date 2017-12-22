<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Tag;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Bookmark;
use AppBundle\Form\BookmarkType;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\View; // Utilisation de la vue de FOSRestBundle

/**
 * Tag controller.
 */
class TagRESTController extends Controller
{
  /**
   * Lists all tags
   * @Rest\View()
   * @Rest\Get("/tags")
   */
  public function getTagsAction()
  {
      $em = $this->getDoctrine()->getManager();
      $tags = $em->getRepository('AppBundle:Tag')->findAll();
      if (empty($tags)) {
          return new JsonResponse(['message' => 'Tags not found'], Response::HTTP_NOT_FOUND);
      }

       $formatted = [];
        foreach ($tags as $tag) {
            $formatted[] = [
               'id' => $tag->getId(),
               'label' => $tag->getLabel()
            ];
        }

      $view = View::create($formatted);
      $view->setFormat('json');

      return $view;
  }

  /**
   * Creates new tags
   * @Rest\View()
   * @Rest\Post("/tags")
   */
  public function addTagsAction(Request $request)
  {
    $datas = json_decode($request->getContent(), true);

    if (empty($datas['tags'])) {
        return new JsonResponse(['message' => 'Tags not found. Add is impossible'], Response::HTTP_NOT_FOUND);
    } else if (!is_array($datas['tags'])) {
        return new JsonResponse(['message' => 'Tags not found. Add is impossible'], Response::HTTP_NOT_ACCEPTABLE);
    } else if (empty($datas['bookmarkId'])) {
        return new JsonResponse(['message' => 'bookmarkId not found. Add is impossible'], Response::HTTP_NOT_FOUND);
    } else {
        $em = $this->getDoctrine()->getManager();
        // Find bookmark
        $bookmark = $em->getRepository('AppBundle:Bookmark')->findBookmark($datas['bookmarkId']);
        if (empty($bookmark)) {
            return new JsonResponse(['message' => 'Bookmark not found. Add is impossible'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        // Add tags
        foreach($datas['tags'] as $label){
            $tag = new Tag();
            $tag->setLabel($label);
            $tag->setBookmark($bookmark);
            $em->persist($tag);
            $em->flush($tag);
        }
        return new JsonResponse(['message' => 'Tags added'], Response::HTTP_ACCEPTED);
    }
  }


    /**
    * Edit tags of a bookmark
    * @Rest\View()
    * @Rest\Put("/tags")
    */
    public function updateTagsAction(Request $request)
    {
        $datas = json_decode($request->getContent(), true);

        if (empty($datas['tags'])) {
            return new JsonResponse(['message' => 'Tags not found. Add is impossible'], Response::HTTP_NOT_FOUND);
        } else if (!is_array($datas['tags'])) {
            return new JsonResponse(['message' => 'Tags not found. Add is impossible'], Response::HTTP_NOT_ACCEPTABLE);
        } else if (empty($datas['bookmarkId'])) {
            return new JsonResponse(['message' => 'bookmarkId not found. Add is impossible'], Response::HTTP_NOT_FOUND);
        } else {
            $em = $this->getDoctrine()->getManager();
            // Find bookmark
            $bookmark = $em->getRepository('AppBundle:Bookmark')->findAllBookmarkTag($datas['bookmarkId']);
            if (empty($bookmark)) {
                return new JsonResponse(['message' => 'Bookmark not found. Add is impossible'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            // Delete old tags
            foreach($bookmark->getTags() as $tag){
                $em->remove($tag);
                $em->flush();
            }
            // Add tags
            foreach($datas['tags'] as $label){
                $tag = new Tag();
                $tag->setLabel($label);
                $tag->setBookmark($bookmark);
                $em->persist($tag);
                $em->flush();
            }
            return new JsonResponse(['message' => 'Tags added'], Response::HTTP_ACCEPTED);
        }
    }

    /**
    * Deletes a tag
    * @Rest\View()
    * @Rest\Delete("/tag/{id}")
    */
    public function deleteTagAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $tag = $em->getRepository('AppBundle:Tag')->find($request->get('id'));
        if (empty($tag)) {
            return new JsonResponse(['message' => 'Tag not found. Delete is impossible'], Response::HTTP_NOT_FOUND);
        } else {
            $em->remove($tag);
            $em->flush($tag);
            return new JsonResponse(['message' => 'Tag deleted'], Response::HTTP_ACCEPTED);
        }
    }

    /**
    * Deletes all tags from bookmark
    * @Rest\View()
    * @Rest\Delete("/tags/{bookmarkId}")
    */
    public function deleteTagsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $bookmark = $em->getRepository('AppBundle:Bookmark')->findAllBookmarkTag($request->get('bookmarkId'));
        if (empty($bookmark)) {
            return new JsonResponse(['message' => 'Bookmark id not found. Delete is impossible'], Response::HTTP_NOT_FOUND);
        } else {
            foreach($bookmark->getTags() as $tag){
                $em->remove($tag);
                $em->flush();
            }
            return new JsonResponse(['message' => 'Tags deleted'], Response::HTTP_ACCEPTED);
        }
    }
}
