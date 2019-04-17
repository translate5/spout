<?php

namespace WilsonGlasser\Spout\Writer\Common\Entity;


use WilsonGlasser\Spout\Common\Entity\Style\Style;
/**
 * Class Cell
 */
class Comment
{

    /**
     * The comment cell
     * @var string
     */
    protected $cell;

    /**
     * The comment
     * @var string
     */
    protected $text;

    /**
     * Comment authors
     * @var string
     */
    protected $author;
    /**
     * Comment author id
     * @var int
     */
    protected $authorId;

    /**
     * The cell style
     * @var Style
     */
    protected $style;

    /**
     * @param $value mixed
     * @param Style|null $style
     */
    public function __construct($cell, $text, $author = null, Style $style = null)
    {
        $this->setCell($cell);
        $this->setText($text);
        $this->setAuthor($author);
        $this->setStyle($style);
    }

    /**
     * @param string
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return string|null
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }
    /**
     * @return string|null
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param string
     */
    public function setAuthorId($authorId)
    {
        $this->authorId = $authorId;
    }
    /**
     * @return string|null
     */
    public function getAuthorId()
    {
        return $this->authorId;
    }

    /**
     * @param string
     */
    public function setCell($cell)
    {
        $this->cell = $cell;
    }
    /**
     * @return string|null
     */
    public function getCell()
    {
        return $this->cell;
    }

    /**
     * @param Style|null $style
     */
    public function setStyle($style)
    {
        $this->style = $style ?: new Style();
    }

    /**
     * @return Style
     */
    public function getStyle()
    {
        return $this->style;
    }
}
