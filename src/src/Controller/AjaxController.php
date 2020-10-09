<?
namespace App\Controller;

use App\Entity\Genres;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use MovieHTTPRequests;
use Symfony\Component\Routing\Annotation\Route;
use function GuzzleHttp\Psr7\str;


class AjaxController extends MovieController {
	protected $apikey = '15ff7d7c46d28add3e527454709aabfa';
	protected $data=[];

	/**
	 * @Route ("/ajax/reviewFilter" ,name="reviewFilter")
	 * @param Request $request
	 * @return Response
	 */
	public function reviewFilter(Request $request){
		if ($request->isXmlHttpRequest()) //если ajax
		{
			$httpReq = new MovieHTTPRequests($this->apikey,$this->getDoctrine());
			$repository = $this->getDoctrine()->getRepository(Genres::class);
			$data = $repository->findAll();
			$genres = $httpReq->normalizeData($data);
			$data = $request->request->all();
			if (strlen($data['genre']) && strlen($data['year'])) {
				$client = new MovieHTTPRequests($this->apikey,$this->getDoctrine());
				$this->data = $client->discoverMovie($data['year'],$data['genre'],$data['page']);
				$this->data['genres'] = $genres;
			}
		}else{
			$data['ERROR']  = 'no ajax req';
		}
		return $this->render('review/listOfMovies.html.twig', [
			'controller_name' => 'MovieController',
			'genres'=>$this->data['genres'],
			'data'=>$this->data,
			'total_pages'=>$this->data['total_pages'],
		]);
	}
}