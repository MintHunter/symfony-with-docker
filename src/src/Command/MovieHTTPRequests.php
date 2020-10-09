<?php

use App\Entity\MovieDataBase;
use App\Entity\Genres;
use GuzzleHttp\Client;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


class MovieHTTPRequests
{
	private $client;
	private $config;
	protected $apikey;
	protected $method;
	protected $movieDB;
	protected $genres;
	protected $doctraine;
	protected $em;
	protected $serializer;
	protected $normalaizer;

	/**
	 * @param mixed $apikey
	 */
	public function setApikey($apikey): void
	{
		$this->apikey = $apikey;
	}
	/**
	 * @return mixed
	 */
	public function getApikey()
	{
		return $this->apikey;
	}

	/**
	 * @return mixed
	 */
	public function getMethod()
	{
		return $this->method;
	}

	/**
	 * @param mixed $method
	 */
	public function setMethod($method): void
	{
		$this->method = $method;
	}

	public function __construct($apikey, $doctraine)
	{
		$this->client = new Client([
				'base_uri' => 'https://api.themoviedb.org/3/'
			]
		);
		$this->movieDB = new MovieDataBase();
		$this->setApikey($apikey);
		$this->getConfiguration(); // for images
		$this->doctraine = $doctraine;
		$this->em = $this->doctraine->getManager();
		/*convert entity obj to array*/
		$this->serializer = new Serializer([new ObjectNormalizer()]);

	}
	// database Obj resp -> array
	function normalizeData($data){
		return  $this->serializer->normalize($data, null);

	}


	/*
	 * API config for images
	 */
	public function getConfiguration()
	{
		$this->setMethod('GET');
		$uri = 'configuration';
		$uri .= '?api_key=' . $this->getApikey();

		$response = $this->client->request(
			$this->getMethod(),
			$uri

		);
		$body = $response->getBody();
		$this->config = json_decode($body->getContents(), true);
		return $this->config;
	}
	/*
	 * send request
 	*/
	public function getBodyRequest($uri)
	{

		$response = $this->client->request(
			$this->getMethod(),
			$uri

		);
		$body = $response->getBody();
		$resp =json_decode($body->getContents(), true);
		if (isset($resp['results']) && count($resp['results'])>0) {
			foreach ($resp['results'] as $key => $movie) {
				$resp['results'][$key]['full_poster_path']['avaible_sizes'] = $this->getImageSizes($movie['poster_path'], 'poster');
				$resp['results'][$key]['full_backdrop_path']['avaible_sizes'] = $this->getImageSizes($movie['backdrop_path'], 'backdrop');

			}
		}
		return $resp;
	}
	/*
	 * get images
	 * need path to image
	 * and img type backdrop, logo, poster, profile, still
	*/
	public function getImageSizes($apiPath, $imageType)
	{
		$err = false;
		$imgSizes = [];
		foreach ($this->config['images'] as $key => $sizes) {
			if (preg_match('/' . $imageType . '_sizes/', $key)) {
				foreach ($sizes as $sizeKey => $size) {
					$imgSizes[$size] = $this->config['images']['base_url'] . $size . $apiPath;
				}
				$err = false;
				break;
			} else {
				$err = "No such type found,\n Available types: backdrop, logo, poster, profile, still";
			}
		}
		if ($err) {
			return $err;
		} else {
			return $imgSizes;

		}
	}
	/*
	finds movies and converts them to array
	*/
	public function findMovieInDatabase($column, $name)
	{
		$repository = $this->doctraine->getRepository(MovieDataBase::class);
		$result = $repository->findOneBy([
			$column => $name
		]);
		if (!is_null($result)) {
			$body = $this->normalizeData($result);
			foreach ($body as $key => $item) {
				$new_key=false;
				$result = json_decode($item);
				if(preg_match('/[A-Z]/',$key,$matches)){
					$new_key = preg_replace('/[A-Z]/', '_'.strtolower($matches[0]), $key);
				}
				if (json_last_error() === JSON_ERROR_NONE) {
					$new_key ==false ? $resp[$key] = $this->normalizeData($result) :  $resp[$new_key] = $this->normalizeData($result) ;
				}else{
					$new_key ==false ? $resp[$key] = $item :  $resp[$new_key] = $item;

				}
			}
			$resp['full_poster_path']['avaible_sizes'] = $this->getImageSizes($body['posterPath'], 'poster');
			$resp['full_backdrop_path']['avaible_sizes'] = $this->getImageSizes($body['backdropPath'], 'backdrop');

			return $resp;
		} else {
			return null;
		}
	}

