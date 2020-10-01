<?php
namespace App\Entity;

use App\Repository\MovieDataBaseRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MovieDataBaseRepository::class)
 */
class MovieDataBase
{
	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $adult;
	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $backdrop_path;
	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $belongs_to_collection;
	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $budget;
	/**
	 * @ORM\Column(type="text",  nullable=true)
	 */
	private $genres;
	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $homepage;
	/**
	 * @ORM\Column(type="integer", nullable=true)
	 */
	private $mdb_id;
	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $imdb_id;
	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $original_language;
	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $original_title;
	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $overview;
	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $popularity;
	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $poster_path;
	/**
	 * @ORM\Column(type="text", length=255, nullable=true)
	 */
	private $production_companies;
	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $production_countries;
	/**
	 * @ORM\Column(type="text",  nullable=true)
	 */
	private $release_date;
	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $revenue;
	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $runtime;
	/**
	 * @ORM\Column(type="text",  nullable=true)
	 */
	private $spoken_languages;
	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $status;
	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $tagline;
	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $title;
	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $video;
	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $vote_average;
	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $vote_count;
	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $language_version;



	public function getId(): ?int
	{
		return $this->id;
	}

	/**
	 * @return mixed
	 */
	public function getAdult(): ?string
	{
		return $this->adult;
	}

	/**
	 * @param mixed $adult
	 */
	public function setAdult($adult): void
	{
		$this->adult = $adult;
	}

	/**
	 * @return mixed
	 */
	public function getBackdropPath()
	{
		return $this->backdrop_path;
	}

	/**
	 * @param mixed $backdrop_path
	 */
	public function setBackdropPath($backdrop_path): void
	{
		$this->backdrop_path = $backdrop_path;
	}

	/**
	 * @return mixed
	 */
	public function getBelongsToCollection()
	{
		return $this->belongs_to_collection;
	}

	/**
	 * @param mixed $belongs_to_collection
	 */
	public function setBelongsToCollection($belongs_to_collection): void
	{
		$this->belongs_to_collection = $belongs_to_collection;
	}

	/**
	 * @return mixed
	 */
	public function getBudget()
	{
		return $this->budget;
	}

	/**
	 * @param mixed $budget
	 */
	public function setBudget($budget): void
	{
		$this->budget = $budget;
	}

	/**
	 * @return mixed
	 */
	public function getGenres()
	{
		return $this->genres;
	}

	/**
	 * @param mixed $genres
	 */
	public function setGenres($genres): void
	{
		$this->genres = $genres;
	}

	/**
	 * @return mixed
	 */
	public function getHomepage()
	{
		return $this->homepage;
	}

	/**
	 * @param mixed $homepage
	 */
	public function setHomepage($homepage): void
	{
		$this->homepage = $homepage;
	}

	/**
	 * @return mixed
	 */
	public function getMdbId()
	{
		return $this->mdb_id;
	}

	/**
	 * @param mixed $mdb_id
	 */
	public function setMdbId($mdb_id): void
	{
		$this->mdb_id = $mdb_id;
	}

	/**
	 * @return mixed
	 */
	public function getImdbId()
	{
		return $this->imdb_id;
	}

	/**
	 * @param mixed $imdb_id
	 */
	public function setImdbId($imdb_id): void
	{
		$this->imdb_id = $imdb_id;
	}

	/**
	 * @return mixed
	 */
	public function getOriginalLanguage()
	{
		return $this->original_language;
	}

	/**
	 * @param mixed $original_language
	 */
	public function setOriginalLanguage($original_language): void
	{
		$this->original_language = $original_language;
	}

	/**
	 * @return mixed
	 */
	public function getOriginalTitle()
	{
		return $this->original_title;
	}

	/**
	 * @param mixed $original_title
	 */
	public function setOriginalTitle($original_title): void
	{
		$this->original_title = $original_title;
	}

	/**
	 * @return mixed
	 */
	public function getOverview()
	{
		return $this->overview;
	}

