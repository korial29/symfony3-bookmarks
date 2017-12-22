<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Tag;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Bookmark;
use AppBundle\Form\BookmarkType;
use AppBundle\Form\VideoBookmarkType;
use AppBundle\Form\AddBookmarkType;


/**
 * Tag controller.
 */
class BookmarkController extends Controller
{
    /**
     * @Route("/", name="list_bookmarks")
     * @Method({"GET", "POST"})
     */
    public function listBookmark(Request $request)
    {
        $bookmarks = array();

        $service = $this->get('RestClientService');
        $result = $service->call($request, 'bookmarks', 'GET');
        if(!$result['error']){
            $bookmarks = $result['result'];
        }

        $bookmark = new Bookmark();
        $form = $this->createForm(AddBookmarkType::class, $bookmark);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->addBookmark($request, $bookmark);
            return $this->redirectToRoute('list_bookmarks');
        }

        return $this->render('bookmark/index.html.twig', array(
            'form' => $form->createView(),
            'bookmarks' => $bookmarks,
            'return' => true,
        ));
    }

    public function addBookmark(Request $request, Bookmark $bookmark)
    {
        $oembed = $this->get('OembedService');
        // Add oembed result to bookmark
        $oembedRes = $oembed->callOembed($bookmark->getUrl());

        if(!$oembedRes['error']){
            $formatted = $oembedRes['result'];
            $formatted['url'] = $bookmark->getUrl();
            $formatted['authorName'] = $oembedRes['result']['author_name'];

            $service = $this->get('RestClientService');
            $result = $service->call($request, 'bookmark', 'POST', $formatted);
        }
    }

    /**
     * @Route("/updateBookmark/{id}", name="updateBookmark")
     * @Method({"GET", "POST"})
     */
    public function updateBookmark(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $bookmark = $em->getRepository('AppBundle:Bookmark')->findAllBookmarkTag($id);

        if($bookmark instanceof \AppBundle\Entity\VideoBookmark) {
            $form = $this->createForm(VideoBookmarkType::class, $bookmark);
        } else {
            $form = $this->createForm(BookmarkType::class, $bookmark);
        }

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $service = $this->get('RestClientService');
            // Update Bookmark values
            $result = $service->call($request, 'bookmark', 'PUT', $bookmark);
            if(!$result['error']){
                // Update tags
                $newTags = $bookmark->getTags();
                $service->call($request, 'tags', 'PUT', ["tags" => explode(',', $newTags[0]), "bookmarkId" => $id]);
            }

            return $this->redirectToRoute('updateBookmark', array('id' => $id));
        }

        return $this->render('bookmark/edit_bookmark.html.twig', array(
            'form' => $form->createView(),
            'return' => true,
        ));
    }

    /**
     * @Route("/deleteBookmark/{id}", name="deleteBookmark")
     * @Method({"GET"})
     */
    public function deleteBookmark(Request $request, $id)
    {
        $service = $this->get('RestClientService');
        $result = $service->call($request, 'bookmark/'.$id, 'DELETE');

        return $this->redirectToRoute('list_bookmarks');
    }
}
