<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Genres;
use MovieHTTPRequests;
use function GuzzleHttp\Psr7\str;

/**
 * @Route("/")
 */
class MovieController extends AbstractController
{
	private $movieData = [];
	protected $apikey = '15ff7d7c46d28add3e527454709aabfa';
	/**
	 * @Route ("/" ,name="movie")
	 * @param Request $request
	 * @return Response
	 */
	public function index(Request $request)
	{
		$routeName = $request->attributes->get('_route');
		$slider_section = [];
		$right_side = [];
		$under_slider_sec = [];
		$client = new MovieHTTPRequests($this->apikey,$this->getDoctrine());
		$body = $client->topRated();
		for ($i=0;$i<count($body['results']);$i++){
			if ( $i > (count($body['results'])-9)&&$i<count($body['results'])-5){
				$right_side[$i] =$body['results'][$i];
				$right_side[$i]['detail_page_link'] = $routeName.'/'.$body['results'][$i]['id'];
			}elseif ($i > count($body['results'])-4){
				$under_slider_sec[$i] = $body['results'][$i];
				$under_slider_sec[$i]['detail_page_link'] = $routeName.'/'.$body['results'][$i]['id'];
			}else{
				$slider_section[$i] = $body['results'][$i];
				$slider_section[$i]['detail_page_link']  =  $routeName.'/'.$body['results'][$i]['id'];
			}
		}
		$this->movieData['SLIDER_SECTION'] = $slider_section;
		$this->movieData['UNDER_SLIDER_SECTION'] = $under_slider_sec;
		$this->movieData['RIGHT_SIDE_SECTION'] = $right_side;
		return $this->render('movies/index.html.twig', [
			'controller_name' => 'MovieController',
			'movies' => $this->movieData
		]);
	}
	/**
	 * @Route ("/movie/{movie_id}" ,name="movie_detail")
	 * @param Request $request
	 * @return Response
	 */
	public function detail(Request $request){
		$client = new MovieHTTPRequests($this->apikey,$this->getDoctrine());
		$this->movieData = $client->getMovie($request->get('movie_id'));
    	$date = explode('-',$this->movieData['release_date']);
    	$category = '';
    	foreach ($this->movieData['genres'] as $key=>$genre){
    		$category.=$client->getGenre($genre)['genreName'].'/';
    		if ($key==6){break;}
    	}
		return $this->render('movies/detail.html.twig', [
			'controller_name' => 'MovieController',
			'movie' =>$this->movieData,
			'rating'=>floor($this->movieData['vote_average']*(2*1.5)),
			'date' => date("d M Y", mktime(0, 0, 0, $date[2], $date[1], $date[0])),
			'category'=>$category,
			'year_for_player' =>$date[0],
		]);
	}
	/**
	 * @Route ("/review/",name="review")
	 * @return Response
	 */
	public function review()
	{
		$httpReq = new MovieHTTPRequests($this->apikey,$this->getDoctrine());
		$repository = $this->getDoctrine()->getRepository(Genres::class);
		$data = $repository->findAll();
		$genres = $httpReq->normalizeData($data);

		$this->movieData = $httpReq->discoverMovie('2020','28','1');

		return $this->render('review/index.html.twig', [
			'controller_name' => 'MovieController',
			'genres'=>$genres,
			'data'=>$this->movieData,
			//'total_pages'=>$this->movieData['total_pages'],
		]);
	}

	/**
	 * @Route ("/updateGenres/{lang?}",name="updateGenres")
	 * @param Request $request
	 * @return Response
	 */
	public function updateGenres(Request $request)
	{
		$lang = $request->get('lang');
		if (is_null($lang)){
			$lang='en-EN';
		}
		$client = new MovieHTTPRequests($this->apikey,$this->getDoctrine());
		$body = $client->updateGenresTable($lang);
		return new Response("<pre>".print_r($body,true)."</pre>");

	}
}