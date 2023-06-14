<?php

namespace Html\Form;

use Entity\Movie;
use Exception\ParameterException;
use Html\StringEscaper;

class MovieForm
{
    use StringEscaper;

    /**
     * @var Movie|null
     */
    private ?Movie $movie;

    /**
     * @return Movie|null
     */
    public function getMovie(): ?Movie
    {
        return $this->movie;
    }

    /**
     * @param Movie|null $movie
     */
    public function __construct(?Movie $movie=null)
    {
        $this->movie = $movie;
    }

    /**
     * Méthode d'instance de MovieForm.
     * Renvoie l'objet sous forme d'un formulaire HTML organisé.
     * Les attributs obligatoires de ce formulaire sont le Titre et la date de sortie.
     *
     * @param string $action
     * @return string
     */
    public function getHtmlForm(string $action): string
    {
        if (isset($this->movie)) {
            $id = self::escapeString($this->movie->getId());
            $title = self::escapeString($this->movie->getTitle());
            $originalLang = self::escapeString($this->movie->getOriginalLanguage());
            $originalTitle = self::escapeString($this->movie->getOriginalTitle());
            $overview = self::escapeString($this->movie->getOverview());
            $releaseD = self::escapeString($this->movie->getReleaseDate());
            $runtime = self::escapeString($this->movie->getRuntime());
            $tagline = self::escapeString($this->movie->getTagline());
        } else {
            $id = null;
            $title = null;
            $originalLang = null;
            $originalTitle = null;
            $overview = null;
            $releaseD = null;
            $runtime = null;
            $tagline = null;
        }
        return <<<HTML
        <form action='$action' method='post'>
            <label>
                <input name="id" type="hidden" value="$id">
            </label>
            <label>
                Titre :
                <input name="title" type="text" value="$title" required>
            </label>
            <label>
                Titre Original :
                <input name="originalTitle" type="text" value="$originalTitle">
            </label>
            <label>
                Langue Originale :
                <input name="originalLanguage" type="text" value="$originalLang">
            </label>
            <label>
                Phrase d'accroche :
                <textarea name="tagline">
                $tagline
                </textarea>
            </label>
            <label>
                 Résumé :
                <textarea name="overview">
                $overview
                </textarea>
            </label>
            <label>
                Date de Sortie du film :
                <input name="releaseDate" type="date" value="$releaseD" required>
            </label>
            <label>
                Durée du film en minute :
                <input name="runtime" type="number" value="$runtime">
            </label>
            <button type="submit">Enregistrer</button>
        </form>
HTML;
    }

    public function setEntityFromQueryString(): void
    {
        if (!(isset($_POST['id'])) || $_POST['id'] === '' || !(ctype_digit($_POST['id']))) {
            $idPost = null;
        } else {
            $idPost = intval($_POST['id']);
        }

        if (!(isset($_POST['title'])) || $_POST['title'] === '') {
            throw new ParameterException();
        } else {
            $titlePost = $_POST['title'];
        }

        if (!(isset($_POST['originalTitle'])) || $_POST['originalTitle'] === '') {
            $originalTitlePost = $_POST['title'];
        } else {
            $originalTitlePost = $_POST['originalTitle'];
        }

        if (!(isset($_POST['originalLanguage'])) || $_POST['originalLanguage'] === '') {
            $originalLangPost = '';
        } else {
            $originalLangPost = $_POST['originalLanguage'];
        }

        if (!(isset($_POST['tagline'])) || $_POST['tagline'] === '') {
            $taglinePost = '';
        } else {
            $taglinePost = $_POST['tagline'];
        }

        if (!(isset($_POST['overview'])) || $_POST['overview'] === '') {
            $overviewPost = '';
        } else {
            $overviewPost = $_POST['overview'];
        }

        if (!(isset($_POST['releaseDate'])) || $_POST['releaseDate'] === '') {
            $releaseDatePost = date("Y-m-d");
        } else {
            $releaseDatePost = $_POST['releaseDate'];
        }

        if (!(isset($_POST['runtime'])) || $_POST['runtime'] === '') {
            $runtimePost = 0;
        } else {
            $runtimePost = $_POST['runtime'];
        }
        $movie = Movie::create($titlePost, $originalLangPost, $originalTitlePost, $overviewPost, $releaseDatePost, $taglinePost, $idPost, $runtimePost);
        $this->movie = $movie;
    }

}
