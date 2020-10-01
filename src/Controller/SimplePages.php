<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SimplePages extends AbstractController
{
	/**
	 * @Route ("/about",name="about");
	 */
	public function about(){
		return $this->render("about/index.html.twig",[
			'controller_name' => 'SimplePages',
		]);
	}
	/**
	 * @Route ("/contacts",name="contacts");
	 */
	public function contacts(){
		return $this->render("contacts/index.html.twig",[
			'controller_name' => 'SimplePages',
		]);
	}
	/**
	 * @Route ("/join",name="join");
	 */
	public function join(){
		return $this->render("join/index.html.twig",[
			'controller_name' => 'SimplePages',
		]);
	}

}