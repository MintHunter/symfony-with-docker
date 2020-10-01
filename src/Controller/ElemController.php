<?php

namespace App\Controller;

use App\Entity\MovieDataBase;
use App\Repository\MovieDataBaseRepository;
use Doctrine\DBAL\DBALException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ElemController extends AbstractController
{
	/**
	 * @Route("/elem/{title?}&{src?}", name="elem")
	 * @param Request $request
	 * @return Response
	 */
	public function index(Request $request)
	{
		$title = $request->get('title');
		$src = $request->get('src');
		$el = new MovieDataBase();
		$el->setTitle($title);
		$el->setSrc($src);
		//entity
		$em = $this->getDoctrine()->getManager();
		$em->persist($el);
		$em->flush();

		return new Response('<h2><a href="http://127.0.0.1/openserver/phpmyadmin/sql.php?server=1&db=kinoservice&table=elem&pos=0">new element was created</a></h2>');

	}

	/**
	 * @Route ("/showallelem/",name="showallelem")
	 * @param MovieDataBaseRepository $elemRep
	 * @return Response
	 */
	public function getAllElem(MovieDataBaseRepository $elemRep)
	{
		$elemArray = $elemRep->findAll();
		return $this->render('elem/index.html.twig', [
			'elems' => $elemArray
		]);

	}


	/**
	 * @Route ("/showelem/{id?}",name="showelem")
	 * @param MovieDataBaseRepository $elemRep
	 * @param Request $request
	 * @return Response
	 */
	public function showElem(MovieDataBaseRepository $elemRep, Request $request)
	{
		if ($request->get('id') === null) {
			return $this->render('elem/index.html.twig', [
				'message'=> 'Missing elem ID'
			]);
		} else {
			$id = $request->get('id');
			$elem = $elemRep->find($id);
			$elemArray = array(
				$id => [
					'id' => $elem->getId(),
					'title' => $elem->getTitle(),
					'src' => $elem->getSrc()
				]
			);
			return $this->render('elem/index.html.twig', [
				'elems' => $elemArray
			]);
		}
	}
}