	public function insertMovieDataInTable($body,$lang=null)
	{
		if(is_null($lang)){$lang='en-EN';}
		!isset($body['adult']) ? : $this->movieDB->setAdult($body['adult']);
		!isset($body['backdrop_path']) ? : $this->movieDB->setBackdropPath($body['backdrop_path']);
		!isset($body['belongs_to_collection']) ? : $this->movieDB->setBelongsToCollection(json_encode($body['belongs_to_collection']));
		!isset($body['budget']) ? : $this->movieDB->setBudget($body['budget']);
		if (isset($body['genres'])) {
			$genre_ids = array();
			foreach ($body['genres'] as $key => $genre){
			$genre_ids[$key] = $genre['id'];
		}
			$body['genres']= $genre_ids;
		}elseif (isset($body['genre_ids'])){
			$body['genres'] = $body['genre_ids'];
		}
		!isset($body['genres']) ? : $this->movieDB->setGenres(json_encode($body['genres']));
		!isset($body['homepage']) ? : $this->movieDB->setHomepage($body['homepage']);
		!isset($body['id']) ? : $this->movieDB->setMdbId($body['id']);
		!isset($body['imdb_id']) ? : $this->movieDB->setImdbId($body['imdb_id']);
		!isset($body['original_language']) ? : $this->movieDB->setOriginalLanguage($body['original_language']);
		!isset($body['original_title']) ? : $this->movieDB->setOriginalTitle($body['original_title']);
		!isset($body['overview']) ? : $this->movieDB->setOverview($body['overview']);
		!isset($body['popularity']) ? : $this->movieDB->setPopularity($body['popularity']);
		!isset($body['poster_path']) ? : $this->movieDB->setPosterPath($body['poster_path']);
		!isset($body['production_companies']) ? : $this->movieDB->setProductionCompanies(json_encode($body['production_companies']));
		!isset($body['production_countries']) ? : $this->movieDB->setProductionCountries(json_encode($body['production_countries']));
		!isset($body['release_date']) ? : $this->movieDB->setReleaseDate($body['release_date']);
		!isset($body['revenue']) ? : $this->movieDB->setRevenue($body['revenue']);
		!isset($body['runtime']) ? : $this->movieDB->setRuntime($body['runtime']);
		!isset($body['spoken_languages']) ? : $this->movieDB->setSpokenLanguages(json_encode($body['spoken_languages']));
		!isset($body['status']) ? : $this->movieDB->setStatus($body['status']);
		!isset($body['tagline']) ? : $this->movieDB->setTagline($body['tagline']);
		!isset($body['title']) ? : $this->movieDB->setTitle($body['title']);
		!isset($body['video']) ? : $this->movieDB->setVideo($body['video']);
		!isset($body['vote_average']) ? : $this->movieDB->setVoteAverage($body['vote_average']);
		!isset($body['vote_count']) ? : $this->movieDB->setVoteCount($body['vote_count']);
		$this->movieDB->setLanguageVersion($lang); //
		$this->em->persist($this->movieDB);
		$this->em->flush();
		$this->em->clear();

	}