	/**
	 * @param mixed $overview
	 */
	public function setOverview($overview): void
	{
		$this->overview = $overview;
	}

	/**
	 * @return mixed
	 */
	public function getPopularity()
	{
		return $this->popularity;
	}

	/**
	 * @param mixed $popularity
	 */
	public function setPopularity($popularity): void
	{
		$this->popularity = $popularity;
	}

	/**
	 * @return mixed
	 */
	public function getPosterPath()
	{
		return $this->poster_path;
	}

	/**
	 * @param mixed $poster_path
	 */
	public function setPosterPath($poster_path): void
	{
		$this->poster_path = $poster_path;
	}

	/**
	 * @return mixed
	 */
	public function getProductionCompanies()
	{
		return $this->production_companies;
	}

	/**
	 * @param mixed $production_companies
	 */
	public function setProductionCompanies($production_companies): void
	{
		$this->production_companies = $production_companies;
	}

	/**
	 * @return mixed
	 */
	public function getProductionCountries()
	{
		return $this->production_countries;
	}

	/**
	 * @param mixed $production_countries
	 */
	public function setProductionCountries($production_countries): void
	{
		$this->production_countries = $production_countries;
	}

	/**
	 * @return mixed
	 */
	public function getReleaseDate()
	{
		return $this->release_date;
	}

	/**
	 * @param mixed $release_date
	 */
	public function setReleaseDate($release_date): void
	{
		$this->release_date = $release_date;
	}

	/**
	 * @return mixed
	 */
	public function getRevenue()
	{
		return $this->revenue;
	}

	/**
	 * @param mixed $revenue
	 */
	public function setRevenue($revenue): void
	{
		$this->revenue = $revenue;
	}

	/**
	 * @return mixed
	 */
	public function getSpokenLanguages()
	{
		return $this->spoken_languages;
	}

	/**
	 * @param mixed $spoken_languages
	 */
	public function setSpokenLanguages($spoken_languages): void
	{
		$this->spoken_languages = $spoken_languages;
	}

	/**
	 * @return mixed
	 */
	public function getStatus()
	{
		return $this->status;
	}

	/**
	 * @param mixed $status
	 */
	public function setStatus($status): void
	{
		$this->status = $status;
	}

	/**
	 * @return mixed
	 */
	public function getTagline()
	{
		return $this->tagline;
	}

	/**
	 * @param mixed $tagline
	 */
	public function setTagline($tagline): void
	{
		$this->tagline = $tagline;
	}

	/**
	 * @return mixed
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @param mixed $title
	 */
	public function setTitle($title): void
	{
		$this->title = $title;
	}

	/**
	 * @return mixed
	 */
	public function getVideo()
	{
		return $this->video;
	}

	/**
	 * @param mixed $video
	 */
	public function setVideo($video): void
	{
		$this->video = $video;
	}

	/**
	 * @return mixed
	 */
	public function getVoteAverage()
	{
		return $this->vote_average;
	}

	/**
	 * @param mixed $vote_average
	 */
	public function setVoteAverage($vote_average): void
	{
		$this->vote_average = $vote_average;
	}

	/**
	 * @return mixed
	 */
	public function getVoteCount()
	{
		return $this->vote_count;
	}

	/**
	 * @param mixed $vote_count
	 */
	public function setVoteCount($vote_count): void
	{
		$this->vote_count = $vote_count;
	}

	/**
	 * @return mixed
	 */
	public function getRuntime()
	{
		return $this->runtime;
	}

	/**
	 * @param mixed $runtime
	 */
	public function setRuntime($runtime): void
	{
		$this->runtime = $runtime;
	}

	/**
	 * @return mixed
	 */
	public function getLanguageVersion()
	{
		return $this->language_version;
	}

	/**
	 * @param mixed $language_version
	 */
	public function setLanguageVersion($language_version)
	{
		$this->language_version = $language_version;
	}


}