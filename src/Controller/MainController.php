<?php
// src/Controller/MainController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route ("/main",name="main")
 *
 */
class MainController extends AbstractController
{
	/**
	 * @Route ("/{parametr?}",name="mainParametr")
	 * @param Request $hi
	 * @return Response
	 */
public function index(Request $hi)
{

	return new Response(
		"<h3>".$hi->get('parametr')."</h3>"
	);
}

}
