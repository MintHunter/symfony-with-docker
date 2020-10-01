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
		$this->getConfiguration();
		$this->doctraine = $doctraine;
		$this->em = $this->doctraine->getManager();
		/*convert entity obj to array*/
		$this->serializer = new Serializer([new ObjectNormalizer()]);

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
		return json_decode($body->getContents(), true);
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
	finds movies and converts them to array
	*/
	public function findMovieInDatabase($column, $name)
	{
		$repository = $this->doctraine->getRepository(MovieDataBase::class);
		$result = $repository->findOneBy([
			$column => $name
		]);
		if (!is_null($result)) {
			$body = $this->serializer->normalize($result, null);
			foreach ($body as $key => $item) {
				$result = json_decode($item);
				if (json_last_error() === JSON_ERROR_NONE) {
					$body[$key] = $result;
				}
				$body['full_poster_path']['avaible_sizes'] = $this->getImageSizes($body['posterPath'], 'poster');
				$body['full_backdrop_path']['avaible_sizes'] = $this->getImageSizes($body['backdropPath'], 'backdrop');
			}
			return $body;
		} else {
			return null;
		}
	}

	public function insertMovieDataInTable($body,$lang=null)
	{
		if(is_null($lang)){$lang='en-EN';}
		$this->movieDB->setAdult($body['adult']);
		$this->movieDB->setBackdropPath($body['backdrop_path']);
		$this->movieDB->setBelongsToCollection(json_encode($body['belongs_to_collection']));
		$this->movieDB->setBudget($body['budget']);
		$this->movieDB->setGenres(json_encode($body['genres']));
		$this->movieDB->setHomepage($body['homepage']);
		$this->movieDB->setMdbId($body['id']);
		$this->movieDB->setImdbId($body['imdb_id']);
		$this->movieDB->setOriginalLanguage($body['original_language']);
		$this->movieDB->setOriginalTitle($body['original_title']);
		$this->movieDB->setOverview($body['overview']);
		$this->movieDB->setPopularity($body['popularity']);
		$this->movieDB->setPosterPath($body['poster_path']);
		$this->movieDB->setProductionCompanies(json_encode($body['production_companies']));
		$this->movieDB->setProductionCountries(json_encode($body['production_countries']));
		$this->movieDB->setReleaseDate($body['release_date']);
		$this->movieDB->setRevenue($body['revenue']);
		$this->movieDB->setRuntime($body['runtime']);
		$this->movieDB->setSpokenLanguages(json_encode($body['spoken_languages']));
		$this->movieDB->setStatus($body['status']);
		$this->movieDB->setTagline($body['tagline']);
		$this->movieDB->setTitle($body['title']);
		$this->movieDB->setVideo($body['video']);
		$this->movieDB->setVoteAverage($body['vote_average']);
		$this->movieDB->setVoteCount($body['vote_count']);
		$this->movieDB->setLanguageVersion($lang); //
		$this->em->persist($this->movieDB);
		$this->em->flush();

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

		$body = $this->getBodyRequest($uri);
		foreach ($body['results'] as $key => $movie) {
			$body['results'][$key]['full_poster_path']['avaible_sizes'] = $this->getImageSizes($movie['poster_path'], 'poster');
			$body['results'][$key]['full_backdrop_path']['avaible_sizes'] = $this->getImageSizes($movie['backdrop_path'], 'backdrop');

		}
		return $body;

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
		}
		return $body;
	}

	public function discoverMovie($year,$genre){
		$this->setMethod("GET");
		$uri = 'discover/movie' ;
		$uri .= '?api_key=' . $this->getApikey();
	}


}