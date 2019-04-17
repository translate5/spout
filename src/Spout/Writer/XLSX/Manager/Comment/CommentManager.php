<?php

namespace WilsonGlasser\Spout\Writer\XLSX\Manager\Comment;

use WilsonGlasser\Spout\Common\Helper\Escaper;
use WilsonGlasser\Spout\Common\Entity\Style\Style;
use WilsonGlasser\Spout\Writer\Common\Entity\Worksheet;

/**
 * Class CommentManager
 * Manages comments to be applied to a cell
 */
class CommentManager
{

    /** @var Escaper\XLSX Strings escaper */
    protected $stringsEscaper;

    private $tmpAuthors = [];

    /**
     * @param Escaper\XLSX $stringsEscaper Strings escaper
     */
    public function __construct( $stringsEscaper)
    {
        $this->stringsEscaper = $stringsEscaper;
    }

    /**
     * Returns the content of the "styles.xml" file, given a list of styles.
     * @param Worksheet $worksheet
     * @return string
     */
    public function getCommentsXMLFileContent($worksheet)
    {
        $content = <<<'EOD'
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<comments xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">
EOD;

        $this->tmpAuthors = [];

        $content .= $this->getAuthorsSectionContent($worksheet);
        $content .= $this->getCommentsListSectionContent($worksheet);

        $content .= <<<'EOD'
</comments>
EOD;

        return $content;
    }
    /**
     * @param Worksheet $worksheet
     * @return string
     */
    public function getAuthorsSectionContent($worksheet) {
        $content = '';

        $authors = [];

        $registeredComments = $worksheet->getExternalSheet()->getComments();
        foreach($registeredComments as $comment) {
            if ($comment->getAuthor() !== null) {
                $authors[$comment->getAuthor()] = $comment->getAuthor();
            }
        }

        if (count($authors)) {
            $content .= '<authors>'.PHP_EOL;
            foreach($authors as $author) {
                $content .= '<author>'.$this->stringsEscaper->escape($author).'</author>'.PHP_EOL;
            }
            $content .= '</authors>';
        }

        $this->tmpAuthors = array_values($authors);

        return $content;
    }

    /**
     * @param Worksheet $worksheet
     * @return string
     */
    public function getCommentsListSectionContent($worksheet) {
        $registeredComments = $worksheet->getExternalSheet()->getComments();

        $content = '<commentList>'.PHP_EOL;
        foreach($registeredComments as $comment) {
            if ($comment->getAuthor() !== null) {
                $authorId = array_search($comment->getAuthor(), $this->tmpAuthors);
                $comment->setAuthorId($authorId);
            }
            $content .= '<comment ref="'.$comment->getCell().'"'.($comment->getAuthorId() !== null ? ' authorId="'.$comment->getAuthorId().'"':'').'>';

            if (!empty($comment->getText()) && $comment->getText() !== null ) {
                $content .= '<text><r>';
                // apply styles!
                $fontSize = $comment->getStyle()->getFontSize() > 0 ? $comment->getStyle()->getFontSize() : Style::DEFAULT_FONT_SIZE;
                $fontFamily = !empty($comment->getStyle()->getFontName()) && $comment->getStyle()->getFontName() !== null ? $comment->getStyle()->getFontName() : Style::DEFAULT_FONT_NAME;
                $content .= '<rPr><sz val="'.$fontSize.'"/><rFont val="'.$fontFamily.'"/><charset val="0"/></rPr>';

                $content .= '<t>'.$this->stringsEscaper->escape($comment->getText()).'</t></r></text>';
            }

            $content .= '</comment>'.PHP_EOL;
        }

        $content .= '</commentList>';

        return $content;
    }
}