	public function updateGenresTable($lang=null){
		$repository = $this->doctraine->getRepository(Genres::class);
		if(is_null($lang)){$lang='en-US';}else{
			$newStr = explode('-',$lang);
			$lang = $newStr[0].'-'.strtoupper($newStr[1]);
		}
		$this->setMethod("GET");
		$uri = 'genre/movie/list';
		$uri .= '?api_key=' . $this->getApikey();
		$uri .= '&language=' . $lang;
		$body =  $this->getBodyRequest($uri);
		$response=array();
		foreach ($body as $genres){
			foreach ( $genres as $key=> $genre){
				$result = $repository->findOneBy([
					'genre_id' => $genre['id'],
					'lang' => $lang
				]);
				$response[]=$result;
				if(is_null($result)){
					$this->genres = new Genres();
					$this->genres->setGenreId($genre['id']);
					$this->genres->setGenreName($genre['name']);
					$this->genres->setLang($lang);
					$this->em->persist($this->genres);
					$this->em->flush();
					$response['genres'][$key][]  =$genre;
					$response['genres'][$key]['status'] = 'success update';
				}else{
					$response['genres'][$key][]  =$genre;
					$response['genres'][$key]['status'] = 'nothing to update';
				}
			}
		}

		return $response;
	}
	public function getGenre($genre_id){
		$repository = $this->doctraine->getRepository(Genres::class);
		$result = $repository->findOneBy([
			'genre_id' => $genre_id
		]);
		if (!is_null($result)) {
			return  $this->normalizeData($result);
		}else{
			$this->updateGenresTable();
			return $result['err']='genres table was updated';
		}
	}
	/*one latest movie*/
	public function getLatest($lang = null)
	{
		$this->setMethod('GET');
		$uri = 'movie/latest';
		$uri .= '?api_key=' . $this->getApikey();
		//en-US
		$uri .= '&language=' . $lang;
		return $this->getBodyRequest($uri);
	}

	public function topRated($lang = null, $page = null, $region = null)
	{
		$this->setMethod('GET');
		$uri = 'movie/top_rated';
		$uri .= '?api_key=' . $this->getApikey();
		$lang == null ?$lang='en-EN': $uri .= '&language=' . $lang;
		$page == null ?: $uri .= '&page=' . $page;
		$region == null ?: $uri .= '&region=' . $region;
		return $this->getBodyRequest($uri);

	}

	/*
	 *  get movie
	 * need id
	 */
	public function getMovie($id, $lang = null)
	{
		$this->setMethod('GET');
		$uri = 'movie/' . $id;
		$uri .= '?api_key=' . $this->getApikey();
		$lang == null ?$lang='en-EN': $uri .= '&language=' . $lang;
		if (is_null($body = $this->findMovieInDatabase('mdb_id', $id))) {
			$body = $this->getBodyRequest($uri);
			$this->insertMovieDataInTable($body,$lang);
			$body['full_poster_path']['avaible_sizes'] = $this->getImageSizes($body['poster_path'], 'poster');
			$body['full_backdrop_path']['avaible_sizes'] = $this->getImageSizes($body['backdrop_path'], 'backdrop');
		}
		return $body;
	}
	public function discoverMovie($year,$genre,$page,$lang=null){
		$this->setMethod("GET");
		$uri = 'discover/movie' ;
		$uri .= '?api_key=' . $this->getApikey();
		$lang == null ?$lang='en-EN': $uri .= '&language=' . $lang;
		$uri .= '&sort_by=popularity.desc';
		$uri .= '&include_adult=false';
		$uri .= '&include_video=false';
		$uri .= '&year=' . $year;
		$uri .= '&with_genres=' . $genre;
		$uri .= '&page=' . $page;
		$body =  $this->getBodyRequest($uri);  													// условия перепутаны местами см getMovie()
		foreach ($body['results'] as $key => $movie) {
			if (is_null($resp = $this->findMovieInDatabase('mdb_id', $movie['id']))) { 	// для мульти серча нужнен другой функционал
				$this->insertMovieDataInTable($movie);											// добавить в бд столбец с нумерацией страницы
				$body['results'][$key]['fromDataBase']  = $movie ;
			}

		}
		return $body;
	}

	public function searchMovie($name,$lang=null){
		$this->setMethod("GET");
		$uri = 'search/movie' ;
		$uri .= '?api_key=' . $this->getApikey();
		$lang == null ?$lang='en-EN': $uri .= '&language=' . $lang;
		$uri .= '&query='.$name;
		$body =  $this->getBodyRequest($uri);													// условия перепутаны местами см getMovie()
		//foreach ($body['results'] as $key => $movie) {
			//if (is_null($body = $this->findMovieInDatabase('mdb_id', $movie['id']))) {   //для мульти серча нужнен другой функционал
			//	$this->insertMovieDataInTable($movie);											// добавить в бд столбец с нумерацией страницы, для отслеживания групп фильмов
			//}

		//}
		return $body;
	}


}