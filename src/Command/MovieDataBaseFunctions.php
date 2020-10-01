<?php
/*use App\Entity\MovieDataBase;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;*/
/*Разделить функии бд и функции для API*/
class MovieDataBaseFunctions{
	protected $doctraine;
	protected $em;
	public function __construct($doctraine)
	{
		$this->doctraine = $doctraine;
		$this->em = $this->doctraine->getManager();
		/*convert entity obj to array*/
		$this->serializer = new Serializer([new ObjectNormalizer()]);
	}

	/*
	finds movies and converts them to array
	*/
/*	public function findMovieInDatabase($column, $name)
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
	}*/
/*
	public function insertMovieDataInTable($body)
	{
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
		$this->em->persist($this->movieDB);
		$this->em->flush();

	}*/
}
