<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Bookmark;
use AppBundle\Entity\VideoBookmark;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\View; // Utilisation de la vue de FOSRestBundle


/**
 * Bookmark REST controller.
 */
class BookmarkRESTController extends Controller
{
    /**
    * Lists all bookmarks
    * @Rest\View()
    * @Rest\Get("/bookmarks")
    */
    public function getBookmarksAction()
    {
        $em = $this->getDoctrine()->getManager();
        $bookmarks = $em->getRepository('AppBundle:Bookmark')->findAll();
        if (empty($bookmarks)) {
            return new JsonResponse(['message' => 'Bookmarks not found'], Response::HTTP_NOT_FOUND);
        }
        $formatted = [];
        foreach ($bookmarks as $bookmark) {
            $formatted[] = [
                'id' => $bookmark->getId(),
                'title' => $bookmark->getTitle(),
                'url' => $bookmark->getUrl(),
                'authorName' => $bookmark->getAuthorName(),
                'width' => $bookmark->getWidth(),
                'height' => $bookmark->getHeight(),
                'addDate' => $bookmark->getAddDate()
            ];
        }

        $view = View::create($formatted);
        $view->setFormat('json');
        return $view;
    }

    /**
    * Get a bookmark
    * @Rest\View()
    * @Rest\Get("/bookmark/{id}")
    */
    public function getBookmarkAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $bookmark = $em->getRepository('AppBundle:Bookmark')->findBookmark($request->get('id'));
        if (empty($bookmark)) {
            return new JsonResponse(['message' => 'Bookmarks not found'], Response::HTTP_NOT_FOUND);
        }

        $formatted = [
            'id' => $bookmark->getId(),
            'title' => $bookmark->getTitle(),
            'url' => $bookmark->getUrl(),
            'authorName' => $bookmark->getAuthorName(),
            'width' => $bookmark->getWidth(),
            'height' => $bookmark->getHeight(),
            'addDate' => $bookmark->getAddDate()
        ];

        $view = View::create($formatted);
        $view->setFormat('json');
        return $view;
    }

    /**
    * Creates a new bookmark
    * @Rest\View()
    * @Rest\Post("/bookmark")
    */
    public function addBookmarkAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $datas = json_decode($request->getContent(), true);

        if (empty($datas)) {
            return new JsonResponse(['message' => 'No data sent. Update is impossible'], Response::HTTP_NOT_FOUND);
        } else {
            try {
                // Update Bookmark
                $bookmark = new Bookmark();
                if(Bookmark::VIDEO_TYPE == $datas['type']){
                    $bookmark = new VideoBookmark();
                    $bookmark->setDuration($datas['duration']);
                }
                $bookmark->setTitle($datas['title']);
                $bookmark->setUrl($datas['url']);
                $bookmark->setAuthorName($datas['authorName']);
                $bookmark->setWidth($datas['width']);
                $bookmark->setHeight($datas['height']);
                $bookmark->setAddDate(new \DateTime('now'));
                $em->persist($bookmark);
                $em->flush();

                // Return new id
                $view = View::create(["id" => $bookmark->getId()]);
                $view->setFormat('json');
                return $view;
            } catch (Exception $e) {
                return new JsonResponse(['message' => 'Add is impossible : '.$e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            return new JsonResponse(['message' => 'Bookmark added'], Response::HTTP_ACCEPTED);
        }
    }

    /**
    * Edit an existing bookmark entity.
    * @Rest\View()
    * @Rest\Put("/bookmark")
    */
    public function updateBookmarkAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $datas = json_decode($request->getContent(), true);
        if (empty($datas)) {
            return new JsonResponse(['message' => 'No data sent. Update is impossible'], Response::HTTP_NOT_FOUND);
        }

        $bookmark = $em->getRepository('AppBundle:Bookmark')->findBookmark($datas['id']);
        if (empty($bookmark)) {
            return new JsonResponse(['message' => 'Bookmark not found. Update is impossible'], Response::HTTP_NOT_FOUND);
        } else {
            try {
            $bookmark->setTitle($datas['title']);
            $bookmark->setUrl($datas['url']);
            $bookmark->setAuthorName($datas['authorName']);
            $bookmark->setWidth($datas['width']);
            $bookmark->setHeight($datas['height']);
            if($bookmark instanceof \AppBundle\Entity\VideoBookmark) {
                $bookmark->setDuration($datas['duration']);
            }

            $em->flush();
            } catch (Exception $e) {
                return new JsonResponse(['message' => 'Update is impossible : '.$e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            return new JsonResponse(['message' => 'Bookmark updated'], Response::HTTP_ACCEPTED);
        }
    }

  /**
   * Deletes an bookmark entity.
   * @Rest\View()
   * @Rest\Delete("/bookmark/{id}")
   */
  public function deleteBookmarkAction(Request $request)
  {
      $em = $this->getDoctrine()->getManager();
      $bookmark = $em->getRepository('AppBundle:Bookmark')->findBookmark($request->get('id'));
      if (empty($bookmark)) {
          return new JsonResponse(['message' => 'Bookmark not found. Delete is impossible'], Response::HTTP_NOT_FOUND);
      } else {
          $em = $this->getDoctrine()->getManager();
          $em->remove($bookmark);
          $em->flush($bookmark);
          return new JsonResponse(['message' => 'Bookmark deleted'], Response::HTTP_ACCEPTED);
      }
  }
}
