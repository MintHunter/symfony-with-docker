<?php

namespace App\Entity;

use App\Repository\GenresRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GenresRepository::class)
 */
class Genres
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $genre_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $genre_name;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $lang;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGenreId(): ?string
    {
        return $this->genre_id;
    }

    public function setGenreId(string $genre_id): self
    {
        $this->genre_id = $genre_id;

        return $this;
    }

    public function getGenreName(): ?string
    {
        return $this->genre_name;
    }

    public function setGenreName(string $genre_name): self
    {
        $this->genre_name = $genre_name;

        return $this;
    }

	/**
	 * @return mixed
	 */
	public function getLang()
	{
		return $this->lang;
	}

	/**
	 * @param mixed $lang
	 */
	public function setLang($lang)
	{
		$this->lang = $lang;
	}
}
